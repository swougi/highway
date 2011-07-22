<?
class blog_controller extends application_controller
{
	private function before_filter()
	{
	
	}
	
	function index()
	{
		$this->gardens = Garden::find("all");
	}
		
	function add()
	{
            $this->garden = new Garden();
	}
	function create()
	{
		$this->garden = Garden::create($this->params["garden"]);
		
		if($this->garden->valid())
		{
			  $this->session->set_flash("success","Successfully created garden");
			 $this->redirect_to(gardens_path());
		}
		else
		{
		   $this->session->set_flash("error","Could not create your garden");
		   $this->render("add");
		}
	}
	
	function show()
	{
	    $this->garden = Garden::find($this->params['id']);
	}	
	
	function edit()
	{
		$this->garden = Garden::find($this->params['id']);
	}
	
	function update()
	{
		//die("here");
		$this->garden = Garden::find($this->params['id']);
		if($this->garden->update_attributes($this->params['garden']))
		{
			$this->session->set_flash("success","Successfully updated garden");
			$this->redirect_to(garden_path($this->garden));
		}
		else
		{
		    $this->session->set_flash("error","Could not update your garden");
			$this->render("edit");
		}
		
	}
	
	function search()
	{
		$this->render("text",$this->params["test"]);
	}
	
	function destroyall()
	{
    	Garden::destroy_all();
		$this->redirect_to(gardens_path());
		
	}
	

}
?>