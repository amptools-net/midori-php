<?php
namespace Midori\Data\Adapters\Pdo
{
	
	class MySqlSchema extends \Midori\Data\Schema
	{
		
		public $current_database = "";
		
		public function __construct($adapter)
		{
			parent::__construct($adapter);
		}
		
		
		public function disableForeignKeys()
		{
			return $this->execute("SET foreign_key_checks = 0");
		}
		
		public function enableForeignKeys()
		{
			return $this->execute("SET foreign_key_checks = 1");
		}
		
		public function database()
		{
			if($this->current_database == "")
			{
				$fields = $this->adapter->selectOne("SELECT  DATABASE() as `database`");
				$this->current_database = $fields["database"];
			}
			
			return $this->current_database;
		}
		
		public function tableExists($database, $tableName)
		{
			$item = $this->adapter->selectOne("SELECT COUNT(*) as count
				FROM INFORMATION_SCHEMA.TABLES
				WHERE `TABLE_SCHEMA` = '{$database}' AND `TABLE_NAME` = '{$tableName}'
			");
			return $item["count"];
		}
		
		public function databaseExists($databaseName)
		{
			$item = $this->adapter->selectOne("SELECT COUNT(*) as count
				FROM INFORMATION_SCHEMA.SCHEMATA
				WHERE `SCHEMA_NAME` = '$databaseName'
			");
			return $item["count"];
		}
		
		public function useDatabase($databaseName)
		{
			$this->current_database = $databaseName;
			return parent::useDatabase($databaseName);
		}
		
		public function constraints($tableName)
		{
			$constraints = new Midori_List();
			$database = $this->database();
			
			$sql = "
				SELECT DISTINCT
					cu.COLUMN_NAME as `column_name`,
					cu.REFERENCED_TABLE_NAME as `referenced_table_name`,
					cu.REFERENCED_COLUMN_NAME as `referenced_column_name`,
	        tc.CONSTRAINT_NAME as `name`,
					tc.CONSTRAINT_TYPE as `type`
	
					FROM  INFORMATION_SCHEMA.KEY_COLUMN_USAGE as cu
						LEFT OUTER JOIN
	          INFORMATION_SCHEMA.TABLE_CONSTRAINTS as tc
						 ON tc.CONSTRAINT_NAME = cu.CONSTRAINT_NAME
	           AND tc.TABLE_SCHEMA = cu.TABLE_SCHEMA
	           AND cu.TABLE_NAME = tc.TABLE_NAME
					WHERE cu.TABLE_NAME = '{$tableName}' 
						AND cu.TABLE_SCHEMA = '$database'
			";
			
			$rows = $this->adapter->selectAll($sql);
			foreach($rows as $row)
			{
				$constraint = null;
				switch($row["type"])
				{
					case "PRIMARY KEY":
						$constraint = new \Midori\Data\PrimaryKeyConstraint($tableName, $tableName, $row["column_name"]);
						//$constraint->name = $row["name"];
						break;
					case "FOREIGN KEY":
						$constraint = new \Midori\Data\ForeignKeyConstraint($tableName, $tableName, $row["column_name"]);
						$constraint->referenceTable = $row["referenced_table_name"];
						$constraint->referenceColumn = $row["referenced_column_name"];
						break;
				}
				if($constraint != null)
					$constraints->add($constraint);
			}
			return $constraints;
		}
		
		public function columns($tableName)
		{
			$columns = new Midori_List();
			$database = $this->database();
			
			$sql = "
			SELECT 
				COLUMN_NAME as `name`,
				COLUMN_DEFAULT as `default`,
				IS_NULLABLE as `null`,
				DATA_TYPE as `type`,
				CHARACTER_MAXIMUM_LENGTH as `length`,
				NUMERIC_PRECISION as `precision`,
				NUMERIC_SCALE as `scale`,
				ORDINAL_POSITION as `position`
	
					FROM INFORMATION_SCHEMA.COLUMNS  
					WHERE TABLE_NAME = '{$tableName}' 
						AND TABLE_SCHEMA = '$database'
			";
			
			$rows = $this->adapter->selectAll($sql);
			foreach($rows as $row)
			{
				$column = new \Midori\Data\Column($this->adapter, $row["name"], $row['type']);
				$column->length = $row["length"];
				$column->null = $row["null"];
				$column->default = is_null($row["default"]) ? "" : $row["default"];
				$column->precision = $row["precision"];
				$column->scale = $row["scale"];
				$column->position = $row["position"];
				$columns->add($column);
			}
			return $columns;
		}
		
		public function createTable($table)
		{
			if($table->options == null)
				$table->options = "ENGINE=InnoDB CHARSET=utf8";
			return parent::createTable($table);
		}
		
		public function renameTable($tableName, $newName)
		{
			return $this->execute("RENAME TABLE {$this->adapter->quoteIdentifier($tableName)} TO {$this->adapter->quoteIdentifier($newName)}");
		}
		
		public function changeColumnDefault($tableName, $columnName, $default)
		{
			$column = $this->columnFor($tableName, $columnName);
			return $this->changeColumn($tableName, $columnName, $column->type, array("default" => $default));
		}
		
		public function changeColumnNull($tableName, $columnName, $null, $default = null)
		{
			$column = $this->columnFor($tableName, $columnName);
			
			if($null == false || !is_null($default))
			{
				$this->execute(
			"UPDATE {$this->quoteIdentifier($tableName)} SET {$this->quoteIdentifier($columnName)}={$this->quote($default)} 
				WHERE {$this->quoteIdentifier($columnName)} IS NULL
			"
				);
			}
			
			return $this->changeColumn($tableName, $columnName, $column->type, array("null" => $null));
		}
		
		public function changeColumn($tableName, $columnName, $type, $options = array())
		{
			$column = $this->columnFor($tableName, $columnName);
			$options = $this->defaultOptions($options);
			
			if(!is_null($options["default"]))
				$options["default"] = $column->default;
			
			if($options["null"] != $column->null)
				$options["null"] = $column->null;
			
			$sql = "
			ALTER TABLE {$this->quoteIdentifier($tableName)} 
				CHANGE {$this->quoteIdentifier($columnName)}
					{$this->quoteIdentifier($columnName)} {$this->typeToSql($type, $options['limit'], $options['precision'], $options['scale'])}";
					
			$sql = $this->addColumnOptions($sql, $options);
			
			return $this->adapter->execute($sql);
		}
		
		
		protected function columnFor($tableName, $columnName)
		{
			$columns = $this->columns($tableName);
			foreach($columns as $column)
				if($column->name == $columnName)
					return $column;
			throw new InvalidArgumentException("No such column {$tableName}.{$columnName}");
		}
				
	}
}