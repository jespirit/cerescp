<?php

session_start();
include_once 'config.php'; // loads config variables
include_once 'query.php'; // imports queries
include_once 'functions.php';

$newitem = "[<span class='newitem'>NEW</span>]";

$items = array(
	"consume"=>array(
		array("$newitem Guarana Candy", 12414),
		array("Reduced weight of Grape Juice to 0.5", 533),
		array("Increased the effectiveness of Piece of Cake", 539),
		array("Reduced weight of Condensed White Potion to 1", 547),
	),
	"equipment"=>array(
		array("$newitem Deviruchi Headphones", 18653),
		array("$newitem Poker Card in Mouth", 18750),
		array("$newitem Elven Bow[1], Elven Arrow, Elven Arrow Quiver", 1747, 1773, 20157),
		array("Ice Pick, Ice Pick[1] does -30% less to Demi-Humans, Guardians, and Emperium", 1230, 13017),
		array("Lord Kaho's Horns", 5013),
		array("Pussy Cat Bell", 5051),
	),
	"cards"=>array(
		array("Alligator Card", 4252),
		array("Assassin Cross Card", 4359),
		array("Bloody Knight Card", 4320),
		array("Dark Illusion Card", 4169),
		array("Dark Lord Card", 4168),
		array("Doppelganger Card", 4142),
		array("Dracula Card", 4134),
		array("Eddga Card", 4123),
		array("Evil Snake Lord Card", 4330),
		array("Gargoyle Card", 4149),
		array("General Egnigem Cenia Card", 4352),
		array("Gloom Under Night Card", 4408),
		array("Gold Queen Scaraba Card", 4509),
		array("Gryphon Card", 4163),
		array("High Priest Card", 4363),
		array("High Wizard Card", 4365),
		array("Ifrit Card", 4430),
		array("Kiel D-01 Card", 4403),
		array("Ktullanux Card", 4419),
		array("Lady Tanee Card", 4376),
		array("Lord Knight Card", 4357),
		array("Lord of the Dead", 4276),
		array("Maya Card", 4146),
		array("Mysteltainn Card", 4207),
		array("Queen Scaraba Card", 4507),
		array("RSX-0806 Card", 4342),
		array("Samurai Card", 4263),
		array("Sniper Card", 4367),
		array("Stem Worm Card", 4224),
		array("Sting Card", 4226),
		array("Stormy Knight Card", 4318),
		array("Thanatos Card", 4399),
		array("Toad Card", 4306),
		array("Turtle General Card", 4305),
		array("Valkyrie Randgris Card", 4407),
		array("Vesper Card", 4374),
		array("Whitesmith Card", 4361),

	),
);

caption('Customizations');

function expand($ids) {
	$span = "
	<span
		class='link expand'
		onClick=\"this.style.visibility='hidden'; $(this).siblings('span.hide').css('visibility', 'visible');
			$(this).siblings('div').css('display', 'block'); ";
    //  if (fetch_from_itemdb[id] === undefined) {
    //      fetch_from_itemdb[id] = 1; // set
    //      link_ajax(id, div);
    //  }
    $span .= "if (fetch_from_itemdb[". $ids[1] ."] === undefined) { fetch_from_itemdb[". $ids[1] ."] = 1; ";
	for ($x=1; $x<count($ids); $x++) {
		$span .= "LINK_ajax('item.php?id=".$ids[$x]."', 'item_".$ids[$x]."');";
	}
	$span .= "}\">
		&gt;&gt;
	</span>";
	return $span;
}

function hide($id=0) {
	$span = "
	<span
		class='link hide'
		onClick=\"this.style.visibility='hidden'; $(this).siblings('span.expand').css('visibility', 'visible');
			$(this).siblings('div').css('display', 'none');\"
	>
		&lt;&lt;
	</span>";
	return $span;
}

function writegroup($h1, $group) {
	echo "<h1>$h1</h1>";
	
	foreach ($group as $line) {
		echo '<ul>
			  <li>'.$line[0];
		
		echo expand($line) . hide();
		
		for ($x=1; $x<count($line); $x++) {
			echo '<div class="item" id="item_'.$line[$x].'"></div>';
		}
		echo '</li></ul>';
	}
}

echo '
<div>
<script type="text/javascript">
// TODO: Alternative to using a global variable.
var fetch_from_itemdb = new Array();
</script>';

foreach ($items as $key=>$arr) {
	writegroup($key, $arr);
}

echo '
</div>';
fim();

?>
