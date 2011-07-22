<?
define('DOCROOT', str_replace(basename(""), '', realpath(""))."/..");
define('PUBLIC_PATH',realpath($_SERVER['PHP_SELF'])."");
require_once(DOCROOT.'/vendor/freeway/init.php');
?>
