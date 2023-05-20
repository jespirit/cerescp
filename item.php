<?php

session_start();
include_once 'config.php'; // loads config variables
include_once 'query.php'; // imports queries
include_once 'functions.php';

if (!isset($GET_id))
	return;
	
$desc_file = "idnum2itemdesctable.txt";
$name_file = "idnum2itemdisplaynametable.txt";

$DESC = file_get_contents($desc_file);
$NAME = file_get_contents($name_file);

$desc = "";
$matches = array();
$desc_re = "/$GET_id#(.*?)\b[a-zA-Z]+\s*:/s";

$item_types = array(
    "0"=>"Healing",
    "2"=>"Usable",
    "3"=>"ETC",
    "4"=>"Weapon", 
    "5"=>"Armor",
    "6"=>"Card",
    "7"=>"Pet Egg",
    "8"=>"Pet Armor",
    "10"=>"Ammo",
    "11"=>"Delay Consume",
    "18"=>"Cash");
$equip_locations = array(1, 2, 4, 8, 16, 32, 64, 128, 256, 512);
$equip_position = array(
    "1"=>"Lower Headgear", 
    "2"=>"Weapon", 
    "4"=>"Garment", 
    "8"=>"Accessory", 
    "16"=>"Armor", 
    "32"=>"Shield",
    "34"=>"Two Handed",
    "64"=>"Shoes", 
    "128"=>"Accessory",
    "136"=>"Accessory",
    "256"=>"Upper Headgear", 
    "257"=>"Upper and Lower",
    "512"=>"Middle",
    "513"=>"Middle and Lower",
    "768"=>"Upper and Middle",
    "769"=>"Upper, Middle, and Lower");
					
$weapon_types = array(
    "Bare Hands", // 0
    "Dagger", // 1
    "One-Handed Sword", "Two-Handed Sword", "One-Handed Spear", "Two-Handed Spear",  // 2-5
    "One-Handed Axe", "Two-Handed Axe",  // 6, 7
    "Mace", "Two-Handed Mace",  // 8, 9
    "Staff",  // 10
    "Bow", // 11
    "Knuckle",  // 12
    "Musical Instrument",  // 13
    "Whip",  // 14
    "Book",  // 15
    "Katar",  // 16
    "Revolver", "Rifle", "Gatling", "Shotgun", "Grenade",  // 17-21
    "Huuma",  // 22
    "Two-Handed Staff"  // 23
);

if ($DESC) {
	// retrieve description for item
	if (preg_match($desc_re, $DESC, $matches)) {
		$desc = $matches[1];
		// remove hidden underscore _
		$desc = preg_replace('/\^[f]{6}_\^[0]{6}/', '',  $desc);
		$desc = preg_replace('/^(\r\n|\n)*|(\r\n|\n)*$/', '', $desc);  // remove leading and trailing newlines
		$desc = preg_replace('/\r\n|\n|$/', '<br/><br/>', $desc);  // replace newlines with <br/>
		// replace color syntax with css
		$desc = preg_replace('/\^([0-9a-fA-F]{6})(.*?)\^[0]{6}/', '<span style="color:#$1;">$2</span>', $desc);
	}
}

$query = 'SELECT * FROM `item_db` WHERE `id` = ?';
$stmt = prepare_query($query, 0, 'i', $GET_id);
$result = execute_query($stmt, 'item.php');

$line = $result->fetch_assoc();

$slot = $line['slots'] !== NULL ? '['.$line['slots'].']' : '';

echo '
<table class="item">
	<tr>
		<td class="top">&nbsp</td>
		<td class="top" colspan="8">'.$line['name_japanese'].' '.$slot.' - ID #'.$line['id'].' - ('.$line['name_english'].')</td>
		<td class="top">&nbsp</td>
	<tr>
	<tr>
		<td class="attribute">Type:</td>
		<td>'.$item_types[$line['type']].'</td>';
		
if ($line['type'] == 5 || $line['type'] == 6) {  // Armor or Card
echo '
		<td class="attribute">Location:</td>
		<td>'.$equip_position[$line['equip_locations']].'</td>';
} else if ($line['type'] == 4) {  // Weapon
echo '
		<td class="attribute">Class:</td>
		<td>'.$weapon_types[$line['view']].'</td>';
} else {  // Other
echo '
		<td class="attribute">&nbsp</td>
		<td>&nbsp</td>';
}
echo '
		<td class="attribute">Sell:</td>
		<td>'.$line['price_sell'].'</td>
		<td class="attribute">Buy:</td>
		<td>'.$line['price_buy'].'</td>
		<td class="attribute">Weight:</td>
		<td>'.($line['weight']/10).'</td>
	</tr>';
if ($line['type'] == 4) {  // Weapon
echo '
	<tr>
		<td class="attribute">Attack:</td>
		<td>'.$line['attack'].'</td>
		<td class="attribute">&nbsp</td>
		<td>&nbsp</td>
		<td class="attribute">Weapon Level:</td>
		<td>'.$line['weapon_level'].'</td>
		<td class="attribute">Required Level</td>
		<td>'.$line['equip_level'].'</td>
		<td class="attribute">&nbsp</td>
		<td>&nbsp</td>
	</tr>';
} else if ($line['type'] == 5) {  // Armor
echo '
	<tr>
		<td class="attribute">Defense:</td>
		<td>'.$line['defence'].'</td>
		<td class="attribute">Required Lvl:</td>
		<td>'.$line['equip_level'].'</td>
		<td class="attribute">&nbsp</td>
		<td>&nbsp</td>
		<td class="attribute">Slot:</td>
		<td>'.$line['slots'].'</td>
		<td class="attribute">&nbsp</td>
		<td>&nbsp</td>
	</tr>';
}
echo '
	<tr>
		<td class="attribute">Description:</td>
		<td colspan="9">'.$desc.'</td>
	</tr>
	<tr>
		<td class="attribute">Script:</td>
		<td colspan="9">['.$line['script'].'], ['.$line['equip_script'].'], ['.$line['unequip_script'].']</td>
	</tr>
</table>';
fim();

?>
