<?
class active_attributes
{
	function __construct($active_record,&$model)
	{
		$this->active_record  	    = $active_record;
		$this->connection     	    = $active_record::$connection;
		$this->table_name           = $active_record::$table_name;
		$this->model		    = $model;
		$this->model->db_columns    = $this->connection->fetch_attributes($active_record::$table_name);
		$active_record::$columns    = array();

	}
			
	function map($fields)
	{
		$active_record = $this->active_record;
		foreach($fields as $field=>$options)
		{
			//TODO: add db type compare checks here
			if(!array_key_exists($field,$this->model->db_columns))
			{
				 $this->connection->create_attribute($this->table_name,$field,$options);
			}
			$active_record::$columns[] =  $field;
		}
		
		foreach($this->model->db_columns as $column=>$row)
		{
			
			if(!in_array($column,$active_record::$columns))
			{
				$this->connection->remove_attribute($this->table_name,$column);
			}
		}
	}
	
	
	
}
?>