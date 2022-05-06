<?php
/**
*
* File: _admin/_inc/sosial_media/menu.php
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
	<h1>Ads</h1>
	<div class=\"vertical\">
		<ul>
			<li><a href=\"index.php?open=ads&amp;editor_language=$editor_language\">Ads</a></li>

	";
}


echo"
			<li><a href=\"index.php?open=ads&amp;page=ads_overview&amp;editor_language=$editor_language\""; if($page == "ads_overview"){ echo" class=\"selected\""; } echo">Ads overview</a></li>
			<li><a href=\"index.php?open=ads&amp;page=ads_settings&amp;editor_language=$editor_language\""; if($page == "ads_settings"){ echo" class=\"selected\""; } echo">Ads settings</a></li>
			<li><a href=\"index.php?open=ads&amp;page=tables&amp;editor_language=$editor_language\""; if($page == "tables"){ echo" class=\"selected\""; } echo">Tables</a></li>
	
";

if($page == "menu"){
	echo"
		</ul>
	</div>
	";
}
?>