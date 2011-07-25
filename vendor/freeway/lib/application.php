<?
	class Application
	{
		function __construct()
		{
			$this->boot();
		}
		function boot()
		{
			
			//load resources
			require_once(DOCROOT."/vendor/freeway/lib/resources/prototype.readonly.php");
			foreach(glob(DOCROOT."/vendor/freeway/lib/resources/*.php") as $resource)
			{
				require_once($resource);
				if(preg_match("/readonly/",$resource)){continue;}
				$resource = str_replace(DOCROOT."/vendor/freeway/lib/resources/","",str_replace(".php","",$resource));
				//not sure
                                $resource::load();
				
			}
			
			//DB settings
			$db_settings = parse_ini_file(DOCROOT.'/config/database.ini',TRUE);
			model::$connection = new connection($db_settings[ENV]);
			
			foreach(glob(DOCROOT."/vendor/plugins/*") as $plugin_folder)
			{
				if(file_exists($plugin_folder."/init.php"))
				{
					require_once($plugin_folder."/init.php");
				}				
			}
			
			
			//preload models
			foreach(glob(DOCROOT."/app/models/*.php") as $model) 
			{
				require_once($model);
				$model_name = str_replace(DOCROOT."/app/models/","",str_replace(".php","",$model));
				$model_name::load();
			}
			
			
			//init router			
			$this->router = new Router();
			
			//init middleware
			$this->middleware =  new MiddleWare();	
			
			
		}
		function run()
		{
		    //start router
			if($this->route = $this->router->run())
            {
                    require_once(DOCROOT."/app/controllers/application_controller.php");
                    require_once(DOCROOT."/app/controllers/".$this->route["controller"]);
                    $controller_name = str_replace(".php","",$this->route["controller"]);
					$controller_name::load();
                    new $controller_name($this->route['action']);
            }
            else
            {	
                  
					require_once(DOCROOT."/app/controllers/application_controller.php");
					application_controller::load();
                    new application_controller("404_error");
            }
			
		}
	}
	$Application = new Application();

?>