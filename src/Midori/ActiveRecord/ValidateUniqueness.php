<?php

namespace Midori\ActiveRecord
{
	class ValidateUniqueness extends Validation
	{
		
		public function __construct($propertyName, $class, $message)
		{
			$this->propertyName = $propertyName;
			$this->class = $class;
			$this->message = $message;
		}
		
		public function validate($value, $object)
		{
			// if( $this->primaryKey == 0 || is_null($this->primaryKey))
			// 	return true;
			// if($value == '' || is_null($value))
			// 	return true;
		   
		    $adapter = Midori\config()->dataAdapter;
		    $var = $adapter->selectOne("SELECT COUNT(*) FROM {$adapter->quoteIdentifier($object->tableName)} WHERE {$this->propertyName} = ? ", array($value));
		    
			return $var == 0;
		}
		
	}
}