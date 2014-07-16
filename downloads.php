<?php

session_start();
include_once 'config.php'; // loads config variables
include_once 'query.php'; // imports queries
include_once 'functions.php';

caption('Downloads');
echo '<table class="maintable">
		<tr>
			<td>Client</td>
			<td><a href="/data/TestRO.rar">TestRO Client</a></td>
		</tr>
		<tr>
			<td>&nbsp</td>
			<td>
				<ol>
					<h2>How to Install</h2>
					<li>Extract the TestRO.rar file to your RO folder<br/><br/>
					<span style="font-weight: bold">Note: You may copy the RO folder, though not required. You can simply
					copy extract the contents of the archive straight to your RO folder</span></li>
				<ol>
			</td>
	  </table>';
fim();

?>
