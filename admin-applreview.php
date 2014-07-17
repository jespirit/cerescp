<?php

session_start();
include_once 'config.php'; // loads config variables
include_once 'query.php'; // imports queries
include_once 'adminquery.php';
include_once 'functions.php';
include_once 'mail.php';

if (!isset($_SESSION[$CONFIG_name.'level']) || $_SESSION[$CONFIG_name.'level'] < $CONFIG['cp_admin'])
	die ('Not Authorized');

if (isset($GET_frm_name) && isset($GET_id)) {
	if (notnumber($GET_id))
		alert($lang['INCORRECT_CHARACTER']);

	if (strcmp($GET_decide, "accept") != 0 && strcmp($GET_decide, "decline"))
		alert('Invalid option for \'decide\' field');

	$stmt = prepare_query(GET_APPLICATION, 0, 'i', trim($GET_id));
	$result = execute_query($stmt, 'admin-applreview.php');
	
	$state = 0;
	
	if ($line = $result->fetch_row()) {
		if (!strcasecmp($GET_decide, "accept")) {
			var_dump($line);
			$stmt = prepare_query(INSERT_NEWACCOUNT, 0, 'sssssiis', trim($line[3]), trim($line[4]),
				$line[5], $line[6], $line[8], $line[7], $state, $line[2]);
			$result = execute_query($stmt, 'admin-applreview.php');
			
			if ($result) {
				$stmt = prepare_query(REMOVE_APPLICATION, 0, 'i', trim($line[0]));
				$result = execute_query($stmt, 'admin-applreview.php');

				// Send confirmation email
				confirm_account($line[3], $line[6]);
				alert('Application Accepted');
			}
			else
				trigger_error('Error: Could not insert new account');
		}
		else if (!strcmp($GET_decide, "decline")) {
			$stmt = prepare_query(REMOVE_APPLICATION, 0, 'i', trim($line[0]));
			$result = execute_query($stmt, 'admin-applreview.php');

			// Send email
			confirm_account($line[3], $line[6]);
			alert('Application Declined');
		}
		else
			alert('No action, invalid decide value='.$GET_decide);
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
					<td align="right">Id</td><td align="left">'.$line[0].'<input type="hidden" name="id" value="'.$line[0].'"></td>
				</tr><tr>
					<td align="right">Time</td><td align="left"><input type="input" name="time" value="'.htmlformat($line[1]).'" maxlength="23" size="23"></td>
				</tr><tr>
					<td align="right">'.$lang['IP_ADDRESS'].'</td><td align="left"><input type="input" name="ipaddress" value="'.htmlformat($line[2]).'" maxlength="23" size="23"></td>
				</tr><tr>
					<td align="right">'.$lang['USERNAME'].'</td><td align="left"><input type="input" name="login" value="'.htmlformat($line[3]).'" maxlength="23" size="23"></td>
				</tr><tr>
					<td align="right">Sex</td><td align="left"><input type="input" name="sex" value="'.htmlformat($line[5]).'" maxlength="60" size="23"></td>
				</tr><tr>
					<td align="right">'.$lang['MAIL'].'</td><td align="left"><input type="input" name="email" value="'.htmlformat($line[6]).'" maxlength="40" size="40"></td>
				</tr><tr>
					<td align="right">'.$lang['LEVEL'].'</td><td align="left"><input type="input" name="level" value="'.$line[7].'" maxlength="5" size="6"></td>
				</tr><tr>
					<td align="right">'.$lang['BIRTHDAY'].'</td><td align="left"><input type="input" name="birthdate" value="'.htmlformat($line[8]).'" maxlength="8" size="8"></td>
				</tr><tr>
					<td align="right">'.$lang['ABOUT_ME'].'</td><td align="left"><textarea name="aboutme" rows="10" cols="50" >'.$line[9].'</textarea></td>
				</tr><tr>
					<td>&nbsp;</td>
					<td align="left">
						<select name="decide">
						<option selected="selected" value="accept">Accept</option>
						<option value="decline">Decline</option>
						</select>
					</td>
				</tr>
					<td>&nbsp;</td><td align="left"><input type="submit" name="submit" value="Confirm"></td>
				</tr>
			</table>
		</form>
		';
	}

} else echo 'Not Found';

fim();
?>