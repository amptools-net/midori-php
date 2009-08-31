<?php

namespace Midori\Data
{
	/**
	 * 
	 * @author Michael
	 * @property string $tableName
	 * @property string $tableAlias
	 * @property boolean $force
	 * @property string $options
	 * @property Midori_Data_Adapter $adapter
	 * @property Midori_List $columns
	 * @property Midori_List $constraints
	 */
	class Table extends Object implements \ArrayAccess
	{
		
		
		public function __construct($adapter, $name, $alias = null, $options = false)
		{
			$this->adapter = $adapter;
			$this->tableName = $name;
			$this->tableAlias = $alias;
			$this->columns = new Midori_List();
			$this->constraints = new Midori_List();
			$this->options = ($options === false) ? null : $options;
		}
		
		
		public function constraint($type, $columnName, $options = array())
		{
			$index = $this->indexOfConstraint($type, $columnName);
			
			if($index > -1) 
				$constraint = $this->constraints[$index];
			else 
			{
				$string = new Midori_String($type);
				$class = "Midori_Data_".$string->camelize()."Constraint";
				$tableAlias = is_null($this->tableAlias) ? $this->tableName : $this->tableAlias;
				$constraint = new $class($this->tableName, $tableAlias, $columnName);
			}				
			
			$constraint->send($options);
			
			if($index == -1)
				$this->constraints->add($constraint);
		}
		
		public function column($name, $type, $options = array())
		{
			$index = $this->indexOfColumn($name);
			
			$column = ($index > -1) ? 
				$this[$name] : new Column($this->adapter, $name, $type);
	
			$column->send($options);
				
			if($index == -1)
				$this->columns->add($column);
		}
		
		public function toSql()
		{
			$sql = "CREATE TABLE ". $this->adapter->quoteIdentifier($this->tableName). " (\n\t";
			
			foreach($this->columns as $column)
				$sql .= $column->toSql().",\n\t";
			
			foreach($this->constraints as $constraint)
				$sql .= $constraint->toSql().",\n\t";
			
			$options = is_null($this->options) ? "" : $this->options;
				
			$sql = rtrim($sql, ",\n\t").") {$options}";
			
			return $sql;
		}
		
		/**
		 * Determines if an item exists at the given offset
		 * 
		 * @param $offset int|string
		 * @return boolean
		 */
		public function offsetExists($columnName)
		{
			return isset($this->columns[$columnName]);
		}
		
		public function indexOfConstraint($type, $columnName)
		{
			$count = $this->constraints->count();
			for($i = 0; $i < $count; $i++)
			{
				$constraint = $this->constraints[$i];
				if($constraint->type == $type && $constraint->columnName == $columnName)
					return $i;
			}
			return -1;
		}
		
		public function indexOfColumn($columnName)
		{
			$count = $this->columns->count();
			for($i = 0; $i < $count; $i++)
				if($this->columns[$i]->name == $columnName)
					return $i;
			return -1;
		}
		
		/**
		 * Gets the item at the given offset/position.
		 * 
		 * @param $offset int|string
		 * @return mixed the item at the offset.
		 */
		public function offsetGet($columnName)
		{
			foreach($this->columns as $column)
				if($column->name == $columnName)
					return $column;
		}
		
		/**
		 * Sets the item at the given
		 * offset/position.
		 * 
		 * @param $offset int|string
		 * @param $value mixed
		 * @return void
		 */
		public function offsetSet($columnName, $value)
		{
			$count = $this->columns->count();
			$found = false;
			for($i = 0; $i < $count; $i++)
			{
				if($this->columns[$i]->name == $columnName)
				{
					$this->columns[$i] = $value;
					$found = true;
					break;
				}
			}
			if(!$found)
				$this->columns[] = $value;
		}
		
		/**
		 * Deletes an item from the enumerable object
		 * at the given offset/position.
		 * 
		 * @param $offset
		 * @return void
		 */
		public function offsetUnset($columnName)
		{
			unset($this->columns[$columnName]); 
		}
	}
}