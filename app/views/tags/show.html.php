<div id='header'>
	<div id="image" class='details'></div>
	<div id='title'>
		<h2><?=$this->tag->name?> (<?=$this->tag->blog->name?>)</h2>
		<div class='cb'></div>	
		<div id="info"><?link_to("back",blog_path($this->tag->blog))?> : <?link_to("edit",edit_blog_tag_path($this->tag))?></div>
	</div>
	<div class='cb'></div>
</div>
<div id="body">
	<div class='list'>
		<div class='header'>
			<div class='title'>Properties</div> 
			<div class='tools'>
				
			</div>
			<div class='cb'></div>
		</div>
		<div class='item'>
			<table>
				
				
				<?foreach($this->tag->attributes as $key=>$value){?>
					<tr>
						<td>
							<label><?= $key?></label>
						</td>
						<td>
							<?= $value?>
						</td>
					</tr>
				<?}?>
			</table>
		</div>
	</div>
</div>