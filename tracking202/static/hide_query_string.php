<?php
if (isset($tracker_row['hide_query_string']) &&
	$tracker_row['hide_query_string']) {
?>
(function () {
  var l = document.location + '';
  var p = l.indexOf('?');

  if (p !== -1) {
    createCookie('tracking202ignore', '1');

    var e, v, form = document.createElement('form');
    form.style.display = 'none';
<?php
	foreach (array('t202id', 'keyword', 'c1', 'c2', 'c3', 'c4') as $name)  {
		$cookie_name = "tracking202$name";
		$value = json_encode(isset($_GET[$name]) ? $_GET[$name] : '');
		echo <<<EOF
    e = document.createElement('input');
    v = $value; e.name = "$name"; e.value = v;
    form.appendChild(e);
    createCookie("$cookie_name", v);

EOF;
    }
?>
    document.body.appendChild(form);
    form.method = 'POST';
    form.action = l.substr(0, p);
    form.submit();
  }
}());
<?php
}
?>