<? 
	include("../inc/connection.inc"); 
  include("../inc/header2.php");	
?>

<?
	# extract the post data
	@extract($_POST);

	$username = trim($PHP_AUTH_USER);

	if ($changeclass == "yes") {
			$query = "UPDATE experience SET class = '$class' where username = '$username';";
			$result = pg_query($query);
	}
	if ($changeweapon == "yes") {
			$query = "UPDATE experience SET equipment = '$weapon' where username = '$username';";
			$result = pg_query($query);
	}


	$query = "SELECT username, points, class, equipment FROM experience ORDER BY points DESC;";
	$result = pg_query($query);
	?>
		<h3>Resnet TechCenter Experience Chart</h3><hr width="35%"><br>
		<table width="70%" valign="top">
			<tr>
				<td valign="top" align="center"><u>Username</u></td>
				<td valign="top" align="center"><u>Class</u></td>
				<td valign="top" align="center"><u>XP</u></td>
				<td valign="top" align="center"><u>Level</u></td>
				<td valign="top" align="center"><u>Equipping A:</u></td>
			</tr>
	<?
	while ($row = pg_fetch_assoc($result)) {

	$username = trim($row['username']);
	$points = trim($row['points']);
	$userclass = trim($row['class']);
	$equipment = trim($row['equipment']);
	
	?>
	<tr>
		<td valign="top" align="center"><? echo $username; ?></td>
		<td valign="top" align="center"><? echo $userclass; ?></td>
		<td valign="top" align="center"><? echo $points; ?></td>
	<?
		if ($points <= 50) {
			$level = "n00b";
		}
		elseif ($points > 50 && $points <= 150) {
			$level = "Acolyte";
		}
		elseif ($points > 150 && $points <= 350) {
			$level = "Swashbuckler";
		}
		elseif ($points > 350 && $points <= 700) {
			$level = "Veteran";
		}
		elseif ($points > 700 && $points <= 1300) {
			$level = "Priest";
		}
		elseif ($points > 1300 && $points <= 2000) {
			$level = "Lama";
		}
		elseif ($points > 2000 && $points <= 3000) {
			$level = "Neo Hacker";
		}
		elseif ($points > 2000 && $points <= 3000) {
			$level = "Magician Hacker";
		}
		elseif ($points > 3000 && $points <= 4500) {
			$level = "Enchanter Hacker";
		}
		elseif ($points > 4500 && $points <= 6500) {
			$level = "Warlock Hacker";
		}
		elseif ($points > 6500 && $points <= 10000) {
			$level = "Sorcerer Hacker";
		}
		elseif ($points > 10000 && $points <= 15000) {
			$level = "Wizard Hacker";
		}
		elseif ($points > 15000 && $points <= 22500) {
			$level = "Uber Master Hacker";
		}
		elseif ($points > 22500 && $points <= 30000) {
			$level = "Neuromancer";
		}
		elseif ($points > 30000 && $points <= 38000) {
			$level = "Ninja";
		}
		elseif ($points > 38000 && $points <= 50000) {
			$level = "Pirate";
		}
		
		if ($username == "mjghelp") {
			$level = "The Godfather";
		}
	?>	
		<td valign="top" align="center"><? echo $level; ?></td>
		<td valign="top" align="center"><? echo $equipment; ?></td>
	</tr>
	<?
	}
	?>
	</table>
	<table width="70%">
		<tr>
			<td>Change Your Class:</td>
			<td>
			<form enctype="multipart/form-data" method="post" action="experience.php">
			<input type="hidden" name="changeclass" value="yes">
				<select name="class">
							<option>Viking</option>
							<option>Mage</option>
							<option>Cleric</option>
							<option>Amazon</option>
							<option>Barbarian</option>
							<option>Warrior</option>
							<option>Ranger</option>
							<option>Necromancer</option>
							<option>Bard</option>
							<option>Alchemist</option>
							<option>Assassin</option>
							<option>Druid</option>
							<option>Enchanter</option>
							<option>Samurai</option>
							<option>Rogue</option>
							<option>Psionicist</option>
							<option>Monk</option>
							<option>Paladin</option>
							<option>Midget Warlord</option>
							<option>Elephant Shaman</option>
							<option>Beastmaster</option>
							<option>Ginger</option>
				</select>
			</td>
			<td>
				<input type="submit" name="submit" value="Change Class">
			</td>
		</tr>
		</form>
		<tr>
			<td>Change Your Equipped Weapon:</td>
			<td>
			<form enctype="multipart/form-data" method="post" action="experience.php">
			<input type="hidden" name="changeweapon" value="yes">
				<select name="weapon">
					<?
						$username = trim($PHP_AUTH_USER);
						$query = "SELECT points FROM experience WHERE username = '$username';";
						$result = pg_query($query);
						while ($row = pg_fetch_assoc($result)) {
							$points = trim($row['points']);

						if ($points > 0) {
							?>
							<option>+1 Weeping Willow Twig</option>
							<option>Metal Spoon</option>
							<option>-2 Butter Knife</option>
							<option>Sneaker of Throwing</option>
							<option>+1 Flannel Blanket</option>
							<option>Exodus Paper Clip</option>
							<?
						}
						if ($points > 50) {
							?>
							<option>Toothpick Blowgun</option>
							<option>+2 Pebble Sling</option>
							<option>-1 Rusty Cleaver</option> 
							<option>Plastic Kiddie Shovel of the Undead</option>
							<option>Shaolin Two-by-Four</option>
							<?
						}
						if ($points > 150) {
							?>
							<option>-1 Morning Star Mace</option>
							<option>Jagged Sickle</option>
							<option>Chipped Longsword</option>
							<option>+1 Copper Banded Club</option>
							<?
						}
						if ($points > 350) {
							?>
							<option>Spiked Flail</option>
							<option>+1 Long Bow</option>
							<option>+1 Serrated Scimitar</option>
							<?
						}
						if ($points > 700) {
							?>
							<option>+2 Dagger of Restricted Access</option>
							<option>+2 Battle Mace of Google Search</option>
							<option>+2 Battle Axe of Virus Cleaving</option>
							<option>+2 Footman's Lance of Botnet Tracking</option>
							<option>+2 Quarterstaff of Cleansing</option>
							<option>+2 Throwing Star of Security</option>
							<option>+2 Spiked Club of Data Eradication</option>
							<option>+2 Halberd of Newsgroup Seeking</option>
							<option>+2 Broadsword of Encryption</option>
							<?
						}
						if ($points > 1300) {
							?>
							<option>+3 Desert Eagle .50</option>
							<option>+3 Studded Mace of Coding Frenzy</option>
							<option>+3 Google Longbow of Data Mining</option>
							<option>+3 Warhammer of the Undead</option>
							<option>+3 Brute Force Cleaver</option>
							<?
						}
						if ($points > 2000) {
							?>
							<option></option>
							<?
						}
						if ($points > 3000) {
							?>
							<option></option>
							<?
						}
						if ($points > 4500) {
							?>
							<option>Chain Mace of Crushing Destruction</option>
							<option>+3 Staff of Spyware Disintegration</option>
							<option>+4 Rapier of Lightning Speed</option>
							<?
						}
						if ($points > 6500) {
							?>
							<option>Legendary Hammer of Scourging</option>
							<option>Resnet Halberd of Spyware Mastery</option>
							<option>Shining Lance of True Cleaning</option>
							<option>+9 Sword of Impending Darkness</option>
							<?
						}
						if ($points > 10000) {
							?>
							<option>+5 Resnet Sword of Smiting</option>
							<option>Staff of Ultimate Customer Satisfaction</option>
							<option>+7 Iron Crossbow of IPEdit Domination</option>
							<?
						}
						if ($points > 15000) {
							?>
							<option>Resnet Warhammer of Torrential Lightning</option>
							<option>+3 Mace of Impenetrable Security</option>
							<option>+9 Harpoon of Glory</option>
							<?
						}
						if ($points > 22500) {
							?>
							<option>Glowing Spear of Virus Eradication</option>
							<option>The Great Sword of the Internet</option>
							<option>Gigantic Battle Axe of Terror</option>
							<?
						}
						if ($points > 30000) {
							?>
							<option>Morning Star of Ninja Lightning Frenzy</option>
							<option>Demon Shuriken of Fearsome Darkness</option>
							<option>Katana of Hardware Purification</option>
							<?
						}
						if ($points > 38000) {
							?>
							<option>Giant Pirate Blade of Ultimate Cleansing</option>
							<option>Pirate Cannon of Atomic Destruction</option>
							<option>Pirate Scimitar of the Sun's Flame</option>
							<?
						}
						}
				?>
				</select>
			</td>
			<td>
				<input type="submit" name="submit" value="Change Equipped Weapon">
			</td>
		</tr>
		</form>
	</table>

<? 
# close database connection
pg_close($db_connect); ?>
					
