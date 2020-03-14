<?php 

	/**
	 * 
	 */
	class Request 
	{
		
		protected $method, $path, $scheme;
		protected $parameters, $query, $parametersRoute;

		private $level = 0;
		private $pathElements;

		function __construct()
		{
			#Session::sessionStart(); // If uncomment you must say you are ussing cookies in your website.
			$this->method = $_SERVER['REQUEST_METHOD'];
			$this->path = strtolower ($_SERVER['REQUEST_URI']);
			
			if(preg_match("/[^\\\\]+$/", getcwd(), $subfolder)){
				$subfolder = $subfolder[0];
			}
			$this->path = substr(str_replace("$subfolder/", "", $this->path), 1); 

			$this->pathElements = explode("/", $this->path);

			if ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443)
				$this->scheme = 'https';
			else
				$this->scheme = 'http';

			if($this->method === "POST"){
				$this->parameters = $_POST;
				if(count($_POST) == 0){
					
					$this->parameters = json_decode(file_get_contents('php://input'), true);
				}
			}else if($this->method === "GET"){
				$this->parameters = $_GET;
			}

			$this->query = $_SERVER['QUERY_STRING'];

		}

		public function getMethod() { return $this->method; }
		public function methodIsGet():bool { return $this->method === "GET" ;}
		public function methodIsPost():bool { return $this->method === "POST" ;}
		public function getDomain() { return $this->domain; }
		public function getPath() { return $this->path; }
		public function getScheme() { return $this->scheme; }
		public function getNextElement():string { return isset($this->pathElements[$this->level]) ? $this->pathElements[$this->level++] : "";}
		public function getElement(int $level):string { return isset($this->pathElements[$level]) ? $this->pathElements[$level] : "";}

		private function getParamArray(array $arr)
		{
			$result = [];
			foreach ($arr as $key => $value) {
				if (is_array($value)){
					$result[htmlspecialchars($key)] = $this->getParamArray($value);
				}else{
					$result[htmlspecialchars($key)] = htmlspecialchars($value);
				}
			}
			return $result;
		}
		public function getParam(string $p, bool $scape = true) {
			if (($p!==null) and array_key_exists($p, $this->parameters)) {
				if($scape){
					if (is_array($this->parameters[$p]))
						return $this->getParamArray($this->parameters[$p]);
					else
						return htmlspecialchars($this->parameters[$p]);
				}else{
					return $this->parameters[$p];
				}
			}else
				return null;
		}

	}


 ?>