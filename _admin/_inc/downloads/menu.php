<?php
/**
*
* File: _admin/_inc/downloads/menu.php
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
	<h1>Downloads</h1>
	<div class=\"vertical\">
		<ul>
			<li><a href=\"index.php?open=$open&amp;editor_language=$editor_language\">Downloads</a></li>

	";
}



echo"
			<li><a href=\"index.php?open=$open&amp;page=downloads&amp;editor_language=$editor_language\""; if($page == "downloads"){ echo" class=\"selected\""; } echo">Downloads</a></li>
			<li><a href=\"index.php?open=$open&amp;page=new_download&amp;editor_language=$editor_language\""; if($page == "new_download"){ echo" class=\"selected\""; } echo">New download</a></li>
			<li><a href=\"index.php?open=$open&amp;page=categories&amp;editor_language=$editor_language\""; if($page == "categories"){ echo" class=\"selected\""; } echo">Categories</a></li>
			<li><a href=\"index.php?open=$open&amp;page=scan_for_new_files&amp;l=$l&amp;editor_language=$editor_language\""; if($page == "scan_for_new_files"){ echo" class=\"selected\""; } echo">Scan for new files</a></li>
			<li><a href=\"index.php?open=$open&amp;page=tables&amp;editor_language=$editor_language\""; if($page == "tables"){ echo" class=\"selected\""; } echo">Tables</a></li>
			<li><a href=\"index.php?open=$open&amp;page=backup&amp;editor_language=$editor_language\""; if($page == "backup"){ echo" class=\"selected\""; } echo">Backup</a></li>
		
";


if($page == "menu"){
	echo"
		</ul>
	</div>
	";
}
?>