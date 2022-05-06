<?php
/**
*
* File: _admin/_inc/food/titles.php
* Version 11:57 16.12.2021
* Copyright (c) 2008-2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- MySQL Tables -------------------------------------------------- */
$t_users 	 		= $mysqlPrefixSav . "users";
$t_users_profile 		= $mysqlPrefixSav . "users_profile";
$t_users_friends 		= $mysqlPrefixSav . "users_friends";
$t_users_friends_requests 	= $mysqlPrefixSav . "users_friends_requests";
$t_users_profile		= $mysqlPrefixSav . "users_profile";
$t_users_profile_photo 		= $mysqlPrefixSav . "users_profile_photo";
$t_users_status 		= $mysqlPrefixSav . "users_status";
$t_users_status_comments 	= $mysqlPrefixSav . "users_status_comments";
$t_users_status_comments_likes 	= $mysqlPrefixSav . "users_status_comments_likes";
$t_users_status_likes 		= $mysqlPrefixSav . "users_status_likes";

$t_food_categories		  = $mysqlPrefixSav . "food_categories";
$t_food_categories_translations	  = $mysqlPrefixSav . "food_categories_translations";
$t_food_index			  = $mysqlPrefixSav . "food_index";
$t_food_index_stores		  = $mysqlPrefixSav . "food_index_stores";
$t_food_index_ads		  = $mysqlPrefixSav . "food_index_ads";
$t_food_index_tags		  = $mysqlPrefixSav . "food_index_tags";
$t_food_index_prices		  = $mysqlPrefixSav . "food_index_prices";
$t_food_index_contents		  = $mysqlPrefixSav . "food_index_contents";
$t_food_stores		  	  = $mysqlPrefixSav . "food_stores";
$t_food_prices_currencies	  = $mysqlPrefixSav . "food_prices_currencies";
$t_food_favorites 		  = $mysqlPrefixSav . "food_favorites";
$t_food_measurements	 	  = $mysqlPrefixSav . "food_measurements";
$t_food_measurements_translations = $mysqlPrefixSav . "food_measurements_translations";
$t_food_titles 			  = $mysqlPrefixSav . "food_titles";


/*- Variables -------------------------------------------------------------------------- */



if($process == "1"){

	// Get active languages
	$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_flag_path_16x16, language_active_flag_16x16 FROM $t_languages_active";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_flag_path_16x16, $get_language_active_flag_16x16) = $row;

		$inp_value = $_POST["inp_value_$get_language_active_iso_two"];
		$inp_value = output_html($inp_value);
		$inp_value_mysql = quote_smart($link, $inp_value);


		$language_mysql = quote_smart($link, $get_language_active_iso_two);

		mysqli_query($link, "UPDATE $t_food_titles SET 
 					title_value=$inp_value_mysql WHERE title_language=$language_mysql") or die(mysqli_error());

	} // while languages

	$url = "index.php?open=$open&page=$page&editor_language=$editor_language&ft=success&fm=changes_saved";
	header("Location: $url");
	exit;

} // process
echo"
<h1>Titles</h1>


<!-- Feedback -->
	";
	if($ft != ""){
		$fm = ucfirst($fm);
		$fm = str_replace("_", " ", $fm);
		echo"<div class=\"$ft\"><span>$fm</span></div>";
	}
	echo"
<!-- //Feedback -->

	<!-- Languages and titles -->

		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th>
			<span>Language</span>
		   </th>
		   <th>
			<span>Translation</span>
		   </th>
		  </tr>
		 </thead>
		 <tbody>\n";

		// Get active languages
		$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_flag_path_16x16, language_active_flag_16x16 FROM $t_languages_active";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_flag_path_16x16, $get_language_active_flag_16x16) = $row;

			// Translation
			$language_mysql = quote_smart($link, $get_language_active_iso_two);
			$query_translation = "SELECT title_id, title_language, title_value FROM $t_food_titles WHERE title_language=$language_mysql";
			$result_translation = mysqli_query($link, $query_translation);
			$row_translation = mysqli_fetch_row($result_translation);
			list($get_title_id, $get_title_language, $get_title_value) = $row_translation;
			if($get_title_id == ""){
				// Translation
				include("_translations/site/$get_language_active_iso_two/food/ts_index.php");
				$inp_title_mysql = quote_smart($link, $l_food);
				mysqli_query($link, "INSERT INTO $t_food_titles (title_id, title_language, title_value) VALUES
					(NULL, $language_mysql, $inp_title_mysql)") or die(mysqli_error());

				echo"
				<meta http-equiv=\"refresh\" content=\"0;URL='index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l&amp;ft=info&amp;fm=inserted_translations'\" />
				";
			}

			echo"
			 <tr>
			  <td>
				<span><img src=\"../$get_language_active_flag_path_16x16/$get_language_active_flag_16x16\" alt=\"$get_language_active_flag_16x16\" /> $get_language_active_name</span>
			  </td>
			  <td>
				<span><input type=\"text\" name=\"inp_value_$get_language_active_iso_two\" value=\"$get_title_value\" size=\"25\" /></span>
			  </td>
			 </tr>";
		}
		echo"
		 </tbody>
		</table>

		<p><input type=\"submit\" value=\"Save\" class=\"btn_default\" /></p>
	<!-- //Languages and titles -->
";
?>