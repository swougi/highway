<div id='header'>
	<div id="image" class='list'></div>
	<div id='title'>
		<h2>My Blogs</h2>
		<div class='cb'></div>	
		<div id="info"><a href='<?=add_blog_path()?>'>add</a> <b>:</b> <a href='<?=destroyall_blogs_path()?>'>destroy all</a></div>
	</div>
	<div class='cb'></div>
</div>
<div id="body">
	<div class='list'>
		<div class='header'>
			<div class='title'>index</div>
			<div class='tools'>
				
			</div>
			<div class='cb'></div>
		</div>
		<div class='items'>
			<?=$this->render("partial","blog",array("collection"=>$this->blogs))?>
		</div>		
	</div>
</div>