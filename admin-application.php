<?php

session_start();
include_once 'config.php'; // loads config variables
include_once 'query.php'; // imports queries
include_once 'adminquery.php';
include_once 'functions.php';

if (!isset($_SESSION[$CONFIG_name.'level']) || $_SESSION[$CONFIG_name.'level'] < $CONFIG['cp_admin'])
	die ('Not Authorized');
	
caption('Applications');

if (!isset($GET_page))
	$GET_page = 0;
else if (notnumber($GET_page))
	alert($lang['INCORRECT_CHARACTER']);

$stmt = prepare_query(TOTAL_APPLICATIONS);
$result = execute_query($stmt, 'admin-application.php');
$row = $result->fetch_row();
$pages = (int)(($row[0]-1) / 100);

$offset = $GET_page * 100;  // 100 per page
$back = 'page='.$GET_page;
$back = base64_encode($back);

$stmt = prepare_query(BROWSE_APPLICATIONS, 0, 'i', $offset);
$result = execute_query($stmt, 'admin-application.php');

echo '
<table id="application" class="maintable">
	<tr>
		<th>Id</th>
		<th>Time</th>
		<th>'.$lang['IP_ADDRESS'].'</th>
		<th>'.$lang['USERNAME'].'</th>
		<th>'.$lang['SEX'].'</th>
		<th>'.$lang['MAIL'].'</th>
		<th>'.$lang['LEVEL'].'</th>
		<th>'.$lang['BIRTHDAY'].'</th>
	</tr>';
	
while ($line = $result->fetch_row()) {
	echo '<tr>
		  <td>'.$line[0].'</td>
		  <td>'.$line[1].'</td>
		  <td>'.$line[2].'</td>
		  <td>'.$line[3].'</td>
		  <td>'.$line[4].'</td>
		  <td>'.$line[5].'</td>
		  <td>'.$line[6].'</td>
		  <td>'.$line[7].'</td>
		  <td align="center">
		  <span title="Review" class="link" onClick="return LINK_ajax(\'admin-applreview.php?id='.$line[0].'&back='.$back.'\',\'main_div\');">Review</span></td>
		  </tr>';
}
echo '</table>';

if ($pages) {
	echo '
	<table class="maintable">
		<tr>
			<td>
				<span title="0" class="link" onClick="return LINK_ajax(\'adminaccounts.php?page=0\',\'main_div\');">&lt;&lt;</span>';

	for ($i = ($GET_page - 10); $i <= ($GET_page + 10); $i++) {
		echo ' ';
		if ($i >= 0 && $i != $GET_page && $i <= $pages)
			echo '<span title="'.$i.'" class="link" onClick="return LINK_ajax(\'adminaccounts.php?page='.$i.'\',\'main_div\');">'.$i.'</span>';
		else if ($i == $GET_page)
			echo '<b>'.$i.'</b>';
	}

	echo '
				<span title="'.$pages.'" class="link" onClick="return LINK_ajax(\'adminaccounts.php?page='.$pages.'\',\'main_div\');">&gt;&gt;</span>
			</td>
		</tr>
	</table>';
}

fim();
?>