<?php

namespace Midori\Data
{
	/**
	 * 
	 * @author Michael
	 * @property string $compositeColumnName 
	 */
	class  PrimaryKeyConstraint extends Constraint
	{
		
		public function __construct($tableName, $tableAlias, $columnName, $compositeColumName = null)
		{
			$this->tableAlias = strtolower($tableAlias);
			$this->tableName = $tableName;
			$this->columnName = strtolower($columnName);
			$this->prefix = "pk";
			$this->constraintName = "PRIMARY KEY";
			$this->type = "primarykey";
			$this->name = "{$this->prefix}_{$this->tableAlias}_{$this->columnName}";
		}
		
		
		
		public function generate()
		{
			$sql = "(".$this->columnName;
			if($this->compositeColumnName)
				$sql .= ",".$this->compositeColumnName.")";
			else 
				$sql .= ")";
			return $sql;
		}
		
	}
}