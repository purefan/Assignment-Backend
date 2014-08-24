<?php

	namespace ProspectEye\FetchVisits;
	use ProspectEye\Database as DB;
	use ProspectEye\Cache as Cache;
	use ProspectEye\Log as Log;
	use Exception;

	class FetchVisits {
	
		protected $_cache;
		protected $_db;

		protected $_filters;

		function __construct() {
			$this->_cache = new Cache\Cache();
			$this->_db = new DB\Database();
		}
		
		public function __set($name, $value){
			$this->_filters[$name] = $value;
		}

		/**
		*	Gets all visits per account id
		*	@param int $accountId the id of the account
		*	@return array
		*/
		function getVisits($accountId) {
			// Check that $accountId is a valid int
			if (filter_var($accountId, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1))) === false){
				throw new Exception("accountId must be an integer higher than zero", 1);				
			}

			$visits = $this->_cache->$accountId; 

			if ($visits === false){
				Log\Log::save('The accountId ' . $accountId . ' was not found in cache, fetching from DB');
				$visits = $this->_db->select('*')
					->from('visit')
					->where(array('AccountId = ' => $accountId))
					->get('allRows');
				$this->_cache->$accountId = $visits;
			}
			 else {
			 	Log\Log::save('Found the account id ' . $accountId . ' in cache');
			 }

			return $visits;
		}
	
	}
	
