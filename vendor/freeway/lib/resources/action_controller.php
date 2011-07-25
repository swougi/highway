<?

class action_controller extends prototype
{
	var $layout   = "application";
	var $action   = "index";
	var $helpers  = array("application");
	var $rendered = false;
	function __construct($action)
	{
		session_start();
		$this->action = $action;
		$this->session = new session();
		$this->params  = $_REQUEST;
		if(in_array($action,get_class_methods($this)))
		{
			if($this->before_filter())
			{
				$this->$action();		
			}
			$this->after_filter();
		}
		$this->render();
 	}
	function render($args=null)
	{
		
		$args   = func_get_args();
		$view   = array_key_exists(0,$args)?$args[0]:null;
		$view   = empty($view)?$this->action:$view;
        $format = "html";
		$yield  = "";
		
		if($this->rendered==false || $view=="partial")
		{
			
			$this->rendered=true;
			//check for html page in controller action folder
			foreach($this->helpers as $helper)
			{
				require_once(DOCROOT."/app/helpers/".$helper."_helper.php");
			}
			if(empty($view))
			{
				if(file_exists(DOCROOT."/app/views/".str_replace("_controller","",get_class($this))."/".$this->action.".".$format.".php"))
				{
					$yield  = $this->compile(DOCROOT."/app/views/".str_replace("_controller","",get_class($this))."/".$this->action.".".$format.".php");
				}
				else
				{
					$yield  = $this->compile(DOCROOT."/app/views/application/404_error.html.php");
				}
			}
            else
			{
			  	switch($view)
				{
					case "text":
						$yield=$args[0];

					break;
					case "partial":
						
					    $path = explode("/",$args[1]);
						$name = str_replace("_","",array_pop($path));
					    if(array_key_exists('collection',$args[2]))
						{
							foreach((array)$args[2]['collection'] as $resource){
							  
								$path = $path?$path:array("/".str_replace("_controller","",get_class($this)));
								$file_name = DOCROOT."/app/views".implode("/",$path)."/_".$name.".".$format.".php";
								if(file_exists($file_name))
								{
									$yield  .= $this->compile($file_name,array($name=>&$resource));
								}
								else
								{
									$yield  .= $this->compile(DOCROOT."/app/views/application/404_error.html.php");
								}
							}
						}
						else
						{
							$locals = array_key_exists('locals',$args[2])?(array)$args[2]['locals']:array();
							if(file_exists(DOCROOT."/app/views/".str_replace("_controller","",get_class($this))."/_".$name.".".$format.".php"))
							{
								$yield  .= $this->compile(DOCROOT."/app/views/".str_replace("_controller","",get_class($this))."/_".$name.".".$format.".php",$locals);
							}
							else
							{
								$yield  .= $this->compile(DOCROOT."/app/views/application/404_error.html.php");
							}
						}
						
					break;
					default:
					
						//die(DOCROOT."/app/views/".str_replace("_controller","",(get_class($this)))."/".$view.".".$format.".php");
						if(file_exists(DOCROOT."/app/views/".str_replace("_controller","",(get_class($this)))."/".$view.".".$format.".php"))
						{
							$yield  = $this->compile(DOCROOT."/app/views/".str_replace("_controller","",(get_class($this)))."/".$view.".".$format.".php");
						}
						else
						{
							$yield  = $this->compile(DOCROOT."/app/views/application/404_error.html.php");
						}
					
				}
			}
			
			if(file_exists(DOCROOT."/app/views/layouts/".$this->layout.".".$format.".php") && $view!="partial")
			{
			 	include(DOCROOT."/app/views/layouts/".$this->layout.".".$format.".php");
			}
			else
			{
				echo $yield;
			}
			$this->session->clear_flash();
		}
		
		
	}
	function redirect_to($path)
	{
		$this->rendered=true;
		header('Location: '.$path);
		
	}
	private function before_filter()
	{
		return true;
	}	
	private function after_filter()
	{
		return true;
	}	

	private function layout($layout)
	{
		$this->layout=$layout;
	}
	
	private function compile($file,$vars=array())
	{
		extract($vars);
		ob_start();
		require $file;
		return ob_get_clean();
	}
}
?>