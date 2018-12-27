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

function XSSCallback(event) {
  console.log("XSS: " + event.data);
}

function XSSCheck(log) {
  return (function() {
    li = document.createElement('li');
    if (this.contentWindow.XSS === true) {
      li.className = "list-group-item list-group-item-danger glyphicon glyphicon-exclamation-sign";
    }
    else {
      li.className = "list-group-item list-group-item-success glyphicon glyphicon-ok-circle";
    }
    link = document.createElement('a');
    link.href = this.src;
    link.innerText = " " + this.src;
    li.appendChild(link)
    log.appendChild(li);
    document.body.removeChild(this);
  })
}

// Detect when automatic xss probe is successful
function probe_single(url, log) {
  iframe = document.createElement('iframe');
  iframe.src = url;
  iframe.style = "display: none;";
  iframe.addEventListener("load", XSSCheck(log));
  document.body.appendChild(iframe);
}

// Probe mutliple payloads
function probe_multi(payloads, log) {
  //window.addEventListener("message", XSSCallback, false);

  payloads.forEach(element => {
    console.log("Testing " + element);
    probe_single(element, log);
  });

}

// Get payloads
function try_payloads(payloads_file, log_id) {
  log = document.getElementById(log_id);
  var xhr = new XMLHttpRequest();
  xhr.open("GET", payloads_file, true);
  xhr.onload = function (e) {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        var payloads = xhr.responseText.split('\n');
        probe_multi(payloads, log);
      } else {
        console.error(xhr.statusText);
      }
    }
  };
  xhr.onerror = function (e) {
    console.error(xhr.statusText);
  };
  xhr.send(null);
}