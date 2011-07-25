#!/usr/bin/env php -q
<?

require_once('script_boot.php');

$server = new highway_http_server();
$server->run_forever();

?>