<?
class router extends prototype
{
	public $routes;
	static function match($path, $route,$method="GET")
	{
            $route 	    = explode("/",$route);
            $controller = $route[0];
            $action     = $route[1];
            return array(array($path=>array("controller"=>$controller."_controller.php","action"=>$action,"method"=>$method)));
	}
	static function resources($resources,$args=array())
	{
		    $args = func_get_args();
			array_shift($args);
							
		    $routes     = array();
			$controller = $resources."_controller.php";
            
            $resource   = singularize($resources);
			$path       = "/".$resources;
            $name       = $resources."_path";
           
			//TODO: replace eval
			eval("function ".$name."(){return \"".$path."\";}");
            foreach(array("index"=>"GET","create"=>"POST") as $action=>$method)
            {
				$routes[] = array($path=>array("controller"=>$controller,"action"=>$action,"resource"=>$resources,"method"=>$method,"name"=>$name));
            }
			
			//!NEW... NOVUS,NEOS,NEU...add...ADD!!
            foreach(array("add"=>"GET") as $action=>$method)
            {
				$path = "/".implode("/",array($resource,$action));
				$name = implode("_",array($action,$resource,"path"));
				//TODO: replace eval
				eval("function ".$name."(){return \"".$path."\";}");
				$routes[] = array($path=>array("controller"=>$controller,"action"=>$action,"resource"=>$resources,"method"=>$method,"name"=>$name));
            }

            //set singular paths
            foreach(array("update"=>"POST","show"=>"GET","edit"=>"GET") as $action=>$method)
            {
				//TODO: add REST
				if( $action=="show")
				{
					$path = "/".implode("/",array($resource,":id"));
				}
				else{$path = "/".implode("/",array($resource,$action,":id"));}
                
				$name = ($action=="show"?"":$action."_").$resource."_path";
				$routes[] = array($path=>array("controller"=>$controller,"action"=>$action,"resource"=>$resource,"method"=>$method,"name"=>$name));
				//TODO: replace eval
				eval("function ".$name."("."$"."".$resource."){return str_replace(':id',$".$resource."->id,'".$path."');}");
            }
			//set nested resources
			//TODO : clean this shit up
			if(!empty($args))
			{
				
				foreach($args as $collections)
				{
					foreach($collections as $key=>$paths)
					{
						
						foreach($paths as $path=>$collection)
						{
							if($key==="collection")
							{
								$path =  "/".implode("/",array($resources,$collection));
								$name = implode("_",array($collection,$resources,"path"));
								$routes[] = array($path=>array("controller"=>$controller,"type"=>"collection","action"=>$collection,"method"=>"GET","name"=>$name));
								//TODO: replace eval
								eval("function ".$name."(){return \"".$path."\";}");
							}
							else
							{
								$prefix = in_array($collection['action'],array("index","show"))?"":$collection['action']."_";
								$collection['name'] = $prefix.$resource."_".str_replace($collection['action']."_","",$collection['name']);
								$path = "/".$resources."/:".$resource."_id".$path;
								
								switch ($collection['action'])
								{
									case 'add' : $eval_key = "->id";break;
								
									case 'create' : $eval_key = "['".singularize($resources)."_id']";break;
									default:$eval_key ="->".singularize($resources)."_id";
									
								}
											
								if(in_array($collection['action'],array("show","edit","update")))
								{
									$eval_params = "$".$collection['resource']."";
									$eval_key    =   "->".singularize($resources)."_id";
									$eval_func   =  "str_replace(':id',$".singularize($collection['resource'])."->id,'".$path."')";
									$eval_func   =  "return str_replace(':".singularize($resources)."_id',$".singularize($collection['resource']).$eval_key.",".$eval_func.");";
								}
								else
								{
								    if(key_exists("type",$collection))
									{
										$eval_key  =   "->id";	
									}
									$eval_func =  "return str_replace(':".singularize($resources)."_id',$".singularize($resources).$eval_key.",'".$path."');";
									$eval_params = "$"."".singularize($resources);
									
								}
								$eval = ("function ".$collection['name']."(".$eval_params."){".$eval_func."}");
								$collection['eval'] = $eval;
								eval($eval);
								$routes[] = array($path=>$collection);
							}
						}
					}
				}
			}
			return $routes;
	}
	function member($args)
	{
		//TODO: Add
	}
	static function collection($collection)
	{
            return array("collection"=>func_get_args());
	}
	function __construct()
	{
            //feels like i need to pass something in here
	}	
	function map($mapping)
	{
            //TODO: improve this stupidy loopidy
            $this->routes = array();
            foreach(func_get_args() as $routes)
            {
               foreach((array)$routes as $path=>$route)
               {
                   foreach((array)$route as $key=>$value)
                   {
                         $value['params'] = array();
						 //TODO: add control characters
						 if(preg_match_all('/\/?(:[a-zA-Z0-9\_]+)\/?/',$key,$match))
                         {
                                $regex = $key;
								$match = $match[1];
								if(is_array($match))
								{
									foreach($match as $matched_param)
									{
										$value['params'][]=$matched_param;
										$regex = str_replace($matched_param,'([a-zA-Z0-9]+)',$regex);
									}
								}
								else{$regex = str_replace($match,'\/?([a-zA-Z0-9]+)\/?/',$regex);}
								$value['regex'] = "/^".$value['method']." ".slash_regex($regex)."$/";
                         }
                         else
                         {
                                $value['regex'] = "/^".$value['method']." ".slash_regex($key)."$/";
                         }
                         $this->routes[$value['method']." ".$key] = $value;
                    }
               }
            }
            krsort($this->routes);
            //raise($this->routes,true);
	}
	
	function route_for($query,$method="GET")
	{
            $query = $method." ".$query;
			//TODO: improve another stupidy loopidy
        	foreach($this->routes as $path=>$route)
            {
            	if(preg_match_all($route['regex'],$query,$match) && $route['method']==$method)
                {
					foreach($route["params"] as $key=> $param)
					{
						$_REQUEST[str_replace(":","",$param)] =is_array($match[$key+1])?$match[$key+1][0]:$match[$key+1];
					}
					return $route;
                }
            }
	}
	
	function run()
	{
            //TODO: refactor into using a path_for of class method type that can be used for link_to etc
			//DONE
			
            $base = explode("/",$_SERVER['PHP_SELF']);
            array_pop($base);
            $path = preg_replace('/.\/$/',"",preg_replace('/\?.*$/', '', str_replace(implode("/",$base),"",$_SERVER['REQUEST_URI'])));
            return $this->route = $this->route_for($path,$_SERVER['REQUEST_METHOD']);
			
			
	}
}

?>