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

include("_translations/admin/$l/settings/t_common.php");


if($page == "menu"){
	echo"
	<h1>Settings</h1>
	<div class=\"vertical\">
		<ul>
			<li><a href=\"index.php?open=$open&amp;editor_language=$editor_language\">Settings</a></li>

	";
}




echo"
			<li";if($page == "meta_data"){echo" class=\"down\"";}echo"><a href=\"./?open=settings&amp;page=meta_data&amp;editor_language=$editor_language\"";if($page == "meta_data"){echo" class=\"selected\"";}echo">Meta data</a></li>
			<li";if($page == "slogan"){echo" class=\"down\"";}echo"><a href=\"./?open=settings&amp;page=slogan&amp;editor_language=$editor_language\"";if($page == "slogan"){echo" class=\"selected\"";}echo">Slogan</a></li>
			<li";if($page == "languages"){echo" class=\"down\"";}echo"><a href=\"./?open=settings&amp;page=languages&amp;editor_language=$editor_language\"";if($page == "languages"){echo" class=\"selected\"";}echo">$l_language</a></li>
			<li";if($page == "cp_translation"){echo" class=\"down\"";}echo"><a href=\"./?open=settings&amp;page=cp_translation&amp;editor_language=$editor_language\"";if($page == "cp_translation"){echo" class=\"selected\"";}echo">$l_cp_translation</a></li>
			<li";if($page == "site_translation"){echo" class=\"down\"";}echo"><a href=\"./?open=settings&amp;page=site_translation&amp;editor_language=$editor_language\"";if($page == "site_translation"){echo" class=\"selected\"";}echo">$l_site_translation</a></li>
			<li";if($page == "mysql"){echo" class=\"down\"";}echo"><a href=\"./?open=settings&amp;page=mysql&amp;editor_language=$editor_language\"";if($page == "mysql"){echo" class=\"selected\"";}echo">$l_mysql</a></li>
			<li";if($page == "emojies_index"){echo" class=\"down\"";}echo"><a href=\"./?open=$open&amp;page=emojies_index&amp;editor_language=$editor_language\"";if($page == "emojies_index"){echo" class=\"selected\"";}echo">Emojies index</a></li>
			<li><a href=\"index.php?open=settings&amp;page=search_engine_index&amp;editor_language=$editor_language\"";if($page == "search_engine_index"){echo" class=\"selected\"";}echo">Search engine index</a></li>

			<li";if($page == "liquidbase"){echo" class=\"down\"";}echo"><a href=\"index.php?open=settings&amp;page=liquidbase&amp;editor_language=$editor_language\"";if($page == "liquidbase"){echo" class=\"selected\"";}echo">Liquidbase</a></li>

			
";
?>