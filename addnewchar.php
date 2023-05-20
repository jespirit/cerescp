<?php

session_start();
include_once 'config.php'; // loads config variables
include_once 'query.php'; // imports queries
include_once 'functions.php';

function istrans($job_id) {
    if ($job_id >= 4008 && $job_id <= 4021)
        return true;
    else
        return false;
}

// convert job id to array-based index
function jobid2idx($job_id) {
    if ($job_id >= 23 && $job_id <= 25)
        return $job_id - 23;
    else if ($job_id >= 4008 && $job_id <= 4021)
        return $job_id - 4008 + 3;
    else if ($job_id == 4047)
        return 4021 - 4008 + 3;
    else if ($job_id == 4049)
        return 4012 - 4008 + 3 + 1;
    else
        trigger_error("jobid2idx: invalid job id");
}

$classes = array(
    23   => "Super Novice",
    24   => "Gunslinger",
    25   => "Ninja",
    4008 => "Lord Knight",
    4009 => "High Priest",
    4010 => "High Wizard",
    4011 => "Whitesmith",
    4012 => "Sniper",
    4013 => "Assassin Cross",
    4015 => "Paladin",
    4016 => "Champion",
    4017 => "Professor",
    4018 => "Stalker",
    4019 => "Creator",
    4020 => "Clown",
    4021 => "Gypsy",
    4047 => "Star Gladiator",
    4049 => "Linker"
);

//$hpfactor = array(0, 88, 80, 150, 75, 55, 90, 85, 110, 110, 90, 75, 85, 90, 75, 75, 90, 75);
$hpmultiplicator = array(500, 0, 0, 500, 500, 500, 500, 500, 500, 700, 650, 500, 500, 500, 300, 300, 650, 500);
$spfactor = array(100, 450, 515, 300, 800, 900, 400, 400, 400, 470, 470, 700, 500, 400, 600, 600, 470, 900);

$str = $agi = $vit = $int = $dex = $luk = 5;
$max_hp = $hp = 0;
$max_sp = $sp = 0;
$status_point = 0;
$skill_point = 0;
$hair = 1;
$hair_color = 0;
$start_zeny = 100000000;  // 100m
$start_map = "prontera";
$start_x = 156;
$start_y = 191;

if (!empty($_SESSION[$CONFIG_name.'account_id'])) {  // logged in
	if ($_SESSION[$CONFIG_name.'account_id'] > 0) {
        
        if (!empty($GET_opt)) {
            if ($GET_opt == 1) {
                
                // check if the name already exists in either the login or register table

                $stmt = prepare_query(ADDCHAR_CHECKNAME, 0, 's', trim($GET_char_name));
                $result = execute_query($stmt, 'addnewchar.php');
                
                if ($result)
                    $checkuser = $result->num_rows;
                else
                    alert('FAILED TO CHECK DUPLICATE NAME');
                
                if ($checkuser > 0)
                    alert($lang['USERNAME_IN_USE']);
                
                // slot must be within range
                
                if ($GET_slot < 0 || $GET_slot > 9)
                    alert('OUT OF RANGE SLOT');
                
                // check if slot is already taken
                
                $stmt = prepare_query(ADDCHAR_CHECKSLOT, 0, 'ii', $GET_accountid, $GET_slot);
                $result = execute_query($stmt, 'addnewchar.php');
                
                if ($result && $result->num_rows > 0)
                    alert('SLOT ALREADY IN USE');
                
                // get the gender of the account
                
                $stmt = prepare_query(ADDCHAR_GENDER, 0, 'i', $GET_accountid);
                $result = execute_query($stmt, 'addnewchar.php');
                
                $gender = '';
                $val = 0;
                $job_idx = jobid2idx($GET_roclass);
                
                if ($result && $result->num_rows > 0)
                    $gender = $result->fetch_row()[0];
                else
                    alert('BAD GENDER');
                
                // validate the input class
                
                if (!array_key_exists($GET_roclass, $classes))
                    alert('INVALID CLASS');
                
                // check that only a male account can be a clown and ditto for gypsys
                
                if ($gender == "M" && $GET_roclass == 4021)
                    alert('CANNOT BE A GYPSY');
                else if ($gender == "F" && $GET_roclass == 4020)
                    alert('CANNNOT BE A CLOWN');
                
                // calculate maxhp
                $val = 35 + (int)($hpmultiplicator[$job_idx]/100);
                
                if ($GET_roclass == 24 || $GET_roclass == 25)
                    $val += 100; //Double hp for Gunslingers and Ninjas
                
                $val += (int)($val * $vit/100); // +1% per each point of VIT
                
                if (istrans($GET_roclass))
                    $val += (int)($val * 25/100); //Trans classes get a 25% hp bonus
                
                $max_hp = $hp = $val;
                
                // calculate maxsp
                
                $val = 10 + (int)($spfactor[$job_idx]/100);
                
                $val += (int)($val * $int/100);
                
                if (istrans($GET_roclass))
                    $val += (int)($val * 25/100);
                
                $max_sp = $sp = $val;
                
                // determine number of status points and skills points
                
                if (istrans($GET_roclass))
                    $status_point = 100;
                
                if ($GET_roclass == 23)
                    $skill_point = 9;
                else
                    $skill_point = 49; //For Trans
                
                // create the new character
                
                // there's a total of 25 parameters
                $stmt = prepare_query(ADDCHAR_INSERT, 0, 'iisiiiiiiiiiiiiiiiisiisii',
                    $GET_accountid, $GET_slot, trim($GET_char_name), $GET_roclass, $start_zeny,
                    $str, $agi, $vit, $int, $dex, $luk,
                    $max_hp, $hp, $max_sp, $sp,
                    $status_point, $skill_point,
                    $hair, $hair_color,
                    $start_map, $start_x, $start_y, $start_map, $start_x, $start_y);
                $result = execute_query($stmt, 'addnewchar.php');
                
                if (!$result)
                    alert('FAILED TO INSERT NEW CHARACTER');
                
                // insert the gears/items based on the battle type and specific class
                
                redir("addnewchar.php", "main_div", 'Successfully added a new character!');
            }
        }

        // sort by value but maintain key=>value relationship
        uasort($classes, 'strcmp');
        
        // read all ingame accounts associated with the forum account
        $stmt = prepare_query(VIEW_GET_ACCOUNT_ALL, 0, 'i', $_SESSION[$CONFIG_name.'account_id']);
        $result = execute_query($stmt, 'addnewchar.php');
        
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
                      onClick="toggleMenu(\'newchar'.$index.'\')">+ Details</span>
                <div id="newchar'.$index.'" style="display:none;">
                <table>
                    <tr>
                        <th>Char ID</th>
                        <th>Name</th>
                        <th>Slot</th>
                    </th>
            ';
            
            $char_count = 0;
            $char_slots = array();
            for ($i=0; $i<9; $i++) {
                $char_slots[] = 1;  // 1=available
            }

            $char_result = execute_query($stmt, 'changeslot.php');
            while ($char = $char_result->fetch_array()) {  // char info
                $char_slots[$char['char_num']] = 0;  // slot taken
                echo '
                    <tr>
                        <td>'.$char['char_id'].'</td>
                        <td>'.$char['name'].'</td>
                        <td>'.($char['char_num']+1).'</td>
                        </td>
                    </tr>
                ';
            }
            
            echo '
                </table>';
                
            echo '
                    <div>
                        <form id="addnewchar'.$index.'" onsubmit="return GET_ajax(\'addnewchar.php\',\'main_div\',\'addnewchar'.$index.'\')">
                        <table>
                            <tr>
                                <th>Name</th>
                                <th>Slot</th>
                                <th>Class</th>
                                <th>Battle Type</th>
                                <th>&nbsp;</th>
                            </tr>
                            <tr>
                                <td><input type="text" name="char_name" maxlength="23" size="23" onKeyPress="return force(this.name,this.form.id,event);"></td>
                                <td>';
                                
                                echo '
                                    <select name="slot">';
                                for ($i=0; $i<9; $i++) {
                                    if ($char_slots[$i]) {  // slot available
                                        echo "<option value=$i>".($i+1)."</option>";
                                    }
                                }
            echo                    '</select>
                                <td>';
                                
                                echo '
                                    <select name="roclass">';
                                foreach ($classes as $k => $v) {
                                    if ($line[4] == "M" && $k == 4021)  // must be female for gypsy
                                        continue;
                                    else if ($line[4] == "F" && $k == 4020)  // must be male for clown
                                        continue;
                                    echo "<option value=$k>$v</option>";
                                }
                            
            echo                    '</select>
                                <td>
                                    <input type="radio" name="battle_type" value="None" checked> None
                                    <input type="radio" name="battle_type" value="Vanilla"> Vanilla
                                    <input type="radio" name="battle_type" value="Normal"> Normal
                                </td>
                                <td>
                                    <input type="submit" value="'.$lang['CREATE_NEW_CHAR'].'" onclick="return window.confirm(\'Create New Char?\');">
                                    <input type="hidden" name="accountid" value="'.$account_id.'">
                                    <input type="hidden" name="opt" value="1">
                                </td>
                            </tr>
                        </table>
                        </form>
                    </div>
                </div>
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