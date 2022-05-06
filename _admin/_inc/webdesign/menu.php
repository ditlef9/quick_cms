<?php
/**
*
* File: _admin/_inc/sosial_media/menu.php
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


if($page == "menu"){
	echo"
	<h1>Webdesign</h1>
	<div class=\"vertical\">
		<ul>
			<li><a href=\"index.php?open=$open&amp;editor_language=$editor_language\">Webdesign</a></li>

	";
}


echo"
			<li";if($page == "webdesigns"){echo" class=\"down\"";}echo"><a href=\"./?open=$open&amp;page=webdesigns&amp;editor_language=$editor_language&amp;l=$l\"";if($page == "webdesigns"){echo" class=\"selected\"";}echo">Webdesigns</a></li>
			<li";if($page == "front_page"){echo" class=\"down\"";}echo"><a href=\"index.php?open=$open&amp;page=front_page&amp;editor_language=$editor_language&amp;l=$l\"";if($page == "front_page"){echo" class=\"selected\"";}echo">Front page</a></li>
			<li";if($page == "social_media"){echo" class=\"down\"";}echo"><a href=\"./?open=$open&amp;page=social_media&amp;editor_language=$editor_language&amp;l=$l\"";if($page == "social_media"){echo" class=\"selected\"";}echo">Social media</a></li>
			<li";if($page == "slides"){echo" class=\"down\"";}echo"><a href=\"index.php?open=$open&amp;page=slides&amp;editor_language=$editor_language&amp;l=$l\"";if($page == "slides"){echo" class=\"selected\"";}echo">Slides</a></li>
			<li";if($page == "favicon"){echo" class=\"down\"";}echo"><a href=\"index.php?open=$open&amp;page=favicon&amp;editor_language=$editor_language&amp;l=$l\"";if($page == "favicon"){echo" class=\"selected\"";}echo">Favicon</a></li>
			<li";if($page == "logo"){echo" class=\"down\"";}echo"><a href=\"index.php?open=$open&amp;page=logo&amp;editor_language=$editor_language&amp;l=$l\"";if($page == "logo"){echo" class=\"selected\"";}echo">Logo</a></li>
			<li";if($page == "share_buttons"){echo" class=\"down\"";}echo"><a href=\"index.php?open=$open&amp;page=share_buttons&amp;editor_language=$editor_language&amp;l=$l\"";if($page == "share_buttons"){echo" class=\"selected\"";}echo">Share buttons</a></li>
			<li";if($page == "footer"){echo" class=\"down\"";}echo"><a href=\"index.php?open=$open&amp;page=footer&amp;editor_language=$editor_language&amp;l=$l\"";if($page == "footer"){echo" class=\"selected\"";}echo">Footer</a></li>
			<li";if($page == "grids"){echo" class=\"down\"";}echo"><a href=\"index.php?open=$open&amp;page=grids&amp;editor_language=$editor_language&amp;l=$l\"";if($page == "grids"){echo" class=\"selected\"";}echo">Grids</a></li>
			
";

if($page == "menu"){
	echo"
		</ul>
	</div>
	";
}
?>