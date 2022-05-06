<?php
/**
*
* File: _admin/_inc/exercises/menu.php
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
	<h1>Muscles</h1>
	<div class=\"vertical\">
		<ul>
			<li><a href=\"index.php?open=$open&amp;editor_language=$editor_language\">Muscles default</a></li>

	";
}


echo"
			<li><a href=\"index.php?open=$open&amp;page=muscles&amp;editor_language=$editor_language\">Muscles</a></li>
			<li><a href=\"index.php?open=$open&amp;page=sqlite_muscles&amp;editor_language=$editor_language\""; if($page == "sqlite_muscles"){ echo" class=\"selected\""; } echo">SQLite Muscles</a></li>
			<li><a href=\"index.php?open=$open&amp;page=muscle_groups&amp;editor_language=$editor_language\""; if($page == "muscle_groups"){ echo" class=\"selected\""; } echo">Muscle groups</a></li>
			<li><a href=\"index.php?open=$open&amp;page=muscle_part_of&amp;editor_language=$editor_language\""; if($page == "muscle_part_of"){ echo" class=\"selected\""; } echo">Muscle part of</a></li>
			<li><a href=\"index.php?open=$open&amp;page=tables&amp;editor_language=$editor_language\""; if($page == "tables"){ echo" class=\"selected\""; } echo">Tables</a></li>
		
";

if($page == "menu"){
	echo"
		</ul>
	</div>
	";
}
?>