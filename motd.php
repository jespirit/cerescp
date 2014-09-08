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

caption($lang['NEWS_MESSAGE']);
?>
<div>
	<p>Welcome to TestRO Control Panel</p>
	
	<p>You may register an account by clicking here: <span title="Register" class="link active" onClick="return LINK_ajax('account.php', 'main_div');">Register New Account</span></p>
	
	<p>To start playing please go here and download the necessary files: <span title="Download" class="link active" onClick="return LINK_ajax('downloads.php', 'main_div');">Downloads</span></p>
</div>
<div id="update">
	<div class="entry">
		<h1>Updates <span class="date">8/23/2014</span></h1>
		<p>I've added few new things that I hope you will find useful.</p>
		<h2>Monster Room</h2>
		<p>Instanced dungeon room where you can spawn monsters with <span class="rocmd">@monster</span> and kill them with <span class="rocmd">@killmonster</span>.
		Since it is an instanced map, only you and your party members have access to the map.</p>
		<h2>Consistent Damage Output</h2>
		<p>It can be difficult to compare different builds and equipment when the damage output varies, in particular with
		duel-wielding/Katar SinXs, Star Glad's Warmth, and pretty much any non-crit builds.</p>
		<p>So I've added 3 new commands <span class="rocmd">@minatk</span>, <span class="rocmd">@avgatk</span>, and <span class="rocmd">@maxatk</span> that will give you consistent damage no matter what build or equipment you're using.</p>
		<p>You can also return your damage output back to normal with the <span class="rocmd">@atkoff</span> command.</p>
		<h2>Additional Commands</h2>
		<ul>
			<li><span class="rocmd">@streset and <span class="rocmd">@skreset</span></li>
			<li><span class="rocmd">@useskill</span></li>
			<li><span class="rocmd">@miracle</span></li>
		</ul>
	</div>
	<div class="entry">
		<h1>Updates <span class="date">7/20/2014</span></h1>
		<h2>New Items</h2>
		<ul>
			<li>Added Elven Bow[1]</li>
			<li>Elven Arrow/Quiver</li>
			<li>Added Deviruchi Headphones[1]</li>
		</ul>
		
		<h2>New NPCs</h2>
		<ul>
			<li>Soul Linker NPC</li>
			<li>Remove Buffs NPC</li>
		</ul>
		
		<h2>Skill Changes</h2>
		<ul>
			<li>Asura Strike now has a 15 second unique cooldown</li>
			<li>Increased Finger Offensive's delay from 0.5s to 0.75s</li>
		</ul>
		
		<h2>Other changes</h2>
		<ul>
			<li>Split the Middle Headgears NPC to slotted and non-slotted versions</li>
			<li>Only level 99 GMs can refine Middle, Lower headgears and Accessories via @refine</li>
			<li>Adding/removing a SQI bonus via @sqibonus unequips the SQI</li>
			<li>Removed @allskill GM command for normal players</li>
			<li>Adjusted @jobchange to give out correct number of skill points when changing jobs</li>
		</ul>
	</div>
</div>
<?php
fim();
?>
