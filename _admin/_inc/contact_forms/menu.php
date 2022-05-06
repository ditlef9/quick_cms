<?php
/**
*
* File: _admin/_inc/contact_forms/menu.php
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
include("_translations/admin/$l/contact_forms/t_common.php");


if($page == "menu"){
	echo"
	<h1>Contact forms</h1>
	<div class=\"vertical\">
		<ul>
			<li><a href=\"index.php?open=$open&amp;editor_language=$editor_language\">Contact forms</a></li>

	";
}

echo"

			<li><a href=\"./?open=$open&amp;page=tables&amp;editor_language=$editor_language\""; if($page == "tables"){ echo" class=\"selected\""; } echo">$l_tables</a></li>
	
";


if($page == "menu"){
	echo"
		</ul>
	</div>
	";
}
?>