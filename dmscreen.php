<?php
/**
 * This script creates a dm screen with information written in the jsons found in jsons/
 */
session_start();

// Scan the files found in jsons/ and list them for the user in the Campaigns dropdown
$path = 'jsons/';

$campaigns = scandir('jsons/');

unset($campaigns[0]);
unset($campaigns[1]);

natsort($campaigns);

$defaultCampaign = $campaigns[array_search('Example.json', $campaigns)];

if (isset($_SESSION['selectedCampaign']) == false) {
    $playersRaw = file_get_contents($path . $defaultCampaign);
} else {
    $selectedCampaign = $_SESSION['selectedCampaign'] . '.json';
    $playersRaw = file_get_contents($path . $selectedCampaign);
}

$players = json_decode($playersRaw, true);
$playersInit = [];

if (isset($_POST['submit1'])) {

    $newPlayers = [];

    foreach ($players as $player) {

        $playerId = array_search($players, $player);

        $player['AC'] = $_POST[$player['htmlName'] . 'AC'];
        $player['TouchAC'] = $_POST[$player['htmlName'] . 'TouchAC'];
        $player['Hp'] = $_POST[$player['htmlName'] . 'Hp'];
        $player['Damage'] = $_POST[$player['htmlName'] . 'Damage'];
        $player['Str'] = $_POST[$player['htmlName'] . 'Str'];
        $player['TmpStr'] = $_POST[$player['htmlName'] . 'TmpStr'];
        $player['Dex'] = $_POST[$player['htmlName'] . 'Dex'];
        $player['TmpDex'] = $_POST[$player['htmlName'] . 'TmpDex'];
        $player['Con'] = $_POST[$player['htmlName'] . 'Con'];
        $player['TmpCon'] = $_POST[$player['htmlName'] . 'TmpCon'];
        $player['Int'] = $_POST[$player['htmlName'] . 'Int'];
        $player['TmpInt'] = $_POST[$player['htmlName'] . 'TmpInt'];
        $player['Wis'] = $_POST[$player['htmlName'] . 'Wis'];
        $player['TmpWis'] = $_POST[$player['htmlName'] . 'TmpWis'];
        $player['Cha'] = $_POST[$player['htmlName'] . 'Cha'];
        $player['TmpCha'] = $_POST[$player['htmlName'] . 'TmpCha'];
        $player['Bab'] = $_POST[$player['htmlName'] . 'Bab'];
        $player['Melee'] = $_POST[$player['htmlName'] . 'Melee'];
        $player['Ranged'] = $_POST[$player['htmlName'] . 'Ranged'];
        $player['Fort'] = $_POST[$player['htmlName'] . 'Fort'];
        $player['Ref'] = $_POST[$player['htmlName'] . 'Ref'];
        $player['Will'] = $_POST[$player['htmlName'] . 'Will'];
        $player['Initiative'] = $_POST[$player['htmlName'] . 'Init'];
        $player['Gold'] = $_POST[$player['htmlName'] . 'Gold'];
        $player['Notes'] = $_POST[$player['htmlName'] . 'Notes'];
        $player['Exp'] = $_POST[$player['htmlName'] . 'Exp'];
        array_push($newPlayers, $player);
    }

    file_put_contents($path . $selectedCampaign, json_encode($newPlayers, JSON_PRETTY_PRINT));

    header("Location: http://dm.bigteddy.ro/dmscreen.php");
}

if (isset($_POST['create'])) {
    $newPlayer['name'] = $_POST['createName'];
    $newPlayer['htmlName'] = preg_replace('/\s+/', '_',$_POST['createName']);
    $newPlayer['AC'] = 1;
    $newPlayer['TouchAC'] = 1;
    $newPlayer['Hp'] = 1;
    $newPlayer['Damage'] = 1;
    $newPlayer['Str'] = 1;
    $newPlayer['TmpStr'] = 1;
    $newPlayer['Dex'] = 1;
    $newPlayer['TmpDex'] = 1;
    $newPlayer['Con'] = 1;
    $newPlayer['TmpCon'] = 1;
    $newPlayer['Int'] = 1;
    $newPlayer['TmpInt'] = 1;
    $newPlayer['Wis'] = 1;
    $newPlayer['TmpWis'] = 1;
    $newPlayer['Cha'] = 1;
    $newPlayer['TmpCha'] = 1;
    $newPlayer['Bab'] = 1;
    $newPlayer['Melee'] = 1;
    $newPlayer['Ranged'] = 1;
    $newPlayer['Fort'] = 1;
    $newPlayer['Ref'] = 1;
    $newPlayer['Will'] = 1;
    $newPlayer['Initiative'] = 1;
    $newPlayer['Gold'] = 1;
    $newPlayer['Notes'] = 1;
    $newPlayer['Exp'] = 1;
    array_push($players, $newPlayer);

    file_put_contents($path . $selectedCampaign, json_encode($players, JSON_PRETTY_PRINT));

    header("Location:http://dm.bigteddy.ro/dmscreen.php");
}

if (isset($_POST['remove'])) {

    $newPlayers = [];

    $toDelete = $_POST['removeName'];

    foreach ($players as $player) {

        if ($player['name'] === $toDelete) {
//            print_r($player);
            continue;
        }
        array_push($newPlayers, $player);
    }

    file_put_contents($path . $selectedCampaign, json_encode($newPlayers, JSON_PRETTY_PRINT));
    header("Location:http://dm.bigteddy.ro/dmscreen.php");
}

if (isset($_POST['createCampaignName'])) {
    $newFile = fopen('jsons/' . ucfirst($_POST['createCampaignName']) . '.json', 'w');
    fwrite($newFile, '[]');
    fclose($newFile);
    $_SESSION['selectedCampaign'] = $_POST['createCampaignName'];
    header("Location:http://dm.bigteddy.ro/dmscreen.php");
}

if (isset($_POST['selectedCampaign'])) {
    $_SESSION['selectedCampaign'] = $_POST['selectedCampaign'];
    header("Location:http://dm.bigteddy.ro/dmscreen.php");
}

?>


<html>
<head>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="mystyle.css">
    <link rel="icon" href="favicons/favicon.ico">
    <script
            src="https://code.jquery.com/jquery-3.2.1.js"
            integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
            crossorigin="anonymous"></script>
    <script src="https://unpkg.com/popper.js"></script>
    <script src="https://unpkg.com/tooltip.js"></script>
    <script src="js/bootstrap.js"></script>

    <script>
        var slashing = [
            "2x damage",
            "3x Damage",
            "Hand Slashed open, –1 to bab and AC",
            "Lose 1 finger from hand",
            "Lose 1d4 fingers, hand incapacitated",
            "Foot Slashed open, 1⁄2 move",
            "Foot Lose 1d2 toes, 1⁄2 move",
            "Leg Slashed open, 1⁄2 move",
            "Leg Removed at ankle, opponent falls",
            "Leg Removed at knee, opponent falls",
            "Leg Removed just below hip, opponent falls",
            "Wrist removed",
            "Elbow removed",
            "Arm removed just below shoulder",
            "Ripped open, guts hanging out, roll Fort or fall",
            "Ripped open, guts hanging out, stunned 1 round",
            "Ripped open, death",
            "Lung slashed, –1 to bab and AC",
            "Rib broken, stunned 1 round",
            "Chest slashed open, death",
            "Throat cut, no speech",
            "Chest slashed, opponent –2 to bab and AC",
            "Throat cut, death",
            "Eye removed, stunned 1 round",
            "Ear removed",
            "Nose Shattered",
            "1D6 Teeth shattered",
            "Decapitated, death"
        ];
        var bludgeoning = [
            "2x Damage",
            "2x damage, shield breaks",
            "3x Damage",
            "3x damage, shield breaks",
            "Hand Smashed, –1 to Bab and AC",
            "1d4 fingers broken, hand incapacitated",
            "Hand Broken and incapacitated",
            "Toe crushed, 1⁄2 move",
            "Foot smashed, 1⁄4 move",
            "Crushed thigh, fall, 1⁄2 move",
            "Broken knee, 1⁄4 move",
            "Broken hip bone, opponent falls, 1⁄4 move (shield)",
            "Broken shin, opponent falls, 1⁄4 move",
            "Broken wrist, drop item",
            "Broken, shoulder incapacitated, drop item",
            "Smashed guts, roll Fort or fall",
            "Crushed guts, stunned 1 round",
            "Pulped guts, Death",
            "Shoulder smashed, –1 to Bab and AC",
            "Rib Broken, stunned 1 round",
            "Rib cage broken, defender incapacitated",
            "Chest crushed, opponent –2 to Bab and AC",
            "Chest crushed, death",
            "Skull hit, stunned 1 round, lose 1d4 INT",
            "Skull hit, stunned 1 round, lose 2d4 INT",
            "Crushed Nose",
            "Crushed Teeth",
            "Skull Crushed, Death"
        ];

        var piercing = [
            "2x Damage",
            "2x damage, roll Ref or be knocked down",
            "3x Damage",
            "3x damage, roll Ref or be knocked down",
            "Punctured muscle, –1 to Bab and AC",
            "Muscle pierced, hand incapacitated",
            "Punctured muscle, 1/2 move",
            "Punctured thigh, roll Ref or fall",
            "Split knee, fall, 1⁄2 move",
            "Split knee, fall, 1⁄4 move",
            "Pierced wrist, –1 to Bab and AC",
            "Torn shoulder, –1 to Bab and AC",
            "Torn, shoulder incapacitated",
            "Punctured guts, roll Fort or fall",
            "Stabbed, death",
            "Lung pierced, –1 to Bab and AC",
            "Lung pierced, stunned 1 round",
            "Chest pierced, defender incapacitated",
            "Heart pierced, death",
            "Throat pierced, no speech",
            "Throat pierced, death",
            "Eye removed, helm removed",
            "Skull hit, stunned 1 round, lose 1-4 INT",
            "Skull pierced, death"
        ];
        var weaponFumbles = [
            "Hit an ally",
            "Weapon slips and hits ally",
            "Weapon slips and goes into enemy square",
            "Weapon slips and falls into your square",
            "You hit something very hard and the weapon breaks",
            "You hit something very hard, the weapon is damaged",
            "You hit yourself dealing half damage",
            "You hit yourself dealing full damage",
            "You hit yourself and crit",
            "Weapon slips and hits another enemy",
            "Weapon is unusable, it must be repaired",
            "You hit yourself and damage your armor",
            "You hit yourself and damage an item from your inventory",
            "If applicable, you hit a tree/plant, the tree/plant is now mad, comes to life and attacks you",
            "If applicable, you hit an object, that object is a mimic and now attacks you",
            "Your weapon slips and you break an objects around you",
            "Your weapon slips and you break an object from an ally's inventory",
            "Your weapon slips and sunders the armor/clothing of an ally",
            "You swing so hard that you destabilize yourself and fall prone",
            "If applicable, you accidentally hit an innocent creature/animal/civilian/child and kill it",
            "Your hit is so lousy that the enemies laugh at you, you feel bad and have a -1 to hit for this combat",
            "Your hit is so lousy that the enemies laugh at you, you feel bad and have a -2 to hit for this combat",
            "Your hit is so lousy that the enemies laugh at you, you feel bad and have a -3 to hit for this combat",
            "Your hit is so lousy that the enemies laugh at you, you feel bad and have a -4 to hit for this combat",
            "Your hit is so lousy that the enemies laugh at you, you feel bad and have a -5 to hit for this combat",
            "You hit yourself in the head and feint (Fort DC 20)",
            "You hit yourself in the head and get scared/frightened (Will DC 20)",
            "You hit yourself in the foot, you move at half speed for this combat",
            "Nothing bad happens",
            "You stretch your arms too much and they hurt really bad, you do half damage for 3 rounds",
            "You slip, Reflex DC 15 or fall",
            "Your weapon falls and is swallowed by the ground",
            "Your miss is so lousy it causes an attack of opportunity from every enemy near you",
            "Your miss bolsters the courage of your enemies giving them +1 to hit against you",
            "Your miss bolsters the courage of your enemies giving them +2 to hit against you",
            "Your miss bolsters the courage of your enemies giving them +3 to hit against you",
            "Your miss bolsters the courage of your enemies giving them +4 to hit against you",
            "Your miss bolsters the courage of your enemies giving them +5 to hit against you",
            "Your weapon slips in the air and dissappears",
            "Your weapon slips and is grabbed by an enemy",
            "You hit an ally with a crit",
            "You hit an ally dealing half damage",
            "You swirl your weapon around you, automatically hitting everyone around you"
        ];
        critsMaxSlash = slashing.length;
        critsMaxBludg = bludgeoning.length;
        critsMaxPierc = piercing.length;
        weaponFumblesMax = weaponFumbles.length;
    </script>

</head>
<title>DM Screen</title>
<body>


<div>

    <nav class="navbar navbar-expand-sm navbar-custom">
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse"
                data-target="#navbarCustom">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a href="http://dm.bigteddy.ro/dmscreen.php" class="navbar-brand">DM Screen</a>
        <div class="navbar-collapse collapse" id="navbarCustom">
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">
                        Campaign
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <form method="post" action="dmscreen.php">
                            <?php
                            foreach ($campaigns as $campaign) {
                                echo '<input type="submit" name="selectedCampaign" class="nav-link dropdown-item" value="' . str_replace('.json', '', $campaign) . '">';
                            }
                            ?>
                        </form>
                        <button class="dropdown-item"
                                onclick="document.getElementById('createCampaignModal').style.display = 'block'">Create
                            Campaign
                        </button>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">
                        Controls
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <button class="dropdown-item"
                                onclick="document.getElementById('creationModal').style.display = 'block'">Create
                            Character
                        </button>
                        <button class="dropdown-item"
                                onclick="document.getElementById('destroyModal').style.display = 'block'">Destroy
                            Character
                        </button>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">
                        Manuals
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="nav-link dropdown-item"
                           href="https://drive.google.com/open?id=0B2BXhrFHwVfSRVYzQU9EU04xLUE"
                           target="_blank">3.5 PHB</a>
                        <a class="nav-link dropdown-item"
                           href="https://drive.google.com/open?id=0B2BXhrFHwVfScjh1TnVTbmZBRFk"
                           target="_blank">3.5 PHB II</a>
                        <a class="nav-link dropdown-item"
                           href="https://drive.google.com/open?id=0B2BXhrFHwVfSNF9uQXRxQkQ0TlE"
                           target="_blank">3.5 Dungeon Master Guide</a>
                        <a class="nav-link dropdown-item"
                           href="https://drive.google.com/open?id=0B2BXhrFHwVfSclg4MDRZWFVjb2s"
                           target="_blank">3.5 Monster Manual I</a>
                        <a class="nav-link dropdown-item"
                           href="https://drive.google.com/open?id=0B2BXhrFHwVfSam5jbVhVcC1Md2M"
                           target="_blank">3.5 Monster Manual II</a>
                        <a class="nav-link dropdown-item"
                           href="https://drive.google.com/open?id=0B2BXhrFHwVfSeG9CdjNhUTZnM2s"
                           target="_blank">3.5 Monster Manual III</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">
                        D&D Tools
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="nav-link dropdown-item" href="https://inkarnate.com/maps#/new" target="_blank">Inkarnate
                            Maps</a>
                        <a class="nav-link dropdown-item" href="https://watabou.itch.io/medieval-fantasy-city-generator"
                           target="_blank">Medieval City Generator Watabou</a>
                        <a class="nav-link dropdown-item" href="http://whothefuckismydndcharacter.com/" target="_blank">Who
                            the fuck is my character?</a>
                        <a class="nav-link dropdown-item" href="http://www.dandwiki.com/wiki/3.5e_Diseases"
                           target="_blank">3.5 Diseases</a>
                        <a class="nav-link dropdown-item" href="http://www.dandwiki.com/wiki/SRD:Poisons#Table:_Poisons"
                           target="_blank">3.5 Poisons</a>
                        <a class="nav-link dropdown-item" href="http://www.d20srd.org/indexes/magicItems.htm"
                           target="_blank">3.5 Magical Items</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">
                        Fumbles
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <button class="dropdown-item"
                                onclick="alert(weaponFumbles[Math.floor(Math.random() * (weaponFumblesMax - 0 + 1))])">
                            Weapon Fumbles
                        </button>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">
                        Crits
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <button class="dropdown-item"
                                onclick="alert(slashing[Math.floor(Math.random() * (critsMaxSlash - 0 + 1))])">Shlashing
                        </button>
                        <button class="dropdown-item"
                                onclick="alert(bludgeoning[Math.floor(Math.random() * (critsMaxBludg - 0 + 1))])">
                            Bludgeoning
                        </button>
                        <button class="dropdown-item"
                                onclick="alert(piercing[Math.floor(Math.random() * (critsMaxPierc - 0 + 1))])">Piercing
                        </button>
                    </div>
                </li>
            </ul>
            <span class="ml-auto navbar-text"></span>
        </div>
    </nav>

    <?php

    if (isset($selectedCampaign) == false) {
        echo '<p style= "color: white; text-align: center; font-size: 100px; font-family: medieval">Please Select a campaign so I know where to save!</p>';
        echo '<p style= "color: white; text-align: center; font-size: 100px; font-family: medieval">Or create a campaign and then select it...</p>';
        echo '<p style= "color: white; text-align: center; font-size: 100px; font-family: medieval">The setup below is an example of how it looks</p>';
        foreach ($players as $player) {
            $playersInit[$player['htmlName']] = $player['Initiative'];
            arsort($playersInit);
        }
    }

    if (!empty($players)) {
        foreach ($players as $player) {
            $playersInit[$player['name']] = $player['Initiative'];
            arsort($playersInit);
        }
    } else {
        echo '<p style= "color: white; text-align: center; font-size: 100px; font-family: medieval">No players found, please add some</p>';
    }
    ?></div>
<div>
    <form id="characterControl" class="form-inline" method="post" action="dmscreen.php">

        <div class="content flex-container">
            <?php
            foreach ($players as $player) {
                $remainingHp = $player['Hp'] - $player['Damage'];
                echo "
<div class='char-container flex-container flex-wrap'>
    <h3 class='charName'>" . $player['name'] . "</h3>
    <h5 class='charStatus'></h5>
    
    <hr class='half-rule'>
    
    <label class='statLabel' for='" . $player['htmlName'] . "AC'>AC</label>
    <input type='text' name=" . $player['htmlName'] . 'AC' . " value='" . $player['AC'] . "' class=\"form-control input-group-sm-2 mainstats\" id=\"" . $player['htmlName'] . "AC\">
    
    <label class='statLabel' for='" . $player['htmlName'] . "TouchAC'>Touch</label>
    <input type='text' name=" . $player['htmlName'] . 'TouchAC' . " value='" . $player['TouchAC'] . "' class=\"form-control input-group-sm-2 mainstats\" id=\"" . $player['htmlName'] . "TouchAC\">
    
    <label class='statLabel' for='" . $player['htmlName'] . "Hp'>Full Hp</label>
    <input type='text' name=" . $player['htmlName'] . 'Hp' . " value='" . $player['Hp'] . "' class=\"form-control input-group-sm-2 mainstats\" id=\"" . $player['htmlName'] . "Hp\">
    
    <label class='statLabel' for='" . $player['htmlName'] . "Damage'>Dmg/Hp</label>
    <input type='text' name=" . $player['htmlName'] . 'Damage' . " value='" . array_sum(explode(" ", $player['Damage'])) . "' class=\"form-control input-group-sm-2 damagestats js-dmg\" id=\"" . $player['htmlName'] . "Damage\">
    <input type='text' name=" . $player['htmlName'] . 'RemainingHp' . " value='" . $remainingHp . "' class=\"form-control input-group-sm-2 damagestats \" id=\"" . $player['htmlName'] . "Damage\" readonly>
    
    <hr class='half-rule'>
    
    <label class='statLabel' for='" . $player['htmlName'] . "Str'>Str</label>
    <input type='text' name=" . $player['htmlName'] . 'Str' . " value='" . $player['Str'] . "' class=\"form-control input-group-sm-2 stats\" id=\"" . $player['htmlName'] . "Str\">
    <input type='text' name=" . $player['htmlName'] . 'TmpStr' . " value='" . $player['TmpStr'] . "' class=\"form-control input-group-sm-2 stats\" id=\"" . $player['htmlName'] . "TmpStr\">
    <hr class='stats-rule'>
    <label class='statLabel' for='" . $player['htmlName'] . "Dex'>Dex</label>
    <input type='text' name=" . $player['htmlName'] . 'Dex' . " value='" . $player['Dex'] . "' class=\"form-control input-group-sm-2 stats\" id=\"" . $player['htmlName'] . "Dex\">
    <input type='text' name=" . $player['htmlName'] . 'TmpDex' . " value='" . $player['TmpDex'] . "' class=\"form-control input-group-sm-2 stats\" id=\"" . $player['htmlName'] . "TmpDex\">
    <hr class='stats-rule'>
    <label class='statLabel' for='" . $player['htmlName'] . "Con'>Con</label>
    <input type='text' name=" . $player['htmlName'] . 'Con' . " value='" . $player['Con'] . "' class=\"form-control input-group-sm-2 stats\" id=\"" . $player['htmlName'] . "Con\">
    <input type='text' name=" . $player['htmlName'] . 'TmpCon' . " value='" . $player['TmpCon'] . "' class=\"form-control input-group-sm-2 stats\" id=\"" . $player['htmlName'] . "TmpCon\">
    <hr class='stats-rule'>
    <label class='statLabel' for='" . $player['htmlName'] . "Int'>Int</label>
    <input type='text' name=" . $player['htmlName'] . 'Int' . " value='" . $player['Int'] . "' class=\"form-control input-group-sm-2 stats\" id=\"" . $player['htmlName'] . "Int\">
    <input type='text' name=" . $player['htmlName'] . 'TmpInt' . " value='" . $player['TmpInt'] . "' class=\"form-control input-group-sm-2 stats\" id=\"" . $player['htmlName'] . "TmpInt\">
    <hr class='stats-rule'>
    <label class='statLabel' for='" . $player['htmlName'] . "Wis'>Wis</label>
    <input type='text' name=" . $player['htmlName'] . 'Wis' . " value='" . $player['Wis'] . "' class=\"form-control input-group-sm-2 stats\" id=\"" . $player['htmlName'] . "Wis\">
    <input type='text' name=" . $player['htmlName'] . 'TmpWis' . " value='" . $player['TmpWis'] . "' class=\"form-control input-group-sm-2 stats\" id=\"" . $player['htmlName'] . "TmpWis\">
    <hr class='stats-rule'>
    <label class='statLabel' for='" . $player['htmlName'] . "Cha'>Cha</label>
    <input type='text' name=" . $player['htmlName'] . 'Cha' . " value='" . $player['Cha'] . "' class=\"form-control input-group-sm-2 stats\" id=\"" . $player['htmlName'] . "Cha\">
    <input type='text' name=" . $player['htmlName'] . 'TmpCha' . " value='" . $player['TmpCha'] . "' class=\"form-control input-group-sm-2 stats\" id=\"" . $player['htmlName'] . "TmpCha\">
    
    <hr class='half-rule'>
    
    <label class='statLabel' for='" . $player['htmlName'] . "Fort'>Fort</label>
    <input type='text' name=" . $player['htmlName'] . 'Fort' . " value='" . $player['Fort'] . "' class=\"form-control input-group-sm-2 saves\" id=\"" . $player['htmlName'] . "Fort\">
    <hr class='stats-rule'>
    <label class='statLabel' for='" . $player['htmlName'] . "Ref'>Ref</label>
    <input type='text' name=" . $player['htmlName'] . 'Ref' . " value='" . $player['Ref'] . "' class=\"form-control input-group-sm-2 saves\" id=\"" . $player['htmlName'] . "Ref\">
    <hr class='stats-rule'>
    <label class='statLabel' for='" . $player['htmlName'] . "Will'>Will</label>
    <input type='text' name=" . $player['htmlName'] . 'Will' . " value='" . $player['Will'] . "' class=\"form-control input-group-sm-2 saves\" id=\"" . $player['htmlName'] . "Will\">
    
    <hr class='half-rule'>
    
    <label class='statLabel' for='" . $player['htmlName'] . "Bab'>Bab</label>
    <input type='text' name=" . $player['htmlName'] . 'Bab' . " value='" . $player['Bab'] . "' class=\"form-control input-group-sm-2 bab\" id=\"" . $player['htmlName'] . "Bab\">
    
    <label class='statLabel' for='" . $player['htmlName'] . "Melee'>Melee</label>
    <input type='text' name=" . $player['htmlName'] . 'Melee' . " value='" . $player['Melee'] . "' class=\"form-control input-group-sm-2 bab\" id=\"" . $player['htmlName'] . "Melee\">
    
    <label class='statLabel' for='" . $player['htmlName'] . "Ranged'>Ranged</label>
    <input type='text' name=" . $player['htmlName'] . 'Ranged' . " value='" . $player['Ranged'] . "' class=\"form-control input-group-sm-2 bab\" id=\"" . $player['htmlName'] . "Ranged\">
    
    <label class='statLabel' for='" . $player['htmlName'] . "Notes'>Notes</label>
    <textarea name=" . $player['htmlName'] . 'Notes' . " class=\"form-control input-group-sm-2 notes\" id=\"" . $player['htmlName'] . "notes\" >" . $player['Notes'] . "</textarea>
    </div>
    ";
            }
            ?>
        </div>
        <hr class="half-rule" style="background-color: green">
        <div class="ini-container flex-container flex-wrap">
            <h3 class='charName' style="color: forestgreen;">Initiative</h3>
            <hr class="half-rule">
            <?php
            foreach ($playersInit as $player => $init) {
                echo "
            <label class='statLabel' for='" . $player . "'>" . $player . "</label>
    <input type='text' name=" . preg_replace('/\s+/', '_', $player) . 'Init' . " value='" . $init . "' class=\"form-control input-group-sm-2 stats\" id=\"Initiative\">
            <hr class='stats-rule'>
            ";
            }
            ?>
        </div>

        <div class="gold-container flex-container flex-wrap">
            <h3 class='charName' style="color: gold;">Gold</h3>
            <hr class="half-rule">
            <?php
            foreach ($players as $player) {
                echo "
            <label class='statLabel' for='" . $player['htmlName'] . "'>" . $player['name'] . "</label>
    <input type='text' name=" . $player['htmlName'] . 'Gold' . " value='" . array_sum(explode(" ", $player['Gold'])) . "' class=\"form-control input-group-sm-2 stats\" id=\"Gold\">
            <hr class='stats-rule'>
            ";
            }
            ?>
        </div>

        <div class="xp-container flex-container flex-wrap">
            <h3 class='charName' style="color: cornflowerblue;">XP</h3>
            <hr class="half-rule">
            <?php
            foreach ($players as $player) {
                echo "
            <label class='statLabel' for='" . $player['htmlName'] . "'>" . $player['name'] . "</label>
    <input type='text' name=" . $player['htmlName'] . 'Exp' . " value='" . array_sum(explode(" ", $player['Exp'])) . "' class=\"form-control input-group-sm-2 stats\" id=\"Xp\">
            <hr class='stats-rule'>
            ";
            }
            ?>
        </div>

        <hr class="half-rule">

        <div class="btn-container flex-container">
            <input type="submit" value="Save" name="submit1" class="btn-success" id="butondemuie">
        </div>
    </form>
    <form method="post" action="dmscreen.php">
        <div id="creationModal" class="modal">

            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" id="closeMod"
                            onclick="document.getElementById('creationModal').style.display = 'none'">x
                    </button>
                </div>
                <hr class="half-rule" style="background-color: green">
                <p style="font-family: medieval; font-size: 20px">Enter the name of the new character</p>
                <label class='statLabel' for="createName"></label>
                <input type='text' name="createName" placeholder="Char Name" class="form-control input-group-sm-2"
                       id="inputDeCreat" autocomplete="off" required pattern="[A-Za-z ]{1,30}">
                <hr class="half-rule" style="background-color: green">
                <input type="submit" value="Go ->" name="create" class="btn-success" id="butondecreat">
            </div>

        </div>
    </form>

    <form method="post" action="dmscreen.php">

        <div id="destroyModal" class="modal">

            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" id="closeMod"
                            onclick="document.getElementById('destroyModal').style.display = 'none'">x
                    </button>
                </div>
                <hr class="half-rule" style="background-color: green">
                <p style="font-family: medieval; font-size: 25px">Enter the name of the character that you wish to destroy</p>
                <p style="font-family: medieval; font-size: 25px">Note that it must be case sensitive</p>
                <label class='statLabel' for="createName"></label>
                <input type='text' name="removeName" placeholder="Char Name" class="form-control input-group-sm-2"
                       id="inputDeSters" autocomplete="off" >
                <hr class="half-rule" style="background-color: green">
                <input type="submit" value="Remove ->" name="remove" class="btn-danger" id="butondesters">
            </div>

        </div>
    </form>

    <form method="post" action="dmscreen.php">

        <div id="createCampaignModal" class="modal">

            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" id="closeMod"
                            onclick="document.getElementById('createCampaignModal').style.display = 'none'">x
                    </button>
                </div>
                <hr class="half-rule" style="background-color: green">
                <label class='statLabel' for="createName"></label>
                <p style="font-family: medieval; font-size: 20px">Please only use letters</p>
                <p style="font-family: medieval; font-size: 20px">Maximum name lenght is 50 characters</p>
                <input type='text' name="createCampaignName" placeholder="Campaign Name"
                       class="form-control input-group-sm-2"
                       id="inputDeCreatCampanie" autocomplete="off" required
                       style="text-transform: capitalize; width: 100%" pattern="[A-Za-z ]{1,30}">
                <hr class="half-rule" style="background-color: green">
                <input type="submit" value="Create ->" name="createCampaign" class="btn-danger"
                       id="createCampaignButton">
            </div>

        </div>
    </form>

    <footer class="footer">
        <div class="container footerText">
            <span class="footerText">Made by Lithiil! Contact me at tudor.palade@bigteddy.ro</span>
        </div>
    </footer>

</body>

<script>
    var statuses = ['-Barelly Wounded-', '-Wounded-', '-Badly Wounded-', '-Unconscious-', '-Dying-', '-Dead-'];

    Array.prototype.sum = function () {
        var sum = 0;
        for (var i = 0; i < this.length; i += 1)
            sum += +this[i];

        return sum;
    };

    function calcHp() {
        var playerName = $('.char-container h3');
        var playerStatus = $('.char-container h5');
        var sumDICK = 0, currentHP, maxHP, dmg;
        var pCurrentHP;
        for (var i = 0; i < playerName.length; i += 1) {
            var player = playerName[i].textContent.replace(/ /g,"_");

//set remaining HP
            sumDICK = document.getElementsByName(player + 'Damage')[0].value.split(" ").sum();
            currentHP = document.getElementsByName(player + 'RemainingHp')[0];
            maxHP = document.getElementsByName(player + 'Hp')[0];
            dmg = document.getElementsByName(player + 'Damage')[0];
            currentHP.value = maxHP.value - sumDICK;
            dmg.value = sumDICK;

//set status bar

            pCurrentHP = currentHP.value * 100 / maxHP.value;
            if (pCurrentHP >= 100)
                playerStatus[i].textContent = 'Healthy', playerStatus[i].style.color = 'green';
            else if (pCurrentHP < 100 && pCurrentHP > 74)
                playerStatus[i].textContent = statuses[0], playerStatus[i].style.color = 'darkgreen';
            else if (pCurrentHP < 74 && pCurrentHP > 26)
                playerStatus[i].textContent = statuses[1], playerStatus[i].style.color = 'orange';
            else if (pCurrentHP <= 25 && pCurrentHP > 0)
                playerStatus[i].textContent = statuses[2], playerStatus[i].style.color = 'darkorange';
            else if (+currentHP.value === 0)
                playerStatus[i].textContent = statuses[3], playerStatus[i].style.color = 'darkred';
            else if (+currentHP.value < 0 && +currentHP.value >= -10)
                playerStatus[i].textContent = statuses[4], playerStatus[i].style.color = 'red';
            else if (+currentHP.value < -10)
                playerStatus[i].textContent = statuses[5], playerStatus[i].style.color = 'purple';
        }
    }

    $(".js-dmg").on("change", function (ev) {
        calcHp();
    });

    window.onload = calcHp();

</script>

</html>
