<?php

namespace Midori\Application
{
	class Configuration
	{
		
		public function __construct()
		{
			$this->midori = new \stdClass();
			$this->zend = new \stdClass();
			$this->initialize();
		}
		
		protected function initialize()
		{
			$this->getDataConfig();
			$this->getPaths();	
			$this->getLoadPaths();
		}
		
		protected function getDataConfig()
		{
			require MIDORI_ROOT. "config/database.php";
			$this->database = $dbConfig[MIDORI_ENV];
			$this->databaseAdapter = $dbConfig['adapter'];	
			
		}
		
		protected function getLoadPaths()
		{
			$paths = $this->paths;
			$this->include_paths = \listof(
				$paths->app,
				$paths->models,
				$paths->controllers,
				$paths->helpers,
				$paths->services,
				$paths->lib,
				$paths->vendor,
				$paths->root
			)->findAll(function($dir){ return is_dir($dir); });		
		}
		
		public function updateUrls($base = "/", $theme = "default")
		{
			$this->urls = new \stdClass();
			$base = $this->urls->base = $base;
			$this->urls->javascripts =	$base."javascripts/";
			$this->urls->stylesheets =	$base."stylesheets/";
			$this->urls->images =	$base."images/";
			$themes = $this->urls->themes = 	$base."themes/";
			$this->urls->theme =	$themes."$theme/";
		}
		
		protected function getPaths()
		{
			$this->paths					= new \stdClass();	
			$root = $this->paths->root 		= \Midori\root();
			$this->paths->lib 				= $root."lib/";
			$this->paths->vendor			= $root."vendor/";
			$public = $this->paths->public			= $root."public/";
			$this->paths->javascripts	    = $public."javascripts/";
			$this->paths->stylesheets	    = $public."stylesheets/";
			$app = $this->paths->app 		= $root."app/";
			$this->paths->models 			= $app."models/";
			$this->paths->controllers 		= $app."controllers/";
			$this->paths->views 			= $app."views/";
			$this->paths->helpers 			= $app."helpers/";
			$this->paths->services 			= $app."services/";
			$this->paths->layout			= $app."views/layouts";
			$this->paths->cache				= $root."tmp/cache/";
			$this->paths->logs				= $root."tmp/logs/";
			$this->paths->test				= $root."test/";
		}
	}		
		
}