<?php 

namespace Midori\Application {
	
	class Routes extends \Midori\Object
	{
	
		private $_routes = array();
		
		private static $instance = null;
		
		public function __construct()
		{
			
		}
		
		protected function getRoutes()
		{
			
			return 	$this->_routes;
		}
	
		public function root($route)
		{
			$this->_routes["default"][] = $route;		
		}
		
		public function __call($method, $args)
		{
			$this->_routes[$method][] = $args[0];			
		}
		
		public function addRoute($name, $route)
		{
			$this->_routes[$name][] = $route;
		}
		
		public static function getAllRoutes()
		{
			if(self::$instance == null)
				return null;
			return self::$instance->routes;	
		}
		
		public static function draw($block)
		{
			$map = new Routes();
			$block($map);
			self::$instance = $map;
		}
				
	}
}