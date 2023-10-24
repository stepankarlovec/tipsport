<?php
require_once 'bootstrap.php';

$TS = new Tipsport();
//$cat = $TS->getSportByName("Fotbal");
$cat = $TS->getCategoriesByName("Liga mistrů");

echo '<pre>';
var_dump($cat);
//$TS->getCompetitionMatches($cat[0]);

//$search = $TS->search("Sparta");
//$detail = $TS->getMatchDetails($search[0]["matchId"]);
//var_dump($TS->opportunityFilter($detail, 'opportunityName', 'Remíza'));
echo '</pre>';