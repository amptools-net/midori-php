<?php 

namespace Midori
{
	

	class DateTimeSpec extends PHPUnit\Spec
	{
		
		public function setUp()
		{
			\date_default_timezone_set("America/New_York");		
		}
		
		/**
		 * @test
		 */
		public function ItShouldCreateADateTimeObject()
		{
			$date = new \DateTime("now");
			$greenDate = new DateTime($date);
			
			$this->expectsThat($greenDate->value == $date)->isTrue();
	
		}
	}
	
}