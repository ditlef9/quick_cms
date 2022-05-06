<?php 
/**
*
* File: recipes/chef_of_the_month.php
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


/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_chef_of_the_month - $l_recipes";
include("$root/_webdesign/header.php");



	

/*- Content ---------------------------------------------------------------------------------- */
echo"
<!-- Headline, buttons, search -->
	<div class=\"recipes_headline\">
		<h1>$l_chef_of_the_month</h1>
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
	</p>
<!-- //You are here -->


<!-- Chef of the month select years -->
	<ul class=\"vertical\">
	";
	

	// Get chef of the month
	$x = 0;
	$query = "SELECT DISTINCT (stats_chef_of_the_month_year) FROM $t_recipes_stats_chef_of_the_month";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_stats_chef_of_the_month_year) = $row;
	
		echo"
		<li><a href=\"chef_of_the_month_view_year.php?year=$get_stats_chef_of_the_month_year&amp;l=$l\">$get_stats_chef_of_the_month_year</a></li>
		";
	}
	echo"
	</ul>
<!-- //Chef of the month select years -->
";


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>