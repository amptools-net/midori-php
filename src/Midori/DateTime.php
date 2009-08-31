<?php


namespace Midori
{
		
	use Midori\Globalization\CultureInfo;
	
	/**
	 * 
	 * @author Michael
	 * @package Midori
	 * @property DateTime $value
	 * @property boolean $hasValue
	 */
	class DateTime extends Nullable 
	{
		
		
		const MYSQL = "Y-m-d H:i:s";
		
		/**
		 * 
		 * @param $value
		 */
		public function __construct($value = "now")
		{
			$value = unbox($value);
						
			if($value == null)
			{
				$this->value = null;
				return;	
			}
			
			if($value instanceof \DateTime)
			{
				$this->value = $value;
				return;	
			}
			
			if(is_int($value))
			{
				$this->value = new \DateTime(date(CultureInfo::currentCulture()->dateFormat, (integer)$value));
				return;
			}
						
			if(is_string($value))
			{
				if(box_str($value)->trim()->isNullOrEmpty == true)
				{
					$this->value = null;
					return;		
				}
					
				if(is_numeric($value))
				{
					$this->value = new \DateTime(date(CultureInfo::currentCulture()->dateFormat, (integer)$value));
					return;
				} 
				else 
				{
					$this->value = new \DateTime($value);
					return;
				}
			}
			$this->value = null;
			return;
		}
		
		public static function tryParse($value = "now")
		{
			try {
				return new DateTime($value);
			} catch(Exception $ex) {
				return new DateTime(null);	
			}
		}
		
		
		/**
		 * Sets the date portion of the DateTime.
		 * 
		 * @param int $year
		 * @param int $month
		 * @param int $day
		 * @return Midori_DateTime
		 */
		public function setDate($year, $month, $day)
		{
			$this->value->setDate($year, $month, $day);
			return $this;
		}
		
		/**
		 * Sets the time portion of the DateTime.
		 * 
		 * @param int $hour
		 * @param int $minute
		 * @param int $second
		 * @return Midori_DateTime
		 */
		public function setTime($hour, $minute, $second = 0)
		{
			$this->value->setTime($hour, $minute, $second);
			return $this;
		}
		
		/**
		 * Add days to the current DateTime
		 * 
		 * <code>
		 *		$this->addDays(2); // adds 2 days
		 *		$this->addDays(-4) // subtracts 4 days
		 * </code>
		 * 
		 * @param int $days
		 * @return Midori_DateTime
		 */
		public function addDays($days)
		{
			$days = ($days >= 0) ? "+$days days" : "$days days";
			$this->value->modify($days);
			return $this;
		}
		
		/**
		 * Add months to the current DateTime
		 * 
		 * <code>
		 *		$this->addMonths(2); // adds 2 months
		 *		$this->addMonths(-4) // subtracts 4 months
		 * </code>
		 * 
		 * @param int $months
		 * @return Midori_DateTime
		 */
		public function addMonths($months)
		{
			$months = ($months >= 0) ? "+$months months" : "$months months";
			$this->value->modify($months);
			return $this;
		}
		
		/**
		 * Adds years to the current DateTime
		 * 
		 * <code>
		 *		$this->addYears(2); // adds 2 years
		 *		$this->addYears(-4) // subtracts 4 years
		 * </code>
		 * 
		 * @param int $years
		 * @return Midori_DateTime
		 */
		public function addYears($years)
		{
			$years = ($years >= 0) ? "+$years years" : "$years years";
			$this->value->modify($years);
			return $this;
		}
	
		
		/**
		 * Formats the datetime into a string.
		 * 
		 * @param string $format
		 * @return string
		 */
		public function format($format = false)
		{
			if($this->hasValue)
				return $this->value->format($format ||  CultureInfo::currentCulture()->dateFormat);
			return "";
		}
		
		/**
		 * Returns the date portion of the object
		 * 
		 * @return string
		 */
		public function getDate()
		{
			return $this->format('n/j/Y');
		}
		
		/**
	     * Returns the hours portion of the object
	     * 
	     * @return integer
	     */
		public function getHour()
		{
			return (integer)$this->format('g');
		}
		
		/**
	     * Returns the minutes portion of the object
	     * 
	     * @return integer
	     */
		public function getMinute()
		{
			return (integer)$this->format('i');
		}
		
		/**
	     * Returns the seconds portion of the object
	     * 
	     * @return integer
	     */
		public function getSecond()
		{
			return (integer)$this->format('s');
		}
		
		/**
	     * Returns the am/pm portion of the object
	     * 
	     * @return string
	     */
		public function getAmPm()
		{
			return $this->format('A');
		}
		
		/**
		 * determines if the date is an empty date i.e. 0000-00-00 00:00:00,
		 * because not everyone uses null.
		 * 
		 * @return boolean
		 */
		public function isEmpty()
		{
			if($this->hasValue)
			{
				$empty = new DateTime("0000-00-00 00:00:00");
				return $this->value == $empty;
			}
			return true;
		}
		
		/**
		 * (non-PHPdoc)
		 * @see Midori/Midori_Object#toString()
		 */
		public function toString($format = false, $useMidoriString = true)
		{
			if($this->hasValue)
			{
				$string = $this->value->format($format || CultureInfo::currentCulture()->dateFormat);
				if(!$useMidoriString)
					return $string;
				return new String($string);
			}
			if(!$useMidoriString)
	            return "";
	        return new String("");
		}
		
		/**
		 * (non-PHPdoc)
		 * @see Midori/Midori_Object#__toString()
		 */
		public function __toString()
		{
			$value = "";
			if($this->hasValue)
				$value =  $this->value->format(CultureInfo::currentCulture()->dateFormat);	
			return "$value";
		}
	}
}