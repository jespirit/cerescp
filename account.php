<?php
/*
Ceres Control Panel

This is a control pannel program for Athena and Freya
Copyright (C) 2005 by Beowulf and Nightroad

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

To contact any of the authors about special permissions send
an e-mail to cerescp@gmail.com
*/

session_start();
include_once 'config.php'; // loads config variables
include_once 'query.php'; // imports queries
include_once 'functions.php';

if ($CONFIG_disable_account || check_ban())
	redir('motd.php', 'main_div', 'Disabled');

if (isset($POST_opt)) {
	if ($POST_opt == 1 && isset($POST_frm_name) && !strcmp($POST_frm_name, 'account')) {
		$session = $_SESSION[$CONFIG_name.'sessioncode'];
		if ($CONFIG_auth_image && function_exists('gd_info')
			&& strtoupper($POST_code) != substr(strtoupper(md5('Mytext'.$session['account'])), 0,6))
			alert($lang['INCORRECT_CODE']);

		if (inject($POST_username) || inject($POST_password) || inject($POST_email))
			alert($lang['INCORRECT_CHARACTER']);

		if (strlen(trim($POST_username)) < 4 || strlen(trim($POST_username)) > 23)
			alert($lang['USERNAME_LENGTH']);

		if ($CONFIG_safe_pass && (strlen(trim($POST_password)) < 6 || strlen(trim($POST_password)) > 32))
			alert($lang['PASSWORD_LENGTH']);

		if (strlen(trim($POST_password)) < 4 || strlen(trim($POST_password)) > 32)
			alert($lang['PASSWORD_LENGTH_OLD']);

		if (!strcmp($POST_password, $POST_username)) // username must not be same as password
			alert($lang['PASSWORD_REJECTED']);

		if (strcmp($POST_password, $POST_confirm))
			alert($lang['PASSWORD_NOT_MATCH']);

		if ($CONFIG_safe_pass && thepass(trim($POST_password)))
			alert($lang['PASSWORD_REJECTED']);

		if (strlen($POST_email) < 7 || !strstr($POST_email, '@') || !strstr($POST_email, '.'))
			alert($lang['EMAIL_NEEDED']);
			
		if (strlen($POST_birthdate) < 8 || notnumber($POST_birthdate))
			alert($lang['INVALID_BIRTHDAY']);

		if (strlen(trim($POST_aboutme)) < 1)
			alert($lang['ABOUT_NO_TEXT']);

		if (!$POST_agree) {
			echo '<script type="text/javascript">alert("You must check the \'I have read the warning ...\' checkbox\'");</script>';
			redir('motd.php', 'main_div', 'You did not wish to register an account');
		}

		// Check if the Username exists in either the `web_account` table.
		$stmt = prepare_query(CHECK_USERID, 0, 's', trim($POST_username));
		$result = execute_query($stmt, 'account.php');
		
		$checkuser = 0;
		$checkuser += $result->num_rows;
		
        if ($checkuser == 0) {
            // Check if the Username exists in either the `accregister` table.
            $stmt = prepare_query(CHECK_USERID2, 0, 's', trim($POST_username));
            $result = execute_query($stmt, 'account.php');
		
            $checkuser += $result->num_rows;
        }

		if ($checkuser > 0)
			alert($lang['USERNAME_IN_USE']);

		if ($CONFIG_md5_pass)
			$POST_password = md5($POST_password);

		$level = 1;  // Level 1 by default

		// date fields can bind to 's'
		$stmt = prepare_query(NEW_APPLICATION, 0, 'sssisss',
            trim($POST_username), trim($POST_password),
			trim($POST_email), $level, $POST_birthdate, $_SERVER['REMOTE_ADDR'], trim($POST_aboutme));
		$result = execute_query($stmt, 'account.php', 1);
        if ($result) {
            redir('motd.php', 'main_div',
            'Thanks for applying to TestRO.<br/><br/>Your application is being considered and
            you will receive an email whether your application has been accepted.');
        } else
            trigger_error("Failed to insert new application into account table");

	}
}

if (isset($_SESSION[$CONFIG_name.'sessioncode']))
	$session = $_SESSION[$CONFIG_name.'sessioncode'];
$session['account'] = rand(12345, 99999);
$_SESSION[$CONFIG_name.'sessioncode'] = $session;
$var = rand(10, 9999999);

	caption($lang['NEW_ACCOUNT']);
	echo '
	<form id="account" onSubmit="return POST_ajax(\'account.php\',\'main_div\',\'account\');">
	<table class="maintable">
		<tr>
			<td align="right">'.$lang['USERNAME'].':</td>
			<td align="left"><input type="text" name="username" maxlength="23" size="23" onKeyPress="return force(this.name,this.form.id,event);"></td>
	</tr>
	<tr>
		<td align="right">'.$lang['PASSWORD'].':</td>
		<td align="left"><input type=password name="password" maxlength="32" size="32" onKeyPress="return force(this.name,this.form.id,event);"></td>
	</tr>
	<tr>
		<td align="right">'.$lang['CONFIRM'].':</td>
		<td align="left"><input type=password name="confirm" maxlength="32" size="32" onKeyPress="return force(this.name,this.form.id,event);"></td>
	</tr>
	<tr>
		<td align="right">'.$lang['MAIL'].':</td>
		<td align="left"><input type="text" name="email" maxlength="40" size="40" onKeyPress="return force(this.name,this.form.id,event);"></td>
	</tr>
	<tr>
		<td align="right">'.$lang['BIRTHDAY'].':</td>
		<td align="left">
			<input type="text" name="birthdate" maxlength="8" size="8" onKeyPress="return force(this.name,this.form.id,event);">
		</td>
	</tr>
	<tr>
		<td align="right">'.$lang['ABOUT_ME'].':</td>
		<td align="left">
			<textarea name="aboutme" maxlength="250" rows="10" cols="50"></textarea>
		</td>
	</tr>';
	if ($CONFIG_auth_image && function_exists("gd_info")) { 
		echo '
	<tr>
		<td></td>
		<td align=left><img src="img.php?img=account&var='.$var.'" alt="'.$lang['SECURITY_CODE'].'"></td>
	</tr>
	<tr>
		<td align=right>'.$lang['CODE'].':</td>
		<td align="left"><input type="text" name="code" maxlength="6" size="6" onKeyPress="return force(this.name,this.form.id,event);"></td>
	</tr>';
	}

	echo '
	<tr>
		<td>&nbsp;</td>
		<td><h1>Warning</h1><span id="warning-register">Register an account at your own risk. The site does NOT use SSL or any other type of encryption for server-client communication.
		That means all the data sent from your browser will be unencrypted, easily visible in plain text for anybody with the right tools to extract.<br /><br />
		
		In addition, passwords are not encrypted in the database either, that means the System Administrator will know exactly what the passwords are.<br /><br />
		
		If you choose to make an account, I suggest you NOT use any of your passwords that you use for your email account, bank account, or any other
		account with confidential and/or sensitive information.</span></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="checkbox" name="agree"><span id="warning-chk">I have read the warning and I still wish to register for an account.</span></td>
    </tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" id="register" name="register" value="'.$lang['REGISTER'].'"></td>
	</tr>
	</table>
	<input type="hidden" name="opt" value="1">
	</form>';

?>
