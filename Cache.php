<?php

	namespace ProspectEye\Cache;
	use Memcached;
	use ProspectEye\Log as Log;
	use Exception;

	class Cache {
		
		private $_memcachedConn;	

		/**
		*	Initializes the connection to memcached 
		*	@throws warning|error if can't connect to memcache
		*	@return true on success
		*/
		function __construct() {
			$this->_memcachedConn = new Memcached;
			$this->_memcachedConn->addServer('localhost', 11211);
			return true;
		}
		
		/**
		*	Gets a value from Memcached
		*	@return array on success | false if the key does not exist in memcache
		*/
		public function __get($name){
			$value = $this->_memcachedConn->get($name);
			Log\Log::save('Fetching from Memcached the key "' . $name . '"');
			$emptyValueCodes = [
				14, // not stored
				16 // not found
			];
			if ( in_array($this->_memcachedConn->getResultCode(), $emptyValueCodes) ){
				return false;
			}
			return json_decode($value, true);
		}

		/**
		*	Stores a value in Memcached, overwriting a previous value if it exists
		*	@throws exception on error
		*/
		public function __set($name, $values){
			$value = json_encode($values);
			$expire = 60 * 5; // 5 minutes
			$success = $this->_memcachedConn->set($name, $value, $expire);
			if ($success !== true){
				throw new Exception("Could not add a value to Memcached", 1);				
			}
			Log\Log::save('Stored ' . count($values) . ' records under the key "' . $name . '" in Memcached');
		}

		/**
		*	Deletes the key from Memcached
		*	@throws exception on error
		*	@return true on success, false on error
		*/
		function flushCacheByAccountId($accountId) {
			// Check that $accountId is a valid int
			if (filter_var($accountId, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1))) === false){
				throw new Exception("accountId must be an integer higher than zero", 1);				
			}

			$result = $this->_memcachedConn->delete($accountId);
			if ($result === true){
				return true;
			}

			throw new Exception("Error deleting a key from Memcached: " . $this->_memcachedConn->getResultMessage(), 1);
			
		}
		
		/**
		*	@see __get($name)
		*/
		function getCachedVisitsByAccountId($accountId) {
			// Check that $accountId is a valid int
			if (filter_var($accountId, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1))) === false){
				throw new Exception("accountId must be an integer higher than zero", 1);				
			}
			return $this->__get($accountId);
		}
		
		function getUncachedVisitsByAccountId($accountId) {
		
		}
	}

?>
