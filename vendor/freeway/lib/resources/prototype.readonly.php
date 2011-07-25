<?class prototype
{
	public static $extensions = array();
	public static $includes   = array();
	function __construct()
	{
		$class = get_called_class();
		//TODO - array walk, array map?
		foreach($class::$extensions as $extension){
			call_user_func_array($extension,array($class,&$this));
		}
	}
	//TODO: which is|si hcihw
	public static function extend($method)
	{
		$class = get_called_class();
		$class::$extensions[]=$method;
	}
	
	//might have accidentally fell upon middleware concept
	// plugin for application
	public static function includes($method)
	{		
		$class = get_called_class();
		$class::$includes[]=$method;
	}
	
	public static function load()
	{
		$class = get_called_class();
		foreach($class::$includes as $include){
			call_user_func_array($include,array($class));
		}
	}
	
	public static function uses($method)
	{
		return in_array($method,get_class_methods(get_called_class()));
	}
	
	
}
?>