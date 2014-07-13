<?php

session_start();
include_once 'config.php'; // loads config variables
include_once 'query.php'; // imports queries
include_once 'functions.php';

caption('Downloads');
echo '<table class="maintable">
		<tr>
			<td>Client</td>
			<td><a href="/data/TestRO.exe">TestRO Client</a></td>
		</tr>
		<tr>
			<td>Data folder</td>
			<td><a href="/data/data-min.rar">Minimum Data folder</a></td>
		</tr>
	  </table>';
fim();

?>
