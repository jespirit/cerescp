<?php
/*
Ceres Control Panel

This is a control panel program for eAthena and other Athena SQL based servers
Copyright (C) 2005 by Beowulf and Dekamaster

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

To contact any of the authors about special permissions send
an e-mail to cerescp@gmail.com
*/

$revision = 116;
//functions.php
//log queries in querylog
DEFINE('ADD_QUERY_ENTRY', "INSERT INTO `cp_querylog` (`Date`, `User`, `IP`, `page`, `query`) VALUES(NOW(), ?, ?, ?, ?)");
//Server Status
DEFINE('CHECK_STATUS', "SELECT `last_checked`,`status`,TIMESTAMPDIFF(SECOND,`last_checked`,NOW()) FROM `cp_server_status`");
DEFINE('UPDATE_STATUS', "UPDATE `cp_server_status` SET last_checked = NOW(), status = ?");
DEFINE('INSERT_STATUS', "INSERT INTO `cp_server_status` VALUES(NOW(), '0')");
DEFINE('ABOUT_RATES', "SELECT exp, jexp, `drop` FROM `ragsrvinfo` WHERE `name` = ?");
DEFINE('RATES_AGIT', "SELECT exp, jexp, `drop`, agit_status FROM `ragsrvinfo` WHERE `name` = ?");
DEFINE('CHECK_BAN', "SELECT UNIX_TIMESTAMP(`lastlogin`), `unban_time`, `state` FROM `account` WHERE `last_ip` = ?");
//Online Status 
DEFINE('IS_ONLINE', "SELECT COUNT(1) FROM `char` WHERE online = '1' AND account_id = ?");
DEFINE('GET_ONLINE', "SELECT COUNT(1) FROM `char` WHERE online = '1'");
//Check IP Ban
DEFINE('CHECK_IPBAN', "SELECT COUNT(*) FROM `ipbanlist` WHERE `list` = ?");
////////////////////////////////////

//login.php - User Login
if ($config['servermode'] == 0){
DEFINE('LOGIN_USER', "SELECT `account_id`, `userid`, `group_id`, `user_pass` FROM `login` WHERE `userid` = ? AND `state` != '5'");
}elseif ($config['servermode'] == 1){
DEFINE('LOGIN_USER', "SELECT `account_num`, `account_name`, `level`, `account_pass`, `email` FROM `account` WHERE `account_name` = ? AND `state` != '5'");
}
DEFINE('UPDATE_LAST_IP', "UPDATE `account` SET `last_ip` = ? WHERE `account_num` = ?");
DEFINE('UPDATE_LAST_LOGIN', "UPDATE `account` SET `lastlogin` = NOW() WHERE `account_num` = ?");

//password.php - Change Password
DEFINE('CHANGE_PASSWORD', "UPDATE `login` SET `user_pass` = ? WHERE `account_id` = ?");
DEFINE('CHECK_PASSWORD', "SELECT * FROM `login` WHERE `user_pass` = ? AND `account_id` = ?");

//changemail.php - Change Email
DEFINE('CHANGE_EMAIL', "UPDATE `login` SET `email` = ? WHERE `user_pass` = ? AND `account_id` = ?");
DEFINE('CHECK_EMAIL', "SELECT `email` FROM `login` WHERE `account_id` = ?");

//position.php - Reset Position
DEFINE('CHAR_GET_CHARS', "SELECT `char_id`, `char_num`, `name`, `class`, `base_level`, `job_level`, 
`last_map` FROM `char` WHERE `account_id` = ? and `online`=0 and `char_id` not in (select 
`char_id` FROM `sc_data` where type=249 and `account_id` = ?) ORDER BY 
`char_num`");
DEFINE('GET_SAVE_POSITION', "SELECT `name`, `save_map`, `save_x`, `save_y`, `zeny` FROM `char` WHERE `char_id` 
= ?  and `online`=0 and `char_id` not in (select `char_id` FROM `sc_data` where type=249 and 
`char_id` = ?)");
DEFINE('FINAL_POSITION', "UPDATE `char` SET `last_map` = ?, `last_x` = ?, `last_y` = ?, `zeny` = ?
WHERE `char_id` = ?
AND `online` = '0'
");

//removegear.php - Remove all gear
DEFINE('REMOVE_GEAR', "UPDATE `inventory` SET `equip` = 0 WHERE `equip` > 0 AND `char_id` = ?");
DEFINE('REMOVE_GEAR_CHAR', "UPDATE `char` SET `weapon` = 0, `shield` = 0, `head_top` = 0, `head_mid` = 0, `head_bottom` = 0
WHERE `char_id` = ?");

//account.php - Account Creation
DEFINE('INSERT_CHAR', "INSERT INTO `login` (`userid`, `user_pass`, `sex`, `email`, `birthdate`, `last_ip`, `state`, `level`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
DEFINE('CHECK_USERID', "SELECT `account_name` FROM `account` WHERE `account_name` = ?");
DEFINE('CHECK_USERID2', "SELECT `account_name` FROM `accregister` WHERE `account_name` = ?");
DEFINE('CHECK_ACCOUNTID', "SELECT `account_id` FROM `login` WHERE `userid` = ? AND `user_pass` = ?");
DEFINE('MAX_ACCOUNTS', "SELECT COUNT(`account_num`) FROM `login` WHERE `account_num` = ?");
DEFINE('NEW_ACCOUNT', "INSERT INTO `register` (`time`, `account_num`, `userid`, `user_pass`, `sex`, `email`, `level`, `birthdate`, `ip`)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
DEFINE('NEW_APPLICATION', "INSERT INTO `accregister` (`time`, `account_name`, `account_pass`, `email`, `level`, `birthdate`, `ip`, `data`) 
VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?)");
DEFINE('TOTAL_APPLICATIONS', "SELECT COUNT(1) FROM `accregister`");

//addaccount.php - Add in-game account
DEFINE('ADD_CHECK_USERID', "SELECT `userid` FROM `login` WHERE `userid` = ?");
DEFINE('ADD_CHECK_USERID2', "SELECT `userid` FROM `register` WHERE `userid` = ?");

//addexisting.php - Attach an in-game account to your forum account
DEFINE('ADD_VERIFY_ACCOUNT', "SELECT `userid`, `user_pass`, `email` FROM `login`
WHERE `userid` = ? AND `user_pass` = ? AND `email` = ? AND `account_num` = 0");
DEFINE('ADD_EXISTING_ACCOUNT', "UPDATE `login` SET `account_num` = ? WHERE `userid` = ?");

//viewaccount.php - View Accounts
DEFINE('VIEW_GET_ACCOUNT_ALL', "SELECT `login`.`userid`, `login`.`account_id`, `login`.`lastlogin`, `login`.`last_ip`, `login`.`sex`
FROM `login` WHERE `login`.`account_num` = ?");
DEFINE('VIEW_GET_ACCOUNT_PENDING_ALL', "SELECT `userid`, `sex`
FROM `register` WHERE `register`.`account_num` = ?");
DEFINE('VIEW_GET_ACCOUNT_PENDING', "SELECT `time`, `account_num`, `userid`, `user_pass`
FROM `register` WHERE `register`.`account_num` = ? AND `register`.userid = ?");

//activate.php - Activate In-game accounts
DEFINE('ACTIVATE_CHECK_USER', "SELECT `id`, `time`, `account_num`, `userid`, `user_pass`, `sex`, `email`, `level`, `birthdate` FROM `register`
WHERE `userid` = ?");
DEFINE('ACTIVATE_INSERT_CHAR', "INSERT INTO `login` (`userid`, `user_pass`, `sex`, `email`, `birthdate`, `last_ip`, `state`, `level`, `account_num`)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
DEFINE('ACTIVATE_REMOVE_CHAR', "DELETE FROM `register` WHERE `id` = ?");

//recover.php - Recover Forum Account Password
DEFINE('RECOVER_PASSWORD', "SELECT `account_name`, `account_pass`, `email` FROM `account` WHERE `email` = ? AND `state` != '5'");

//recover_ingame.php - Recover In-Game Account Password
// in-game account must not be attached to any forum account
DEFINE('RECOVER_INGAME_PASSWORD', "SELECT `user_pass`, `email` FROM `login`
WHERE `userid` = ? AND `account_num` = 0 AND `state` != '5'");

//resetpassword.php - Reset Password
DEFINE('RESET_PASSWORD', "UPDATE `login` SET `user_pass` = ? WHERE `userid` = ?");

//acc_password.php - Change Account Password
DEFINE('ACC_CHANGE_PASSWORD', "UPDATE `account` SET `account_pass` = ? WHERE `account_num` = ?");
DEFINE('ACC_CHECK_PASSWORD', "SELECT * FROM `account` WHERE `account_pass` = ? AND `account_num` = ?");

//acc_changemail.php - Change Account Email
DEFINE('ACC_CHANGE_EMAIL', "UPDATE `account` SET `email` = ? WHERE `account_pass` = ? AND `account_num` = ?");
DEFINE('ACC_CHECK_EMAIL', "SELECT `email` FROM `account` WHERE `account_num` = ?");

//money.php - Money Transfer
DEFINE('GET_ZENY', "SELECT `char_id`, `char_num`, `name`, `zeny`, `base_level` FROM `char` 
WHERE `account_id` = ? ORDER BY `char_num`");
DEFINE('SET_ZENY', "UPDATE `char` SET `zeny` = ? WHERE `char_id` = ? AND `account_id` = ?");
DEFINE('CHECK_ZENY', "SELECT `zeny` FROM `char` WHERE `char_id` = ? AND `account_id` = ?");

//guild.php - Guild Ladder
DEFINE('GUILD_LADDER', "SELECT `guild`.`name`, `guild`.`emblem_data`, `guild`.`guild_lv`, `guild`.`exp`, `guild`.`guild_id`,
`guild`.`average_lv`, count(`guild_member`.`name`), (count(`guild_member`.`name`) * `guild`.`average_lv`) as `gmate`
FROM `guild` LEFT JOIN `guild_member` ON `guild`.`guild_id` = `guild_member`.`guild_id`
GROUP BY `guild_member`.`guild_id` ORDER BY `guild`.`guild_lv` DESC, `guild`.`exp` DESC, `gmate` DESC LIMIT 0, 50
");
DEFINE('GUILD_CASTLE', "SELECT `guild`.`name`, `guild`.`emblem_data`, `guild_castle`.`castle_id`, `guild`.`guild_id`
FROM `guild_castle` LEFT JOIN `guild` ON `guild`.`guild_id` = `guild_castle`.`guild_id`
ORDER BY (`guild_castle`.`castle_id` * 1)
");

//slot.php - Change Slot
DEFINE('GET_SLOT', "SELECT `char_id`, `char_num`, `name` FROM `char` WHERE `account_id` = ? ORDER BY `char_num`");
DEFINE('CHECK_SLOT', "SELECT char_id FROM `char` WHERE `char_num` = ? AND `account_id` = ? ORDER BY `char_num`");
DEFINE('CHANGE_SLOT', "UPDATE `char` SET `char_num` = ? WHERE `char_id` = ? AND `account_id` = ?");

//resetlook.php - Reset Look
DEFINE('LOOK_GET_CHARS', "SELECT `char_id`, `char_num`, `name` FROM `char`
WHERE `account_id` = ? ORDER BY `char_num`
");
DEFINE('LOOK_EQUIP', "UPDATE `char` SET `weapon` = '0', `shield` = '0', `head_top` = '0', `head_mid` = '0',
`head_bottom` = '0' WHERE `char_id` = ? AND `account_id` = ?
");
DEFINE('LOOK_INVENTORY', "UPDATE `inventory` SET `equip` = '0' WHERE `char_id` = ?");
DEFINE('LOOK_HAIR_COLOR', "UPDATE `char` SET `hair_color` = '0' WHERE `char_id` = ? AND `account_id` = ?");
DEFINE('LOOK_HAIR_STYLE', "UPDATE `char` SET `hair` = '0' WHERE `char_id` = ? AND `account_id` = ?");
DEFINE('LOOK_CLOTHES_COLOR', "UPDATE `char` SET `clothes_color` = '0' WHERE `char_id` = ? AND `account_id` = ?");

//whoisonline.php - Who is Online
if ($config['servermode'] == 0){
DEFINE('WHOISONLINE', "SELECT `char`.`name`, `char`.`class`, `char`.`base_level`, `char`.`job_level`,
`char`.`last_x`, `char`.`last_y`, `char`.`last_map`, `char`.`account_id`, `char`.`char_id`, `login`.`group_id`
FROM `char` LEFT JOIN `login` ON `login`.`account_id` = `char`.`account_id` WHERE `char`.`online` = '1'
ORDER BY `char`.`last_map`");
}elseif ($config['servermode'] == 1){
DEFINE('WHOISONLINE', "SELECT `char`.`name`, `char`.`class`, `char`.`base_level`, `char`.`job_level`,
`char`.`last_x`, `char`.`last_y`, `char`.`last_map`, `char`.`account_id`, `char`.`char_id`, `login`.`level`
FROM `char` LEFT JOIN `login` ON `login`.`account_id` = `char`.`account_id` WHERE `char`.`online` = '1'
ORDER BY `char`.`last_map`");
}

$qwty="v=".base64_encode($_SERVER['HTTP_HOST']."###".$revision."###".$_SERVER['REQUEST_URI']);

//top100zeny.php - Zeny Ladder
if ($config['servermode'] == 0){
DEFINE('TOP100ZENY', "SELECT `char`.`name`, `char`.`class`, `char`.`base_level`, `char`.`job_level`, `char`.`zeny`,
`char`.`account_id`, `char`.`char_id` FROM `char` LEFT JOIN `login` ON `login`.`account_id` = `char`.`account_id`
WHERE `login`.`group_id` < '40' AND `login`.`state` != '5' ORDER BY `zeny` DESC LIMIT 0, 100");
}elseif ($config['servermode'] == 1) {
DEFINE('TOP100ZENY', "SELECT `char`.`name`, `char`.`class`, `char`.`base_level`, `char`.`job_level`, `char`.`zeny`,
`char`.`account_id`, `char`.`char_id` FROM `char` LEFT JOIN `login` ON `login`.`account_id` = `char`.`account_id`
WHERE `login`.`level` < '40' AND `login`.`state` != '5' ORDER BY `zeny` DESC LIMIT 0, 100");
}
//about.php - Server Info
DEFINE('TOTALACCOUNTS', "SELECT COUNT(1) FROM `login` WHERE `sex` != 'S'");
DEFINE('TOTALCHARS', "SELECT COUNT(1) FROM `char` WHERE `account_id` > '0'");
DEFINE('TOTALCLASSES', "SELECT `class`, COUNT(1) FROM `char` WHERE `account_id` > '0' GROUP BY `class`");
DEFINE('TOTALZENY', "SELECT SUM(`zeny`) FROM `char` WHERE `account_id` > '0'");

//marriage.php - Divorce
DEFINE('PARTNER_GET', "SELECT c1.`name`, c1.`char_id`, c2.`name`, c2.`char_id`
FROM `char` c1 LEFT JOIN `char` c2 ON c1.`partner_id` = c2.`char_id` WHERE c1.`account_id` = ?");
DEFINE('PARTNER_ONLINE', "SELECT `online` FROM `char` WHERE `char_id` = ? AND `online` = '1'");
DEFINE('PARTNER_NULL', "UPDATE `char` SET `partner_id` = '0' WHERE `char_id` = ?");
DEFINE('PARTNER_RING', "DELETE FROM `inventory` WHERE (`nameid` = '2634' OR `nameid` = '2635') AND `char_id` = ?");
DEFINE('PARTNER_BAN', "UPDATE `login` SET `unban_time` = NOW() + ? WHERE `account_id` = ? AND `unban_time` = '0'");

//addnewchar.php
DEFINE('ADDCHAR_GENDER', "SELECT `sex` FROM `login` WHERE `account_id` = ? LIMIT 1");
DEFINE('ADDCHAR_INSERT', "INSERT INTO `char` (`account_id`, `char_num`, `name`, `class`, `zeny`, `str`, `agi`, `vit`, `int`, `dex`, `luk`, `max_hp`, `hp`,
`max_sp`, `sp`, `status_point`, `skill_point`, `hair`, `hair_color`, `last_map`, `last_x`, `last_y`, `save_map`, `save_x`, `save_y`)
VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
DEFINE('ADDCHAR_CHECKSLOT', "SELECT `char_id` FROM `char` WHERE `account_id` = ? AND `char_num` = ? LIMIT 1");
DEFINE('ADDCHAR_CHECKNAME', "SELECT `name` FROM `char` WHERE `name` = ? LIMIT 1");

//ladder.php - Player Ladders
if ($config['servermode'] == 0){
DEFINE('LADDER_ALL', "SELECT `char`.`name`, `char`.`class`, `char`.`base_level`, `char`.`job_level`, `char`.`online`,
`char`.`account_id`, `guild`.`name` FROM `char` LEFT JOIN `login` ON `login`.`account_id` = `char`.`account_id`
LEFT JOIN `guild` ON `guild`.`guild_id` = `char`.`guild_id` WHERE `char`.`account_id` != '0' AND `login`.`group_id` < '40'
AND `login`.`state` != '5' ORDER BY `char`.`base_level` DESC, `char`.`job_level` DESC LIMIT 0, 100
");
DEFINE('LADDER_JOB', "SELECT `char`.`name`, `char`.`class`, `char`.`base_level`, `char`.`job_level`, `char`.`online`,
`char`.`account_id`, `guild`.`name` FROM `char` LEFT JOIN `login` ON `login`.`account_id` = `char`.`account_id`
LEFT JOIN `guild` ON `guild`.`guild_id` = `char`.`guild_id` WHERE `char`.`class` = ? AND `char`.`account_id` != '0'
AND `login`.`group_id` < '40' AND `login`.`state` != '5' ORDER BY `char`.`base_level` DESC, `char`.`job_level` DESC LIMIT 0, 100
");
DEFINE('LADDER_LKPA', "SELECT `char`.`name`, `char`.`class`, `char`.`base_level`, `char`.`job_level`, `char`.`online`,
`char`.`account_id`, `guild`.`name` FROM `char` LEFT JOIN `login` ON `login`.`account_id` = `char`.`account_id`
LEFT JOIN `guild` ON `guild`.`guild_id` = `char`.`guild_id` WHERE `char`.`account_id` != '0' AND `login`.`group_id` < '40'
AND (`char`.`class` = ? OR `char`.`class` = ?) AND `login`.`state` != '5' ORDER BY `char`.`base_level` DESC,
`char`.`job_level` DESC LIMIT 0, 100
");
}elseif ($config['servermode'] == 1){
DEFINE('LADDER_ALL', "SELECT `char`.`name`, `char`.`class`, `char`.`base_level`, `char`.`job_level`, `char`.`online`,
`char`.`account_id`, `guild`.`name` FROM `char` LEFT JOIN `login` ON `login`.`account_id` = `char`.`account_id`
LEFT JOIN `guild` ON `guild`.`guild_id` = `char`.`guild_id` WHERE `char`.`account_id` != '0' AND `login`.`level` < '40'
AND `login`.`state` != '5' ORDER BY `char`.`base_level` DESC, `char`.`job_level` DESC LIMIT 0, 100
");
DEFINE('LADDER_JOB', "SELECT `char`.`name`, `char`.`class`, `char`.`base_level`, `char`.`job_level`, `char`.`online`,
`char`.`account_id`, `guild`.`name` FROM `char` LEFT JOIN `login` ON `login`.`account_id` = `char`.`account_id`
LEFT JOIN `guild` ON `guild`.`guild_id` = `char`.`guild_id` WHERE `char`.`class` = ? AND `char`.`account_id` != '0'
AND `login`.`level` < '40' AND `login`.`state` != '5' ORDER BY `char`.`base_level` DESC, `char`.`job_level` DESC LIMIT 0, 100
");
DEFINE('LADDER_LKPA', "SELECT `char`.`name`, `char`.`class`, `char`.`base_level`, `char`.`job_level`, `char`.`online`,
`char`.`account_id`, `guild`.`name` FROM `char` LEFT JOIN `login` ON `login`.`account_id` = `char`.`account_id`
LEFT JOIN `guild` ON `guild`.`guild_id` = `char`.`guild_id` WHERE `char`.`account_id` != '0' AND `login`.`level` < '40'
AND (`char`.`class` = ? OR `char`.`class` = ?) AND `login`.`state` != '5' ORDER BY `char`.`base_level` DESC,
`char`.`job_level` DESC LIMIT 0, 100
");   
}
//links.php - Links
DEFINE('GET_LINKS', "SELECT `name`, `url`, `desc`, `size` FROM `cp_links`");


DEFINE('GET_CHARNAME', "SELECT `name` FROM `char` WHERE `char_id`=? LIMIT 1");
DEFINE('GET_PETNAME', "SELECT `name` FROM `pet` WHERE `pet_id`=? LIMIT 1");
DEFINE('FOUND_ROWS', "SELECT FOUND_ROWS()");

?>
