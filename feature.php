<?php

session_start();
include_once 'config.php'; // loads config variables
include_once 'query.php'; // imports queries
include_once 'functions.php';

caption('Features');

// create curl resource
$ch = curl_init();

// set url
if ($CONFIG_local_machine) {
    curl_setopt($ch, CURLOPT_URL, "localhost/public_html/resources/features.html");
}
else {
    curl_setopt($ch, CURLOPT_URL, $CONFIG_site_name . "/public_html/resources/features.html");
}

//return the transfer as a string
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// $output contains the output string
$output = curl_exec($ch);

// close curl resource to free up system resources
curl_close($ch); 

echo $output;
fim();

?>