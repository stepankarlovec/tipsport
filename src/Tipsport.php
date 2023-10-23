<?php
namespace Stepankarlovec\Tipsport;

use Exception;

class Tipsport
{

    private string $JSESSION;

    private string $cookies;

    public function __construct()
    {
        $this->getToken();
    }

    public function __call($name, $arguments) {
        try {
            if (method_exists($this, $name)) {
                return call_user_func_array([$this, $name], $arguments);
            } else {
                throw new Exception("Method $name does not exist !");
            }
        } catch (Exception $e) {
            echo "Exception: " . $e->getMessage();
        }
    }


    private function getToken():void
    {
        $reqone = new Request("https://www.tipsport.cz/", "GET", "", [], true);
        $reqone = $reqone->execute();
        if($reqone) {
            preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $reqone, $matches);
            $cookies = array();
            foreach ($matches[1] as $item) {
                parse_str($item, $cookie);
                $cookies = array_merge($cookies, $cookie);
            }
            $this->JSESSION = $cookies["JSESSIONID"];
            $this->cookies = "Cookie:JSESSIONID=" . $this->JSESSION . ";";
        }else{
            throw new Exception("Get request doesn't return anything. Cannot get JSESSIONID.", 500);
        }
    }

    public function search($searchText):mixed
    {
        $r = new Request("https://www.tipsport.cz/rest/offer/v2/search?searchText=" . $searchText . "&includePrematch=true&includeResults=false", "GET", "", [$this->cookies]);
        $res = $r->executeAndParse();
        return $res["results"];
    }

    public function getMatchDetails(int $matchId): mixed
    {
        $r = new Request("https://www.tipsport.cz/rest/offer/v3/matches/" . $matchId . "/communityStats?withOpportunitiesStats=true&withAnalysesStats=false&withTicketsStats=false&withMatchForumData=false&withMilestones=false&fromResults=false", "GET", "", [$this->cookies]);
        return $r->executeAndParse();
    }

    public function getCategories():mixed
    {
        $r = new Request("https://www.tipsport.cz/rest/offer/v4/sports", "GET", "", [$this->cookies]);
        return $r->executeAndParse();
    }

    public function topCompetitions()
    {
        $r = new Request("https://www.tipsport.cz/rest/offer/v1/competitions/top", "GET", "", [$this->cookies]);
        $res = $r->executeAndParse();
        var_dump($res);
    }

    // https://www.tipsport.cz/rest/offer/v1/competitions/top top competitions
    public function getOfferData(int $competitionId, int $limit=75): mixed
    {
        $r = new Request("https://www.tipsport.cz/rest/offer/v2/offer", "POST", json_encode([
            "results" => false,
            "highlightAnyTime" => false,
            "limit" => $limit,
            "type" => "SUPERSPORT",
            "id" => $competitionId,
            "fulltexts" => [],
            "matchIds" => [],
            "matchViewFilters" => [],
        ]),
            ['Content-Type:application/json',
                $this->cookies], false);
        return $r->executeAndParse();
    }

    //https://www.tipsport.cz/rest/offer/v3/sports/COMPETITION/5300/matches?fromResults=false
    public function getCompetitionData()
    {
        $r = new Request("https://www.tipsport.cz/rest/offer/v1/competitions/top", "GET", "", [$this->cookies]);
        $res = $r->executeAndParse();
        var_dump($res);
    }
}