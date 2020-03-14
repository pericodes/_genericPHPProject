<?php 
	require_once 'app/controllers/tools/Request.class.php';
	
/**
 * 
 */
final class ControllerFactory
{
	private function __construct(){}

	public static function createController()
	{	
		$request = new Request(); 
		//echo $request->getPath();
		switch ($request->getNextElement()) {
			case '':
			case 'index':
				require_once 'app/controllers/Index.class.php';
				return new Index($request); 
				break;
			case 'login':
				require_once 'app/controllers/Login.class.php';
				return new Login($request); 
				break;
			case 'avisocookies':
				require_once 'app/controllers/AvisoCookies.class.php';
				return new AvisoCookies($request); 
				break;
			default:
				require_once 'app/controllers/NotFound.class.php';
				return new NotFound($request);
				break;
		}
	}

}


?>