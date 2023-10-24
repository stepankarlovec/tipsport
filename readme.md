# Tipsport.cz API - PHP class
Disclaimer: These are not official files from Tipsport, this piece of software uses free public data avaliable on the internet.

### What is implemented ###

- Fetching
  - Match data ✅
  - Competition data ✅
  - Analyzes ❌
  - (the data comes in pretty random not organized format)
- Communication
  - Login ❌ (soon)
  - Place bet ❌

#### Share, use, contribute, enjoy!

## Installation
``` 
composer require stepankarlovec/tipsport
```
## Usage

```php
<?php
// require your Tipsport.php class from somewhere
require_once 'bootstrap.php';

// initialize Tipsport class
$TS = new Tipsport();

// Get all matches which includes text "Sparta"
$search = $TS->search("Sparta");

// Get match details about the first one
$result = $TS->getMatchDetails($search[0]["matchId"]);

// Finds all opportunities which include opportunityName "Remíza"
$draw = $TS->opportunityFilter($detail, 'opportunityName', 'Remíza');

echo $draw[0]["opportunityName"] . ": " . $draw[0]["currentOdd"];
// Remíza: 3.75
```

## Documentation
#### Public methods
| Function name        | Description                         | Parameters                              |
|----------------------|-------------------------------------|-----------------------------------------|
| getCompetitions() | competitions                        | -                                       |
| getCompetitionsByName()| competitions by name                | string $searchValue                     |
| getSports()    | sports                              | -                                       | 
| getSportByName()             | sports by name                      | String $searchValue                     |
| topCompetitions()            | top competitions                    | -                                       |
| getOfferData()               | get offer data                      | -                                       |
| getCompetitionMatches()      | get competition matches             | int $competitionId                      |
| search()                     | search                              | String $searchValue                     |
| getMatchDetails()            | get match details                   | int $matchId                            |
| opportunityFilter()          | filter opportunities                | array $data, String $key, String $value |
