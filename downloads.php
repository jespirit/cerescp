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
	<ol>
		<li>Copy your RO folder</li>
		<li>Extract the contents of the TestRO.rar file to your RO folder</li>
        <li>Run TestPatcher.exe</li>
		<li>Run TestRO.exe</li>
	</ol>
</div>';
fim();

?>
