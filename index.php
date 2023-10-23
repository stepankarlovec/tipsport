<?php
require_once 'bootstrap.php';

$TS = new Tipsport();
$TS->topCompetitions();
$search = $TS->search("sparta");
var_dump($TS->getMatchDetails($search[0]["matchId"]));