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

session_start();
include_once 'config.php'; // loads config variables
include_once 'query.php'; // imports queries
include_once 'functions.php';

$jobs = $_SESSION[$CONFIG_name.'jobs'];

if (is_woe())
	redir('motd.php', 'main_div', $lang['WOE_TIME']);

$query = sprintf(WHOISONLINE);
$result = execute_query($query, "whoisonline.php");

caption($lang['WHOISONLINE_WHOISONLINE']);
echo '
<table class="maintable">
<tr>
	<th align="left">'.$lang['NAME'].'</th>
	<th align="left">'.$lang['CLASS'].'</th>
	<th align="center">'.$lang['BLVLJLVL'].'</th>
	';
if (isset($_SESSION[$CONFIG_name.'level']) && $_SESSION[$CONFIG_name.'level'] >= $CONFIG['cp_admin'])
	echo '<th align="center">'.$lang['WHOISONLINE_COORDS'].'</th>';
	echo '
	<th align="left">'.$lang['MAP'].'</th>
</tr>
';
if ($result) {
	while ($line = $result->fetch_row()) {
		$charname = htmlformat($line[0]);
		if ($line[9] >= $CONFIG_gm_hide) {
			if (!isset($_SESSION[$CONFIG_name.'level']) || (isset($_SESSION[$CONFIG_name.'level']) && $_SESSION[$CONFIG_name.'level'] < $line[9]))
				continue;
		}
			echo '    
				<tr>
					<td align="left">'.$charname.'</td>
					<td align="left">
				';
				if (isset($jobs[$line[1]]))
					echo $jobs[$line[1]];
				else
					echo $lang['UNKNOWN'];
				echo '
					</td>
					<td align="center">'.$line[2].'/'.$line[3].'</td>
					';
				if (isset($_SESSION[$CONFIG_name.'level']) && $_SESSION[$CONFIG_name.'level'] >= $CONFIG['cp_admin'])
					echo '<td align="center">'.$line[4].','.$line[5].'</td>';
				echo '
					<td align="left">'.$line[6].'</td>
				</tr>
			';
	}
}
echo '</table>';

fim();
?>
