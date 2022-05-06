<?php
/**
*
* File: _admin/_inc/webdesign/default.php
* Version 1.0.0
* Date 20:32 01.05.2019
* Copyright (c) 2008-2019 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Config ----------------------------------------------------------------------------- */
if(!(file_exists("_data/webdesign.php"))){
	$update_file="<?php
\$webdesignSav 	 = \"default\";
?>";

		$fh = fopen("_data/webdesign.php", "w+") or die("can not open file");
		fwrite($fh, $update_file);
		fclose($fh);
}
include("_data/webdesign.php");


echo"
<h1>Webdesign</h1>
		
<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = ucfirst($ft);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"
<!-- //Feedback -->


<!-- Webdesign menu -->
	<div class=\"vertical\">
		<ul>
			";
			include("_inc/webdesign/menu.php");
			echo"
		</ul>
	</div>
<!-- //Webdesign menu -->
";
?>