<?php
	require ('DB.php');
	require ('Cache.php');
	require ('Log.php');

	use ProspectEye\Database as DB;
	use ProspectEye\Cache as Cache;

	// Keeps the log from printing information 
	define('PHPUNIT', true);
	
	class VisitsTest extends PHPUnit_Framework_TestCase
	{
		/**
		*	Tests a simple select from the database
		*/
	    public function testDatabaseSimpleSelect()
	    {
	    	$db = new DB\Database();
	        
	        $test1 = $db->select('1=1')->get('field');
	        
	        $this->assertEquals(1, $test1);
	    }

	    /**
	    *	Tests getting and setting values to Memcached
	    */
	    public function testCacheSimpleSet(){
	    	$cache = new Cache\Cache();
	    	
	    	// test getting a non existent key
	    	$nonExistent = $cache->notFound;	    	
	    	$this->assertEquals(false, $nonExistent);

	    	// test assigning a value
	    	$cache->newValue = true;
	    	$this->assertTrue($cache->newValue);
	    }

	    public function testCacheFlushKey(){
	    	$cache = new Cache\Cache();
	    	$accountId = 55;

	    	// Initialize the accountId
	    	$cache->$accountId = 'Some value';
	    	
	    	// Make sure the value was set
	    	$this->assertEquals('Some value', $cache->$accountId);

	    	// Expire the cache
	    	$result = $cache->flushCacheByAccountId($accountId);

	    	// Check that the value is no longer available
	    	$this->assertEquals(false, $cache->$accountId);
	    }
	}
?>