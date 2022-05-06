<?php
/**
*
* File: _admin/_inc/talk/menu.php
* Version 1.0
* Date 17:37 31.08.2019
* Copyright (c) 2019 Sindre Andre Ditlefsen
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
	<h1>Talk</h1>
	<div class=\"vertical\">
		<ul>
			<li><a href=\"index.php?open=chat&amp;editor_language=$editor_language\">Default</a></li>

	";
}



echo"
			<li><a href=\"index.php?open=chat&amp;page=settings&amp;editor_language=$editor_language\""; if($page == "settings"){ echo" class=\"selected\""; } echo">Settings</a></li>
			<li><a href=\"index.php?open=chat&amp;page=tables&amp;editor_language=$editor_language\""; if($page == "tables"){ echo" class=\"selected\""; } echo">Tables</a></li>
		
";
?>