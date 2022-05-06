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
	<h1>Exercises</h1>
	<div class=\"vertical\">
		<ul>
			<li><a href=\"index.php?open=$open&amp;editor_language=$editor_language\">Exercises</a></li>

	";
}



echo"
			<li><a href=\"index.php?open=$open&amp;page=sqlite_exercises&amp;editor_language=$editor_language\""; if($page == "sqlite_exercises.php"){ echo" class=\"selected\""; } echo">SQLite Exercises</a></li>
			<li><a href=\"index.php?open=$open&amp;page=sqlite_equipments_levels_types&amp;editor_language=$editor_language\""; if($page == "sqlite_exercises.php"){ echo" class=\"selected\""; } echo">SQLite Equipments levels types</a></li>
			<li><a href=\"index.php?open=$open&amp;page=types&amp;editor_language=$editor_language\""; if($page == "types"){ echo" class=\"selected\""; } echo">Types</a></li>
			<li><a href=\"index.php?open=$open&amp;page=levels&amp;editor_language=$editor_language\""; if($page == "levels"){ echo" class=\"selected\""; } echo">Levels</a></li>
			<li><a href=\"index.php?open=$open&amp;page=tables&amp;editor_language=$editor_language\""; if($page == "tables"){ echo" class=\"selected\""; } echo">Tables</a></li>
	
";


if($page == "menu"){
	echo"
		</ul>
	</div>
	";
}
?>