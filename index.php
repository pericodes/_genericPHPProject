<?php 
	error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	ini_set('display_errors', 'On');
	
	define("T_START", microtime(true));
	require_once 'app/controllers/tools/ControllerFactory.class.php';
	/*require_once 'app/models/DAOs/tools/DataAccessObjectFactory.class.php';
	$userDAO = DataAccessObjectFactory::getDataAccessObject("user"); 
	$userDAO->addUser("admin", "1234", "admin");*/
	$t1 = microtime(true);
	function getSiteConfig()
	{
		if(!defined("SITE_CONFIG")){
			define("SITE_CONFIG", parse_ini_file("./app/config/dataSite.config.php"));
		}
		return SITE_CONFIG; 
	}
	function getAmazonConfig()
	{
		if(!defined("AMAZON_CONFIG")){
			define("AMAZON_CONFIG", parse_ini_file("./app/config/amazon.config.php"));
		}
		return AMAZON_CONFIG; 
	}

	function generateLink(string $link):string
	{
		$link = strtolower($link);
		$link = str_replace(["à","á","â","ã","ä","å","æ","ç","è","é","ê","ë","ì","í","î","ï","ð","ñ","ń","ò","ó","ô","õ","ö","ø","ù","ú","û","ü"],
							["a","a","a","a","a","a","a","c","e","e","e","e","i","i","i","i","o","n","n","o","o","o","o","o","o","u","u","u","u"],
							$link); 

        return preg_replace(['/[^\w]/im', '/\-+/im'], "-", $link);
	}

	echo ControllerFactory::createController()->render(); 
	$t2 = microtime(true);
	//echo ($t2-$t1);
	

 ?>