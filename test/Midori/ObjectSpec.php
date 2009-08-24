<?php

namespace Midori
{
	class ObjectSpec extends PHPUnit\Spec
	{
		
		private $obj;
		
		public function setUp()
		{
			$this->obj = new  Object;
		}
		
		
		/**
		 * @test
		 */
		public function constructNewObject()
		{
			$obj = new Object();
			$this->expectsThat($obj)->isNotNull();
		}
		
		/**
		 * @test
		 * Enter description here...
		 * @return unknown_type
		 */
		public function classNameShouldBeMidoriObject()
		{
			$this->expectsThat($this->obj->getClassName(true))
				->shouldBe("Midori\Object");
		}
		
		/**
		 * @test
		 * Enter description here...
		 * @return unknown_type
		 */
		public function tostringShouldBeTheClassName()
		{
			$this->expectsThat($this->obj->toString(true))
				->shouldBe($this->obj->getClassName(true));
		}
	}
}