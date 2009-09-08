<?php

namespace Midori\Data
{

	class Adapter extends \Midori\PHPUnit
	{
		
		
		public function getProviders()
		{
			return array(
					new Adapters\Pdo\MysqlAdapter(
						array(
							"username" 	=> "midori",
							"password" 	=> "midori",
							"dbname" 	=> "midori_test",
							"host"		=> "127.0.0.1"
						)
					)
				);
		}
		
		/**
		 * @dataProvider getProviders
		 * @test
		 */
		public function itShouldBeAbleToInstantiate($adapter)
		{
						
		}
		
	}		
		
}