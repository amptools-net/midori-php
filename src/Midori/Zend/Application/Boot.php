<?php 


namespace 
{
	function db()
	{
		$registry = Zend_Registry::getInstance();
		$db = isset($registry['db']) ? $registry['db'] : null;
		if($db == null)
		{
			$config = config();
			
			$db = Zend_Db::factory($config->databaseAdapter, $config->database);
			Zend_Registry::set("db", $db);
			
			//$db->getConnection()->exec("SET NAMES utf8");
		}
		return $db;
	}
}

namespace Midori\Zend\Application
{
	
	
	class Boot extends \Midori\Application\Boot
	{
		
		protected function startLog()
		{
			$writer = new \Zend_Log_Writer_Stream('php://stdout');
			$logger = new \Zend_Log($writer);		
			$logger->addPriority("sql", 8);
			$this->config->log = $logger;
		}
		
		protected function startLayout()
		{
		
			\Zend_Layout::startMvc(array());
			$layout = \Zend_Layout::getMvcInstance();
			$layout->setLayoutPath($this->config->paths->layout);
			$layout->setLayout('site');	
			$this->config->layout = $this->config->zend->layout = $layout;	
		}
		
		protected function startDispatcher()
		{
			
			$front = \Zend_Controller_Front::getInstance();
			
			if(MIDORI_ENV == "test")
			{
				$front->setResponse(new \Midori\PHPUnit\Zend\HttpResponse());
				$front->setRequest( new \Midori\PHPUnit\Zend\HttpRequest());
			}
		
			if(MIDORI_ENV != "development")
			{
				$error_plugin = new \Zend_Controller_Plugin_ErrorHandler();
				$error_plugin
			       ->setErrorHandlerController('Error')
			       ->setErrorHandlerAction('error');
			       
			    $front->registerPlugin($error_plugin);
			}
		       
		
			$paths = $this->config->paths;
			
			$front->setControllerDirectory(array(
				'default'	=>	$paths->controllers,
				'admin'		=>  $paths->controllers.'/admin'
			));
			
			
			$front->setDefaultControllerName("home");
			$front->setDefaultAction('index');
		
			$router = $front->getRouter();
			
			require MIDORI_ROOT."config/routes.php";
			
			$array = \Midori\Application\Routes::getAllRoutes();
			
			foreach($array as $name => $routes)
			{
				foreach($routes as $route)
					$router->addRoute($name, $route);		
			}
			
			
			if(MIDORI_ENV == "development")
				$front->throwExceptions(true);	
			$this->config->zend->frontController = $this->config->dispatcher = $front;
				
		}
	
		
		protected function startView()
		{
			$view = \Midori\Zend\View::get();
			$view->setBasePath($this->config->paths->app);
		
			$viewRenderer = \Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
			$viewRenderer->setView($view);
			
			$this->config->zend->view = $view;
			$this->config->zend->viewRenderer = $viewRenderer;
		}
		
		protected function startSession()
		{
			\Zend_Session::start();			
		}
	}	
	
	\Midori\Registry::set("boot", new Boot());
}
