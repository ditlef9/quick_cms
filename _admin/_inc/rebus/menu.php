<?php
/**
*
* File: _admin/_inc/hash_db/menu.php
* Version 1.0
* Date 14:41 23.02.2020
* Copyright (c) 2020 Sindre Andre Ditlefsen
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
	<h1>Hash DB</h1>
	<div class=\"vertical\">
		<ul>
			<li><a href=\"index.php?open=rebus&amp;editor_language=$editor_language\">Default</a></li>

	";
}



echo"
			<li><a href=\"index.php?open=rebus&amp;page=api_overview&amp;editor_language=$editor_language\""; if($page == "api_overview"){ echo" class=\"selected\""; } echo">API overview</a></li>
			<li><a href=\"index.php?open=rebus&amp;page=settings&amp;editor_language=$editor_language\""; if($page == "settings"){ echo" class=\"selected\""; } echo">Settings</a></li>
			<li><a href=\"index.php?open=rebus&amp;page=places&amp;editor_language=$editor_language\""; if($page == "places"){ echo" class=\"selected\""; } echo">Places</a></li>
			<li><a href=\"index.php?open=rebus&amp;page=tables&amp;editor_language=$editor_language\""; if($page == "tables"){ echo" class=\"selected\""; } echo">Tables</a></li>

";
?>