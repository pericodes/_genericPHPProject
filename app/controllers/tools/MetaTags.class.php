<?php


    class MetaTags
    {
        private $title; 
        private $description;
        private $pubDate;
        private $type;
        private $siteName;
        private $keywords = []; 
        private $url;
        private $urlIndex;
        private $urlFeed;
        private $urlRss;

        private $urlImage;
        private $urlWidth;
        private $urlHeight;
        private $iconURL;

        private $language;
        private $locale;
        private $siteDescription;
        private $index;

        public function __construct() {
            $this->index = true;
            $siteConfig 	= getSiteConfig();
            $this->siteName = $siteConfig["siteName"];
            $this->language = $siteConfig["language"];
            $this->locale = $siteConfig["locale"];
            $this->siteDescription  = $siteConfig["siteDescription"];
            $this->urlIndex  = $siteConfig["urlIndex"];
            $this->urlFeed  = $siteConfig["urlFeed"];
            $this->iconURL = isset($siteConfig["urlIcon"]) ? $siteConfig["urlIcon"] : "public/images/ico/logo.ico";


        }

        public function setTitle(string $title){
            $this->title = $title;
        } 
        public function setDescription(string $description){
            $this->description = $description;
        }
        public function setSiteName(string $siteName){
            $this->siteName = $siteName;
        }
        public function setUrl(string $url){
            $this->url = $url;
        }
        public function setLanguage(string $language){
            $this->language = $language;
        }
        public function setPubDate(string $pubDate){
            $this->pubDate = $pubDate;
        }
        public function setIndex(bool $index){
            $this->index = $index;
        }
        public function setUrlImage(string $urlImage){
            $this->urlImage = $urlImage;
        }
        public function setKeywords(array $keywords){
            $this->keywords = $keywords;
        }
        /*
<meta property="og:image" content="https://avesexoticas.org/wp-content/uploads/2018/03/Hagen-Vision-II-M01.jpg" />
<meta property="og:image:secure_url" content="https://avesexoticas.org/wp-content/uploads/2018/03/Hagen-Vision-II-M01.jpg" />
<meta property="og:image:width" content="1024" />
<meta property="og:image:height" content="1085" />
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:image" content="https://avesexoticas.org/wp-content/uploads/2018/03/Hagen-Vision-II-M01.jpg" />
        */
        private function generate()
        {
            if(isset($this->type))
            switch ($this->type) {
                case 'product':
                        
                    break;
                
                default:
                    # code...
                    break;
            }
        }
        public function toHTML()
        {
            $metaTags = "";
            $metaTagsFacebook = "";
            $metaTagsTwitter = "";
            
            if($this->index){

                if($this->title){
                    $metaTags         .= "	<title>$this->title</title>".PHP_EOL;
                    $metaTagsFacebook .= '	<meta property="og:title" content="'.$this->title."\"/>".PHP_EOL;
                    $metaTagsTwitter  .= '	<meta name="twitter:title" content="'.$this->title."\"/>".PHP_EOL;
                }
                if($this->description){
                    $metaTags        .= '	<meta name="description" content="'.$this->description."\"/>".PHP_EOL;
                    $metaTagsTwitter .= '	<meta name="twitter:description" content="'.$this->description."\"/>".PHP_EOL;
                    $metaTagsFacebook .= '	<meta property="og:description" content="'.$this->description."\"/>".PHP_EOL;
                }
                if($this->locale){
                    $metaTagsFacebook .= '	<meta property="og:locale" content="'.$this->locale."\"/>".PHP_EOL;
                }  
                if($this->url){
                    $metaTags .= '	<link rel="canonical" href="/'.$this->url."\"/>".PHP_EOL;
                    $metaTags .= '	<meta name="url" content="/'.$this->url."\"/>".PHP_EOL;
                    $metaTagsFacebook .= '	<meta property="og:url" content="/'.$this->url."\"/>".PHP_EOL;
                }
                if($this->siteName){
                    $metaTagsFacebook .= '	<meta property="og:site_name" content="'.$this->siteName."\"/>".PHP_EOL;
                }
                if($this->siteName && $this->urlIndex){
                    $metaTags .= '	<link rel="index" title="'.$this->siteName.'" href="/'.$this->urlIndex."\"/>".PHP_EOL;
                }
                if($this->siteName && $this->urlFeed){
                    $metaTags .= '	<link rel="alternate" type="application/rss+xml" title="'.$this->siteName.': Feed" href="/'.$this->urlFeed.".xml\"/>".PHP_EOL;
                }
                if($this->iconURL && file_exists($this->iconURL)){
                    $metaTags .= <<<EOT
                        <link rel="shortcut icon" href="/{$this->iconURL}" />
                        <link rel="icon" href="/{$this->iconURL}" sizes="32x32" />
                        <link rel="icon" href="/{$this->iconURL}" sizes="192x192" />
                        <link rel="apple-touch-icon-precomposed" href="/{$this->iconURL}" />
                    EOT;
                    $metaTags .= PHP_EOL;
                    if($this->siteName){
                        $metaTags .= "	<meta name=\"msapplication-TileImage\" content=\"".$this->siteName."\" />".PHP_EOL;
                    }
                }


                if($this->urlImage && file_exists(preg_replace("/^\//i", "", $this->urlImage))){
                    $metaTags .= '	<link rel="image_src" href="/'.$this->urlImage.'">'.PHP_EOL;
                    $metaTagsFacebook .= <<<IMAGE_FACEBOOK
                        <meta property="og:image" content="/{$this->urlImage}" />
                        <meta property="og:image:secure_url" content="/{$this->urlImage}" />
                    IMAGE_FACEBOOK;
                    $metaTagsFacebook .= PHP_EOL;
                    $metaTagsTwitter .= <<<IMAGE_TWITTER
                        <meta name="twitter:card" content="summary_large_image" />
                        <meta name="twitter:image" content="/{$this->urlImage}" />
                    IMAGE_TWITTER;
                    $metaTagsTwitter .= PHP_EOL;
                    /*
                    <meta property="og:image:width" content="1024" />
                    <meta property="og:image:height" content="1085" />
                    */
                }
                if($this->pubDate){
                    date_default_timezone_set('UTC');
                    $metaTags .= '	<meta name="date" content="'.date('l\, F d\, Y\, g:i a', $this->pubDate)."\"/>".PHP_EOL;
                    $metaTagsFacebook .= '	<meta property="og:type" content="article"/>'.PHP_EOL;
                    $metaTagsFacebook .= '	<meta property="og:article:published_time" content="'.date(DateTimeInterface::ATOM, $this->pubDate)."\"/>".PHP_EOL;
                }

                if(count($this->keywords) > 0){
                    $metaTags .= '	<meta name="keywords" content="'.implode(",", $this->keywords).'" />'.PHP_EOL ;
                }

                $metaTags .= $metaTagsFacebook;
                $metaTags .= $metaTagsTwitter;
            }else{
                $metaTags .='	<meta name="robots" content="noindex, nofollow" />'.PHP_EOL;
                $metaTags .='	<meta name="googlebot" content="noindex, nofollow" />'.PHP_EOL;
            }
            
            //var_dump($this->title);
            return $metaTags;
            
        }

    }

?>