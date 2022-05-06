<?php
/**
*
* File: _admin/_inc/muscles/open_sub_muscle_group.php
* Version 15.00 03.03.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Tables ----------------------------------------------------------------------------- */
$t_muscles				= $mysqlPrefixSav . "muscles";
$t_muscles_translations 		= $mysqlPrefixSav . "muscles_translations";
$t_muscle_groups 			= $mysqlPrefixSav . "muscle_groups";
$t_muscle_groups_translations	 	= $mysqlPrefixSav . "muscle_groups_translations";


/*- Variables -------------------------------------------------------------------------- */
$editor_language_mysql = quote_smart($link, $editor_language);

if(isset($_GET['id'])){
	$id = $_GET['id'];
	$id = strip_tags(stripslashes($id));
}
else{
	$id = "";
}
if(isset($_GET['main_muscle_group_id'])){
	$main_muscle_group_id = $_GET['main_muscle_group_id'];
	$main_muscle_group_id = strip_tags(stripslashes($main_muscle_group_id));
}
else{
	$main_muscle_group_id = "";
}
if(isset($_GET['sub_muscle_group_id'])){
	$sub_muscle_group_id = $_GET['sub_muscle_group_id'];
	$sub_muscle_group_id = strip_tags(stripslashes($sub_muscle_group_id));
}
else{
	$sub_muscle_group_id = "";
}


/*- Scriptstart ---------------------------------------------------------------------- */

// Find sub
$sub_muscle_group_id_mysql = quote_smart($link, $sub_muscle_group_id);
$query = "SELECT muscle_group_id, muscle_group_name, muscle_group_name_clean, muscle_group_parent_id, muscle_group_image_path, muscle_group_image_file FROM $t_muscle_groups WHERE muscle_group_id='$sub_muscle_group_id_mysql'";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_sub_muscle_group_id, $get_current_sub_muscle_group_name, $get_current_sub_muscle_group_name_clean, $get_current_sub_muscle_group_parent_id, $get_current_sub_muscle_group_image_path, $get_current_sub_muscle_group_image_file) = $row;

// Find main
$main_muscle_group_id_mysql = quote_smart($link, $main_muscle_group_id);
$query = "SELECT muscle_group_id, muscle_group_name, muscle_group_name_clean, muscle_group_parent_id, muscle_group_image_path, muscle_group_image_file FROM $t_muscle_groups WHERE muscle_group_id='$main_muscle_group_id_mysql'";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_main_muscle_group_id, $get_current_main_muscle_group_name, $get_current_main_muscle_group_name_clean, $get_current_main_muscle_group_parent_id, $get_current_main_muscle_group_image_path, $get_current_main_muscle_group_image_file) = $row;


if($get_current_sub_muscle_group_id == ""){
	echo"
	<p>Sub not found.</p>
	";
}
else{ 

	if($get_current_main_muscle_group_id == ""){
		echo"
		<p>Main not found.</p>
		";
	}
	else{

		echo"
	<!-- Headline and Language -->
		<div style=\"float:left;\">
			<h1>Muscles</h1>
		</div>
		<div style=\"float:left;padding: 14px 0px 0px 500px\">
			<form>
			<select name=\"inp_language\" id=\"inp_language\">\n";

			$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_flag, language_active_default FROM $t_languages_active";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_flag, $get_language_active_default) = $row;

				$flag_path 	= "_design/gfx/flags/16x16/$get_language_active_flag" . "_16x16.png";
				// No language selected?
				if($editor_language == ""){
						$editor_language = "$get_language_active_iso_two";
				}
				echo"<option value=\"index.php?open=$open&amp;page=$page&amp;main_muscle_group_id=$main_muscle_group_id&amp;sub_muscle_group_id=$sub_muscle_group_id&amp;editor_language=$get_language_active_iso_two&amp;l=$l\" style=\"background: url('$flag_path') no-repeat;padding-left: 20px;\"";if($editor_language == "$get_language_active_iso_two"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";
			}
			echo"
			</select>
			</form>

			<script>
   			 $(function(){
    			  // bind change event to select
    			  $('#inp_language').on('change', function () {
     			     var url = $(this).val(); // get selected value
      			    if (url) { // require a URL
       			       window.location = url; // redirect
      			    }
      			    return false;
   			   });
  			  });
			</script>
		</div>
		<div class=\"clear\"></div>
	<!-- //Headline and Language -->

		<p>
		<a href=\"index.php?open=$open&amp;page=muscle_new&amp;main_muscle_group_id=$main_muscle_group_id&amp;sub_muscle_group_id=$sub_muscle_group_id&amp;editor_language=$editor_language\">New</a>
		</p>
	
		<!-- Left and right -->
			<div style=\"float: left;\">
				<!-- Main muscle groups -->
					<table class=\"hor-zebra\">
					 <tr>
					  <td class=\"odd\">
						<p>\n";
						// Get all main
						$query = "SELECT muscle_group_id, muscle_group_name, muscle_group_name_clean, muscle_group_parent_id, muscle_group_image_path, muscle_group_image_file FROM $t_muscle_groups WHERE muscle_group_parent_id='0'";
						$result = mysqli_query($link, $query);
						while($row = mysqli_fetch_row($result)) {
							list($get_muscle_group_id, $get_muscle_group_name, $get_muscle_group_name_clean, $get_muscle_group_parent_id, $get_muscle_group_image_path, $get_muscle_group_image_file) = $row;
			
							// Translation
							$query_translation = "SELECT muscle_group_translation_id,muscle_group_translation_name FROM $t_muscle_groups_translations WHERE muscle_group_translation_muscle_group_id=$get_muscle_group_id AND muscle_group_translation_language=$editor_language_mysql";
							$result_translation = mysqli_query($link, $query_translation);
							$row_translation = mysqli_fetch_row($result_translation);
							list($get_muscle_group_translation_id, $get_muscle_group_translation_name) = $row_translation;
							echo"					";
							echo"<a href=\"index.php?open=$open&amp;page=open_main_muscle_group&amp;main_muscle_group_id=$get_muscle_group_id&amp;editor_language=$editor_language\""; if($get_muscle_group_id == "$main_muscle_group_id"){ echo" style=\"font-weight: bold\""; } echo">$get_muscle_group_translation_name</a><br />\n";

							if($get_muscle_group_id == "$main_muscle_group_id"){
								// Get sub
								$query_sub = "SELECT muscle_group_id, muscle_group_name, muscle_group_name_clean, muscle_group_parent_id, muscle_group_image_path, muscle_group_image_file FROM $t_muscle_groups WHERE muscle_group_parent_id='$get_muscle_group_id'";
								$result_sub = mysqli_query($link, $query_sub);
								while($row_sub = mysqli_fetch_row($result_sub)) {
									list($get_sub_muscle_group_id, $get_sub_muscle_group_name, $get_sub_muscle_group_name_clean, $get_sub_muscle_group_parent_id, $get_sub_muscle_group_image_path, $get_sub_muscle_group_image_file) = $row_sub;
			
									// Translation
									$query_translation = "SELECT muscle_group_translation_id,muscle_group_translation_name FROM $t_muscle_groups_translations WHERE muscle_group_translation_muscle_group_id=$get_sub_muscle_group_id AND muscle_group_translation_language=$editor_language_mysql";
									$result_translation = mysqli_query($link, $query_translation);
									$row_translation = mysqli_fetch_row($result_translation);
									list($get_sub_muscle_group_translation_id, $get_sub_muscle_group_translation_name) = $row_translation;
									echo"					";
									echo"&nbsp; &nbsp; <a href=\"index.php?open=$open&amp;page=open_sub_muscle_group&amp;main_muscle_group_id=$get_muscle_group_id&amp;sub_muscle_group_id=$get_sub_muscle_group_id&amp;editor_language=$editor_language\""; if($get_sub_muscle_group_id == "$sub_muscle_group_id"){ echo" style=\"font-weight: bold\""; } echo">$get_sub_muscle_group_translation_name</a><br />\n";
								}
							}
						}
					echo"
					  </td>
					 </tr>
					</table>
				<!-- //Main categories -->
			</div>
			<div style=\"float: left;padding: 0px 0px 0px 20px;\">
				<!-- All muscles in sub category -->
					
						<table style=\"width: 100%;\">
						";
						// Set layout
						$layout = 0;

						// Get all muscles
						$query = "SELECT muscle_id, muscle_latin_name, muscle_latin_name_clean, muscle_simple_name, muscle_image_path, muscle_image_file FROM $t_muscles WHERE muscle_group_id_sub='$get_current_sub_muscle_group_id' ORDER BY muscle_latin_name ASC";
						$result = mysqli_query($link, $query);
						while($row = mysqli_fetch_row($result)) {
							list($get_muscle_id, $get_muscle_latin_name, $get_muscle_latin_name_clean, $get_muscle_simple_name, $get_muscle_image_path, $get_muscle_image_file) = $row;
				

							// Translation
							$query_translation = "SELECT muscle_translation_id, muscle_translation_simple_name FROM $t_muscles_translations WHERE muscle_translation_muscle_id='$get_muscle_id' AND muscle_translation_language=$editor_language_mysql";
							$result_translation = mysqli_query($link, $query_translation);
							$row_translation = mysqli_fetch_row($result_translation);
							list($get_muscle_translation_id, $get_muscle_translation_simple_name) = $row_translation;
							if($get_muscle_translation_id == ""){

								mysqli_query($link, "INSERT INTO $t_muscles_translations
								(muscle_translation_id, muscle_translation_muscle_id, muscle_translation_language, muscle_translation_simple_name, muscle_translation_text) 
								VALUES 
								(NULL, 	'$get_muscle_id', $editor_language_mysql, '$get_muscle_simple_name', '')")
								or die(mysqli_error($link));
								echo"<div class=\"info\"><span>L O A D I N G</span></div>";
								echo"
 								<meta http-equiv=\"refresh\" content=\"0;URL='index.php?open=$open&amp;page=$page&amp;main_muscle_group_id=$main_muscle_group_id&amp;sub_muscle_group_id=$sub_muscle_group_id&amp;editor_language=$editor_language&amp;l=$l'\" />
								";

							}

							// Name saying
							$check = strlen($get_muscle_simple_name);
							if($check > 30){
								$get_muscle_simple_name = substr($get_muscle_simple_name, 0, 33);
								$get_muscle_simple_name = $get_muscle_simple_name . "...";
							}	



							if($layout == 0){
								echo"
								 <tr>
								  <td style=\"width: 143px;padding: 0px 10px 0px 0px;vertical-align: top;text-align: center;\">
									<p style=\"padding-bottom:0;\">
									<a href=\"index.php?open=$open&amp;page=view_muscle&amp;main_muscle_group_id=$main_muscle_group_id&amp;sub_muscle_group_id=$sub_muscle_group_id&amp;muscle_id=$get_muscle_id&amp;editor_language=$editor_language\"><img src=\"";
									if($get_muscle_image_file != "" && file_exists("../$get_muscle_image_path/$get_muscle_image_file")){
										echo"../image.php?width=132&amp;height=132&amp;image=/$get_muscle_image_path/$get_muscle_image_file";
									}
									else{
										echo"_design/gfx/no_thumb.png";
									}
									echo"\" alt=\"$get_muscle_latin_name\" style=\"margin-bottom: 5px;\" /></a><br />
					
									<a href=\"index.php?open=$open&amp;page=view_muscle&amp;main_muscle_group_id=$main_muscle_group_id&amp;sub_muscle_group_id=$sub_muscle_group_id&amp;muscle_id=$get_muscle_id&amp;editor_language=$editor_language\">$get_muscle_translation_simple_name</a>
									</p>

								  </td>
								";
							}
							elseif($layout == 1){
								echo"
								  <td style=\"width: 143px;padding: 0px 10px 0px 0px;vertical-align: top;text-align: center;\">

									<p style=\"padding-bottom:0;\">
									<a href=\"index.php?open=$open&amp;page=view_muscle&amp;main_muscle_group_id=$main_muscle_group_id&amp;sub_muscle_group_id=$sub_muscle_group_id&amp;muscle_id=$get_muscle_id&amp;editor_language=$editor_language\"><img src=\"";
									if($get_muscle_image_file != "" && file_exists("../$get_muscle_image_path/$get_muscle_image_file")){
										echo"../image.php?width=132&amp;height=132&amp;image=/$get_muscle_image_path/$get_muscle_image_file";
									}
									else{
										echo"_design/gfx/no_thumb.png";
									}
									echo"\" alt=\"$get_muscle_latin_name\" style=\"margin-bottom: 5px;\" /></a><br />
					
									<a href=\"index.php?open=$open&amp;page=view_muscle&amp;main_muscle_group_id=$main_muscle_group_id&amp;sub_muscle_group_id=$sub_muscle_group_id&amp;muscle_id=$get_muscle_id&amp;editor_language=$editor_language\">$get_muscle_translation_simple_name</a>
									
									</p>
								  </td>
								";
							}
							elseif($layout == 2){
								echo"
								  <td style=\"width: 143px;padding: 0px 10px 0px 0px;vertical-align: top;text-align: center;\">

									<p style=\"padding-bottom:0;\">
									<a href=\"index.php?open=$open&amp;page=view_muscle&amp;main_muscle_group_id=$main_muscle_group_id&amp;sub_muscle_group_id=$sub_muscle_group_id&amp;muscle_id=$get_muscle_id&amp;editor_language=$editor_language\"><img src=\"";
									if($get_muscle_image_file != "" && file_exists("../$get_muscle_image_path/$get_muscle_image_file")){
										echo"../image.php?width=132&amp;height=132&amp;image=/$get_muscle_image_path/$get_muscle_image_file";
									}
									else{
										echo"_design/gfx/no_thumb.png";
									}
									echo"\" alt=\"$get_muscle_latin_name\" style=\"margin-bottom: 5px;\" /></a><br />
					
									<a href=\"index.php?open=$open&amp;page=view_muscle&amp;main_muscle_group_id=$main_muscle_group_id&amp;sub_muscle_group_id=$sub_muscle_group_id&amp;muscle_id=$get_muscle_id&amp;editor_language=$editor_language\">$get_muscle_translation_simple_name</a>
									
									</p>
								  </td>
								 </tr>
								";
								$layout = -1;
							}
							$layout++;
						} // while

						echo"
						</table>
				<!-- //All muscles in sub category -->
			</div>
		<!-- //Left and right -->
		";
	}  // main found
} // sub found

?>