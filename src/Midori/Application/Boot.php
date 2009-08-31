<?php 



namespace Midori
{

	function boot($dispatch = true)
	{
		$boot = Vars::get("boot");
		$boot->run($dispatch);
	}	
	
	$path = get_include_path();
	$folder = dirname(__FILE__)."/../../";
	$real = realpath($folder);
	
	if(strpos($path, $folder) === false && strpos($path, $real) === false)
	{
		set_include_path($real.PATH_SEPARATOR.$path);		
	}
		
	
	class AutoLoad
	{
			private static $loaded = array();
	
			public static function load($class)
			{
				 $char = DIRECTORY_SEPARATOR;
				 $file = str_replace(array('_', "\\") , array($char, $char), $class) . '.php';	
				 
				self::loadFile($file);
			}
			
	
			public static function loadFile($file)
			{
				if(is_dir($file))
					return;
				if(!in_array($file, self::$loaded))
				{	
					$test = @include($file);
					 if($test === 1)
						 	self::$loaded[] = $file;
					 else {
					 	if(defined("MIDORI_ENV") && MIDORI_ENV != "production")
						{
						 	trigger_error("could not load file $file", E_USER_NOTICE);	
						}
					 }
				}	
			}
			
	}
	
	
	spl_autoload_register("Midori\AutoLoad::load");
		
}



namespace Midori\Application
{
	
	use \Midori\File as File;
	
	class Boot
	{
		
		public function __construct()
		{
				
		}
		
		protected function initialize()
		{
			require File::join(MIDORI_ROOT."config", "environment.php");	
			$this->config = \Midori\config();
			$this->startDataAccess();
			$this->startLayout();
			$this->startView();
			$this->startDispatcher();
		}
		
		protected function startDataAccess()
		{
			$config = $this->config;
			$this->config->dataAdapter =  \Midori_Data_Adapter::fetch($config->databaseAdapter, $config->database);
			\Midori_Environment::getInstance()->defaultAdapter = $this->config->dataAdapter;
		}
		
		protected function startDispatcher()
		{
			
			
		}
		
		protected function startLayout()
		{
				
		}
		
		protected function startView()
		{
			
		}
		
		protected function startSession()
		{
				
		}
		
		public function dispatch()
		{
			if($this->config->dispatcher != null)
				$this->config->dispatcher->dispatch();		
			
		}

//	use this version of run() for local tab functionality --db
	
//		public function run($dispatch)
//		{
//			$this->startSession();
//			$this->initialize();	
//		
//			if($dispatch == true)
//			{
//				$this->dispatch();
//			}
//		}
	
		public function run($dispatch = true)
		{
			$this->initialize();
			
			if($dispatch)
			{
				$this->startSession();
				$this->dispatch();
			}
		}		
	}
	
	\Midori\Vars::set("boot", new Boot());
			
}