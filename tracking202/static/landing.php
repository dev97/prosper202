<? header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
header('Expires: Sun, 03 Feb 2008 05:00:00 GMT'); // Date in the past
header("Pragma: no-cache");
header('Content-type: application/javascript');
if (array_key_exists('HTTPS', $_SERVER) && $_SERVER['HTTPS'] == 'on') {
	$strProtocol = 'https';
} else {
	$strProtocol =  'http';
}
?>
function t202Init(){
	//this grabs the t202kw, but if they set a forced kw, this will be replaced 
	
	if (readCookie('t202forcedkw')) {
		var t202kw = readCookie('t202forcedkw');
	} else {
		var t202kw = t202GetVar('t202kw');
	}

	var lpip = '<? echo htmlentities($_GET['lpip']); ?>';
	var t202id = t202GetVarOrCookie('t202id');
	var OVRAW = t202GetVar('OVRAW');
	var OVKEY = t202GetVar('OVKEY');
	var OVMTC = t202GetVar('OVMTC');
<?php if (array_key_exists('c1', $_GET)): ?>
	var c1 = <?= json_encode($_GET['c1']); ?>;
<?php else: ?>
	var c1 = t202GetVarOrCookie('c1');
<?php endif; ?>
<?php if (array_key_exists('c2', $_GET)): ?>
	var c2 = <?= json_encode($_GET['c2']); ?>;
<?php else: ?>
	var c2 = t202GetVarOrCookie('c2');
<?php endif; ?>
<?php if (array_key_exists('c3', $_GET)): ?>
	var c3 = <?= json_encode($_GET['c3']); ?>;
<?php else: ?>
	var c3 = t202GetVarOrCookie('c3');
<?php endif; ?>
<?php if (array_key_exists('c4', $_GET)): ?>
	var c4 = <?= json_encode($_GET['c4']); ?>;
<?php else: ?>
	var c4 = t202GetVarOrCookie('c4');
<?php endif; ?>
	var target_passthrough = t202GetVar('target_passthrough');
	var keyword = t202GetVarOrCookie('keyword');
	var referer = document.referrer;
	var resolution = screen.width+'x'+screen.height;
	var language = navigator.appName=='Netscape'?navigator.language:navigator.browserLanguage; 
	language = language.substr(0,2); 
										    
	document.write("<script src=\"<?php echo $strProtocol; ?>://<? echo $_SERVER['SERVER_NAME']; ?>/tracking202/static/record.php?lpip=" + t202Enc(lpip)
		+ "&t202id="				+ t202Enc(t202id)
		+ "&t202kw="				+ t202kw
		+ "&OVRAW="					+ t202Enc(OVRAW)
		+ "&OVKEY="					+ t202Enc(OVKEY)
		+ "&OVMTC="					+ t202Enc(OVMTC)
		+ "&c1="					+ t202Enc(c1)
		+ "&c2="					+ t202Enc(c2)
		+ "&c3="					+ t202Enc(c3)
		+ "&c4="					+ t202Enc(c4)
		+ "&target_passthrough="	+ t202Enc(target_passthrough)
		+ "&keyword="				+ t202Enc(keyword)
		+ "&referer="   			+ t202Enc(referer)
		+ "&resolution="			+ t202Enc(resolution)
		+ "&language="				+ t202Enc(language)
		+ "\" type=\"text/javascript\" ></script>"
	);
<?php if (isset($_GET['p'])): ?>
	document.write('<!--');
<?php endif; ?>
		
}

function  t202Enc(e){
	return encodeURIComponent(e);

}

function  t202GetVar(name){
	get_string = document.location.search;         
	 return_value = '';
	 
	 do { 
		name_index = get_string.indexOf(name + '=');
		 
		if(name_index != -1) {
			get_string = get_string.substr(name_index + name.length + 1, get_string.length - name_index);
		  
			end_of_value = get_string.indexOf('&');
			if(end_of_value != -1) {                
				value = get_string.substr(0, end_of_value);                
			} else {                
				value = get_string;                
			}

			value = decodeURIComponent(value.replace(/\+/g, '%20'));
			
			if(return_value == '' || value == '') {
				return_value += value;
			} else {
				return_value += ', ' + value;
			}
		  }
		} 
		
		while(name_index != -1)
		
	 return(return_value);

}

function t202GetVarOrCookie(name) {
    var v = t202GetVar(name), c;

    if (v === '' && (c = readCookie('tracking202' + name)) !== null) {
        return c;
    }

    return v;
}

function createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = encodeURIComponent(name)+"="+
		encodeURIComponent(value)+expires+"; path=/";

}

function readCookie(name) {
	var nameEQ = encodeURIComponent(name) + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0)
			return decodeURIComponent(c.substring(nameEQ.length,c.length));
	}
	return null;

}

function eraseCookie(name) {
	createCookie(name,"",-1);
}

if (readCookie('tracking202ignore')) {
	eraseCookie('tracking202ignore');
} else {
	t202Init();
}