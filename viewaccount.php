<?php

session_start();
include_once 'config.php'; // loads config variables
include_once 'query.php'; // imports queries
include_once 'adminquery.php';
include_once 'functions.php';

if (!empty($_SESSION[$CONFIG_name.'account_id'])) {
	if ($_SESSION[$CONFIG_name.'account_id'] > 0) {

        // read all ingame accounts associated with the forum account
        $stmt = prepare_query(VIEW_GET_ACCOUNT_ALL, 0, 'i', $_SESSION[$CONFIG_name.'account_id']);
        $result = execute_query($stmt, 'viewaccount.php');

        echo '
        <table class="maintable">
            <tr>
                <th align="left">Account Name</th>
                <th align="left">Last Login</th>
                <th align="left">Last IP</th>
                <th align="left">Gender</th>
                <th>&nbsp</th>
            </tr>
            ';

        while ($line = $result->fetch_row()) {

            echo '
            <tr>
                <td align="left">'.$line[0].'</td>
                <td align="left">'.$line[2].'</td>
                <td align="left">'.$line[3].'</td>
                <td align="left">'.($line[4]=='M'?'Male':'Female').'</td>
                <td>
                    <span title="Edit Password" class="link"
                          onClick="return LINK_ajax(\'password.php?userid='. $line[0] . '&account_id='. $line[1] . 
                          '\', \'main_div\');">Edit Password</span>
                </td>
            </tr>
            ';
            // The email is already part of the account's information
            // <td>
                // <span title="Change Email" class="link"
                      // onClick="return LINK_ajax(\'changemail.php?userid=' . $line[0] . '&account_id='. $line[1] . 
                      // '\', \'main_div\');">Change Email</span>
            // </td>
        }
        echo '</table>';

        echo '<span title="Add account" class="link" onClick="return LINK_ajax(\'addaccount.php\',\'main_div\');">Add Account</span>';

        fim();
    }
}
?>
