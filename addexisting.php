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
	$result = execute_query($stmt, 'addexisting.php');
	$maxaccounts = $result->fetch_row();
	if ($maxaccounts[0] >= $CONFIG_max_accounts)
		redir('motd.php', 'main_div', $lang['ACCOUNT_MAX_REACHED']);
}

if (!empty($_SESSION[$CONFIG_name.'account_id'])) {
	if ($_SESSION[$CONFIG_name.'account_id'] > 0) {

        if (isset($POST_opt)) {
            if ($POST_opt == 1 && isset($POST_frm_name) && !strcmp($POST_frm_name, 'addexist')) {
                $session = $_SESSION[$CONFIG_name.'sessioncode'];
                if ($CONFIG_auth_image && function_exists('gd_info')
                    && strtoupper($POST_code) != substr(strtoupper(md5('Mytext'.$session['addexist'])), 0, 6))
                    alert($lang['INCORRECT_CODE']);

                if (strlen(trim($POST_username)) < 4 || strlen(trim($POST_username)) > 23)
                    alert($lang['USERNAME_LENGTH']);
                
                if ($CONFIG_safe_pass && (strlen(trim($POST_password)) < 6 || strlen(trim($POST_password)) > 32))
                    alert($lang['PASSWORD_LENGTH']);

                if (strlen(trim($POST_password)) < 4 || strlen(trim($POST_password)) > 32)
                    alert($lang['PASSWORD_LENGTH_OLD']);
                
                if (strlen($POST_email) < 7 || !strstr($POST_email, '@') || !strstr($POST_email, '.'))
                    alert($lang['EMAIL_NEEDED']);

                // Verify the account information matches the input data
                $stmt = prepare_query(ADD_VERIFY_ACCOUNT, 0, 'sss', trim($POST_username), trim($POST_password), trim($POST_email));
                $result = execute_query($stmt, 'addexisting.php');
                
                if ($result) {
                    if (!$result->num_rows)
                        alert($lang['ADD_EXISTING_NOMATCH']);
                }
                else {
                    alert($lang['ADD_EXISTING_VERIFY_FAILURE']);
                }

                // The account information is valid, so attach the game account to the corresponding
                // forum account.
                $stmt = prepare_query(ADD_EXISTING_ACCOUNT, 0, 'is', $_SESSION[$CONFIG_name.'account_id'], trim($POST_username));
                $result = execute_query($stmt, 'addaccount.php');
                
                if ($result) {
                    redir('viewaccount.php', 'main_div', $lang['ADD_EXISTING_ACCOUNT_SUCCESS']);
                }
                else {
                    alert($lang['ADD_EXISTING_ACCOUNT_FAILURE']);
                }
            }
        }

        if (isset($_SESSION[$CONFIG_name.'sessioncode']))
            $session = $_SESSION[$CONFIG_name.'sessioncode'];
        $session['addexist'] = rand(12345, 99999);
        $_SESSION[$CONFIG_name.'sessioncode'] = $session;
        $var = rand(10, 9999999);

        caption($lang['ADD_EXISTING_ACCOUNT']);
        echo '
        <form id="addexist" onSubmit="return POST_ajax(\'addexisting.php\',\'main_div\',\'addexist\');">
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
                    <td align="right">'.$lang['MAIL'].':</td>
                    <td align="left"><input type="text" name="email" maxlength="40" size="40" onKeyPress="return force(this.name,this.form.id,event);"></td>
                </tr>';
        
        if ($CONFIG_auth_image && function_exists("gd_info")) {
            echo '
                <tr>
                    <td></td>
                    <td align=left><img src="img.php?img=addexist&var='.$var.'" alt="'.$lang['SECURITY_CODE'].'"></td>
                </tr>
                <tr>
                    <td align=right>'.$lang['CODE'].':</td>
                    <td align="left"><input type="text" name="code" maxlength="6" size="6" onKeyPress="return force(this.name,this.form.id,event);"></td>
                </tr>';
        }
        
        echo'
                <tr>
                    <td>&nbsp;</td>
                    <td><input type="submit" id="add" name="add" value="'.$lang['ADD_ACCOUNT'].'"></td>
                </tr>
            </table>
            <input type="hidden" name="opt" value="1">
        </form>';
        
        fim();
    }/*account_id>0*/
}/*!empty(account_id)*/
?>
