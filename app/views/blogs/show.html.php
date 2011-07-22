<div id='header'>
	<div id="image" class='details'></div>
	<div id='title'>
		<h2><?=$this->garden->name?></h2>
		<div class='cb'></div>	
		<div id="info"><?link_to("back",gardens_path())?> : <?link_to("edit",edit_garden_path($this->garden))?></div>
	</div>
	<div class='cb'></div>
</div>
<div id="body">
	<div class='list'>
		<div class='header'>
			<div class='title'>Timeline</div> 
			<div class='tools'>
				<div class='tool'>
					<div class='label'>Graph :</div>
					<ul>
						<li> <span><?=empty($_REQUEST['graph'])?"line":$_REQUEST['graph'];?></span>
							<ul>
								<li>
									<span><a href='?graph=line'>line</a></span>
								</li>
								<li>
									<span><a href='?graph=album'>album</a></span>
								</li>
								<li>
									<span><a href='?graph=growth'>growth</a></span>
								</li>
							</ul>
						</li>
					</ul>
				</div>
				<div class='tool'>
					<div class='label'></div>
				</div>
			</div>
			<div class='cb'></div>
		</div>
		<div class='item'>
			<table>
				<?foreach($this->garden->attributes as $key=>$value){?>
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