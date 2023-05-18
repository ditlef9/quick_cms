<?php 
/**
*
* File: recipes/submit_recipe_step_4_directions_autosave.php
* Version 1.0.0
* Date 14:18 13.03.2022
* Copyright (c) 2022 S. Ditlefsen
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
include("_tables.php");

/*- Functions ------------------------------------------------------------------------- */
include("$root/_admin/_functions/encode_national_letters.php");
include("$root/_admin/_functions/decode_national_letters.php");

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/recipes/ts_index.php");


/*- Tables ------------------------------------------------------------------------ */
$t_recipes_images			= $mysqlPrefixSav . "recipes_images";

/*- Variables ------------------------------------------------------------------------ */
if(isset($_POST['recipe_id'])){
	$recipe_id = $_POST['recipe_id'];
	$recipe_id = output_html($recipe_id);
	if(!(is_numeric($recipe_id))){
		echo"Recipe not numeric";
		die;
	}
}
else{
	echo"Missing recipe";
	die;
}

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


	// Get recipe
	$recipe_id_mysql = quote_smart($link, $recipe_id);

	$query = "SELECT recipe_id, recipe_user_id, recipe_title, recipe_category_id, recipe_language, recipe_country, recipe_introduction, recipe_directions, recipe_image_path, recipe_image_h_a, recipe_image_h_b, recipe_image_v_a, recipe_thumb_h_a_278x156, recipe_thumb_h_b_278x156, recipe_video_h, recipe_video_v, recipe_date, recipe_date_saying, recipe_time, recipe_cusine_id, recipe_season_id, recipe_occasion_id, recipe_marked_as_spam, recipe_unique_hits, recipe_unique_hits_ip_block, recipe_comments, recipe_times_favorited, recipe_user_ip, recipe_notes, recipe_password, recipe_last_viewed, recipe_age_restriction, recipe_published FROM $t_recipes WHERE recipe_id=$recipe_id_mysql AND recipe_user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_recipe_id, $get_recipe_user_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_language, $get_recipe_country, $get_recipe_introduction, $get_recipe_directions, $get_recipe_image_path, $get_recipe_image_h_a, $get_recipe_image_h_b, $get_recipe_image_v_a, $get_recipe_thumb_h_a_278x156, $get_recipe_thumb_h_b_278x156, $get_recipe_video_h, $get_recipe_video_v, $get_recipe_date, $get_recipe_date_saying, $get_recipe_time, $get_recipe_cusine_id, $get_recipe_season_id, $get_recipe_occasion_id, $get_recipe_marked_as_spam, $get_recipe_unique_hits, $get_recipe_unique_hits_ip_block, $get_recipe_comments, $get_recipe_times_favorited, $get_recipe_user_ip, $get_recipe_notes, $get_recipe_password, $get_recipe_last_viewed, $get_recipe_age_restriction, $get_recipe_published) = $row;

	if($get_recipe_id == ""){
		echo"
		<h1>Server error</h1>

		<p>
		Recipe not found.
		</p>
		";
		die;
	}
	else{
		$inp_recipe_directions = $_POST['inp_recipe_directions'];
		if(empty($inp_recipe_directions)){
			echo"directions_cant_be_empty";
			exit;
		}

		require_once "$root/_admin/_functions/htmlpurifier/HTMLPurifier.auto.php";
		$config = HTMLPurifier_Config::createDefault();
		$purifier = new HTMLPurifier($config);

		if($get_user_rank == "admin" OR $get_user_rank == "moderator" OR $get_user_rank == "editor"){
		}
		elseif($get_user_rank == "trusted"){
		}
		else{
			// p, ul, li, b
			$config->set('HTML.Allowed', 'p,b,strong,a[href],i,ul,li');
			$inp_recipe_directions = $purifier->purify($inp_recipe_directions);
		}
		$inp_recipe_directions = encode_national_letters($inp_recipe_directions);

		$sql = "UPDATE $t_recipes SET recipe_directions=? WHERE recipe_id=$get_recipe_id";
		$stmt = $link->prepare($sql);
		$stmt->bind_param("s", $inp_recipe_directions);
		$stmt->execute();
		if ($stmt->errno) {
			echo "FAILURE!!! " . $stmt->error; die;
		}

		$hour_minute = date("H:i");
		echo"$hour_minute";
	}		
}
else{
	echo"Not logged in";
}

?>