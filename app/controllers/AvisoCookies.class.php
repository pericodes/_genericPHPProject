<?php 
	
	require_once 'app/controllers/tools/Controller.class.php';
	//require_once 'app/models/tools/DataBaseFactory.class.php';
	//require_once 'app/models/DAOs/tools/DataObjectFactory.class.php';

	/**
	 * 
	 */
	class AvisoCookies extends Controller
	{	
		
		function __construct($request){
			parent::__construct(self::$templates["default"],'AvisoCookies.html', $request, true);
			$this->addInclude("baseCSS", "includes/base.css");
			$this->addInclude("framework", "includes/framework.css");

		}

		public function render(){
			$siteConfig = getSiteConfig();
			$this->addArgument("urlSite", $siteConfig["urlIndex"]);
			$this->addArgument("siteName", $siteConfig["siteName"]);
			$this->useCache = true;
			return parent::render();
		}

	}

 ?>