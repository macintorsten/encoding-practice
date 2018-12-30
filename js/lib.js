// Override window.alert to detect when user xss is successful
(function(proxied) {
    window.alert = function() {

      // When set, alert has been called hence successful XSS
      window.XSS = true;
      $('#success').show();

      // If in iframe, postmessage to parent to indicate successful xss - remove?
      let win = window.opener || top;
      if (win) {
        // For now assumed that vector is GET-based and calling window is window.opener or top
        let xss_result = {vector: window.location.href, xss: true};
        win.postMessage(xss_result, "*");
        return;
      } 

      // otherwise just show success banner 
      ret = proxied.apply(this, arguments);
      return ret;
  };
})(window.alert);

// Detect when automatic xss probe is successful
function probe(url, timeout=null) {
  var promises = [];
  var iframe;

  var p_xssvar = new Promise(function(resolve, reject) {
    function iframeLoadCallback(event) {
      let is_xss = this.contentWindow.XSS || false;
      let xss_result = {vector: url, xss: is_xss};
      if (is_xss || timeout===null)
        console.log(`XSS detected for url ${url}`);
        resolve(xss_result);
    }
    iframe = document.createElement('iframe');
    iframe.src = url;
    iframe.style = "display: none;";
    iframe.addEventListener("load", iframeLoadCallback);
    document.body.appendChild(iframe);
  });
  
  // If timeout is defined, wait for max timeout ms and use postessage method in addition to window.XSS
  if (timeout) {
    let p_timeout = new Promise((resolve, reject) => {
      let id = setTimeout(() => {
        clearTimeout(id);
        console.log(`Probe for ${url} timed out - no XSS detected`);
        let xss_result = {xss: false, vector: url};
        resolve(xss_result);
      }, timeout)
    });

    let p_postmessage = new Promise ((resolve, reject) => {
      function messageListener(event) {
        if ("xss" in event.data && "vector" in event.data) {
          if (event.source === iframe.contentWindow) {
            let xss_result = {xss: true, vector: url};
            resolve(xss_result);
          }
        }
      }
      window.addEventListener("message", messageListener, false);
    });

    promises.push(p_timeout);
    promises.push(p_postmessage);
  }

  promises.push(p_xssvar);

  // I'm not sure how this works - but it seems to do the job
  function cleanUp() {
    iframe.parentNode.removeChild(iframe);
  }

  return Promise.race(promises).finally(cleanUp);
}

function PerformXSSCheck() {
  // Get payloads
  fetch('payloads.txt')
  .then(function(response) {
    return response.text();
  })
  .then(function(text) {
    return text.split('\n');
  })
  .then(async function(payloads) {

    // Add result to here
    log = document.getElementById('check_log');

    // Clean old result
    while (log.firstChild) {
        log.removeChild(log.firstChild);
    }

    // For each payload, create a pending probe and update when promise resolves
    Array(...payloads.entries()).map(async function(item) {
      let id = item[0];
      let url = item[1];
      console.log("Probing URL " + url);
      pendingProbeElement(id, url);
      result_prom = probe(url, 2500);
      let xss_result = await result_prom;
      updateProbeResult(id, xss_result);
      return result_prom;
    });
  })

}

function pendingProbeElement(id, url) {
  li = document.createElement('li');
  li.id = "probe" + id;

  li.className = "list-group-item list-group-item-warning glyphicon glyphicon-hourglass";

  link = document.createElement('a');
  link.href = url;
  link.innerText = " " + url;

  li.appendChild(link);
  log = document.getElementById('check_log');
  log.appendChild(li);
  return li.id;
}

async function updateProbeResult(id, result) {
  id = "probe" + id;
  li = document.getElementById(id);

  if (result.xss === true) {
      li.className = "list-group-item list-group-item-danger glyphicon glyphicon-thumbs-down";
  }
  else {
      li.className = "list-group-item list-group-item-success glyphicon glyphicon-thumbs-up";
  }
}