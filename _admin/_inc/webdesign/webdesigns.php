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


if($action == ""){
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


	<!-- All webdesigns -->
		
		";
		$path = "../_webdesign";
		if(!(is_dir("$path"))){
			echo"$path doesnt exists";
			die;
		}
		if ($handle = opendir($path)) {
			$x = 0;
			while (false !== ($webdesign_name = readdir($handle))) {
				if ($webdesign_name === '.') continue;
				if ($webdesign_name === '..') continue;
				if ($webdesign_name === 'images') continue;
				if ($webdesign_name === '_other_designs') continue;
				if(is_dir("$path/$webdesign_name")){
					if($x == 0){
						echo"
						<div class=\"flex_row\">
						";
					}
					echo"
							<div class=\"flex_col\">
								<p>
								<a href=\"index.php?open=webdesign&amp;page=$page&amp;action=view_webdesign&amp;webdesign_name=$webdesign_name&amp;editor_language=$editor_language&amp;l=$l\">$webdesign_name</a><br />
								<a href=\"index.php?open=webdesign&amp;page=$page&amp;action=view_webdesign&amp;webdesign_name=$webdesign_name&amp;editor_language=$editor_language&amp;l=$l\"><img src=\"$path/$webdesign_name/webdesign_preview_$webdesign_name.jpg\" alt=\"webdesign_preview_$webdesign_name.jpg\" style=\"border: ";
								if($webdesignSav == "$webdesign_name"){ echo"#009b33 2px solid"; } else{ echo"#ccc 1px solid"; } echo";\" /></a>
								</p>
							</div>
					";

					if($x == 1){
						echo"
							</div>
						";

						$x = -1;
					}
					$x++;
				}
			}
		}
		if($x == 1){
						echo"
							</div>
						";

		}
		echo"
	<!-- //All webdesigns -->
	";
}
elseif($action == "view_webdesign"){
	/*- Variables ------------------------------------------------------------------------ */
	if(isset($_GET['webdesign_name'])) {
		$webdesign_name = $_GET['webdesign_name'];
		$webdesign_name = strip_tags(stripslashes($webdesign_name));
	}
	else{
		$webdesign_name = "";
	}
	if(is_dir("../_webdesign/$webdesign_name")){
		echo"
		<h1>$webdesign_name</h1>

		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=webdesign&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Webdesign</a>
			&gt;
			<a href=\"index.php?open=webdesign&amp;page=$page&amp;action=view_webdesign&amp;webdesign_name=$webdesign_name&amp;editor_language=$editor_language&amp;l=$l\">$webdesign_name</a>
			</p>
		<!-- //Where am I? -->

		<!-- Design info -->
			";
			if(file_exists("../_webdesign/$webdesign_name/webdesign_preview_$webdesign_name.jpg")){
				echo"
				<div style=\"float: left;\">
					<img src=\"../_webdesign/$webdesign_name/webdesign_preview_$webdesign_name.jpg\" alt=\"webdesign_preview_$webdesign_name.jpg\" style=\"border: #ccc 1px solid;\" />
				</div>
				";
			}
			echo"
				<p><b>Name:</b> $webdesign_name</p>

				<p><a href=\"index.php?open=webdesign&amp;page=$page&amp;action=switch_to_webdesign&amp;webdesign_name=$webdesign_name&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_default\">Use design</a></p>


			<div class=\"clear\"></div>
		<!-- //Design info -->
		";
	} // found
}

elseif($action == "switch_to_webdesign"){
	/*- Variables ------------------------------------------------------------------------ */
	if(isset($_GET['webdesign_name'])) {
		$webdesign_name = $_GET['webdesign_name'];
		$webdesign_name = strip_tags(stripslashes($webdesign_name));
	}
	else{
		$webdesign_name = "";
	}
	if(is_dir("../_webdesign/$webdesign_name")){
		

	$update_file="<?php
\$webdesignSav 	 = \"$webdesign_name\";
?>";

		$fh = fopen("_data/webdesign.php", "w+") or die("can not open file");
		fwrite($fh, $update_file);
		fclose($fh);

		header("Location: index.php?open=$open&page=$page&ft=success&fm=changes_saved");
		exit;
	}
}
?>