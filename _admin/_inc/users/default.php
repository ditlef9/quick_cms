<?php
/**
*
* File: _admin/_inc/users/default.php
* Version 1.0
* Date: 18:32 30.10.2017
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
<h1>$l_users</h1>


	<!-- Feedback -->
	";
	if($ft != "" && $fm != ""){
		if($fm == "user_deleted"){
			$fm = "$l_user_deleted";
		}
		else{
			$fm = ucfirst($ft);
		}
		echo"<div class=\"$ft\"><p>$fm</p></div>";
	}
	echo"
	<!-- //Feedback -->
<!-- Users menu -->
	<div class=\"vertical\">
		<ul>
			";
			include("_inc/users/menu.php");
			echo"
		</ul>
	</div>
<!-- //Users menu -->

";
?>
