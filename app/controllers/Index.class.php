<?php 
	
	require_once 'app/controllers/tools/Controller.class.php';
	//require_once 'app/models/tools/DataBaseFactory.class.php';
	//require_once 'app/models/DAOs/tools/DataObjectFactory.class.php';

	/**
	 * 
	 */
	class Index extends Controller
	{	
		
		function __construct($request){
			parent::__construct('index.html', $request);
		}

		public function render(){
			require_once 'app/models/DAOs/tools/DataAccessObjectFactory.class.php';
			//$posts = DataAccessObjectFactory::getDataAccessObject("post")->find();
			//var_dump($posts);
			//$this->metaTags->setTitle($post["metaTitle"]);


			//$this->addArgument("posts", $posts);
			$this->useCache = false;
			//file_put_contents("app/var/cache/index.html", parent::renderDirectly());
			return parent::renderDirectly();
			//return parent::render();
		}

	}

 ?>