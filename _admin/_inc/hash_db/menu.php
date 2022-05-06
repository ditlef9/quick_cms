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
			<li><a href=\"index.php?open=hash_db&amp;editor_language=$editor_language\">Default</a></li>

	";
}



echo"
			<li><a href=\"index.php?open=hash_db&amp;page=categories&amp;editor_language=$editor_language\""; if($page == "categories"){ echo" class=\"selected\""; } echo">Categories</a></li>
			<li><a href=\"index.php?open=hash_db&amp;page=entries&amp;editor_language=$editor_language\""; if($page == "entries"){ echo" class=\"selected\""; } echo">Entries</a></li>
			<li><a href=\"index.php?open=hash_db&amp;page=api&amp;editor_language=$editor_language\""; if($page == "api"){ echo" class=\"selected\""; } echo">API</a></li>
			<li><a href=\"index.php?open=hash_db&amp;page=settings&amp;editor_language=$editor_language\""; if($page == "settings"){ echo" class=\"selected\""; } echo">Settings</a></li>
			<li><a href=\"index.php?open=hash_db&amp;page=tables&amp;editor_language=$editor_language\""; if($page == "tables"){ echo" class=\"selected\""; } echo">Tables</a></li>
		
";
?>