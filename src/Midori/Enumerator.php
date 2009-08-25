<?php 

namespace Midori
{

	/**
	 * 
	 * @author Michael
	 * @package Midori 
	 */
	class Enumerator extends Object implements \Iterator, \Countable, \SeekableIterator
	{
		protected $items = array();
		
		/**
		 * 
		 * @param $items array
		 */
		public function __construct(array $items)
		{
			$this->items = $items;
		}
		
		/**
		 * Resets/Rewinds the pointer in the array 
		 * to the first item
		 * 
		 * @return Enumerator
		 */
		public function rewind()
		{
			reset($this->items);
			return $this;
		}
		
		/**
		 * gets the current item in the array the pointer
		 * is referencing
		 * 
		 * @return mixed
		 */
		public function current()
		{
			return current($this->items);
		}
		
		/**
		 * gets the key of the current element
		 * 
		 * @return mixed
		 */
		public function key()
		{
			return key($this->items);
		}
		
		/**
		 * moves the pointer to the next element.
		 * 
		 * @return mixed 
		 */
		public function next()
		{
			return next($this->items);
		}
		
		/**
		 * checks to see if there is a current element 
		 * after calls to rewind or next
		 * 
		 * @return boolean
		 */
		public function valid()
		{
			return $this->current() !== false;
		}
		
		/**
		 * positions the pointer to a specific location
		 * in the Enumerable
		 * 
		 * @param int $index 
		 */
		public function seek($index)
		{
			$this->rewind();
	        $position = 0;
	        while($position < $index && $this->valid()) {
	            $this->next();
	            $position++;
	        }
	        if (!$this->valid()) {
	            throw new \OutOfBoundsException('Invalid seek position');
	        }
		}
		
		/**
		 * returns the length/count of items in the 
		 * enumerable object
		 * 
		 * @return integer 
		 */
		public function count()
		{
			return count($this->items);	
		}
		
	}
}