<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/202-config/connect.php'); 

AUTH::require_user();
	
//set the timezone for this user.
	AUTH::set_timezone($_SESSION['user_timezone']);
	
//grab the users date range preferences
	$time = grab_timeframe(); 
	$mysql['to'] = mysql_real_escape_string($time['to']);
	$mysql['from'] = mysql_real_escape_string($time['from']); 
	
	
//show real or filtered clicks
	$mysql['user_id'] = mysql_real_escape_string($_SESSION['user_id']);
	$user_sql = "SELECT * FROM 202_users_pref WHERE user_id=".$mysql['user_id'];
	$user_result = _mysql_query($user_sql, $dbGlobalLink); //($user_sql);
	$user_row = mysql_fetch_assoc($user_result);	
	
	$html['user_pref_group_1'] = htmlentities($user_row['user_pref_group_1'], ENT_QUOTES, 'UTF-8');
	$html['user_pref_group_2'] = htmlentities($user_row['user_pref_group_2'], ENT_QUOTES, 'UTF-8');
	$html['user_pref_group_3'] = htmlentities($user_row['user_pref_group_3'], ENT_QUOTES, 'UTF-8');
	$html['user_pref_group_4'] = htmlentities($user_row['user_pref_group_4'], ENT_QUOTES, 'UTF-8');
	
	if ($user_row['user_cpc_or_cpv'] == 'cpv') {
		$cpv = true;
	} else {
		$cpv = false;
	}
	
	$summary_form = new ReportSummaryForm();
	$summary_form->setDetails(array($user_row['user_pref_group_1'],$user_row['user_pref_group_2'],$user_row['user_pref_group_3'],$user_row['user_pref_group_4']));
	$sort_trans = array(
		'name'   => ReportBasicForm::SORT_NAME,
		'click'  => ReportBasicForm::SORT_CLICK,
		'lead'   => ReportBasicForm::SORT_LEAD,
		'su'     => ReportBasicForm::SORT_SU,
		'payout' => ReportBasicForm::SORT_PAYOUT,
		'epc'    => ReportBasicForm::SORT_EPC,
		'cpc'    => ReportBasicForm::SORT_CPC,
		'income' => ReportBasicForm::SORT_INCOME,
		'cost'   => ReportBasicForm::SORT_COST,
		'net'    => ReportBasicForm::SORT_NET,
		'roi'    => ReportBasicForm::SORT_ROI,
		'ctr'    => ReportBasicForm::SORT_CTR
	);
	if (array_key_exists('order', $_POST) && array_key_exists($_POST['order'], $sort_trans)) {
		$sort_key = $sort_trans[$_POST['order']];
	} else {
		$sort_key = ReportBasicForm::SORT_CLICK;
	}
	$summary_form->setDetailsSort(array_fill(0, 4, $sort_key));
	$summary_form->setDisplayType(array(ReportBasicForm::DISPLAY_TYPE_TABLE));
	$summary_form->setStartTime($mysql['from']);
	$summary_form->setEndTime($mysql['to']);
	$summary_form->setPrecision($cpv ? 4 : 2);
	
?>

<h3 class="green overview-spacer">Group Overview</h3>
<div>
	<img src="/202-img/icons/16x16/page_white_excel.png" style="margin: 0px 0px -3px 3px;"/>
	<a target="_new" href="/tracking202/overview/group_overview_download.php">
		<strong>Download to excel</strong>
	</a>
</div>
<?

$mysql['user_id'] = mysql_real_escape_string($_SESSION['user_id']);

$info_result = _mysql_query($summary_form->getQuery($mysql['user_id'],$user_row));

while ($row = mysql_fetch_assoc($info_result)) {
	$summary_form->addReportData($row);
}

echo $summary_form->getHtmlReportResults('summary report');
?>
