<?php

function geoip_available() {
	$functions = array('geoip_record_by_name', 'geoip_region_by_name',
			   'geoip_country_code_by_name',
			   'geoip_country_name_by_name');

	foreach ($functions as $function) {
		if (!function_exists($function)) {
			return FALSE;
		}
	}
	return TRUE;
}

function add_geoip_data($ip, &$row) {
	$row['location_country_code'] = NULL;
	$row['location_country_name'] = NULL;
	$row['location_region_code'] = NULL;
	$row['location_city_name'] = NULL;

	$record = @geoip_record_by_name($ip);

	if ($record) {
		$row['location_country_code'] = $record['country_code'];
		$row['location_country_name'] = $record['country_name'];
		$row['location_region_code'] = $record['region'];
		$row['location_city_name'] = $record['city'];
		return TRUE;
	}

	$record = @geoip_region_by_name($ip);

	if ($record) {
		$country_name = @geoip_country_name_by_name($ip);
		$row['location_country_code'] = $record['country_code'];
		$row['location_country_name'] = $country_name;
		$row['location_region_code'] = $record['region'];
		return TRUE;
	}

	$country_code = @geoip_country_code_by_name($ip);

	if ($country_code) {
		$country_name = @geoip_country_name_by_name($ip);
		$row['location_country_code'] = $country_code;
		$row['location_country_name'] = $country_name;
		return TRUE;
	}

	return FALSE;
}

?>