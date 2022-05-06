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
	<h1>$get_current_food_manufacturer_name $get_current_food_name</h1>

	<h2>$l_please_select_your_country</h2>
	";
	$x = 0;
	$query = "SELECT restriction_id, restriction_country_name, restriction_country_iso_two, restriction_country_flag_path_16x16, restriction_country_flag_16x16 FROM $t_food_age_restrictions ORDER BY restriction_country_name ASC";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_restriction_id, $get_restriction_country_name, $get_restriction_country_iso_two, $get_restriction_country_flag_path_16x16, $get_restriction_country_flag_16x16) = $row;

		$get_restriction_country_name = substr($get_restriction_country_name, 0, 30);
		if($x == 0){
			echo"
			<div class=\"view_food_select_country_row\">
			";
		}
	
		echo"
				<div class=\"view_food_select_country_col\">
					<a href=\"view_food.php?action=view_agreement&amp;main_category_id=$main_category_id&amp;sub_category_id=$sub_category_id&amp;food_id=$food_id&amp;country=$get_restriction_country_iso_two&amp;l=$l\"><img src=\"$root/$get_restriction_country_flag_path_16x16/$get_restriction_country_flag_16x16\" alt=\"$get_restriction_country_flag_16x16\" /> $get_restriction_country_name</a>
				
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

	
	$query = "SELECT restriction_id, restriction_country_name, restriction_country_iso_two, restriction_title, restriction_text FROM $t_food_age_restrictions WHERE restriction_country_iso_two=$country_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_restriction_id, $get_restriction_country_name, $get_restriction_country_iso_two, $get_restriction_title, $get_restriction_text) = $row;

	if($get_restriction_id == ""){
		echo"<p>Restriction not found.</p>";
	}
	else{
		echo"<h1>$get_restriction_title</h1>

		<p>$get_restriction_text</p>

		<p>
		<a href=\"view_food.php?action=agree_to_agreement&amp;main_category_id=$main_category_id&amp;sub_category_id=$sub_category_id&amp;food_id=$food_id&amp;country=$get_restriction_country_iso_two&amp;l=$l&amp;process=1\" class=\"btn_default\">$l_agree</a>
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

	$query = "SELECT restriction_id, restriction_country_name, restriction_country_iso_two, restriction_country_flag_path_16x16, restriction_country_flag_16x16, restriction_language, restriction_age_limit, restriction_title, restriction_text, restriction_show_food, restriction_show_image_a, restriction_show_image_b, restriction_show_image_c, restriction_show_image_d, restriction_show_image_e, restriction_show_smileys FROM $t_food_age_restrictions WHERE restriction_country_iso_two=$country_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_restriction_id, $get_current_restriction_country_name, $get_current_restriction_country_iso_two, $get_current_restriction_country_flag_path_16x16, $get_current_restriction_country_flag_16x16, $get_current_restriction_language, $get_current_restriction_age_limit, $get_current_restriction_title, $get_current_restriction_text, $get_current_restriction_show_food, $get_current_restriction_show_image_a, $get_current_restriction_show_image_b, $get_current_restriction_show_image_c, $get_current_restriction_show_image_d, $get_current_restriction_show_image_e, $get_current_restriction_show_smileys) = $row;

	if($get_current_restriction_id == ""){
		echo"<p>Restriction not found.</p>";
	}
	else{
		// Agree

		$inp_ip = $_SERVER['REMOTE_ADDR'];
		$inp_ip = output_html($inp_ip);
		$inp_ip_mysql = quote_smart($link, $inp_ip);

		$year = date("Y");
		$month = date("m");

		// Delete old agreements
		mysqli_query($link, "DELETE FROM $t_food_age_restrictions_accepted WHERE accepted_year < $year") or die(mysqli_error($link));


		// Insert new
		$inp_country_mysql = quote_smart($link,  $get_current_restriction_country_iso_two);

		mysqli_query($link, "INSERT INTO $t_food_age_restrictions_accepted
		(accepted_id, accepted_ip, accepted_year, accepted_month, accepted_country) 
		VALUES 
		(NULL, $inp_ip_mysql, $year, $month, $inp_country_mysql)")
		or die(mysqli_error($link));



		$url = "view_food.php?main_category_id=$main_category_id&sub_category_id=$sub_category_id&food_id=$food_id&l=$l&ft=success&fm=agreement_saved";
		header("Location: $url");
		exit;
	}
}
?>