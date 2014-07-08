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
include_once 'adminquery.php';
include_once 'functions.php';

if (!isset($_SESSION[$CONFIG_name.'level']) || $_SESSION[$CONFIG_name.'level'] < $CONFIG['cp_admin'])
	die ('Not Authorized');

caption('Atcommand Logs');

if (!isset($GET_page))
	$GET_page = 0;
else if (notnumber($GET_page))
	alert($lang['INCORRECT_CHARACTER']);


$lpp = 30;

$inicio = $GET_page * $lpp;
$back = "page=".$GET_page;

$query = sprintf(LOGS_ATCOMMAND, $CONFIG_rag_db);
$stmt = prepare_query($query, 2, 'iii', $_SESSION[$CONFIG_name.'level'], $inicio, $lpp);
$result = execute_query($stmt, 'logatcommand.php');

$stmt = prepare_query(FOUND_ROWS, 2);
$result2 = execute_query($stmt, 'logatcommand.php');
$row = $result2->fetch_row();
$num = $row[0];
$pages = (int)(($num-1)/$lpp);

$pagestring = '';
if ($pages) {
	$pagestring .= '<table class="maintable"><tr><td><center><span title="0" class="link" onClick="return LINK_ajax(\'logatcommand.php?page=0\',\'main_div\');">&lt;&lt;</span>';
	if ($GET_page > 0) {
		$pagestring .= ' <span title="'.($GET_page-1).'" class="link" onClick="return LINK_ajax(\'logatcommand.php?page='.($GET_page-1).'\',\'main_div\');">&lt;</span>';
	}

	for ($i = ($GET_page - 10); $i <= ($GET_page + 10); $i++) {
		$pagestring .=  ' ';
		if ($i >= 0 && $i != $GET_page && $i <= $pages)
			$pagestring .= '<span title="'.$i.'" class="link" onClick="return LINK_ajax(\'logatcommand.php?page='.$i.'\',\'main_div\');">'.$i.'</span>';
		else if ($i == $GET_page)
			$pagestring .= '<b>'.$i.'</b>';
	}

	if ($GET_page < $pages)
		$pagestring .= ' <span title="'.($GET_page+1).'" class="link" onClick="return LINK_ajax(\'logatcommand.php?page='.($GET_page+1).'\',\'main_div\');">&gt;</span>';

	$pagestring .= ' <span title="'.$pages.'" class="link" onClick="return LINK_ajax(\'logatcommand.php?page='.$pages.'\',\'main_div\');">&gt;&gt;</span></center></td></tr></table>';
}

echo $pagestring;
echo '<table align="center"><tr><td><span title="'.$GET_page.'" class="link" onClick="return LINK_ajax(\'logatcommand.php?page='.$GET_page.'\',\'main_div\');">Refresh</span></td></tr></table>';

echo '
<table class="maintable">
	<tr>
		<th style="text-align: center; width: 100px;">Date</th>
		<th style="text-align: center; width: 100px;">AccountID</th>
		<th style="text-align: center; width:  50px;">CharID</th>
		<th style="text-align: center; width:  50px;">Name</th>
		<th style="text-align: center; width:  50px;">Map</th>
		<th style="text-align: center; width: 100%;">Command</th>
	</tr>';

while ($line = $result->fetch_assoc()) {
	echo '<tr>
		  <td style="white-space: nowrap;">'.$line['atcommand_date'].'</td>
		  <td style="white-space: nowrap;">'.$line['account_id'].' ('.($config['servermode'] == 0 ? $line['group_id'] : $line['level']).')</td>
		  <td style="white-space: nowrap;">'.$line['char_id'].'</td>
		  <td style="white-space: nowrap;">'.htmlformat($line['char_name']).'</td>
		  <td style="white-space: nowrap;">'.$line['map'].'</td>
		  <td style="white-space: nowrap; text-align: left;">'.htmlformat($line['command']).'</td>
		 </tr>';
}
echo '</table>';

echo '<table align="center"><tr><td><span title="'.$GET_page.'" class="link" onClick="return LINK_ajax(\'logatcommand.php?page='.$GET_page.'\',\'main_div\');">Refresh</span></td></tr></table>';
echo $pagestring;

fim();
?>
