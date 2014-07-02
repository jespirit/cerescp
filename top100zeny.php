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

$query = sprintf(TOP100ZENY);
$result = execute_query($query, "top100zeny.php");

caption($lang['TOP100ZENY_TOP100ZENY']);
echo '
<table class="maintable">
<tr>
	<th align="right">'.$lang['POS'].'</th>
	<th>&nbsp;</th>
	<th align="left">'.$lang['NAME'].'</th>
	<th align="left">'.$lang['CLASS'].'</th>
	<th align="right">'.$lang['ZENY'].'</th>
</tr>
';
$nusers = 0;
if ($result) {
	while ($line = $result->fetch_row()) {
		$nusers++;
		if ($nusers > 100)
			break;

		$zeny = moneyformat($line[4]);
		$charname = htmlformat($line[0]);

		if (isset($_SESSION[$CONFIG_name.'account_id']) && $line[5] == $_SESSION[$CONFIG_name.'account_id'])
			echo '<tr class="highlight">';
		else
			echo '<tr>';
		echo '
			<td align="right">'.$nusers.'</td>
			<td>&nbsp;</td>
			<td align="left">'.$charname.'</td>
			<td align="left">
		';
		if (isset($jobs[$line[1]]))
			echo $jobs[$line[1]];
		else
			echo $lang['UNKNOWN'];
		echo '
			</td>
			<td align="right">'.$zeny.'</td>
		</tr>
		';
	}
}
echo '</table>';

fim();
?>
