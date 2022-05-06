<?php 
/**
*
* File: recipes/rate_recipe.php
* Version 1.0.0
* Date 13:43 18.11.2017
* Copyright (c) 2011-2017 Localhost
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

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/recipes/ts_index.php");


/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['recipe_id'])) {
	$recipe_id = $_GET['recipe_id'];
	$recipe_id = strip_tags(stripslashes($recipe_id));
}
else{
	$recipe_id = "";
}



// Select
$recipe_id_mysql = quote_smart($link, $recipe_id);
$query = "SELECT recipe_id, recipe_user_id, recipe_title, recipe_category_id, recipe_language, recipe_introduction, recipe_directions, recipe_image_path, recipe_image, recipe_thumb, recipe_video, recipe_date, recipe_time, recipe_cusine_id, recipe_season_id, recipe_occasion_id, recipe_marked_as_spam, recipe_unique_hits, recipe_unique_hits_ip_block, recipe_user_ip, recipe_notes, recipe_password FROM $t_recipes WHERE recipe_id=$recipe_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_recipe_id, $get_recipe_user_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_language, $get_recipe_introduction, $get_recipe_directions, $get_recipe_image_path, $get_recipe_image, $get_recipe_thumb, $get_recipe_video, $get_recipe_date, $get_recipe_time, $get_recipe_cusine_id, $get_recipe_season_id, $get_recipe_occasion_id, $get_recipe_marked_as_spam, $get_recipe_unique_hits, $get_recipe_unique_hits_ip_block, $get_recipe_user_ip, $get_recipe_notes, $get_recipe_password) = $row;



/*- Headers ---------------------------------------------------------------------------------- */
if($get_recipe_id == ""){
	$website_title = "Server error 404";
}
else{
	$website_title = "$get_recipe_title - $l_recipes";
}
include("$root/_webdesign/header.php");

/*- Content ---------------------------------------------------------------------------------- */

if($get_recipe_id == ""){
	echo"
	<h1>Recipe not found</h1>

	<p>
	The recipe you are trying to edit was not found.
	</p>

	<p>
	<a href=\"index.php\">Back</a>
	</p>
	";
}
else{

	// Logged in?
	if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
		// Get my user
		$my_user_id = $_SESSION['user_id'];
		$my_user_id = output_html($my_user_id);
		$my_user_id_mysql = quote_smart($link, $my_user_id);
		$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_rank) = $row;



		// Rating
		$query_rating = "SELECT rating_id, rating_recipe_id, rating_1, rating_2, rating_3, rating_4, rating_5, rating_total_votes, rating_average, rating_popularity, rating_ip_block FROM $t_recipes_rating WHERE rating_recipe_id='$get_recipe_id'";
		$result_rating = mysqli_query($link, $query_rating);
		$row_rating = mysqli_fetch_row($result_rating);
		list($get_rating_id, $get_rating_recipe_id, $get_rating_1, $get_rating_2, $get_rating_3, $get_rating_4, $get_rating_5, $get_rating_total_votes, $get_rating_average, $get_rating_popularity, $get_rating_ip_block) = $row_rating;
		if($get_rating_id == ""){
			// Create rating
			mysqli_query($link, "INSERT INTO $t_recipes_rating
			(rating_id, rating_recipe_id, rating_1, rating_2, rating_3, rating_4, rating_5, rating_total_votes, rating_average, rating_popularity, rating_ip_block) 
			VALUES 
			(NULL, '$get_recipe_id', '0', '0', '0', '0', '0', '0', '0', '0', '')")
			or die(mysqli_error($link));
			
		}
		if($get_rating_average == ""){
			$get_rating_average = 0;
		}


		// Get rating
		if(isset($_GET['stars'])) {
			$stars = $_GET['stars'];
			$stars = strip_tags(stripslashes($stars));

			if(is_numeric($stars)){
				if($stars > 0 && $stars < 6){


					// Check IP block
					//$inp_ip = $_SERVER['REMOTE_ADDR'];
					//$inp_ip = output_html($inp_ip);


					$rating_ip_block_array = explode("\n", $get_rating_ip_block);
					$rating_ip_block_array_size = sizeof($rating_ip_block_array);
	
					if($rating_ip_block_array_size > 30){
						$rating_ip_block_array_size = 20;
					}
	
					$has_voted_before = 0;

					for($x=0;$x<$rating_ip_block_array_size;$x++){
						if($rating_ip_block_array[$x] == "$get_my_user_id"){
							$has_voted_before = 1;

							$url = "view_recipe.php?recipe_id=$recipe_id&rating_ft=error&rating_fm=you_have_alreaddy_voted#rating_feedback";
							header("Location: $url");
							exit;

						}
					}
	
					if($has_voted_before == 0){
						$inp_rating_ip_block_array = $get_my_user_id . "\n" . $get_rating_ip_block;
						$inp_rating_ip_block_array_mysql = quote_smart($link, $inp_rating_ip_block_array);
					

						if($get_rating_1 == ""){
							$get_rating_1 = 0;
						}
						if($get_rating_2 == ""){
							$get_rating_2 = 0;
						}
						if($get_rating_3 == ""){
							$get_rating_3 = 0;
						}
						if($get_rating_4 == ""){
							$get_rating_4 = 0;
						}
						if($get_rating_5 == ""){
							$get_rating_5 = 0;
						}

						if($stars == 1){	
							$get_rating_1 = $get_rating_1+1;
						}
						elseif($stars == 2){	
							$get_rating_2 = $get_rating_2+1;
						}
						elseif($stars == 3){	
							$get_rating_3 = $get_rating_3+1;
						}
						elseif($stars == 4){	
							$get_rating_4= $get_rating_4+1;
						}
						elseif($stars == 5){	
							$get_rating_5 = $get_rating_5+1;
						}

						$inp_rating_total_votes = $get_rating_1+$get_rating_2+$get_rating_3+$get_rating_4+$get_rating_5;
						$inp_rating_average     = round((($get_rating_1*1) + ($get_rating_2*2) + ($get_rating_3*3) + ($get_rating_4*4) + ($get_rating_5*5))/$inp_rating_total_votes);


						$positive = $get_rating_4+$get_rating_5;
						$negative = $get_rating_1+$get_rating_2;
						$total    = $positive+$negative;
						$inp_rating_popularity  = round(($positive/$total*100));
						
						$result = mysqli_query($link, "UPDATE $t_recipes_rating SET rating_1=$get_rating_1, rating_2=$get_rating_2, rating_3=$get_rating_3, rating_4=$get_rating_4, rating_5=$get_rating_5, rating_total_votes=$inp_rating_total_votes, rating_average=$inp_rating_average , rating_popularity=$inp_rating_popularity, rating_ip_block=$inp_rating_ip_block_array_mysql WHERE rating_recipe_id='$get_recipe_id'") or die(mysqli_error($link));
					

						$url = "view_recipe.php?recipe_id=$recipe_id&rating_ft=success&rating_fm=thank_you#rating_feedback";
						header("Location: $url");
						exit;
					}
					



				}
				else{
					echo"<p>Stars out of range.</p>"; die;
				}
			}
			else{
				echo"<p>Illeagal stars.</p>"; die;
			}
		}
		else{
			echo"<p>Missing stars.</p>"; die;
		}
	}
	else{
		echo"
		<p>Please log in to rate</p>
		";
	}

} // recipe found

/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>