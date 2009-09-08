<?php
	
namespace Midori\ActiveRecord
{
	/**
	 * 
	 * @author Michael
	 * @package Midori
	 * @property 
	 */
	class Base extends \Midori\Object
	{
		private static $validations;
		protected $changes = array();
		public $errors = array();
		private $properties = array();
		protected $isNew = true;
		
		public $tableName = null;
		public $tableAlias = null;
		public $primaryKey = null;
		public $associations = array();
		
		
		private static $new_only_validations;
		public $errors_new = array();
		protected static $isFetching = false;
		
		
		
		public function __construct()
		{
			if(self::$validations == null)
			{
				 self::$validations = array();
			}
			
			if($this->id == null)
			{
				$this->onCreate();
			}
		}
		
		protected function onStaticInit()
		{
			
		}
		
		protected function onCreate()
		{
			
		}
		
		protected function getId()
		{
			$pk = $this->primaryKey;
			return $this->$pk;
		}
		
		protected function setId($value)
		{
			$pk = $this->primaryKey;
			$this->$pk = $value;
		}
		
		public function getValue($propertyName)
		{
			$value = $this->$propertyName;
			if($value == null)
				return null;
			if($value instanceof \Midori\Nullable)
				return $value->value;
			return $value;
		}
		
		
		public function send($properties)
		{
			foreach($properties as $property => $value)
			{
				if(is_array($value) || $value instanceof \IteratorAggregate)
					$this->$property->send($value);
				else {
					$this->$property = $value;
				}
			}
			return $this;
		}
		
		public function validatePresenceOf($names, $message = null, $if = null)
		{
			if(is_string($names))
				$names = array($names);
				
			$prop = $this->getValidations();
			foreach($names as $value)
			{
				if(!isset($prop[$value]))
					$prop[$value] = new ValidationList();
				$prop[$value]->add(new ValidateRequired($value, $message, $if));
			}
			$this->setValidations($prop);
		}
		
		public function validateLengthOf($names, $min = null, $max = null, $message = null, $if = null)
		{
			if(is_string($names))
				$names = array($names);
				
			$prop = $this->getValidations();
			foreach($names as $value)
			{
				if(!isset($prop[$value]))
					$prop[$value] = new ValidationList();
			
				$prop[$value]->add(new ValidateLength($value, $message, $min, $max, $if));
			}
			$this->setValidations($prop);
		}
		
	
		public function validateFormatOf($names, $format, $message = "%s is not the correct format", $if = null)
		{
			if(is_string($names))
				$names = array($names);
			$prop = $this->getValidations();
			foreach($names as $value)
			{
				if(!isset($prop[$value]))
					$prop[$value] = new ValidationList();
				$prop[$value]->add(new ValidateFormat($value, $format, $message, $if));
			}
			$this->setValidations($prop);
		}
		
	
		
		public function validateUniqueness($names, $message = "%s must be unique")
		{
			if(is_string($names))
				$names = array($names);
			$prop = $this->getNewOnlyValidations();
			foreach($names as $value)
			{
				if(!isset($prop[$value]))
					$prop[$value] = new ValidationList();
				$prop[$value]->add(new ValidateUniqueness($value, $this->getClassName(), $message));
			}
			$this->setNewOnlyValidations($prop);
		}
	
		public  function getValidations()
		{
			$name = $this->getClassName(false);
			if(!isset(self::$validations[$name])) {
				self::$validations[$name] = array();
				$this->onStaticInit();
			}
			return self::$validations[$name];
		}
		
		protected function setValidations($value)
		{
			self::$validations[$this->getClassName(false)] = $value;
		}
	
		/**
		 * 
		 * @return Midori_ActiveRecord_Service
		 */
		public function getService()
		{
			return Service::fetch($this->getClassName());
		}
		
		public function delete()
		{
			if($this->isNew)
				throw new Exception("you can not delete a new object that has not been saved.");
			$id = $this->getValue($this->primaryKey);
			return $this->getService()->delete($id);
		}
		
		public function insert()
		{
			$pk = $this->primaryKey;
			$id = $this->getService()->insert($this->changes);
			$this->$pk = $id;
			return $this->$pk;
		}
		
		public function update()
		{
			$prop = $this->primaryKey;
			$id = $this->$prop;
			$update = $this->getService()->update($id, $this->changes);
			return $update;
		}
		
		protected function isChanged()
		{
			return count($this->changes) > 0;
		}
		
		public function save($throwException = false)
		{
			if($this->isChanged())
			{
			
				$valid = (count($this->errors) == 0) && (count($this->errors_new) == 0);
				if($valid == false)
				{
					if($throwException)
					{
						$pk = $this->primaryKey;
						$id = $this->$pk;
						$class = $this->getClassName();
						throw new \Midori\Exception(join($this->errors, ",").join($this->errors_new, ","));
					}
					return false;
				}
				
				#$this->getService()->beginTransaction();
				$value = false;
				if(count($this->changes) > 0)
				{
					if($this->isNew)
					{
						self::markFetching(false); // check if this creates any problems, used to differentiate between old and new objects
						$value = $this->insert();	
					}
					else 
						$value = $this->update();
				}
					
				#$this->getService()->commitTransaction();
				$this->saveChildren();
				$this->markFetched();
				return $value;
			}
			return false;
		}
		
		public function saveChildren()
		{
			
		}
		
		public function markFetched()
		{
			$this->isNew = false;
			$this->changes = array();	
		}
		
		
		public function __get($property)
		{
			$get = "get".str_replace("_", "", $property); // getId
			return $this->$get(); // $this->getId(); $this->setId(
		
		}
		
	
		public function __set($property, $value)
		{
			$set = "set".str_replace("_", "", $property);
			$this->$set($value);
		}
		
		protected function set($property, $newValue)
		{
			$oldValue = parent::get($property);
	
			if($oldValue instanceof \Midori\Nullable)
				$oldValue = $oldValue->value;
			if($newValue instanceof \Midori\Nullable)
				$value = $newValue->value;
			else 
				$value = $newValue;
			
			if($value != $oldValue || $oldValue == null)
			{
				parent::set($property, $newValue);
				$this->validate($property, $newValue);
				if(!self::$isFetching)
					$this->validateNew($property, $newValue);
				$this->changes[$property] = $newValue;	
			}
		}
		
	
	
		public function validate($propertyName, $value)
		{
			$prop = $this->getValidations();
			if(isset($prop[$propertyName]))
			{
				$rules = $prop[$propertyName];
				unset($this->errors[$propertyName]);
				foreach($rules as $rule)
				{
					if(!$rule->validate($value, $this))
					{
						if(!isset($this->errors[$propertyName]))
							$this->errors[$propertyName] = "";
						$this->errors[$propertyName] .= $rule->getErrorMessage();	
					}
				}
			}
			
			/*
			$rules = self::$validations[$this->getClassName()];
			$this->errors = array();
			foreach($rules as $rule)
			{
				$property = $rule->propertyName;
				if($rule->validate($this->$property, $this) == false)
				{
					if(!isset($this->errors[$property]))
						$this->errors[$property] = "";
					$title = new Midori_String($property);
					$this->errors[$property] .=  sprintf($rule->message, $title->titleize());
				}		
			}*/	
			return true;
		}
		
		/**
		 * Validation for new objects only
		 *
		 */
		
		public function getNewOnlyValidations()
		{
			if(!isset(self::$new_only_validations[$this->getClassName()])) {
				self::$new_only_validations[$this->getClassName()] = array();
				//$this->onStaticInit();
			}
			return self::$new_only_validations[$this->getClassName()];
		}
		
		protected function setNewOnlyValidations($value)
		{
			self::$new_only_validations[$this->getClassName()] = $value;
		}
		
		public function validateNew($propertyName, $value)
		{
			$prop = $this->getNewOnlyValidations();
			if(isset($prop[$propertyName]))
			{
				$rules = $prop[$propertyName];
				unset($this->errors_new[$propertyName]);
				foreach($rules as $rule)
				{
					if(!$rule->validate($value, $this))
					{
						if(!isset($this->errors_new[$propertyName]))
							$this->errors_new[$propertyName] = "";
						$this->errors_new[$propertyName] .= $rule->getErrorMessage();	
					}
				}
			}
			return true;
		}
		
		public static function markFetching($value = true)
		{
			self::$isFetching = $value;
		}
		
		/**
		 *  End Validation for new objects only
		 *
		 */
		
		
		public function toArray()
		{
			$array = array();
			foreach($this->attributes as $property => $value)
			{
				if($value instanceof \Midori\IValueType)
					$array[$property] = $value->value;
				else 
					$array[$property] = $value;
			}
			return $array;
		}
	}
}
