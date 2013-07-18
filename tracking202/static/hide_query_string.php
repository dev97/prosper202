<?php
if (isset($tracker_row['hide_query_string']) &&
	$tracker_row['hide_query_string']) {
?>
(function () {
  var l = document.location + '';
  var p = l.indexOf('?');

  if (p !== -1) {
    createCookie('tracking202ignore', '1');
    createCookie('tracking202keyword', <?= json_encode($keyword) ?>);
    createCookie('tracking202c1', <?= json_encode($c1) ?>);
    createCookie('tracking202c2', <?= json_encode($c2) ?>);
    createCookie('tracking202c3', <?= json_encode($c3) ?>);
    createCookie('tracking202c4', <?= json_encode($c4) ?>);

    document.location = l.substr(0, p);
  }
}());
<?php
}
?>