<?php 
/**
*
* File: recipes/chef_of_the_month_view_year.php
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
$website_title = "$l_chef_of_the_month $year - $l_recipes";
include("$root/_webdesign/header.php");



	

/*- Content ---------------------------------------------------------------------------------- */
echo"
<!-- Headline, buttons, search -->
	<div class=\"recipes_headline\">
		<h1>$l_chef_of_the_month $year</h1>
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
	</p>
<!-- //You are here -->


<!-- Chef of the month -->";
	

	// Get chef of the month
	$m = 0;
	$x = 0;
	$year_mysql = quote_smart($link, $year);
	for($m=1;$m<13;$m++){

		// Chef of the month for that year
		$query = "SELECT stats_chef_of_the_month_id, stats_chef_of_the_month_month_full, stats_chef_of_the_month_user_id, stats_chef_of_the_month_user_name, stats_chef_of_the_month_user_photo_path, stats_chef_of_the_month_user_photo_thumb, stats_chef_of_the_month_recipes_posted_count, stats_chef_of_the_month_recipes_posted_points, stats_chef_of_the_month_got_visits_count, stats_chef_of_the_month_got_visits_points, stats_chef_of_the_month_got_favorites_count, stats_chef_of_the_month_got_favorites_points, stats_chef_of_the_month_got_comments_count, stats_chef_of_the_month_got_comments_points, stats_chef_of_the_month_total_points FROM $t_recipes_stats_chef_of_the_month WHERE stats_chef_of_the_month_month=$m AND stats_chef_of_the_month_year=$year_mysql ORDER BY stats_chef_of_the_month_total_points DESC LIMIT 0,1";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_stats_chef_of_the_month_id, $get_stats_chef_of_the_month_month_full, $get_stats_chef_of_the_month_user_id, $get_stats_chef_of_the_month_user_name, $get_stats_chef_of_the_month_user_photo_path, $get_stats_chef_of_the_month_user_photo_thumb, $get_stats_chef_of_the_month_recipes_posted_count, $get_stats_chef_of_the_month_recipes_posted_points, $get_stats_chef_of_the_month_got_visits_count, $get_stats_chef_of_the_month_got_visits_points, $get_stats_chef_of_the_month_got_favorites_count, $get_stats_chef_of_the_month_got_favorites_points, $get_stats_chef_of_the_month_got_comments_count, $get_stats_chef_of_the_month_got_comments_points, $get_stats_chef_of_the_month_total_points) = $row;


		
		if($x == "0"){
			echo"
			<div class=\"left_center_center_right_left\" style=\"text-align:center;\">
			";
		}
		elseif($x == "1"){
			echo"
			<div class=\"left_center_center_left_right_center\" style=\"text-align:center;\">
			";
		}
		elseif($x == "2"){
			echo"
			<div class=\"left_center_center_right_right_center\" style=\"text-align:center;\">
			";
		}
		elseif($x == "3"){
			echo"
			<div class=\"left_center_center_right_right\" style=\"text-align:center;\">
			";
		}

		if($get_stats_chef_of_the_month_id != ""){
			echo"
				<p style=\"padding-bottom:0;margin-bottom:0;\">
				<a href=\"chef_of_the_month_view_month.php?month=$m&amp;year=$year&amp;l=$l\" class=\"h2\">$get_stats_chef_of_the_month_month_full</a><br />
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
		
			";
		}
		echo"
			</div>
		";

		// Increment
		if($x == "3"){ $x = -1; } 
		$x = $x+1;
	}


	echo"
	<div class=\"clear\"></div>
<!-- //Chef of the month -->

";


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>