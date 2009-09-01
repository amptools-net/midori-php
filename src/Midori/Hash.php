<?php

namespace Midori
{
	class Hash extends Object implements \IteratorAggregate, \Countable, \ArrayAccess 
	{
		private $items = array();
		
		
		public function __construct($args = array())
		{				
			$this->items = $args;	
		}
		
		public function count()
		{
			return count($this->items);		
		}
		
		
		protected function getKeys()
		{
			return array_keys($this->items);	
		}
		
		protected function getValues()
		{
			return array_values($this->items);		
		}
		
		/**
		 * 
		 * 
		 * returns Enumerator
		 */
		public function getIterator() 
		{
			return new Enumerator($this->items);
		}
		
		public function each($block)
		{
			foreach($this->items as $key => $value)
				$block($key, $value);		
			return $this;
		}
		
		public function map($block)
		{
			$hash = new Hash();
			foreach($this->items as $key => $value)
				if($block($key, $value))
					$hash[$key] = $value;
			return $hash;
		}
		
		public function hasKey($key)
		{
			return array_key_exists($key, $this->items);
		}
		
		public function hasValue($value)
		{
			return in_array($value, $this->items);
		}
		
		public function merge($hash)
		{
			if($hash instanceof Hash)
				$hash = $hash->items;
			
			if(!is_array($hash))
				throw new Exception("argument \$hash must be a PHP array or instance of hash");
			
			$this->items = array_merge($this->items, $hash);
		
				
			return $this;
		}
		
		public function hasherize($key)
		{
			$value = $this->offsetGet($key);	
			if(is_array($value))
			{
				$hash = new Hash();
				return $hash->merge($value);
			}
			return $value;
		}
		
		/**
		 * Determines if an item exists at the given offset
		 * 
		 * @ignore
		 * @param $key string
		 * @return boolean
		 */
		public function offsetExists($key)
		{
			return isset($this->items[$key]) == true;
		}
		
		/**
		 * Gets the item at the given offset/position.
		 * 
		 * @ignore
		 * @param $key string
		 * @return mixed the item at the offset.
		 */
		public function offsetGet($key)
		{
			return $this->offsetExists($key) ? $this->items[$key] : null;	
		}
		
		/**
		 * Sets the item at the given
		 * offset/position.
		 * 
		 * @ignore
		 * @param $key string
		 * @param $value mixed
		 * @return void
		 */
		public function offsetSet($key, $value)
		{
			$this->items[$key] = $value;
		}
		
		/**
		 * Deletes an item from the enumerable object
		 * at the given offset/position.
		 * 
		 * @ignore
		 * @param $key
		 * @return void
		 */
		public function offsetUnset($key)
		{
			unset($this->items[$key]);
		}
		
		public function toArray()
		{
			return $this->items;
		}
		
	}	
}