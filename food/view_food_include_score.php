<?php 
/**
*
* File: food/view_food_include_score.php
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



// Update score
$sodium_diff_med_gram = $sodium_diff_med/1000;

$score_number = $energy_diff_med
		+$fat_diff_med+$saturated_fat_diff_med+$trans_fat_diff_med+$monounsaturated_fat_diff_med+$polyunsaturated_fat_diff_med
		+$sodium_diff_med_gram
		+$cholesterol_diff_med
		+$carbohydrate_diff_med+$dietary_fiber_diff_med+$carbohydrates_of_which_sugars_diff_med+$added_sugars_diff_med
		+$proteins_diff_med
		+$salt_diff_med;
$score_number = round($score_number, 0);
if($get_current_food_score != $score_number){
	$result = mysqli_query($link, "UPDATE $t_food_index SET food_score='$score_number' WHERE food_id='$get_current_food_id'") or die(mysqli_error($link));
	echo"
	<div class=\"info\"><p>Updated score from $get_current_food_score to $score_number</p></div>
	";
}

if($get_current_restriction_show_smileys == "1"){
	echo"
	<p>
	<b>$l_score:</b>
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

	<!-- Show calculation script -->
		<script>
		\$(document).ready(function(){
			\$(\".a_show_calculation\").click(function () {
				\$(\".current_sub_category_calories_med\").toggle();
				\$(\".current_sub_category_calories_diff\").toggle();
			});
		});
		</script>
	<!-- //Show calculation script -->

	<div class=\"score_calculation\">
		<h2>$l_score_calculation_headline</h2>

		<a href=\"#nutrition_facts\" class=\"a_show_calculation\">$l_show_calculation</a>


		<table class=\"hor-zebra\" style=\"width: auto;\">
		 <thead>
		  <tr>
		   <th>
			<span>$l_nutrition</span>
		   </th>
		   <th>
			
		   </th>
		   <th>
			<span>$l_diff_from_median</span>
		   </th>
		  </tr>
		 </thead>
		 <tbody>
		  <tr>
		   <td>
			<span>energy_diff_med</span>
		   </td>
		   <td>
			<span>"; if($energy_diff_med > -1){ echo"+"; } echo"</span>
		   </td>
		   <td>
			<span>$energy_diff_med</span>
		   </td>
		  </tr>
		  <tr>
		   <td>
			<span>+fat_diff_med<br />
			+saturated_fat_diff_med<br />
			+trans_fat_diff_med<br />
			+monounsaturated_fat_diff_med<br />
			+polyunsaturated_fat_diff_med</span>
		   </td>
		   <td>
			<span>"; if($fat_diff_med > -1){ echo"+"; } echo"<br />
			"; if($saturated_fat_diff_med > -1){ echo"+"; } echo"<br />
			"; if($trans_fat_diff_med > -1){ echo"+"; } echo"<br />
			"; if($monounsaturated_fat_diff_med > -1){ echo"+"; } echo"<br />
			"; if($polyunsaturated_fat_diff_med > -1){ echo"+"; } echo"</span>
		   </td>
		   <td>
			<span>$fat_diff_med<br />
			$saturated_fat_diff_med<br />
			$trans_fat_diff_med<br />
			$monounsaturated_fat_diff_med<br />
			$polyunsaturated_fat_diff_med</span>
		   </td>
		  </tr>
		  <tr>
		   <td>
			<span>+sodium_diff_med</span>
		   </td>
		   <td>
			<span>
			"; if($sodium_diff_med_gram > -1){ echo"+"; } echo"
			</span>
		   </td>
		   <td>
			<span>
			$sodium_diff_med_gram
			</span>
		   </td>
		  </tr>
		  <tr>
		   <td>
			<span>+cholesterol_diff_med</span>
		   </td>
		   <td>
			<span>"; if($cholesterol_diff_med > -1){ echo"+"; } echo"</span>
		   </td>
		   <td>
			<span>$cholesterol_diff_med</span>
		   </td>
		  </tr>
		  <tr>
		   <td>
			<span>+carbohydrate_diff_med<br />
			+dietary_fiber_diff_med<br />
			+carbohydrates_of_which_sugars_diff_med<br />
			+added_sugars_diff_med</span>
		   </td>
		   <td>
			<span>"; if($carbohydrate_diff_med > -1){ echo"+"; } echo"<br />
			"; if($dietary_fiber_diff_med > -1){ echo"+"; } echo"<br />
			"; if($carbohydrates_of_which_sugars_diff_med > -1){ echo"+"; } echo"<br />
			"; if($added_sugars_diff_med > -1){ echo"+"; } echo"</span>
		   </td>
		   <td>
			<span>$carbohydrate_diff_med<br />
			$dietary_fiber_diff_med<br />
			$carbohydrates_of_which_sugars_diff_med<br />
			$added_sugars_diff_med</span>
		   </td>
		  </tr>
		  <tr>
		   <td>
			<span>+proteins_diff_med</span>
		   </td>
		   <td>
			<span>"; if($proteins_diff_med > -1){ echo"+"; } echo"</span>
		   </td>
		   <td>
			<span>$proteins_diff_med</span>
		   </td>
		  </tr>
		  <tr>
		   <td>
			<span>+salt_diff_med</span>
		   </td>
		   <td>
			<span>"; if($salt_diff_med > -1){ echo"+"; } echo"</span>
		   </td>
		   <td>
			<span>$salt_diff_med</span>
		   </td>
		  </tr>
		  <tr>
		   <td>
			<span>=score_number</span>
		   </td>
		   <td>
			<span>=</span>
		   </td>
		   <td>
			<span>$score_number</span>
		   </td>
		  </tr>
		 </tbody>
		</table>
	</div>

	<p>*$l_monounsaturated_fat_polyunsaturated_fat_dietary_fiber_and_protein_diff_is_multiplied_with_minus_one_to_get_correct_calculation</p>

	";
}
?>