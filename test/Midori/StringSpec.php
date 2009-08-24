<?php
	
namespace Midori
{
	
	
	class StringSpec extends PHPUnit\Spec
	{
		
		public function setUp()
		{
			$this->string = new String("test value");
		}
		
		/**
		 * 
		 * Enter description here...
		 * @test
		 * @return unknown_type
		 */
		public function itShouldFormatValues()
		{
			$arg2 = "man";
			$arg1 = "yipe";
			
			$value = box_str('I am %2$s, hear me roar, %1$s!')
						->format($arg1, $arg2);
			
			
			$this->expectsThat($value)->shouldBe("I am man, hear me roar, yipe!");
		}
		
		/**
		 * @test
		 * Enter description here...
		 * @return unknown_type
		 */
		public function itShouldCompareStrings()
		{
			$first = box_str("I am first");
			$second = "Second in line";
			$cut = "cutting in line";
			$caseCute = box_str("Cutting in line");
			
			$compare = $first->compareTo($second);
			$this->expectsThat($compare)->shouldBe(-1);
			
			$compare = $first->compareTo($cut, true);
			$this->expectsThat($compare)->shouldBe(1);
			
			$compare = $caseCute->compareTo($cut);
			$this->expectsThat($compare)->shouldBe(-1);
			
			$compare = $caseCute->compareTo($cut, true);
			$this->expectsThat($compare)->shouldBe(0);
		}
		
		/**
		 * @test
		 * Enter description here...
		 * @return unknown_type
		 */
		public function itShouldConcatinateStrings()
		{
			$array = array("I have", " a enumerable object ", "of strings");
			$start = new String("notice:");
			$container = $start->concat(" this is a test. ");
			foreach($array as $value)
				$container = $container->concat($value);
	
			$this->expectsThat($start)->equals("notice:");
			
			$this->expectsThat($container)
				->equals("notice: this is a test. I have a enumerable object of strings");
			
		}
		
		/**
		 * @test
		 * Enter description here...
		 * @return unknown_type
		 */
		public function itShouldDetermineIfAStringContainsAValue()
		{
			$sentence = box_str("Till the roof comes off, Till the lights go out");
		 	$this->expectsThat($sentence->contains("off"))->isTrue();
		 	$this->expectsThat($sentence->contains("till", true))->isTrue();
		 	$this->expectsThat($sentence->contains("till"))->isFalse();
		 	
		}
		
		/**
		 * @test
		 * Enter description here...
		 * @return unknown_type
		 */
		public function itShouldDetermineIfAStringEndsWithAValue()
		{
			$sentence = box_str("My MySpace page is all totally pimped out");
			$this->expectsThat($sentence->endsWith("out"))->isTrue();
			$this->expectsThat($sentence->endsWith("Out"))->isFalse();
			$this->expectsThat($sentence->endsWith("OUT", true))->isTrue();
		}
		
		/**
		 * @test
		 * Enter description here...
		 * @return unknown_type
		 */
		public function itShouldDetermineIfStringsAreEqual()
		{
			$sentence = box_str("My MySpace page is all totally pimped out");
		 	$this->expectsThat($sentence->equals("big pimping spending gs"))->isFalse();
		 	$this->expectsThat($sentence->equals("My MySpace page is all totally pimped out"))->isTrue();
		 	$this->expectsThat($sentence->equals("my mySpace page is all totally pimped out", true))->isTrue();		
		}
		
		/**
		 * @test
		 * Enter description here...
		 * @return unknown_type
		 */
		public function itShouldFormatAString()
		{
			$formatted = box_str("mad libs: I am a %s uncle")->format("monkey's");
			$this->expectsThat($formatted)->shouldBe("mad libs: I am a monkey's uncle");
		}
		
		/**
		 * @test
		 * Enter description here...
		 * @return unknown_type
		 */
		public function itShouldDetectAnEmptyString()
		{
			$this->expectsThat(box_str("")->empty)->shouldBe(true);
			$this->expectsThat(box_str(" ")->empty)->shouldBe(false);
		}
		/**
		 * @test
		 * Enter description here...
		 * @return unknown_type
		 */
		public function itShouldDetectANullOrEmptyString()
		{
			$this->expectsThat(box_str(null)->isNullOrEmpty)->shouldBe(true);
			$this->expectsThat(box_str("")->isNullOrEmpty)->shouldBe(true);	
			$this->expectsThat(box_str(" ")->isNullOrEmpty)->shouldBe(false);
		}
		
		/**
		 * @test
		 * Enter description here...
		 * @return unknown_type
		 */
		public function itShouldDetectTheLength()
		{
			$this->expectsThat(box_str("")->length)->shouldBe(0);
			$this->expectsThat(box_str("mind numbing tests")->length)->shouldBe(18);
			$this->expectsThat(box_str(null)->length)->shouldBe(0);
		}
		
		/**
		 * @test
		 * Enter description here...
		 * @return unknown_type
		 */
		public function itShouldBeAbleToGrepAndReplace()
		{
			$grepped = box_str("I am the president")->gsub("/\bpresident\b/", "loser");
			$this->expectsThat($grepped)->shouldBe("I am the loser");
		}
		
		/**
		 * @test
		 * Enter description here...
		 * @return unknown_type
		 */
		public function itShouldGetTheLastIndex()
		{
			$lastIndex = box_str("get me the last cup of T")->lastIndexOf("t");
			$this->expectsThat($lastIndex)->shouldBe(14);
			
			$lastIndex = box_str("get me the last cup of T")->lastIndexOf("t", true);
			$this->expectsThat($lastIndex)->shouldBe(23);
		}
		
		/**
		 * @test
		 * Enter description here...
		 * @return unknown_type
		 */
		public function itShouldMatchValue()
		{
			$match = box_str("i am a match")->match("/match/");
			$this->expectsThat($match)->shouldBe("match");
		}
		
		/**
		 * @test
		 * Enter description here...
		 * @return unknown_type
		 */
		public function itShouldPadLeft()
		{
			$padding = box_str("oh some cushy padding")->padLeft(25, ".");
			$this->expectsThat($padding)->shouldBe("....oh some cushy padding");
		}
		
		/**
		 * @test
		 * Enter description here...
		 * @return unknown_type
		 */
		public function itShouldPadRight()
		{
			$padding = box_str("oh some cushy padding")->padRight(25, ".");
			$this->expectsThat($padding)->shouldBe("oh some cushy padding....");
		}
		
		/**
		 * @test
		 * Enter description here...
		 * @return unknown_type
		 */
		public function itShouldReplaceValues()
		{
			$replaced = box_str("i am a play station 3")->replace("play station 3", "xbox 360");
			$this->expectsThat($replaced)->shouldBe("i am a xbox 360");
		}
		
	    /**
		 * @test
		 * Enter description here...
		 * @return unknown_type
		 */
		public function itShouldTrim()
		{
			$trimmed = box_str(" i need space  ")->trim();
			$this->expectsThat($trimmed)->shouldBe("i need space");
		}
		
	    /**
		 * @test
		 * Enter description here...
		 * @return unknown_type
		 */
		public function itShouldTrimStart()
		{
			$trimmed = box_str(" i need space  ")->trimStart();
			$this->expectsThat($trimmed)->shouldBe("i need space  ");
		}
		
	    /**
		 * @test
		 * Enter description here...
		 * @return unknown_type
		 */
		public function itShouldTrimEnd()
		{
			$trimmed = box_str(" i need space  ")->trimEnd();
			$this->expectsThat($trimmed)->shouldBe(" i need space");
		}
		
		/**
		 * @test
		 * Enter description here...
		 * @return unknown_type
		 */
		public function itShouldDetermineTheIndexOfAValue()
		{
			$line = box_str("I'll be back");
			
			$this->expectsThat($line->indexOf("back"))->shouldBe(8);
			$this->expectsThat($line->indexOf("got back"))->shouldBe(-1);
			
			$this->expectsThat($line->indexOf("i'll", true))->shouldBe(0);
			$this->expectsThat($line->indexOf("be", false, 2))->shouldBe(5);
			$this->expectsThat($line->indexOf("be", false, 2, 3))->shouldBe(-1);
			$this->expectsThat($line->indexOf("be", false, 2, 5))->shouldBe(5);
		}
		
		/**
		 * @test
		 * Enter description here...
		 * @return unknown_type
		 */
		public function itShouldInsertAStringValueIntoTheString()
		{
			$line = box_str("Insert funny here !");
			$index = $line->indexOf("!");
			$value = $line->insert($index, "TTLY MY BFF LOL ");
			$this->expectsThat($value)->shouldBe("Insert funny here TTLY MY BFF LOL !");
		}
		
		/**
		 * 
		 * @test
		 */
		public function itShouldGetTheCharactersByIndex()
		{
			$str = box_str("abcdef");
			$char = $str[3];
			
			$this->expectsThat($char)->shouldBe("d");
			
			$this->expectsThat($str["de"])->shouldBe("de");
			$this->expectsThat($str["z"])->shouldBe(null);
	
			$this->expectsThat($str[box_regex("/ab/")])->shouldBe("ab");
		}
		
		/**
		 * 
		 * @test
		 */
		public function itShouldReplaceTheCharactersByIndex()
		{
			$str = box_str("abcdef");
			$str[3] = "hi";
			
			$this->expectsThat($str)->shouldBe("abchief");
			
			$str = box_str("abcdef");
			$str["ef"] = "hi";
			
			$this->expectsThat($str)->shouldBe("abcdhi");
			
			$str = box_str("abcdef");
			$str[box_regex('/ab/')] = "hi";
			
			$this->expectsThat($str)->shouldBe("hicdef");
			
		}
	}
}