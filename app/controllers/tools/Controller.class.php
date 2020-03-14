<?php  
	require_once 'app/controllers/tools/MetaTags.class.php';
	use voku\helper\HtmlMin;


	abstract class Controller {
		private static $loader;
		private static  $twig;
		protected $path;
		protected $arguments = [];
		protected $request;
		protected $metaTags; 
		private $includes;
		protected $extends;
		protected $useCache;
		private static $templatesPath; 
		private static $cachePath; 
		private static $siteConfig;
		//private static $template = "bootstrap";
		private static $template = "default";
		//private static $template = "ana";
		protected static $templates = ["default" => "default"]; 

		protected $userControl;

		protected $useTinyHtmlMinifier = false;
		protected $useHtmlMin = false;
		protected $useAmpRemoveUnusedCss = false;


		function __construct( string $path,  Request $request, string $template = "default", bool $index = true, $userControl = false)
		{
			$this->path 		 = $path;
			$this->request 		 = $request;
			self::$cachePath 	 = "./app/var/cache/";
			self::$templatesPath = "./app/views/html/".$template."/";
			$this->useCache 	 = false;
			$this->extends 		 = "base.html";
			$this->includes 	 = [] ;

			$this->metaTags = new MetaTags();
			if($userControl){
				if($userControl instanceof UserControl){
					$this->userControl = $userControl;
				}else{
					require_once 'app/controllers/tools/UserControl.class.php';
					$this->userControl = new UserControl();
				}
			}
			$this->metaTags->setIndex($index);
		}

		public function addArgument(string $argument, $value)
		{
			$this->arguments[$argument] = $value;
		}
		protected function login()
		{
			require_once 'app/controllers/Login.class.php';
			$aux = new Login($this->request); 
			return $aux->render(); 
		}

		protected function notFound()
		{
			require_once 'app/controllers/Index.class.php';
			$aux = new Index($this->request); 
			return $aux->render(); 
		}

		protected function forbiden(string $text = "Forbiden"):void
		{
			header("HTTP/1.1 403 $text.");
			exit("403 $text.");
		}

		/* Checks the origen request */
		protected function origenAllowed(array $acceptedOrigins = []):bool{
			//self::$acceptedOrigins[] = $this->getSiteConfig()["urlIndex"];
			$acceptedOrigins[] = "http://localhost";
			$acceptedOrigins[] = substr(getSiteConfig()["urlIndex"], 0,strlen(getSiteConfig()["urlIndex"])-1);
			if (isset($_SERVER['HTTP_ORIGIN'])) {
				// same-origin requests won't set an origin. If the origin is set, it must be valid.
				if (in_array($_SERVER['HTTP_ORIGIN'], $acceptedOrigins)) {
					header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
					return true;
				} else {
					$this->forbiden("Origin Denied");
				}
			}else{
				$this->forbiden("Origin doesn't found");
			}
		}

		protected function userLoguedOrBlock():bool
		{
			if ($this->userControl->isLogued()) {
				return true;
			}else{
				$this->forbiden("User not allowed");
			}
		}

		protected function userAdminOrBlock():bool
		{
			if ($this->userControl->isAdmin()) {
				return true;
			}else{
				$this->forbiden("User not allowed");
			}
		}

		protected function addInclude(string $name, string $path = "")
		{
			if($path)
				$this->includes[$name] = $path;
			else
				$this->includes[$name] = "includes/{$name}.html";

		}

		private static function getTwig(){
			if(!isset(self::$twig)){
				require_once 'app/dependencies/vendor/autoload.php';
				if(!isset(self::$loader))
					self::$loader = new \Twig\Loader\FilesystemLoader(self::$templatesPath);
				self::$twig = new \Twig\Environment(self::$loader);
			}
			return self::$twig;
		}

		private function templateChanged():bool
		{
			$cacheFilePath = self::$cachePath.$this->request->getPath();
			if(filemtime($cacheFilePath) < filemtime(self::$templatesPath.$this->path))
				return true;

			if(filemtime($cacheFilePath) < filemtime(self::$templatesPath.$this->extends))
				return true;

			foreach ($this->includes as $include) {
				if(filemtime($cacheFilePath) < filemtime(self::$templatesPath.$include))
					return true;
			}

			return false;
		}

		protected function haveToRender():bool
		{
			$cacheFilePath 		= self::$cachePath.$this->request->getPath();
			return !file_exists($cacheFilePath) || $this->templateChanged();
		}
		/*
			Be carefull because this method don't check anything so you can send unupdated pages or alse have errores. 
			However this is really fast.
		*/
		protected function sendCachedPageDirectly(bool $tryToRenderIfError = true)
		{
			$cacheFilePath 		= self::$cachePath.$this->request->getPath();
			$templateFilePath 	= self::$templatesPath.$this->path;
			$fichero = file_get_contents($cacheFilePath);
			//echo "Tiempo empleado: " . (microtime(true) - T_START);
			if($fichero)
				return $fichero;
			else{
				if($tryToRenderIfError){
					if($this->useCache){
						$fichero = $this->renderDirectly();
						file_put_contents($cacheFilePath, $fichero);
						return $fichero;
					}else{
						return $this->renderDirectly();
					}
				}else{
					throw new Exception("You try to send the cached page directly, but we couldn't open the file.");
				}
			}
		}
		/*
			You always render the pages, this is the slowest method but also the safest.
		*/
		protected function renderDirectly(){
			$template = self::getTwig() -> load($this->path);
			$this->arguments["meta"] = $this->metaTags->toHTML();
			$this->arguments["extends"] = $this->extends;
			$this->arguments["includes"] = $this->includes;
			if(isset($this->userControl))
            	$this->arguments["userControl"] = $this->userControl;

			return self::renderFile($this->path, $this->arguments); 
		}

		

		public function render(){ 
			//we try to serve the cached file
			if($this->useCache){
				$cacheFilePath 		= self::$cachePath.$this->request->getPath();
				$templateFilePath 	= self::$templatesPath.$this->path;
				if(!$this->haveToRender()){
					$fichero = file_get_contents($cacheFilePath);
					if($fichero)
						return $fichero;
				}
			}
			//We can't serve the cached file, so we have to load twig and render the template
			if($this->useCache){
				$fichero = $this->renderDirectly();
				file_put_contents($cacheFilePath, $fichero);
				return $fichero;
			}else{
				//var_dump($template -> render($this->arguments));
				return $this->renderDirectly();
			}
			
		}

		protected static function renderFile(string &$file, array &$arguments = [], array &$options = []):string
		{
			return self::getTwig()->load($file)->render($arguments);
		}

		public function renderJson($data):string{ 
			header('Content-Type: application/json');
			return json_encode($data);
		}
	}

?>