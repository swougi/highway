<?
	class application_controller extends action_controller
	{
		var $before_filter = array("logged_in"=>array("except"=>"index"));
	}
?>