<div id='header'>
	<div id="image" class='details'></div>
	<div id='title'>
		<h2><?=$this->blog->name?></h2>
		<div class='cb'></div>	
		<div id="info"><?link_to("back",blogs_path())?> : <?link_to("edit",edit_blog_path($this->blog))?></div>
	</div>
	<div class='cb'></div>
</div>
<div id="body">
	<div class='list'>
		<div class='header'>
			<div class='title'>show</div>
			<div class='tools'>
				
			</div>
			<div class='cb'></div>
		</div>
		<div class='item'>
			<table>
				<?foreach($this->blog->attributes as $key=>$value){?>
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