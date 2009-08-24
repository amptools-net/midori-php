<?php

namespace 
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
		return $value;
	}
	
	

}