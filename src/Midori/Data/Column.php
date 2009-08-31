<?php

namespace Midori\Data
{	
	/**
	 * 
	 * @author Michael
	 * @package Midori
	 * @property string $name
	 * @property string $class (midori class type)
	 * @property string $type (sql type)
	 * @property int $length
	 * @property bool $null
	 * @property mixed $default
	 * @property integer $precision
	 * @property integer $scale
	 * @property integer $position
	 * @property Midori_Data_Adapter $adapter
	 */
	class Column extends Object
	{
		
		
		public function __construct($adapter, $name, $type, $length = null)
		{
			$this->adapter = $adapter;
			$this->name = $name;
			$this->type = $type;
			$this->length = $length;
			$this->null = true;
			$this->default = null;
		}
		
		public function typeToSql()
		{
			return $this->adapter->typeToSql($this->type,
				$this->length, $this->precision, $this->scale);
		}
		
		public function send($options)
		{
			foreach($options as $property => $value)
				if($property == "limit")
				{
					$this->length = $value;
					$this->limit = $value;
				} else 
					$this->$property = $value;
		}
		
		public function toSql()
		{
			$sql = "{$this->adapter->quoteIdentifier($this->name)} {$this->typeToSql()}";
			$schema = $this->adapter->schema();
			$options = array();
			$options["default"] = $this->default;
			$options["null"] = $this->null;
			$sql = $schema->addColumnOptions($sql, $options);
			return $sql;
		}
	}
}