<?php

namespace Midori\Application
{

	class RoutesSpec extends \Midori\PHPUnit\Spec
	{
	
		/**
		 *
		 * @test
		 */
		public function itShouldDrawRoutes()
		{
			Routes::draw(function($map){
				
				$map->addRoute("test", ":controller/:action/:id");
				
				$map->blog("blog/:action/:id");
			});	
			
			$routes = Routes::getAllRoutes();
			$this->expectsThat(count($routes))->shouldBe(2);
			var_dump($routes['blog'][0]);
			$this->expectsThat($routes['blog'][0])->shouldBe("blog/:action/:id");
		}
		 	
	}
	
}