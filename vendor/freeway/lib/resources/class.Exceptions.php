<?php

class SQLException extends Exception 
{
	function __construct($message)
	{
		parent::__construct('SQL Exception thrown : '.$message);
	}
	
	function __destruct(){}
}

?>