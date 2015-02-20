<?php

session_start();
include_once 'config.php'; // loads config variables
include_once 'query.php'; // imports queries
include_once 'functions.php';

if (!empty($_SESSION[$CONFIG_name.'account_id'])) {  // logged in
	if ($_SESSION[$CONFIG_name.'account_id'] > 0) {
	
        // read all ingame accounts associated with the forum account
        $stmt = prepare_query(VIEW_GET_ACCOUNT_ALL, 0, 'i', $_SESSION[$CONFIG_name.'account_id']);
        $result = execute_query($stmt, 'changeslot.php');
        
        $account_id = 2000045; // login.account_id;
        // 2000045
        // $account_id is binded to the statement so that you can run the same query multiple
        // times with a different account id
        $stmt = prepare_query_ex("SELECT * FROM `char` WHERE `char`.`account_id` = ?", 0, 'i', array(&$account_id));
        
        $index = 0;
        $running_count = 0;

        while ($line = $result->fetch_row()) {
            $account_id = $line[1];
            // echo "\$account_id = $account_id <br>";
            echo '
            <div>
                <h1>'.$line[0].'</h1>
                <span style="color:#60c; cursor:pointer; font-weight:bold;" 
                      onclick="toggleMenu(\'account'.$index.'\')">+ Details</span>
                <table id=\'account'.$index.'\' style="display:none;">
                    <tr>
                        <th>Char ID</th>
                        <th>Name</th>
                        <th>Slot</th>
                    </th>
            ';

            $char_result = execute_query($stmt, 'changeslot.php');
            while ($char = $char_result->fetch_array()) {  // char info
                $running_count++;
                echo '
                    <tr>
                        <td>'.$char['char_id'].'</td>
                        <td>'.$char['name'].'</td>
                        <td>'.$char['char_num'].'</td>
                    </tr>
                ';
            }
            
            echo '
                </table>
            </div>';
            
            $index++;
        }
        
        echo "Running: $running_count";
        
        echo '
        <script type="text/javascript">
        function toggleMenu(objID) {
            if (!document.getElementById) return;
            var style = document.getElementById(objID).style;
            style.display = (style.display == "block")?"none":"block";
        }
        </script>
        ';
    }
    
}

fim();
?>