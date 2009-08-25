<?php 

namespace Midori
{
	class ArrayOfSpec extends PHPUnit\Spec
	{
		
		public function setUp()
		{
			$this->array = new ArrayOf("one", "two", "three", "four", "five");
		}
		
		/**
		 * @test
		 * Enter description here...
		 * @return unknown_type
		 */
		public function itShouldPack()
		{
			$result = $this->array->pack("A6");
			$this->expectsThat($result)->shouldBe("one   two   three four  five  ");
		}
		
		/**
		 * @test
		 * Enter description here...
		 * @return unknown_type
		 */
		public function itShouldPush()
		{
			$count = $this->array->push("six", "seven")->count();
			
			$this->expectsThat($count)->shouldBe(7);
			$this->expectsThat($this->array[6])->shouldBe("seven");
		}
		
		/**
		 * @test
		 * Enter description here...
		 * @return unknown_type
		 */
		public function itShouldPop()
		{
			$result = $this->array->pop();
			$this->expectsThat($this->array->count())->shouldBe(4);
			$this->expectsThat($result)->shouldBe("five");	
		}
		
		/**
		 * @test
		 * Enter description here...
		 * @return unknown_type
		 */
		public function itShouldShift()
		{
			$result = $this->array->shift();
			$this->expectsThat($result)->shouldBe("one");
			$this->expectsThat($this->array->count())->shouldBe(4);
		}
		
		/**
		 * @test
		 * Enter description here...
		 * @return unknown_type
		 */
		public function itShouldUnShift()
		{
			$result = $this->array->unshift("a", "b")->toArray();
			$this->expectsThat($result)->shouldBe(array("a", "b", "one", "two", "three", "four", "five"));
		}
		
		/**
		 * @test
		 * Enter description here...
		 * @return unknown_type
		 */
		public function itShouldClone()
		{
			$result = $this->array->copy();
			$result->shift();
			$this->expectsThat($result->toArray())->shouldNotBe($this->array->toArray());
			$this->expectsThat($result->count())->shouldBe(4);
		}
		
	}
}