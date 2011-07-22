<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<head>
		<title>webponics - share your garden</title>
		<?=link_stylesheet("base","errors")?>
		<?=include_javascript("application")?>
	<head>
	<body>
		<div id='application'>
			<div id='header'>
				<div id='logo'>
				</div>
			</div>
			<div id='body'>
				<div id='navigation'>
					<div id='item'><a href="/gardens">My Garden<div class='brown-arrow'></div></a></div>
					<div id='item'><a href="/friends">Friend's Garden<div class='brown-arrow'></div></a></div>
				</div>
				<div id='page'>
					 <?if($this->session->flash("error")){?>
						<div id='flash-error'>
							<?=$this->session->flash("error")?>
						</div>
					 <?}?>
					<?=$yield?>
				</div>
				<div id='footer'>
					powered by <a href='freewayphp.com'>FreeWay</a>
				</div>
			</div>
			<div class='cb'></div>
		</div>
	</body>
</html>
