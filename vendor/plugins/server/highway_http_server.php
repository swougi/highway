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
        
		if(file_exists($docroot.$uri)) {
			return $this->get_static_response($request, "$doc_root$uri");
		} else {
            return $this->get_php_response($request, "$doc_root$uri");			
		}
    }        
}

?>