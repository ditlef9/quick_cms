<?php
/**
*
* File: _admin/_inc/gym_plans/menu.php
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
	<h1>Workout plans</h1>
	<div class=\"vertical\">
		<ul>
			<li><a href=\"index.php?open=$open&amp;editor_language=$editor_language\">Workout plans</a></li>

	";
}



echo"
			<li><a href=\"index.php?open=$open&amp;page=sqlite_workout_plans&amp;editor_language=$editor_language\""; if($page == "sqlite_workout_plans"){ echo" class=\"selected\""; } echo">SQLite Workout plans</a></li>
			<li><a href=\"index.php?open=$open&amp;page=sqlite_tags_unique&amp;editor_language=$editor_language\""; if($page == "sqlite_tags_unique"){ echo" class=\"selected\""; } echo">SQLite Tags unique</a></li>
			<li><a href=\"index.php?open=$open&amp;page=tables&amp;editor_language=$editor_language\""; if($page == "tables"){ echo" class=\"selected\""; } echo">Tables</a></li>
		
";

if($page == "menu"){
	echo"
		</ul>
	</div>
	";
}
?>