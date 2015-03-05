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

if (!empty($_SESSION[$CONFIG_name.'account_id'])) {
	if ($_SESSION[$CONFIG_name.'account_id'] > 0) {

		if (!empty($POST_opt)) {
			if ($POST_opt == 1 && isset($POST_frm_name) && !strcmp($POST_frm_name, "password")) {
				if (strcmp($POST_newpass, $POST_confirm) != 0) 
					alert($lang['PASSWORD_NOT_MATCH']);

				if (inject($POST_login_pass) || inject($POST_newpass)) 
					alert($lang['INCORRECT_CHARACTER']);

				if (strlen($POST_login_pass) < 4 || strlen($POST_login_pass) > 32)
					alert($lang['PASSWORD_LENGTH_OLD']);

				if ($CONFIG_safe_pass && (strlen(trim($POST_newpass)) < 6 || strlen(trim($POST_newpass)) > 32))
					alert($lang['PASSWORD_LENGTH']);

				if (strlen(trim($POST_newpass)) < 4 || strlen(trim($POST_newpass)) > 32)
					alert($lang['PASSWORD_LENGTH_OLD']);

				if ($CONFIG_safe_pass && thepass(trim($POST_newpass)))
					alert($lang['PASSWORD_REJECTED']);

				if ($CONFIG_md5_pass) {
					$POST_login_pass = md5($POST_login_pass);
					$POST_newpass = md5($POST_newpass);
				}

                // userid is passed via viewaccount.php
				$stmt = prepare_query(CHECK_PASSWORD, 0, 'si', trim($POST_login_pass), $POST_account_id);
				$result = execute_query($stmt, 'password.php');

				if (!$result->fetch_row())
					alert($lang['INCORRECT_PASSWORD']);

				$stmt = prepare_query(CHANGE_PASSWORD, 0, 'si', trim($POST_newpass), $POST_account_id);
				$result = execute_query($stmt, 'password.php');

                if ($result) {
                    redir("password.php?userid=$POST_userid&account_id=$POST_account_id", "main_div", $lang['PASSWORD_CHANGED']);
                } else {
                    alert($lang['UNKNOWN_ERROR']);
                }
			}
		}

	caption($lang['CHANGE_PASSWORD']);
		echo '
		<form id="password" onsubmit="return POST_ajax(\'password.php\',\'main_div\',\'password\')">
		<table class="maintable">
            <tr>
                <td align=right>'.$lang['USERNAME'].':</td>
				<td><input type="text" name="userid_txt" value="' . $GET_userid .'" disabled></td>
            </tr>
			<tr>
				<td align=right>'.$lang['PASSWORD'].':</td>
				<td><input type="password" name="login_pass" maxlength="32" size="23" onKeyPress="return force(this.name,this.form.id,event);"></td>
			</tr>
			<tr>
				<td align=right>'.$lang['NEW_PASSWORD'].':</td>
				<td><input type="password" name="newpass" maxlength="32" size="23" onKeyPress="return force(this.name,this.form.id,event);"></td>
			</tr>
			<tr>
				<td align=right>'.$lang['REPEAT_PASSWORD'].':</td>
				<td><input type="password" name="confirm" maxlength=32" size="23" onKeyPress="return force(this.name,this.form.id,event);"></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" value='.$lang['CHANGE'].'></td>
			</tr>
		</table>
		<input type="hidden" name="opt" value="1">
        <input type="hidden" name="userid" value="' . $GET_userid . '">
        <input type="hidden" name="account_id" value="' . $GET_account_id . '">
		</form>
		';
		fim();
	}
}
redir('motd.php', 'main_div', $lang['NEED_TO_LOGIN']);
?>
