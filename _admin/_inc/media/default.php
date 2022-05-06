<?php
/**
*
* File: _admin/_inc/media/default.php
* Version 
* Date 18:40 02.11.2017
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
<h1>$l_media</h1>

	
<!-- Feedback -->
	";
	if($ft != ""){
		if($fm == "changes_saved"){
			$fm = "$l_changes_saved";
		}
		elseif($fm == "navgation_item_deleted"){
			$fm = "$l_navgation_item_deleted";
		}
		
		echo"<div class=\"$ft\"><span>$fm</span></div>";
	}
	echo"	
<!-- //Feedback -->


<!-- Media menu -->
	<div class=\"vertical\">
		<ul>
			";
			include("_inc/media/menu.php");
			echo"
		</ul>
	</div>
<!-- //Media menu -->
";
?>