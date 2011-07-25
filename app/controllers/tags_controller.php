<?
class tags_controller extends application_controller
{
	private function before_filter()
	{
	
	}
	
	function index()
	{
		$this->tags = tag::find("all");
	}
		
	function add()
	{
            $this->tag = new tag(array("blog_id"=>$this->params['blog_id']));
	}
	
	function create()
	{
		$this->tag = tag::create($this->params["tag"]);
		if($this->tag->valid())
		{
			 $this->session->set_flash("success","Successfully created tag");
			 $this->redirect_to(blog_path($this->tag->blog));
		}
		else
		{
		   $this->session->set_flash("error","Could not create your tag");
		   $this->render("add");
		}
	}
	
	function show()
	{
	    $this->tag = tag::find($this->params['id']);
	}	
	
	function edit()
	{
		$this->tag = tag::find($this->params['id']);
	}
	
	function update()
	{
		
		$this->tag = tag::find($this->params['id']);
		if($this->tag->update_attributes($this->params['tag']))
		{
			$this->session->set_flash("success","Successfully updated tag");
			$this->redirect_to(blog_path($this->tag->blog));
		}
		else
		{
		    $this->session->set_flash("error","Could not update your tag");
			$this->render("edit");
		}
		
	}
	
	function search()
	{
		$this->render("text",$this->params["test"]);
	}
	
	function destroyall()
	{
                tag::destroy_all();
		$this->redirect_to(blog_path(array('id'=>$this->params['blog_id'])));
		
	}
	

}
?>