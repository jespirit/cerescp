<?php

session_start();
include_once 'config.php'; // loads config variables
include_once 'query.php'; // imports queries
include_once 'adminquery.php';
include_once 'functions.php';

if (isset($GET_userid) && isset($GET_code)) {
    $userid = trim($GET_userid);
    $stmt = prepare_query(ACTIVATE_CHECK_USER, 0, 's', $userid);
	$result = execute_query($stmt, 'activate.php');
    
    if ($line = $result->fetch_array()) {
        // hash time + account_num + userid + user_pass
        $hash = md5($line[1].$line[2].$line[3].$line[4]);
        echo $hash . '<br>';
		if (strcasecmp($GET_code, $hash) == 0) {

            $stmt = prepare_query(ACTIVATE_INSERT_CHAR, 0, 'ssssssiii',
                $line['userid'], $line['user_pass'], $line['sex'], $line['email'],
                $line['birthdate'], $_SERVER['REMOTE_ADDR'],
                0, $line['level'], $line['account_num']);
            $result = execute_query($stmt, 'activate.php');
            
            if ($result) {
                echo '<p>Congratulations. You have successfully activated your account.</p>';
                echo '<p>Username: '. $GET_userid .'</p>';
            
                $stmt = prepare_query(ACTIVATE_REMOVE_CHAR, 0, 'i', $line['id']);
                $result = execute_query($stmt, 'activate.php');
                
                if (!$result) {
                    trigger_error("Failed to remove account with id: " . $line['id']);
                }
            }
            else
				trigger_error('Error: Could not insert new account');
		}
        else
            trigger_error('md5 hash failed to match. Expected: '. $hash);
       
    }
    else
        trigger_error('Error: Userid does not exist');
}
else
    die("Not authorized");

?>