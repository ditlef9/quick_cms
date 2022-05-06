<?php 
/**
*
* File: exercises/new_equipment.php
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


/*- Tables ---------------------------------------------------------------------------- */
$t_search_engine_index 		= $mysqlPrefixSav . "search_engine_index";
$t_search_engine_access_control = $mysqlPrefixSav . "search_engine_access_control";


/*- Variables ------------------------------------------------------------------------- */
$tabindex = 0;
$l_mysql = quote_smart($link, $l);


/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_new_equipment - $l_exercises";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
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


	if($process == "1"){

		$inp_equipment_title = $_POST['inp_equipment_title'];
		$inp_equipment_title = output_html($inp_equipment_title);
		$inp_equipment_title_mysql = quote_smart($link, $inp_equipment_title);
		if(empty($inp_equipment_title)){
			$url = "new_equipment.php?l=$l";
			$url = $url . "&ft=error&fm=missing_title";
			header("Location: $url");
			exit;
		}
		
		$inp_equipment_title_clean = clean($inp_equipment_title);
		$inp_equipment_title_clean_mysql = quote_smart($link, $inp_equipment_title_clean);

		$inp_equipment_language = $_POST['inp_equipment_language'];
		$inp_equipment_language = output_html($inp_equipment_language);
		$inp_equipment_language_mysql = quote_smart($link, $inp_equipment_language);
		$l = $inp_equipment_language;
		if(empty($inp_equipment_language)){
			$url = "new_equipment.php?l=$l";
			$url = $url . "&ft=error&fm=missing_language";
			header("Location: $url");
			exit;
		}


		// Check if it alreaddy exsits
		$query = "SELECT equipment_id, equipment_title FROM $t_exercise_equipments WHERE equipment_title=$inp_equipment_title_mysql AND equipment_language=$inp_equipment_language_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_equipment_id, $get_equipment_title) = $row;

		if($get_equipment_id == ""){
			// It does not exists

			$datetime = date("Y-m-d H:i:s");
			$datetime_saying = date("j. M Y H:i");

			$inp_user_ip = $_SERVER['REMOTE_ADDR'];
			$inp_user_ip = output_html($inp_user_ip);
			$inp_user_ip_mysql = quote_smart($link, $inp_user_ip);

			mysqli_query($link, "INSERT INTO $t_exercise_equipments
			(equipment_id, equipment_title, equipment_title_clean, equipment_user_id, equipment_language, equipment_muscle_group_id_main, equipment_muscle_group_id_sub, equipment_created_datetime, equipment_updated_datetime, equipment_user_ip, equipment_uniqe_hits, equipment_uniqe_hits_ip_block, equipment_likes, equipment_dislikes, equipment_rating, equipment_number_of_comments) 
			VALUES 
			(NULL, $inp_equipment_title_mysql, $inp_equipment_title_clean_mysql, $my_user_id_mysql, $inp_equipment_language_mysql, '0', '0', '$datetime', 
			'$datetime', $inp_user_ip_mysql, '0', '', '0', '0', '0', '0')
			")
			or die(mysqli_error($link));

			// Get ID
			$query = "SELECT equipment_id, equipment_title FROM $t_exercise_equipments WHERE equipment_title=$inp_equipment_title_mysql AND equipment_language=$l_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_equipment_id, $get_equipment_title) = $row;


			// Search engine
			$inp_index_title = "$inp_equipment_title | $l_equipments";
			$inp_index_title_mysql = quote_smart($link, $inp_index_title);

			$inp_index_url = "exercises/view_equipment.php?equipment_id=$get_equipment_id";
			$inp_index_url_mysql = quote_smart($link, $inp_index_url);

			$inp_index_short_description = "";
			$inp_index_short_description_mysql = quote_smart($link, $inp_index_short_description);

			$inp_index_keywords = "";
			$inp_index_keywords_mysql = quote_smart($link, "$inp_index_keywords");

			$inp_index_module_name_mysql = quote_smart($link, "exercises");
			$inp_index_module_part_name_mysql = quote_smart($link, "equipments");
			$inp_index_reference_name_mysql = quote_smart($link, "equipment_id");
			$inp_index_reference_id_mysql = quote_smart($link, "$get_equipment_id");

			$query_exists = "SELECT index_id FROM $t_search_engine_index WHERE index_module_name=$inp_index_module_name_mysql AND index_reference_name=$inp_index_reference_name_mysql AND index_reference_id=$inp_index_reference_id_mysql";
			$result_exists = mysqli_query($link, $query_exists);
			$row_exists = mysqli_fetch_row($result_exists);
			list($get_index_id) = $row_exists;
			if($get_index_id == ""){
				// Insert
				mysqli_query($link, "INSERT INTO $t_search_engine_index 
				(index_id, index_title, index_url, index_short_description, index_keywords, 
				index_module_name, index_module_part_name, index_module_part_id, index_reference_name, index_reference_id, 
				index_has_access_control, index_is_ad, index_created_datetime, index_created_datetime_print, index_language, 
				index_unique_hits) 
				VALUES 
				(NULL, $inp_index_title_mysql, $inp_index_url_mysql, $inp_index_short_description_mysql, $inp_index_keywords_mysql, 
				$inp_index_module_name_mysql, $inp_index_module_part_name_mysql, '0', $inp_index_reference_name_mysql, $inp_index_reference_id_mysql,
				'0', 0,  '$datetime', '$datetime_saying', $inp_equipment_language_mysql,
				0)")
				or die(mysqli_error($link));
			}


			// Header
			$ft = "success";
			$fm = "new_equipment_created";
				
			$url = "new_equipment_step_2_categorization.php?equipment_id=$get_equipment_id&l=$l";
			$url = $url . "&ft=$ft&fm=$fm";
			header("Location: $url");
			exit;
				
		}
		else{
			// It alreaddy exists,
			// use exising

				
			// Header
			$ft = "success";
			$fm = "the_equipment_already_exists";
				
			$url = "new_equipment.php?l=$l";
			$url = $url . "&ft=$ft&fm=$fm";
			header("Location: $url");
			exit;
		}
	}

	
	echo"
	<h1>$l_new_equipment</h1>
	

	<!-- Focus -->
		<script>
			\$(document).ready(function(){
				\$('[name=\"inp_equipment_title\"]').focus();
			});
		</script>
	<!-- //Focus -->

	<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
	<!-- //Feedback -->

	<!-- Form -->
		<form method=\"post\" action=\"new_equipment.php?l=$l&amp;process=1\" enctype=\"multipart/form-data\">


		<p><b>$l_title*:</b><br />
		<input type=\"text\" name=\"inp_equipment_title\" size=\"40\"  tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		<p><b>$l_language*:</b><br />
		<select name=\"inp_equipment_language\"  tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">\n";
		$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;
			$flag_path 	= "$root/_webdesign/images/flags/16x16/$get_language_active_flag" . "_16x16.png";
				
			echo"	<option value=\"$get_language_active_iso_two\"";if($l == "$get_language_active_iso_two"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";
		}
		echo"
		</select>
		</p>

		<p>
		<input type=\"submit\" value=\"$l_next\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		</form>
	<!-- //Form -->
	";
}
else{
	echo"
	<h1>
	<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=$root/exercises/new_exercise.php\">
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>