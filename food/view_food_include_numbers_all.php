<?php 
/**
*
* File: food/view_food_include_numbers_all.php
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
				   </th>";
				if($get_current_view_system == "all" OR $get_current_view_system == "metric"){
					echo"
					   <th scope=\"col\" style=\"text-align: center;padding: 6px 4px 6px 8px;vertical-align: bottom;\">
						<span>$l_per_100</span>
		 			  </th>
					   <th scope=\"col\" style=\"text-align: center;padding: 6px 8px 6px 8px;\">
						<span>$l_serving<br />$get_current_food_serving_size_metric $get_current_food_serving_size_measurement_metric ($get_current_food_serving_size_pcs $get_current_food_serving_size_pcs_measurement)</span>
					   </th>
					";
				}
				if($get_current_view_system == "all" OR $get_current_view_system == "us"){
					echo"
					  <th scope=\"col\" style=\"text-align: center;padding: 6px 4px 6px 8px;vertical-align: bottom;\">
						<span>$l_per_8 $get_current_food_net_content_measurement_us</span>
		 			   </th>
					   <th scope=\"col\" style=\"text-align: center;padding: 6px 8px 6px 8px;\">
						<span>$l_serving<br />$get_current_food_serving_size_us $get_current_food_serving_size_measurement_us ($get_current_food_serving_size_pcs $get_current_food_serving_size_pcs_measurement)</span>
					   </th>
					";
				}
				echo"
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
				$energy_diff_med = round($get_current_food_energy_metric-$get_current_sub_category_calories_med_metric, 0);
				echo"
				  <tr>
				   <td style=\"padding: 8px 4px 6px 8px;\">
					<span>$l_calories</span>
				   </td>";
				if($get_current_view_system == "all" OR $get_current_view_system == "metric"){
					echo"
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						";
						if($energy_diff_med > 0){
							echo"<span style=\"color: red;\">$get_current_food_energy_metric</span>";
						}
						elseif($energy_diff_med < 0){
							echo"<span style=\"color: green;\">$get_current_food_energy_metric</span>";
						}
						else{
							echo"<span>$get_current_food_energy_metric</span>";
						}
						echo"
					   </td>
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						";
						if($energy_diff_med > 0){
							echo"<span style=\"color: red;\">$get_current_food_energy_calculated_metric</span>";
						}
						elseif($energy_diff_med < 0){
							echo"<span style=\"color: green;\">$get_current_food_energy_calculated_metric</span>";
						}
						else{
							echo"<span>$get_current_food_energy_calculated_metric</span>";
						}
						echo"
					   </td>
					";
				}
				if($get_current_view_system == "all" OR $get_current_view_system == "us"){
					echo"
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						<!-- Per 8 -->
						";
						if($energy_diff_med > 0){
							echo"<span style=\"color: red;\">$get_current_food_energy_us</span>";
						}
						elseif($energy_diff_med < 0){
							echo"<span style=\"color: green;\">$get_current_food_energy_us</span>";
						}
						else{
							echo"<span>$get_current_food_energy_us</span>";
						}
						echo"
					   </td>
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						";
						if($energy_diff_med > 0){
							echo"<span style=\"color: red;\">$get_current_food_energy_calculated_us</span>";
						}
						elseif($energy_diff_med < 0){
							echo"<span style=\"color: green;\">$get_current_food_energy_calculated_us</span>";
						}
						else{
							echo"<span>$get_current_food_energy_calculated_us</span>";
						}
						echo"
					
					   </td>
					";
				}
				echo"
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
					";
						if($energy_diff_med > 0){
							echo"<span style=\"color: red;\">";
						}
						elseif($energy_diff_med < 0){
							echo"<span style=\"color: green;\">";
						}
						else{
							echo"<span>";
						}
						echo"$get_current_food_energy_net_content</span>
				   </td>
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_med\">
					<span>$get_current_sub_category_calories_med_metric</span>
				   </td>
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_diff\">
						";
						if($energy_diff_med > 0){
							echo"<span style=\"color: red;\">";
						}
						elseif($energy_diff_med < 0){
							echo"<span style=\"color: green;\">";
						}
						else{
							echo"<span>";
						}
						echo"$energy_diff_med</span>
				   </td>
				  </tr>
				";

				// Fat
				$fat_diff_med = round($get_current_food_fat_metric-$get_current_sub_category_fat_med_metric, 0);
				$saturated_fat_diff_med = round($get_current_food_saturated_fat_metric-$get_current_sub_category_saturated_fat_med_metric, 0);
				$monounsaturated_fat_diff_med = round($get_current_food_monounsaturated_fat_metric-$get_current_sub_category_monounsaturated_fat_med_metric, 0);
				$polyunsaturated_fat_diff_med = round($get_current_food_polyunsaturated_fat_metric-$get_current_sub_category_polyunsaturated_fat_med_metric, 0);

				echo"
				  <tr>
				   <td style=\"padding: 8px 4px 6px 8px;\">
					<span>$l_fat<br />
					$l_dash_saturated_fat<br />
					$l_dash_monounsaturated_fat<br />
					$l_dash_polyunsaturated_fat</span>
				   </td>";
				if($get_current_view_system == "all" OR $get_current_view_system == "metric"){
					echo"
		 			  <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						";
						if($fat_diff_med > 0){
							echo"<span style=\"color: red;\">$get_current_food_fat_metric<br /></span>";
						}
						elseif($fat_diff_med < 0){
							echo"<span style=\"color: green;\">$get_current_food_fat_metric<br /></span>";
						}
						else{
							echo"<span>$get_current_food_fat_metric<br /></span>";
						}

						if($saturated_fat_diff_med > 0){
							echo"<span style=\"color: red;\">$get_current_food_saturated_fat_metric<br /></span>";
						}
						elseif($saturated_fat_diff_med < 0){
							echo"<span style=\"color: green;\">$get_current_food_saturated_fat_metric<br /></span>";
						}
						else{
							echo"<span>$get_current_food_saturated_fat_metric<br /></span>";
						}

						if($monounsaturated_fat_diff_med > 0){
							echo"<span style=\"color: red;\">$get_current_food_monounsaturated_fat_metric<br /></span>";
						}
						elseif($saturated_fat_diff_med < 0){
							echo"<span style=\"color: green;\">$get_current_food_monounsaturated_fat_metric<br /></span>";
						}
						else{
							echo"<span>$get_current_food_monounsaturated_fat_metric<br /></span>";
						}

						if($polyunsaturated_fat_diff_med > 0){
							echo"<span style=\"color: red;\">$get_current_food_polyunsaturated_fat_metric<br /></span>";
						}
						elseif($polyunsaturated_fat_diff_med < 0){
							echo"<span style=\"color: green;\">$get_current_food_polyunsaturated_fat_metric<br /></span>";
						}
						else{
							echo"<span>$get_current_food_polyunsaturated_fat_metric<br /></span>";
						}
						echo"
					   </td>
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						";
						if($fat_diff_med > 0){
							echo"<span style=\"color: red;\">$get_current_food_fat_calculated_metric<br /></span>";
						}
						elseif($fat_diff_med < 0){
							echo"<span style=\"color: green;\">$get_current_food_fat_calculated_metric<br /></span>";
						}
						else{
							echo"<span>$get_current_food_fat_calculated_metric<br /></span>";
						}

						if($saturated_fat_diff_med > 0){
							echo"<span style=\"color: red;\">$get_current_food_saturated_fat_calculated_metric<br /></span>";
						}
						elseif($saturated_fat_diff_med < 0){
							echo"<span style=\"color: green;\">$get_current_food_saturated_fat_calculated_metric<br /></span>";
						}
						else{
							echo"<span>$get_current_food_saturated_fat_calculated_metric<br /></span>";
						}

						if($monounsaturated_fat_diff_med > 0){
							echo"<span style=\"color: red;\">$get_current_food_monounsaturated_fat_calculated_metric<br /></span>";
						}
						elseif($saturated_fat_diff_med < 0){
							echo"<span style=\"color: green;\">$get_current_food_monounsaturated_fat_calculated_metric<br /></span>";
						}
						else{
							echo"<span>$get_current_food_monounsaturated_fat_calculated_metric<br /></span>";
						}

						if($polyunsaturated_fat_diff_med > 0){
							echo"<span style=\"color: red;\">$get_current_food_polyunsaturated_fat_calculated_metric<br /></span>";
						}
						elseif($polyunsaturated_fat_diff_med < 0){
							echo"<span style=\"color: green;\">$get_current_food_polyunsaturated_fat_calculated_metric<br /></span>";
						}
						else{
							echo"<span>$get_current_food_polyunsaturated_fat_calculated_metric<br /></span>";
						}
						echo"

					   </td>
					";
				}
				if($get_current_view_system == "all" OR $get_current_view_system == "us"){
					echo"
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
					   </td>";
				}
				echo"
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
						<span>$get_current_sub_category_fat_med_metric<br />
						$get_current_sub_category_saturated_fat_med_metric<br />
						$get_current_sub_category_monounsaturated_fat_med_metric<br />
						$get_current_sub_category_polyunsaturated_fat_med_metric</span>
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
				// Carbs
				$carbohydrate_diff_med = round($get_current_food_carbohydrates_metric-$get_current_sub_category_carb_med_metric, 0);
				$carbohydrates_of_which_sugars_diff_med = round($get_current_food_carbohydrates_of_which_sugars_metric-$get_current_sub_category_carb_of_which_sugars_med_metric, 0);

				echo"
				  <tr>
				   <td style=\"padding: 8px 4px 6px 8px;\">
					<span>$l_carbs<br /></span>
					<span>$l_dash_of_which_sugars</span>
				   </td>";
				if($get_current_view_system == "all" OR $get_current_view_system == "metric"){
					echo"
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">";
						if($carbohydrate_diff_med > 0){
							echo"<span style=\"color: red;\">$get_current_food_carbohydrates_metric</span>";
						}
						elseif($carbohydrate_diff_med < 0){
							echo"<span style=\"color: green;\">$get_current_food_carbohydrates_metric</span>";
						}
						else{
							echo"<span>$get_current_food_carbohydrates_metric</span>";
						}

						if($carbohydrates_of_which_sugars_diff_med > 0){
							echo"<span style=\"color: red;\"><br />$get_current_food_carbohydrates_of_which_sugars_metric</span>";
						}
						elseif($carbohydrates_of_which_sugars_diff_med < 0){
							echo"<span style=\"color: green;\"><br />$get_current_food_carbohydrates_of_which_sugars_metric</span>";
						}
						else{
							echo"<span><br />$get_current_food_carbohydrates_of_which_sugars_metric</span>";
						}
						echo"

					   </td>
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						";
						if($carbohydrate_diff_med > 0){
							echo"<span style=\"color: red;\">$get_current_food_carbohydrates_calculated_metric</span>";
						}
						elseif($carbohydrate_diff_med < 0){
							echo"<span style=\"color: green;\">$get_current_food_carbohydrates_calculated_metric</span>";
						}
						else{
							echo"<span>$get_current_food_carbohydrates_calculated_metric</span>";
						}

						if($carbohydrates_of_which_sugars_diff_med > 0){
							echo"<span style=\"color: red;\"><br />$get_current_food_carbohydrates_of_which_sugars_calculated_metric</span>";
						}
						elseif($carbohydrates_of_which_sugars_diff_med < 0){
							echo"<span style=\"color: green;\"><br />$get_current_food_carbohydrates_of_which_sugars_calculated_metric</span>";
						}
						else{
							echo"<span><br />$get_current_food_carbohydrates_of_which_sugars_calculated_metric</span>";
						}
						echo"
					   </td>
					";
				}
				if($get_current_view_system == "all" OR $get_current_view_system == "us"){
					echo"
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						";
						if($carbohydrate_diff_med > 0){
							echo"<span style=\"color: red;\">$get_current_food_carbohydrates_us</span>";
						}
						elseif($carbohydrate_diff_med < 0){
							echo"<span style=\"color: green;\">$get_current_food_carbohydrates_us</span>";
						}
						else{
							echo"<span>$get_current_food_carbohydrates_us</span>";
						}

						if($carbohydrates_of_which_sugars_diff_med > 0){
							echo"<span style=\"color: red;\"><br />$get_current_food_carbohydrates_of_which_sugars_us</span>";
						}
						elseif($carbohydrates_of_which_sugars_diff_med < 0){
							echo"<span style=\"color: green;\"><br />$get_current_food_carbohydrates_of_which_sugars_us</span>";
						}
						else{
							echo"<span><br />$get_current_food_carbohydrates_of_which_sugars_us</span>";
						}
						echo"
					   </td>
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						";
						if($carbohydrate_diff_med > 0){
							echo"<span style=\"color: red;\">$get_current_food_carbohydrates_calculated_us</span>";
						}
						elseif($carbohydrate_diff_med < 0){
							echo"<span style=\"color: green;\">$get_current_food_carbohydrates_calculated_us</span>";
						}
						else{
							echo"<span>$get_current_food_carbohydrates_calculated_us</span>";
						}

						if($carbohydrates_of_which_sugars_diff_med > 0){
							echo"<span style=\"color: red;\"><br />$get_current_food_carbohydrates_of_which_sugars_calculated_us</span>";
						}
						elseif($carbohydrates_of_which_sugars_diff_med < 0){
							echo"<span style=\"color: green;\"><br />$get_current_food_carbohydrates_of_which_sugars_calculated_us</span>";
						}
						else{
							echo"<span><br />$get_current_food_carbohydrates_of_which_sugars_calculated_us</span>";
						}
						echo"
					   </td>
					";
				}
				echo"

				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						";
						if($carbohydrate_diff_med > 0){
							echo"<span style=\"color: red;\">";
						}
						elseif($carbohydrate_diff_med < 0){
							echo"<span style=\"color: green;\">";
						}
						else{
							echo"<span>";
						}
						echo"$get_current_food_carbohydrates_net_content</span>";

						if($carbohydrates_of_which_sugars_diff_med > 0){
							echo"<span style=\"color: red;\"><br />";
						}
						elseif($carbohydrates_of_which_sugars_diff_med < 0){
							echo"<span style=\"color: green;\"><br />";
						}
						else{
							echo"<span><br />";
						}
						echo"$get_current_food_carbohydrates_of_which_sugars_net_content</span>
				   </td>
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_med\">
						<span>$get_current_sub_category_carb_med_metric<br /></span>
						<span>$get_current_sub_category_carb_of_which_sugars_med_metric</span>
					   </td>
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_diff\">";
		
						if($carbohydrate_diff_med > 0){
							echo"<span style=\"color: red;\">$carbohydrate_diff_med</span>";
						}
						elseif($carbohydrate_diff_med < 0){
							echo"<span style=\"color: green;\">$carbohydrate_diff_med</span>";
						}
						else{
							echo"<span>$carbohydrate_diff_med</span>";
						}
						// Sugar
						if($carbohydrates_of_which_sugars_diff_med > 0){
							echo"<span style=\"color: red;\"><br />$carbohydrates_of_which_sugars_diff_med</span>";
						}
						elseif($carbohydrates_of_which_sugars_diff_med < 0){
							echo"<span style=\"color: green;\"><br />$carbohydrates_of_which_sugars_diff_med</span>";
						}
						else{
							echo"<span><br />$carbohydrates_of_which_sugars_diff_med</span>";
						}
						echo"
					   </td>
				  </tr>

				";
				// Dietary fiber
				$dietary_fiber_diff_med = round($get_current_food_dietary_fiber_metric-$get_current_sub_category_dietary_fiber_med_metric, 0);
	
				echo"
				  <tr>
				   <td style=\"padding: 8px 4px 6px 8px;\">
					<span>$l_dietary_fiber<br /></span>
				   </td>";
				if($get_current_view_system == "all" OR $get_current_view_system == "metric"){
					echo"
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						";
						if($dietary_fiber_diff_med > 0){
							echo"<span style=\"color: red;\">";
						}
						elseif($dietary_fiber_diff_med < 0){
							echo"<span style=\"color: green;\">";
						}
						else{
							echo"<span>";
						}
						echo"$get_current_food_dietary_fiber_metric</span>

					   </td>
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						";
						if($dietary_fiber_diff_med > 0){
							echo"<span style=\"color: red;\">";
						}
						elseif($dietary_fiber_diff_med < 0){
							echo"<span style=\"color: green;\">";
						}
						else{
							echo"<span>";
						}
						echo"$get_current_food_dietary_fiber_calculated_metric</span>
					   </td>

					";
				}
				if($get_current_view_system == "all" OR $get_current_view_system == "us"){
					echo"
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						";
						if($dietary_fiber_diff_med > 0){
							echo"<span style=\"color: red;\">";
						}
						elseif($dietary_fiber_diff_med < 0){
							echo"<span style=\"color: green;\">";
						}
						else{
							echo"<span>";
						}
						echo"$get_current_food_dietary_fiber_us</span>
					   </td>
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						";
						if($dietary_fiber_diff_med > 0){
							echo"<span style=\"color: red;\">";
						}
						elseif($dietary_fiber_diff_med < 0){
							echo"<span style=\"color: green;\">";
						}
						else{
							echo"<span>";
						}
						echo"$get_current_food_dietary_fiber_calculated_us</span>
					   </td>
					";
				}
				echo"
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						";
						if($dietary_fiber_diff_med > 0){
							echo"<span style=\"color: red;\">";
						}
						elseif($dietary_fiber_diff_med < 0){
							echo"<span style=\"color: green;\">";
						}
						else{
							echo"<span>";
						}
						echo"$get_current_food_dietary_fiber_net_content</span>
				   </td>
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_med\">
						<span>$get_current_sub_category_dietary_fiber_med_metric</span>
					   </td>
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_diff\">";
						// Fiber
			
						if($dietary_fiber_diff_med > 0){
							echo"<span style=\"color: red;\">";
						}
						elseif($dietary_fiber_diff_med < 0){
							echo"<span style=\"color: green;\">";
						}
						else{
							echo"<span>";
						}
						echo"$dietary_fiber_diff_med</span>
					   </td>
				  </tr>


				";
				// Proteins
				$proteins_diff_med = round($get_current_food_proteins_metric-$get_current_sub_category_proteins_med_metric, 0);
				$proteins_diff_med = $proteins_diff_med*-1;
				echo"
				  <tr>
				   <td style=\"padding: 8px 4px 6px 8px;\">
					<span>$l_proteins</span>
				   </td>";
				if($get_current_view_system == "all" OR $get_current_view_system == "metric"){
					echo"
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						";
						if($proteins_diff_med < 0){
							echo"<span style=\"color: green;\">";
						}
						elseif($proteins_diff_med > 0){
							echo"<span style=\"color: red;\">";
						}
						else{
							echo"<span>";
						}
						echo"$get_current_food_proteins_metric</span>
					   </td>
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						";
						if($proteins_diff_med < 0){
							echo"<span style=\"color: green;\">";
						}
						elseif($proteins_diff_med > 0){
							echo"<span style=\"color: red;\">";
						}
						else{
							echo"<span>";
						}
						echo"$get_current_food_proteins_calculated_metric</span>
		 			  </td>

					";
				}
				if($get_current_view_system == "all" OR $get_current_view_system == "us"){
					echo"
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						";
						if($proteins_diff_med < 0){
							echo"<span style=\"color: green;\">";
						}
						elseif($proteins_diff_med > 0){
							echo"<span style=\"color: red;\">";
						}
						else{
							echo"<span>";
						}
						echo"$get_current_food_proteins_us</span>
					   </td>
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						";
						if($proteins_diff_med < 0){
							echo"<span style=\"color: green;\">";
						}
						elseif($proteins_diff_med > 0){
							echo"<span style=\"color: red;\">";
						}
						else{
							echo"<span>";
						}
						echo"$get_current_food_proteins_calculated_us</span>
		 			  </td>
					";
				}
				echo"
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
					";
					if($proteins_diff_med < 0){
						echo"<span style=\"color: green;\">";
					}
					elseif($proteins_diff_med > 0){
						echo"<span style=\"color: red;\">";
					}
					else{
						echo"<span>";
					}
					echo"$get_current_food_proteins_net_content</span>
				   </td>
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_med\">
						<span>$get_current_sub_category_proteins_med_metric</span>
					   </td>
				 	  <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_diff\">
						";
						if($proteins_diff_med < 0){
							echo"<span style=\"color: green;\">";
						}
						elseif($proteins_diff_med > 0){
							echo"<span style=\"color: red;\">";
						}
						else{
							echo"<span>";
						}
						echo"$proteins_diff_med*</span>
					   </td>
				  </tr>

				";
				// Salt
				$salt_diff_med = round($get_current_food_salt_metric-$get_current_sub_category_salt_med_metric, 0);
				$sodium_diff_med = round($get_current_food_sodium_metric-$get_current_sub_category_sodium_med_metric, 0);
				echo"
				  <tr>
				   <td style=\"padding: 8px 4px 6px 8px;\">
					<span>$l_salt_in_gram<br />
					$l_dash_of_which_sodium_in_mg</span>
				   </td>";
				if($get_current_view_system == "all" OR $get_current_view_system == "metric"){
					echo"
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						";
						if($salt_diff_med > 0){
							echo"<span style=\"color: red;\">";
						}
						elseif($salt_diff_med < 0){
							echo"<span style=\"color: green;\">";
						}
						else{
							echo"<span>";
						}
						echo"$get_current_food_salt_metric<br /></span>\n";

						if($sodium_diff_med > 0){
							echo"<span style=\"color: red;\">";
						}
						elseif($sodium_diff_med < 0){
							echo"<span style=\"color: green;\">";
						}
						else{
							echo"<span>";
						}
						echo"$get_current_food_sodium_metric</span>

					   </td>
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						";
						if($salt_diff_med > 0){
							echo"<span style=\"color: red;\">";
						}
						elseif($salt_diff_med < 0){
							echo"<span style=\"color: green;\">";
						}
						else{
							echo"<span>";
						}
						echo"$get_current_food_salt_calculated_metric<br /></span>\n";

						if($sodium_diff_med > 0){
							echo"<span style=\"color: red;\">";
						}
						elseif($sodium_diff_med < 0){
							echo"<span style=\"color: green;\">";
						}
						else{
							echo"<span>";
						}
						echo"$get_current_food_sodium_calculated_metric</span>
					   </td>
					";
				}
				if($get_current_view_system == "all" OR $get_current_view_system == "us"){
					echo"
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						";
						if($salt_diff_med > 0){
							echo"<span style=\"color: red;\">";
						}
						elseif($salt_diff_med < 0){
							echo"<span style=\"color: green;\">";
						}
						else{
							echo"<span>";
						}
						echo"$get_current_food_salt_us<br /></span>\n";

						if($sodium_diff_med > 0){
							echo"<span style=\"color: red;\">";
						}
						elseif($sodium_diff_med < 0){
							echo"<span style=\"color: green;\">";
						}
						else{
							echo"<span>";
						}
						echo"$get_current_food_sodium_us</span>
					   </td>
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						";
						if($salt_diff_med > 0){
							echo"<span style=\"color: red;\">";
						}
						elseif($salt_diff_med < 0){
							echo"<span style=\"color: green;\">";
						}
						else{
							echo"<span>";
						}
						echo"$get_current_food_salt_calculated_us<br /></span>\n";

						if($sodium_diff_med > 0){
							echo"<span style=\"color: red;\">";
						}
						elseif($sodium_diff_med < 0){
							echo"<span style=\"color: green;\">";
						}
						else{
							echo"<span>";
						}
						echo"$get_current_food_sodium_calculated_us</span>
					   </td>
					";
				}
				echo"
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						";
						if($salt_diff_med > 0){
							echo"<span style=\"color: red;\">";
						}
						elseif($salt_diff_med < 0){
							echo"<span style=\"color: green;\">";
						}
						else{
							echo"<span>";
						}
						echo"$get_current_food_salt_net_content<br /></span>\n";

						if($sodium_diff_med > 0){
							echo"<span style=\"color: red;\">";
						}
						elseif($sodium_diff_med < 0){
							echo"<span style=\"color: green;\">";
						}
						else{
							echo"<span>";
						}
						echo"$get_current_food_sodium_net_content</span>
				   </td>

					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_med\">
						<span>$get_current_sub_category_salt_med_metric<br />
						$get_current_sub_category_sodium_med_metric</span>
					   </td>
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_diff\">
						";
						if($salt_diff_med > 0){
							echo"<span style=\"color: red;\">";
						}
						elseif($salt_diff_med < 0){
							echo"<span style=\"color: green;\">";
						}
						else{
							echo"<span>";
						}
						echo"$salt_diff_med<br /></span>\n";

						if($sodium_diff_med > 0){
							echo"<span style=\"color: red;\">";
						}
						elseif($sodium_diff_med < 0){
							echo"<span style=\"color: green;\">";
						}
						else{
							echo"<span>";
						}
						echo"$sodium_diff_med</span>
					   </td>
				  </tr>

				";
				// Cholesterol
				$cholesterol_diff_med = round($get_current_food_cholesterol_metric-$get_current_sub_category_cholesterol_med_metric, 0);
				
				echo"
				  <tr>
				   <td style=\"padding: 8px 4px 6px 8px;\">
					<span>$l_cholesterol_in_mg</span>
				   </td>";
				if($get_current_view_system == "all" OR $get_current_view_system == "metric"){
					echo"
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						";
						if($cholesterol_diff_med > 0){
							echo"<span style=\"color: red;\">";
						}
						elseif($cholesterol_diff_med < 0){
							echo"<span style=\"color: green;\">";
						}
						else{
							echo"<span>";
						}
						echo"$get_current_food_cholesterol_metric</span>
					   </td>
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						";
						if($cholesterol_diff_med > 0){
							echo"<span style=\"color: red;\">";
						}
						elseif($cholesterol_diff_med < 0){
							echo"<span style=\"color: green;\">";
						}
						else{
							echo"<span>";
						}
						echo"$get_current_food_cholesterol_calculated_metric</span>
		 			  </td>
					";
				}
				if($get_current_view_system == "all" OR $get_current_view_system == "us"){
					echo"
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						";
						if($cholesterol_diff_med > 0){
							echo"<span style=\"color: red;\">";
						}
						elseif($cholesterol_diff_med < 0){
							echo"<span style=\"color: green;\">";
						}
						else{
							echo"<span>";
						}
						echo"$get_current_food_cholesterol_us</span>
					   </td>
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						";
						if($cholesterol_diff_med > 0){
							echo"<span style=\"color: red;\">";
						}
						elseif($cholesterol_diff_med < 0){
							echo"<span style=\"color: green;\">";
						}
						else{
							echo"<span>";
						}
						echo"$get_current_food_cholesterol_calculated_us</span>
		 			  </td>
					";
				}
				echo"
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
					";
						if($cholesterol_diff_med > 0){
							echo"<span style=\"color: red;\">";
						}
						elseif($cholesterol_diff_med < 0){
							echo"<span style=\"color: green;\">";
						}
						else{
							echo"<span>";
						}
						echo"$get_current_food_cholesterol_net_content</span>
				   </td>

					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_med\">
						<span>$get_current_sub_category_cholesterol_med_metric</span>
					   </td>
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_diff\">
						";
						if($cholesterol_diff_med > 0){
							echo"<span style=\"color: red;\">";
						}
						elseif($cholesterol_diff_med < 0){
							echo"<span style=\"color: green;\">";
						}
						else{
							echo"<span>";
						}
						echo"$cholesterol_diff_med</span>
					   </td>
				 </tr>

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

				
				if($get_current_view_system == "all" OR $get_current_view_system == "metric"){
					$score_number = $energy_diff_med+$fat_diff_med+$saturated_fat_diff_med+$monounsaturated_fat_diff_med+$polyunsaturated_fat_diff_med+$carbohydrate_diff_med+$carbohydrates_of_which_sugars_diff_med+$dietary_fiber_diff_med+$proteins_diff_med+$salt_diff_med; // +$sodium_diff_med+$cholesterol_diff_med
	 				if($get_current_food_score != $score_number){
						$result = mysqli_query($link, "UPDATE $t_food_index SET food_score='$score_number' WHERE food_id='$get_current_food_id'") or die(mysqli_error($link));
					}
				}
				else{

				}

				if($get_current_restriction_show_smileys == "1"){
					echo"
					<p>
					<a href=\"#numbers\" class=\"a_show_score\">$l_score:</a> 
					";
					if($get_current_food_score > 0){
						echo"
						<em style=\"color: red;\">$get_current_food_score</em>
						<img src=\"_gfx/smiley_sad.png\" alt=\"smiley_sad.gif\" style=\"padding:0px 0px 0px 4px;\"  />";
					}
					elseif($get_current_food_score < 0){
						echo"
						<em style=\"color: green;\">$get_current_food_score</em>
						<img src=\"_gfx/smiley_smile.png\" alt=\"smiley_smile.png\" style=\"padding:0px 0px 0px 4px;\" />";
					}
					else{
						echo"
						<em>$get_current_food_score</em>
						<img src=\"_gfx/smiley_confused.png\" alt=\"smiley_confused.png\" style=\"padding:0px 0px 0px 4px;\" />";
					}
					echo"
					</p>
					<p class=\"protein_diff\">*$l_protein_diff_is_multiplied_with_minus_one_to_get_correct_calculation</p>
					";
				}
?>