<?php
/**
*
* File: forum/index.php
* Version 1.0.0.
* Date 19:42 08.02.2018
* Copyright (c) 2008-2018 Sindre Andre Ditlefsen
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
include("_include_tables.php");

/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);



if(isset($_GET['order_by'])) {
	$order_by = $_GET['order_by'];
	$order_by = strip_tags(stripslashes($order_by));
}
else{
	$order_by = "";
}
if(isset($_GET['order_method'])) {
	$order_method = $_GET['order_method'];
	$order_method = strip_tags(stripslashes($order_method));
}
else{
	$order_method = "";
}

/*- Title ---------------------------------------------------------------------------------- */
$query_t = "SELECT title_id, title_language, title_value FROM $t_forum_titles WHERE title_language=$l_mysql";
$result_t = mysqli_query($link, $query_t);
$row_t = mysqli_fetch_row($result_t);
list($get_current_title_id, $get_current_title_language, $get_current_title_value) = $row_t;


/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$get_current_title_value";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");

/* Settings */
$viewMethodSav = "chat"; // chat or list


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
}

// My ip
$my_ip = $_SERVER['REMOTE_ADDR'];
$my_ip = output_html($my_ip);
$my_ip_mysql = quote_smart($link, $my_ip);

echo"
<!-- Headline and language -->
	<h1>$get_current_title_value</h1>
<!-- //Headline and language -->


<!-- Hall of fame -->
	
	<div class=\"hall_of_fame_wrapper\">
	
		<a href=\"hall_of_fame.php?l=$l\"><h2>$l_hall_of_fame</h2></a>
		<div class=\"hall_of_fame_row\">
		";
		$year = date("Y");
		$month = date("m");
		$query_w = "SELECT top_yearly_id, top_yearly_user_id, top_yearly_year, top_yearly_topics, top_yearly_replies, top_yearly_times_voted, top_yearly_points, top_yearly_user_alias, top_yearly_user_image FROM $t_forum_top_users_yearly WHERE top_yearly_year=$year ORDER BY top_yearly_points DESC LIMIT 0,8";
		$result_w = mysqli_query($link, $query_w);
		while($row_w = mysqli_fetch_row($result_w)) {
			list($get_top_yearly_id, $get_top_yearly_user_id, $get_top_yearly_year, $get_top_yearly_topics, $get_top_yearly_replies, $get_top_yearly_times_voted, $get_top_yearly_points, $get_top_yearly_user_alias, $get_top_yearly_user_image) = $row_w;


			// Avatar
			$inp_new_x = 40; // 950
			$inp_new_y = 40; // 640
			if(file_exists("$root/_uploads/users/images/$get_top_yearly_user_id/$get_top_yearly_user_image") && $get_top_yearly_user_image != ""){
				$thumb_full_path = "$root/_cache/user_" . $get_top_yearly_user_image . "-" . $inp_new_x . "x" . $inp_new_y . ".png";
				if(!(file_exists("$thumb_full_path"))){
					resize_crop_image($inp_new_x, $inp_new_y, "$root/_uploads/users/images/$get_top_yearly_user_id/$get_top_yearly_user_image", "$thumb_full_path");
				}
			
			}
			else{
				$thumb_full_path = "_gfx/avatar_blank_40.png";
			}


			echo"
			<div class=\"hall_of_fame_column\">
				<p>
				<a href=\"$root/users/view_profile.php?user_id=$get_top_yearly_user_id&amp;l=$l\"><img src=\"$thumb_full_path\" alt=\"$thumb_full_path\" /></a><br />
				<a href=\"$root/users/view_profile.php?user_id=$get_top_yearly_user_id&amp;l=$l\">$get_top_yearly_user_alias</a><br />
				<span class=\"hall_of_fame_points\">$get_top_yearly_points</span><br />
				$l_points_abbreviation_lowercase
				</p>
			</div>";
		}
		echo"
		</div> <!-- //row -->
	</div> <!-- //wrapper -->

<!-- //Hall of fame -->


<!-- Menu -->
	<p>
	<a href=\"new_topic.php?l=$l\" class=\"btn_default\">$l_new_topic</a>
	<a href=\"subscription.php?l=$l\" class=\"btn_default\">$l_subscription</a>
	</p>


	<div class=\"tabs\" style=\"margin-top: 10px;\">
		<ul>
			<li><a href=\"index.php?l=$l\""; if($action == ""){ echo" class=\"selected\""; } echo">$l_recent</a></li>
			<li><a href=\"index.php?action=unanswered&amp;l=$l\""; if($action == "unanswered"){ echo" class=\"selected\""; } echo">$l_unanswered</a></li>
			<li><a href=\"index.php?action=popular&amp;l=$l\""; if($action == "popular"){ echo" class=\"selected\""; } echo">$l_popular</a></li>
			<li><a href=\"index.php?action=unread&amp;l=$l\""; if($action == "unread"){ echo" class=\"selected\""; } echo">$l_new</a></li>
		</ul>
	</div>
	<div class=\"clear\" style=\"height: 15px;\"></div>
<!-- //Menu -->

";
if($action == ""){
	echo"
	<!-- Show topics -->

		<table style=\"width: 100%;\">
	";
	
	$x = 0;
	$query_w = "SELECT topic_id, topic_user_id, topic_user_alias, topic_user_image, topic_title, topic_updated_translated, topic_replies, topic_views, topic_solved FROM $t_forum_topics WHERE topic_language=$l_mysql ORDER BY topic_last_replied DESC";
	$result_w = mysqli_query($link, $query_w);
	while($row_w = mysqli_fetch_row($result_w)) {
		list($get_topic_id, $get_topic_user_id, $get_topic_user_alias, $get_topic_user_image, $get_topic_title, $get_topic_updated_translated, $get_topic_replies, $get_topic_views, $get_topic_solved) = $row_w;

		// Avatar
		$inp_new_x = 40; // 950
		$inp_new_y = 40; // 640
		if(file_exists("$root/_uploads/users/images/$get_topic_user_id/$get_topic_user_image") && $get_topic_user_image != ""){
			$thumb_full_path = "$root/_cache/user_" . $get_topic_user_image . "-" . $inp_new_x . "x" . $inp_new_y . ".png";
			if(!(file_exists("$thumb_full_path"))){
				resize_crop_image($inp_new_x, $inp_new_y, "$root/_uploads/users/images/$get_topic_user_id/$get_topic_user_image", "$thumb_full_path");
			}
			
		}
		else{
			$thumb_full_path = "_gfx/avatar_blank_40.png";
		}

		// Read
		if(isset($get_my_user_id)){
			$query = "SELECT topic_read_id FROM $t_forum_topics_read_by_user WHERE topic_read_topic_id=$get_topic_id AND topic_read_user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_topic_read_id) = $row;
		}
		else{
			$query = "SELECT topic_read_id FROM $t_forum_topics_read_by_ip WHERE topic_read_topic_id=$get_topic_id AND topic_read_ip=$my_ip_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_topic_read_id) = $row;
		}


		// Style
		if(isset($style) && $style == "topics_bodycell"){
			$style = "topics_subcell";
		}
		else{
			$style = "topics_bodycell";
		}

		// Icon = "; if($get_topic_read_id == "" OR $get_topic_read_id == "0"){ echo"_unread"; } if($get_topic_solved == "1"){ echo"_solved"; } echo"

		// Show all
		echo"
		 <tr>
		  <td class=\"$style\">
			<table style=\"width: 100%;\">
			 <tr>
			  <td style=\"vertical-align: center;\">
				<p class=\"p_forum_topic_title\">
				<a href=\"view_topic.php?topic_id=$get_topic_id&amp;l=$l\"  class=\"forum_topic_title\" "; if($get_topic_read_id == ""){ echo" style=\"font-weight: bold;\""; } echo">$get_topic_title</a><br />
				<span class=\"forum_meta_data\">
				$l_by <a href=\"$root/users/view_profile.php?user_id=$get_topic_user_id&amp;l=$l\" class=\"forum_meta_data\">$get_topic_user_alias</a>,
				$get_topic_updated_translated
				</span>
				</p>

				<p class=\"p_forum_replies_views_mobile_only\">
				$get_topic_replies $l_replies_lowercase
				&nbsp; &nbsp; &nbsp; 
				$get_topic_views $l_views_lowercase
				</p>
				
				";
				// Tags
				$tags = 0;
				$query_t = "SELECT topic_tag_id, topic_tag_title, topic_tag_clean FROM $t_forum_topics_tags WHERE topic_id=$get_topic_id";
				$result_t = mysqli_query($link, $query_t);
				while($row_t = mysqli_fetch_row($result_t)) {
					list($get_topic_tag_id, $get_topic_tag_title, $get_topic_tag_clean) = $row_t;
					
					if($get_topic_tag_title == ""){
						echo"<div class=\"info\"><p>Cleanup empty tag for topic id $get_topic_id</p></div>\n";
						mysqli_query($link, "DELETE FROM $t_forum_topics_tags WHERE topic_id=$get_topic_id") or die(mysqli_error($link));
					}

					$tag_title_clean_mysql = quote_smart($link, $get_topic_tag_clean);
					$query_tag = "SELECT tag_id, tag_icon_path, tag_icon_file_16 FROM $t_forum_tags_index WHERE tag_title_clean=$tag_title_clean_mysql";
					$result_tag = mysqli_query($link, $query_tag);
					$row_tag = mysqli_fetch_row($result_tag);
					list($get_tag_id, $get_tag_icon_path, $get_tag_icon_file_16) = $row_tag;
		
					// Tags Header
					if($tags == 0){
						echo"<p class=\"p_forum_tags\">\n";
					}

					// Tags body
					echo"
					<a href=\"open_tag.php?tag=$get_topic_tag_clean&amp;l=$l\" class=\"forum_a_tag\"";
					if(file_exists("$root/$get_tag_icon_path/$get_tag_icon_file_16") && $get_tag_icon_file_16 != ""){
						// echo"<img src=\"$root/$get_tag_icon_path/$get_tag_icon_file_16\" alt=\"$get_tag_icon_file_16\" /> ";
						echo" style=\"background-image: url('$root/$get_tag_icon_path/$get_tag_icon_file_16');background-repeat: no-repeat;background-position: center left 4px;padding-left:26px\" ";
					}
					echo">$get_topic_tag_title</a>
					";
					$tags++;
				}
				// Tags footer
				if($tags > 0){
					echo"
					</p>
					";
				}
				echo"
			  </td>
			  <td style=\"vertical-align: center;text-align: right;width: 20%\" class=\"td_forum_replies_views\">
				<p class=\"forum_meta_data\">
				$get_topic_replies $l_replies_lowercase<br />
				$get_topic_views $l_views_lowercase
				</p>
			  </td>
			  <td style=\"vertical-align: top;text-align: right;width: 50px;\">
				<p>
				<a href=\"$root/users/view_profile.php?user_id=$get_topic_user_id&amp;l=$l\"><img src=\"$thumb_full_path\" alt=\"$thumb_full_path\" /></a>
				</p>
			  </td>
			 </tr>
			</table>
		  </td>
		 </tr>
		";
	}

	echo"
		</table>
	<!-- //Show topics -->

	";
} // recent
elseif($action == "unanswered"){

	echo"
	<!-- Show topics -->

		<table style=\"width: 100%;\">
	";
	
	$x = 0;


	$query_w = "SELECT topic_id, topic_user_id, topic_user_alias, topic_user_image, topic_title, topic_updated_translated, topic_replies, topic_views, topic_solved FROM $t_forum_topics WHERE topic_language=$l_mysql AND topic_replies='0' ORDER BY topic_last_replied DESC";
	$result_w = mysqli_query($link, $query_w);
	while($row_w = mysqli_fetch_row($result_w)) {
		list($get_topic_id, $get_topic_user_id, $get_topic_user_alias, $get_topic_user_image, $get_topic_title, $get_topic_updated_translated, $get_topic_replies, $get_topic_views, $get_topic_solved) = $row_w;

		// Avatar
		$inp_new_x = 40; // 950
		$inp_new_y = 40; // 640
		if(file_exists("$root/_uploads/users/images/$get_topic_user_id/$get_topic_user_image") && $get_topic_user_image != ""){
			$thumb_full_path = "$root/_cache/user_" . $get_topic_user_image . "-" . $inp_new_x . "x" . $inp_new_y . ".png";
			if(!(file_exists("$thumb_full_path"))){
				resize_crop_image($inp_new_x, $inp_new_y, "$root/_uploads/users/images/$get_topic_user_id/$get_topic_user_image", "$thumb_full_path");
			}
			
		}
		else{
			$thumb_full_path = "_gfx/avatar_blank_40.png";
		} 

		// Read
		if(isset($get_my_user_id)){
			$query = "SELECT topic_read_id FROM $t_forum_topics_read_by_user WHERE topic_read_topic_id=$get_topic_id AND topic_read_user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_topic_read_id) = $row;
		}
		else{
			$query = "SELECT topic_read_id FROM $t_forum_topics_read_by_ip WHERE topic_read_topic_id=$get_topic_id AND topic_read_ip=$my_ip_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_topic_read_id) = $row;
		}


		// Style
		if(isset($style) && $style == "topics_bodycell"){
			$style = "topics_subcell";
		}
		else{
			$style = "topics_bodycell";
		}

		// Icon = "; if($get_topic_read_id == "" OR $get_topic_read_id == "0"){ echo"_unread"; } if($get_topic_solved == "1"){ echo"_solved"; } echo"

		// Show all
		echo"
		 <tr>
		  <td class=\"$style\">
			<table style=\"width: 100%;\">
			 <tr>
			  <td style=\"vertical-align: center;\">
				<p class=\"p_forum_topic_title\">
				<a href=\"view_topic.php?topic_id=$get_topic_id&amp;l=$l\"  class=\"forum_topic_title\" "; if($get_topic_read_id == ""){ echo" style=\"font-weight: bold;\""; } echo">$get_topic_title</a><br />
				<span class=\"forum_meta_data\">
				$l_by <a href=\"$root/users/view_profile.php?user_id=$get_topic_user_id&amp;l=$l\" class=\"forum_meta_data\">$get_topic_user_alias</a>,
				$get_topic_updated_translated
				</span>
				</p>

				<p class=\"p_forum_replies_views_mobile_only\">
				$get_topic_replies $l_replies_lowercase
				&nbsp; &nbsp; &nbsp; 
				$get_topic_views $l_views_lowercase
				</p>
				";
				// Tags
				echo"<p class=\"p_forum_tags\">\n";
				$query_t = "SELECT topic_tag_id, topic_tag_title, topic_tag_clean FROM $t_forum_topics_tags WHERE topic_id=$get_topic_id";
				$result_t = mysqli_query($link, $query_t);
				while($row_t = mysqli_fetch_row($result_t)) {
					list($get_topic_tag_id, $get_topic_tag_title, $get_topic_tag_clean) = $row_t;
					
					$tag_title_clean_mysql = quote_smart($link, $get_topic_tag_clean);
					$query_tag = "SELECT tag_id, tag_icon_path, tag_icon_file_16 FROM $t_forum_tags_index WHERE tag_title_clean=$tag_title_clean_mysql";
					$result_tag = mysqli_query($link, $query_tag);
					$row_tag = mysqli_fetch_row($result_tag);
					list($get_tag_id, $get_tag_icon_path, $get_tag_icon_file_16) = $row_tag;
		
					echo"
					<a href=\"open_tag.php?tag=$get_topic_tag_clean&amp;l=$l\" class=\"forum_a_tag\"";
					if(file_exists("$root/$get_tag_icon_path/$get_tag_icon_file_16") && $get_tag_icon_file_16 != ""){
						// echo"<img src=\"$root/$get_tag_icon_path/$get_tag_icon_file_16\" alt=\"$get_tag_icon_file_16\" /> ";
						echo" style=\"background-image: url('$root/$get_tag_icon_path/$get_tag_icon_file_16');background-repeat: no-repeat;background-position: center left 4px;padding-left:26px\" ";
					}
					echo">$get_topic_tag_title</a>
					";
				}
				echo"
				</p>
			  </td>
			  <td style=\"vertical-align: center;text-align: right;width: 20%\" class=\"td_forum_replies_views\">
				<p class=\"forum_meta_data\">
				$get_topic_replies $l_replies_lowercase<br />
				$get_topic_views $l_views_lowercase
				</p>
			  </td>
			  <td style=\"vertical-align: top;text-align: right;width: 50px;\">
				<p>
				<a href=\"$root/users/view_profile.php?user_id=$get_topic_user_id&amp;l=$l\"><img src=\"$thumb_full_path\" alt=\"$thumb_full_path\" /></a>
				</p>
			  </td>
			 </tr>
			</table>
		  </td>
		 </tr>
		";
	}

	echo"
	<!-- //Show topics -->
		</table>
	";
}
elseif($action == "popular"){
	echo"
	<!-- Show topics -->
		<table style=\"width: 100%;\">
	";
	
	$x = 0;


	$query_w = "SELECT topic_id, topic_user_id, topic_user_alias, topic_user_image, topic_title, topic_updated_translated, topic_replies, topic_views, topic_solved FROM $t_forum_topics WHERE topic_language=$l_mysql ORDER BY topic_last_replied DESC";
	$result_w = mysqli_query($link, $query_w);
	while($row_w = mysqli_fetch_row($result_w)) {
		list($get_topic_id, $get_topic_user_id, $get_topic_user_alias, $get_topic_user_image, $get_topic_title, $get_topic_updated_translated, $get_topic_replies, $get_topic_views, $get_topic_solved) = $row_w;

		// Avatar
		$inp_new_x = 40; // 950
		$inp_new_y = 40; // 640
		if(file_exists("$root/_uploads/users/images/$get_topic_user_id/$get_topic_user_image") && $get_topic_user_image != ""){
			$thumb_full_path = "$root/_cache/user_" . $get_topic_user_image . "-" . $inp_new_x . "x" . $inp_new_y . ".png";
			if(!(file_exists("$thumb_full_path"))){
				resize_crop_image($inp_new_x, $inp_new_y, "$root/_uploads/users/images/$get_topic_user_id/$get_topic_user_image", "$thumb_full_path");
			}
			
		}
		else{
			$thumb_full_path = "_gfx/avatar_blank_40.png";
		} 

		// Read
		if(isset($get_my_user_id)){
			$query = "SELECT topic_read_id FROM $t_forum_topics_read_by_user WHERE topic_read_topic_id=$get_topic_id AND topic_read_user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_topic_read_id) = $row;
		}
		else{
			$query = "SELECT topic_read_id FROM $t_forum_topics_read_by_ip WHERE topic_read_topic_id=$get_topic_id AND topic_read_ip=$my_ip_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_topic_read_id) = $row;
		}



		// Style
		if(isset($style) && $style == "topics_bodycell"){
			$style = "topics_subcell";
		}
		else{
			$style = "topics_bodycell";
		}

		// Icon = "; if($get_topic_read_id == "" OR $get_topic_read_id == "0"){ echo"_unread"; } if($get_topic_solved == "1"){ echo"_solved"; } echo"

		// Show all
		echo"
		 <tr>
		  <td class=\"$style\">
			<table style=\"width: 100%;\">
			 <tr>
			  <td style=\"vertical-align: center;\">
				<p class=\"p_forum_topic_title\">
				<a href=\"view_topic.php?topic_id=$get_topic_id&amp;l=$l\"  class=\"forum_topic_title\" "; if($get_topic_read_id == ""){ echo" style=\"font-weight: bold;\""; } echo">$get_topic_title</a><br />
				<span class=\"forum_meta_data\">
				$l_by <a href=\"$root/users/view_profile.php?user_id=$get_topic_user_id&amp;l=$l\" class=\"forum_meta_data\">$get_topic_user_alias</a>,
				$get_topic_updated_translated
				</span>
				</p>

				<p class=\"p_forum_replies_views_mobile_only\">
				$get_topic_replies $l_replies_lowercase
				&nbsp; &nbsp; &nbsp; 
				$get_topic_views $l_views_lowercase
				</p>				";
				// Tags
				echo"<p class=\"p_forum_tags\">\n";
				$query_t = "SELECT topic_tag_id, topic_tag_title, topic_tag_clean FROM $t_forum_topics_tags WHERE topic_id=$get_topic_id";
				$result_t = mysqli_query($link, $query_t);
				while($row_t = mysqli_fetch_row($result_t)) {
					list($get_topic_tag_id, $get_topic_tag_title, $get_topic_tag_clean) = $row_t;
					
					$tag_title_clean_mysql = quote_smart($link, $get_topic_tag_clean);
					$query_tag = "SELECT tag_id, tag_icon_path, tag_icon_file_16 FROM $t_forum_tags_index WHERE tag_title_clean=$tag_title_clean_mysql";
					$result_tag = mysqli_query($link, $query_tag);
					$row_tag = mysqli_fetch_row($result_tag);
					list($get_tag_id, $get_tag_icon_path, $get_tag_icon_file_16) = $row_tag;
		
					echo"
					<a href=\"open_tag.php?tag=$get_topic_tag_clean&amp;l=$l\" class=\"forum_a_tag\"";
					if(file_exists("$root/$get_tag_icon_path/$get_tag_icon_file_16") && $get_tag_icon_file_16 != ""){
						// echo"<img src=\"$root/$get_tag_icon_path/$get_tag_icon_file_16\" alt=\"$get_tag_icon_file_16\" /> ";
						echo" style=\"background-image: url('$root/$get_tag_icon_path/$get_tag_icon_file_16');background-repeat: no-repeat;background-position: center left 4px;padding-left:26px\" ";
					}
					echo">$get_topic_tag_title</a>
					";
				}
				echo"
				</p>
			  </td>
			  <td style=\"vertical-align: center;text-align: right;width: 20%\" class=\"td_forum_replies_views\">
				<p class=\"forum_meta_data\">
				$get_topic_replies $l_replies_lowercase<br />
				$get_topic_views $l_views_lowercase
				</p>
			  </td>
			  <td style=\"vertical-align: top;text-align: right;width: 50px;\">
				<p>
				<a href=\"$root/users/view_profile.php?user_id=$get_topic_user_id&amp;l=$l\"><img src=\"$thumb_full_path\" alt=\"$thumb_full_path\" /></a>
				</p>
			  </td>
			 </tr>
			</table>
		  </td>
		 </tr>
		";
		
	}

	echo"
		</table>
	<!-- //Show topics -->

	";
} // popular
elseif($action == "unread"){
	echo"
	<!-- Show topics -->
		<table style=\"width: 100%;\">
	";
	
	$x = 0;


	$query_w = "SELECT topic_id, topic_user_id, topic_user_alias, topic_user_image, topic_title, topic_updated_translated, topic_replies, topic_views, topic_solved FROM $t_forum_topics WHERE topic_language=$l_mysql AND topic_replies='0' ORDER BY topic_last_replied DESC";
	$result_w = mysqli_query($link, $query_w);
	while($row_w = mysqli_fetch_row($result_w)) {
		list($get_topic_id, $get_topic_user_id, $get_topic_user_alias, $get_topic_user_image, $get_topic_title, $get_topic_updated_translated, $get_topic_replies, $get_topic_views, $get_topic_solved) = $row_w;

		// Check if I have read it
		if(isset($get_my_user_id)){
			$query = "SELECT topic_read_id FROM $t_forum_topics_read_by_user WHERE topic_read_topic_id=$get_topic_id AND topic_read_user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_topic_read_id) = $row;
		}
		else{
			$query = "SELECT topic_read_id FROM $t_forum_topics_read_by_ip WHERE topic_read_topic_id=$get_topic_id AND topic_read_ip=$my_ip_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_topic_read_id) = $row;
		}


		if($get_topic_read_id == ""){


			// Avatar
			$inp_new_x = 40; // 950
			$inp_new_y = 40; // 640
			if(file_exists("$root/_uploads/users/images/$get_topic_user_id/$get_topic_user_image") && $get_topic_user_image != ""){
				$thumb_full_path = "$root/_cache/user_" . $get_topic_user_image . "-" . $inp_new_x . "x" . $inp_new_y . ".png";
				if(!(file_exists("$thumb_full_path"))){
					resize_crop_image($inp_new_x, $inp_new_y, "$root/_uploads/users/images/$get_topic_user_id/$get_topic_user_image", "$thumb_full_path");
				}
			
			}
			else{
				$thumb_full_path = "_gfx/avatar_blank_40.png";
			} 
	
		


		// Style
		if(isset($style) && $style == "topics_bodycell"){
			$style = "topics_subcell";
		}
		else{
			$style = "topics_bodycell";
		}

		// Icon = "; if($get_topic_read_id == "" OR $get_topic_read_id == "0"){ echo"_unread"; } if($get_topic_solved == "1"){ echo"_solved"; } echo"

		// Show all
		echo"
		 <tr>
		  <td class=\"$style\">
			<table style=\"width: 100%;\">
			 <tr>
			  <td style=\"vertical-align: center;\">
				<p class=\"p_forum_topic_title\">
				<a href=\"view_topic.php?topic_id=$get_topic_id&amp;l=$l\"  class=\"forum_topic_title\" "; if($get_topic_read_id == ""){ echo" style=\"font-weight: bold;\""; } echo">$get_topic_title</a><br />
				<span class=\"forum_meta_data\">
				$l_by <a href=\"$root/users/view_profile.php?user_id=$get_topic_user_id&amp;l=$l\" class=\"forum_meta_data\">$get_topic_user_alias</a>,
				$get_topic_updated_translated
				</span>
				</p>

				<p class=\"p_forum_replies_views_mobile_only\">
				$get_topic_replies $l_replies_lowercase
				&nbsp; &nbsp; &nbsp; 
				$get_topic_views $l_views_lowercase
				</p>
				";
				// Tags
				echo"<p class=\"p_forum_tags\">\n";
				$query_t = "SELECT topic_tag_id, topic_tag_title, topic_tag_clean FROM $t_forum_topics_tags WHERE topic_id=$get_topic_id";
				$result_t = mysqli_query($link, $query_t);
				while($row_t = mysqli_fetch_row($result_t)) {
					list($get_topic_tag_id, $get_topic_tag_title, $get_topic_tag_clean) = $row_t;
					
					$tag_title_clean_mysql = quote_smart($link, $get_topic_tag_clean);
					$query_tag = "SELECT tag_id, tag_icon_path, tag_icon_file_16 FROM $t_forum_tags_index WHERE tag_title_clean=$tag_title_clean_mysql";
					$result_tag = mysqli_query($link, $query_tag);
					$row_tag = mysqli_fetch_row($result_tag);
					list($get_tag_id, $get_tag_icon_path, $get_tag_icon_file_16) = $row_tag;
		
					echo"
					<a href=\"open_tag.php?tag=$get_topic_tag_clean&amp;l=$l\" class=\"forum_a_tag\"";
					if(file_exists("$root/$get_tag_icon_path/$get_tag_icon_file_16") && $get_tag_icon_file_16 != ""){
						// echo"<img src=\"$root/$get_tag_icon_path/$get_tag_icon_file_16\" alt=\"$get_tag_icon_file_16\" /> ";
						echo" style=\"background-image: url('$root/$get_tag_icon_path/$get_tag_icon_file_16');background-repeat: no-repeat;background-position: center left 4px;padding-left:26px\" ";
					}
					echo">$get_topic_tag_title</a>
					";
				}
				echo"
				</p>
			  </td>
			  <td style=\"vertical-align: center;text-align: right;width: 20%\" class=\"td_forum_replies_views\">
				<p class=\"forum_meta_data\">
				$get_topic_replies $l_replies_lowercase<br />
				$get_topic_views $l_views_lowercase
				</p>
			  </td>
			  <td style=\"vertical-align: top;text-align: right;width: 50px;\">
				<p>
				<a href=\"$root/users/view_profile.php?user_id=$get_topic_user_id&amp;l=$l\"><img src=\"$thumb_full_path\" alt=\"$thumb_full_path\" /></a>
				</p>
			  </td>
			 </tr>
			</table>
		  </td>
		 </tr>
		";
		} // not read
	} // while

	echo"
		</table>
	<!-- //Show topics -->

	";
} // zero answers

/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>