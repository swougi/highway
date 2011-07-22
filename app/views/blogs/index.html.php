<div id='header'>
	<div id="image" class='list'></div>
	<div id='title'>
		<h2>My Garden</h2>
		<div class='cb'></div>	
		<div id="info"><a href='<?=add_garden_path()?>'>add</a> <b>:</b> <a href='<?=destroyall_gardens_path()?>'>destroy all</a></div>
	</div>
	<div class='cb'></div>
</div>
<div id="body">
	<div class='list'>
		<div class='header'>
			<div class='title'>Collection</div> 
			<div class='tools'>
				<div class='tool'>
					<div class='label'>Sort by :</div>
					<ul>
						<li> <span><?=empty($_REQUEST['sort_by'])?"ranked":$_REQUEST['sort_by'];?></span>
							<ul>
								<li>
									<span><a href='<?=search_gardens_path()?>'>ranked</a></span>
								</li>
								<li>
									<span><a href='?sort_by=health'>health</a></span>
								</li>
								<li>
									<span><a href='?sort_by=last_updated'>last_updated</a></span>
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
			<div class='cb'></div>
		</div>
		<div class='items'>
			<?=$this->render("partial","garden",array("collection"=>$this->gardens))?>
		</div>		
	</div>
</div>