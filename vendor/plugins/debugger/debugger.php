<?
	function raise($args,$die=false)
	{
		echo "<pre style='width:100%;opacity:0.4;'>";
			var_dump($args);
		echo "</pre>";
		if($die){die();}
	}
	
	function debug($args)
	{
		return "<pre style='padding:30px;'>".print_r($args,true)."</pre>";
	}
?>