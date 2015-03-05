<?php

session_start();
include_once 'config.php'; // loads config variables
include_once 'query.php'; // imports queries
include_once 'functions.php';
include_once 'mail.php';

if (!empty($_SESSION[$CONFIG_name.'account_id'])) {  // logged in
	if ($_SESSION[$CONFIG_name.'account_id'] > 0) {
	
        // get all pending game account matching the account number and user id
        $stmt = prepare_query(VIEW_GET_ACCOUNT_PENDING, 0, 'is',
            $_SESSION[$CONFIG_name.'account_id'], $GET_userid);
        $result = execute_query($stmt, 'resend_link.php');
        
        if ($result) {
            $line = $result->fetch_row();
            $hash = md5($line[0] . $line[1] . $line[2] . $line[3]);
            if ($CONFIG_local_machine)
                $link = "http://localhost/cerescp-svn/activate.php?userid=".$line[2]."&code=".$hash;
            else
                $link = "http://54.187.100.97/activate.php?userid=".$line[2]."&code=".$hash;
            
            send_activation($line[2], $_SESSION[$CONFIG_name.'email'], $link);
            
            redir('motd.php', 'main_div',
            'A confirmation email has been sent to your email inbox with instructions
            on how to verify that the account belongs to you.');
        }
        else {
            alert($lang['UNKNOWN_ERROR']);
        }
    }
    
}

fim();
?>