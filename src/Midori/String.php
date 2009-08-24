<?php

namespace Midori
{
	require_once "Inflector.php";
	require_once "Regex.php";
	/**
	 * 
	 * @author Michael
	 * @final true
	 * @package Midori
	 * @property integer $length Gets the length of the string
	 * @property string $value Gets or sets the actual unboxed string value.  
	 * @property boolean $hasValue Gets whether or not the value is not null.
	 * @property-read string $nullReplacementValue; Get the defaulted value for __toString when the value is null.
	 */
	final class String extends Nullable implements \ArrayAccess
	{
		
		/**
		 * constructor
		 * 
		 * @param string $value
		 * @return Midori\String the class
		 */
		public function __construct($value = "")
		{
			$value = unbox($value);
			
			if($value != null)
				$this->value = $value;	
		}
		
	
		/**
		 * 
		 * @ignore
		 * @see src/Midori/Midori_Nullable#getNullReplacementValue()
		 */
		protected function getNullReplacementValue()
		{
			return "";
		}	
		
		
		private function str($value)
		{
			return new String($value);
		}
		
		/**
		 * Ruby Misnomer, it pascalizes by default or returns the camelcase if
		 * true is passed in.
		 * 
		 * Enter description here...
		 * @param boolean		$lower
		 * @return Midori\String
		 */
		public function camelize($lower = false)
		{
			return $this->str(Inflector::camelize($this->value), $lower);
		}
		
		/**
		 * creates a copy of the string and returns it.
		 * 
		 * <p>Creates a copy of the current unboxed string and 
		 * returns it.</p>
		 * 
		 * <pre class="brush: php">
		 * 	$string = new Midori\String("value");
		 *  $copy = $string->copy();
		 *  echo ($string == $copy)? "true" : "false"; // true
		 * </pre>
		 * 
		 * @return Midori\String returns a copy of the string.
		 */
		public function copy()
		{
			return $this->str($this->value);
		}
		
	
		/**
		 * Compares the string value to another value and returns 
		 * if its this value is less than, equal, or greater than.
		 * 
		 * <p>Compares the strings and either returns 0 if the values
		 * are equal, -1 if the value is less than the value its
		 * being compared to, or 1 if the value is greater than
		 * the value its being compared to.</p>
		 * 
		 * <p>This comparsion is helpful for sorting strings alphanumerically.</p>
		 * 
		 * <pre class="brush: php">
		 * 	$string = new Midori\String("adam");
		 *  $valueToCompare = "nathan";
		 *  $comparison = $string->compareTo($valueToCompare);
		 *  echo $comparsion; // -1 (which is less than)
		 *  echo $string->compareTo("adam"); // 0 (which is equal)
		 *  echo $string->compareTo(str("abba")); 1 (which is greater than)
		 * </pre>
		 * 
		 * @param string|Midori\String 			$value 			The value that string is being compared to.
		 * @param boolean 						$ignoreCase 	<strong>[optional false]</strong> Instructs the
		 * 														the compre to be case insensitive or not.
		 * @see strcmp()
		 * @see strcasecmp()
		 * @return integer 0 if its equal, -1 if the current value is less than, 1 if its greater than.
		 */
		public function compareTo($value, $ignoreCase = false)
		{
			$value = $this->unbox($value);
			
			if($ignoreCase){
				$compare = strcasecmp($this->value, $value);
				if($compare > 0)
					return 1;
				if($compare === 0)
					return 0;
				return -1;
			}
			else
				return strcmp($this->value, $value); 
		}
		
		
		
		
		/**
		 * Joins the current string value with the value that
		 * is being passed to this method. 
		 * 
		 * <p>
		 * This method takes the current string and joins the string 
		 * that is being passed to the this method to create a new
		 * string of both values.
		 * </p>
		 * 
		 * <pre class="brush: php">
		 * 	$array = array("I have", " a enumerable object ", "of strings");
		 *  $start = new Midori\String("notice:");
		 *  $container = $start->concat(" this is a text. ");
		 *  foreach($array as $value)
		 *    	$container = $container->concat($value);
		 *  echo $container; // notice: this is a test. I have a enumerable object of strings
		 *  echo $start; // notice: 
		 * </pre>
		 * 
		 * @param mixed 				$value			The value that is to be appended to the new copy
		 * 												of the string that is returned.
		 * @return Midori\String the concatinated string of both values.
		 */
		public function concat($value)
		{
			return $this->str($this->value .$value);
		}
		
		/**
		 * Determines if the current string contains the value.
		 * 
		 * <p></p>
		 * <pre class="brush: php">
		 *   $sentence = str("Till the roof comes off, Till the lights go out");
		 *   if($sentence->contains("off"))
		 *   	echo "the lights have been turned off";
		 * </pre>
		 * 
		 * @exception Midori_ArgumentNullException  
		 * @param string|Midori\String 		$value 			The search value.
		 * @param boolean 					$ignoreCase 	<strong>[optional false]</strong> Instructs the 
		 * 													search to be case insenstive or not.
		 * @return boolean returns true if the string contains the value.
		 */
		public function contains($value, $ignoreCase = false)
		{
			if($value instanceof String)
				$value = $value->value;
			
			if($value == null)
				throw new ArgumentNullException("value");
			
			if($ignoreCase)
				return (stripos($this->value, $value) !== false);
			return (strpos($this->value, $value) !== false);
		}
		
		/**
		 * Determines if the current string ends with a certain value.
		 * 
		 * <p>
		 * This method compares the value to the current string and must match
		 * the full string or the specified ending of the string.  
		 * </p>
		 * 
		 * <pre class="brush: php">
		 * $sentence = str("My MySpace page is all totally pimped out");
		 * if($sentence->endsWith("out"))
		 * 	echo "pimped";
		 * </pre>
		 * 
		 * @exception Midori_ArgumentNullException
		 * @param string|Midori\String 		$value 			The search value of the expected end of the string.
		 * @param boolean  					$ignoreCase 	<strong>[optional false]</strong> Instructs the
		 * 													search to be case insensitive or not. 
		 * @return boolean Returns true if the string ends with the specified value.
		 */
		public function endsWith($value, $ignoreCase = false)
		{
			if($value instanceof String)
				$value = $value->value;
			
			if($value == null)
				throw new ArgumentNullException("value");
			
			if($this->value == $value)
				return true;
				
			$flags = $ignoreCase ? "i" : "";
			return (boolean)preg_match("/{$value}$/{$flags}", $this->value);
		}
		
		/**
		 * Overridden. Determines if the strings have the same value
		 * 
		 * <p>
		 * Equals is overriden to do a string comparion of the 2 values
		 * to see if they are equal, it also takes a second parameter to 
		 * see if comparison needs to be case insensitive.
		 * </p>
		 * 
		 * <pre class="brush: php">
		 * $sentence = str("My MySpace page is all totally pimped out");
		 * if(!$sentence->equals("big pimping spending gs"))
		 * 	echo "ahh homie we're out of luck, lets go find some bling";
		 * </pre>
		 * 
		 * @param mixed 		$value 			The value that is being compared to the current string.
		 * @param boolean 		$ignoreCase 	<strong>[optional false]</strong> Instructs the 
		 * 										the method to be case insensitive or not.
		 * @return boolean
		 */
		public function equals($value, $ignoreCase = false)
		{
			$value = $this->unbox($value);
				
			if($ignoreCase)
				return $this->compareTo($value, true) === 0;
			return $value == $this->value;
		}
		
		/**
		 * Formats the string and Replaces the place holders in the 
		 * string with the arguments that are passed in. 
		 * 
		 * <p>Using {@link sprintf() sprintf} internally, the format method replaces
		 * the values in the current string passes back a copy of the 
		 * formatted string with values replaced.</p> 
		 * 
		 * <pre class="brush: php">
		 *   $noun = "man";
		 *   $comedicAccent = "yipe";
		 *   $value = str('I am %2$s, hear me roar, %1$s!')
		 *   			->format($comedicAccent,$noun);
		 *   echo $value; // I am man! hear me roar, yipe!
		 * </pre>
		 * 
		 * @see sprintf()
		 * @return Midori\String a formatted copy of the string with the replaced values.
		 */
		public function format()
		{
			$temp = func_get_args();
			$args = array($this->value);
			foreach($temp as $value)
				$args[] = $value;	
			return $this->str(call_user_func_array("sprintf", $args));	
		}
		
		/**
		 * Determines the index value of the string that is being 
		 * searched for.
		 * 
		 * <p>
		 * 	Searches the string for a specified value and returns the index or returns
		 *  a -1 if the index is not found.
		 * </p>
		 * 
		 * <pre class="brush: php">
		 * 	$line = str("I'll be back");
		 *  echo $line->indexOf("back"); 		// 8
		 *  echo $line->indexOf("i'll", true); 	// 0
		 *  echo $line->indexOf("i'll"); 		// -1
		 * </pre>
		 *
		 * @param string|Midori\String	 	$value			The search value to find in the string.
		 * @param boolean 					$ignoreCase		<strong>[optional false]</strong> Instructs the 
		 * 													search to be case insensitive or not.
		 * @param integer					$startPosition	<strong>[optional 0]</strong> The index of the 
		 * 													position to start searching in the string.
		 * @param integer 					$limit			<strong>[optional null]</strong> The amount 
		 * 													of characters to search after the start position.
		 * @return integer 
		 */
		public function indexOf($value, $ignoreCase= false, $startPosition = 0, $limit = null)
		{
			$value = $this->unbox($value);
			$search = $limit ? substr($this->value, 0, $startPosition + $limit) : $this->value;
			$result = -1;
			if($ignoreCase)
				$result = stripos($search, $value, $startPosition);
			else 
				$result = strpos($search, $value, $startPosition);
			return is_int($result) ? $result : -1;
		}
		
		/**
		 * Inserts, injects a string value into the current string. 
		 * 
		 * <p>
		 * Using the index as the place to determine where to inject, this
		 * method will break a part a string into parts, and then concatinate
		 * all three parts together. 
		 * </p>
		 * 
		 * <pre class="brush: php">
		 *	$line = str("Insert funny here !");
		 *	$index = $line->indexOf("!");
		 *	echo $line->insert($index, "TTLY MY BFF LOL "); //"Insert funny here TTLY MY BFF LOL !"
		 * </pre>
		 * 
		 * @param string|Midori\String 	$value 			The value to be inserted into the string.
		 * @param integer 				$startIndex		The starting position/index to inject 
		 * 												the value into the string.
		 * @return Midori\String returns a copy of the string with the inserted value.
		 */
		public function insert($startIndex, $value)
		{
			$before = substr($this->value, 0, $startIndex);
			$after = substr($this->value, $startIndex);
			return $this->str($before.$this->unbox($value).$after);
		}
		
		/**
		 * Gets if the string is empty.
		 * 
		 * @ignore
		 * @return boolean returns true if the string is empty.
		 */
		protected function getEmpty()
		{
			return ($this->length == 0);
		}
		
		
		/**
		 * Gets if the string is null or empty.
		 * 
		 * @ignore 
		 * @return boolean returns true if the string is null or empty.
		 */
		protected function getIsNullOrEmpty()
		{
			return $this->length == 0;
		}
		
		/**
		 * gets the length of the string.
		 * 
		 * @ignore
		 * @return integer the length of the string
		 */
		protected function getLength()
		{
			return strlen($this->value);
		}
		
		/**
		 * 
		 * Enter description here...
		 * @param $pattern
		 * @param $replacement
		 * @param $limit
		 * @param $count
		 * @return unknown_type
		 */
		public function gsub($pattern, $replacement, $limit = -1, $count = null)
		{
			return regex($pattern)->replace($this->value, $replacement, $limit, $count);
		}
		
		/**
		 * Determines the last index of the specified value in the string.
		 * 
		 * 
		 * 
		 * 
		 * 
		 * @param string|Midori\String	 	$value			The search value to find in the string.
		 * @param boolean 					$ignoreCase		<strong>[optional false]</strong> Instructs the 
		 * 													search to be case insensitive or not.
		 * @param integer					$startPosition	<strong>[optional 0]</strong> The index of the 
		 * 													position to start searching in the string.
		 * @param integer 					$limit			<strong>[optional null]</strong> The amount 
		 * 													of characters to search after the start position.
		 * @return index the position of value or -1 if the value is not found.
		 */
		public function lastIndexOf($value, $ignoreCase= false, $startPosition = 0, $limit = null)
		{
			$value = $this->unbox($value);
			$search = $limit ? substr($this->value, 0, $startPosition + $limit) : $this->value;
	
			if($ignoreCase)
				$result = strripos($this->value, $value, $startPosition);
			else 
				$result = strrpos($this->value, $value, $startPosition);
			return is_int($result) ? $result : -1;
		}
		
		/**
		 * 
		 * Enter description here...
		 * @param $pattern
		 * @param $startPosition
		 * @param $flags
		 * @return unknown_type
		 */
		public function match($pattern, $startPosition = 0, $flags = null)
		{
			return regex($pattern)->match($this->value, $startPosition, $flags);
		}
		
		/**
	     * Determines if the offset exists, should not be called directly.
	     *
	     * <p> This is called when using {@see isset()} with an index. </p>
	     *
	     * <pre class="brush: php">
	     * $str = box_str("abcef");
	     * $found = isset($str[0]);
	     * $found = isset($str['ab']);
	     * $found = isset($str[box_regex('/ab/')]);
	     * </pre>
	     *
	     * @param integer|string|Midori\Regex $index the character index.
	     * @return boolean
		 */
		public function offsetExists($index)
		{
			$index = $this->unbox($index);
			
			if($index == null)
				throw new ArgumentNullException("index");
			if(is_int($index))
				return isset($this->value[$index]);
			if(is_string($index))
				return $this->contains($index);
			if($index instanceof Regex)
				return $index->isMatch($this->value);
			
			return false;
		}
		
		/**
	     * Gets the characters from a string if the index is found.
	     * 
	     * <p>
	     * This is forced public method of  {@see ArrayAccess} 
	     * interface. This method should not be used directly, instead 
	     * use the actual array access method directly.
	     * </p>
	     * 
	     * <pre class="brush: php">
	     * $str = box_str("abcdef");
	     * 
	     * echo $str[3]; // d
	     * echo $str['de']; // de
	     * echo $str['g']; // null
	     * echo $str[box_regex("/ab/")]; // ab
	     * </pre>
	     * 
	     * 
	     * @param integer|string|Midori\Regex $index the index offset of characters.
	     * @return string|null
		 */
		public function offsetGet($index)
		{
			if($index == null)
				throw new ArgumentNullException("index");
			if(is_int($index))
				return $this->str($this->value[$index]);
			if(is_string($index) && $this->contains($index))
				return $this->str($index);
			if($index instanceof Regex)
			{
				$matches = $index->match($this->value);
				$str = $matches ?: null;
				return $this->str($str);
			}
			return null;
		}
		
		/**
	     * Sets the characters by index.
	     *
	     * <p>
	     * This is forced public method of  {@see ArrayAccess} 
	     * interface. This method should not be used directly, instead 
	     * use the actual array access method directly.
	     * </p>
	     * 
	     * <pre class="brush: php">
	     * $str = box_str("abcdef");
	     * 
	     * $str[3] = "hi";
	     * echo $str; // abchif
	     * 
	     * $str = box_str("abcdef");
	     * $str['ef'] = "hi";
	     * echo $str; // abcdhif
	     *
	     * $str = box_str("abcdef");
	     * $str[box_regex("ab")] = "hi";
	     * echo $str; // hicdef
	     * </pre>
	     *
	     * @param integer|string|Midori\Regex 	$index 		the index offset of characters.
	     * @param string|Midori\String			$value		the value that will replace the index.
	     * @return void
		 */
		public function offsetSet($index, $value)
		{
			$value = $this->unbox($value);
			
			if(is_int($index))
			{
				$before = substr($this->value, 0, $index);
				$after = substr($this->value, $index+1);
				$this->value = $before.$value.$after;
			}
			
			if(is_string($index))
				$this->value = str_replace($index, $value, $this->value);
			
		
			if($index instanceof Regex)
			{
				$temp = $index->replace($this->value, $value)->value;
				$this->value = $temp;
			}
		}
		
		/**
	     * Unsets a value within a string, should not be used directly
	     *
	     * <p> This is called when using {@see unset()} with an index. </p>
	     * 
	     * <pre class="brush: php">
	     *   $str = box_str("abcdef");
	     *   unset($str[0]);
	     *   echo $str; // bcdef
	     *   
	     *   $str = box_str("abcdef");
	     *   unset($str['cd']);
	     *   echo $str; // abef
	     *   
	     *   $str = box_str("abcf");
	     *   unset($str[box_regex('/ab/')]);
	     *   echo $str; // cdef
	     * </pre>
	     * 
	     * @param integer|string|Midori\Regex $index the character index.
	     * @return boolean
		 */
		public function offsetUnset($index)
		{
			if(is_int($index))
			{
				$before = substr($this->value, 0, $index);
				$after = substr($this->value, $index+1);
				$this->value = $before.$after;
			}
			if(is_string($index))
				$this->value = str_replace($index, "", $this->value);
			
			if($index instanceof Regex)
				$this->value = $this->unbox($index->replace($this->value, ""));
		}
		
		/**
		 * Adds padding to the left of the string. 
		 * 
		 * 
		 * @param integer $totalWidth
		 * @param string $paddingChars
		 * @return Midori\String
		 */
		public function padLeft($totalWidth, $paddingChars = " ")
		{
			return $this->str(str_pad($this->value, $totalWidth, $paddingChars, STR_PAD_LEFT));
		}
		
		/**
		 * Adds padding to the right of the string.
		 * 
		 * Enter description here...
		 * @param integer $totalWidth
		 * @param string $paddingChars
		 * @return Midori\String
		 */
		public function padRight($totalWidth, $paddingChars = " ")
		{
			return $this->str(str_pad($this->value, $totalWidth, $paddingChars, STR_PAD_RIGHT));
		}
		
		/**
		 * 
		 * Enter description here...
		 * @param string $search
		 * @param string $replacement
		 * @param integer $limit
		 * @param integer $count
		 * @return Midori\String
		 */
		public function replace($search, $replacement = "",  $limit = -1, $count = null)
		{
			return $this->str(str_replace($this->unbox($search), $this->unbox($replacement), $this->value, $limit));
		}
		
		/**
		 * Reverses the string.
		 * 
		 * Enter description here...
		 * @return unknown_type
		 */
		public function reverse()
		{
			return $this->str(strrev($this->value));
		}
		
		
		
		public function startsWith($value, $ignoreCase = false)
		{	
			if($value instanceof String)
				$value = $value->value;
			
			if($value == null)
				throw new ArgumentNullException("value");
			
			if($this->value == $value)
				return true;
				
			$flags = $ignoreCase ? "i" : "";
			return (bool)preg_match("/^{$value}/{$flags}", $this->value);
		}
		
		/**
		 * 
		 * Enter description here...
		 * @param integer $start
		 * @param integer $length
		 * @return Midori\String
		 */
		public function substring($start, $length = null)
		{
			return $this->str(substr($this->value, $start, $length));
		}
		
		/**
		 * Trims whitespace or specified characters 
		 * from the outer parts of the string.
		 * 
		 * <p>
		 *    
		 * </p>
		 * 
		 * <pre class="brush: php">
		 *    $beard = box_str("!!!my beard has growth!!!")->trim("!");
		 *    echo $beard; // my beard has growth
		 * </pre>
		 * 
		 * @param string $chars optional.
		 * @return Midori\String
		 */
		public function trim($chars = ' ')
		{
			return $this->str(trim($this->value, $chars));	
		}
		
		/**
		 * Trims whitespace or specified characters from
		 * the end (right) of the string.
		 * 
		 * <p> this will trim the characters from the end or right 
		 * side of the string </p>
		 * 
		 * <pre class="brush: php">
		 *    $beard = box_str("!!!my beard has growth!!!")->trimEnd("!");
		 *    echo $beard; // !!!my beard has growth
		 * </pre>
		 * 
		 * @param string $chars optional
		 * @return Midori\String
		 */
		public function trimEnd($chars = ' ')
		{
			return $this->str(rtrim($this->value, $chars));	
		}
		
		/**
		 * Trims whitespace or specified characters from
		 * the start (left) of the string.
		 * 
		 * <p> it will trim the characters from the start or left side
		 * of a string </p>
		 * 
		 * <pre class="brush: php">
		 *    $beard = box_str("!!!my beard has growth!!!")->trimStart("!");
		 *    echo $beard; // my beard has growth!!!
		 * </pre>
		 * 
		 * @param string $chars optional
		 * @return Midori\String
		 */
		public function trimStart($chars = ' ')
		{
			return $this->str(ltrim($this->value, $chars));
		}
		
		
		/**
		 * creates a new Midori\String to wrap the value.
		 * 
		 * <p> {@link http://us3.php.net/manual/en/language.oop5.magic.php#language.oop5.magic.invoke __invoke} is
		 * magically called when you try to use this class like a function.</p>
		 * 
		 * <pre class="brush: php">
		 *   $str = new Midori\String();
		 *   $x = $str("my cool string value");
		 *   $y = $str("i miss ruby");
		 *   
		 *   echo $x; // my cool string value
		 *   echo $y; // i miss ruby
		 * </pre>
		 * 
		 * @param string 			$value 			The value that is to be wrapped into an {@see Midori\String}
		 * @return Midori\String creates string object wrapped around the value.
		 */
		public function __invoke($value)
		{
			return $this->str($value);
		}
		
		
	}
}
	
	
	
