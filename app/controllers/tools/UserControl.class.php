<?php 
    session_cache_expire(60);
    session_start();
    //session_regenerate_id(true);

	/**
	 * 
	 */
	class UserControl 
	{
		
		private $user;

		function __construct()
		{
			//abrimos la sesión
            if (isset($_SESSION["userID"])) {//El usuario está registrado
                $this->userID = $_SESSION["userID"];
				require_once 'app/models/DAOs/tools/DataAccessObjectFactory.class.php';
                //$this->user = DataAccessObjectFactory::getDataAccessObject("user")->adduser("admin", "1234", 1, "");
                $this->user = DataAccessObjectFactory::getDataAccessObject("user")->findById($_SESSION["userID"]);
			}
        }
        public function isLogued():bool
		{
			return $this->user != null;
        }
        public function isAdmin():bool
		{
			return $this->isLogued() && $this->user->isAdmin();
		}
        
        /*public function setUserID(string $userID):void
        {
            $this->userID = $userID;
            $_SESSION["userID"] = $this->userID;
        }*/

        public function getUserID():string
        {
            return $this->isLogued() ? $this->user->getId(): "";
        }

        public function getUser():UserVO 
        {
            return $this->user;
        }

        public function login(string $user, string $pass): bool
        {
            //require_once 'app/models/VOs/UserVO.class.php';
            require_once 'app/models/DAOs/tools/DataAccessObjectFactory.class.php';
            //DataAccessObjectFactory::getDataAccessObject("user")->addUser("admin", "1234", 1, "");
            $user = DataAccessObjectFactory::getDataAccessObject("user")->getUserByUsername($user);
            return $user != null && $user->validatePass($pass);
        }

        public function logout() : void
		{
            unset($this->user);
			if (session_status()==PHP_SESSION_NONE)
			session_start();
			// Borrar variables de sesión
			session_unset(); 
			// Obtener parámetros de cookie de sesión
			$param = session_get_cookie_params();
            // Borrar cookie de sesión si existe
            if(isset($_COOKIE[session_name()])){
                setcookie(session_name(), $_COOKIE[session_name()], time()-2592000,
			    $param['path'], $param['domain'], $param['secure'], $param['httponly']);
            }
			// Destruir sesión
			session_destroy();
		}


	}


 ?>