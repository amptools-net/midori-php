<?php 
	
namespace Midori
{
	
	class ListOfSpec extends PHPUnit\Spec
	{
		
		public function setUp()
		{
			$this->list = new ListOf();
		}
		
		/**
		 * @test
		 * Enter description here...
		 * @return unknown_type
		 */
		public function itShouldAddItems()
		{
			$this->list->add("one", "two", "three");
			$this->expectsThat($this->list->count())->shouldBe(3);
			$this->expectsThat($this->list->first())->shouldBe("one");
		}
		
		/**
		 * @test
		 * Enter description here...
		 * @return unknown_type
		 */
		public function itShouldInsertItems()
		{
			$this->list =  new ListOf("one", "three");
			$this->list->insert(1, "two");
			$this->expectsThat($this->list->toArray())->shouldBe(array("one", "two", "three"));
		}
		
		/**
		 * @test 
		 * Enter description here...
		 * @return unknown_type
		 */
		public function itShouldRemoveItems()
		{
			$this->list = new ListOf("one", "two", "three", "four", "five");
			$this->list->remove("three", "five");
			$this->expectsThat($this->list->toArray())->shouldBe(array("one", "two", "four"));
	
			$this->list->removeAt(2);
			$this->expectsThat($this->list->toArray())->shouldbe(array("one", "two"));
		}
		
		/**
		 * @test
		 * Enter description here...
		 * @return unknown_type
		 */
		public function itShouldClearItems()
		{
			$this->list = new ListOf(array("one", "two"));
			$this->expectsThat($this->list->count())->shouldbe(2);
			$this->list->clear();
			$this->expectsThat($this->list->count())->shouldBe(0);
		}
	}
	
}
		