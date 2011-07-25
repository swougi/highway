<?php

class connection extends prototype
{
	public $connection;

	private $db_server  = "";
	private $db_user    = "";
	private $db_passw   = "";
	private $db_name	= "";
	private $db		    = "";
	function __construct($config)
	{
        $this->config = $config;
		$this->query = "";
		
	}

	function _connectToDB()
	{
		if(!is_resource($this->db))
		{
			
			$this->db = mysql_connect($this->config['host'], 
								  $this->config['username'], 
								  $this->config['password']);
						  
			if($this->db) {
				if(mysql_select_db($this->config['database'], $this->db)) {
					return TRUE;
				}
			}
		}
	}
	function _connection()
	{
		$this->_connectToDB();
		return $this->db;
	}
	
	function sanitize($a)
	{
		return mysql_real_escape_string($a,$this->_connection());
	}
	function _select($query)
	{
		if (strlen($query) > 0)
		{
			$this->_connectToDB();
			$result = mysql_query($query, $this->db) or $this->_throwSQLException('Problem occured running select query : '.$query.' : error : '.mysql_error());
			if (@mysql_num_rows($result) > 0)
			{
				return $result;
			}
		}

		return FALSE;
	}
	
	function _selectSingle($query)
	{
		if (strlen($query) > 0)
		{
			$result = $this->_select($query);
			if ($result !== false)
			{
				return mysql_fetch_assoc($result);
			}
		}
		return false;
	}
    function _update($query)
	{
		if (strlen($query) > 0)
		{
			$this->_connectToDB();
			
			mysql_query($query, $this->db) or $this->_throwSQLException('Problem occured running update query : '.$query.' : error : '.mysql_error());
			return TRUE;
		}
		return FALSE;
	}

	function _insert($query)
	{
		if (strlen($query) > 0)
		{
			$this->_connectToDB();
			mysql_query($query, $this->db) or $this->_throwSQLException('Problem occured running insert query : '.$query.' : error : '.mysql_error());
			if ( ( $id = mysql_insert_id($this->db) ) != 0)
			{
                            return $id;
			}
		}
		return FALSE;
	}

	function _delete($query)
	{
		if (strlen($query) > 0)
		{
			$this->_connectToDB();
			$result = mysql_query($query, $this->db) or $this->_throwSQLException('Problem occured running _delete query : '.$query.' : error : '.mysql_error());
			return $result;
		}
		return FALSE;
	}
	
	function fetch_tables()
	{
		$query = "SHOW TABLES";
		return $this->run_raw_sql($query);
	}
	function create_table($table_name)
	{
		$query = "
			CREATE TABLE  `".$this->config['database']."`.`".$table_name."` (
				`id` INT NOT NULL AUTO_INCREMENT ,
				PRIMARY KEY (  `id` )
				) ENGINE = INNODB;
		";
		return $this->run_raw_sql($query);
	}
	function fetch_attributes($tablename)
	{
		$this->_connectToDB();
		$query = "SHOW COLUMNS FROM %s";
		
		$result = $this->do_raw_query($query, $tablename);
		$attributes = Array();
		if ($result)
		{
			foreach($result as $row) {
						$attributes[$row['Field']] = $row;
			}
			return $attributes;
		}
		die("Cannot fetch attributes: ".$this->error."\n");
	}
	function create_attribute($tablename,$field,$options)
	{
		$this->_connectToDB();
		$query=($options['type']=="primary")?"ALTER TABLE  `".$tablename."` ADD  `".$field."` INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST":"ALTER TABLE ".$tablename." ADD ".$field." ".strtoupper($options['type'])."(".$options['length'].")";
		$results = mysql_query($query,$this->db);
	   
	}
	function remove_attribute($tablename,$field)
	{
		$this->_connectToDB();
		$query = "ALTER TABLE ".$tablename." DROP COLUMN ".$field;
		$results = mysql_query($query,$this->db);
		
	}
	
	//TODO: make this final path for all sql (add empty subs)
	function make_raw_sql($query_string, $subs)
	{
		$sub_strings = Array();
		if(is_array($subs)) {
			foreach($subs as $sub) {
				$sub_strings[] = mysql_real_escape_string($sub);
			}
			$substitutions = "'".implode("', '", $sub_strings)."'";
			eval('$query = sprintf($query_string, $substitutions);');	
		} else {
			$query = sprintf($query_string, mysql_real_escape_string($subs));
		}
		
		return $query;
	}
	
	function run_raw_sql($query)
	{
		$this->_connectToDB();
		$return_values = Array();
		$results = mysql_query($query,$this->db);
		
		if($results) {
				
				while($row = mysql_fetch_assoc($results)) {
					$return_values[] = $row;
				}
			
			return $return_values;
		} else {
			$this->error = mysql_error();
			return false;
		}
	}
	
	function do_raw_query($query, $params)
	{
		return $this->run_raw_sql($this->make_raw_sql($query, $params));
	}
	function _throwSQLException($message)
	{
		die($message);
	}

	function __destruct()
	{
		if (is_resource($this->db))
		{
			mysql_close($this->db);
		}
	}
}

?>
