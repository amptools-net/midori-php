<?php 

namespace Midori
{
	class EnumerableSpec extends PHPUnit\Spec
	{
		
		public function setUp()
		{
			$this->enumerable = new Enumerable("one", "two", "three", "four", "five");
		}
		
		/**
		 * @test
		 * Enter description here...
		 * @return unknown_type
		 */
		public function itShouldHaveACountAndLength()
		{
			$this->expectsThat($this->enumerable->count())->shouldBe(5);
			$this->expectsThat($this->enumerable->length)->shouldBe(5);
		}
		
		/**
		 * @test
		 * Enter description here...
		 * @return unknown_type
		 */
		public function itShouldIterateUsingEach()
		{
			$result = "";
			$this->enumerable->each(function($item) use(&$result) {
				$result .= $item.",";
			});
			
			$this->expectsThat($result)->shouldBe("one,two,three,four,five,");
		}
		
		/**
		 * @test
		 * Enter description here...
		 * @return unknown_type
		 */
		public function itShouldFindAValue()
		{
			$result = $this->enumerable->find(function($item){
				return box_str($item)->startsWith("fo");
			});
			
			$this->expectsThat($result)->shouldBe("four");
		}
		
		/**
		 * @test
		 * Enter description here...
		 * @return unknown_type
		 */
		public function itShouldMapOrFindAll()
		{
			$result = $this->enumerable->map(function($item){
				return box_str($item)->startsWith("t");
			});
			
			$this->expectsThat($result)->shouldBe(array("two", "three"));
			
			$result = $this->enumerable->findAll(function($item) {
				return box_str($item)->startsWith("f");
			});
			
			$this->expectsThat($result)->shouldBe(array("four", "five"));
		}
		
		/**
		 * @test
		 * Enter description here...
		 * @return unknown_type
		 */
		public function itShouldGetTheFirstObject()
		{
			$this->expectsThat($this->enumerable->first())->shouldBe("one");
		}
		
		/**
		 * @test
		 * Enter description here...
		 * @return unknown_type
		 */
		public function itShouldGetTheLastObject()
		{
			$this->expectsThat($this->enumerable->last())->shouldBe("five");
		}
		
		/**
		 * @test
		 */
		public function itShouldGetTheIndexOf()
		{
			$this->expectsThat($this->enumerable->indexOf("two"))->shouldBe(1);	
		}
		
		/**
		 * @test
		 * Enter description here...
		 * @return unknown_type
		 */
		public function itShouldGetTheLastIndexOf()
		{
			$this->expectsThat($this->enumerable->lastIndexOf("four"))->shouldBe(3);	
		}
		
		/**
		 * @test
		 * Enter description here...
		 * @return unknown_type
		 */
		public function itShouldJoinAllItems()
		{
			$this->expectsThat($this->enumerable->join(","))->shouldBe("one,two,three,four,five");
		}
		
		/**
		 * @test
		 * Enter description here...
		 * @return unknown_type
		 */
		public function itShouldReverseItems()
		{
			$results = $this->enumerable->reverse()->toArray();
			$this->expectsThat($results)->shouldBe(array("five", "four", "three", "two", "one"));	
		}
		
		/**
		 * @test
		 * Enter description here...
		 * @return unknown_type
		 */
		public function itShouldSortItems()
		{
			$results = $this->enumerable->sort()->toArray();
			$this->expectsThat($results)->shouldBe(array("five", "four", "one", "three", "two"));
		}
		
		/**
		 * @test
		 * Enter description here...
		 * @return unknown_type
		 */
		public function itShouldSkip()
		{
			$results = $this->enumerable->skip(1);
			$this->expectsThat($results->toArray())->shouldBe(array("two", "three", "four", "five"));
		}
		
		/**
		 * @test
		 * Enter description here...
		 * @return unknown_type
		 */
		public function itShouldTake()
		{
			$results = $this->enumerable->skip(1)->take(1);
			$this->expectsThat($results->toArray())->shouldBe(array("two"));
		}
		
		/**
		 * @test
		 * Enter description here...
		 * @return unknown_type
		 */
		public function itShouldBeIndexed()
		{
			$this->expectsThat($this->enumerable[2])->shouldBe("three");
			unset($this->enumerable[4]);
			$this->expectsThat($this->enumerable->count())->shouldBe(4);
			$this->expectsThat(isset($this->enumerable[4]))->shouldBe(false);
		}
	}
}