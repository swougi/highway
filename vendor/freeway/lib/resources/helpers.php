<?
   class helpers extends prototype
   {
   
   }
   
   //static load internal helpers
   function slash_regex($s)
   {
		$s = str_replace("/","\/",$s);
		$s = str_replace("_","\_",$s);
		return $s;
   }
   
   function humanize($s)
   {
	  return (str_replace("_"," ",$s));
   }
   
   function pluralize($s)
   {
	 //TODO: regex this
	 return (preg_match("/.s$/",$s))?preg_replace("/.s$/","ses",$s):$s."s";		
   }
   
   function singularize($s)
   {
	//TODO: regex this
	return (preg_match("/.ses$/",$s))?preg_replace("/.ses$/","s",$s):$s = preg_replace("/s$/","",$s);		
   }
   
   function link_stylesheet($args)
   {
      foreach(func_get_args() as $css)
	  {
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\""."/stylesheets/".$css.".css\"/>";
	  }
   }
   
   function include_javascript($args)
   {
	  foreach(func_get_args() as $js)
	  {
		echo "<script type=\"text/javascript\" src=\""."/javascript/".$js.".js\"></script>";
	  }
   }
   function link_to($name,$path,$option=array())
   {
	 echo "<a href=".$path.">".$name."</a>";
   }
   function form_for($resource,$options=array())
   {
      class form_for
	  {
		
		function __construct($resource,$options=array())
		{
			$this->resource   = $resource;
			$this->options    = $options;
			$this->name = get_class($resource);
			if(array_key_exists('url',$options))
			{
				$this->action = $options['url'];
			}
			else
			{
				//TODO:: getting lazy now - remove this 
				eval("$"."this->action=".($resource->new_record()?pluralize($this->name):"update_".$this->name)."_path($"."resource);");
			}
			$this->name = get_class($resource);
			$this->open();
		}
		function open()
		{
			echo "<form action='".$this->action."' method='POST'>";
			if(array_key_exists('active_form',$this->options))
			{
				$this->build_form();
			}
		}
		function build_form()
		{
			foreach($this->resource->attributes as $field=>$value)
			{
				echo $this->label_for($field);
				echo "<div class='field'>";
				if(array_key_exists($field,(array)$this->resource->belongs_to))
				{
					$model = $this->resource->belongs_to[$field];
					$this->select($field,$model::find('all'),array('selected'=>$value));	
				}
				else
				{
					echo $this->text_field($field);
				}
				echo "</div>";
				echo "<div class='cb'></div>";
				
			}
		}
		function options_from_collection($collection,$args=array())
		{
			echo $this->options_for(array_map(function($resource){return array($resource->id=>$resource->name);},$collection),$args);
		}
		function select($field,$options=array(),$args=array())
		{
			echo "<select name='".$this->name."[".$field."]' >";
			echo  $this->options_from_collection($options,$args);
			echo "</select>";
		}
		
		function options_for($options=array(),$args=array())
		{
		 	array_key_exists('selected',$args)?"":$args['selected']="";
			foreach($options as $key=>$option)
			{
				foreach($option as $value=>$title)
				{
					echo "<option ".($args['selected']==$value?"selected='selected'":"")." value='".$value."'>".$title."</option>";
				}
			}
		}
		
		function label_for($field,$text="")
		{
			
			echo "<label>".$field."</label>";
		}
		function text_field($field)
		{
			$error_field = in_array($field,$this->resource->error_fields)?"class='error'":"";
			echo "<input name='".$this->name."[".$field."]' type='text' value='".$this->resource->$field."' ".$error_field."/>";
		}
		function end()
		{
			echo "</form>";
		}
	  }
	  return new form_for($resource,$options);
   }
   function error_messages_for($resource)
   {
		if(isset($resource)&&!empty($resource->error_messages))
		{
			echo "<div id='error-messages'>";
			echo "<ul>";
			foreach($resource->error_messages as $error)
			{
				echo "<li>".$error."</li>";
			}
			echo "</ul>";
			echo "</div>";
			
		}
   }
  
?>