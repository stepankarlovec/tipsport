<?php
require_once 'bootstrap.php';

$TS = new Tipsport();
//$cat = $TS->getSportByName("Fotbal");
//$cat = $TS->getCompetitionsByName("Liga mistrů");

echo '<pre>';
//var_dump($cat);
//$TS->getCompetitionMatches($cat[0]);

$search = $TS->search("Sparta");
$detail = $TS->getMatchDetails($search[0]["matchId"]);
// filters opportunities from detail of the match
$draw = $TS->opportunityFilter($detail, 'opportunityName', 'Remíza');
echo $draw[0]["eventName"] . " " . $draw[0]["opportunityName"] . ": " . $draw[0]["currentOdd"] . "\n";
// Výsledek zápasu Remíza: 3.75

echo '</pre>';