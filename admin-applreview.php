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

	if (strcmp($GET_decide, "accept") != 0 && strcmp($GET_decide, "decline") != 0)
		alert('Invalid option for \'decide\' field');

	$stmt = prepare_query(GET_APPLICATION, 0, 'i', trim($GET_id));
	$result = execute_query($stmt, 'admin-applreview.php');
	
	$state = 0;
    $remove_appl = false;
	
	if ($line = $result->fetch_array()) {
		if (!strcasecmp($GET_decide, "accept")) {
			$stmt = prepare_query(INSERT_NEW_APPLICANT, 0, 'sssiiss',
                $line['account_name'], $line['account_pass'],
				$line['email'], $line['level'], $state,
                $line['birthdate'], $line['ip']);
			$result = execute_query($stmt, 'admin-applreview.php');
			
			if ($result) {
                $remove_appl = true;
                
				// Send confirmation email
				confirm_account($line['account_name'], $line['email']);
				echo '<script type="text/javascript">alert("Application Accepted");</script>';
			}
			else
				trigger_error('Error: Could not insert new account');
		}
		else if (!strcmp($GET_decide, "decline")) {
            $remove_appl = true;
            
			// Send denied email
			deny_account($line['account_name'], $line['email']);
			echo '<script type="text/javascript">alert("Application Declined");</script>';
		}
		else
			alert('No action, invalid decide value='.$GET_decide);
            
        // Remove the application
        if ($remove_appl) {
            $stmt = prepare_query(REMOVE_APPLICATION, 0, 'i', $line['id']);
            $result = execute_query($stmt, 'admin-applreview.php');
            
            if (!$result)
                trigger_error('Error: Could not remove application');
        }
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
	if ($line = $result->fetch_array()) {
		echo '
		<form id="appreview" onSubmit="return GET_ajax(\'admin-applreview.php\',\'main_div\',\'appreview\');">
			<table class="maintable">
				<tr>
					<td align="right">Id</td><td align="left">'.$line['id'].'<input type="hidden" name="id" value="'.$line['id'].'"></td>
				</tr><tr>
					<td align="right">Time</td><td align="left"><input type="input" name="time" value="'.htmlformat($line['time']).'" maxlength="23" size="23"></td>
				</tr><tr>
					<td align="right">'.$lang['IP_ADDRESS'].'</td><td align="left"><input type="input" name="ipaddress" value="'.htmlformat($line['ip']).'" maxlength="23" size="23"></td>
				</tr><tr>
					<td align="right">'.$lang['USERNAME'].'</td><td align="left"><input type="input" name="login" value="'.htmlformat($line['account_name']).'" maxlength="23" size="23"></td>
				</tr><tr>
					<td align="right">'.$lang['MAIL'].'</td><td align="left"><input type="input" name="email" value="'.htmlformat($line['email']).'" maxlength="40" size="40"></td>
				</tr><tr>
					<td align="right">'.$lang['LEVEL'].'</td><td align="left"><input type="input" name="level" value="'.$line['level'].'" maxlength="5" size="6"></td>
				</tr><tr>
					<td align="right">'.$lang['BIRTHDAY'].'</td><td align="left"><input type="input" name="birthdate" value="'.htmlformat($line['birthdate']).'" maxlength="8" size="8"></td>
				</tr><tr>
					<td align="right">'.$lang['ABOUT_ME'].'</td><td align="left"><textarea name="aboutme" rows="10" cols="50" >'.$line['data'].'</textarea></td>
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