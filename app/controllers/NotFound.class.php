<?php 
	
	require_once 'app/controllers/tools/Controller.class.php';

	/**
	 * 
	 */
	class NotFound extends Controller
	{	
		
		function __construct($request){
			parent::__construct('NotFound.html', $request);
		}

		public function render(){
			return parent::render();
		}

	}

 ?>