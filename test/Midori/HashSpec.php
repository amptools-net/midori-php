<?php

namespace Midori
{

	class HashSpec extends PHPUnit\Spec
	{
	
		public function setUp()
		{
			
		}
		
		private function createHash()
		{
			$hash = new Hash();
			$hash["one"] = 1;
			$hash["Braveheart"] = "freedom";
			$hash["blink182"] = "i'm feeling this";				
			return $hash;
		}
		
		/**
		 *
		 * @test
		 */
		public function itShouldCreateHash()
		{
			$hash = $this->createHash();
			$this->expectsThat($hash)->shouldNotBeNull();		
		}
		
		/**
		 *
		 * @test
		 */
		public function itShouldHaveArrayAccess()
		{
			$hash = $this->createHash();
			$this->expectsThat($hash["one"])->shouldBe(1);
			$this->expectsThat(isset($hash["one"]))->isTrue();
			$this->expectsThat($hash["two"])->shouldBe(null);
			$this->expectsThat(isset($hash["two"]))->isFalse();
			unset($hash["one"]);
			$this->expectsThat(isset($hash["one"]))->isFalse();
			$hash["two"] = 1;
			$this->expectsThat(isset($hash["two"]))->isTrue();
			$this->expectsThat($hash["two"])->shouldBe(1);
			
		}
	
		/**
		 *
		 * @test
		 */
		public function itShouldGetKeys()
		{
			$hash = $this->createHash();
			$keys = $hash->keys;
			$this->expectsThat($keys)->shouldNotBeNull();
			$this->expectsThat(count($keys))->shouldBe(3);
			$this->expectsThat($keys[0])->shouldBe("one");
		}	
			
		/**
		 * @test
		 */
		public function itShouldGetValues()
		{
			$hash = $this->createHash();
			$values = $hash->values;
			$this->expectsThat($values)->shouldNotBeNull();
			$this->expectsThat(count($values))->shouldBe(3);
			$this->expectsThat($values[0])->shouldBe(1);
		}	
		
		/**
		 * @test
		 */
		public function itShouldMergeHashes()
		{
			$hash = $this->createHash();
			$options = new Hash();
			$options["blink182"] = "all the small things";
			$options["story of the year"] = "anthem of our dying day";
			
			$this->expectsThat($hash->count())->shouldBe(3);
			$this->expectsThat($hash["blink182"])->shouldBe("i'm feeling this");
			
			$hash->merge($options);
			
			$this->expectsThat($hash->count())->shouldBe(4);
			$this->expectsThat($hash["blink182"])->shouldBe("all the small things");
			$this->expectsThat($hash["story of the year"])->shouldBe("anthem of our dying day");
		}
		
		/**
		 * @test
		 */
		public function itShouldItertate()
		{
			$hash = $this->createHash();
			$count = 0;
			foreach($hash as $key => $value)
			{
				if($count == 0)
				{
					$this->expectsThat($key)->shouldBe("one");
					$this->expectsThat($value)->shouldBe(1);
				}
				
				if($count == 1)
				{
					$this->expectsThat($key)->shouldBe("Braveheart");
					$this->expectsThat($value)->shouldBe("freedom");
				}
				
				if($count == 2)
				{
					$this->expectsThat($key)->shouldBe("blink182");
					$this->expectsThat($value)->shouldBe("i'm feeling this");
				}
				
				
				$count++;
			}		
			
			$this->expectsThat($count)->shouldBe(3);
		}
		
		/**
		 * @test
		 */
		public function itShouldIterateUsingEach()
		{
			$hash = $this->createHash();
			$self = $this;
			$count = 0;
			$hash->each(function($key, $value) use(&$count, $self) {
				
				if($count == 0)
				{
					$self->expectsThat($key)->shouldBe("one");
					$self->expectsThat($value)->shouldBe(1);
				}
				
				if($count == 1)
				{
					$self->expectsThat($key)->shouldBe("Braveheart");
					$self->expectsThat($value)->shouldBe("freedom");
				}
				
				if($count == 2)
				{
					$self->expectsThat($key)->shouldBe("blink182");
					$self->expectsThat($value)->shouldBe("i'm feeling this");
				}
				$count++;
			});	
			
			$this->expectsThat($count)->shouldBe(3);		
		}
		
		/**
		 * @test
		 */
		public function itShouldMapValuesIntoANewHash()
		{
			$hash = $this->createHash();
			$results = $hash->map(function($key, $value){
				return box_str($key)->startsWith("b", true);
			});		
			
			$this->expectsThat($results)->isNotNull();
			$this->expectsThat(count($results))->shouldBe(2);
			$this->expectsThat(isset($results["Braveheart"]))->isTrue();
		}
		
		/**
		 * @test
		 */
		public function itShouldDetermineIfAHashHasAKey()
		{
			$hash = $this->createHash();		
			$this->expectsThat($hash->haskey("blink182"))->isTrue();
			$this->expectsThat($hash->hasKey("greenday"))->isFalse();
		}
		
		/**
		 * @test
		 */
		public function itShouldDetermineIfAHashHasAValue()
		{
			$hash = $this->createHash();
			$this->expectsThat($hash->hasValue(1))->isTrue();
			$this->expectsThat($hash->hasValue(2))->isFalse();		
		}
		
		
			
	}	
}