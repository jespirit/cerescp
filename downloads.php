<?php

session_start();
include_once 'config.php'; // loads config variables
include_once 'query.php'; // imports queries
include_once 'functions.php';

caption('Downloads');
echo '
<div>
	<p>Client Download: <span><a id="download" href="/data/TestRO.rar">TestRO Client</a></span></p>
		<h2>How to Install</h2>
		<p>There are two ways to install the RO client</p>
	<ol>
		<li>Copy your RO folder</li>
		<li>Extract the contents of the TestRO.rar file to your RO folder</li>
		<li>Run TestRO.exe</li>
	</ol>
	<p>OR</p>
	<ol>
		<li>Extract the contents of the TestRO.rar file directly to your existing RO folder</li>
		<p class="warning">Warning: This will overwrite two files common to most RO installations: 
		<span class="file-underline">data.ini</span> and <span class="file-underline">data/clientinfo.xml</span><br/>
		This is only recommended IF those two files are not needed or don\'t exist.<br/>
		Otherwise this may break your existing RO installation.</p>
	</ol>
</div>';
fim();

?>
