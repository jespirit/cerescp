<?php

session_start();
include_once 'config.php'; // loads config variables
include_once 'query.php'; // imports queries
include_once 'functions.php';
include_once 'mail.php';

if (!empty($_SESSION[$CONFIG_name.'account_id'])) {  // logged in
	if ($_SESSION[$CONFIG_name.'account_id'] > 0) {
	
        $now = date("Y-m-d H:i:s");
        $newpass = substr(md5($now), 0, 8);
    
        // get all pending game account matching the account number and user id
        $stmt = prepare_query(RESET_PASSWORD, 0, 'ss', $newpass, $GET_userid);
        $result = execute_query($stmt, 'resetpassword.php');
        
        if ($result) {
            // Send email with new password
            reset_password($GET_userid, $newpass, $_SESSION[$CONFIG_name.'email']);
            
            redir('motd.php', 'main_div',
            'An email has been sent to your inbox with the new password details.');
        }
        else {
            alert($lang['UNKNOWN_ERROR']);
        }
    }
    
}

fim();
?>