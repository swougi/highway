 <div id='header'>
	<div id="image" class='add'></div>
	<div id='title'>
		<h2>Add new Garden</h2>
		<div class='cb'></div>	
		<div id="info"><a href='<?=gardens_path()?>'>back</a></div>
	</div>
	<div class='cb'></div>
</div>

<div id="body">
	<div class='list'>
		<div class='header'>
			<div class='title'>Details</div> 
			<div class='cb'></div>
		</div>
           <?error_messages_for($this->garden)?>
		   <?$f = form_for($this->garden)?>
				<?=$this->render("partial","form",array("locals"=>array("f"=>$f)))?>
		   <?$f->end()?>
	</div>
</div>