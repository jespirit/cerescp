<?php

session_start();
include_once 'config.php'; // loads config variables
include_once 'query.php'; // imports queries
include_once 'functions.php';
include_once 'mail.php';

if (!$CONFIG_password_recover || ($CONFIG_password_recover && $CONFIG_md5_pass))
	redir('motd.php', 'main_div', "Disabled");

if (!empty($_SESSION[$CONFIG_name.'account_id'])) {
	if ($_SESSION[$CONFIG_name.'account_id'] > 0) {

        if (!empty($GET_opt)) {
            if ($GET_opt == 1 && isset($GET_frm_name) && !strcmp($GET_frm_name, 'recover')) {
                $session = $_SESSION[$CONFIG_name.'sessioncode'];
                if ($CONFIG_auth_image && function_exists('gd_info')
                    && strtoupper($GET_code) != substr(strtoupper(md5('Mytext'.$session['recover_ingame'])), 0, 6))
                    alert($lang['INCORRECT_CODE']);
                
                $stmt = prepare_query(RECOVER_INGAME_PASSWORD, 0, 's', $GET_username);
                $result = execute_query($stmt);

                if ($result->num_rows > 0) {
                    $line = $result->fetch_array();
                    $answer = recover_password($GET_username, $line['user_pass'], $line['email']);

                    redir('motd.php', 'main_div', $answer);
                }
                else
                    alert($lang['PASSWORD_UNKNOWN_ACCOUNT']);
            }
        }

        if (isset($_SESSION[$CONFIG_name.'sessioncode']))
            $session = $_SESSION[$CONFIG_name.'sessioncode'];
        $session['recover_ingame'] = rand(12345, 99999);
        $_SESSION[$CONFIG_name.'sessioncode'] = $session;
        $var = rand(10, 9999999);

        caption($lang['PASSWORD_RECOVERY']);
        echo '
        <form id="recover" onsubmit="return GET_ajax(\'recover_ingame.php\',\'main_div\',\'recover\')">
        <table class="maintable">
            <tr>
                <td align="right">'.$lang['USERNAME'].':</td>
                <td align="left"><input type="text" name="username" maxlength="23" size="23" onKeyPress="return force(this.name,this.form.id,event);"></td>
            </tr>';

        if ($CONFIG_auth_image && function_exists('gd_info')) {
            echo '
            <tr>
                <td></td>
                <td align=left><img src="img.php?img=recover_ingame&var='.$var.'" alt="'.$lang['SECURITY_CODE'].'"></td>
            </tr>
            <tr>
                <td align=right>'.$lang['CODE'].':</td>
                <td align="left"><input type="text" name="code" maxlength="6" size="6" onKeyPress="return force(this.name,this.form.id,event);"></td>
            </tr>';
        }

        echo '
            <tr>
                <td>&nbsp;</td>
                <td><input type="submit" value="'.$lang['RECOVER'].'"></td>
            </tr>
        </table>
        <input type="hidden" name="opt" value="1">
        </form>';
        
        fim();
    }/*account_id>0*/
}/*!empty(account_id)*/
?>
