<?php
/*
Ceres Control Panel

This is a control pannel program for Athena and Freya
Copyright (C) 2005 by Beowulf and Nightroad

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

$revision = 0;

//adminaccounts
if ($config['servermode'] == 0){
DEFINE('ACCOUNTS_SEARCH_ACCOUNT_ID', "SELECT `account_id`, `userid`, `sex`, `email`, `group_id`, `last_ip`, `unban_time`, `state`, 
`user_pass`, `lastlogin`, `birthdate` FROM `login` WHERE `account_id` = '?'");
DEFINE('ACCOUNTS_SEARCH_EMAIL', "SELECT `account_id`, `userid`, `sex`, `email`, `group_id`, `last_ip`, `unban_time`, `state`, `user_pass`
FROM `login` WHERE `email` LIKE '%%?%%'");
DEFINE('ACCOUNTS_SEARCH_IP', "SELECT `account_id`, `userid`, `sex`, `email`, `group_id`, `last_ip`, `unban_time`, `state`, `user_pass`
FROM `login` WHERE `last_ip` LIKE '%%?%%'");
DEFINE('ACCOUNTS_SEARCH_USERID', "SELECT `account_id`, `userid`, `sex`, `email`, `group_id`, `last_ip`, `unban_time`, `state`, `user_pass`
FROM `login` WHERE `userid` LIKE '%%?%%'");
DEFINE('ACCOUNTS_BROWSE', "SELECT `account_id`, `userid`, `sex`, `email`, `group_id`, `last_ip`, `unban_time`, `state`, `user_pass`
FROM `login` ORDER BY `account_id` LIMIT ?, 100");
}elseif ($config['servermode'] == 1){
DEFINE('ACCOUNTS_SEARCH_ACCOUNT_ID', "SELECT `account_id`, `userid`, `sex`, `email`, `level`, `last_ip`, `unban_time`, `state`, 
`user_pass`, `lastlogin`, `birthdate` FROM `login` WHERE `account_id` = '?'");
DEFINE('ACCOUNTS_SEARCH_EMAIL', "SELECT `account_id`, `userid`, `sex`, `email`, `level`, `last_ip`, `unban_time`, `state`, `user_pass`
FROM `login` WHERE `email` LIKE '%%?%%'");
DEFINE('ACCOUNTS_SEARCH_IP', "SELECT `account_id`, `userid`, `sex`, `email`, `level`, `last_ip`, `unban_time`, `state`, `user_pass`
FROM `login` WHERE `last_ip` LIKE '%%?%%'");
DEFINE('ACCOUNTS_SEARCH_USERID', "SELECT `account_id`, `userid`, `sex`, `email`, `level`, `last_ip`, `unban_time`, `state`, `user_pass`
FROM `login` WHERE `userid` LIKE '%%?%%'");
DEFINE('ACCOUNTS_BROWSE', "SELECT `account_id`, `userid`, `sex`, `email`, `level`, `last_ip`, `unban_time`, `state`, `user_pass`
FROM `login` ORDER BY `account_id` LIMIT ?, 100");
}
//adminaccedit
if ($config['servermode'] == 0){
DEFINE('ACCEDIT_UPDATE', "UPDATE `login` SET `userid` = '?', `user_pass` = '?', `sex` = '?', `email` = '?', `group_id` = '?', `birthdate` = '?'
WHERE `account_id` = '?'
");
}elseif ($config['servermode'] == 1){
DEFINE('ACCEDIT_UPDATE', "UPDATE `login` SET `userid` = '?', `user_pass` = '?', `sex` = '?', `email` = '?', `level` = '?', `birthdate` = '?'
WHERE `account_id` = '?'
");
}
//adminaccchars
DEFINE('ACCCHARS_ID', "SELECT `char_id`, `char_num`, `name`, `class`, `base_level`, `job_level`, `online`, `last_map`, `last_x`, `last_y`
FROM `char` WHERE `account_id` = '?' ORDER BY `char_num`
");

//admincharinfo
DEFINE('CHARINFO_CHAR', "SELECT `char`.*, `guild`.`name`, `guild`.`emblem_data` FROM `char` LEFT JOIN `guild` USING (`guild_id`) WHERE `char`.`char_id` = '?'");
DEFINE('CHARINFO_INVENTORY', "SELECT `nameid`, `amount`, `card0`, `card1`, `card2`, `card3`, `refine`, `equip` FROM `inventory`
WHERE `char_id` = '?'
");
DEFINE('CHARINFO_STORAGE', "SELECT `nameid`, `amount`, `card0`, `card1`, `card2`, `card3`, `refine`, `equip` FROM `storage`
WHERE `account_id` = '?'
");
DEFINE('CHARINFO_CART',"SELECT `nameid`, `amount`, `card0`, `card1`, `card2`, `card3`, `refine`, `equip` FROM `cart_inventory`
WHERE char_id = '?'
");

//adminaccban
DEFINE('ACCBAN_UPDATE', "UPDATE `login` SET `unban_time` = '?', `state` = '?' WHERE `account_id` = '?'");

//adminchars
DEFINE('CHARS_SEARCH_ACCOUNT_ID', "SELECT `account_id`, `char_id`, `name`, `class`, `base_level`, `job_level`, `online`
FROM `char` WHERE `account_id` = '?' ORDER BY `account_id`");
DEFINE('CHARS_SEARCH_CHAR_ID', "SELECT `account_id`, `char_id`, `name`, `class`, `base_level`, `job_level`, `online`
FROM `char` WHERE `char_id` = '?' ORDER BY `account_id`");
DEFINE('CHARS_SEARCH_NAME', "SELECT `account_id`, `char_id`, `name`, `class`, `base_level`, `job_level`, `online`
FROM `char` WHERE `name` LIKE '%%?%%' ORDER BY `account_id`");
DEFINE('CHARS_BROWSE', "SELECT `account_id`, `char_id`, `name`, `class`, `base_level`, `job_level`, `online`
FROM `char` ORDER BY `account_id` LIMIT ?, 100");
DEFINE('CHARS_TOTAL', "SELECT COUNT(1) FROM `char` WHERE `account_id` > '0'");

//logs
if ($config['servermode'] == 0){
DEFINE('LOGS_ATCOMMAND', "SELECT SQL_CALC_FOUND_ROWS `atcommandlog`.*, `login`.`group_id` FROM `atcommandlog` JOIN `%s`.`login` USING (`account_id`) WHERE `login`.`group_id`<=? ORDER BY `atcommand_date` ASC LIMIT ?, ?");
DEFINE('LOGS_BRANCH', "SELECT SQL_CALC_FOUND_ROWS `branchlog`.*, `login`.`group_id` FROM `branchlog` JOIN `%s`.`login` USING (`account_id`) ORDER BY `branch_date` ASC LIMIT ?, ?");
DEFINE('LOGS_LOGIN', "SELECT SQL_CALC_FOUND_ROWS * FROM `loginlog` ORDER BY `time` ASC LIMIT ?, ?");
DEFINE('LOGS_MVP', "SELECT SQL_CALC_FOUND_ROWS * FROM `mvplog` ORDER BY `mvp_date` ASC LIMIT ?, ?");
DEFINE('LOGS_NPC', "SELECT SQL_CALC_FOUND_ROWS `npclog`.*, `login`.`group_id` FROM `npclog` LEFT JOIN `%s`.`login` USING (`account_id`) ORDER BY `npc_date` ASC LIMIT ?, ?");
DEFINE('LOGS_ZENY', "SELECT SQL_CALC_FOUND_ROWS * FROM `zenylog` ORDER BY `time` ASC LIMIT ?, ?");
DEFINE('LOGS_CASH', "SELECT SQL_CALC_FOUND_ROWS * FROM `cashlog` ORDER BY `time` ASC LIMIT ?, ?");
DEFINE('LOGS_ITEMS', "SELECT SQL_CALC_FOUND_ROWS `picklog`.*, `char`.`name` as `char_name`, `login`.`account_id`, `login`.`group_id` FROM `picklog` JOIN `%s`.`char` USING (`char_id`) JOIN `%s`.`login` ON (`char`.`account_id`=`login`.`account_id`) WHERE `login`.`group_id`<=? ORDER BY `time` ASC LIMIT ?, ?");
DEFINE('LOGS_CHAR', "SELECT SQL_CALC_FOUND_ROWS `charlog`.*, `login`.`group_id` FROM `charlog` JOIN `login` USING (`account_id`) WHERE `login`.`group_id`<=? ORDER BY `time` ASC LIMIT ?, ?");
}elseif ($config['servermode'] == 1){
DEFINE('LOGS_ATCOMMAND', "SELECT SQL_CALC_FOUND_ROWS `atcommandlog`.*, `login`.`level` FROM `atcommandlog` JOIN `%s`.`login` USING (`account_id`) WHERE `login`.`level`<=? ORDER BY `atcommand_date` ASC LIMIT ?, ?");
DEFINE('LOGS_BRANCH', "SELECT SQL_CALC_FOUND_ROWS `branchlog`.*, `login`.`level` FROM `branchlog` JOIN `%s`.`login` USING (`account_id`) ORDER BY `branch_date` ASC LIMIT ?, ?");
DEFINE('LOGS_LOGIN', "SELECT SQL_CALC_FOUND_ROWS * FROM `loginlog` ORDER BY `time` ASC LIMIT ?, ?");
DEFINE('LOGS_MVP', "SELECT SQL_CALC_FOUND_ROWS * FROM `mvplog` ORDER BY `mvp_date` ASC LIMIT ?, ?");
DEFINE('LOGS_NPC', "SELECT SQL_CALC_FOUND_ROWS `npclog`.*, `login`.`level` FROM `npclog` LEFT JOIN `%s`.`login` USING (`account_id`) ORDER BY `npc_date` ASC LIMIT ?, ?");
DEFINE('LOGS_ZENY', "SELECT SQL_CALC_FOUND_ROWS * FROM `zenylog` ORDER BY `time` ASC LIMIT ?, ?");
//DEFINE('LOGS_CASH', "SELECT SQL_CALC_FOUND_ROWS * FROM `cashlog` ORDER BY `time` ASC LIMIT ?, ?");
DEFINE('LOGS_ITEMS', "SELECT SQL_CALC_FOUND_ROWS `picklog`.*, `char`.`name` as `char_name`, `login`.`account_id`, `login`.`level` FROM `picklog` JOIN `%s`.`char` USING (`char_id`) JOIN `%s`.`login` ON (`char`.`account_id`=`login`.`account_id`) WHERE `login`.`level`<=? ORDER BY `time` ASC LIMIT ?, ?");
DEFINE('LOGS_CHAR', "SELECT SQL_CALC_FOUND_ROWS `charlog`.*, `login`.`level` FROM `charlog` JOIN `login` USING (`account_id`) WHERE `login`.`level`<=? ORDER BY `time` ASC LIMIT ?, ?");
}
?>
