<?php 
/**
*
* File: recipes/chef_of_the_month_view_month.php
* Version 1.0.0
* Date 10:37 29.12.2020
* Copyright (c) 2011-2020 Localhost
* Author Sindre Andre Ditlefsen
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

/*- Tables ------------------------------------------------------------------------ */
$t_recipes_tags_unique			= $mysqlPrefixSav . "recipes_tags_unique";

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/recipes/ts_index.php");
include("$root/_admin/_translations/site/$l/recipes/ts_frontpage.php");

/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);


/*- Tables ---------------------------------------------------------------------------------- */
include("_tables.php");
/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['month'])) {
	$month = $_GET['month'];
	$month = strip_tags(stripslashes($month));
	if(!(is_numeric($month))){
		echo"Month is not numeric";
		die;
	}
}
else{
	$month = date("m");
}
if($month == "01"){
	$month_saying = $l_january;
}
elseif($month == "02"){
	$month_saying = $l_february;
}
elseif($month == "03"){
	$month_saying = $l_march;
}
elseif($month == "04"){
	$month_saying = $l_april;
}
elseif($month == "05"){
	$month_saying = $l_may;
}
elseif($month == "06"){
	$month_saying = $l_june;
}
elseif($month == "07"){
	$month_saying = $l_july;
}
elseif($month == "08"){
	$month_saying = $l_august;
}
elseif($month == "09"){
	$month_saying = $l_september;
}
elseif($month == "10"){
	$month_saying = $l_october;
}
elseif($month == "11"){
	$month_saying = $l_november;
}
else{
	$month_saying = $l_december;
}

if(isset($_GET['year'])) {
	$year = $_GET['year'];
	$year = strip_tags(stripslashes($year));
	if(!(is_numeric($year))){
		echo"Year is not numeric";
		die;
	}
}
else{
	$year = date("Y");
}

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_chef_of_the_month $month_saying $year - $l_recipes";
include("$root/_webdesign/header.php");



	

/*- Content ---------------------------------------------------------------------------------- */
echo"
<!-- Headline, buttons, search -->
	<div class=\"recipes_headline\">
		<h1>$l_chef_of_the_month $month_saying $year</h1>
	</div>
	<div class=\"recipes_menu\">
		
	</div>
	<div class=\"clear\"></div>
<!-- //Headline, buttons, search -->

<!-- You are here -->
	<p><b>$l_you_are_here:</b><br />
	<a href=\"index.php?l=$l\">$l_recipes</a>
	&gt;
	<a href=\"chef_of_the_month.php?l=$l\">$l_chef_of_the_month</a>
	&gt;
	<a href=\"chef_of_the_month_view_year.php?year=$year&amp;l=$l\">$year</a>
	&gt;
	<a href=\"chef_of_the_month_view_month.php?year=$year&amp;month=$month&amp;l=$l\">$month_saying</a>
	</p>
<!-- //You are here -->


<!-- Chef of the month -->";
	

	// Get chef of the month
	$x = 0;
	$month = date("m");
	$year = date("Y");
	$query = "SELECT stats_chef_of_the_month_id, stats_chef_of_the_month_user_id, stats_chef_of_the_month_user_name, stats_chef_of_the_month_user_photo_path, stats_chef_of_the_month_user_photo_thumb, stats_chef_of_the_month_recipes_posted_count, stats_chef_of_the_month_recipes_posted_points, stats_chef_of_the_month_got_visits_count, stats_chef_of_the_month_got_visits_points, stats_chef_of_the_month_got_favorites_count, stats_chef_of_the_month_got_favorites_points, stats_chef_of_the_month_got_comments_count, stats_chef_of_the_month_got_comments_points, stats_chef_of_the_month_total_points FROM $t_recipes_stats_chef_of_the_month WHERE stats_chef_of_the_month_month=$month AND stats_chef_of_the_month_year=$year ORDER BY stats_chef_of_the_month_total_points DESC LIMIT 0,8";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_stats_chef_of_the_month_id, $get_stats_chef_of_the_month_user_id, $get_stats_chef_of_the_month_user_name, $get_stats_chef_of_the_month_user_photo_path, $get_stats_chef_of_the_month_user_photo_thumb, $get_stats_chef_of_the_month_recipes_posted_count, $get_stats_chef_of_the_month_recipes_posted_points, $get_stats_chef_of_the_month_got_visits_count, $get_stats_chef_of_the_month_got_visits_points, $get_stats_chef_of_the_month_got_favorites_count, $get_stats_chef_of_the_month_got_favorites_points, $get_stats_chef_of_the_month_got_comments_count, $get_stats_chef_of_the_month_got_comments_points, $get_stats_chef_of_the_month_total_points) = $row;

		if($x == "0"){
			echo"
			<div class=\"left_right_left\" style=\"text-align: center;padding-bottom:30px;\">
			";
		}
		elseif($x == "1"){
			echo"
			<div class=\"left_right_right\" style=\"text-align: center;padding-bottom:30px;\">
			";
		}
		echo"
				<p style=\"padding-bottom:0;margin-bottom:0;\">
				<a href=\"$root/users/view_profile.php?user_id=$get_stats_chef_of_the_month_user_id&amp;l=$l\">";
				if($get_stats_chef_of_the_month_user_photo_path != "" && $get_stats_chef_of_the_month_user_photo_thumb != "" && file_exists("$root/$get_stats_chef_of_the_month_user_photo_path/$get_stats_chef_of_the_month_user_photo_thumb")){
					echo"<img src=\"$root/$get_stats_chef_of_the_month_user_photo_path/$get_stats_chef_of_the_month_user_photo_thumb\" alt=\"$get_stats_chef_of_the_month_user_photo_thumb\" />";
				}
				else{
					echo"<img src=\"_gfx/avatar_blank_200.jpg\" alt=\"avatar_blank_200.jpg\" />";
				}
				echo"</a><br />
				<a href=\"$root/users/view_profile.php?user_id=$get_stats_chef_of_the_month_user_id&amp;l=$l\" class=\"h2\">$get_stats_chef_of_the_month_user_name</a>
				</p>

				<table style=\"margin-left: auto;margin-right: auto;\">
				 <tr>
				  <td style=\"padding: 0px 4px 4px 0px;\">
				  </td>
				  <td style=\"padding: 0px 4px 4px 0px;\">
					<span class=\"dark_grey\">$l_count</span>
				  </td>
				  <td style=\"padding: 0px 0px 4px 0px;\">
					<span class=\"dark_grey\">$l_points</span>
				  </td>
				 </tr>

				 <tr>
				  <td style=\"padding: 0px 4px 4px 0px;text-align:left\">
					<span class=\"dark_grey\">$l_recipes_posted</span>
				  </td>
				  <td style=\"padding: 0px 4px 4px 0px;\">
					<span class=\"dark_grey\">$get_stats_chef_of_the_month_recipes_posted_count</span>
				  </td>
				  <td style=\"padding: 0px 0px 4px 0px;\">
					<span class=\"dark_grey\">$get_stats_chef_of_the_month_recipes_posted_points</span>
				  </td>
				 </tr>

				 <tr>
				  <td style=\"padding: 0px 4px 4px 0px;text-align:left\">
					<span class=\"dark_grey\">$l_visits_on_submitted_recipes</span>
				  </td>
				  <td style=\"padding: 0px 4px 4px 0px;\">
					<span class=\"dark_grey\">$get_stats_chef_of_the_month_got_visits_count</span>
				  </td>
				  <td style=\"padding: 0px 0px 4px 0px;\">
					<span class=\"dark_grey\">$get_stats_chef_of_the_month_got_visits_points</span>
				  </td>
				 </tr>

				 <tr>
				  <td style=\"padding: 0px 4px 4px 0px;text-align:left\">
					<span class=\"dark_grey\">$l_recipes_got_favorited</span>
				  </td>
				  <td style=\"padding: 0px 4px 4px 0px;\">
					<span class=\"dark_grey\">$get_stats_chef_of_the_month_got_favorites_count</span>
				  </td>
				  <td style=\"padding: 0px 0px 4px 0px;\">
					<span class=\"dark_grey\">$get_stats_chef_of_the_month_got_favorites_points</span>
				  </td>
				 </tr>

				 <tr>
				  <td style=\"padding: 0px 4px 4px 0px;text-align:left\">
					<span class=\"dark_grey\">$l_recipes_got_comments</span>
				  </td>
				  <td style=\"padding: 0px 4px 4px 0px;\">
					<span class=\"dark_grey\">$get_stats_chef_of_the_month_got_comments_count</span>
				  </td>
				  <td style=\"padding: 0px 0px 4px 0px;\">
					<span class=\"dark_grey\">$get_stats_chef_of_the_month_got_comments_points</span>
				  </td>
				 </tr>

				 <tr>
				  <td style=\"padding: 0px 4px 4px 0px;text-align:left\" colspan=\"2\">
					<span class=\"dark_grey\">$l_total</span>
				  </td>
				  <td style=\"padding: 0px 0px 4px 0px;\">
					<span class=\"dark_grey\"><em>$get_stats_chef_of_the_month_total_points</em></span>
				  </td>
				 </tr>
				</table>

			</div>
		";
			
		// Increment
		if($x == "1"){ $x = -1; } 
		$x = $x+1;
	
	}
	if($x == "1"){
		echo"
			<div class=\"left_right_right\" style=\"text-align: center;padding-bottom:30px;\">
			</div>
		";
	}
	echo"
	<div class=\"clear\"></div>
<!-- //Chef of the month -->

";


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>