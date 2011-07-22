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
			require_once(DOCROOT."/vendor/freeway/lib/resources/prototype.php");
			foreach(glob(DOCROOT."/vendor/freeway/lib/resources/*.php") as $resource)
			{
				require_once($resource);
			}
			
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
			}
			//DB settings
			$db_settings = parse_ini_file(DOCROOT.'/config/database.ini',TRUE);
			active_record::$connection = new connection($db_settings[ENV]);
			
			//init router			
			$this->router = new Router();

                        //middleware??/


		}
                function register_middleware($args)
                {

                    
                }
		function run()
		{
		    //start router
                    $route = $this->router->run();
				
		}
	}
	$Application = new Application();

?>