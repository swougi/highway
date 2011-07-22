<?
$Application->router->map(
    Router::match("/","blogs/index"),
	
	
    Router::resources("blogs",
            Router::collection("search","destroyall")
    )
	
);
?>