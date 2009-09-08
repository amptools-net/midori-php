<?php

namespace Midori\ActiveRecord
{	
	/**
	 * 
	 * @author Michael
	 * @property Midori_Data_Adapters_Pdo_MySqlAdapter $adapter
	 * @property string $tableName
	 * @property string $tableAlias
	 * @property string $primaryKey
	 */
	class Service extends \Midori\Data\Object
	{
		private $transaction = false;
		protected $type = null;
		
		public function __construct($tableName, $tableAlias, $primaryKey, $adapter = "default")
		{
			$this->tableName = $tableName;
			$this->tableAlias = $tableAlias;
			$this->primaryKey = $primaryKey;
			$config = Midori\config();
			if($config->dataAdapter == null)
				throw new \Midori\Exception('Midori_Environment::$defaultAdapter must be set in order to use active record');
			$this->adapter = $config->dataAdapter;
		}
		
		/**
		 *
		 * @return Midori_ActiveRecord_Service
		 */
		public static function fetch($type)
		{
			$service = \Midori\Meta::get($type, "service");
			if($service == null)
			{
				$x = new $type();
				if($x instanceof Base ||$x instanceof \Midori\Object )
				{
					$service = 
						new Service(
							$x->tableName, 
							$x->tableAlias, 
							$x->primaryKey);
					$service->type = $type;
					\Midori\Meta::add($type, "service", $service);
				}
			}
			return $service;
		}
		
		protected function defaultOptions(&$options = array())
		{
			if(!isset($options["class"]))
				$options["class"] = $this->type;
			if(!isset($options["from"]))
				$options["from"] = $this->tableName;
		}
		
		/**
		 * 
		 * @param $scope
		 * @param $options
		 * @return Midori_List
		 */
		public function find($scope, $options = array())
		{
			$this->defaultOptions($options);
			return $this->adapter->find($scope, $options);
		}
		
		/**
		 * 
		 * @param $options
		 * @return Midori_List
		 */
		public function findAll($options = array())
		{
			$this->defaultOptions($options);
			return $this->adapter->find("all", $options);
		}
		
		/**
		 * 
		 * @param $options
		 * @return Midori_ActiveRecord_Base
		 */
		public function findOne($options)
		{
			$this->defaultOptions($options);
			return $this->adapter->find("one", $options);
		}
		
		public function findCount($options)
		{
			$this->defaultOptions($options);
			return $this->adapter->findCount($options);
		}
		
		public function findBySql($sql, $params = array())
		{
			return $this->adapter->findBySql($sql, $params);
		}
		
		public function beginTransaction()
		{
			$this->adapter->beginTransaction();
		}
		
		public function commitTransaction()
		{
			$this->adapter->commit();	
		}
		
		public function delete($id)
		{
			$pk = $this->adapter->quoteIdentifier($this->primaryKey);
			return $this->adapter->delete($this->tableName, "{$pk} = $id");	
		}
		
		public function insert($changed = array())
		{
			return $this->adapter->insert($this->tableName, $changed);
		}
		
		public function update($id, $changed = array())
		{
			$pk = $this->adapter->quoteIdentifier($this->primaryKey);
			return $this->adapter->update($this->tableName, $changed, "{$pk} = $id");
		}
	}
}