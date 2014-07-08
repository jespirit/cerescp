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

include_once 'config.php'; // loads config variables
include_once 'functions.php';

DEFINE('BF_IP', "SELECT `ban`, `user` FROM `cp_bruteforce` WHERE `IP` = ? AND (`date` > ? OR `ban` > ?)");
DEFINE('BF_USER', "SELECT `ban` FROM `cp_bruteforce` WHERE `user` = ? AND (`date` > ? OR `ban` > ?)");
DEFINE('BF_ADD', "INSERT INTO `cp_bruteforce` (`user`, `IP`, `date`, `ban`) VALUES(?, ?, ?, ?)");


function bf_check_user($username) {
	$log_ip = $_SERVER['REMOTE_ADDR'];
	$current = time();
	
	$stmt = prepare_query(BF_IP, 0, 'sii', $log_ip, $current - 300, $current);
	$result = execute_query($stmt, "check_user", 1, 0);
	$tentativas = $result->num_rows;
	while ($line = $result->fetch_row()) {
		if ($line[0] > $current)
			return (int)(($line[0] - $current) / 60);
	}
	$stmt->close();

	if ($tentativas > 9) {
		$stmt = prepare_query(BF_ADD, 0, 'ssii', "Random Try", $log_ip, $current, $current + 600);
		$result = execute_query($stmt, "check_user", 1, 0);
		$stmt->close();
		return (int)(600 / 60);
	}

	if (inject($username))
		return 0;

	$stmt = prepare_query(BF_USER, 1, 'sii', $username, $current - 300, $current);
	$result = execute_query($stmt, "check_user", 0);
	$tentativas = $result->count();
	while ($line = $result->fetch_row()) {
		if ($line[0] > $current)  // still banned?
			return (int)(($line[0] - $current) / 60);  // return how much time until ban is lifted
	}

	if ($tentativas > 2) {  // failed 3 times too many, ban the IP for 5 minutes
		$stmt = prepare_query(BF_ADD, 1, 'ssii', $username, $log_ip, $current, $current + 300);
		$result = execute_query($stmt, "check_user", 0);
		$stmt->close();
		return (int)(300 / 60);
	}
	
	return 0;
}

function bf_error($username) {
	$log_ip = $_SERVER['REMOTE_ADDR'];
	$current = time();

	$stmt = prepare_query(BF_ADD, 1, 'ssii', $username, $log_ip, $current, 0);
	$result = execute_query($stmt, "check_user", 0);
	return 1;
}

?>
