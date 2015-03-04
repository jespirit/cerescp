<?php

session_start();
include_once 'config.php'; // loads config variables
include_once 'query.php'; // imports queries
include_once 'functions.php';

if (!empty($_SESSION[$CONFIG_name.'account_id'])) {  // logged in
	if ($_SESSION[$CONFIG_name.'account_id'] > 0) {
	
        if (!empty($GET_opt)) {
            if ($GET_opt == 1) {
                if (is_online($GET_accountid)) 
                    alert($lang['NEED_TO_LOGOUT_F']);

                // Unequip any items
                $stmt = prepare_query(REMOVE_GEAR, 0, 'i', $GET_charid);
                $result = execute_query($stmt, "removegear.php");
                
                // Remove any client oriented visual styles
                $stmt = prepare_query(REMOVE_GEAR_CHAR, 0 ,'i', $GET_charid);
                $result = execute_query($stmt, "removegear.php");
                
                redir("removegear.php", "main_div", 'Equipment removed!');
            }
        }
        
        // read all ingame accounts associated with the forum account
        $stmt = prepare_query(VIEW_GET_ACCOUNT_ALL, 0, 'i', $_SESSION[$CONFIG_name.'account_id']);
        $result = execute_query($stmt, 'removegear.php');
        
        $account_id = 0; // login.account_id;
        // $account_id is binded to the statement so that you can run the same query multiple
        // times with a different account id
        $stmt = prepare_query_ex("SELECT * FROM `char` WHERE `char`.`account_id` = ?", 0, 'i', array(&$account_id));
        
        $index = 0;

        while ($line = $result->fetch_row()) {
            $account_id = $line[1];
            echo '
            <div>
                <h1>'.$line[0].'</h1>
                <span style="color:#60c; cursor:pointer; font-weight:bold;" 
                      onClick="toggleMenu(\'account'.$index.'\')">+ Details</span>
                <table id=\'account'.$index.'\' style="display:none;">
                    <tr>
                        <th>Char ID</th>
                        <th>Name</th>
                        <th>Slot</th>
                    </th>
            ';

            $char_result = execute_query($stmt, 'changeslot.php');
            while ($char = $char_result->fetch_array()) {  // char info
                echo '
                    <tr>
                        <td>'.$char['char_id'].'</td>
                        <td>'.$char['name'].'</td>
                        <td>'.$char['char_num'].'</td>
                        <td>
                            <form id="removegear'.$index.'" onsubmit="return GET_ajax(\'removegear.php\',\'main_div\',\'removegear'.$index.'\')">
                                <input type="submit" value="'.$lang['REMOVE_GEAR'].'" onclick="return window.confirm(\'Remove Gear?\');">
                                <input type="hidden" name="charnum" value="'.$char['char_num'].'">
                                <input type="hidden" name="charid" value="'.$char['char_id'].'">
                                <input type="hidden" name="accountid" value="'.$account_id.'">
                                <input type="hidden" name="opt" value="1">
                            </form>
                        </td>
                    </tr>
                ';
            }
            
            echo '
                </table>
            </div>';
            
            $index++;
        }
        
        echo '
        <script>
        function toggleMenu(objID) {
            var style = document.getElementById(objID).style;
            style.display = (style.display == "block")?"none":"block";
        }
        </script>
        ';
    }
    
}

fim();
?>