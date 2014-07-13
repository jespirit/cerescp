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

if ($CONFIG_max_accounts) {
	$stmt = prepare_query(MAX_ACCOUNTS);
	$result = execute_query($stmt, 'account.php');
	$maxaccounts = $result->fetch_row();
	if ($maxaccounts[0] >= $CONFIG_max_accounts)
		redir('motd.php', 'main_div', $lang['ACCOUNT_MAX_REACHED']);
}

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

		if ($CONFIG_safe_pass && (strlen(trim($POST_password)) < 6 || strlen(trim($POST_password)) > 23))
			alert($lang['PASSWORD_LENGTH']);

		if (strlen(trim($POST_password)) < 4 || strlen(trim($POST_password)) > 23)
			alert($lang['PASSWORD_LENGTH_OLD']);

		if (!strcmp($POST_password, $POST_username)) // passwords e username iguais
			alert($lang['PASSWORD_REJECTED']);

		if (strcmp($POST_password, $POST_confirm))
			alert($lang['PASSWORD_NOT_MATCH']);

		if ($CONFIG_safe_pass && thepass(trim($POST_password)))
			alert($lang['PASSWORD_REJECTED']);

		if (strlen($POST_email) < 7 || !strstr($POST_email, '@') || !strstr($POST_email, '.'))
			alert($lang['EMAIL_NEEDED']);
			
		if (strlen($POST_birthdate) < 8 || inject($POST_birthdate))
			alert($lang['INVALID_BIRTHDAY']);
			
		if (strlen($POST_aboutme) < 1)
			alert($lang['ABOUT_NO_TEXT']);

		$stmt = prepare_query(CHECK_USERID, 0, 's', trim($POST_username));
		$result = execute_query($stmt, 'account.php');

		if ($result->num_rows)
			alert($lang['USERNAME_IN_USE']);

		if ($POST_sex) 
			$POST_sex = 'F';
		else
			$POST_sex = 'M';

		if ($CONFIG_md5_pass)
			$POST_password = md5($POST_password);

		$state = 5;
		$level = 1;

		// date fields can bind to 's'
		$stmt = prepare_query(INSERT_CHAR, 0, 'ssssssii', trim($POST_username), trim($POST_password), $POST_sex, $POST_email, $POST_birthdate, $_SERVER['REMOTE_ADDR'], $state, $level);
		$result = execute_query($stmt, 'account.php');

		$stmt = prepare_query(CHECK_ACCOUNTID, 0, 'ss', trim($POST_username), trim($POST_password));
		$result = execute_query($stmt, 'account.php');
		
		if ($line = $result->fetch_row()) {
			$stmt = prepare_query(NEW_APPLICATION, 0, 'iss', $line[0], $_SERVER['REMOTE_ADDR'], $POST_aboutme);
			$result = execute_query($stmt, 'account.php');

			erro_de_login(1);
			redir('motd.php', 'main_div', $lang['ACCOUNT_CREATED']);
		} else {
			erro_de_login(1);
			redir('motd.php', 'main_div', $lang['ACCOUNT_PROBLEM']);
		}

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
		<td align="left"><input type=password name="password" maxlength="23" size="23" onKeyPress="return force(this.name,this.form.id,event);"></td>
	</tr>
	<tr>
		<td align="right">'.$lang['CONFIRM'].':</td>
		<td align="left"><input type=password name="confirm" maxlength="23" size="23" onKeyPress="return force(this.name,this.form.id,event);"></td>
	</tr>
	<tr>
		<td align="right">'.$lang['SEX'].':</td>
		<td align="left">
			<select name="sex" onKeyPress="return force(this.name,this.form.id,event);">
				<option value="0">'.$lang['SEX_MALE'].'
				<option value="1">'.$lang['SEX_FEMALE'].'
			</select>
		</td>
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
			<textarea name="aboutme" maxlength="500" rows="10" cols="50" onKeyPress="return force(this.name,this.form.id,event);">
			</textarea>
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
		<td><input type="submit" name="create" value="'.$lang['CREATE'].'"></td>
	</tr>
	</table>
	<input type="hidden" name="opt" value="1">
	</form>';

?>
