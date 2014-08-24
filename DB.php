<?php

	namespace ProspectEye\Database;
	
	use PDO;
	use ProspectEye\Log as Log;
	
	
	
	class Database {

		private $_dbConn, $_select, $_from, $_where;

		public function __construct(){
			$this->_dbConn = null;
		}

		/** 
		*	Since we dont always need to connect to the database this is left out of the constructor
		*	@throws PDOException on connection error
		*	@return true on success
		*/
		private function _connectToMysql(){
			if ($this->_dbConn != null){
				return true;
			}
			$dsn = 'mysql:dbname=ProspectEye;host=127.0.0.1';
			$user = 'root';
			$password = 'mike5567';

			$this->_dbConn = new PDO($dsn, $user, $password);
			$this->_dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

			return true;
		}

		/**
		*	Populates the table `visit` with random data
		*	@return true on success
		*	@throws PDOException
		*/
		public function populateDB(){
			// used to determine the earliest visit. 1 Month
			$visitRange = time() - (60*60*24*30);

			// How many entries to add?
			$totalVisits = rand(25000,50000);

			// List of country codes
			$countries = ['AF','AX','AL','DZ','AS','AD','AO','AI','AQ','AG','AR','AM','AW','AU','AT','AZ','BS','BH','BD','BB','BY','BE','BZ','BJ','BM','BT','BO','BA','BW','BV','BR','IO','BN','BG','BF','BI','KH','CM','CA','CV','KY','CF','TD','CL','CN','CX','CC','CO','KM','CG','CD','CK','CR','CI','HR','CU','CY','CZ','DK','DJ','DM','DO','EC','EG','SV','GQ','ER','EE','ET','FK','FO','FJ','FI','FR','GF','PF','TF','GA','GM','GE','DE','GH','GI','GR','GL','GD','GP','GU','GT','GG','GN','GW','GY','HT','HM','VA','HN','HK','HU','IS','IN','ID','IR','IQ','IE','IM','IL','IT','JM','JP','JE','JO','KZ','KE','KI','KP','KR','KW','KG','LA','LV','LB','LS','LR','LY','LI','LT','LU','MO','MK','MG','MW','MY','MV','ML','MT','MH','MQ','MR','MU','YT','MX','FM','MD','MC','MN','ME','MS','MA','MZ','MM','NA','NR','NP','NL','AN','NC','NZ','NI','NE','NG','NU','NF','MP','NO','OM','PK','PW','PS','PA','PG','PY','PE','PH','PN','PL','PT','PR','QA','RE','RO','RU','RW','BL','SH','KN','LC','MF','PM','VC','WS','SM','ST','SA','SN','RS','SC','SL','SG','SK','SI','SB','SO','ZA','GS','ES','LK','SD','SR','SJ','SZ','SE','CH','SY','TW','TJ','TZ','TH','TL','TG','TK','TO','TT','TN','TR','TM','TC','TV','UG','UA','AE','GB','US','UM','UY','UZ','VU','VE','VN','VG','VI','WF','EH','YE','ZM','ZW'];
			$cities = ['Oranjestad','Kabul','Luanda','South Hill','Tirana','Andorra la Vella','Willemstad','Dubai','Buenos Aires','Yerevan','Tafuna','Saint JohnÂ´s','Sydney','Wien','Baku','Bujumbura','Antwerpen','Cotonou','Ouagadougou','Dhaka','Sofija','al-Manama','Nassau','Sarajevo','Minsk','Belize City','Saint George','Santa Cruz de la Sierra','SÃ£o Paulo','Bridgetown','Bandar Seri Begawan','Thimphu','Gaborone','Bangui','MontrÃ©al','Bantam','ZÃ¼rich','Santiago de Chile','Shanghai','Abidjan','Douala','Kinshasa','Brazzaville','Avarua','SantafÃ© de BogotÃ¡','Moroni','Praia','San JosÃ©','La Habana','Flying Fish Cove','George Town','Nicosia','Praha','Berlin','Djibouti','Roseau','KÃ¸benhavn','Santo Domingo de GuzmÃ¡n','Alger','Guayaquil','Cairo','Asmara','El-AaiÃºn','Madrid','Tallinn','Addis Abeba','Helsinki [Helsingfors]','Suva','Stanley','Paris','TÃ³rshavn','Weno','Libreville','London','Tbilisi','Accra','Gibraltar','Conakry','Les Abymes','Serekunda','Bissau','Malabo','Athenai','Saint GeorgeÂ´s','Nuuk','Ciudad de Guatemala','Cayenne','Tamuning','Georgetown','Kowloon and New Kowloon','Tegucigalpa','Zagreb','Port-au-Prince','Budapest','Jakarta','Mumbai (Bombay)','Dublin','Teheran','Baghdad','ReykjavÃ­k','Jerusalem','Roma','Spanish Town','Amman','Tokyo','Almaty','Nairobi','Bishkek','Phnom Penh','Bikenibeu','Basseterre','Seoul','al-Salimiya','Vientiane','Beirut','Monrovia','Tripoli','Castries','Schaan','Colombo','Maseru','Vilnius','Luxembourg [Luxemburg/LÃ«tzebuerg]','Riga','Macao','Casablanca','Monte-Carlo','Chisinau','Antananarivo','Male','Ciudad de MÃ©xico','Dalap-Uliga-Darrit','Skopje','Bamako','Birkirkara','Rangoon (Yangon)','Ulan Bator','Garapan','Maputo','Nouakchott','Plymouth','Fort-de-France','Port-Louis','Blantyre','Kuala Lumpur','Mamoutzou','Windhoek','NoumÃ©a','Niamey','Kingston','Lagos','Managua','Alofi','Amsterdam','Oslo','Kathmandu','Yangor','Auckland','al-Sib','Karachi','Ciudad de PanamÃ¡','Adamstown','Lima','Quezon','Koror','Port Moresby','Warszawa','San Juan','Pyongyang','Lisboa','AsunciÃ³n','Gaza','Faaa','Doha','Saint-Denis','Bucuresti','Moscow','Kigali','Riyadh','Omdurman','Pikine','Singapore','Jamestown','Longyearbyen','Honiara','Freetown','San Salvador','Serravalle','Mogadishu','Saint-Pierre','SÃ£o TomÃ©','Paramaribo','Bratislava','Ljubljana','Stockholm','Mbabane','Victoria','Damascus','Cockburn Town','NÂ´DjamÃ©na','LomÃ©','Bangkok','Dushanbe','Fakaofo','Ashgabat','Dili','NukuÂ´alofa','Chaguanas','Tunis','Istanbul','Funafuti','Taipei','Dar es Salaam','Kampala','Kyiv','Montevideo','New York','Toskent','CittÃ  del Vaticano','Kingstown','Caracas','Road Town','Charlotte Amalie','Ho Chi Minh City','Port-Vila','Mata-Utu','Apia','Sanaa','Beograd','Cape Town','Lusaka','Harare'];

			// Prepare the query
			$query = 'INSERT INTO visit (VisitTime, PageviewCount, Score, CountryCode, VisitorName, RefererUrl, CityName, AccountId) VALUES(?,?,?,?,?,?,?,?)';
			$stm = $this->_conn->prepare($query);
			
			$stm->bindParam(1, $visitTime);
			$stm->bindParam(2, $pageviewCount);
			$stm->bindParam(3, $score);
			$stm->bindParam(4, $countryCode);
			$stm->bindParam(5, $visitorName);
			$stm->bindParam(6, $refererUrl);
			$stm->bindParam(7, $cityName);
			$stm->bindParam(8, $accountId);

			while($totalVisits--){
				$visitTime = rand($visitRange, time());
				$pageviewCount = rand(10, 3500);
				$score = rand(0,99);
				$countryCode = $countries[array_rand($countries)];
				$visitorName = 'Visitor-' . rand(1,9999);
				$refererUrl = 'http://referersite' . rand(1,9999) . '.com';
				$cityName = $cities[array_rand($cities)];
				$accountId = rand(1,20);
				$stm->execute();
				echo "\n$totalVisits";
			}
			return true;
		}


		public function select($select){
			$this->_select = $select;
			return $this;
		}

		public function from($from){
			$this->_from = $from;
			return $this;
		}

		public function where($where){
			$this->_where = $where;
			return $this;
		}

		public function get($what){
			$bind = [];
			$this->_connectToMysql();
			$query = 'SELECT ' . $this->_select;
			if ($this->_from != null){
				$query .=' FROM ' . $this->_from;
			}

			if ($this->_where != null){
				$query .= ' WHERE ';
				foreach ($this->_where as $name => $value){
					$bindName = ':'.substr($name, 0, strpos($name, ' '));
					$query .= $name . $bindName . ' ';
					$bind[$bindName] = $value;
				}
			}
			
			$stm = $this->_dbConn->prepare($query);
			$stm->execute($bind);
			
			// Reset for the next search
			$this->_select = $this->_from = $this->_where = null;

			switch ($what) {
				case 'allRows':
					return $stm->fetchAll(PDO::FETCH_ASSOC);
					break;
				
				case 'field':
					return $stm->fetchColumn();
					break;

				default:
					throw new Exception("Dont know how to get '{$what}'", 1);					
					break;
			}
		}

	}


	// Quick way of populating the table
	// $db = new Database();
	// $db->populateDB();
	

?>
