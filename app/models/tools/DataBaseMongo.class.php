<?php

class DataBaseMongo
{
	
	private $connection;
	private	$dataBase;
    private	$collection;
    private $schema;
	private $host;
	private	$onlyreadMongoUser;
	private	$onlyreadMongoPassword;
	private	$writeMongoUser;
	private	$writeMongoPassword;
	private	$name;

    public static function testStatic(string $schema, string $user, string $password, string $host, bool $useWriteUser = false)
    {
        require_once 'app/dependencies/vendor/autoload.php';
        $mongo = new MongoDB\Client("$schema://$user:$password@$host");
        $dbs = $mongo->listDatabases();
        if ($useWriteUser) {
            $test = $mongo->selectDatabase("test");
            $test->test->insertOne(["test" => "test"]);
            $test->test->drop();
        }
        return true; 
    }

    public function test(){
        return  self::testStatic($this->schema, $this->onlyreadMongoUser, $this->onlyreadMongoPassword,   $this->host, false) && 
                self::testStatic($this->schema, $this->writeMongoUser,    $this->writeMongoPassword,      $this->host, false); 
    }


	public function __construct($credentialsPath)
	{	
		$credentials 	= parse_ini_file($credentialsPath);
		$this->schema 	= isset($credentials["schema"]) ? $credentials["schema"] : "mongo" ;
		//$this->schema 	= $credentials["schema"] ;
		$this->onlyreadMongoUser 	    = $credentials["onlyreadMongoUser"];
		$this->onlyreadMongoPassword    = $credentials["onlyreadMongoPassword"];
		$this->writeMongoUser 	        = $credentials["writeMongoUser"];
		$this->writeMongoPassword 	    = $credentials["writeMongoPassword"];
        $this->host 	                = $credentials["host"];
		
    }

    public function getConnection(string $collection = "", bool $useWriteUser = false) {
		if(!$this->connection || !$this->dataBase){
            require_once 'app/dependencies/vendor/autoload.php';
            if(!$useWriteUser){
                $this->connection = new MongoDB\Client("$this->schema://$this->onlyreadMongoUser:$this->onlyreadMongoPassword@$this->host");
            }else{
                $this->connection = new MongoDB\Client("$this->schema://$this->writeMongoUser:$this->writeMongoPassword@$this->host");
            }

            $dataBase = strtolower(getSiteConfig()["domain"]);
            $pattern = '/[\/\\. "$*<>:|?]/';
            $name = preg_replace($pattern, "-", $dataBase);
            $this->dataBase = $this->connection->selectDatabase($name); 
        }
		if($collection){
            return $this->dataBase->selectCollection($collection);
        }else{
            return $this->dataBase; 
        }

    }
    
    private function showDataBases()
		{
			$client = $this->getConnection();

			$databases = $client->listDatabases();
			foreach ($client->listDatabases() as $databaseInfo) {
				$database = $this->selectDatabase($databaseInfo["name"]);
				echo "<pre>".$databaseInfo["name"]."</pre>";
				if($databaseInfo["name"] != "admin" && $databaseInfo["name"] != "local")
				foreach ($database->listCollections() as $collectionInfo) {
					echo "<pre>    ".$collectionInfo['name']."</pre>";
					$collection = $this->selectCollection($collectionInfo['name']);
					$cursor = $collection->find();
					foreach ($cursor as $document) {
						echo "<pre>        ".$document['_id']."</pre>";
					}

				}
				echo "<br>" ;
			}
		}
    /*
    public function selectCollection(string $dataBase, string $collection = false)
    {
        if(!$collection){
            if(!isset($this->database)){
                throw new Exception("You can't set a collection if you don't know the database");
            }else{
                $this->collection = $this->dataBase->selectCollection($dataBase);
            }
        }else{
            $this->collection = $this->selectDataBase($dataBase)->selectCollection($collection);
        }
        return $this->collection;
    }
    */
    public function findOne($filter = [], array $options = [])
    {
        if(isset($this->collection)){
            return $this->collection->findOne($filter,$options);
        }else{
            throw new Exception("You must define a collection before to search an element");
        }
    }

}
	
?>
