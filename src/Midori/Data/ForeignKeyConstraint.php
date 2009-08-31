<?php

namespace Midori\Data
{
	/**
	 * 
	 * @author Michael
	 * @package Midori
	 * @property $referenceTable
	 * @property $referenceColumn
	 * @property $delete
	 * @property $update
	 */
	class ForeignKeyConstraint extends Constraint
	{
		
		public function __construct($tableName, $tableAlias, $columnName)
		{
			$this->tableAlias = strtolower($tableAlias);
			$this->tableName = $tableName;
			$this->columnName = strtolower($columnName);
			$this->prefix = "fk";
			$this->constraintName = "FOREIGN KEY";
			$this->type = "foreignkey";
			$this->delete = null;
			$this->update = null;
			$this->name = "{$this->prefix}_{$this->tableAlias}_{$this->columnName}";
		}
		
		
		public function generate()
		{
			$sql = " {$this->tableName}_{$this->columnName} ({$this->columnName}) 
			REFERENCES {$this->referenceTable} ({$this->referenceColumn}) ";
				
			if(!is_null($this->delete))
				$sql .= " ON DELETE {$this->delete} ";
			if(!is_null($this->update))
				$sql .= " ON UPDATE {$this->update} ";
			return $sql;
		}
		
	}
}