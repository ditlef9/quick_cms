<?php 
/**
*
* File: food/view_food_include_numbers_metric.php
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
		<span>$l_per_100</span>
	   </th>
	   <th scope=\"col\" style=\"text-align: center;padding: 6px 8px 6px 8px;\">
		<span>$l_serving<br />$get_current_food_serving_size_metric $get_current_food_serving_size_measurement_metric ($get_current_food_serving_size_pcs $get_current_food_serving_size_pcs_measurement)</span>
	   </th>
	   <th scope=\"col\" style=\"text-align: center;padding: 6px 8px 6px 8px;\">
		<span>$l_net_content<br />$get_current_food_net_content_metric $get_current_food_net_content_measurement_metric (1 $l_pcs_lowercase)</span>
	   </th>
	   <th scope=\"col\" style=\"text-align: center;padding: 6px 8px 6px 8px;\" class=\"current_sub_category_calories_med\">
		<span>$l_median_for<br />
		$get_current_sub_category_translation_value</span>
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
	<span style=\"color: $energy_color;\">$get_current_food_energy_metric</span>
   </td>
   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
	<span style=\"color: $energy_color;\">$get_current_food_energy_calculated_metric</span>
   </td>
   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
	<span style=\"color: $energy_color;\">$get_current_food_energy_net_content</span>
   </td>
   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_med\">
	<span>$get_current_sub_category_calories_med_100_g</span>
   </td>
   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_diff\">
	<span style=\"color: $energy_color;\">$energy_diff_med</span>
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

$fat_color = "black";
if($fat_diff_med > 0){
	$fat_color = "red";
}
elseif($fat_diff_med < 0){
	$fat_color = "green";
}

$saturated_fat_color = "black";
if($saturated_fat_diff_med > 0){
	$saturated_fat_color = "red";
}
elseif($saturated_fat_diff_med < 0){
	$saturated_fat_color = "green";
}

$trans_fat_color = "black";
if($trans_fat_diff_med > 0){
	$trans_fat_color = "red";
}
elseif($trans_fat_diff_med < 0){
	$trans_fat_color = "green";
}

$monounsaturated_fat_color = "black";
if($monounsaturated_fat_diff_med > 0){
	$monounsaturated_fat_color = "red";
}
elseif($monounsaturated_fat_diff_med < 0){
	$monounsaturated_fat_color = "green";
}

$polyunsaturated_fat_color = "black";
if($polyunsaturated_fat_diff_med > 0){
	$polyunsaturated_fat_color = "red";
}
elseif($polyunsaturated_fat_diff_med < 0){
	$polyunsaturated_fat_color = "green";
}

echo"
  <tr>
   <td style=\"padding: 8px 4px 6px 8px;\">
	<span>$l_fat<br />
	$l_dash_saturated_fat<br />
	$l_dash_monounsaturated_fat<br />
	$l_dash_polyunsaturated_fat</span>
   </td>
  <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
	<span style=\"color: $fat_color;\">$get_current_food_fat_metric<br /></span>
	<span style=\"color: $saturated_fat_color;\">$get_current_food_saturated_fat_metric<br /></span>
	<span style=\"color: $monounsaturated_fat_color;\">$get_current_food_monounsaturated_fat_metric<br /></span>
	<span style=\"color: $polyunsaturated_fat_color;\">$get_current_food_polyunsaturated_fat_metric<br /></span>
   </td>
   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
	<span style=\"color: $fat_color;\">$get_current_food_fat_calculated_metric<br /></span>
	<span style=\"color: $saturated_fat_color;\">$get_current_food_saturated_fat_calculated_metric<br /></span>
	<span style=\"color: $monounsaturated_fat_color;\">$get_current_food_monounsaturated_fat_calculated_metric<br /></span>
	<span style=\"color: $polyunsaturated_fat_color;\">$get_current_food_polyunsaturated_fat_calculated_metric<br /></span>
   </td>
   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
	<span style=\"color: $fat_color;\">$get_current_food_fat_net_content<br /></span>
	<span style=\"color: $saturated_fat_color;\">$get_current_food_saturated_fat_net_content<br /></span>
	<span style=\"color: $monounsaturated_fat_color;\">$get_current_food_monounsaturated_fat_net_content<br /></span>
	<span style=\"color: $polyunsaturated_fat_color;\">$get_current_food_polyunsaturated_fat_net_content<br /></span>
   </td>
   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_med\">
	<span>$get_current_sub_category_fat_med_100_g<br />
	$get_current_sub_category_saturated_fat_med_100_g<br />
	$get_current_sub_category_monounsaturated_fat_med_100_g<br />
	$get_current_sub_category_polyunsaturated_fat_med_100_g</span>
   </td>
   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_diff\">
	<span style=\"color: $fat_color;\">$fat_diff_med<br /></span>
	<span style=\"color: $saturated_fat_color;\">$saturated_fat_diff_med<br /></span>
	<span style=\"color: $monounsaturated_fat_color;\">$monounsaturated_fat_diff_med<br /></span>
	<span style=\"color: $polyunsaturated_fat_color;\">$polyunsaturated_fat_diff_med<br /></span>
   </td>
  </tr>
";

// Carbs
$carbohydrate_diff_med = round($get_current_food_carbohydrates_metric-$get_current_sub_category_carb_med_100_g, 0);
$carbohydrates_of_which_sugars_diff_med = round($get_current_food_carbohydrates_of_which_sugars_metric-$get_current_sub_category_carb_of_which_sugars_med_100_g, 0);
$added_sugars_diff_med = round($get_current_food_added_sugars_metric-$get_current_sub_category_added_sugars_med_100_g, 0);

$carbohydrate_color = "black";
if($carbohydrate_diff_med > 0){
	$carbohydrate_color = "red";
}
elseif($carbohydrate_diff_med < 0){
	$carbohydrate_color = "green";
}

$carbohydrate_of_which_sugars_color = "black";
if($carbohydrates_of_which_sugars_diff_med > 0){
	$carbohydrate_of_which_sugars_color = "red";
}
elseif($carbohydrates_of_which_sugars_diff_med < 0){
	$carbohydrate_of_which_sugars_color = "green";
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
	<span>$l_dash_of_which_sugars<br /></span>
	<span>$l_dash_added_sugars</span>
   </td>
   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
	<span style=\"color: $carbohydrate_color;\">$get_current_food_carbohydrates_metric<br /></span>
	<span style=\"color: $carbohydrate_of_which_sugars_color;\">$get_current_food_carbohydrates_of_which_sugars_metric<br /></span>
	<span style=\"color: $added_sugars_color;\">$get_current_food_added_sugars_metric<br /></span>
   </td>
   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
	<span style=\"color: $carbohydrate_color;\">$get_current_food_carbohydrates_calculated_metric<br /></span>
	<span style=\"color: $carbohydrate_of_which_sugars_color;\">$get_current_food_carbohydrates_of_which_sugars_calculated_metric<br /></span>
	<span style=\"color: $added_sugars_color;\">$get_current_food_added_sugars_calculated_metric<br /></span>
   </td>
   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
	<span style=\"color: $carbohydrate_color;\">$get_current_food_carbohydrates_net_content<br /></span>
	<span style=\"color: $carbohydrate_of_which_sugars_color;\">$get_current_food_carbohydrates_of_which_sugars_net_content<br /></span>
	<span style=\"color: $added_sugars_color;\">$get_current_food_added_sugars_net_content<br /></span>
   </td>
   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_med\">
	<span>$get_current_sub_category_carb_med_100_g<br /></span>
	<span>$get_current_sub_category_carb_of_which_sugars_med_100_g<br /></span>
	<span>$get_current_sub_category_added_sugars_med_100_g<br /></span>
   </td>
   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_diff\">
	<span style=\"color: $carbohydrate_color;\">$carbohydrate_diff_med<br /></span>
	<span style=\"color: $carbohydrate_of_which_sugars_color;\">$carbohydrates_of_which_sugars_diff_med<br /></span>
	<span style=\"color: $added_sugars_color;\">$added_sugars_diff_med<br /></span>
   </td>
  </tr>

";

// Dietary fiber
$dietary_fiber_diff_med = round($get_current_food_dietary_fiber_metric-$get_current_sub_category_dietary_fiber_med_100_g, 0);
if($dietary_fiber_diff_med != 0){
	$dietary_fiber_diff_med = $dietary_fiber_diff_med*-1;
}

$dietary_fiber_color = "black";
if($dietary_fiber_diff_med > 0){
	$dietary_fiber_color = "red";
}
elseif($dietary_fiber_diff_med < 0){
	$dietary_fiber_color = "green";
}
echo"
  <tr>
   <td style=\"padding: 8px 4px 6px 8px;\">
	<span>$l_dietary_fiber<br /></span>
   </td>
   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
	<span style=\"color: $dietary_fiber_color;\">$get_current_food_dietary_fiber_metric</span>
   </td>
   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
	<span style=\"color: $dietary_fiber_color;\">$get_current_food_dietary_fiber_calculated_metric</span>
   </td>
   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
	<span style=\"color: $dietary_fiber_color;\">$get_current_food_dietary_fiber_net_content</span>
   </td>
   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_med\">
	<span>$get_current_sub_category_dietary_fiber_med_100_g</span>
   </td>
   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_diff\">
	<span style=\"color: $dietary_fiber_color;\">$dietary_fiber_diff_med</span>
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
	<span style=\"color: $proteins_color;\">$get_current_food_proteins_calculated_metric</span>
   </td>
   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
	<span style=\"color: $proteins_color;\">$get_current_food_proteins_net_content</span>
   </td>
   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_med\">
	<span>$get_current_sub_category_proteins_med_100_g</span>
   </td>
   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_diff\">
	<span style=\"color: $proteins_color;\">$proteins_diff_med</span>
   </td>
  </tr>

";

// Salt
$salt_diff_med = round($get_current_food_salt_metric-$get_current_sub_category_salt_med_100_g, 0);
$sodium_diff_med = round($get_current_food_sodium_metric-$get_current_sub_category_sodium_med_100_g, 0);

$salt_color = "black";
if($salt_diff_med > 0){
	$salt_color = "red";
}
elseif($salt_diff_med < 0){
	$salt_color = "green";
}

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
	<span>$l_salt_in_gram<br />
	$l_dash_of_which_sodium_in_mg</span>
   </td>
   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
	<span style=\"color: $salt_color;\">$get_current_food_salt_metric<br /></span>
	<span style=\"color: $sodium_color;\">$get_current_food_sodium_metric</span>
   </td>
   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
	<span style=\"color: $salt_color;\">$get_current_food_salt_calculated_metric<br /></span>
	<span style=\"color: $sodium_color;\">$get_current_food_sodium_calculated_metric</span>
   </td>
   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
	<span style=\"color: $salt_color;\">$get_current_food_salt_net_content<br /></span>
	<span style=\"color: $sodium_color;\">$get_current_food_sodium_net_content</span>
   </td>
   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_med\">
	<span>$get_current_sub_category_salt_med_100_g<br />
	$get_current_sub_category_sodium_med_100_g</span>
   </td>
   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_diff\">
	<span style=\"color: $salt_color;\">$salt_diff_med<br /></span>
	<span style=\"color: $sodium_color;\">$sodium_diff_med</span>
   </td>
  </tr>

";
// Cholesterol
$cholesterol_diff_med = round($get_current_food_cholesterol_metric-$get_current_sub_category_cholesterol_med_100_g, 0);

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
	<span style=\"color: $cholesterol_color;\">$get_current_food_cholesterol_metric</span>
   </td>
   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
	<span style=\"color: $cholesterol_color;\">$get_current_food_cholesterol_calculated_metric</span>
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
 </tbody>
</table>



";


?>