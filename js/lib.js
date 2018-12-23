// Override window.alert to detect when xSS is successful
(function(proxied) {
    window.alert = function() {
    var ret = proxied.apply(this, arguments);
    $('#success').show();
    return ret;
  };
})(window.alert);


