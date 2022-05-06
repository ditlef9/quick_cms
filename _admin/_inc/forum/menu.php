<?php
/**
*
* File: _admin/_inc/discuss/menu.php
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
	<h1>Discuss</h1>
	<div class=\"vertical\">
		<ul>
			<li><a href=\"index.php?open=$open&amp;editor_language=$editor_language\">Discuss</a></li>

	";
}


echo"
			<li><a href=\"index.php?open=$open&amp;page=settings&amp;editor_language=$editor_language\""; if($page == "settings"){ echo" class=\"selected\""; } echo">Settings</a></li>
			<li><a href=\"index.php?open=$open&amp;page=forms&amp;editor_language=$editor_language\""; if($page == "forms"){ echo" class=\"selected\""; } echo">Forms</a></li>
			<li><a href=\"index.php?open=$open&amp;page=title&amp;editor_language=$editor_language\""; if($page == "title"){ echo" class=\"selected\""; } echo">Title</a></li>
			<li><a href=\"index.php?open=$open&amp;page=tags&amp;editor_language=$editor_language\""; if($page == "title"){ echo" class=\"selected\""; } echo">Tags</a></li>
			<li><a href=\"index.php?open=$open&amp;page=tables&amp;editor_language=$editor_language\""; if($page == "tables"){ echo" class=\"selected\""; } echo">Tables</a></li>
		
";


if($page == "menu"){
	echo"
		</ul>
	</div>
	";
}
?>