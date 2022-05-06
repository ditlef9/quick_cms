<?php
/**
*
* File: _admin/_inc/domains_monitoring/menu.php
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
	<h1>Bitcoin tracker</h1>
	<div class=\"vertical\">
		<ul>
			<li><a href=\"index.php?open=$open&amp;editor_language=$editor_language\">Domains monitoring</a></li>

	";
}



echo"
			<li><a href=\"index.php?open=$open&amp;page=domains&amp;editor_language=$editor_language\""; if($page == "domains"){ echo" class=\"selected\""; } echo">Domains</a></li>
			<li><a href=\"index.php?open=$open&amp;page=insert_domains&amp;editor_language=$editor_language\""; if($page == "insert_domains"){ echo" class=\"selected\""; } echo">Insert domains</a></li>
			<li><a href=\"index.php?open=$open&amp;page=filters&amp;editor_language=$editor_language\""; if($page == "filters"){ echo" class=\"selected\""; } echo">Filters</a></li>
			<li><a href=\"index.php?open=$open&amp;page=check_domains&amp;editor_language=$editor_language\""; if($page == "check_domains"){ echo" class=\"selected\""; } echo">Check domains</a></li>
			<li><a href=\"index.php?open=$open&amp;page=domains_filtered&amp;editor_language=$editor_language\""; if($page == "domains_filtered"){ echo" class=\"selected\""; } echo">Domains filtered</a></li>
			<li><a href=\"index.php?open=$open&amp;page=domains_monitored&amp;editor_language=$editor_language\""; if($page == "domains_monitored"){ echo" class=\"selected\""; } echo">Domains monitored</a></li>
			<li><a href=\"index.php?open=$open&amp;page=tables&amp;editor_language=$editor_language\""; if($page == "tables"){ echo" class=\"selected\""; } echo">Tables</a></li>
			<li><a href=\"index.php?open=$open&amp;page=settings&amp;editor_language=$editor_language\""; if($page == "settings"){ echo" class=\"selected\""; } echo">Settings</a></li>
		
";

if($page == "menu"){
	echo"
		</ul>
	</div>
	";
}
?>