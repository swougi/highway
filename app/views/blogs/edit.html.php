<div id='header'>
	<div id="image" class='edit'></div>
	<div id='title'>
		<h2><?=empty($this->blog->name)?"<i>Please add a name</i>":$this->blog->name?></h2>
		<div class='cb'></div>	
		<div id="info"><?link_to("back",blog_path($this->blog))?></div>
	</div>
	<div class='cb'></div>
</div>
<div id="body">
	<div class='list'>
            <div class='header'>
                    <div class='title'>edit</div>
                    <div class='tools'>

                    </div>
                    <div class='cb'></div>
            </div>
	    <?error_messages_for($this->blog)?>
		<?$f = form_for($this->blog)?>
				<?=$this->render("partial","form",array("locals"=>array("f"=>$f)))?>
	    <?$f->end()?>
	</div>
</div>