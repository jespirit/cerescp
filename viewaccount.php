<?php

session_start();
include_once 'config.php'; // loads config variables
include_once 'query.php'; // imports queries
include_once 'adminquery.php';
include_once 'functions.php';

if (!empty($_SESSION[$CONFIG_name.'account_id'])) {
	if ($_SESSION[$CONFIG_name.'account_id'] > 0) {

        // read all ingame accounts associated with the forum account
        $stmt = prepare_query(VIEW_GET_ACCOUNT, 0, 'i', $_SESSION[$CONFIG_name.'account_id']);
        $result = execute_query($stmt, 'viewaccount.php');

        echo '
        <table class="maintable">
            <tr>
                <th align="left">Account Name</th>
            </tr>
            ';

        while ($line = $result->fetch_row()) {

            echo '
            <tr>
                <td align="left">'.$line[0].'</td>
            </tr>
            ';
            // <td align="center">
                // <span title="Detailed Info" class="link" onClick="window.open(\'admincharinfo.php?id='.$line[1].'\', \'_blank\', \'height = 600, width = 800, menubar = no, status = no, titlebar = no, scrollbars = yes\');">Detail</span>
            // </td>
        }
        echo '</table>';

        echo '<span title="Add account" class="link" onClick="return LINK_ajax(\'addaccount.php\',\'main_div\');">Add Account</span>';

        fim();
    }
}
?>
