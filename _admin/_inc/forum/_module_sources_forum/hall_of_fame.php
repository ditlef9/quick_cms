<?php
/**
*
* File: forum/hall_of_fame.php
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

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/forum/ts_index.php");

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
$website_title = "$l_hall_of_fame - $get_current_title_value";
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


echo"
<!-- Headline and language -->
	<h1>$l_hall_of_fame</h1>
<!-- //Headline and language -->


<!-- Menu -->
	<p>
	<a href=\"index.php?l=$l\" class=\"btn btn_default\">$l_home</a>
	</p>


	<div class=\"tabs\" style=\"margin-top: 10px;\">
		<ul>
			<li><a href=\"hall_of_fame.php?l=$l\""; if($action == ""){ echo" class=\"selected\""; } echo">$l_overall</a></li>
			<li><a href=\"hall_of_fame.php?action=yearly&amp;l=$l\""; if($action == "yearly"){ echo" class=\"selected\""; } echo">$l_yearly</a></li>
			<li><a href=\"hall_of_fame.php?action=monthly&amp;l=$l\""; if($action == "monthly"){ echo" class=\"selected\""; } echo">$l_monthly</a></li>
		</ul>
	</div>
	<div class=\"clear\" style=\"height: 15px;\"></div>
<!-- //Menu -->


";
if($action == ""){
	echo"
	<!-- Hall of fame -->

		<table class=\"hor_hall_of_fame_zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\" style=\"text-align:center;\">
			<span>$l_rank</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_leader</span>
		   </th>
		   <th scope=\"col\" style=\"text-align:center;\">
			<span>$l_topics</span>
		   </th>
		   <th scope=\"col\" style=\"text-align:center;\">
			<span>$l_answers</span>
		   </th>
		   <th scope=\"col\" style=\"text-align:center;\">
			<span>$l_votes</span>
		   </th>
		   <th scope=\"col\" style=\"text-align:center;\">
			<span>$l_points</span>
		   </th>
		  </tr>
		</thead>
		<tbody>
		";


		$year = date("Y");
		$month = date("m");
		$x = 1;
		$query_w = "SELECT top_all_id, top_all_user_id, top_all_topics, top_all_replies, top_all_times_voted, top_all_points, top_all_user_alias, top_all_user_image FROM $t_forum_top_users_all_time ORDER BY top_all_points DESC LIMIT 0,100";
		$result_w = mysqli_query($link, $query_w);
		while($row_w = mysqli_fetch_row($result_w)) {
			list($get_top_all_id, $get_top_all_user_id, $get_top_all_topics, $get_top_all_replies, $get_top_all_times_voted, $get_top_all_points, $get_top_all_user_alias, $get_top_all_user_image) = $row_w;

			if(isset($odd) && $odd == false){
				$odd = true;
			}
			else{
				$odd = false;
			}


			// Avatar
			$inp_new_x = 40; // 950
			$inp_new_y = 40; // 640
			if(file_exists("$root/_uploads/users/images/$get_top_all_user_id/$get_top_all_user_image") && $get_top_all_user_image != ""){
				$thumb_full_path = "$root/_cache/user_" . $get_top_all_user_image . "-" . $inp_new_x . "x" . $inp_new_y . ".png";
				if(!(file_exists("$thumb_full_path"))){
					resize_crop_image($inp_new_x, $inp_new_y, "$root/_uploads/users/images/$get_top_all_user_id/$get_top_all_user_image", "$thumb_full_path");
				}
			
			}
			else{
				$thumb_full_path = "_gfx/avatar_blank_40.png";
			}


			echo"
			<tr>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo" style=\"text-align:center;\">
				<p>$x</p>
			  </td>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
				<table>
				 <tr>
				  <td style=\"padding-right: 15px;\">
					<p>
					<a href=\"$root/users/view_profile.php?user_id=$get_top_all_user_id&amp;l=$l\"><img src=\"$thumb_full_path\" alt=\"$thumb_full_path\" /></a>
					</p>
				  </td>
				  <td>
					<p>
					<a href=\"$root/users/view_profile.php?user_id=$get_top_all_user_id&amp;l=$l\">$get_top_all_user_alias</a>
					</p>
				  </td>
				 </tr>
				</table>
			  </td>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo" style=\"text-align: center;\">
				<p>$get_top_all_topics</p>
			  </td>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo" style=\"text-align: center;\">
				<p>$get_top_all_replies</p>
			  </td>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo" style=\"text-align: center;\">
				<p>$get_top_all_times_voted</p>
			  </td>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo" style=\"text-align: center;\">
				<p>$get_top_all_points</p>
			  </td>
			 </tr>


";

			$x++;
		}
		echo"
		</tbody>
		</table>

	<!-- //Hall of fame -->

	";
} // overall
elseif($action == "yearly"){
	$year = date("Y");

	echo"
	<h2>$year</h2>
	<!-- Hall of fame -->
	
		<table class=\"hor_hall_of_fame_zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\" style=\"text-align:center;\">
			<span>$l_rank</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_leader</span>
		   </th>
		   <th scope=\"col\" style=\"text-align:center;\">
			<span>$l_topics</span>
		   </th>
		   <th scope=\"col\" style=\"text-align:center;\">
			<span>$l_answers</span>
		   </th>
		   <th scope=\"col\" style=\"text-align:center;\">
			<span>$l_votes</span>
		   </th>
		   <th scope=\"col\" style=\"text-align:center;\">
			<span>$l_points</span>
		   </th>
		  </tr>
		</thead>
		<tbody>
		";


		$x = 1;
		$query_w = "SELECT top_yearly_id, top_yearly_user_id, top_yearly_year, top_yearly_topics, top_yearly_replies, top_yearly_times_voted, top_yearly_points, top_yearly_user_alias, top_yearly_user_image FROM $t_forum_top_users_yearly WHERE top_yearly_year=$year ORDER BY top_yearly_points DESC LIMIT 0,8";
		$result_w = mysqli_query($link, $query_w);
		while($row_w = mysqli_fetch_row($result_w)) {
			list($get_top_yearly_id, $get_top_yearly_user_id, $get_top_yearly_year, $get_top_yearly_topics, $get_top_yearly_replies, $get_top_yearly_times_voted, $get_top_yearly_points, $get_top_yearly_user_alias, $get_top_yearly_user_image) = $row_w;

			if(isset($odd) && $odd == false){
				$odd = true;
			}
			else{
				$odd = false;
			}



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
			<tr>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo" style=\"text-align:center;\">
				<p>$x</p>
			  </td>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
				<table>
				 <tr>
				  <td style=\"padding-right: 15px;\">
					<p>
					<a href=\"$root/users/view_profile.php?user_id=$get_top_yearly_user_id&amp;l=$l\"><img src=\"$thumb_full_path\" alt=\"$thumb_full_path\" /></a>
					</p>
				  </td>
				  <td>
					<p>
					<a href=\"$root/users/view_profile.php?user_id=$get_top_yearly_user_id&amp;l=$l\">$get_top_yearly_user_alias</a>
					</p>
				  </td>
				 </tr>
				</table>
			  </td>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo" style=\"text-align: center;\">
				<p>$get_top_yearly_topics</p>
			  </td>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo" style=\"text-align: center;\">
				<p>$get_top_yearly_replies</p>
			  </td>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo" style=\"text-align: center;\">
				<p>$get_top_yearly_times_voted</p>
			  </td>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo" style=\"text-align: center;\">
				<p>$get_top_yearly_points</p>
			  </td>
			 </tr>


";

			$x++;
		}
		echo"
		</tbody>
		</table>

	<!-- //Hall of fame -->

	";
} // yearly
elseif($action == "monthly"){
	$year = date("Y");
	$month = date("m");
	echo"
	<!-- Hall of fame monthly -->

	<h2>";	
	if($month == "01"){
		echo $l_january;
				}
				elseif($month == "02"){
		echo $l_february;
				}
				elseif($month == "03"){
		echo $l_march;
				}
				elseif($month == "04"){
		echo $l_april;
				}
				elseif($month == "05"){
		echo $l_may;
				}
				elseif($month == "06"){
		echo $l_june;
				}
				elseif($month == "07"){
		echo $l_july;
				}
				elseif($month == "08"){
		echo $l_august;
				}
				elseif($month == "09"){
		echo $l_september;
				}
				elseif($month == "10"){
		echo $l_october;
				}
				elseif($month == "11"){
		echo $l_november;
				}
				else{
		echo $l_december;
				}
	echo"</h2>
		<table class=\"hor_hall_of_fame_zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\" style=\"text-align:center;\">
			<span>$l_rank</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_leader</span>
		   </th>
		   <th scope=\"col\" style=\"text-align:center;\">
			<span>$l_topics</span>
		   </th>
		   <th scope=\"col\" style=\"text-align:center;\">
			<span>$l_answers</span>
		   </th>
		   <th scope=\"col\" style=\"text-align:center;\">
			<span>$l_votes</span>
		   </th>
		   <th scope=\"col\" style=\"text-align:center;\">
			<span>$l_points</span>
		   </th>
		  </tr>
		</thead>
		<tbody>
		";


		$x = 1;
		$query_w = "SELECT top_monthly_id, top_monthly_user_id, top_monthly_year, top_monthly_month, top_monthly_topics, top_monthly_replies, top_monthly_times_voted, top_monthly_points, top_monthly_user_alias, top_monthly_user_image FROM $t_forum_top_users_monthly WHERE top_monthly_year=$year AND top_monthly_month=$month ORDER BY top_monthly_points DESC LIMIT 0,8";
		$result_w = mysqli_query($link, $query_w);
		while($row_w = mysqli_fetch_row($result_w)) {
			list($get_top_monthly_id, $get_top_monthly_user_id, $get_top_monthly_year, $get_top_monthly_month, $get_top_monthly_topics, $get_top_monthly_replies, $get_top_monthly_times_voted, $get_top_monthly_points, $get_top_monthly_user_alias, $get_top_monthly_user_image) = $row_w;

			if(isset($odd) && $odd == false){
				$odd = true;
			}
			else{
				$odd = false;
			}



			// Avatar
			$inp_new_x = 40; // 950
			$inp_new_y = 40; // 640
			if(file_exists("$root/_uploads/users/images/$get_top_monthly_user_id/$get_top_monthly_user_image") && $get_top_monthly_user_image != ""){
				$thumb_full_path = "$root/_cache/user_" . $get_top_monthly_user_image . "-" . $inp_new_x . "x" . $inp_new_y . ".png";
				if(!(file_exists("$thumb_full_path"))){
					resize_crop_image($inp_new_x, $inp_new_y, "$root/_uploads/users/images/$get_top_monthly_user_id/$get_top_monthly_user_image", "$thumb_full_path");
				}
			
			}
			else{
				$thumb_full_path = "_gfx/avatar_blank_40.png";
			}


			echo"
			<tr>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo" style=\"text-align:center;\">
				<p>$x</p>
			  </td>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
				<table>
				 <tr>
				  <td style=\"padding-right: 15px;\">
					<p>
					<a href=\"$root/users/view_profile.php?user_id=$get_top_monthly_user_id&amp;l=$l\"><img src=\"$thumb_full_path\" alt=\"$thumb_full_path\" /></a>
					</p>
				  </td>
				  <td>
					<p>
					<a href=\"$root/users/view_profile.php?user_id=$get_top_monthly_user_id&amp;l=$l\">$get_top_monthly_user_alias</a>
					</p>
				  </td>
				 </tr>
				</table>
			  </td>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo" style=\"text-align: center;\">
				<p>$get_top_monthly_topics</p>
			  </td>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo" style=\"text-align: center;\">
				<p>$get_top_monthly_replies</p>
			  </td>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo" style=\"text-align: center;\">
				<p>$get_top_monthly_times_voted</p>
			  </td>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo" style=\"text-align: center;\">
				<p>$get_top_monthly_points</p>
			  </td>
			 </tr>


";

			$x++;
		}
		echo"
		</tbody>
		</table>

	<!-- //Hall of fame monthly -->

	";
} // monthly


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>