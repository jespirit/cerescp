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
        <h1>Updates <span class="date">03/21/2015</span></h1>
        
        <h2>Poem of Bragi - Damage Reduction</h2>
        <p>Implemented damage reduction while in Poem of Bragi</p>
        
        <p>Formula:</p>
<pre class="code">
<code>skilllvb = skill level of Bragi
skilllvm = skill level of Musical Lesson
int = int stat of the character
input_damage = damage after everything has been applied (card modifiers, etc.)

final_damage = (200-((3*skilllvb+int/5)+2*skilllvm))*input_damage/200;
</code>
</pre>

        <h2>New Commands</h2>
        <ul>
            <li>Added <span class="rocmd">@getranked</span> command for obtaining ranked potions</li>
            <li>Added <span class="rocmd">@getweapon</span> command for obtaining ranked weapons</li>
            <li>Added <span class="rocmd">@linkme</span> command for linking yourself</li>
        </ul>
        
        <h2>Other Updates</h2>
        <ul>
            <li>Added two GvG Maps accessible through the Warper for GvG testing</li>
            <li>Fixed Soul Linker NPC not linking certain classes</li>
            <li>Added third slot to Djinn SQI</li>
            <li>Updated <span class="rocmd">@item</span> to allow spaces in item names for ease of use</li>
            <li>Fixed character deletion</li>
        </ul>
    </div>
    
    <div class="entry">
		<h1>Updates <span class="date">03/05/2015</span></h1>
		
		<h2>New Account Management System</h2>
		<p>You no longer have to submit an application for each in-game account.</p>
        
        <p>
        Instead, you submit an application to create an online <span style="color: blue;">Control Panel</span> account
		from which you can manage all your in-game accounts. This way you can create
        new game accounts with ease, without myself having to review each in-game account application.
        </p>
        
        <h3>Existing Users</h3>
        <p>For existing users who already have 1 or more in-game accounts, you must first
        submit an application for an online Control Panel account. And once the application has been
        accepted, you can attach an existing in-game account to your Control Panel account.
        You may have to do a Password Recovery if you forget what the password was for that particular
        game account.</p>
        
        <h2>Asura Strike Soft Cap</h2>
        <p>Based on the output damage results for Asura Strike, I've implemented a similiar diminishing returns
        formula for Asura Strike to emulate the soft cap of 200K damage.</p>
        
        <p>Exponential decay formula (positively increasing): <span style="color: green">y = C * (1 - e<sup>-kx</sup>)</span>, where
        <br>x = asura damage
        <br>C = maximum output
        <br>k = constant growth rate
        </p>
        
        <p>Code:</p>
<pre class="code">
<code>soft_cap = 200000;
C = 250000;  // max damage becomes 450k (200k+250k)
k = 1.98E-6  // growth rate of 0.000198%
x = damage - soft_cap;

if (damage > soft_cap)  // apply diminishing returns?
    damage = soft_cap + C * (1 - exp(-k*x);
</code>
</pre>
        <table>
            <tr>
                <th>Input</th>
                <th>Output</th>
                <th>Expected Output</th>
            </tr><tr>
                <td>200k</td>
                <td>200,000</td>
                <td>200k</td>
            </tr><tr>
                <td>210k</td>
                <td>204,901 (-99)</td>
                <td>205k</td>
            </tr><tr>
                <td>300k</td>
                <td>244,907 (-5093)</td>
                <td>250k</td>
            </tr><tr>
                <td>500k</td>
                <td>311,971 (-29)</td>
                <td>312k</td>
            </tr><tr>
                <td>750k</td>
                <td>365,861 (+8861)</td>
                <td>357k</td>
            </tr><tr>
        </table>

		<h2>Other Updates</h2>
		<ul>
			<li>Added Kris[1] enchantment NPC named <span class="npcname">Kris Enchanter</span> in Prontera</li>
            <li>Added <span class="rocmd">@gospelbuffs</span> to enable/disable gospel buffs as you wish
                <div>
                    Usage: @gospelbuffs -1:14 7-9 10 (Disable all buffs, but enable buffs 7 to 9 and the 10th buff)
                </div>
            </li>
            <li>Fixed Telekinetic Orb and Alchemy Glove[1] to be wearable and also includes their new bonus scripts</li>
            <li>Also added new armor: Tidung[1]</li>
		</ul>
        <p>Added the following new headgears:</p>
        <ul>
            <li>Fancy Phantom Mask[1]</li>
            <li>Bone Hat[1]</li>
            <li>Ribbon Magician Hat[1]</li>
            <li>Entweihen Hairband[1]</li>
            <li>Angeling Fur Hat[1]</li>
            <li>Preschool Hat[1]</li>
            <li>Bandit Hat[1]</li>
        </ul>
	</div>
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
