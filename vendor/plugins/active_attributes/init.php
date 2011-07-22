<?
require_once("active_attributes.php");
active_record::extend(function($class,&$instance)
{
    if($class::uses('active_attributes'))
    {
            $instance->active_attributes(new active_attributes($class,$instance));
    }
});
?>