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
include("_translations/admin/$l/pages/t_common.php");


if($page == "menu"){
	echo"
	<h1>Pages</h1>
	<div class=\"vertical\">
		<ul>
			<li><a href=\"index.php?open=$open&amp;editor_language=$editor_language\">Pages</a></li>

	";
}
echo"
			<li";if($page == "overview"){echo" class=\"down\"";}echo"><a href=\"./?open=pages&amp;page=overview&amp;editor_language=$editor_language\"";if($page == "new_page"){echo" class=\"selected\"";}echo">Overview</a></li>
			<li";if($page == "new_page"){echo" class=\"down\"";}echo"><a href=\"./?open=pages&amp;page=new_page&amp;editor_language=$editor_language\"";if($page == "new_page"){echo" class=\"selected\"";}echo">$l_new_page</a></li>
			<li";if($page == "navigation"){echo" class=\"down\"";}echo"><a href=\"./?open=pages&amp;page=navigation&amp;editor_language=$editor_language\"";if($page == "navigation"){echo" class=\"selected\"";}echo">$l_navigation</a></li>
			<li";if($page == "cookies_policy"){echo" class=\"down\"";}echo"><a href=\"index.php?open=pages&amp;page=cookies_policy&amp;editor_language=$editor_language&amp;l=$l\"";if($page == "cookies_policy"){echo" class=\"selected\"";}echo">Cookies policy</a></li>
			<li";if($page == "privacy_policy"){echo" class=\"down\"";}echo"><a href=\"index.php?open=pages&amp;page=privacy_policy&amp;editor_language=$editor_language&amp;l=$l\"";if($page == "privacy_policy"){echo" class=\"selected\"";}echo">Privacy policy</a></li>
			<li";if($page == "terms_of_use"){echo" class=\"down\"";}echo"><a href=\"index.php?open=pages&amp;page=terms_of_use&amp;editor_language=$editor_language&amp;l=$l\"";if($page == "terms_of_use"){echo" class=\"selected\"";}echo">Terms of use</a></li>
			
";

if($page == "menu"){
	echo"
		</ul>
	</div>
	";
}
?>