<?php
	

namespace Midori\Data\Adapters\Pdo	
{
	/**
	 * 
	 * @author Michael
	 * @property bool $supportsLastId
	 */
	abstract class Base extends \Midori\Data\Adapter
	{
		
		protected $transaction = false;
		
		
		
		
		protected function init($dsn, $username = null, $password = null, $options = array())
		{
			
			$this->driver = new \Pdo($dsn, $username, $password, $options);
			
			$this->supportsLastId = true;
		}
		
	
		
		public function beginTransaction()
		{
			if($this->transaction == false)
			{
				$this->transaction = true;
				$this->driver->beginTransaction();
				return true;
			}
			return false;
		}
		
		public function commit()
		{
			if($this->transaction == true)
			{
				$this->transaction = false;
				$this->driver->commit();
				return true;
			}
			return false;
		}
		
		public function rollback()
		{
			if($this->transaction == true)
			{
				$this->driver->transaction = false;
				$this->driver->rollBack();
				return true;
			}
			return false;
		}
		
		
		public function selectObjects($class, $sql, $params = array())
		{
			$this->log->sql($sql);
			$statement = $this->driver->prepare($sql);
			$statement->execute($params);
			$list = new \Midori\ListOf();
			
			 while ($object = $statement->fetchObject($class)) {
			 	$object->markFetched();
			 	$list->add($object);
			 }
			 return $list;
		}
		
		public function selectAll($sql, $params = array())
		{
			$sets = array();
		 	$i = 0;
			$this->log->sql($sql);
			
			$statement = $this->driver->prepare($sql);
			$statement->execute($params);
			
			if($this->allowsMultipleQueries)
			{
				do {
		    		$sets[$i] = $statement->fetchAll(\PDO::FETCH_ASSOC);
		    		$i++;
				} while ($statement->nextRowset());
			} 
			else 
			{
				$sets[] = $statement->fetchAll(\PDO::FETCH_ASSOC);
				$statement->closeCursor();
			}
			
			unset($statement);
			
			if(count($sets) == 1)
				return $sets[0];
		
			return $sets;
		}
		
		public function selectRow($sql, $params = array())
		{
			$this->log->sql($sql);
			$statement = $this->driver->prepare($sql);
			$statement->execute($params);
			$items = $statement->fetch(\PDO::FETCH_ASSOC);	
			$statement->closeCursor();
			unset($statement);
			return $items;
		}
		
		public function selectOne($sql, $params = array())
		{
			$this->log->sql($sql);
			$statement = $this->driver->prepare($sql);
			$statement->execute($params);
			$item = $statement->fetch(\PDO::FETCH_NUM);	
			$statement->closeCursor();
			unset($statement);
			return $item[0];
		}
		
		public function execute($sql, $params = array())
		{
			$this->log->sql($sql);
			$statement = $this->driver->prepare($sql);
			return $statement->execute($params);
		}
		
		
		public function delete($table, $where)
		{
			$transaction = $this->beginTransaction();
			$count = 0;
			
			try
			{
				$sql =  "DELETE FROM {$this->quoteIdentifier($table)} WHERE $where ";
				
				$this->log->sql($sql);
				
				$count = $this->driver->exec($sql);
				
				
				if($transaction)
					$this->commit();
			} 
			catch (Exception $ex)
			{
				if($transaction)
					$this->rollback();
				throw $ex;
			}
			
			return $count;
		}
		
		public function insert($table, $values)
		{
			$transaction = $this->beginTransaction();
			$id = null;
	
			try
			{
				
				$sql = new \Midori\String("INSERT INTO {$this->quoteIdentifier($table)} ");
				$columns = new Midori_String(" (");
				$fields = new Midori_String(" VALUES (");
				foreach($values as $column => $field)
				{
					$columns->append($this->quoteIdentifier($column).",");
					$temp = $this->quote($field);
					$fields->append($temp.",");
				}
				
				$sql->append($columns->trimEnd(",")->append(")"));
				$sql->append($fields->trimEnd(",")->append(");"));
				
				$query = $sql->__toString();
		
				$this->log->sql($query);
				//echo "inserting: $query <br />";
				$stmt = $this->driver->exec($query);
	
				
				if($this->supportsLastId)
				{
					$id = $this->selectOne($this->getLastInsertId());
				}
				
				
				if($transaction)
					$this->commit();
			} 
			catch (Exception $ex) 
			{
				//if($transaction)
				//	$this->rollback();
				throw $ex;
			}
			
			return $id;
		}
		
		public function update($table, $values, $where)
		{
			$transaction = $this->beginTransaction();
			$count = 0;
			
			try 
			{
				$sql = new \Midori\String("UPDATE {$this->quoteIdentifier($table)} SET ");
				
				foreach($values as $column => $value)
					$sql->append("\n\t{$this->quoteIdentifier($column)} = {$this->quote($value)},");
				
				$sql = $sql->trimEnd(",");
				$sql->append("\n\t WHERE ". $where);
				
				$query = $sql->__toString();
				$this->log->sql($query);
				//echo "updating $query <br /> " ;
				
				$count = $this->driver->exec($query);
				if($transaction)
					$this->commit();
			} 
			catch(Exception $ex) 
			{
				if($transaction)
					$this->rollback();
				throw $ex;
			}
			return $count;
		}
	
		
	}
}