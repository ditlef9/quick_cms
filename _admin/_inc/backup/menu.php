<?php
/**
*
* File: _admin/_inc/backup/menu.php
* Version 20:14 12.01.2022
* Copyright (c) 2022 Sindre Andre Ditlefsen
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
	<h1>Backup</h1>
	<div class=\"vertical\">
		<ul>
			<li><a href=\"index.php?open=$open&amp;editor_language=$editor_language\">Backup</a></li>

	";
}



echo"
			<li><a href=\"index.php?open=$open&amp;page=backups&amp;editor_language=$editor_language\""; if($page == "backups"){ echo" class=\"selected\""; } echo">Backups</a></li>
			<li><a href=\"index.php?open=$open&amp;page=new_backup&amp;editor_language=$editor_language\""; if($page == "new_backup"){ echo" class=\"selected\""; } echo">New backup</a></li>
			<li><a href=\"index.php?open=$open&amp;page=restore&amp;editor_language=$editor_language\""; if($page == "restore"){ echo" class=\"selected\""; } echo">Restore</a></li>
			<li><a href=\"index.php?open=$open&amp;page=tables&amp;editor_language=$editor_language\""; if($page == "tables"){ echo" class=\"selected\""; } echo">Tables</a></li>
	
";


if($page == "menu"){
	echo"
		</ul>
	</div>
	";
}
?>