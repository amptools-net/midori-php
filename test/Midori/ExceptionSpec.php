<?php

namespace Midori 
{
	/**
	* spec for Exception
	*/
	class ExceptionSpec extends PHPUnit\Spec
	{
		
		/**
		 * @test
		 */
		public function itShouldBeOfTypeMidoriException()
		{
			try{
				throw new Exception(" to the rule ");
			} catch(\Exception $ex) {
				$this->expectsThat($ex)->isInstanceOf("Midori\Exception");				
				$this->expectsThat($ex->getMessage())->equals(" to the rule ");
			}
		}
	}
	
	
}