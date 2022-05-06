<?php
/**
*
* File: _admin/_inc/music_star/menu.php
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
	<h1>Knowledge</h1>
	<div class=\"vertical\">
		<ul>
			<li><a href=\"index.php?open=knowledge&amp;editor_language=$editor_language\">Default</a></li>

	";
}



echo"
			<li><a href=\"index.php?open=knowledge&amp;page=tables&amp;editor_language=$editor_language\""; if($page == "tables"){ echo" class=\"selected\""; } echo">Tables</a></li>
			
		
";
?>