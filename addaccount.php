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
include_once 'mail.php';

if ($CONFIG_disable_account || check_ban())
	redir('motd.php', 'main_div', 'Disabled');

if ($CONFIG_max_accounts) {
	$stmt = prepare_query(MAX_ACCOUNTS);
	$result = execute_query($stmt, 'account.php');
	$maxaccounts = $result->fetch_row();
	if ($maxaccounts[0] >= $CONFIG_max_accounts)
		redir('motd.php', 'main_div', $lang['ACCOUNT_MAX_REACHED']);
}

if (!empty($_SESSION[$CONFIG_name.'account_id'])) {
	if ($_SESSION[$CONFIG_name.'account_id'] > 0) {

        if (isset($POST_opt)) {
            if ($POST_opt == 1 && isset($POST_frm_name) && !strcmp($POST_frm_name, 'account')) {

                if (inject($POST_username) || inject($POST_password))
                    alert($lang['INCORRECT_CHARACTER']);

                if (strlen(trim($POST_username)) < 4 || strlen(trim($POST_username)) > 23)
                    alert($lang['USERNAME_LENGTH']);

                if ($CONFIG_safe_pass && (strlen(trim($POST_password)) < 6 || strlen(trim($POST_password)) > 32))
                    alert($lang['PASSWORD_LENGTH']);

                if (strlen(trim($POST_password)) < 4 || strlen(trim($POST_password)) > 32)
                    alert($lang['PASSWORD_LENGTH_OLD']);

                if (!strcmp($POST_password, $POST_username)) //account name and password cannot be the same
                    alert($lang['PASSWORD_REJECTED']);

                if (strcmp($POST_password, $POST_confirm))  //check if the passwords match
                    alert($lang['PASSWORD_NOT_MATCH']);

                if ($CONFIG_safe_pass && thepass(trim($POST_password)))
                    alert($lang['PASSWORD_REJECTED']);
                    
                if (strlen($POST_birthdate) < 8 || notnumber($POST_birthdate))
                    alert($lang['INVALID_BIRTHDAY']);

                // Check if the Username exists in the `login` table
                $stmt = prepare_query(ADD_CHECK_USERID, 0, 's', trim($POST_username));
                $result = execute_query($stmt, 'addaccount.php');
                
                $checkuser = 0;
                $checkuser += $result->num_rows;
                
                if ($checkuser == 0) {
                    // Check if the Username exists in the `register` table
                    $stmt = prepare_query(ADD_CHECK_USERID2, 0, 's', trim($POST_username));
                    $result = execute_query($stmt, 'addaccount.php');
                    
                    $checkuser += $result->num_rows;
                }

                if ($checkuser > 0)
                    alert($lang['USERNAME_IN_USE']);

                if ($POST_sex)
                    $POST_sex = 'F';
                else
                    $POST_sex = 'M';

                if ($CONFIG_md5_pass)
                    $POST_password = md5($POST_password);

                $level = 1;  // Level 1 by default
                $userid = trim($POST_username);
                $user_pass = trim($POST_password);
                $account_num = $_SESSION[$CONFIG_name.'account_id'];
                $email = $_SESSION[$CONFIG_name.'email'];
                $now = date("Y-m-d H:i:s");

                // date fields can bind to 's'
                $stmt = prepare_query(NEW_ACCOUNT, 0, 'sissssiss',
                    $now,
                    $account_num, $userid, $user_pass,
                    $POST_sex, $email, $level, $POST_birthdate, $_SERVER['REMOTE_ADDR']);
                $result = execute_query($stmt, 'addaccount.php');
                
                if ($result) {
                    $hash = md5($now . $account_num . $userid . $user_pass);
                    //$link = "http://54.187.100.97/activate.php?userid=".$userid."&code=".$hash;
                    $link = "http://localhost/cerescp-svn/activate.php?userid=".$userid."&code=".$hash;
                    
                    // Send confirmation link to account's email address
                    send_activation($userid, $email, $link);

                    redir('motd.php', 'main_div',
                    'A confirmation email has been sent to your email inbox with instructions
                    on how to verify that the account belongs to you.');
                }
                 else {
                    alert($lang['UNKNOWN_ERROR']);
                }

            }
        }

        caption($lang['NEW_ACCOUNT']);
        echo '
        <form id="account" onSubmit="return POST_ajax(\'addaccount.php\',\'main_div\',\'account\');">
        <table class="maintable">
            <tr>
                <td align="right">'.$lang['USERNAME'].':</td>
                <td align="left"><input type="text" name="username" maxlength="23" size="23" onKeyPress="return force(this.name,this.form.id,event);"></td>
        </tr>
        <tr>
            <td align="right">'.$lang['PASSWORD'].':</td>
            <td align="left"><input type=password name="password" maxlength="32" size="23" onKeyPress="return force(this.name,this.form.id,event);"></td>
        </tr>
        <tr>
            <td align="right">'.$lang['CONFIRM'].':</td>
            <td align="left"><input type=password name="confirm" maxlength="32" size="32" onKeyPress="return force(this.name,this.form.id,event);"></td>
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
            <td align="right">'.$lang['BIRTHDAY'].':</td>
            <td align="left">
                <input type="text" name="birthdate" maxlength="8" size="8" onKeyPress="return force(this.name,this.form.id,event);">
            </td>
        </tr>';

        echo '
        <tr>
            <td>&nbsp;</td>
            <td><input type="submit" id="add" name="add" value="'.$lang['ADD_ACCOUNT'].'"></td>
        </tr>
        </table>
        <input type="hidden" name="opt" value="1">
        </form>';
        
    }/*account_id>0*/
}/*!empty(account_id)*/

?>
