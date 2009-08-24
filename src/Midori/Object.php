<?php



namespace Midori
{
require_once "Util.php";	
	
	/**
	 * Midori_Object is the core base class for all of the objects
	 * in the midori library and hold the basic methods for creating
	 * objects with type information in php.  
	 * 
	 * <p>Midori_Object is design to help create plain old php objects that 
	 * utilize {@link http://us.php.net/oop5.magic magic methods} for properties
	 * which means pretty much all properties within the library are public.</p>
	 * 
	 * <pre class="brush: php">
	 *
	 *   /**
	 *    * 
	 *    * 
	 *    * @property string $type
	 *    *\/ 
	 *   class Car extends Midori_Object
	 *   {
	 *   		
	 *      /**
	 *       * @ignore
	 *       *\/
	 *   	protected function getType()
	 *      {
	 *   		return $this->get("type");
	 *      }
	 *      
	 *      /**
	 *       * @ignore 
	 *       *\/
	 *      protected function setType($value)
	 *      { 
	 *      	$this->set("type", $value);
	 *      }
	 *	}
	 * </pre>
	 * 
	 * 
	 * @author Michael Herndon
	 * @package Midori
	 * 
	 */
	class Object
	{
		
		private $fields = array();
	
		/**
		 * gets the property value that is stored in the private $fields array.
		 * 
		 * <p>generic get method that pulls a property from the private
		 * fields array which is used to hold values for cloning purposes
		 * and help with api changes by having properties with gets/sets</p>
		 * 
		 * <pre class="brush: php">
		 * /**
		 *  * Person Model 
		 *  *
		 *  * @property string $name
		 *  *\/ 
		 * class Person extends Midori_Object
		 * {
		 *    protected function getName()
		 *    {
		 *       return $this->get("name");
		 *    }
		 *    
		 *    protected function setName($value)
		 *    {
		 *       $this->set("name", $value);
		 *    }
		 * }
		 * </pre>
		 * 
		 * @param string 		$property		the name of the property.
		 * @return mixed
		 */
		protected function get($property)
		{
			if(!isset($this->fields[$property]))
				return null;
			return $this->fields[$property];
		}
		
		/**
		 * sets the property value that is stored in the private $fields array.
		 * 
		 * <p>generic set method that sets a value in the private fields array
		 * which is used to hold properties for cloning purposes and help
		 * with api changes of having properties.</p>
		 * 
		 * <pre class="brush: php">
		 * /**
		 *  * Person Model 
		 *  *
		 *  * @property string $name
		 *  *\/ 
		 * class Person extends Midori_Object
		 * {
		 *    protected function getName()
		 *    {
		 *       return $this->get("name");
		 *    }
		 *    
		 *    protected function setName($value)
		 *    {
		 *       $this->set("name", $value);
		 *    }
		 * }
		 * </pre>
		 * 
		 * @param string 		$property		the name of the property.
		 * @param mixed 		$value			the value that is being set.
		 * @return void
		 */
		protected function set($property, $value)
		{
			$this->fields[$property] = $value;
		}
		
		/**
		 * gets the name the of the current class. 
		 * 
		 * <p> 
		 * returns the name of the class as Midori_String or string
		 * in case you need to do some dynamic creation or manipulating 
		 * the name.
		 * </p>
		 * 
		 * <pre class="brush: php">
		 *  $obj = new Midori_Object();
		 *  echo $obj->getClassName()->toLower(); // midori_object
		 * </pre>
		 * 
		 * @see get_class()
		 * @param 	boolean 		$returnPhpString 	determines if the result is a boxed 
		 * 												string or unboxed string.
		 * @return Midori_String|string
		 */
		public function getClassName($returnPhpString = false)
		{
			$name = get_class($this);
			return $returnPhpString ? $name : new String($name);
		}
		
		/**
		 * gets the reflected type information about the class.
	     *
	     * <p> intitializes the ReflectionObject and passes the
	     * current object into the constructor and returns the
	     * ReflectionObject </p>
	     *
	     * @see ReflectionObject
		 * @return ReflectionObject
		 */
		public function getReflectedType()
		{
			return new ReflectionObject($this);
		}
		
		public function inspect()
		{
			return $this->getClassName();
		}
		
		/**
		 * determines if the current object is equal to the
		 * object being passed into this method.
		 * 
		 * <p> 
		 * This can be and should be overriden in custom objects
		 * in order to determine if the objects are equal. Unfortunately
		 * php does not let you have operator overload at the moment. 
		 * </p>
		 * 
		 * <pre class="brush: php">
		 *   $obj = new Midori_Object();
		 *   $str = new Midori_String("test")
		 *   echo $obj->equals($str)  ? "true" : "false"; // false
		 * </pre>
		 * 
		 * @param 		mixed 			$obj the object the current object 
		 * 								is being compared to. 
		 * @return 		boolean
		 */
		public function equals($obj)
		{
			return ($obj == ($this));
		}
		
		/**
		 * returns the object name into a {@see Midori_String} or
		 * {@see string}  
		 * 
		 * <p> By default toString returns the name of the class.
		 * However this function is meant to be used by __toString
		 * as well as an easier method to remember of getting the
		 * string conversion of the object. </p>
		 * 
		 * <pre class="brush: php">
		 *  $obj = new Midori_Object();
		 *  echo $obj->toString()->toLower(); // midori_string
		 *  echo $obj->toString(false); // Midori_String
		 * </pre>
		 * 
		 * @param 	boolean 		$returnPhpString 	determines if the result is a boxed 
		 * 												string or unboxed string.
		 * @return 	Midori_String|string
		 */
		public function toString($returnPhpString = false)
		{
			return $this->getClassName($returnPhpString); 
		}
		
		
		/**
		 * Gets a property.
		 * 
		 * <p> 
		 * 	This method basically looks for a getter method for a property that
		 *  is called on the object.  This implimentation will concatinate "get"+$property
		 *  in order to get the name of the property.  The property should be in 
		 *  camel casing to fit traditional php guidelines.  
		 * </p>
		 * <p> For more information, take a look at
		 * {@link http://us3.php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.members magic methods}
		 * about __get and __set on classes. 
		 * </p>
		 * 
		 * <pre class="brush: php">
		 * /**
		 *  * Person Model 
		 *  *
		 *  * @property string $name
		 *  *\/ 
		 * class Person extends Midori_Object
		 * {
		 *    protected function getName()
		 *    {
		 *       return $this->get("name");
		 *    }
		 *    
		 *    protected function setName($value)
		 *    {
		 *       $this->set("name", $value);
		 *    }
		 * }
		 * 
		 * // calls __set($property, $value) magically which calls getName
		 * $person = new Person();
		 * $person->name = "Megan Fox"; 
		 * 
		 * // calls __get($property)
		 * $name = $person->name;
		 * </pre>
		 * 
		 * @param string $property
		 * @return mixed
		 */
		public function __get($property)
		{
			$get = "get".$property;
			return $this->$get();
		}
		
		/**
		 * Sets a property.
		 * 
		 * <p> 
		 * 	This method basically looks for a setter method for a property that
		 *  is called on the object.  This implimentation will concatinate "set"+$property
		 *  in order to get the name of the property.  The property should be in 
		 *  camel casing to fit traditional php guidelines.  
		 * </p>
		 * <p> For more information, take a look at
		 * {@link http://us3.php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.members magic methods}
		 * about __get and __set on classes. 
		 * </p>
		 * 
		 * <pre class="brush: php">
		 * /**
		 *  * Person Model 
		 *  *
		 *  * @property string $name
		 *  *\/ 
		 * class Person extends Midori_Object
		 * {
		 *    protected function getName()
		 *    {
		 *       return $this->get("name");
		 *    }
		 *    
		 *    protected function setName($value)
		 *    {
		 *       $this->set("name", $value);
		 *    }
		 * }
		 * 
		 * // calls __set($property, $value) magically which calls getName
		 * $person = new Person();
		 * $person->name = "Megan Fox"; 
		 * 
		 * // calls __get($property)
		 * $name = $person->name;
		 * </pre>
		 * 
		 * @param string $property
		 * @param mixed $value
		 */
		public function __set($property, $value)
		{
			$set = "set".$property;
			$this->$set($value);
		}
		
		
		/**
		 * Clones the current object, shallowly. 
		 * 
		 * <p> clones the object's properties that are stored 
		 * in the $fields array stored via {@link set()} method 
		 * and returns a new object with the copied fields.  </p>
		 * 
		 * <p> You can get more information about
		 *   {@link http://us3.php.net/manual/en/language.oop5.cloning.php cloning objects} 
		 *   on the php site. 
		 * </p>
		 * 
		 * <pre class="brush: php">
		 * /**
		 *  * Person Model 
		 *  *
		 *  * @property string $name
		 *  *\/ 
		 * class Person extends Midori_Object
		 * {
		 *    protected function getName()
		 *    {
		 *       return $this->get("name");
		 *    }
		 *    
		 *    protected function setName($value)
		 *    {
		 *       $this->set("name", $value);
		 *    }
		 * }
		 * 
		 * $person = new Person();
		 * $person->name = "Megan Fox";
		 * $person2 = clone $person;
		 * 
		 * echo $person2->name; // Megan Fox
		 * 
		 * </pre>
		 * 
		 * 
		 * @return self
		 */
		public function __clone()
		{
			$class = $this->getClassName(true);
			$obj = new $class();
			$obj->fields = $this->fields;
			return $obj;
		}
		
		/**
		 * returns the name of the class.
		 * 
		 * <p>__toString is actually a powerful magic method that 
		 * outputs the object into a string, which allows for
		 * methods to use echo or string concatination. </p>
		 * 
		 * <pre class="brush: php">
		 * class Dog extends Midori_Object
		 * {
		 *   
		 * }
		 * 
		 * $dog = new Dog();
		 * echo $dog; // Dog
		 * 
		 * echo $dog." barks"; // Dog barks
		 * </pre>
		 * 
		 * @return string 
		 */
		public function __toString()
		{
			return $this->getClassName(true);
		}
			
	}
}
