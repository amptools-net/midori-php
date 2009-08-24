<?php 

namespace Midori
{
	require "Midori/Util.php";
	
	/**
	 * 
	 * 
	 */
	class RegexSpec extends PHPUnit\Spec
	{
		
		/**
		 *
		 * @test
		 */
		public function itShouldTestPattern()
		{
			$result = regex("/test/")->test(" this is a test");
			$this->expectsThat($result)->isTrue();
		}
				
	}
		
}