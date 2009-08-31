<?php

namespace Midori
{
	class  NotImplementedException extends Exception
	{
		
		
		public function __construct($codeName, $code = 0, $previous = null)
		{
			parent::__construct("${$codeName} has not been implemented.", $code, $previous);
		}
		
	}	
	
	
}

