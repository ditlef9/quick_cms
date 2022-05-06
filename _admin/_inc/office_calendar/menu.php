<?php
/**
*
* File: _admin/_inc/office_calendar/menu.php
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
	<h1>office calendar</h1>
	<div class=\"vertical\">
		<ul>
			<li><a href=\"index.php?open=office_calendar&amp;editor_language=$editor_language\">Default</a></li>

	";
}



echo"
			<li><a href=\"index.php?open=office_calendar&amp;page=locations&amp;editor_language=$editor_language\""; if($page == "locations"){ echo" class=\"selected\""; } echo">Locations</a></li>
			<li><a href=\"index.php?open=office_calendar&amp;page=equipments&amp;editor_language=$editor_language\""; if($page == "equipments"){ echo" class=\"selected\""; } echo">Equipments</a></li>
			<li><a href=\"index.php?open=office_calendar&amp;page=tables&amp;editor_language=$editor_language\""; if($page == "tables"){ echo" class=\"selected\""; } echo">Tables</a></li>
		
";
?>