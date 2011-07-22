<?
class blogs_controller extends application_controller
{
	private function before_filter()
	{
	
	}
	
	function index()
	{
		$this->blogs = Blog::find("all");
	}
		
	function add()
	{
            $this->blog = new Blog();
	}
	function create()
	{
		$this->blog = Blog::create($this->params["blog"]);
		
		if($this->blog->valid())
		{
			  $this->session->set_flash("success","Successfully created blog");
			 $this->redirect_to(blogs_path());
		}
		else
		{
		   $this->session->set_flash("error","Could not create your blog");
		   $this->render("add");
		}
	}
	
	function show()
	{
	    $this->blog = Blog::find($this->params['id']);
	}	
	
	function edit()
	{
		$this->blog = Blog::find($this->params['id']);
	}
	
	function update()
	{
		//die("here");
		$this->blog = Blog::find($this->params['id']);
		if($this->blog->update_attributes($this->params['blog']))
		{
			$this->session->set_flash("success","Successfully updated blog");
			$this->redirect_to(blog_path($this->blog));
		}
		else
		{
		    $this->session->set_flash("error","Could not update your blog");
                    $this->render("edit");
		}
		
	}
	
	function search()
	{
		$this->render("text",$this->params["test"]);
	}
	
	function destroyall()
	{
            Blog::destroy_all();
            $this->redirect_to(blogs_path());
		
	}
	

}
?>