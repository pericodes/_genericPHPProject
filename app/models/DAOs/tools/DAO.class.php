<?php 

	/**
	 * 
	 */
	abstract class DAO 
	{
		protected $pdo = null;
        protected $mongo = null;
        protected $use = "sql_pdo";
        
		protected function __construct()
		{
			$this->tableName="example";
			parent::__construct('mainPDO');
		}

        protected function getPdo()
        {
            if($this->pdo == null){
                $className = get_class($this).'_SQL_PDO';
                require_once 'app/models/DAOs/SQL/'.$className.'.class.php';
                $this->pdo = new $className();
            }
            return $this->pdo; 
        }
        protected function getMongo()
        {
            if($this->mongo == null){
                $className = get_class($this).'_Mongo';
                require_once 'app/models/DAOs/Mongo/'.$className.'.class.php';
                $this->mongo = new $className();
            }
            return $this->mongo; 

        }
        public function __call($name, $arguments)
        {
            $object = null; 
            switch ($this->use) {
                case 'sql_pdo':
                        $object = $this->getPdo();
                    break;
                case 'mongo':
                    $object = $this->getMongo();
                break;
                default:
                    # code...
                    break;
            }
            return call_user_func_array(array($object,$name), $arguments); 

        }
    }

?>