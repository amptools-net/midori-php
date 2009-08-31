<?php

namespace Midori\Data
{	
	/**
	 * 
	 * @author Michael
	 * @package Midori
	 * @property Midori_Data_Adapter $adapter
	 */
	abstract class Schema extends Object
	{
		
		public function __construct($adapter)
		{
			$this->adapter = $adapter;
		}
		
		public function execute($sql, $params = array())
		{
			return $this->adapter->execute($sql, $params);
		}
		
		public abstract function disableForeignKeys();
		
		public abstract function enableForeignKeys();
		
		public function useDatabase($databaseName)
		{
			return $this->execute("USE {$this->quoteIdentifier($databaseName)};");
		}
		
		public function createDatabase($databaseName)
		{
			return $this->execute("CREATE DATABASE {$this->quoteIdentifier($databaseName)}");
		}
		
		public function dropDatabase($databaseName)
		{
			return $this->execute("DROP DATABASE {$this->quoteIdentifier($databaseName)}");
		}
		
		public function createTableDefinition($name, $alias = null)
		{
			$table = new Midori_Data_Table($this->adapter, $name, $alias);
			return $table;
		}
		
		public function createTable($table)
		{
			return $this->execute($table->toSql());
		}
		
		
		
		public function addIndex($tableName, $columnName,  $options = array())
		{
			$columnNames = new Midori_List($columnName);
			$indexName = $this->indexName($tableName, array("column" => $columnNames));
			
			$indexType = isset($options["unique"]) ? "UNIQUE" : "";
			$indexName = isset($options["name"]) ? $options["name"] : $indexName;
			
			$quote = "";
			foreach($columnNames as $name)
			{
				$quote .= "{$this->quoteIdentifier($name)},";
			}
			$quote .= rtrim($quote, ",");
			
			return $this->execute("CREATE {$indexType} INDEX {$this->quoteIdentifier($indexName)}
				ON {$this->quoteIdentifier($tableName)}({$quote})}");
		}
		
		public function removeIndex($tableName, $options)
		{
			$indexName = $this->indexName($tableName, $options);
			return $this->execute("DROP INDEX {$this->adapter->quoteIdentifier($indexName)} ON {$this->adapter->quoteIdentifier($tableName)}");
		}
		
		public function indexName($tableName, $options)
		{
			if(isset($options["column"]))
			{
				$names = "";
				foreach($options["column"] as $columnName)
					$names .= "$columnName_and_";
				$names = rtrim($names, "_and_");
				
				return "ix_{$tableName}_on_{$names}";
			} else if($options["name"])
				return $options["name"];
				
			throw new InvalidArgumentException("you must specify the index name");		
		}
		
		protected function defaultOptions($options = array())
		{
			if(!isset($options["limit"]))
				$options["limit"] = null;
			if(!isset($options["precision"]))
				$options["precision"] = null;
			if(!isset($options["scale"]))
				$options["scale"] = null;
			if(!isset($options["null"]))
				$options["null"] = true;
			if(!isset($options["default"]))
				$options["default"] = null;
	
			return $options;
		}
		
		public function addColumn($tableName, $columnName, $type, $options = array())
		{
			$options = $this->defaultOptions($options);
			
			$sql = "ALTER TABLE {$this->quoteIdentifier($tableName)} 
					 ADD {$this->quoteIdentifier($columnName)}
					{$this->typeToSql($type, $options['limit'], $options['precision'], $options['scale'])}";
				
			$sql = $this->addColumnOptions($sql, $options);
			$this->execute($sql);
			
			return $this;
		}
		
		public function removeColumns()
		{
			$args = func_get_args();
			$tableName = array_shift($args);
			foreach($args as $columnName)
				$this->execute("ALTER TABLE {$this->quoteIdentifier($tableName)} DROP {$this->adapter->quoteIdentifier($columnName)}");
			return $this;
		}
		
		public function removeColumn($tableName, $columnName)
		{
			return $this->removeColumns($tableName, $columnName);	
		}
		
		/**
		 *
		 * Certain SQL defaults like CURRENT_TIMESTAMP do not take parentheses and quotes
		 * If we get more examples and cases we should refactor the code into a switch statement
		 */
		public function addColumnOptions($sql, $options)
		{
			if(!is_null($options["default"]) && ($options["default"] != 'CURRENT_TIMESTAMP') && ($options["default"] != "0"))
				$sql .= " DEFAULT {$this->quote($options['default'])}";
			elseif(!is_null($options["default"]) && ($options["default"] == "0"))
				$sql .= " DEFAULT {$options['default']}";
	
			if($options["null"] == false)
				$sql .= " NOT NULL";
			return $sql;
		}
		
		public function dropTable($tableName)
		{
			return $this->execute("DROP TABLE {$this->quoteIdentifier($tableName)}");
		}
		
		public function dropForeignKey($tableName, $constraint)
		{
			return $this->execute("ALTER TABLE {$this->quoteIdentifier($tableName)} DROP FOREIGN KEY {$constraint}");
		}
		
		public function dropPrimaryKey($tableName)
		{
			return $this->execute("ALTER TABLE {$this->quoteIdentifier($tableName)} DROP PRIMARY KEY");
		}
		
		protected function typeToSql($type, $limit, $precision, $scale)
		{
			return $this->adapter->typeToSql($type, $limit, $precision, $scale);
		}
		
		protected function quoteIdentifier($value)
		{
			return $this->adapter->quoteIdentifier($value);
		}
		
		protected function quote($value)
		{
			return $this->adapter->quote($value);
		}
	}
}