<?php
/**
 * Tipsport API communication
 * https://github.com/stepankarlovec/tipsport
 *
 * feel free to contribute <3
 */
class Tipsport
{
    private string $JSESSION;

    private string $cookies;

    /**
     * @throws Exception
     */
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


    /**
     * @throws Exception
     */
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

    /**
     * Filters the matchResult, finds and returns the opportunity
     * @param mixed $result
     * @param string $field
     * @param string $value
     * @return array
     */
    public function opportunityFilter(mixed $result, string $field, string $value):array{
        $filteredOpportunities = [];

        if ($field === 'opportunityName') {
            foreach ($result as $key => $data) {
                if (is_array($data)) {
                    // Recursively search within sub-arrays
                    $subFilteredOpportunities = $this->opportunityFilter($data, $field, $value);
                    if (!empty($subFilteredOpportunities)) {
                        $filteredOpportunities = array_merge($filteredOpportunities, $subFilteredOpportunities);
                    }
                } else {
                    if ($key === $field && strpos($data, $value) !== false) {
                        // If the value is found in the opportunityName, consider it a match
                        $filteredOpportunities[] = $result;
                        break;  // Remove this line if you want to continue searching for more matches
                    }
                }
            }
        }

        return $filteredOpportunities;
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

    public function getCompetitions():mixed
    {
        $sports = $this->getSports();
        return $this->findCompetitions($sports);
    }

    public function getCompetitionsByName(string $searchValue):mixed
    {
        $sports = $this->getSports();
        $res = $this->findCompetitions($sports);
        return $this->findByTitle($res, $searchValue);
    }


    public function getSports():mixed
    {
        $r = new Request("https://www.tipsport.cz/rest/offer/v4/sports", "GET", "", [$this->cookies]);
        $data = $r->executeAndParse()["data"]["children"];
        return array_merge($data[0]["children"], $data[1]["children"]);
    }

    public function getSportByName(string $searchValue):mixed
    {
        $r = new Request("https://www.tipsport.cz/rest/offer/v4/sports", "GET", "", [$this->cookies]);
        $data = $r->executeAndParse()["data"]["children"];
        $finalArr = array_merge($data[0]["children"], $data[1]["children"]);
        return $this->findByTitle($finalArr, $searchValue);
    }

    public function topCompetitions():mixed
    {
        $r = new Request("https://www.tipsport.cz/rest/offer/v1/competitions/top", "GET", "", [$this->cookies]);
        return $r->executeAndParse();
    }

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

    // get competition matches
    //https://www.tipsport.cz/rest/offer/v3/sports/COMPETITION/5300/matches?fromResults=false
    public function getCompetitionMatches(int $competitionId):mixed
    {
        $r = new Request("https://www.tipsport.cz/rest/offer/v3/sports/COMPETITION/".$competitionId."/matches?fromResults=false", "GET", "", [$this->cookies]);
        return $r->executeAndParse();
    }


    // searches in array based on title
    private function findByTitle(array $array, string $searchValue):mixed{
        foreach ($array as $item) {
            if (stripos($item['title'], $searchValue) !== false) {
                $foundItems[] = $item;
            }
        }
        if (!empty($foundItems)) {
            return $foundItems;
        } else {
            throw new Exception("Category with this search value cannot be found");
        }
    }

    // recursively return all the field which have type=="competition"
    private function findCompetitions($data): array
    {
        $result = [];

        foreach ($data as $item) {
            if (isset($item['type']) && $item['type'] === 'COMPETITION') {
                $result[] = $item;
            }

            if (isset($item['children']) && is_array($item['children'])) {
                $result = array_merge($result, $this->findCompetitions($item['children']));
            }
        }
        return $result;
    }
}