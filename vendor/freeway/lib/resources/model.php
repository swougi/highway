<?php
/**
 * Description of action_record
 *
 * @author Martin
 */
class model extends prototype{
    
	public static $includes     = array();
	
	public static $table 	     = array();
    
	public static $table_name    = "";
    public static $db_columns    = array();
	public static $columns       = array();
    public static $attr_accessor = array();
	public static $connection    = array();
	  
    var $attributes              = array();
    var $virtual_attributes      = array();
	var $method_attributes       = array();
    var $error_messages          = array();
    var $error_fields            = array();
  	
    function __construct($attributes=array())
    {
    	$class				= get_called_class();
		$this->class_name   = $class =  get_called_class();
		$this->table_name   = $class::$table_name = pluralize($this->class_name);
		$this->columns      = $class::$columns; 
		$this->build_default();
		$this->build($attributes);
		parent::__construct();
		
   }
    public static function create($attributes)
    {
        
		$class = get_called_class();
		
		$class::$table_name = pluralize($class);
		$model  = new $class($attributes);
      
		$model->before_validate();
        $model->validate();
        $model->after_validate();
        $model->before_create();
		
	   if($model->valid())
    	{
            $model->save();
        }
        $model->after_create();
         	
		return $model;
    }
	
    public static function find($args)
    {
        $class 				= get_called_class();
		$class::$table_name = pluralize($class);
		$args 				= func_get_args();
		switch(true)
		{
			case $args[0]=="first":return $class::find_by($args[1]);
			case $args[0]=="all":return $class::find_all_by(array_key_exists(1,$args)?$args[1]:null);
			case is_numeric($args[0]):return $class::find_by("id",(int)$args[0]);
		}
    }
	
	public static function destroy_all()
	{
		$class = get_called_class();
		
		$class::$table_name = pluralize($class);
		
		$sql = "DELETE FROM ".$class::$table_name."";
		return $class::$connection->_delete($sql);
		
	}
    public static function find_all_by($field,$value=null)
    {
        $class = get_called_class();
        return $class::find_all_by_sql($class::build_find_query($field,$value));
    }

    public static function find_by($field,$value)
    {
        $class = get_called_class();
        return $class::find_by_sql($class::build_find_query($field,$value));
    }
    private static function build_find_query($field,$value)
    {
        $class = get_called_class();
		
		$class::$table_name = pluralize($class);
		if(empty($field))
		{
			$sql = "SELECT * FROM ".$class::$table_name;
		}
		else
		{
			
			if(is_array($field)&&empty($value))
			{
				$sql = "SELECT * FROM ".$class::$table_name." WHERE ";
				$sql_fields =array();
				foreach($field as $key=>$value)
				{
				  $sql_fields[] = $key." = ".$value;
				}
				$sql.= implode(" AND ",$sql_fields);
			}
			else
			{
				$value = is_int($value)?$value:"'".$value."'";
				$sql = "SELECT * FROM ".$class::$table_name." WHERE ".$field." = ".$value;
			}
		}
		return $sql;
    }
    public static function find_by_sql($sql)
    {
        $class = get_called_class();
		if($result = $class::$connection->_selectSingle($sql))
        {
            $model = new $class($result);
            return $model;
        }
        return null;
    }
    public static function find_all_by_sql($sql)
    {
        $class     = get_called_class();
        if($result = $class::$connection->_select($sql))
        {
            $collection = array();
            while( $row = mysql_fetch_assoc($result))
            {
                $model = new $class($row);
                $collection[] =  $model;
            }
            return $collection;
        }
        return null;
    }

    function __get($attribute)
    {
       
		if(array_key_exists($attribute, $this->attributes))
        {
          return $this->attributes[$attribute];
        }
        elseif(array_key_exists($attribute, $this->virtual_attributes))
        {
           return $this->virtual_attributes[$attribute];
        }
		elseif(array_key_exists($attribute, $this->method_attributes))
        {
			eval("$"."result = ".$this->method_attributes[$attribute]);
			return  $result;
		}
        else
        {
           
		   if($this->__isset($attribute))
		   {
			  return $this->$attribute;
		   }
		   elseif(in_array($attribute,get_class_methods(get_called_class())))
		   {
			  $this->$attribute();
		   }		   
		   
        }
    }
    function __set($attribute,$value)
    {
    	if(array_key_exists($attribute, $this->attributes))
        {
           $this->attributes[$attribute] = $value;
        }
        elseif(array_key_exists($attribute, $this->virtual_attributes))
        {
           $this->virtual_attributes[$attribute] = $value;
        }
		elseif(array_key_exists($attribute, $this->method_attributes))
        {
            $this->method_attributes[$attribute] = $value;
        }
        else
        {
           
		   $this->$attribute =$value;
        }
    }
	 
    function __isset($attribute)
    {
        if(array_key_exists($attribute, $this->attributes))
        {
            return isset($this->attributes[$attribute]);
        }
        else
        {
            return isset($this->$attribute);
        }
    }
    function build_default($attributes=array())
    {
        $tmp = array();
		
        foreach($this::$attr_accessor as $key=>$attr)
        {
            $this->virtual_attributes[$attr]=array_key_exists($attr,$attributes)?$attributes[$attr]:null;
			$this->method_attributes[$attr]=array_key_exists($attr,$attributes)?$attributes[$attr]:null;
        }
			
        foreach($this->columns as $key=>$attr)
        {
            $tmp[$attr]=array_key_exists($attr,$attributes)?$attributes[$attr]:null;
        }
	
        $this->build($tmp);
    }
    function build($attributes)
    {
        foreach($attributes as $key=>$value)
        {
            if(array_key_exists($key, $this->virtual_attributes))
            {
              $this->virtual_attributes[$key]=$value;
              unset($attributes[$key]);
            }
        }
        $this->attributes = array_merge($this->attributes,$attributes);
        $this->sanitize();
    }
	
	function update_attributes($attributes)
	{
		$this->build($attributes);
		$this->update();
		return $this->valid();
	}
    function update()
    {
        $this->before_validate();
        $this->validate();
        $this->after_validate();
        $this->before_update();
        if($this->valid())
        {
           $this->commit();
        }
        $this->after_update();
    }
    function before_update()
    {
      //stub method
    }
    function before_create()
    {
        //stub method
    }
    function before_validate()
    {
        //stub method
    }
    function validate()
    {
        //stub method
    }
    function after_validate()
    {
        //stub method
    }
    function after_update()
    {
        //stub method
    }
    function after_create()
    {
        //stub method
    }
    function valid()
    {
        return empty($this->error_messages);
    }
    function save()
    {
       
		if($this->new_record())
        {
            $this->commit();
        }
        else
        {
            $this->update();
        }

    }
    function commit()
    {
        $this->sanitize();
        $sql = "";
        if($this->new_record())
        {
            $sql .= "INSERT INTO ".$this->table_name;
        }
        else
        {
            $sql .= "UPDATE ".$this->table_name;
        }
        $sql .= " SET ";
        $values= array();
        foreach($this->attributes as $key=>$value)
        {
            if($key!="id")
            {
                $values[]= $key." = '".$value."'";
            }
        }
        $sql .= implode(", ",$values);
		if(!$this->new_record())
        {
            $sql .= " WHERE id = ".$this->attributes['id'];
            $this::$connection->_update($sql);
        }
        else
        {
			$this->id = $this::$connection->_insert($sql);
			
        }

    }
    function before_destroy()
    {
      //stub method
    }
    function destroy()
    {
       $this->before_destroy();
       if($this->delete())
       {
            $this->after_destroy();
            return true;
       }
       else
       {
          return false;
       }
    }

    function after_destroy()
    {
         //stub method
    }
    function delete()
    {
        if(!$this->new_record())
        {
           $sql = "DELETE FROM ".$this::$table_name." WHERE id = ".$this->id;
           return $this::$connection->_delete($sql);
        }
        return false;
    }
    function new_record()
    {
        return empty($this->id);
    }

    function sanitize()
    {
        foreach($this->attributes as $key=>$a)
        {
            $this->attributes[$key] = $this::$connection->sanitize($a);
        }
    }
    function accepts_attributes_for($model)
    {
       $this->error_fields = array_merge($this->error_fields,$model->error_fields);
       $this->error_messages = array_merge($this->error_messages,$model->error_messages);
    }
    function validates_presence_of($field)
    {
       if(empty($this->attributes[$field]))
       {
        $this->error($field,"cannot be blank");
       }
    }
    function validates_format_of($field,$regex)
    {
       if(!preg_match($regex,$this->attributes[$field]))
       {
         $this->error($field,"format is invalid");
       }
    }
    function validates_length_of($field,$value)
    {
        if(strlen($this->attributes[$field])>$value)
        {
            echo $value;
            $this->error($field,"exceeds maximum length of ".$value);
        }
    }
    function validates_numericality_of($field)
    {
        if(!is_numeric($this->$field))
        {
            $this->error($field," is not a number");
            $this->$field="";
        }
    }
     function validates_acceptance_of($field)
    {
        if($this->$field!=true)
        {
            $this->error($field," must be accepted");
        }
    }
    function error($field,$msg)
    {
        $this->error_fields[] = $field;
        $this->base_error(humanize($field)." ".$msg);
    }
    function base_error($msg)
    {
        $this->error_messages[] =  $msg;
    }

}
?>
