<?php

namespace 
{
	if(defined("MIDORI_UNSAFE") && MIDORI_UNSAFE == true)
	{

		function benchmark($start, $end = null)
		{
			if($end == null)
				$end = microtime(true);
			$diff = round($end - $start, 4);
			
			return " $diff seconds";
		}
		
		function str($value)
		{
			return new Midori\String($value);		
		}
		
		function regex($pattern)
		{
			return new Midori\Regex($pattern);		
		}
	}
	
	function unbox($value)
	{
		if($value instanceof Midori\IValueType)
			$value = $value->value;
		return $value;
	}
	
	function box_str($value)
	{
		return new Midori\String($value);		
	}
	
	function box_regex($value)
	{
		return new Midori\Regex($value);		
	}

}

namespace Midori
{
		
	function benchmark($start, $end = null)
	{
		if($end == null)
			$end = microtime(true);
		$diff = round($end - $start, 4);
		
		return " $diff seconds";
	}
	
	function unbox($value)
	{
		if($value instanceof IValueType)
			$value = $value->value;
		return $value;
	}
	
	function str($value)
	{
		return new String($value);		
	}
	
	function regex($pattern)
	{
		return new Regex($pattern);		
	}
	
}