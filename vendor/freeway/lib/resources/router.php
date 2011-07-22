<?
class router
{
	public $routes;
	static function match($path, $route,$method="GET")
	{
            $route 	    = explode("/",$route);
            $controller = $route[0];
            $action     = $route[1];
            return array(array($path=>array("controller"=>$controller."_controller.php","action"=>$action,"method"=>$method)));
	}
	static function resources($resources,$options=array())
	{
            $routes     = array();
			$controller = $resources."_controller.php";
            $resource   = singularize($resources);
            
			$path       = "/".$resources;
            $name       = $resources."_path";
            
			eval("function ".$name."(){return \"".$path."\";}");
            foreach(array("index"=>"GET","create"=>"POST") as $action=>$method)
            {
				$routes[] = array($path=>array("controller"=>$controller,"action"=>$action,"resource"=>$resources,"method"=>$method,"name"=>$name));
            }
			
			//!NEW... NOVUS,NEOS,NEU...add...ADD!!
            foreach(array("add"=>"GET") as $action=>$method)
            {
				$path = "/".implode("/",array($action,$resource));
				$name = implode("_",array($action,$resource,"path"));
				eval("function ".$name."(){return \"".$path."\";}");
				$routes[] = array($path=>array("controller"=>$controller,"action"=>$action,"resource"=>$resources,"method"=>$method,"name"=>$name));
            }

            //set singular paths
            foreach(array("update"=>"POST","show"=>"GET","edit"=>"GET") as $action=>$method)
            {
				//TODO:add REST
                    
				$path = "/".implode("/",array($resource,$action,":id"));
				$name = ($action=="show"?"":$action."_").$resource."_path";
				$routes[] = array($path=>array("controller"=>$controller,"action"=>$action,"resource"=>$resources,"method"=>$method,"name"=>$name));
				eval("function ".$name."("."$"."".$resource."){return str_replace(':id',$".$resource."->id,'".$path."');}");
            }

            //set nested resources
            if(array_key_exists('collection',$options))
            {
				foreach($options['collection'] as $collection)
				{
						$path =  "/".implode("/",array($resources,$collection));
						$name = implode("_",array($collection,$resources,"path"));
						$routes[] = array($path=>array("controller"=>$controller,"action"=>$collection,"method"=>"GET","name"=>$name));
						eval("function ".$name."(){return \"".$path."\";}");
				}
            }
            return $routes;
	}
	function member($args)
	{

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
						 if(preg_match_all('/\/?(:[a-zA-Z0-9]+)\/?/',$key,$match))
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
			//die($query);
			//raise
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
            $base = explode("/",$_SERVER['PHP_SELF']);
            array_pop($base);
            $path = preg_replace('/.\/$/',"",preg_replace('/\?.*$/', '', str_replace(implode("/",$base),"",$_SERVER['REQUEST_URI'])));
            $this->route = $this->route_for($path,$_SERVER['REQUEST_METHOD']);
			if($this->route)
            {
                    require_once(DOCROOT."/app/controllers/application_controller.php");
                    require_once(DOCROOT."/app/controllers/".$this->route["controller"]);
                    $controller_name = str_replace(".php","",$this->route["controller"]);
                    new $controller_name($this->route['action']);
            }
            else
            {	
                    
					require_once(DOCROOT."/app/controllers/application_controller.php");
                    new application_controller("404_error");
            }
	}
}

?>