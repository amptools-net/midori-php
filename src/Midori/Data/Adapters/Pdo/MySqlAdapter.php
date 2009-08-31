<?php

namespace Midori\Data\Adapters\Pdo
{	
	class MySqlAdapter extends Base
	{
		private static $native_database_types = null;
		private $schema = null;
		
		private $isMultipleQueriesAllowed = true;
		
		
		public function __construct($options)
		{
			$this->initLogger();
			$dsn = "mysql:";
			if(isset($options["dbname"]))
			{
				$dsn .= "dbname=".$options["dbname"].";";
				unset($options["dbname"]);	
			}
			if(isset($options["host"]))
			{
				$dsn .= "host=".$options["host"].";";
				unset($options["host"]);	
			}
			if(isset($options["port"]))
			{
				$dsn .= "port=".$options["port"].";";
				unset($options["port"]);	
			}
			$username = $options["username"]; $password = $options["password"];
			unset($options["username"], $options["password"]);
	
			//$options[ PDO::ATTR_PERSISTENT ] = true;
			$options[PDO::ATTR_AUTOCOMMIT] = 0;
			if(defined("PDO::MYSQL_ATTR_INIT_COMMAND"))
				$options[\PDO::MYSQL_ATTR_INIT_COMMAND] = "SET NAMES utf8";
			//$options[PDO::MYSQL_ATTR_USE_BUFFERED_QUERY] = true;
			
			$this->driver = new PDO(rtrim($dsn, ";"), $username, $password, $options);
			$this->driver->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->driver->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
			
			$env = Midori_Environment::getInstance();
			
			//$this->driver->getConnection()->exec("SET NAMES utf8");
			$this->execute("SET NAMES utf8");
			
			if($env->os == "WINNT" && ( ($env->phpVersion != "5.3.0beta1") && 
				(version_compare($env->phpVersion, "5.3.0", "<"))))
				$this->isMultipleQueriesAllowed = false;
		}
		
		public function getAllowsMultipleQueries()
		{
			return $this->isMultipleQueriesAllowed;
		}
		
		protected function getSupportsLastId()
		{
			return true;
		}
		
		/**
		 * 
		 * @return Midori_Data_Adapters_Pdo_MySqlSchema
		 */
		public function schema()
		{
			if($this->schema == null)
				$this->schema = new MySqlSchema($this);
			return $this->schema;
		}
		
		public function quoteDateTime($value)
		{
			if($value instanceof DateTime)
			{
				$value =  $value->format('Y-m-d H:i:s');
			}
			else 
				$value = date('Y-m-d H:i:s' , $value);
			return $this->quoteString($value); ;
		}
		
		protected function getLastInsertId()
		{
			return " SELECT LAST_INSERT_ID() as lastInsertId";
		}
		
		public function quoteString($value)
		{
			return $this->driver->quote($value, PDO::PARAM_STR);
		}
		
		public function quoteIdentifier($value)
		{
			return "`$value`";
		}
		
		
		public function typeToSql($type, $limit = null, $precision = null, $scale = null)
		{
			if($type != "integer")
				return parent::typeToSql($type, $limit, $precision, $scale);
				
			switch($limit)
			{
				case 1: return "tinyint";
				case 2: return "smallint";
				case 3: return "mediumint";
				case 4:
				case 11: return "int(11)";
				case 5:
				case 6:
				case 7:
				case 8: return "bigint";
				default:
					if(is_null($limit))
						return "int(11)";
				throw new InvalidArgumentException("no integer type has byte size {$limit}");
			}
		}
		
		
		public function nativeDatabaseTypes()
		{
			if(self::$native_database_types == null)
			{
				$test = (Midori_Environment::getInstance()->env == "test");
	
				$types = array(
					'fixturepk'			=>  "int(11) DEFAULT NULL",
					'primarykey'		=>	"int(11) DEFAULT NULL auto_increment ",
					'string'		=>	array("name" => "varchar", "limit" => 255),
					'text'			=>	array('name' => 'text'),
					'integer'		=>	array('name' => 'int', 'limit' => 4),
					'float' 		=>	array('name' => 'float'),	
					'decimal' 		=> 	array('name' => 'decimal'),	
					'datetime' 		=> 	array('name' => 'datetime'),	
					'timestamp' 	=> 	array('name' => 'timestamp'),	
					'time' 			=> 	array('name' => 'time'),	
					'date' 			=> 	array('name' => 'date'),	
					'binary'		=> 	array('name' => 'blob'),	
					'boolean'		=>	array('name' => 'tinyint', 'limit' => 1)
				);
				self::$native_database_types = $types;
			}	
			return self::$native_database_types;
		}
	}
}