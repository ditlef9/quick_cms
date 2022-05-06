<?php
/**
*
* File: _admin/_inc/dashboard/menu.php
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
	<h1>Food</h1>
	<div class=\"vertical\">
		<ul>
			<li><a href=\"index.php?open=$open&amp;editor_language=$editor_language\">Food</a></li>

	";
}



echo"
			<li><a href=\"index.php?open=$open&amp;page=food&amp;editor_language=$editor_language\""; if($page == "food"){ echo" class=\"selected\""; } echo">Food</a></li>
			<li><a href=\"index.php?open=$open&amp;page=categories&amp;editor_language=$editor_language\""; if($page == "categories"){ echo" class=\"selected\""; } echo">Categories</a></li>
			<li><a href=\"index.php?open=$open&amp;page=measurements&amp;editor_language=$editor_language\""; if($page == "measurements"){ echo" class=\"selected\""; } echo">Measurements</a></li>
			<li><a href=\"index.php?open=$open&amp;page=members&amp;editor_language=$editor_language\""; if($page == "members"){ echo" class=\"selected\""; } echo">Members</a></li>
			<li><a href=\"index.php?open=$open&amp;page=settings&amp;editor_language=$editor_language\""; if($page == "settings"){ echo" class=\"selected\""; } echo">Settings</a></li>
			<li><a href=\"index.php?open=$open&amp;page=age_restrictions&amp;editor_language=$editor_language\""; if($page == "age_restrictions"){ echo" class=\"selected\""; } echo">Age restrictions</a></li>
			<li><a href=\"index.php?open=$open&amp;page=tables&amp;editor_language=$editor_language\""; if($page == "tables"){ echo" class=\"selected\""; } echo">Tables</a></li>
			<li><a href=\"index.php?open=$open&amp;page=export_to_android_kotlin&amp;editor_language=$editor_language\""; if($page == "export_to_android_kotlin"){ echo" class=\"selected\""; } echo">Export to Android Kotlin</a></li>
			<li><a href=\"index.php?open=$open&amp;page=check_food_for_errors&amp;editor_language=$editor_language\""; if($page == "check_food_for_errors"){ echo" class=\"selected\""; } echo">Check food for errors</a></li>
			<li><a href=\"index.php?open=$open&amp;page=titles&amp;editor_language=$editor_language\""; if($page == "titles"){ echo" class=\"selected\""; } echo">Titles</a></li>
		
";

if($page == "menu"){
	echo"
		</ul>
	</div>
	";
}
?>