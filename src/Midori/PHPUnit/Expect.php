<?php
// copyright 2009 Michael Herndon, Amptools  - see license file.


namespace Midori\PHPUnit
{
	require_once "Midori/Util.php";
	
	\PHPUnit_Util_Filter::addFileToFilter(__FILE__, 'PHPUNIT');
	
	/**
	 * 
	 * @author Michael
	 * @package Midori
	 * @subpackage PHPUnit
	 */
	class Expect
	{
		private $data;
		private $message = "";
		
		/**
		 * that (fluent interface) method taking in the data to wrap it)
		 * 
		 * @param mixed $data
		 * @param string $message
		 * @return $self
		 */
		public function that($data, $message)
		{
			$this->data = unbox($data);
			return $this;
		}
		
		public function andThat($data, $message = null)
		{
			$this->data = unbox($data);
			return $this;
		}
		
		public function expectsThat($data, $message = null)
		{
			$this->data = unbox($data);
			return $this;
		}
		
		/**
		 * determines if the data is null.
		 * 
		 * @param string $message optional.
		 * @return $self
		 */
		public function isNull($message = null)
		{
			\PHPUnit_Framework_Assert::assertNull($this->data, $message);
			return $this;
		}
		
		/**
		 * determines if the data is NOT null.
		 * 
		 * @param string $message optional.
		 * @return $self
		 */
		public function isNotNull($message = null)
		{
			\PHPUnit_Framework_Assert::assertNotNull($this->data, $message);
			return $this;
		}
		
		/**
		 * determines if the data evaluates to true.
		 *
		 * @param string $message optional.
		 * @return $self
		 */
		public function isTrue($message = null)
		{
			\PHPUnit_Framework_TestCase::assertTrue($this->data, $message);
			return $this;
		}
		
		public function isInstanceOf($className, $message = null)
		{	
			$actual = @get_class($this->data);
			
			\PHPUnit_Framework_TestCase::assertTrue($this->data instanceof $className, " $actual was not instance of $className ");
			
			return $this;
		}
		
		/**
		 * determines if the data evaluates to false.
		 *
		 * @param string $message optional.
		 * @return $self
		 */
		public function isFalse($message = null)
		{
			\PHPUnit_Framework_Assert::assertFalse($this->data, $message);
			return $this;
		}
		
		/**
		 * determines if the data should be equal to the data that is expected.
		 *
		 * @param string $message optional.
		 * @return $self
		 */
		public function shouldBe($data, $message = null)
		{
			\PHPUnit_Framework_Assert::assertEquals($data, $this->data, $message);
			return $this;
		}
		
		public function shouldNotBe($data, $message = null)
		{
			\PHPunit_Framework_Assert::assertNotEquals($data, $this->data, $message);
			return $this;
		}
		
		public function shouldBeNull($message = null)
		{
			\PHPunit_Framework_Assert::assertEquals(null, $this->data, $message);
			return $this;	
		}
		
		public function shouldNotBeNull($message = null)
		{
			\PHPunit_Framework_Assert::assertNotEquals(null, $this->data, $message);
			return $this;	
		}
		
		/**
		 * determines if the data evaluates to the expected value.
		 *
		 * @param string $message optional.
		 * @return $self
		 */
		public function equals($data, $message= null)
		{
			\PHPUnit_Framework_Assert::assertEquals($data, $this->data, $message);
			return $this;
		}
	
	}
}