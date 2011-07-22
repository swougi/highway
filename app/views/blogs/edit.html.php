<div id='header'>
	<div id="image" class='edit'></div>
	<div id='title'>
		<h2><?=empty($this->garden->name)?"<i>Please add a name</i>":$this->garden->name?></h2>
		<div class='cb'></div>	
		<div id="info"><?link_to("back",garden_path($this->garden))?></div>
	</div>
	<div class='cb'></div>
</div>
<div id="body">
	<div class='list'>
	    <?error_messages_for($this->garden)?>
		<?$f = form_for($this->garden)?>
				<?=$this->render("partial","form",array("locals"=>array("f"=>$f)))?>
	    <?$f->end()?>
	</div>
</div>