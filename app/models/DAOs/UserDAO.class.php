<?php 
	//require_once 'app/models/DAOs/tools/DataObjectPDO.class.php';
	require_once 'app/models/DAOs/tools/DAO.class.php';

	/**
	 * 
	 */
	class UserDAO extends DAO
	{

        
        public function __construct() {
        }

        /*public function addUser(string $user, string $pass, string $role = "normalUser", string $amazonAffiliateTag = "" ){
            return parent::insertOne(new UserVO( $user, $pass, $role, $amazonAffiliateTag));
        }

        public function getNormalUsers(){
            return parent::find(["role" => "normalUser"]);
        }

        public function updateUserById(string $id, string $user = "", string $pass = "", string $role = "", string $amazonAffiliateTag = "" ){
            $update = []; 
            if($user){
                $update["user"] = $user;
            }
            if($pass){
                $update["hash"] = UserVO::generateHash($pass);
            }
            if($role){
                $update["role"] = $role;
            }
            if($amazonAffiliateTag){
                $update["amazonAffiliateTag"] = $amazonAffiliateTag;
            }
            
            return parent::updateOneById($id, $update);
        }

        
        /*public static function getDatabase()
        {   
            if(!isset(self::$collection)){
                require_once 'app/models/tools/DataBaseFactory.class.php';
                self::$collection = DataBaseFactory::getDataBase("mongo")->getConnection("users");
            };
            return self::$collection; 
            
        }
		public static function getUserById(string $id)
        {
            return self::getDatabase()->findOne(["_id" => new MongoDB\BSON\ObjectId("$id")], ['typeMap' => ['root' => 'array']]);
        }

        public function findById(string $id)
        {
            return $this->collection->findOne(["_id" => new MongoDB\BSON\ObjectId("$id")], ['typeMap' => ['root' => 'array']]);
            
        }*/

	}


?>