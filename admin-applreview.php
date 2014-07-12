<?php

session_start();
include_once 'config.php'; // loads config variables
include_once 'query.php'; // imports queries
include_once 'adminquery.php';
include_once 'functions.php';

if (!isset($_SESSION[$CONFIG_name.'level']) || $_SESSION[$CONFIG_name.'level'] < $CONFIG['cp_admin'])
	die ('Not Authorized');

if (isset($GET_frm_name) && isset($GET_id)) {
	if (notnumber($GET_id))
		alert($lang['INCORRECT_CHARACTER']);

	$stmt = prepare_query(GET_APPLICATION, 0, 'i', trim($GET_id));
	$result = execute_query($stmt, 'admin-applreview.php');
	if ($line = $result->fetch_row()) {
		$stmt = prepare_query(ACCEPT_APPLICATION, 0, 'i', trim($GET_id));
		$result = execute_query($stmt, 'admin-applreview.php');
		
		$stmt = prepare_query(REMOVE_APPLICATION, 0, 'i', trim($GET_id));
		$result = execute_query($stmt, 'admin-applreview.php');

		// Send email
		alert('Application Accpeted');
	}
}

caption('Application Review');
if (isset($GET_back)) {
	$back = base64_decode($GET_back);
	echo '<center><span title="Back" class="link" onClick="return LINK_ajax(\'admin-application.php?'.$back.'\',\'main_div\');">&lt;-back</span></center>';
}

if (isset($GET_id)) {
	$stmt = prepare_query(GET_APPLICATION, 0, 'i', trim($GET_id));
	$result = execute_query($stmt, 'admin-applreview.php');
	if ($line = $result->fetch_row()) {
		echo '
		<form id="appreview" onSubmit="return GET_ajax(\'admin-applreview.php\',\'main_div\',\'appreview\');">
			<table class="maintable">
				<tr>
					<td align="right">Account_id</td><td align="left">'.$line[0].'<input type="hidden" name="id" value="'.$line[0].'"></td>
				</tr><tr>
					<td align="right">'.$lang['USERNAME'].'</td><td align="left"><input type="input" name="login" value="'.htmlformat($line[1]).'" maxlength="23" size="23"></td>
				</tr><tr>
					<td align="right">'.$lang['IP_ADDRESS'].'</td><td align="left"><input type="input" name="ipaddress" value="'.htmlformat($line[2]).'" maxlength="32" size="23"></td>
				</tr><tr>
					<td align="right">'.$lang['MAIL'].'</td><td align="left"><input type="input" name="email" value="'.htmlformat($line[3]).'" maxlength="60" size="23"></td>
				</tr><tr>
					<td align="right">'.$lang['ABOUT_ME'].'</td><td align="left"><textarea name="aboutme" rows="10" cols="50" >'.$line[4].'</textarea></td>
				</tr><tr>
					<td>&nbsp;</td><td align="left"><input type="submit" value="Accept">
				</tr>
			</table>
		</form>
		';
	}

} else echo 'Not Found';

fim();
?>