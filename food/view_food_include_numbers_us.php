<?php 
/**
*
* File: food/view_food_include_numbers_us.php
* Version 1.0.0
* Date 09:53 08.04.2022
* Copyright (c) 2011-2022 S. A. Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
if(!(isset($get_current_food_id))){
	echo"error";
	die;
}


echo"

	<table class=\"hor-zebra\" style=\"width: auto;min-width: 0;display: table;\">
	 <thead>
	  <tr>
	   <th scope=\"col\">
	   </th>
	   <th scope=\"col\" style=\"text-align: center;padding: 6px 4px 6px 8px;vertical-align: bottom;\">
		<span>$l_per_100 g</span>
	   </th>
	   <th scope=\"col\" style=\"text-align: center;padding: 6px 8px 6px 8px;\">
		<span>$l_serving<br />
		$get_current_food_serving_size_us $get_current_food_serving_size_measurement_us<br />
		($get_current_food_serving_size_metric $get_current_food_serving_size_measurement_metric)<br />
		$get_current_food_serving_size_pcs $get_current_food_serving_size_pcs_measurement</span>
	   </th>
	   <th scope=\"col\" style=\"text-align: center;padding: 6px 8px 6px 8px;\">
		<span>$l_net_content<br />
		$get_current_food_net_content_us $get_current_food_net_content_measurement_us
		($get_current_food_net_content_metric $get_current_food_net_content_measurement_metric) <br />
		1 $l_pcs_lowercase<br /></span>
	   </th>
	   <th scope=\"col\" style=\"text-align: center;padding: 6px 8px 6px 8px;\" class=\"current_sub_category_calories_med\">
		<span>$l_median_for<br />
		$get_current_sub_category_translation_value $l_per_100_lowercase g</span>
	   </th>
	   <th scope=\"col\" style=\"text-align: center;padding: 6px 8px 6px 8px;\" class=\"current_sub_category_calories_diff\">
		<span>$l_diff</span>
	   </th>
	  </tr>
	 </thead>
	 <tbody>
";

// Calories
$energy_diff_med = round($get_current_food_energy_metric-$get_current_sub_category_calories_med_100_g, 0);

$energy_color = "black";
if($energy_diff_med > 0){
	$energy_color = "red";
}
elseif($energy_diff_med < 0){
	$energy_color = "green";
}

echo"
	  <tr>
	   <td style=\"padding: 8px 4px 6px 8px;\">
		<span>$l_calories</span>
	   </td>
	   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
		<!-- Per 100 -->
		<span style=\"color: $energy_color;\">$get_current_food_energy_metric</span>
		<!-- //Per 100 -->
	   </td>
	   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
		<span style=\"color: $energy_color;\">$get_current_food_energy_calculated_us</span>
		<!-- //Per slice -->
	   </td>
	   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
		<!-- Per net content -->
		<span style=\"color: $energy_color;\">$get_current_food_energy_net_content</span>
		<!-- //Per net content -->
	   </td>
	   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_med\">
		<!-- Median -->
		<span>$get_current_sub_category_calories_med_100_g</span>
		<!-- //Median -->
	   </td>
	   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_diff\">
		
		<!-- Diff-->
		<span style=\"color: $energy_color;\">$energy_diff_med</span>
		<!-- //Diff-->
	   </td>
	  </tr>
	";

// Fat
$fat_diff_med = round($get_current_food_fat_metric-$get_current_sub_category_fat_med_100_g, 0);
$saturated_fat_diff_med = round($get_current_food_saturated_fat_metric-$get_current_sub_category_saturated_fat_med_100_g, 0);
$trans_fat_diff_med = round($get_current_food_saturated_fat_metric-$get_current_sub_category_trans_fat_med_100_g, 0);
$monounsaturated_fat_diff_med = round($get_current_food_monounsaturated_fat_metric-$get_current_sub_category_monounsaturated_fat_med_100_g, 0);
$polyunsaturated_fat_diff_med = round($get_current_food_polyunsaturated_fat_metric-$get_current_sub_category_polyunsaturated_fat_med_100_g, 0);

if($monounsaturated_fat_diff_med != 0){
	$monounsaturated_fat_diff_med = $monounsaturated_fat_diff_med*-1;
}
if($polyunsaturated_fat_diff_med != 0){
	$polyunsaturated_fat_diff_med = $polyunsaturated_fat_diff_med*-1;
}


echo"
	  <tr>
	   <td style=\"padding: 8px 4px 6px 8px;\">
		<span>$l_fat<br />
		$l_dash_saturated_fat<br />
		$l_dash_trans_fat<br />
		$l_dash_monounsaturated_fat<br />
		$l_dash_polyunsaturated_fat</span>
	   </td>
	   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
		";
		if($fat_diff_med > 0){
			echo"<span style=\"color: red;\">$get_current_food_fat_us<br /></span>";
		}
		elseif($fat_diff_med < 0){
			echo"<span style=\"color: green;\">$get_current_food_fat_us<br /></span>";
		}
		else{
			echo"<span>$get_current_food_fat_us<br /></span>";
		}

		if($saturated_fat_diff_med > 0){
			echo"<span style=\"color: red;\">$get_current_food_saturated_fat_us<br /></span>";
		}
		elseif($saturated_fat_diff_med < 0){
			echo"<span style=\"color: green;\">$get_current_food_saturated_fat_us<br /></span>";
		}
		else{
			echo"<span>$get_current_food_saturated_fat_us<br /></span>";
		}

		if($trans_fat_diff_med > 0){
			echo"<span style=\"color: red;\">$get_current_food_trans_fat_us<br /></span>";
		}
		elseif($trans_fat_diff_med < 0){
			echo"<span style=\"color: green;\">$get_current_food_trans_fat_us<br /></span>";
		}
		else{
			echo"<span>$get_current_food_trans_fat_us<br /></span>";
		}

		if($monounsaturated_fat_diff_med > 0){
			echo"<span style=\"color: red;\">$get_current_food_monounsaturated_fat_us<br /></span>";
		}
		elseif($saturated_fat_diff_med < 0){
			echo"<span style=\"color: green;\">$get_current_food_monounsaturated_fat_us<br /></span>";
		}
		else{
			echo"<span>$get_current_food_monounsaturated_fat_us<br /></span>";
		}

		if($polyunsaturated_fat_diff_med > 0){
			echo"<span style=\"color: red;\">$get_current_food_polyunsaturated_fat_us<br /></span>";
		}
		elseif($polyunsaturated_fat_diff_med < 0){
			echo"<span style=\"color: green;\">$get_current_food_polyunsaturated_fat_us<br /></span>";
		}
		else{
			echo"<span>$get_current_food_polyunsaturated_fat_us<br /></span>";
		}
		echo"
	   </td>
	   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
		";
		if($fat_diff_med > 0){
			echo"<span style=\"color: red;\">$get_current_food_fat_calculated_us<br /></span>";
		}
		elseif($fat_diff_med < 0){
			echo"<span style=\"color: green;\">$get_current_food_fat_calculated_us<br /></span>";
		}
		else{
			echo"<span>$get_current_food_fat_calculated_us<br /></span>";
		}

		if($saturated_fat_diff_med > 0){
			echo"<span style=\"color: red;\">$get_current_food_saturated_fat_calculated_us<br /></span>";
		}
		elseif($saturated_fat_diff_med < 0){
			echo"<span style=\"color: green;\">$get_current_food_saturated_fat_calculated_us<br /></span>";
		}
		else{
			echo"<span>$get_current_food_saturated_fat_calculated_us<br /></span>";
		}


		if($trans_fat_diff_med > 0){
			echo"<span style=\"color: red;\">$get_current_food_trans_fat_calculated_us<br /></span>";
		}
		elseif($trans_fat_diff_med < 0){
			echo"<span style=\"color: green;\">$get_current_food_trans_fat_calculated_us<br /></span>";
		}
		else{
			echo"<span>$get_current_food_trans_fat_calculated_us<br /></span>";
		}

		if($monounsaturated_fat_diff_med > 0){
			echo"<span style=\"color: red;\">$get_current_food_monounsaturated_fat_calculated_us<br /></span>";
		}
		elseif($saturated_fat_diff_med < 0){
			echo"<span style=\"color: green;\">$get_current_food_monounsaturated_fat_calculated_us<br /></span>";
		}
		else{
			echo"<span>$get_current_food_monounsaturated_fat_calculated_us<br /></span>";
		}

		if($polyunsaturated_fat_diff_med > 0){
			echo"<span style=\"color: red;\">$get_current_food_polyunsaturated_fat_calculated_us<br /></span>";
		}
		elseif($polyunsaturated_fat_diff_med < 0){
			echo"<span style=\"color: green;\">$get_current_food_polyunsaturated_fat_calculated_us<br /></span>";
		}
		else{
			echo"<span>$get_current_food_polyunsaturated_fat_calculated_us<br /></span>";
		}
		echo"
	   </td>
	   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
		";
		if($fat_diff_med > 0){
			echo"<span style=\"color: red;\">$get_current_food_fat_net_content<br /></span>";
		}
		elseif($fat_diff_med < 0){
			echo"<span style=\"color: green;\">$get_current_food_fat_net_content<br /></span>";
		}
		else{
			echo"<span>$get_current_food_fat_net_content<br /></span>";
		}

		if($saturated_fat_diff_med > 0){
			echo"<span style=\"color: red;\">$get_current_food_saturated_fat_net_content<br /></span>";
		}
		elseif($saturated_fat_diff_med < 0){
			echo"<span style=\"color: green;\">$get_current_food_saturated_fat_net_content<br /></span>";
		}
		else{
			echo"<span>$get_current_food_saturated_fat_net_content<br /></span>";
		}

		if($trans_fat_diff_med > 0){
			echo"<span style=\"color: red;\">$get_current_food_trans_fat_net_content<br /></span>";
		}
		elseif($trans_fat_diff_med < 0){
			echo"<span style=\"color: green;\">$get_current_food_trans_fat_net_content<br /></span>";
		}
		else{
			echo"<span>$get_current_food_trans_fat_net_content<br /></span>";
		}

		if($monounsaturated_fat_diff_med > 0){
			echo"<span style=\"color: red;\">$get_current_food_monounsaturated_fat_net_content<br /></span>";
		}
		elseif($saturated_fat_diff_med < 0){
			echo"<span style=\"color: green;\">$get_current_food_monounsaturated_fat_net_content<br /></span>";
		}
		else{
			echo"<span>$get_current_food_monounsaturated_fat_net_content<br /></span>";
		}

		if($polyunsaturated_fat_diff_med > 0){
			echo"<span style=\"color: red;\">$get_current_food_polyunsaturated_fat_net_content<br /></span>";
		}
		elseif($polyunsaturated_fat_diff_med < 0){
			echo"<span style=\"color: green;\">$get_current_food_polyunsaturated_fat_net_content<br /></span>";
		}
		else{
			echo"<span>$get_current_food_polyunsaturated_fat_net_content<br /></span>";
		}
		echo"

	   </td>
	   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_med\">
		<span>$get_current_sub_category_fat_med_100_g<br />
		$get_current_sub_category_saturated_fat_med_100_g<br />
		$get_current_sub_category_trans_fat_med_100_g<br />
		$get_current_sub_category_monounsaturated_fat_med_100_g<br />
		$get_current_sub_category_polyunsaturated_fat_med_100_g</span>
	   </td>
	   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_diff\">";
		if($fat_diff_med > 0){
			echo"<span style=\"color: red;\">$fat_diff_med<br /></span>";
		}
		elseif($fat_diff_med < 0){
			echo"<span style=\"color: green;\">$fat_diff_med<br /></span>";
		}
		else{
			echo"<span>$fat_diff_med<br /></span>";
		}

		if($saturated_fat_diff_med > 0){
			echo"<span style=\"color: red;\">$saturated_fat_diff_med<br /></span>";
		}
		elseif($saturated_fat_diff_med < 0){
			echo"<span style=\"color: green;\">$saturated_fat_diff_med<br /></span>";
		}
		else{
			echo"<span>$saturated_fat_diff_med<br /></span>";
		}

		if($trans_fat_diff_med > 0){
			echo"<span style=\"color: red;\">$trans_fat_diff_med<br /></span>";
		}
		elseif($trans_fat_diff_med < 0){
			echo"<span style=\"color: green;\">$trans_fat_diff_med<br /></span>";
		}
		else{
			echo"<span>$saturated_fat_diff_med<br /></span>";
		}

		if($monounsaturated_fat_diff_med > 0){
			echo"<span style=\"color: red;\">$monounsaturated_fat_diff_med<br /></span>";
		}
		elseif($saturated_fat_diff_med < 0){
			echo"<span style=\"color: green;\">$monounsaturated_fat_diff_med<br /></span>";
		}
		else{
			echo"<span>$monounsaturated_fat_diff_med<br /></span>";
		}

		if($polyunsaturated_fat_diff_med > 0){
			echo"<span style=\"color: red;\">$polyunsaturated_fat_diff_med<br /></span>";
		}
		elseif($polyunsaturated_fat_diff_med < 0){
			echo"<span style=\"color: green;\">$polyunsaturated_fat_diff_med<br /></span>";
		}
		else{
			echo"<span>$polyunsaturated_fat_diff_med<br /></span>";
		}
		echo"
	   </td>
	  </tr>
";


// Cholesterol
$cholesterol_diff_med = round($get_current_food_cholesterol_us-$get_current_sub_category_cholesterol_med_100_g, 0);

$cholesterol_color = "black";
if($cholesterol_diff_med > 0){
	$cholesterol_color = "red";
}
elseif($cholesterol_diff_med < 0){
	$cholesterol_color = "green";
}
		
echo"
	  <tr>
	   <td style=\"padding: 8px 4px 6px 8px;\">
		<span>$l_cholesterol_in_mg</span>
	   </td>
	   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
		<span style=\"color: $cholesterol_color;\">$get_current_food_cholesterol_us</span>
	   </td>
	   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
		<span style=\"color: $cholesterol_color;\">$get_current_food_cholesterol_calculated_us</span>
	   </td>
	   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
		<span style=\"color: $cholesterol_color;\">$get_current_food_cholesterol_net_content</span>
	   </td>
	   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_med\">
		<span>$get_current_sub_category_cholesterol_med_100_g</span>
	   </td>
	   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_diff\">
		<span style=\"color: $cholesterol_color;\">$cholesterol_diff_med</span>
	   </td>
	 </tr>
";


// Sodium
$sodium_diff_med = round($get_current_food_sodium_metric-$get_current_sub_category_sodium_med_100_g, 0);

$sodium_color = "black";
if($sodium_diff_med > 0){
	$sodium_color = "red";
}
elseif($sodium_diff_med < 0){
	$sodium_color = "green";
}

echo"
	  <tr>
	   <td style=\"padding: 8px 4px 6px 8px;\">
		<span>$l_sodium_in_mg</span>
	   </td>
	   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
		<span style=\"color: $sodium_color;\">$get_current_food_sodium_us</span>
	   </td>
	   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
		<span style=\"color: $sodium_color;\">$get_current_food_sodium_calculated_us</span>
	   </td>
	   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
		<span style=\"color: $sodium_color;\">$get_current_food_sodium_net_content</span>
	   </td>

	   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_med\">
		<span>$get_current_sub_category_sodium_med_100_g</span>
	   </td>
	   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_diff\">	
		<span style=\"color: $sodium_color;\">$sodium_diff_med</span>
	   </td>
	  </tr>


";

// Carbs + Fiber + Sugar
$carbohydrate_diff_med = round($get_current_food_carbohydrates_metric-$get_current_sub_category_carb_med_100_g, 0);
$dietary_fiber_diff_med = round($get_current_food_dietary_fiber_metric-$get_current_sub_category_dietary_fiber_med_100_g, 0);
$carbohydrates_of_which_sugars_diff_med = round($get_current_food_carbohydrates_of_which_sugars_metric-$get_current_sub_category_carb_of_which_sugars_med_100_g, 0);
$added_sugars_diff_med = round($get_current_food_added_sugars_metric-$get_current_sub_category_added_sugars_med_100_g, 0);
if($dietary_fiber_diff_med != 0){
	$dietary_fiber_diff_med = $dietary_fiber_diff_med*-1;
}

$carbohydrate_color = "black";
if($carbohydrate_diff_med > 0){
	$carbohydrate_color = "red";
}
elseif($carbohydrate_diff_med < 0){
	$carbohydrate_color = "green";
}

$dietary_fiber_color = "black";
if($dietary_fiber_diff_med > 0){
	$dietary_fiber_color = "red";
}
elseif($dietary_fiber_diff_med < 0){
	$dietary_fiber_color = "green";
}

$carbohydrates_of_which_sugars_color = "black";
if($carbohydrates_of_which_sugars_diff_med > 0){
	$carbohydrates_of_which_sugars_color = "red";
}
elseif($carbohydrates_of_which_sugars_diff_med < 0){
	$carbohydrates_of_which_sugars_color = "green";
}

$added_sugars_color = "black";
if($added_sugars_diff_med > 0){
	$added_sugars_color = "red";
}
elseif($added_sugars_diff_med < 0){
	$added_sugars_color = "green";
}
echo"
  <tr>
   <td style=\"padding: 8px 4px 6px 8px;\">
	<span>$l_carbs<br /></span>
	<span>$l_dietary_fiber<br /></span>
	<span>$l_dash_of_which_sugars<br /></span>
	<span>$l_dash_added_sugars<br /></span>
   </td>
   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
	<span style=\"color: $carbohydrate_color;\">$get_current_food_carbohydrates_us<br /></span>
	<span style=\"color: $dietary_fiber_color;\">$get_current_food_dietary_fiber_us<br /></span>
	<span style=\"color: $carbohydrates_of_which_sugars_color;\">$get_current_food_carbohydrates_of_which_sugars_us<br /></span>
	<span style=\"color: $added_sugars_color;\">$get_current_food_added_sugars_us<br /></span>
   </td>
   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
	<span style=\"color: $carbohydrate_color;\">$get_current_food_carbohydrates_calculated_us<br /></span>
	<span style=\"color: $dietary_fiber_color;\">$get_current_food_dietary_fiber_calculated_us<br /></span>
	<span style=\"color: $carbohydrates_of_which_sugars_color;\">$get_current_food_carbohydrates_of_which_sugars_calculated_us<br /></span>
	<span style=\"color: $added_sugars_color;\">$get_current_food_added_sugars_calculated_us<br /></span>
   </td>
   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
	<span style=\"color: $carbohydrate_color;\">$get_current_food_carbohydrates_net_content<br /></span>
	<span style=\"color: $dietary_fiber_color;\">$get_current_food_dietary_fiber_net_content<br /></span>
	<span style=\"color: $carbohydrates_of_which_sugars_color;\">$get_current_food_carbohydrates_of_which_sugars_net_content<br /></span>
	<span style=\"color: $added_sugars_color;\">$get_current_food_added_sugars_net_content<br /></span>
   </td>
   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_med\">
	<span>$get_current_sub_category_carb_med_100_g<br /></span>
	<span>$get_current_sub_category_dietary_fiber_med_100_g<br /></span>
	<span>$get_current_sub_category_carb_of_which_sugars_med_100_g<br /></span>
	<span>$get_current_sub_category_added_sugars_med_100_g</span>
   </td>
   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_diff\">
	<span style=\"color: $carbohydrate_color;\">$carbohydrate_diff_med<br /></span>
	<span style=\"color: $dietary_fiber_color;\">$dietary_fiber_diff_med<br /></span>
	<span style=\"color: $carbohydrates_of_which_sugars_color;\">$carbohydrates_of_which_sugars_diff_med<br /></span>
	<span style=\"color: $added_sugars_color;\">$added_sugars_diff_med<br /></span>
   </td>
  </tr>

";
// Proteins
$proteins_diff_med = round($get_current_food_proteins_metric-$get_current_sub_category_proteins_med_100_g, 0);
$proteins_diff_med = $proteins_diff_med*-1;

$proteins_color = "black";
if($proteins_diff_med > 0){
	$proteins_color = "red";
}
elseif($proteins_diff_med < 0){
	$proteins_color = "green";
}

echo"
  <tr>
   <td style=\"padding: 8px 4px 6px 8px;\">
	<span>$l_proteins</span>
   </td>
   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
	<span style=\"color: $proteins_color;\">$get_current_food_proteins_metric</span>
   </td>
   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
	<span style=\"color: $proteins_color;\">$get_current_food_proteins_calculated_us</span>
   </td>
   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
	<span style=\"color: $proteins_color;\">$get_current_food_proteins_net_content</span>
   </td>
   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_med\">
	<span>$get_current_sub_category_proteins_med_100_g</span>
   </td>
   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_diff\">
	<span>$proteins_diff_med</span>
   </td>
  </tr>
";

// Salt
$salt_diff_med = round($get_current_food_salt_metric-$get_current_sub_category_salt_med_100_g, 0);

$salt_color = "black";
if($salt_diff_med > 0){
	$salt_color = "red";
}
elseif($salt_diff_med < 0){
	$salt_color = "green";
}

echo"
	  <tr>
	   <td style=\"padding: 8px 4px 6px 8px;\">
		<span>$l_salt_in_gram</span>
	   </td>
	   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
		<span style=\"color: $salt_color;\">$get_current_food_salt_metric</span>
	   </td>
	   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
		<span style=\"color: $salt_color;\">$get_current_food_salt_calculated_us</span>
	   </td>
	   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
		<span style=\"color: $salt_color;\">$get_current_food_salt_net_content<br /></span>
	   </td>
	   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_med\">
		<span>$get_current_sub_category_salt_med_100_g</span>
	   </td>
	   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_diff\">
		<span style=\"color: $salt_color;\">$salt_diff_med</span></span>
	   </td>
	  </tr>
	 </tbody>
	</table>

	<script>
	\$(document).ready(function(){
		\$(\".a_show_score\").click(function () {
			\$(\".current_sub_category_calories_med\").toggle();
			\$(\".current_sub_category_calories_diff\").toggle();
			\$(\".protein_diff\").toggle();
		});
	});
	</script>
";


?>