<?php
/**
*
* File: _admin/_inc/comments/menu.php
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
	<h1>Courses</h1>
	<div class=\"vertical\">
		<ul>
			<li><a href=\"index.php?open=$open&amp;editor_language=$editor_language\">Courses</a></li>

	";
}



echo"
			<li><a href=\"index.php?open=$open&amp;page=courses&amp;editor_language=$editor_language\">Courses</a></li>
			<li><a href=\"index.php?open=$open&amp;page=categories_main&amp;editor_language=$editor_language\">Categories</a></li>
			<li><a href=\"index.php?open=$open&amp;page=tables&amp;editor_language=$editor_language\""; if($page == "tables"){ echo" class=\"selected\""; } echo">Tables</a></li>
			<li><a href=\"index.php?open=$open&amp;page=backup&amp;editor_language=$editor_language\""; if($page == "backup"){ echo" class=\"selected\""; } echo">Backup</a></li>
		
";
?>