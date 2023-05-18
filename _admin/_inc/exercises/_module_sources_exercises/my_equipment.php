<?php 
/**
*
* File: food/my_exercises.php
* Version 1.0.0
* Date 12:05 10.02.2018
* Copyright (c) 2011-2018 S. A. Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Configuration --------------------------------------------------------------------- */
$pageIdSav            = "2";
$pageNoColumnSav      = "2";
$pageAllowCommentsSav = "0";

/*- Root dir -------------------------------------------------------------------------- */
// This determine where we are
if(file_exists("favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
elseif(file_exists("../../../../favicon.ico")){ $root = "../../../.."; }
else{ $root = "../../.."; }

/*- Website config -------------------------------------------------------------------- */
include("$root/_admin/website_config.php");

/*- Tables ---------------------------------------------------------------------------- */
include("_tables_exercises.php");


/*- Variables ------------------------------------------------------------------------- */
$tabindex = 0;
$l_mysql = quote_smart($link, $l);

if(isset($_GET['type_id'])){
	$type_id = $_GET['type_id'];
	$type_id = strip_tags(stripslashes($type_id));
}
else{
	$type_id = "";
}
if(isset($_GET['main_muscle_group_id'])){
	$main_muscle_group_id = $_GET['main_muscle_group_id'];
	$main_muscle_group_id = strip_tags(stripslashes($main_muscle_group_id));
}
else{
	$main_muscle_group_id = "";
}

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_my_equipment - $l_exercises";
include("$root/_webdesign/header.php");

/*- Content ---------------------------------------------------------------------------------- */
// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
	// Get my user
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_user_id, $get_user_email, $get_user_name, $get_user_alias, $get_user_rank) = $row;


	
	echo"
	<h1>$l_my_equipment</h1>
	

	<!-- Where am I? -->
		<p><b>$l_you_are_here:</b><br />
		<a href=\"index.php?l=$l\">$l_exercises</a>
		&gt;
		<a href=\"my_exercises.php?l=$l\">$l_my_exercises</a>
		&gt;
		<a href=\"my_equipment.php?l=$l\">$l_my_equipment</a>
		</p>
	<!-- //Where am I? -->
	
	<!-- Buttons -->
		<p>
		<a href=\"$root/exercises/new_equipment.php?l=$l\" class=\"btn_default\">$l_new_equipment</a>
		</p>
	<div class=\"clear\"></div>
	<!-- //Buttons -->

	<!-- Selector -->

	<div class=\"right\" style=\"text-align: right;\">

		<script>
			\$(function(){
				\$('#inp_language_select').on('change', function () {
					var url = \$(this).val();
					if (url) { // require a URL
 						window.location = url;
					}
					return false;
				});
			});
		</script>

		<form method=\"get\" action=\"cc\" enctype=\"multipart/form-data\">
			<p>

			<select name=\"inp_language_select\" id=\"inp_language_select\">
				<option value=\"my_equipment.php?l=$l\">- $l_language -</option>\n";

				$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;



					echo"		";
					echo"<option value=\"my_equipment.php?l=$get_language_active_iso_two\""; if($l == "$get_language_active_iso_two"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";

				}
			echo"
			</select>

			</p>
        	</form>
	</div>
	<!-- //Selector -->

	<!-- List my exercises -->
	<table class=\"hor-zebra\">
	 <thead>
	  <tr>
	   <th scope=\"col\">
		<span>$l_equipment</span>
	   </th>
	   <th scope=\"col\">
		<span>$l_date</span>
	   </th>
	   <th scope=\"col\">
		<span>$l_actions</span>
	   </th>
	  </tr>
	</thead>
	<tbody>
	";
	
	$query = "SELECT equipment_id, equipment_title, equipment_image_path, equipment_image_file, equipment_updated_datetime FROM $t_exercise_equipments WHERE equipment_user_id=$my_user_id_mysql AND equipment_language=$l_mysql";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_equipment_id, $get_equipment_title, $get_equipment_image_path, $get_equipment_image_file, $get_equipment_updated_datetime) = $row;

			if(isset($style) && $style == "odd"){
				$style = "";
			}
			else{
				$style = "odd";
			}

			echo"
			<tr>
			  <td class=\"$style\" style=\"vertical-align: top;\">
				<div style=\"float:left;margin-right: 10px;\">
					";
					// Images
					if($get_equipment_image_file != "" && file_exists("$root/$get_equipment_image_path/$get_equipment_image_file")){
						echo"<a href=\"view_equipment.php?equipment_id=$get_equipment_id&amp;l=$l\"><img src=\"$root/image.php?width=80&amp;height=54&amp;image=/$get_equipment_image_path/$get_equipment_image_file\" alt=\"$get_equipment_image_file\" /></a>\n";
					}
					echo"
				</div>
				<div style=\"float:left;\">
					<p>
					<a href=\"view_equipment.php?equipment_id=$get_equipment_id&amp;l=$l\">$get_equipment_title</a><br />
					

					</p>
				</div>
				
			  </td>
			  <td class=\"$style\" style=\"vertical-align: top;\">
				<p>$get_equipment_updated_datetime</p>
			  </td>
			  <td class=\"$style\" style=\"vertical-align: top;\">
				<p>
				<a href=\"edit_equipment.php?equipment_id=$get_equipment_id&amp;l=$l\">$l_edit</a>
				&middot;
				<a href=\"delete_equipment.php?equipment_id=$get_equipment_id&amp;l=$l\">$l_delete</a>
				</p>
			 </td>
			</tr>
			";
	}
	echo"
	 </tbody>
	</table>
	<!-- //List all exercises -->
	";
}
else{
	echo"
	<h1>
	<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;refer=$root/exercises/my_exercises.php\">
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>