<?php


namespace Midori
{
	/**
	 * 
	 * @author Michael
	 * @package Midori
	 */
	class Regex extends Object
	{
		private $pattern;
		
		/**
		 * 
		 * Enter description here...
		 * @param $pattern
		 * @exception Midori_ArgumentNullException
		 * @return unknown_type
		 */
		public function __construct($pattern)
		{
			if($pattern == null)
				throw new ArgumentNullException("pattern");
			
			$this->pattern = $pattern;
		}
		
		/**
		 * 
		 * Enter description here...
		 * @param $input
		 * @param $replacement
		 * @param $limit
		 * @param $count
		 * @return unknown_type
		 */
		public function replace($input, $replacement, $limit = -1, &$count = null)
		{
			if(is_callable($replacement))
				return box_str(preg_replace_callback($this->pattern, $replacement, $input, $limit, $count));
			return box_str(preg_replace($this->pattern, $replacement, $input, $limit, $count));
		}
		
		/**
		 * 
		 * Enter description here...
		 * @param $input
		 * @param $startPosition
		 * @param $flags
		 * @return unknown_type
		 */
		public function match($input, $startPosition = 0, $flags  = null)
		{
			$matches = array();
			preg_match($this->pattern, $input, $matches, $flags, $startPosition);
			return isset($matches[0]) ? $matches[0] : null;
		}
		
		public function matches($input, $startPositions = 0, $flags = null)
		{
			$matches = array();
			preg_match($this->pattern, $input, $matches, $flags, $startPosition);
			return $matches;
		}
		
		public function test($input, $startPosition = 0, $flags = null)
		{
			return (boolean)preg_match($this->pattern, $input, $matches, $flags, $startPosition);
		}
		
		/**
		 * 
		 * Enter description here...
		 * @param $input
		 * @param $limit
		 * @param $flags
		 * @return unknown_type
		 */
		public function split($input, $limit = null, $flags = null)
		{
			return preg_split($this->pattern, $input, $limit, $flags);
		}
		
		public function toString($returnPhpString = false)
		{
			return $returnPhpString ? $this->pattern : new String($this->pattern);
		}
		
		/**
		 * (non-PHPdoc)
		 * @see src/Midori/Midori_Object#__toString()
		 */
		public function __toString()
		{
			return $this->pattern;
		}
	}
}

