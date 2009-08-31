<?php 



namespace Midori
{

	function boot($dispatch = true)
	{
		$boot = \Midori\Registry::get("boot");
		$boot->run($dispatch);
	}	
	
		
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
			//$this->config->dataAdapter =  \Midori\Data\Adapter::fetch($config->databaseAdapter, $config->database);
			
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
	
	\Midori\Registry::set("boot", new Boot());
			
}