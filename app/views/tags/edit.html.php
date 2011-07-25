<div id='header'>
	<div id="image" class='edit'></div>
	<div id='title'>
		<h2><?=empty($this->tag->name)?"<i>Please add a name</i>":$this->tag->name?></h2>
		<div class='cb'></div>	
		<div id="info"><?link_to("back",blog_path($this->tag->blog))?></div>
	</div>
	<div class='cb'></div>
</div>
<div id="body">
	<div class='list'>
	    <?error_messages_for($this->tag)?>
		<?$f = form_for($this->tag,array('url'=>update_blog_tag_path($this->tag)))?>
				<?=$this->render("partial","form",array("locals"=>array("f"=>$f)))?>
	    <?$f->end()?>
	</div>
</div>