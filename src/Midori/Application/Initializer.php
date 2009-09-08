<?php



namespace Midori
{
	function config()
	{
		return Registry::get("Midori/config");		
	}
			
}

namespace Midori\Application
{
	use Midori\Registry as Registry;
	
	
	
	class Initializer 
	{
		private $_configuration = null;
		
		public function __construct($configuration)
		{
			$this->_configuration = $configuration;
			$this->init();
			Registry::set("Midori/config", $this->_configuration);
		}
		
		protected function init()
		{
			$paths = $this->_configuration->include_paths;
			set_include_path(implode(PATH_SEPARATOR, $paths).PATH_SEPARATOR.get_include_path());
			
			date_default_timezone_set($this->_configuration->timezone);
		}
		
		public static function run($environment, $block)
		{
			if(!defined("MIDORI_ENV"))
				define("MIDORI_ENV", $environment);
			
			$config = new Configuration();
			$block($config);
			require MIDORI_ROOT."/config/environments/".MIDORI_ENV.".php";
			$initializer = new Initializer($config);
		}
		
	}	
	
}