<?php
/**
*
* File: food/view_food_show_age_restriction_warning.php
* Version 1.0.0
* Date 23:07 09.07.2017
* Copyright (c) 2008-2018 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if($action == ""){
	// Select country
	echo"
	<h1>$get_recipe_title</h1>

	<h2>$l_please_select_your_country</h2>
	";
	$x = 0;
	$query = "SELECT restriction_id, restriction_country_iso, restriction_country_name, restriction_country_flag, restriction_language, restriction_age_limit, restriction_title, restriction_text, restriction_can_view_recipe, restriction_can_view_image FROM $t_recipes_age_restrictions ORDER BY restriction_country_name ASC";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_restriction_id, $get_restriction_country_iso, $get_restriction_country_name, $get_restriction_country_flag, $get_restriction_language, $get_restriction_age_limit, $get_restriction_title, $get_restriction_text, $get_restriction_can_view_recipe, $get_restriction_can_view_image) = $row;

		$flag = "$root/_admin/_design/gfx/flags/16x16/$get_restriction_country_flag" . "_16x16.png";
		$get_restriction_country_name = substr($get_restriction_country_name, 0, 30);
		if($x == 0){
			echo"
			<div class=\"view_recipe_select_country_row\">
			";
		}
	
		echo"
				<div class=\"view_recipe_select_country_col\">
					<a href=\"view_recipe.php?action=view_agreement&amp;recipe_id=$recipe_id&amp;country=$get_restriction_country_iso&amp;l=$l\"><img src=\"$flag\" alt=\"$get_restriction_country_flag\" /> $get_restriction_country_name</a>
				
				</div>
		";
		if($x == 2){
			echo"
			</div>
			";
			$x = -1;
		}
	

		$x++;

	} //while
	if($x < 3){	
		echo"
			</div>
		";

	}
}
elseif($action == "view_agreement"){
	if(isset($_GET['country'])) {
		$country = $_GET['country'];
		$country = strip_tags(stripslashes($country));
	}
	else{
		$country = "";
	}
	$country_mysql = quote_smart($link, $country);

	
	$query = "SELECT restriction_id, restriction_country_iso, restriction_country_name, restriction_country_flag, restriction_language, restriction_age_limit, restriction_title, restriction_text, restriction_can_view_recipe, restriction_can_view_image FROM $t_recipes_age_restrictions WHERE restriction_country_iso=$country_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_restriction_id, $get_restriction_country_iso, $get_restriction_country_name, $get_restriction_country_flag, $get_restriction_language, $get_restriction_age_limit, $get_restriction_title, $get_restriction_text, $get_restriction_can_view_recipe, $get_restriction_can_view_image) = $row;

	if($get_restriction_id == ""){
		echo"<p>Restriction not found.</p>";
	}
	else{
		echo"<h1>$get_restriction_title</h1>

		<p>$get_restriction_text</p>

		<p>
		<a href=\"view_recipe.php?action=agree_to_agreement&amp;recipe_id=$recipe_id&amp;country=$get_restriction_country_iso&amp;l=$l\" class=\"btn_default\">$l_agree</a>
		</p>
		";
	}
} // view_agreement
elseif($action == "agree_to_agreement"){
	if(isset($_GET['country'])) {
		$country = $_GET['country'];
		$country = strip_tags(stripslashes($country));
	}
	else{
		$country = "";
	}
	$country_mysql = quote_smart($link, $country);

	
	$query = "SELECT restriction_id, restriction_country_iso, restriction_country_name, restriction_country_flag, restriction_language, restriction_age_limit, restriction_title, restriction_text, restriction_can_view_recipe, restriction_can_view_image FROM $t_recipes_age_restrictions WHERE restriction_country_iso=$country_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_restriction_id, $get_restriction_country_iso, $get_restriction_country_name, $get_restriction_country_flag, $get_restriction_language, $get_restriction_age_limit, $get_restriction_title, $get_restriction_text, $get_restriction_can_view_recipe, $get_restriction_can_view_image) = $row;
	if($get_restriction_id == ""){
		echo"<p>Restriction not found.</p>";
	}
	else{
		// Agree

		$inp_ip = $_SERVER['REMOTE_ADDR'];
		$inp_ip = output_html($inp_ip);
		$inp_ip_mysql = quote_smart($link, $inp_ip);

		$year = date("Y");
		$month = date("m");

		$inp_country_mysql = quote_smart($link,  $get_restriction_country_iso);

		mysqli_query($link, "INSERT INTO $t_recipes_age_restrictions_accepted
		(accepted_id, accepted_ip, accepted_year, accepted_month, accepted_country) 
		VALUES 
		(NULL, $inp_ip_mysql, $year, $month, $inp_country_mysql)")
		or die(mysqli_error($link));

		$can_view_recipe = $get_restriction_can_view_recipe;
		$can_view_images = $get_restriction_can_view_image;

	}
}
?>