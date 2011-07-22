<?php
/**
 * User model
 *
 * @author Martin
 */

class blog extends active_record
{
    
    function active_attributes($fields)
    {
        $fields->map(array("id"       =>array('type' =>'int','key'=>'primary','length'=>"40"),
                           "name"     =>array('type' =>'varchar','length'=>"255"),
                           "body"     =>array('type' =>'varchar','length'=>"255")));
    }
	   
    function before_create()
    {
   
    }
    
    function validate()
    {
        $this->validates_presence_of("name");
    }
   
}
?>
