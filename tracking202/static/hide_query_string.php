<?php
if (isset($tracker_row['hide_query_string']) &&
	$tracker_row['hide_query_string']) {
	print <<<EOD
(function () {
  var l = document.location + '';
  var p = l.indexOf('?');

  if (p !== -1) {
    createCookie('tracking202ignore', '1');
    document.location = l.substr(0, p);
  }
}());

EOD;
}
?>