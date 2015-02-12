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

if (!isset($GET_frm_name) && !isset($GET_page)) {
	caption('View Chars');
	echo '
	<form id="chars" onSubmit="return GET_ajax(\'adminchars.php\',\'accounts_div\',\'chars\');">
		<table class="maintable" style="width: auto">
			<tr>
				<td>Search</td><td>
				<input type="text" name="termo" maxlength="23" size="23">
				<select name="tipo">
				<option value="1">account_id
				<option value="2">char_id
				<option selected="selected" value="3">name
				</select></td><td>
				<input type="submit" name="search" value="search"></td>
				<td><span title="Show All" class="link" onClick="return LINK_ajax(\'adminchars.php?page=0\',\'accounts_div\');">Show All</span></td>
			</tr>
		</table>
	</form>

	<div id="accounts_div" style="color:#000000">';
	$begin = 1;
}

if (isset($GET_tipo)) {
	if (inject($GET_tipo))
		alert($lang['INCORRECT_CHARACTER']);

	if (strlen($GET_termo) < 3)
		alert("Please type at least 3 chars");

	switch($GET_tipo) {
		case 1:
			$stmt = prepare_query(CHARS_SEARCH_ACCOUNT_ID, 0, 's', trim($GET_termo));
			break;
		case 2:
			$stmt = prepare_query(CHARS_SEARCH_CHAR_ID, 0, 's', trim($GET_termo));
			break;
		default:
			$stmt = prepare_query(CHARS_SEARCH_NAME, 0, 's', trim($GET_termo));
			break;
	}
	$pages = 0;
	$back = 'frm_name='.$GET_frm_name.'&tipo='.$GET_tipo.'&termo='.$GET_termo;
} else {
	if (!isset($GET_page))
		$GET_page = 0;
	else if (notnumber($GET_page))
		alert($lang['INCORRECT_CHARACTER']);


	$stmt = prepare_query(CHARS_TOTAL);
	$result = execute_query($stmt, 'adminchars.php');
	$row = $result->fetch_row();  //number of chars
	$pages = (int)($row[0] / 100);
	
	$inicio = $GET_page * 100;
	$stmt = prepare_query(CHARS_BROWSE, 0, 'i', $inicio);

	$back = 'page='.$GET_page;
}

$back = base64_encode($back);
$result = execute_query($stmt, 'adminchars.php');

echo '
<table class="maintable">
	<tr>
		<th align="right">Account_id</th>
		<th align="right">Char_id</th>
		<th align="left">'.$lang['NAME'].'</th>
		<th align="left">'.$lang['CLASS'].'</th>
		<th align="center">'.$lang['BLVLJLVL'].'</th>
		<th align="left">Online</th>
		<th>&nbsp;</th>
	</tr>
	';

$jobs = $_SESSION[$CONFIG_name.'jobs'];

while ($line = $result->fetch_row()) {
	if ($line[6] != 0)
		$online = '<font color="green">on</font>';
	else
		$online = '<font color="red">off</font>';
	
	$job = 'unknown';
	if (isset($jobs[$line[3]]))
		$job = $jobs[$line[3]];


	echo '
	<tr>
		<td align="right">'.$line[0].'</td>
		<td align="right">'.$line[1].'</td>
		<td align="left">'.htmlformat($line[2]).'</td>
		<td align="left">'.$job.'</td>
		<td align="center">'.$line[4].'/'.$line[5].'</td>
		<td align="center">'.$online.'</td>
		<td align="center">
			<span title="Detailed Info" class="link" onClick="window.open(\'admincharinfo.php?id='.$line[1].'\', \'_blank\', \'height = 600, width = 800, menubar = no, status = no, titlebar = no, scrollbars = yes\');">Detail</span>
		</td>

	</tr>
	';
}
echo '</table>';

echo '<span title="0" class="link" onClick="return LINK_ajax(\'addaccount.php\',\'main_div\');">Add Account</span>';

if ($pages) {
	echo '
	<table class="maintable">
		<tr>
			<td>
				<span title="0" class="link" onClick="return LINK_ajax(\'adminchars.php?page=0\',\'accounts_div\');">&lt;&lt;</span>';

	for ($i = ($GET_page - 10); $i <= ($GET_page + 10); $i++) {
		echo ' ';
		if ($i >= 0 && $i != $GET_page && $i <= $pages)
			echo '<span title="'.$i.'" class="link" onClick="return LINK_ajax(\'adminchars.php?page='.$i.'\',\'accounts_div\');">'.$i.'</span>';
		else if ($i == $GET_page)
			echo '<b>'.$i.'</b>';
	}

	echo '
				<span title="'.$pages.'" class="link" onClick="return LINK_ajax(\'adminchars.php?page='.$pages.'\',\'accounts_div\');">&gt;&gt;</span>
			</td>
		</tr>
	</table>';
}


if (isset($begin)) {
	echo '</div>';
}

fim();
?>