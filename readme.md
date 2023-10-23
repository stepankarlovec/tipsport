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

## Installation
``` 
just yoink the Class folder. packagist coming soon.
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

// dump the results
var_dump($result);
```

## Documentation
| Function name        | Description                         | Parameters                        |
|----------------------|-------------------------------------|-----------------------------------|
| getCompetitionData() | competition data                    | -                                 |
| getOfferData()       | offer data                          | int $competitionId, int $limit=75 |
| topCompetitions()    | top competitions                    | -                                 | 
| search()             | searches for a match                | String $searchText                |
| getMatchDetails()    | get match details                   | int $matchId                      |
| getCategories()      | get different categories/competions |                                   |