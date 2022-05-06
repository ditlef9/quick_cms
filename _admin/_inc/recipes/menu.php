<?php
/**
*
* File: _admin/_inc/cicolife/menu.php
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

/*- Language ----------------------------------------------------------------------- */
// include("_translations/admin/$l/recipes/t_common.php");

if($page == "menu"){
	echo"
	<h1>Recipes</h1>
	<div class=\"vertical\">
		<ul>
			<li><a href=\"index.php?open=$open&amp;editor_language=$editor_language\">Recipes</a></li>

	";
}


echo"

			<li><a href=\"index.php?open=$open&amp;page=recipes&amp;editor_language=$editor_language&amp;l=$l\""; if($page == "recipes"){ echo" class=\"selected\""; } echo">Recipes</a></li>
			<li><a href=\"index.php?open=$open&amp;page=welcome_text&amp;editor_language=$editor_language&amp;l=$l\""; if($page == "welcome_text"){ echo" class=\"selected\""; } echo">Welcome text</a></li>
			<li><a href=\"index.php?open=$open&amp;page=categories&amp;editor_language=$editor_language&amp;l=$l\""; if($page == "categories"){ echo" class=\"selected\""; } echo">Categories</a></li>
			<li><a href=\"index.php?open=$open&amp;page=cuisines&amp;editor_language=$editor_language&amp;l=$l\""; if($page == "cuisines"){ echo" class=\"selected\""; } echo">Cuisines</a></li>
			<li><a href=\"index.php?open=$open&amp;page=seasons&amp;editor_language=$editor_language&amp;l=$l\""; if($page == "seasons"){ echo" class=\"selected\""; } echo">Seasons</a></li>
			<li><a href=\"index.php?open=$open&amp;page=occasions&amp;editor_language=$editor_language&amp;l=$l\""; if($page == "occasions"){ echo" class=\"selected\""; } echo">Occasions</a></li>
			<li><a href=\"index.php?open=$open&amp;page=searches&amp;editor_language=$editor_language&amp;l=$l\""; if($page == "searches"){ echo" class=\"selected\""; } echo">Searches</a></li>
			<li><a href=\"index.php?open=$open&amp;page=main_ingredients&amp;editor_language=$editor_language&amp;l=$l\""; if($page == "main_ingredients"){ echo" class=\"selected\""; } echo">Main ingredients</a></li>
			<li><a href=\"index.php?open=$open&amp;page=settings&amp;editor_language=$editor_language&amp;l=$l\""; if($page == "settings"){ echo" class=\"selected\""; } echo">Settings</a></li>
			<li><a href=\"index.php?open=$open&amp;page=tables&amp;editor_language=$editor_language&amp;l=$l\""; if($page == "tables"){ echo" class=\"selected\""; } echo">Tables</a></li>
			<li><a href=\"index.php?open=$open&amp;page=backup&amp;editor_language=$editor_language&amp;l=$l\""; if($page == "backup"){ echo" class=\"selected\""; } echo">Backup</a></li>
	
";

if($page == "menu"){
	echo"
		</ul>
	</div>
	";
}
?>