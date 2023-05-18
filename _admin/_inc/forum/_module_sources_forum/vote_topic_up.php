<?php 
/**
*
* File: forum/vote_topic_up.php
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

/*- Forum config ------------------------------------------------------------------------ */
include("$root/_admin/_data/forum.php");
include("_include_tables.php");

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/forum/ts_index.php");


/*- Variables ------------------------------------------------------------------------- */
$tabindex = 0;
$l_mysql = quote_smart($link, $l);




/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['topic_id'])){
	$topic_id = $_GET['topic_id'];
	$topic_id = output_html($topic_id);
}
else{
	$topic_id = "";
}
if(isset($_GET['show'])) {
	$show = $_GET['show'];
	$show = strip_tags(stripslashes($show));
}
else{
	$show = "";
}

// Get topic
$topic_id_mysql = quote_smart($link, $topic_id);
$query = "SELECT topic_id, topic_user_id, topic_user_alias, topic_user_image, topic_language, topic_title, topic_text, topic_created, topic_updated, topic_updated_translated, topic_replies, topic_views, topic_views_ip_block, topic_likes, topic_dislikes, topic_rating, topic_likes_ip_block, topic_user_ip FROM $t_forum_topics WHERE topic_id=$topic_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_topic_id, $get_current_topic_user_id, $get_current_topic_user_alias, $get_current_topic_user_image, $get_current_topic_language, $get_current_topic_title, $get_current_topic_text, $get_current_topic_created, $get_current_topic_updated, $get_current_topic_updated_translated, $get_current_topic_replies, $get_current_topic_views, $get_current_topic_views_ip_block, $get_current_topic_likes, $get_current_topic_dislikes, $get_current_topic_rating, $get_current_topic_likes_ip_block, $get_current_topic_user_ip) = $row;

if($get_current_topic_id == ""){
	echo"<p>Topic post not found.</p>";
	
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

			// Get my photo
			$query = "SELECT photo_id, photo_destination FROM $t_users_profile_photo WHERE photo_user_id='$get_my_user_id' AND photo_profile_image='1'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_my_photo_id, $get_my_photo_destination) = $row;

		// Ready input
		$inp_topic_likes_ip_block = $get_my_user_id . ":1";

		// Have i already voted?
		$voted_array = explode("\n", $get_current_topic_likes_ip_block);
		$voted_array_size = sizeof($voted_array);
		
		for($x=0;$x<$voted_array_size;$x++){
			$temp = explode(":", $voted_array[$x]);
			$user_id = $temp[0];

			if(isset($temp[1])){
				$like_dislike = $temp[1];
				if($user_id == "$get_my_user_id" && $like_dislike == "1"){
					$url = "view_topic.php?topic_id=$topic_id&show=$show&l=$l&ft=error&fm=you_have_already_voted";
					header("Location: $url");
					exit;
				}
	
				if($x > 50){
					break;
				}

				// Add to input
				$inp_topic_likes_ip_block = $inp_topic_likes_ip_block . "\n" . $user_id  . ":" . $like_dislike;
			}
		}

		$inp_topic_likes_ip_block_mysql = quote_smart($link, $inp_topic_likes_ip_block);

		// Vote
		$inp_topic_likes = $get_current_topic_likes + 1;
		$inp_topic_rating = $get_current_topic_rating + 1;

		$result = mysqli_query($link, "UPDATE $t_forum_topics SET 
						topic_likes=$inp_topic_likes,
						topic_rating=$inp_topic_rating,
						topic_likes_ip_block=$inp_topic_likes_ip_block_mysql
		 WHERE topic_id=$topic_id_mysql");



			// Top users monthly
			$year = date("Y");
			$month = date("m");
			$inp_my_user_alias_mysql = quote_smart($link, $get_my_user_alias);
			$inp_my_photo_destination_mysql = quote_smart($link, $get_my_photo_destination);

			$query = "SELECT top_monthly_id, top_monthly_user_id, top_monthly_year, top_monthly_month, top_monthly_topics, top_monthly_replies, top_monthly_times_voted, top_monthly_points FROM $t_forum_top_users_monthly WHERE top_monthly_user_id=$my_user_id_mysql AND top_monthly_year=$year AND top_monthly_month=$month";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_top_monthly_id, $get_top_monthly_user_id, $get_top_monthly_year, $get_top_monthly_month, $get_top_monthly_topics, $get_top_monthly_replies, $get_top_monthly_times_voted, $get_top_monthly_points) = $row;
			if($get_top_monthly_id == ""){
				// First time I posted this month
				mysqli_query($link, "INSERT INTO $t_forum_top_users_monthly 
				(top_monthly_id, top_monthly_user_id, top_monthly_year, top_monthly_month, top_monthly_topics, top_monthly_replies, top_monthly_times_voted, top_monthly_points, top_monthly_user_alias, top_monthly_user_image) 
				VALUES 
				(NULL, $my_user_id_mysql, '$year', '$month', '0', '1', '0', 1, $inp_my_user_alias_mysql, $inp_my_photo_destination_mysql)")
				or die(mysqli_error($link));
			}
			else{
				$inp_top_monthly_times_voted = $get_top_monthly_times_voted + 1;
				$inp_top_monthly_points = $get_top_monthly_points + 1;

				$result = mysqli_query($link, "UPDATE $t_forum_top_users_monthly SET top_monthly_times_voted=$inp_top_monthly_times_voted, top_monthly_points=$inp_top_monthly_points, top_monthly_user_alias=$inp_my_user_alias_mysql, top_monthly_user_image=$inp_my_photo_destination_mysql WHERE top_monthly_id='$get_top_monthly_id'");

			}

			// Top users yearly
			$query = "SELECT top_yearly_id, top_yearly_user_id, top_yearly_topics, top_yearly_replies, top_yearly_times_voted, top_yearly_year, top_yearly_points FROM $t_forum_top_users_yearly WHERE top_yearly_user_id=$my_user_id_mysql AND top_yearly_year=$year";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_top_yearly_id, $get_top_yearly_user_id, $get_top_yearly_topics, $get_top_yearly_replies, $get_top_yearly_times_voted, $get_top_yearly_year, $get_top_yearly_points) = $row;
			if($get_top_yearly_id == ""){
				// First time I posted this month
				mysqli_query($link, "INSERT INTO $t_forum_top_users_yearly 
				(top_yearly_id, top_yearly_user_id, top_yearly_topics, top_yearly_replies, top_yearly_times_voted, top_yearly_year, top_yearly_points, top_yearly_user_alias, top_yearly_user_image) 
				VALUES 
				(NULL, $my_user_id_mysql, '0', '0', '1', '$year', 1, $inp_my_user_alias_mysql, $inp_my_photo_destination_mysql)")
				or die(mysqli_error($link));
			}
			else{
				$inp_top_yearly_times_voted = $get_top_yearly_times_voted + 1;
				$inp_top_yearly_points = $get_top_yearly_points + 1;

				$result = mysqli_query($link, "UPDATE $t_forum_top_users_yearly SET top_yearly_times_voted=$inp_top_yearly_times_voted, top_yearly_points=$inp_top_yearly_points, top_yearly_user_alias=$inp_my_user_alias_mysql, top_yearly_user_image=$inp_my_photo_destination_mysql WHERE top_yearly_id='$get_top_yearly_id'") or die(mysqli_error($link));
			}



			// Top users all time
			$query = "SELECT top_all_id, top_all_user_id, top_all_topics, top_all_replies, top_all_times_voted, top_all_points FROM $t_forum_top_users_all_time WHERE top_all_user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_top_all_id, $get_top_all_user_id, $get_top_all_topics, $get_top_all_replies, $get_top_all_times_voted, $get_top_all_points) = $row;
			if($get_top_all_id == ""){
				// First time I posted at all
				mysqli_query($link, "INSERT INTO $t_forum_top_users_all_time
				(top_all_id, top_all_user_id, top_all_topics, top_all_replies, top_all_times_voted, top_all_points, top_all_user_alias, top_all_user_image) 
				VALUES 
				(NULL, $my_user_id_mysql, '0', '0', '1', 1, $inp_my_user_alias_mysql, $inp_my_photo_destination_mysql)")
				or die(mysqli_error($link));
			}
			else{
				$inp_top_all_times_voted = $get_top_all_times_voted + 1;
				$inp_top_all_points = $get_top_all_points + 1;

				$result = mysqli_query($link, "UPDATE $t_forum_top_users_all_time SET top_all_times_voted=$inp_top_all_times_voted, top_all_points=$inp_top_all_points, top_all_user_alias=$inp_my_user_alias_mysql, top_all_user_image=$inp_my_photo_destination_mysql WHERE top_all_id='$get_top_all_id'");

			}


		$url = "view_topic.php?topic_id=$topic_id&show=$show&l=$l";
		header("Location: $url");
		exit;
	}
	else{
		echo"
		<p>Not logged in.</p>
		";
	}
} //  post found



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>