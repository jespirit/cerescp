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
        $hash = md5($line[0].$line[1].$line[2].$line[3].$line[4]);
        echo $hash . '<br>';
		if (strcasecmp($GET_code, $hash) == 0) {
            echo '<p>Congratulations. You have successfully activated your account.</p>';
            echo '<p>Username: '. $GET_userid .'</p>';
            
            $stmt = prepare_query(ACTIVATE_INSERT_CHAR, 0, 'ssssssiii',
                $line['userid'], $line['user_pass'], $line['sex'], $line['email'],
                $line['birthdate'], $_SERVER['REMOTE_ADDR'],
                0, $line['level'], $line['account_num']);
            $result = execute_query($stmt, 'activate.php');
            
            if (!$result) {
                echo 'Yikes';
            }
        
			/* var_dump($line);
			$stmt = prepare_query(INSERT_NEWACCOUNT, 0, 'sssssiis', trim($line[3]), trim($line[4]),
				$line[5], $line[6], $line[8], $line[7], $state, $line[2]);
			$result = execute_query($stmt, 'admin-applreview.php');
			
			if ($result) {
				$stmt = prepare_query(REMOVE_APPLICATION, 0, 'i', trim($line[0]));
				$result = execute_query($stmt, 'admin-applreview.php');

				// Send confirmation email
				confirm_account($line[3], $line[6]);
				alert('Application Accepted');
			}
			else
				trigger_error('Error: Could not insert new account'); */
		}
        else
            trigger_error('md5 hash failed to match. Expected: '. $hash);
       
    }
    else
        trigger_error('Error: Userid does not exist');
}

?>