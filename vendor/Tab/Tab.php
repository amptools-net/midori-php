<?php 


namespace Tab
{
	$path = get_include_path();
	$folder = dirname(__FILE__)."/../";
	$real = realpath($folder);
	
	if(strpos($path, $folder) === false && strpos($path, $real) === false)
	{
		set_include_path($real.PATH_SEPARATOR.$path);		
	}
	
	$tab_last_description = "";
	
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
					 if((include $file) == 'OK')
						 	self::$loaded[] = $file;
				}	
			}
			
			public static function loadDir($path)
			{
				$files = FileUtils::getFilesForDirectory($path);
				foreach($files as $file)
					self::loadFile($path.DIRECTORY_SEPARATOR.$file);
			}
			
	}
	
	spl_autoload_register("Tab\AutoLoad::load");

	
	final class Tab extends Object
	{
		private $_originalDirectory = null;
		private $_application = null; 
		private static $instance = null;
		
		public function __construct()
		{
			$this->_originalDirectory = getcwd();		
		}
		
		public function getApplication()
		{
			return $this->_application = $this->_application ?: new Application(); 		
		}
		
		public function setApplication(Application $application)
		{
			$this->application = $application;		
		}
		
		public function getOriginalDirectory()
		{
			return $this->_originalDirectory;
		}
		
		public static function getInstance()
		{
			return self::$instance =  self::$instance ?: new Tab();
		}
		
		public static function addIncludePaths()
		{
			$include = "";
			$paths = func_get_args();
			foreach($paths as $path)
			{
				$include .= $path.PATH_SEPARATOR;		
			}
			set_include_path($include.PATH_SEPARATOR.get_include_path());
		}
		
		public static function loadDir($path)
		{
			Autoload::loadDir($path);		
		}
		
		public static function run()
		{
			self::getInstance()->application->run();		
		}
		
		public function __destruct()
		{
			echo "\n\n";
		}
	}
	
	
}

namespace 
{
	function desc($description)
	{
		global $tab_last_description;
		$tab_last_description = $description;
	}
	
	function task($tasks = array(), $args = array(), $block)
	{
		$tab = Tab\Tab::getInstance();
		$tab->application->defineTask("Tab\Task", $tasks, $args, $block);
	}
	
	\Tab\Tab::run();
	
}

