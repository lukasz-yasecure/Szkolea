jQuery(document).ready(
function()
{
	jQuery("#sugestia").mouseenter(function()
	{
		jQuery(this).stop().animate({right: 0}, "normal");
	}).mouseleave(function()
	{
		jQuery(this).stop().animate({right: -225}, "normal");
	});;
	
});
	