<?php
namespace Midori\PHPUnit
{

	\PHPUnit_Util_Filter::addFileToFilter(__FILE__, 'PHPUNIT');
	
	/**
	 * 
	 * @author Michael
	 * @package Midori
	 * @subpackage PHPUnit
	 */
	class Spec extends \PHPUnit_Framework_TestCase
	{
		/**
		 * 
		 * @var Midori\PHPUnit\Expect
		 */
		protected $expectation = null;
		
		/**
		 *
		 * @return Midori\PHPUnit\Expect
		 */
		public function expectsThat($data, $message = null)
		{
			if($this->expectation == null)
				$this->expectation = new Expect();
			
			$this->expectation->that($data, $message);
			return $this->expectation;
		}
		
		
	}
}
