<?
require_once("active_attributes.php");
model::includes(function($class)
{
	
	active_attributes::load($class);
	
});
model::extend(function($class,&$instance)
{
	
	
});
?>