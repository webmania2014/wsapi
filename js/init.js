function show_loading( bShow )
{
	jQuery("#sLoading").css( "width", jQuery(document).width() );
	jQuery("#sLoading").css( "height", jQuery(document).height() );
	
	$img		= jQuery("#sLoading img");

	$w = $h = 100;
	$img.css( { "width": $w + "px" }, { "height": $h + "px" } );

	jQuery("#sLoading img").css( "left", jQuery(window).width() / 2 - $w / 2 );
	jQuery("#sLoading img").css( "top", jQuery(window).height() / 2 - $h / 2 + window.scrollY );

	if ( bShow )
	{
		jQuery("#sLoading").css( "display", "inline" );
	}
	else
	{
		jQuery("#sLoading").css( "display", "none" );
	}
}

function post_form ( str_action, formname ) 
{
	var form = document.getElementById( formname );
	form.action = str_action;
	form.submit();
}
