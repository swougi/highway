<?class prototype
{
	public static $extensions = array();
	function __construct()
	{
            $class = get_called_class();
            //TODO - array walk, array map?
            foreach($class::$extensions as $extension)
            {
               call_user_func_array($extension,array($class,&$this));
            }
	}
	public static function extend($method)
	{
            $class = get_called_class();
            $class::$extensions[]=$method;
	}
	public static function uses($method)
	{
            return in_array($method,get_class_methods(get_called_class()));
	}
}
?>