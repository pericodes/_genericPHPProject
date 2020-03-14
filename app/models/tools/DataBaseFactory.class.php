<?php 

	
	/**
	 * 
	 */
	final class DataBaseFactory
	{
		private static $dataBases = array();

		private function __construct($argument)
		{
			# code...
		}

		public static function getDataBase($dataBase)
		{
			if(!isset(self::$dataBases[$dataBase])){
				self::createDataBase($dataBase);
			}
			return self::$dataBases[$dataBase];
			
		}

		private static function createDataBase($dataBase){
			switch ($dataBase) {
				case 'mainPDO':
					require_once 'app/models/tools/DataBasePDO.class.php';
					self::$dataBases[$dataBase] = new DataBasePDO("./app/config/dbMysqlCredentials.config.php"); 
					break;
				case 'mongo':
					require_once 'app/models/tools/DataBaseMongo.class.php';
					self::$dataBases[$dataBase] = new DataBaseMongo("./app/config/dbMongoCredentials.config.php"); 
					break;
				default:
					throw new Exception("No DataBase found with this name: " . $dataBase);
					break;
			}
		}


	}



 ?>