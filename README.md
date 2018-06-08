# WWTC sdk

## getnewaddress
```
$wtc = new wwtc(); 
$wtc->setsecret('secret','apikey'); 
$address = $wtc->getnewaddress(); 

```
## listtransactions

```
$wtc = new wwtc(); 
$wtc->setsecret('secret','apikey'); 
$listtransactions = $wtc->listtransactions($limit,$offset);

```

## sendcoin

```
$wtc = new wwtc(); 
$wtc->setsecret('secret','apikey'); 
$send = $wtc->sendcoin('address here',amount here);

```

