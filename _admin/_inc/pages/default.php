<?php
/**
*
* File: _admin/_inc/pages/default.php
* Version 
* Date 20:17 30.10.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

echo"
<h1>$l_pages</h1>

	<!-- Feedback -->
	";
	if($ft != ""){
		if($fm == "changes_saved"){
			$fm = "$l_changes_saved";
		}
		else{
			$fm = str_replace("_", " ", $fm);
			$fm = ucfirst($fm);
		}
		echo"<div class=\"$ft\"><span>$fm</span></div>";
	}
	echo"	
	<!-- //Feedback -->


<!-- Pages menu -->
	<div class=\"vertical\">
		<ul>
			";
			include("_inc/pages/menu.php");
			echo"
		</ul>
	</div>
<!-- //Pages menu -->

";

?>