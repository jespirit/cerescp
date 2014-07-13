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
		<tr>
			<td>How to install</td>
			<td>
				<ol>
					<li>Make a copy of your RO folder</li>
					<li>Delete the data folder</li>
					<li>Delete the existing DATA.ini file (if any) and rename the talo.nro to data.ini</li>
					<li>Download the client and data minimum folder from above</li>
					<li>Copy the client to your RO folder</li>
					<li>Extract the data folder to your RO folder</li>
					<li>That\'s it! Enjoy!</li>
				<ol>
	  </table>';
fim();

?>
