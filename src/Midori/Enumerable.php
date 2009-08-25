<?php

namespace Midori
{
	/**
	 * 
	 * @author Michael
	 * @package Midori
	 */
	class Enumerable extends Object
		implements \IteratorAggregate, \Countable, \ArrayAccess 
	{
		protected $items = array();
		
		public function __construct()
		{
			$args = func_get_args();
			
			if(count($args) == 1 && (is_array($args[0]) || $args[0] instanceof \IteratorAggregate))
				$args = $args[0];
			
			foreach($args as $item)
				$this->items[] = $item;
		}
		
		public function each($action)
		{
			$count = $this->length;
			for($i = 0; $i < $count; $i++)
				$action($this->items[$i]);
		}
		
		
		
		public function find($predicate)
		{
			$count = $this->length;
			for($i = 0; $i < $count; $i++)
				if($predicate($this->items[$i]))
					return $this->items[$i];
			return null;	
		}
		
		public function findAll($predicate)
		{
			return $this->map($predicate);
		}
		
		public function first()
		{
			if(isset($this->items[0]))
				return $this->items[0];
			return null;
		}
		
		public function inspect($tabs = 0)
		{
			$tabify = "";
			
			box_int($tabs)->times(function () 
				use(&$tabify){
				$tabify += "\t";
			});
			
			foreach($this->items as $key => $value)
			{
				if($value instanceof Object)
					$value = $value->inspect($tabs);
				$key = box_str($key)->padRight(7, " ");
				$output .= "{$tabify}[{$key}]{$value}\n";	
			}
		}
		
		public function indexOf($value)
		{
			$count = $this->length;
			for($i = 0; $i < $count; $i++)
				if($this->items[$i] == $value)
					return $i;
			return -1;
		}
		
		public function join($delimiter = ",")
		{
			$string = "";
			$count = $this->count();
			for($i = 0; $i < $count; $i++)
				$string .= $this->items[$i].$delimiter;
			return box_str($string)->trimEnd($delimiter);
		}
		
		public function last()
		{
			$index = $this->length -1;
			if($index > -1)
				return $this->items[$index];
			return null; 	
		}
		
		public function lastIndexOf($value)
		{
			$count = $this->length;
			$index = -1;
			for($i = 0; $i < $count; $i++)
				if($this->items[$i] == $value)
					$index = $i;
			return $index;
		}
		
		public function map($predicate)
		{
			$store = array();
			$count = $this->length;
			for($i = 0; $i < $count; $i++)
				if($predicate($this->items[$i]))
					$store[] = $this->items[$i];
			return $store;
		}
		
	/**
		 * Determines if an item exists at the given offset
		 * 
		 * @param $offset int|string
		 * @return boolean
		 */
		public function offsetExists($index)
		{
			return isset($this->items[$index]);
		}
		
		/**
		 * Gets the item at the given offset/position.
		 * 
		 * @param $offset int|string
		 * @return mixed the item at the offset.
		 */
		public function offsetGet($index)
		{
			if(isset($this->items[$index]))
				return $this->items[$index];
			return null;
		}
		
		/**
		 * Sets the item at the given
		 * offset/position.
		 * 
		 * @param $offset int|string
		 * @param $value mixed
		 * @return void
		 */
		public function offsetSet($index, $value)
		{
			$this->items[$index] = $value;
		}
		
		/**
		 * Deletes an item from the enumerable object
		 * at the given offset/position.
		 * 
		 * @param $offset
		 * @return void
		 */
		public function offsetUnset($index)
		{
			unset($this->items[$index]);
			$this->items = array_merge($this->items);
		}
		
		/**
		 * 
		 * 
		 * returns Midori_Enumerator
		 */
		public function getIterator() 
		{
			return new Enumerator($this->items);
		}
		
		/**
		 * get the the length/count
		 * 
		 * @return int
		 */
		protected function getLength()
		{
			return $this->count();
		}
		
		/**
		 * 
		 * @return int the number of items in the object.
		 */
		public function count()
		{
			return count($this->items);
		}
		
		public function reverse()
		{
			$this->items = array_reverse($this->items);
			return $this;
		}
		
		public function skip($skip)
		{
			$items = array();
			$count = $this->count();
			for($i = 0; $i < $count; $i++)
			{
				if($i < $skip)
				 continue;
				$items[] =  $this->items[$i];
			}
			$class = $this->getClassName(true);
			return new $class($items);
		}
		
		public function take($take)
		{
			$items = array();
			for($i = 0; $i < $take; $i++)
			{
				$items[] =  $this->items[$i];
			}
			$class = $this->getClassName(true);
			return new $class($items);
		}
		
		public function sort($method = null)
		{
			if($method)
				usort($this->items, $method);
			else 
				sort($this->items);
			return $this;
		}
		
		
		/**
		 * returns the items in the enumerable object 
		 * as a php array.
		 * 
		 * @return array
		 */
		public function toArray()
		{
			return $this->items;
		}
		
	} 

}