<?php
/**
 * User model
 *
 * @author Martin
 */

class garden extends active_record
{
    
    function active_attributes($fields)
    {
        $fields->map(array("id"       =>array('type' =>'int','key'=>'primary','length'=>"40"),
                           "name"     =>array('type' =>'varchar','length'=>"255"),
                           "family"   =>array('type' =>'varchar','length'=>"255"),
                           "species"  =>array('type' =>'varchar','length'=>"255"),
                           "type"     =>array('type' =>'varchar','length'=>"255"),
                           "details"  =>array('type' =>'varchar','length'=>"255")));
    }
	   
    function before_create()
    {
   
    }
    
    function validate()
    {
        $this->validates_presence_of("name");
    }
    function activate()
    {
        $this->attributes['status']  = 1;
        $this->update();
    }
}
?>
