<?php

session_start();
include_once 'config.php'; // loads config variables
include_once 'query.php'; // imports queries
include_once 'functions.php';

caption('Features');
echo '
<div>
<h1>Features</h1>
<h2>Super Quest Items Galore</h2>
<p>You can buy any SQI you want</p>
<img src="big/sqi-shop.jpg" alt="sqi shop" width="" height="">

<h2>SQI Bonuses</h2>
<p>Add or remove SQI bonuses as you wish</p>
<img src="big/sqibonus-command.png" alt="@sqibonus" width="" height="">

<h2>Barricade Builder</h2>
<p>Know what it takes to become the fastest Barricade builder</p>
<img src="big/barricade-builder-progress.jpg" alt="barricade builder progress" width="" height="">
<img src="big/barricade-builder-done.png" alt="barricade builder done" width="" height="">

<h2>Guardian Stone Builder</h2>
<p>Know what it takes to become the fastest Guardian Stone builder</p>
<img src="big/guardian-stone-progress.jpg" alt="guardian stone progress" width="" height="">
<img src="big/guardian-stone-progress-effect.jpg" alt="guardian stone progress effect" width="" height="">
<img src="big/guardian-stone-done.png" alt="guardian stone done" width="" height="">

<h2>Emperium Breaker Room</h2>
<p>Set the record for the fastest Emperium break</p>
<img src="big/emp-sniper-progress.jpg" alt="emp sniper progress" width="" height="">
<img src="big/emp-sniper-done.jpg" alt="emp sniper done" width="" height="">

</div>';
fim();

?>