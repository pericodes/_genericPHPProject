<?php 
	//require_once 'app/models/DAOs/ExampleDAO.class.php';
	

	final class DataAccessObjectFactory {

		private static $dataObjects = array();

		private function __construct($argument)
		{
			# code...
		}

		public static function getDataAccessObject($dataObject)
		{

			if(!isset(self::$dataObjects[$dataObject])){
				self::createDataObject($dataObject);
			}
			return self::$dataObjects[$dataObject];
			
		}

		private static function createDataObject($dataObject){
			switch ($dataObject) {
				case 'user':
					require_once 'app/models/DAOs/UserDAO.class.php';
					self::$dataObjects[$dataObject] = new UserDAO(); 
					break;
				case 'post':
					require_once 'app/models/DAOs/PostDAO.class.php';
					self::$dataObjects[$dataObject] = new PostDAO(); 
					break;
				case 'PostDAO_SQL_PDO':
					require_once 'app/models/DAOs/SQL/PostDAO_SQL_PDO.class.php';
					self::$dataObjects[$dataObject] = new PostDAO_SQL_PDO(); 
					break;
				case 'TagDAO_SQL_PDO':
					require_once 'app/models/DAOs/SQL/TagDAO_SQL_PDO.class.php';
					self::$dataObjects[$dataObject] = new TagDAO_SQL_PDO(); 
					break;
				case 'ImageDAO_SQL_PDO':
					require_once 'app/models/DAOs/SQL/ImageDAO_SQL_PDO.class.php';
					self::$dataObjects[$dataObject] = new ImageDAO_SQL_PDO(); 
				break;
				default:
					throw new Exception("No dataObject found with this name: " . $dataObject);
					break;
			}
		}


	}


 ?>