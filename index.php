<?php

require ('Log.php');
require ('Cache.php');
require ('CacheJob.php');
require ('FetchVisits.php');
require ('DB.php');



$fetch = new ProspectEye\FetchVisits\FetchVisits();

// the first time that we search for the accountId 1 it will need to fetch it
// from the db
$visits = $fetch->getVisits(1);

// But the second time it will get it from cache. Cache expires every 5 mins.
$visits = $fetch->getVisits(1);

// Flush cache
$cache = new ProspectEye\Cache\Cache();
$result = $cache->flushCacheByAccountId(1);

$visits = $fetch->getVisits(1);

?>