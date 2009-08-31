<?php
	
namespace Midori\Data
{
			
	
	/**
	 * 
	 * @author Michael
	 * @Package Midori
	 * @property boolean $allowsMultipleQueries
	 */
	abstract class Adapter extends \Midori\Object
	{
		
		/**
		 * 
		 * @var Midori_Log_Logger
		 */
		protected $log;
		
		/** 
		 * @var PDO
		 */
		protected $driver;
		
		private static $initialized = false;
		
		
		public static function fetch()
		{
			$args = func_get_args();
			if(is_array($args[0]))
				$array = $args[0];
			else 
				$array = $args;
	
			$type = $array[0];
			$config = $array[1];
			$class = "Midori\\Data\\Adapters\\".$type."Adapter";
			return new $class($config);
		}
		
		
		protected function getDatabaseName()
		{
			return $this->get("database_name");
		}
		
		protected function setDatabaseName($value)
		{
			return $this->set("database_name", $value);
		}
	
		protected function initLogger()
		{
			$this->log = Midori_Log_Logger::getInstance();
		}
		
		public function getAllowsMultipleQueries()
		{
			return true;
		}
		
		public function useDatabase($databaseName)
		{
			$this->database_name = $databaseName;
			$schema = $this->schema();
			$schema->useDatabase($databaseName);
		}
	
		abstract public function beginTransaction();
		
		abstract public function commit();
		
		abstract public function rollback();
		
		abstract public function schema();
		
		abstract public function selectObjects($class, $sql, $params = array());
		
		abstract public function selectAll($sql, $params = array());
		
		abstract public function selectOne($sql, $params = array());
		
		abstract public function execute($sql, $params = array());
		
		abstract public function delete($table, $where);
		
		abstract public function insert($table, $values);
		
		abstract public function update($table, $values, $where);
		
		abstract protected function getLastInsertId();
		
		
		public function simplifiedType($column)
		{
			
			switch(strtolower($column->type))
			{
				case "int": return "integer";
				case "smallint":
				case "mediumint": return "integer";
				
				case "float":
				case "double": return "float";
				
				case "decimal":
				case "numeric":
				case "number": 
					return "decimal";
				case "datetime":
					return "datetime";
				case "date":
					return "date";
				case "time":
					return "time";
				case "varchar":
				case "char":
				case "string":
					return "string";
				case "clob":
				case "text":
					return "text";
				case "blob":
				case "binary": 
					return "binary";
				case "tinyint":
				case "boolean":
					return "boolean";
				case "timestamp":
					return "timestamp";
					
			}
		}/*
	          case field_type
	            when /int/i
	              :integer
	            when /float|double/i
	              :float
	            when /decimal|numeric|number/i
	              extract_scale(field_type) == 0 ? :integer : :decimal
	            when /datetime/i
	              :datetime
	            when /timestamp/i
	              :timestamp
	            when /time/i
	              :time
	            when /date/i
	              :date
	            when /clob/i, /text/i
	              :text
	            when /blob/i, /binary/i
	              :binary
	            when /char/i, /string/i
	              :string
	            when /boolean/i
	              :boolean
	          end
	        end
	    end*/
		
		public function klass($type)
		{
			switch($type)
			{
				case "integer": 	return "Midori_Int32";
				case "float":		return "Midori_Float";
				case "decimal":		return "Midori_Decimal";
				case "timestamp":
				case "datetime":
				case "date":
				case "time":		return "Midori_DateTime";
				case "text":		return "Midori_Text";
				case "string":		return "Midori_String";
				case "binary":		return "Midori_Binary";
				case "tinyint":
				case "boolean":		return "Midori_Boolean";
			}
			return null;
		}
		
		/**
		 * 
		 * @return array
		 */
		protected abstract function nativeDatabaseTypes();
		
		public function findAll($options = array())
		{
			
			$this->validateOptions($options);
	 		$sql = $this->constructFinderSql($options);
	
		 	$params = (isset($options["conditions"])) ? $options["conditions"] : array();
	 
	 		if(isset($options["includes"]))
	 		{
	 			$class = $options['class'];	
	 			$includes = $options["includes"];
	 			
	 			throw new InvalidArgumentException("includes is not yet ready, but comming.");
	 		} 
	 		else 
	 		{
				$class = $options['class'];
	 			$data = $this->selectObjects($class, $sql, $params); 
				return $data;
	 		}
		}
		
		public function findCount($options = array())
		{
			unset($options["select"]);
				$options["select"] = "Count(*) as count";
				
			if(isset($options['order']))
				unset($options['order']);
				
			$this->validateOptions($options);
	 		$sql = $this->constructFinderSql($options);
	 		
		 	$params = (isset($options["conditions"])) ? $options["conditions"] : array();
		 	return $this->selectOne($sql, $params);
		}
		
		public function findOne($options = array())
		{
			$options["limit"] = 1;
			$items = $this->findAll($options);
			if(count($items) > 0)
				return $items[0];
			return null;
		}
	
		
		public function findBySql($sql, $params = null)
		{
			return $this->selectAll($sql, $params);
		}
		
		protected function constructFinderSql(&$options)
		{
			$distinct = isset($options["distinct"]) ? "DISTINCT" : "";
			$from = $options["from"];
			$columns = $options["select"];
			$alias = isset($options["alias"]) ? " AS {$options["alias"]} " : "";
			
			$sql = "SELECT {$distinct} $columns FROM {$this->quoteIdentifier($from)} $alias ";
			
			$this->addJoins($sql, $options);
			$this->addConditions($sql, $options);
			$this->addGroup($sql, $options);
			$this->addOrder($sql, $options);
			$this->addLimit($sql, $options);
			
			return $sql;
		}
		
		protected function validateOptions(&$options)
		{
			if(!isset($options["select"]))
				$options["select"] = "*";
			if(!isset($options["class"]))
				throw new InvalidArgumentException('$options[] must include the class type; $options["class"] = "ClassName"');
		}
		
		public function find($scope,  $options = array())
		{
			switch($scope)
			{
				case "all" : return $this->findAll($options);
				case "one" : return $this->findOne($options);
				default:
					return $this->findByIds($scope); 
			}
		}
		
		protected function addJoins(&$sql, $options) 
		{
			if(isset($options['joins']))
			{
				$joins = $options['joins'];
				if(is_string($joins))
					$sql .= " {$joins} ";
				else 
				{
					foreach($joins as $join)
						$sql .= " $join ";
				}
			}
		}
		
		protected function addConditions(&$sql, &$options) 
		{
			if(isset($options["conditions"]))
			{
				$conditions = $options["conditions"];
				$where = array_shift($conditions);
				$params = array();
				foreach($conditions as $k => $v)
				{
					if($v instanceof Midori_Nullable)
					{
						$value = ($v instanceof Midori_Datetime) ? $this->quote($v) : $v->value;
					} else {
						$value = $v;
					}
					
					$params[$k] = $value;
				}
				$options["conditions"] = $params;
				$sql .= " WHERE {$where} ";
			}
		}
		
		protected function addGroup(&$sql, $options) 
		{
			if(isset($options["group"]))
				$sql .= " GROUP BY {$options['group']} ";
		}
		
		protected function addOrder(&$sql, $options) 
		{
			if(isset($options["order"]))
			$sql .= " ORDER BY {$options['order']} ";
		}
		
		protected function addLimit(&$sql, $options) 
		{
			$offset = isset($options["offset"]) ? ",".$options["offset"] : "";
	
			if(isset($options["limit"]))
				$sql .= " LIMIT {$options['limit']}{$offset}";
		}
		
		
		
		
		
		public function typeToSql($type, $limit = null, $precision = null, $scale = null)
		{
			$natives = $this->nativeDatabaseTypes();
			if(isset($natives[$type]))
			{
				$native = $natives[$type];
				$columnTypeSql = is_array($native) ? $native["name"] : $native;
				
				
				if(is_null($limit) && is_array($native) && isset($native["limit"]))
					$limit = $native["limit"];
				
				if($type == "decimal")
				{
					if(is_null($scale) && isset($native["scale"]))
						$scale = $native["scale"];
					if(is_null($precision) && isset($native["precision"]))
						$precision = $native["precision"];
					
					if($precision != null)
						if($scale != null)
							$columnTypeSql .= "{$precision},{$scale}";
						else
							$columnTypeSql .= "{$precision}";
					else if($scale != null)
						throw new InvalidArgumentException("error adding decimal column precision can not be empty if scale is specified");
				} 
				else if($type != "primarykey" && ($limit != null))
				{
					$columnTypeSql .= "({$limit})";
				}
				
				return $columnTypeSql;
			} 
			return $type;
					
		}
		
		/**
		 * Quotes the value being sent do the database.
		 * 
		 * @param mixed $value
		 * @return mixed
		 */
		public function quote($value)
		{
			if($value == null)
				return "NULL";
	
			if($value instanceof Midori_Nullable)
			{
				$type = get_class($value);
				
				if($value->hasValue)
					$value = $value->value;
				else
					return "NULL";
				
			} else {
				$type = gettype($value);
			}
			
			switch($type)
			{
				case "Midori_String": 
				case "Midori_Text":
				case "string":
				case "Midori_Binary":
					return $this->quoteString($value);
				case "Midori_DateTime":
					return $this->quoteDateTime($value);
				case "Midori_Boolean":
				case "bool":
				case "boolean":
					return $this->quoteBoolean($value);
				default:
					return $value;	
			}
		}
		
		public function quoteBoolean($value)
		{
			return ((bool)$value) ? "'1'" : "'0'";
		}
		
		public function quoteString($value)
		{
			return "'$value'";
		}
		
		public function quoteDateTime($value)
		{
			return "'$value'";
		}
		
		public function quoteIdentifier($value)
		{
			return $value; 
		}
	}