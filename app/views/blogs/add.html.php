 <div id='header'>
	<div id="image" class='add'></div>
	<div id='title'>
		<h2>Add new blog</h2>
		<div class='cb'></div>	
		<div id="info"><a href='<?=blogs_path()?>'>back</a></div>
	</div>
	<div class='cb'></div>
</div>

<div id="body">
	<div class='list'>
		<div class='header'>
			<div class='title'>Details</div> 
			<div class='cb'></div>
		</div>
           <?error_messages_for($this->blog)?>
		   <?$f = form_for($this->blog)?>
				<?=$this->render("partial","form",array("locals"=>array("f"=>$f)))?>
		   <?$f->end()?>
	</div>
</div>