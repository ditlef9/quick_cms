<?php
/**
*
* File: _admin/_inc/settings/menu.php
* Version 02:10 28.12.2011
* Copyright (c) 2008-2012 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Language --------------------------------------------------------------------------- */
include("_translations/admin/$l/media/t_common.php");


if($page == "menu"){
	echo"
	<h1>Media</h1>
	<div class=\"vertical\">
		<ul>
			<li><a href=\"index.php?open=$open&amp;editor_language=$editor_language\">Media</a></li>

	";
}


echo"
			<li";if($page == "browse"){echo" class=\"down\"";}echo"><a href=\"./?open=$open&amp;page=browse&amp;editor_language=$editor_language\"";if($page == "browse"){echo" class=\"selected\"";}echo">Browse</a></li>
			<li";if($page == "upload_image"){echo" class=\"down\"";}echo"><a href=\"./?open=$open&amp;page=upload_image&amp;editor_language=$editor_language\"";if($page == "upload_image"){echo" class=\"selected\"";}echo">$l_upload_image</a></li>
			
";

if($page == "menu"){
	echo"
		</ul>
	</div>
	";
}
?>