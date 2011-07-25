<?
class active_record
{
	public static $preloaded = array();
	function __construct($active_record,&$model)
	{
		$this->model			 = $model;
		$this->model->belongs_to = array();
		$this->model->has_many	 = array();
		return $this;
	}
			
	function belongs_to($args)
	{
		$args 		= func_get_args($args);
		$belongs_to = $args[0];
		$key        = $belongs_to."_id";
		$this->model->belongs_to[$key]				  = $belongs_to;
		$this->model->method_attributes[$belongs_to]  = "";
		
		array_key_exists($belongs_to ,active_record::$preloaded)?"":active_record::$preloaded[$belongs_to]=array();
		array_key_exists($belongs_to."_id",active_record::$preloaded[$belongs_to])?"":active_record::$preloaded[$belongs_to][$belongs_to."_id"]=array();
		if(!array_key_exists($this->model->attributes[$key],active_record::$preloaded[$belongs_to][$belongs_to."_id"]))
		{
			active_record::$preloaded[$belongs_to][$belongs_to."_id"][$this->model->attributes[$key]] =  $belongs_to."::find(".$this->model->$key.");";
		}
		//TODO : remove eval set as closure object and set model var
		$this->model->$belongs_to    = active_record::$preloaded[$belongs_to][$belongs_to."_id"][$this->model->attributes[$key]];
	}
	
	function has_many($args)
	{
		
		$args 		= func_get_args($args);
		$has_many   = singularize($args[0]);
		$key        = $has_many."_id";
		$this->model->has_many[$key]				  = $has_many;
		$this->model->method_attributes[$args[0]]    = "";
		
		array_key_exists($has_many,active_record::$preloaded)?"":active_record::$preloaded[$has_many]=array();
		array_key_exists($this->model->class_name."_id",active_record::$preloaded[$has_many])?"":active_record::$preloaded[$has_many][$this->model->class_name."_id"]=array();
		
		if(!array_key_exists($this->model->id,active_record::$preloaded[$has_many][$this->model->class_name."_id"]))
		{
			active_record::$preloaded[$has_many][$this->model->class_name."_id"][$this->model->id] = $has_many."::find_all_by(\"".$this->model->class_name."_id\",".$this->model->id.");";
		}
		//TODO : remove eval set as closure object and set model var
		$this->model->$args[0]     = active_record::$preloaded[$has_many][$this->model->class_name."_id"][$this->model->id];
		
	}
	
	function has_one($args)
	{
	
	}
	
	
	
}
?>