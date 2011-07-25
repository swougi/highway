<?php
/**
 * User model
 *
 * @author Martin
 */

class tag extends model
{
    
    public static $columns =array();
    function active_record($record)
    {
            $record->belongs_to("blog");

    }
    function active_attributes($fields)
    {
        $fields->map(array("id"       =>array('type' =>'int','key'=>'primary','length'=>"40"),
                           "name"     =>array('type' =>'varchar','length'=>"255"),
                           "blog_id"  =>array('type' =>'int','length'=>"40"),
                           ));
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
