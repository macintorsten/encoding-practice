// Override window.alert to detect when user xss is successful
(function(proxied) {
    window.alert = function() {

      // When set, alert has been called hence successful XSS
      window.XSS = true;

      // If in iframe, postmessage to parent to indicate successful xss - remove?
      if (self!=top) {
        window.parent.postMessage(location.href, "*");
        return;
      } 

      // otherwise just show success banner 
      ret = proxied.apply(this, arguments);
      $('#success').show();
      return ret;
  };
})(window.alert);

// Detect when automatic xss probe is successful
function probe(url) {
  return new Promise(function(resolve, reject) {
    function iframeLoadCallback(event) {
      let is_xss = this.contentWindow.XSS || false;
      let xss_result = {vector: url, xss: is_xss};
      resolve(xss_result);
    }
    iframe = document.createElement('iframe');
    iframe.src = url;
    iframe.style = "display: none;";
    iframe.addEventListener("load", iframeLoadCallback);
    document.body.appendChild(iframe);
  })
}

// Probe mutliple payloads
function test_payloads(payloads) {
  return payloads.map(async function(url) {
    console.log("Testing " + url);
    result_prom = probe(url);
    console.log(result_prom);
    return result_prom;
  });
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
    let checks = test_payloads(payloads);
    checks.forEach(async function(result) {
      let xss_result = await result;
      addXSSResult(xss_result);
    });
  })

async function addXSSResult(result) {
  li = document.createElement('li');

  if (result.xss === true) {
      li.className = "list-group-item list-group-item-danger glyphicon glyphicon-exclamation-sign";
  }
  else {
      li.className = "list-group-item list-group-item-success glyphicon glyphicon-ok-circle";
  }
  link = document.createElement('a');
  link.href = result.vector;
  link.innerText = " " + result.vector;
  li.appendChild(link)
  log = document.getElementById('check_log');
  log.appendChild(li);
  }
}