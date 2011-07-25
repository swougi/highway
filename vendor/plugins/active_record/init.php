<?
require_once("active_record.php");
model::extend(function($class,&$instance){
	
		if($class::uses('active_record'))
		{
			$instance->active_record(new active_record($class,$instance));
		}
});
?>