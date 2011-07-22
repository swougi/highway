<?class session{
	
	
	function __construct($asddas)
	{
		$_SESSION['flash'] = array_key_exists('flash',$_SESSION)?$_SESSION['flash']:array();
	}

	function set_flash($key,$value)
	{
		$_SESSION['flash'][$key]=$value;
	}
	function flash($key)
	{
		return empty($_SESSION['flash'][$key])?false:$_SESSION['flash'][$key];
	}
	function clear_flash()
	{
		unset($_SESSION['flash']);
	}
        


}?>