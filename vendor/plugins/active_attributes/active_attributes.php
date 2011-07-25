<?
class active_attributes
{
	function __construct($class)
	{
		$this->active_record  	    = $class;
		$this->connection     	    = $class::$connection;
		$this->table_name           = pluralize($class);
		$class::$db_columns         = array();
		$class::$db_columns 	    = $this->connection->fetch_attributes($this->table_name);
		$class::$columns    	    = array();
		
		
	}
	public static function load($class)
	{
		if($class::uses('active_attributes'))
		{
			$tables = $class::$connection->fetch_tables();
			if(!in_array(pluralize($class),$tables))
			{
				$class::$connection->create_table(pluralize($class));
			}
		
			$class::active_attributes(new active_attributes($class));
		}
	}			
	function map($fields)
	{
		$active_record = $this->active_record;
		foreach($fields as $field=>$options)
		{
			//TODO: add db type compare checks here
			if(!array_key_exists($field,$active_record::$db_columns))
			{
				 $this->connection->create_attribute($this->table_name,$field,$options);
			}
			$active_record::$columns[] =  $field;
		}
		
		foreach($active_record::$db_columns as $column=>$row)
		{
			if(!in_array($column,$active_record::$columns))
			{
				$this->connection->remove_attribute($this->table_name,$column);
			}
		}
		//$this->active_record::build_default($this->model->attributes);
	}
	
	
	
	
}
?>