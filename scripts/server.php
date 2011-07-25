#!/usr/bin/env php -q
<?

date_default_timezone_set('Africa/Johannesburg');
require_once('script_boot.php');

$server = new highway_http_server();
$server->run_forever();

?>