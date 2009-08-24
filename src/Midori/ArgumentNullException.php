<?php

namespace Midori
{
	/**
	 * 
	 * @author Michael
	 * @package Midori
	 */
	class ArgumentNullException extends Exception
	{
		
		
		public function __construct($argument, $code = 0, $previous = null)
		{
			parent::__construct("${$argument} can not be null.", $code, $previous);
		}
	}
}