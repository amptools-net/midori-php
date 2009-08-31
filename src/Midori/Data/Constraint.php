<?php

namespace Midori\Data
{	
	/**
	 * 
	 * @author Michael
	 * @package Midori
	 * @property string $prefix
	 * @property string $tableAlias
	 * @property string $tableName
	 * @property string $columnName
	 * @property string $constraintName
	 * @property string $type
	 * @property string $name
	 */
	abstract class Constraint extendsObject
	{
		
		public function toSql()
		{
			return 
				"CONSTRAINT {$this->name} {$this->constraintName} {$this->generate()}";
		}
		
		public function send($options)
		{
			foreach($options as $property => $value)
				$this->$property = $value;
		}
		
		public function generate()
		{
			return "";
		}
	}
}