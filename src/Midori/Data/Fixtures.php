<?php
	
namespace Midori\Data
{	
	/**
	 * 
	 * @author Michael
	 * @package Midori
	 */
	use Midori\Console;
	class Fixtures extends \Midori\Object
	{
		
		private static $fixturesFilePath;
		private static $modelsFilePath;
		/**
		 * 
		 * @var Midori_Data_Adapter
		 */
		private static $adapter;
		
		public static function setFixturesFilePath($filePath)
		{
			self::$fixturesFilePath = $filePath;
		}
		
		public static function setAdapter($adapter)
		{
			self::$adapter = $adapter;
			self::$adapter->schema()->useDatabase(config()->database["dbname"]);
		}
		
		public static function loadAll()
		{
			$adapter = self::$adapter->schema()->disableForeignKeys();
			$files = Midori\Directory::getFiles(self::$fixturesFilePath);
			foreach($files as $file)
			{
				self::parse(self::$fixturesFilePath.DIRECTORY_SEPARATOR.$file);
			}
			$adapter = self::$adapter->schema()->enableForeignKeys();
		}
		
		public static function load()
		{
			$adapter = self::$adapter->schema()->disableForeignKeys();
			$args = func_get_args();
			if(is_array($args[0]))
				$args = $args[0];
			$list = new Midori_List($args);
			foreach($list as $fixture)
				self::_load($fixture);	
			$adapter = self::$adapter->schema()->enableForeignKeys();
		}
		
		private static function delete($fixture)
		{
			$table = self::$adapter->quoteIdentifier($fixture);
			self::$adapter->execute("TRUNCATE TABLE {$table}");
		}
		
		private static function parse($file)
		{
			$path = pathinfo($file);
			$fixture = $path["filename"];
			
			if(file_exists($file))
				require $file;
			else {
				echo "a file for '$fixture' does not exists.";
				return;
			}
			
			$rows = $data["rows"];
			try {
		 
				self::delete($fixture);
		
					
				if(count($rows) > 0)
				{
					//TODO: migrate using types like Midori_DateTime
					$schema = self::$adapter->schema();
					$columns = $schema->columns($fixture);
						
					foreach($rows as $row)
					{
						
						$params = array();
						foreach($row as $k => $value)
							if(self::containsColumn($k, $columns))
								$params[$k] = $value;
							
						self::$adapter->insert($fixture, $params);
						
					}
						
				}
			} catch(Exception $ex) {		
				throw new Exception("error occured for $fixture ", 0, $ex);
			}
			
		}
		
		private static function _load($fixture)
		{
			
			$string = new Midori_String($fixture);
			if($string->startsWith("_config."))
			{
				$class = "";
				$path = self::$fixturesFilePath."{$string->toString(false)}.php";
			} else {
				$class = $string->camelize();
				$path = self::$fixturesFilePath.$fixture.".php";
			}
			self::parse($path);
			
			
		}
		
		private static function containsColumn($name, $columns)
		{
			foreach($columns as $column)
				if(strtolower($column->name) == strtolower($name))
					return true;
			return false;	
		}
		
	}
}