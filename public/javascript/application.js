window.onload= function()
{
	
		var list = document.getElementsByClassName('items');
		for(var i=0;i<list.length;i++)
		{
			list[i].ondrag=function(e)
			{
				this.scrollTop=e.y+e.offSetY;
			}.bind(list[i]);
		}
}