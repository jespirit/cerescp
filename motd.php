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
		<h1>Updates <span class="date">10/5/2014</span></h1>
		
		<h2>Battlegrounds</h2>
		<p>Most of the Battlegrounds equipment had remained the same with no modifications except
		for a few weapons that were affected while wearing the Aegis shield.
		You can find all the BG related equipment from the new Battlegrounds Gear NPC
		in the Izlude Marketplace.</p>
		
		<h2>Monster Room Spawn Limit</h2>
		<p>I've restricted the maximum number of monsters that can be spawned in the Monster Room to 10.</p>
		
		<h2>Other Updates</h2>
		<ul>
			<li>Added Cat Ear Beret[1]</li>
			<li>Fixed Bone Plate[1] armor to be wearable by the classes listed.</li>
			<li>Fixed defense and level requirements for Silver Guard[1], Round Buckler[1], and Rosa Shield[1]</li>
			<li>Changed level requirement for Carga Mace[2] to 90.</li>
		</ul>
	</div>
	<div class="entry">
		<h1>Updates <span class="date">9/8/2014</span></h1>
		<p>
		I've added some new additions some of you may find useful.

		<h2>Monster Room</h2>

		<p>You can spawn any monster you wish with <span class="rocmd">@monster</span>
		and kill them with <span class="rocmd">@killmonster</span>. It's also an instance map
		so only you and your party members will have access.</img>
		
		<br><br>
		The Monster Room NPC is located in Prontera.
		</p>
		
		
		<div style="float:clear;"></div>
		
		<h2>Always Consistent Damage</h2>

		<p>Have you always wanted clear results to test out the difference between
		a +9 Mes[3] and a +10 Mes[3]? How about one-shotting a Mavka when your
		damage always varies between Bowling Bashes? What about the difference
		between a +8 or +9 Book of Blazing Sun[3] in Dimensional Gorge?</p>

		<p>With the new commands <span class="rocmd">@minatk</span>, <span class="rocmd">@avgatk</span>,
		and <span class="rocmd">@maxatk</span>, you will always have consistent damage
		so that you can clearly see the difference between builds and equipment.</p>

		<p>To restore your damage calculations back to normal use <span class="rocmd">@atkoff</span>.</p>

		<p>This also works for magic damage too!</p>

		<p><span class="warning">Note:</span> I removed the random component in SOFT DEF damage reduction.
		Equation 1 is player SOFT DEF reduction, while equation 2 is for monsters.</p>

		<p>
		Old equations:
		<ul>
			<li>(1) [VIT*0.5] + rnd([VIT*0.3], max([VIT*0.3],[VIT^2/150]-1))</li>
			<li>(2) VIT + rnd(0,[VIT/20]^2-1)</li>
		</ul>
		</p>

		<p>
		New equations:

		<ul>
			<li>(1) [VIT*0.5]</li>
			<li>(2) VIT</li>
		</ul>
		
		</p>

		<h2>Additional commands:</h2>

		<p>Reset your stats and skills with <span class="rocmd">@streset</span>
		and <span class="rocmd">@skreset</span>.</p>

		<p>Toggle Miracle on/off for your Star Glad with <span class="rocmd">@miracle</span>. Works on any map!</p>

		<p>Cast any skill you want with <span class="rocmd">@useskill</span> such as Fire endow, Kaahi,
		Kaizel, Blessing, Increase Agility, etc...</p>
		
		<p><span class="warning">Note:</span> You must skill IDs</p>
		
		<h2>Other Updates</h2>
		<ul>
			<li>Updated Assassin Cross Eremes card is now compounded to armor.</li>
			<li>Added Splash Hat[1]</li>
			<li>Miracle cannot be dispelled by use of Dispel skill</li>
			<li>Stat Food cannot be dispelled on death</li>
			<li>Added Scaraba and Gold Scaraba cards</li>
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
