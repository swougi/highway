<?
$Application->router->map(
    Router::match("/","blogs/index"),
	
	
    Router::resources("blogs",
            Router::resources("tags", Router::collection("search","destroyall")),
            Router::collection("search","destroyall")
    )
	
);
?>