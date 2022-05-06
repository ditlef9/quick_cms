<?php
/**
*
* File: _admin/_inc/settings/default.php
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

echo"
<h1>Settings</h1>

<!-- Feedback -->
	";
	if($ft != ""){
		if($fm == "changes_saved"){
			$fm = "$l_changes_saved";
		}
		else{
			$fm = ucfirst($fm);
			$fm = str_replace("_", " ", $fm);
		}
		echo"<div class=\"$ft\"><span>$fm</span></div>";
	}
	echo"	
<!-- //Feedback -->

<!-- Settings menu -->
	<div class=\"vertical\">
		<ul>
			";
			include("_inc/settings/menu.php");
			echo"
		</ul>
	</div>
<!-- //Settings menu -->


";
?>