<?php 

class DataBasePDO
{

	private $connection;
	private $host;
	private	$userReadOnly;
	private	$passwordReadOnly;
	private	$user;
	private	$password;
	private	$name;
	private $dbType;


	public function __construct($credentialsPath, $dbType = "mysql")
	{	

		$credentials 	= parse_ini_file($credentialsPath);
		//var_dump($credentials);
		$this->host 			= $credentials["hostMySQL"];
		$this->user 			= $credentials["writeMySQLUser"];
		$this->password 		= $credentials["writeMySQLPassword"];
		$this->userReadOnly 	= $credentials["onlyreadMySQLUser"];
		$this->passwordReadOnly = $credentials["onlyreadMySQLPassword"];
		$this->name 			= $credentials["databaseNameMySQL"];

		if(isset($credentials["dbType"]))
			$this->dbType 	= $credentials["dbType"];
		else
			$this->dbType = $dbType; 
		
	}

	public function getConnection() {

		if(!$this->connection){
			try { 
				//echo "$this->dbType:host=$this->host;dbname=$this->name;charset=utf8";
				$this->connection = new PDO( "$this->dbType:host=$this->host;dbname=$this->name;charset=utf8", $this->user, $this->password); 
				$this->connection->setAttribute( PDO::ATTR_PERSISTENT, true ); //Keep alive the db connection
				$this->connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION );
			} catch ( PDOException $e ) { 
				throw new Exception("DataBaseMySQL connection fail: " . $e->getMessage());
			}
		}
		
		return $this->connection; 

	}

	public function getConnectionReadOnly() {

		if(!$this->connection){
			try { 
				//echo "$this->dbType:host=$this->host;dbname=$this->name;charset=utf8";
				$this->connection = new PDO( "$this->dbType:host=$this->host;dbname=$this->name;charset=utf8", $this->user, $this->password); 
				$this->connection->setAttribute( PDO::ATTR_PERSISTENT, true ); //Keep alive the db connection
				$this->connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION );
			} catch ( PDOException $e ) { 
				throw new Exception("DataBaseMySQL connection fail: " . $e->getMessage());
			}
		}
		
		return $this->connection; 

	}

}


 ?>