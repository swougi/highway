<?

require_once DOCROOT . '/vendor/3rdparty/httpserver/httpserver.php';

class highway_http_server extends HTTPServer
{
    function __construct()
    {
        parent::__construct(array(
            'port' => 3000,
        ));
    }

    function route_request($request)
    {
        $uri = $request->uri;
        
        $doc_root = DOCROOT . '/public';
        
		echo "URI: $uri\n";
		if($uri != '/' && file_exists($doc_root.$uri)) {
			echo "returning a file\n";
			return $this->get_static_response($request, "$doc_root$uri");
		} else {
			echo "doing routing\n";
            return $this->get_php_response($request, "$doc_root/index.php");			
		}
    }        
}

?>