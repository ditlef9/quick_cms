<?php
/**
*
* File: _admin/_inc/recipes/edit_recipe_ingredients.php
* Version 1.0.0
* Date 00:28 06.01.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables --------------------------------------------------------------------------- */
$t_recipes 	 	= $mysqlPrefixSav . "recipes";
$t_recipes_ingredients	= $mysqlPrefixSav . "recipes_ingredients";
$t_recipes_groups	= $mysqlPrefixSav . "recipes_groups";
$t_recipes_items	= $mysqlPrefixSav . "recipes_items";
$t_recipes_numbers	= $mysqlPrefixSav . "recipes_numbers";
$t_recipes_rating	= $mysqlPrefixSav . "recipes_rating";
$t_recipes_cuisines	= $mysqlPrefixSav . "recipes_cuisines";
$t_recipes_seasons	= $mysqlPrefixSav . "recipes_seasons";
$t_recipes_occasions	= $mysqlPrefixSav . "recipes_occasions";


/*- Variables ------------------------------------------------------------------------ */
if(isset($_GET['mode'])) {
	$mode = $_GET['mode'];
	$mode = strip_tags(stripslashes($mode));
}
else{
	$mode = "";
}
if(isset($_GET['recipe_id'])) {
	$recipe_id = $_GET['recipe_id'];
	$recipe_id = strip_tags(stripslashes($recipe_id));
}
else{
	$recipe_id = "";
}
if(isset($_GET['group_id'])) {
	$group_id = $_GET['group_id'];
	$group_id = strip_tags(stripslashes($group_id));
}
else{
	$group_id = "";
}
if(isset($_GET['item_id'])) {
	$item_id = $_GET['item_id'];
	$item_id = strip_tags(stripslashes($item_id));
}
else{
	$item_id = "";
}


/*- Translations --------------------------------------------------------------------- */
include("_translations/admin/$l/recipes/t_view_recipe.php");

// Select
$recipe_id_mysql = quote_smart($link, $recipe_id);
$query = "SELECT recipe_id, recipe_user_id, recipe_title, recipe_category_id, recipe_language, recipe_country, recipe_introduction, recipe_directions, recipe_image_path, recipe_image, recipe_thumb_278x156, recipe_date, recipe_time, recipe_cusine_id, recipe_season_id, recipe_occasion_id, recipe_marked_as_spam, recipe_unique_hits, recipe_unique_hits_ip_block, recipe_user_ip, recipe_notes, recipe_password FROM $t_recipes WHERE recipe_id=$recipe_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_recipe_id, $get_recipe_user_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_language, $get_recipe_country, $get_recipe_introduction, $get_recipe_directions, $get_recipe_image_path, $get_recipe_image, $get_recipe_thumb_278x156, $get_recipe_date, $get_recipe_time, $get_recipe_cusine_id, $get_recipe_season_id, $get_recipe_occasion_id, $get_recipe_marked_as_spam, $get_recipe_unique_hits, $get_recipe_unique_hits_ip_block, $get_recipe_user_ip, $get_recipe_notes, $get_recipe_password) = $row;

if($get_recipe_id == ""){
	echo"
	<h1>Recipe not found</h1>

	<p>
	The recipe you are trying to edit was not found.
	</p>

	<p>
	<a href=\"index.php?open=$open&amp;editor_language=$editor_language\">Back</a>
	</p>
	";
}
else{
	// Get numbers
	$query = "SELECT number_id, number_recipe_id, number_hundred_calories, number_hundred_proteins, number_hundred_fat, number_hundred_fat_of_which_saturated_fatty_acids, number_hundred_carbs, number_hundred_carbs_of_which_dietary_fiber, number_hundred_carbs_of_which_sugars, number_hundred_salt, number_hundred_sodium, number_serving_calories, number_serving_proteins, number_serving_fat, number_serving_fat_of_which_saturated_fatty_acids, number_serving_carbs, number_serving_carbs_of_which_dietary_fiber, number_serving_carbs_of_which_sugars, number_serving_salt, number_serving_sodium, number_total_weight, number_total_calories, number_total_proteins, number_total_fat, number_total_fat_of_which_saturated_fatty_acids, number_total_carbs, number_total_carbs_of_which_dietary_fiber, number_total_carbs_of_which_sugars, number_total_salt, number_total_sodium, number_servings FROM $t_recipes_numbers WHERE number_recipe_id=$recipe_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_number_id, $get_number_recipe_id, $get_number_hundred_calories, $get_number_hundred_proteins, $get_number_hundred_fat, $get_number_hundred_fat_of_which_saturated_fatty_acids, $get_number_hundred_carbs, $get_number_hundred_carbs_of_which_dietary_fiber, $get_number_hundred_carbs_of_which_sugars, $get_number_hundred_salt, $get_number_hundred_sodium, $get_number_serving_calories, $get_number_serving_proteins, $get_number_serving_fat, $get_number_serving_fat_of_which_saturated_fatty_acids, $get_number_serving_carbs, $get_number_serving_carbs_of_which_dietary_fiber, $get_number_serving_carbs_of_which_sugars, $get_number_serving_salt, $get_number_serving_sodium, $get_number_total_weight, $get_number_total_calories, $get_number_total_proteins, $get_number_total_fat, $get_number_total_fat_of_which_saturated_fatty_acids, $get_number_total_carbs, $get_number_total_carbs_of_which_dietary_fiber, $get_number_total_carbs_of_which_sugars, $get_number_total_salt, $get_number_total_sodium, $get_number_servings) = $row;
	if($get_number_id == ""){
		mysqli_query($link, "INSERT INTO $t_recipes_numbers
		(number_id, number_recipe_id, number_hundred_calories, number_hundred_proteins, number_hundred_fat, number_hundred_carbs, number_serving_calories, number_serving_proteins, number_serving_fat, number_serving_carbs, number_total_weight, number_total_calories, number_total_proteins, number_total_fat, number_total_carbs, number_servings) 
		VALUES 
		(NULL, '$get_recipe_id', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '4')")
		or die(mysqli_error($link));
	}



	if($action == ""){
		echo"
		<!-- Headline -->
			<div class=\"recipes_headline\">
				<h1>$get_recipe_title</h1>
			</div>
			<div class=\"recipes_buttons\">
				<p>
				<a href=\"../recipes/view_recipe.php?recipe_id=$get_recipe_id&amp;l=$get_recipe_language\" class=\"btn_default\">View</a>
				</p>
			</div>
			<div class=\"clear\"></div>
		<!-- //Headline -->


		<!-- Where am I ? -->
			<p><b>$l_you_are_here:</b><br />
			<a href=\"index.php?open=recipes&amp;page=default&amp;editor_language=$editor_language&amp;l=$l#recipe$recipe_id\">Recipes</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=edit_recipe_ingredients&amp;recipe_id=$recipe_id&amp;editor_language=$editor_language\">Ingredients</a>
			</p>
		<!-- //Where am I ? -->


		<!-- Menu -->
			<div class=\"tabs\">
				<ul>
					<li><a href=\"index.php?open=$open&amp;page=edit_recipe_general&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">General</a></li>
					<li><a href=\"index.php?open=$open&amp;page=edit_recipe_ingredients&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\" class=\"active\">Ingredients</a></li>
					<li><a href=\"index.php?open=$open&amp;page=edit_recipe_categorization&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Categorization</a></li>
					<li><a href=\"index.php?open=$open&amp;page=edit_recipe_image&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Image</a></li>
					<li><a href=\"index.php?open=$open&amp;page=edit_recipe_video&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Video</a></li>
					<li><a href=\"index.php?open=$open&amp;page=edit_recipe_tags&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Tags</a></li>
					<li><a href=\"index.php?open=$open&amp;page=edit_recipe_links&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Links</a></li>
					<li><a href=\"index.php?open=$open&amp;page=delete_recipe&amp;recipe_id=$recipe_id&amp;editor_language=$editor_language\">Delete</a>
			</ul>
			</div><p>&nbsp;</p>
		<!-- //Menu -->


		<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			elseif($fm == "please_confirm_that_you_want_to_delete_the_item"){
				$fm = "$l_please_confirm_that_you_want_to_delete_the_item <br /><br /><a href=\"index.php?open=recipes&amp;page=edit_recipe_ingredients&amp;action=delete_item&amp;recipe_id=$recipe_id&amp;group_id=$group_id&amp;item_id=$item_id&amp;editor_language=$editor_language&amp;process=1\" class=\"btn\">$l_confirm_delete</a>";
			}
			else{
				$fm = ucfirst($ft);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
		<!-- //Feedback -->


		<!-- Groups -->
			<p>
			<a href=\"index.php?open=recipes&amp;page=edit_recipe_ingredients&amp;action=new_group&amp;recipe_id=$recipe_id&amp;editor_language=$editor_language\" class=\"btn\">$l_new_group</a>
			</p>

				<table class=\"hor-zebra\">
				 <thead>
				  <tr>
			 	  <th scope=\"col\">
					<span>$l_group</span>
				   </th>
				   <th scope=\"col\">
					<span>
					
					</span>
		 		   </th>
				  </tr>
				 </thead>
				 <tbody>
			";
			$query_groups = "SELECT group_id, group_title FROM $t_recipes_groups WHERE group_recipe_id=$get_recipe_id";
			$result_groups = mysqli_query($link, $query_groups);
			while($row_groups = mysqli_fetch_row($result_groups)) {
				list($get_group_id, $get_group_title) = $row_groups;


				if(isset($style) && $style == "odd"){
					$style = "";
				}
				else{
					$style = "odd";
				}
				echo"
				<tr>
				   <td class=\"$style\">
					<span>$get_group_title</span>
				   </td>
				   <td class=\"$style\">
					<span>
					<a href=\"index.php?open=recipes&amp;page=edit_recipe_ingredients&amp;action=edit_group&amp;recipe_id=$recipe_id&amp;group_id=$get_group_id&amp;editor_language=$editor_language\">$l_edit</a>
					&middot;
					<a href=\"index.php?open=recipes&amp;page=edit_recipe_ingredients&amp;action=delete_group&amp;recipe_id=$recipe_id&amp;group_id=$get_group_id&amp;editor_language=$editor_language\">$l_delete</a>
					&middot;
					<a href=\"index.php?open=recipes&amp;page=edit_recipe_ingredients&amp;action=add_item&amp;recipe_id=$recipe_id&amp;group_id=$get_group_id&amp;editor_language=$editor_language\">Add item</a>
					</span>
		 		   </td>
				  </tr>
				";
				$query_items = "SELECT item_id, item_amount, item_measurement, item_grocery FROM $t_recipes_items WHERE item_group_id=$get_group_id";
				$result_items = mysqli_query($link, $query_items);
				$row_cnt = mysqli_num_rows($result_items);
				while($row_items = mysqli_fetch_row($result_items)) {
					list($get_item_id, $get_item_amount, $get_item_measurement, $get_item_grocery) = $row_items;


					if(isset($style) && $style == "odd"){
						$style = "";
					}
					else{
						$style = "odd";
					}
					if($mode == "delete_item" && $item_id == $get_item_id){ 
						$style = "danger";
					}
					echo"
					<tr>
					  <td class=\"$style\" style=\"padding-left: 20px;\">
						<span><a id=\"item_id$get_item_id\"></a>$get_item_amount $get_item_measurement $get_item_grocery</span>
					   </td>
					   <td class=\"$style\">
						<span>
						<a href=\"index.php?open=recipes&amp;page=edit_recipe_ingredients&amp;action=edit_item&amp;recipe_id=$recipe_id&amp;group_id=$get_group_id&amp;item_id=$get_item_id&amp;editor_language=$editor_language\">$l_edit</a>
						&middot;";

						if($mode == "delete_item" && $item_id == $get_item_id){ 
							echo" <a href=\"index.php?open=recipes&amp;page=edit_recipe_ingredients&amp;action=delete_item&amp;recipe_id=$recipe_id&amp;group_id=$get_group_id&amp;item_id=$get_item_id&amp;editor_language=$editor_language&amp;process=1\">$l_confirm_delete</a>";
						
						}
						else{
							echo"
							<a href=\"index.php?open=recipes&amp;page=edit_recipe_ingredients&amp;mode=delete_item&amp;recipe_id=$recipe_id&amp;group_id=$get_group_id&amp;item_id=$get_item_id&amp;editor_language=$editor_language&amp;ft=warning&amp;fm=please_confirm_that_you_want_to_delete_the_item#item_id$get_item_id\">$l_delete</a>
							";
						}
						echo"</span>
		 			   </td>
					  </tr>
					";
				}

			}
			echo"		
				 </tbody>
				</table>		
			</p>
		<!-- //Groups -->

		";
	} // action == ""
	elseif($action == "new_group" OR $action == "new_group_save"){
		if($action == "new_group_save"){
			// inp_group_title 
			$inp_group_title = $_POST['inp_group_title'];
			$inp_group_title = output_html($inp_group_title);
			$inp_group_title_mysql = quote_smart($link, $inp_group_title);
			if(empty($inp_group_title)){
				$ft = "error";
				$fm = "group_title_is_empty";
			}
			else{
				// Check if group already exists
				$query = "SELECT group_id FROM $t_recipes_groups WHERE group_recipe_id=$recipe_id_mysql AND group_title=$inp_group_title_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_group_id) = $row;
				if($get_group_id != ""){
					$ft = "error";
					$fm = "you_already_have_a_group_with_that_name";
				}
				else{
					// Insert group
					mysqli_query($link, "INSERT INTO $t_recipes_groups
					(group_id, group_recipe_id, group_title) 
					VALUES 
					(NULL, $recipe_id_mysql, $inp_group_title_mysql)")
					or die(mysqli_error($link));

					// Get ID
					$query = "SELECT group_id FROM $t_recipes_groups WHERE group_recipe_id=$recipe_id_mysql AND group_title=$inp_group_title_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_group_id) = $row;


					$ft = "success";
					$fm = "ingredients_saved";
							
					$loading = "<p style=\"font-weight: bold;\"><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float: left;padding-right: 5px;\" /> Loading</p>
					<meta http-equiv=refresh content=\"1; url=index.php?open=recipes&amp;page=edit_recipe_ingredients&amp;recipe_id=$recipe_id&amp;editor_language=$editor_language&amp;ft=$ft&amp;fm=$fm\">";



				} // Group alreaddy exists


			} // group title empty
		}
		echo"
		<!-- Headline -->
			<div class=\"recipes_headline\">
				<h1>$get_recipe_title</h1>
			</div>
			<div class=\"recipes_buttons\">
				<p>
				<a href=\"../recipes/view_recipe.php?recipe_id=$get_recipe_id&amp;l=$get_recipe_language\" class=\"btn_default\">View</a>
				</p>
			</div>
			<div class=\"clear\"></div>
		<!-- //Headline -->


		<!-- Where am I ? -->
			<p><b>$l_you_are_here:</b><br />
			<a href=\"index.php?open=recipes&amp;page=default&amp;editor_language=$editor_language&amp;l=$l#recipe$recipe_id\">Recipes</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=edit_recipe_ingredients&amp;recipe_id=$recipe_id&amp;editor_language=$editor_language\">Ingredients</a>
			</p>
		<!-- //Where am I ? -->


		<!-- Menu -->
			<div class=\"tabs\">
				<ul>
					<li><a href=\"index.php?open=$open&amp;page=edit_recipe_general&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">General</a></li>
					<li><a href=\"index.php?open=$open&amp;page=edit_recipe_ingredients&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\" class=\"active\">Ingredients</a></li>
					<li><a href=\"index.php?open=$open&amp;page=edit_recipe_categorization&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Categorization</a></li>
					<li><a href=\"index.php?open=$open&amp;page=edit_recipe_image&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Image</a></li>
					<li><a href=\"index.php?open=$open&amp;page=edit_recipe_video&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Video</a></li>
					<li><a href=\"index.php?open=$open&amp;page=edit_recipe_tags&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Tags</a></li>
					<li><a href=\"index.php?open=$open&amp;page=edit_recipe_links&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Links</a></li>
					<li><a href=\"index.php?open=$open&amp;page=delete_recipe&amp;recipe_id=$recipe_id&amp;editor_language=$editor_language\">Delete</a>
			</ul>
			</div><p>&nbsp;</p>
		<!-- //Menu -->
		
		<!-- Feedback -->
		";
		if(isset($loading)){
			echo"$loading";
		}
		else{
			if($ft != ""){
				if($fm == "group_title_is_empty"){
					$fm = "$l_group_title_is_empty";
				}
				elseif($fm == "you_already_have_a_group_with_that_name"){
					$fm = "$l_you_already_have_a_group_with_that_name";
				}
				else{
					$fm = ucfirst($ft);
				}
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
		}
		echo"	
		<!-- //Feedback -->



		<!-- Focus -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_group_title\"]').focus();
			});
			</script>
		<!-- //Focus -->


		<!-- New group form -->
			<form method=\"post\" action=\"index.php?open=recipes&amp;page=edit_recipe_ingredients&amp;action=new_group_save&amp;recipe_id=$recipe_id&amp;editor_language=$editor_language\" enctype=\"multipart/form-data\">
	
			<p><b>$l_group_title:</b><br />
			<input type=\"text\" name=\"inp_group_title\" value=\"\" size=\"40\" tabindex=\""; $tabindex = $tabindex + 1; echo"$tabindex\" />
			</p>

			<p><input type=\"submit\" value=\"$l_save\" class=\"submit\" /></p>
			</form>
		<!-- //New group form -->

		";
	} // new group
	elseif($action == "edit_group" OR $action == "edit_group_save"){
		// Find group
		$group_id_mysql = quote_smart($link, $group_id);
		$query = "SELECT group_id, group_recipe_id, group_title FROM $t_recipes_groups WHERE group_id=$group_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_group_id, $get_group_recipe_id, $get_group_title) = $row;
	
		if($get_group_id == ""){
			echo"
			<h1>Recipe group not found</h1>

			<p>
			<a href=\"index.php?open=$open&amp;page=edit_recipe_ingredients&amp;recipe_id=$recipe_id&amp;editor_language=$editor_language\" class=\"btn\">Back</a>
			</p>
			";
		}
		else{
			if($action == "edit_group_save"){

				// inp_group_title 
				$inp_group_title = $_POST['inp_group_title'];
				$inp_group_title = output_html($inp_group_title);
				$inp_group_title_mysql = quote_smart($link, $inp_group_title);
				if(empty($inp_group_title)){
					$ft = "error";
					$fm = "group_title_is_empty";
				}
				else{
					// Update group name
					$result = mysqli_query($link, "UPDATE $t_recipes_groups SET group_title=$inp_group_title_mysql WHERE group_id=$group_id_mysql") or die(mysqli_error($link));



					$ft = "success";
					$fm = "ingredients_saved";
						
					$loading = "<p style=\"font-weight: bold;\"><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float: left;padding-right: 5px;\" /> Loading</p>
					<meta http-equiv=refresh content=\"1; url=index.php?open=recipes&amp;page=edit_recipe_ingredients&amp;recipe_id=$recipe_id&amp;group_id=$group_id&amp;editor_language=$editor_language&amp;ft=$ft&amp;fm=$fm\">";


					// Give new info
					$group_id_mysql = quote_smart($link, $group_id);
					$query = "SELECT group_id, group_recipe_id, group_title FROM $t_recipes_groups WHERE group_id=$group_id_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_group_id, $get_group_recipe_id, $get_group_title) = $row;



				} // group title empty
			}
			
			echo"
			<!-- Headline -->
				<div class=\"recipes_headline\">
					<h1>$get_recipe_title</h1>
				</div>
				<div class=\"recipes_buttons\">
					<p>
					<a href=\"../recipes/view_recipe.php?recipe_id=$get_recipe_id&amp;l=$get_recipe_language\" class=\"btn_default\">View</a>
					</p>
				</div>
				<div class=\"clear\"></div>
			<!-- //Headline -->


			<!-- Where am I ? -->
				<p><b>$l_you_are_here:</b><br />
				<a href=\"index.php?open=recipes&amp;page=default&amp;editor_language=$editor_language&amp;l=$l#recipe$recipe_id\">Recipes</a>
				&gt;
				<a href=\"index.php?open=$open&amp;page=edit_recipe_ingredients&amp;recipe_id=$recipe_id&amp;editor_language=$editor_language\">Ingredients</a>
				</p>
			<!-- //Where am I ? -->
	

			<!-- Menu -->
				<div class=\"tabs\">
					<ul>
						<li><a href=\"index.php?open=$open&amp;page=edit_recipe_general&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">General</a></li>
						<li><a href=\"index.php?open=$open&amp;page=edit_recipe_ingredients&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\" class=\"active\">Ingredients</a></li>
						<li><a href=\"index.php?open=$open&amp;page=edit_recipe_categorization&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Categorization</a></li>
						<li><a href=\"index.php?open=$open&amp;page=edit_recipe_image&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Image</a></li>
						<li><a href=\"index.php?open=$open&amp;page=edit_recipe_video&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Video</a></li>
						<li><a href=\"index.php?open=$open&amp;page=edit_recipe_tags&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Tags</a></li>
						<li><a href=\"index.php?open=$open&amp;page=edit_recipe_links&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Links</a></li>
						<li><a href=\"index.php?open=$open&amp;page=delete_recipe&amp;recipe_id=$recipe_id&amp;editor_language=$editor_language\">Delete</a>
					</ul>
				</div><p>&nbsp;</p>
			<!-- //Menu -->
		
			<!-- Feedback -->
				";
				if(isset($loading)){
					echo"$loading";
				}
				else{
					if($ft != ""){
						if($fm == "group_title_is_empty"){
							$fm = "$l_group_title_is_empty";
						}
						elseif($fm == "you_already_have_a_group_with_that_name"){
							$fm = "$l_you_already_have_a_group_with_that_name";
						}
						else{
							$fm = ucfirst($ft);
						}
						echo"<div class=\"$ft\"><span>$fm</span></div>";
					}
				}
				echo"	
			<!-- //Feedback -->



			<!-- Focus -->
			<script>
			\$(document).ready(function(){\n";
				if($mode == "edit_item"){
					echo"				";
					echo"\$('[name=\"inp_item_amount_$item_id\"]').focus();";
				}
				else{
					echo"				";
					echo"\$('[name=\"inp_group_title\"]').focus();";
				}
				echo"
			});
			</script>
			<!-- //Focus -->


			<!-- Edit group form -->
			<form method=\"post\" action=\"index.php?open=recipes&amp;page=edit_recipe_ingredients&amp;action=edit_group_save&amp;recipe_id=$recipe_id&amp;group_id=$group_id&amp;editor_language=$editor_language&amp;l=$l\" enctype=\"multipart/form-data\">
	
			<p><b>$l_group_title:</b><br />
			<input type=\"text\" name=\"inp_group_title\" value=\"$get_group_title\" size=\"40\" tabindex=\""; $tabindex = $tabindex + 1; echo"$tabindex\" />
			</p>
			<p><input type=\"submit\" value=\"$l_save_changes\" class=\"submit\" /></p>
			</form>
			<!-- //Edit group form -->

			";

		} // recipe group found
	} // edit_group
	elseif($action == "delete_item"){
		// Find group
		$group_id_mysql = quote_smart($link, $group_id);
		$query = "SELECT group_id, group_recipe_id, group_title FROM $t_recipes_groups WHERE group_id=$group_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_group_id, $get_group_recipe_id, $get_group_title) = $row;
	
		if($get_group_id == ""){
			echo"
			<h1>Recipe group not found</h1>

			<p>
			<a href=\"index.php?open=$open&amp;page=edit_recipe_ingredients&amp;recipe_id=$recipe_id&amp;editor_language=$editor_language\" class=\"btn\">Back</a>
			</p>
			";
		}
		else{
			// Find item
			$item_id_mysql = quote_smart($link, $item_id);
			$query = "SELECT item_id FROM $t_recipes_items WHERE item_id=$item_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_item_id) = $row;

			if($get_item_id == ""){
				echo"
				<h1>Item not found</h1>

				<p>
				<a href=\"index.php?open=$open&amp;page=edit_recipe_ingredients&amp;recipe_id=$recipe_id&amp;editor_language=$editor_language\" class=\"btn\">Back</a>
				</p>
				";
			}
			else{

				// Delete item
				$result = mysqli_query($link, "DELETE FROM $t_recipes_items WHERE item_id=$get_item_id") or die(mysqli_error($link));

				// Header
				$prev_item = $get_item_id-1;
				$url = "index.php?open=recipes&page=edit_recipe_ingredients&mode=delete_item&recipe_id=$recipe_id&group_id=$group_id&editor_language=$editor_language&ft=success&fm=item_deleted#item$prev_item";
				header("Location: $url");
				exit;
			}
		}

	}
	elseif($action == "delete_group"){
		// Find group
		$group_id_mysql = quote_smart($link, $group_id);
		$query = "SELECT group_id, group_recipe_id, group_title FROM $t_recipes_groups WHERE group_id=$group_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_group_id, $get_group_recipe_id, $get_group_title) = $row;
	
		if($get_group_id == ""){
			echo"
			<h1>Recipe group not found</h1>

			<p>
			<a href=\"index.php?open=$open&amp;page=edit_recipe_ingredients&amp;recipe_id=$recipe_id&amp;editor_language=$editor_language\" class=\"btn\">Back</a>
			</p>
			";
		}
		else{
			if($process == 1){

				// Delete groups
				$result = mysqli_query($link, "DELETE FROM $t_recipes_groups WHERE group_id=$get_group_id") or die(mysqli_error($link));

				// Delete items
				$result = mysqli_query($link, "DELETE FROM $t_recipes_items WHERE item_group_id=$get_group_id") or die(mysqli_error($link));

				// Header
				$url = "index.php?open=recipes&page=edit_recipe_ingredients&recipe_id=$recipe_id&group_id=$group_id&editor_language=$editor_language&ft=success&fm=group_delete";
				header("Location: $url");
				exit;
			}
			echo"
			<!-- Headline -->
				<div class=\"recipes_headline\">
					<h1>$get_recipe_title</h1>
				</div>
				<div class=\"recipes_buttons\">
					<p>
					<a href=\"../recipes/view_recipe.php?recipe_id=$get_recipe_id&amp;l=$get_recipe_language\" class=\"btn_default\">View</a>
					</p>
				</div>
				<div class=\"clear\"></div>
			<!-- //Headline -->


			<!-- Where am I ? -->
				<p><b>$l_you_are_here:</b><br />
				<a href=\"index.php?open=recipes&amp;page=default&amp;editor_language=$editor_language&amp;l=$l#recipe$recipe_id\">Recipes</a>
				&gt;
				<a href=\"index.php?open=$open&amp;page=edit_recipe_ingredients&amp;recipe_id=$recipe_id&amp;editor_language=$editor_language\">Ingredients</a>
				</p>
			<!-- //Where am I ? -->
	

			<!-- Menu -->
				<div class=\"tabs\">
					<ul>
						<li><a href=\"index.php?open=$open&amp;page=edit_recipe_general&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">General</a></li>
						<li><a href=\"index.php?open=$open&amp;page=edit_recipe_ingredients&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\" class=\"active\">Ingredients</a></li>
						<li><a href=\"index.php?open=$open&amp;page=edit_recipe_categorization&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Categorization</a></li>
						<li><a href=\"index.php?open=$open&amp;page=edit_recipe_image&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Image</a></li>
						<li><a href=\"index.php?open=$open&amp;page=edit_recipe_video&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Video</a></li>
						<li><a href=\"index.php?open=$open&amp;page=edit_recipe_tags&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Tags</a></li>
						<li><a href=\"index.php?open=$open&amp;page=edit_recipe_links&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Links</a></li>
						<li><a href=\"index.php?open=$open&amp;page=delete_recipe&amp;recipe_id=$recipe_id&amp;editor_language=$editor_language\">Delete</a>
					</ul>
				</div><p>&nbsp;</p>
			<!-- //Menu -->
		


			<!-- Delete form -->
				<h2>$l_delete $get_group_title</h2>
				<p>$l_are_you_sure_you_want_to_delete_the_group</p>

				<p>
				<a href=\"index.php?open=recipes&amp;page=edit_recipe_ingredients&amp;action=delete_group&amp;recipe_id=$recipe_id&amp;group_id=$group_id&amp;editor_language=$editor_language&amp;process=1\" class=\"btn\">$l_confirm_delete</a>
				</p>
			";

		}
	} // action == delete group
	elseif($action == "add_item"){
		// Find group
		$group_id_mysql = quote_smart($link, $group_id);
		$query = "SELECT group_id, group_recipe_id, group_title FROM $t_recipes_groups WHERE group_id=$group_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_group_id, $get_group_recipe_id, $get_group_title) = $row;
	
		if($get_group_id == ""){
			echo"
			<h1>Recipe group not found</h1>

			<p>
			<a href=\"index.php?open=$open&amp;page=edit_recipe_ingredients&amp;recipe_id=$recipe_id&amp;editor_language=$editor_language\" class=\"btn\">Back</a>
			</p>
			";
		}
		else{
			if($process == "1"){
				$inp_item_amount = $_POST['inp_item_amount'];
				$inp_item_amount = output_html($inp_item_amount);
				$inp_item_amount = str_replace(",", ".", $inp_item_amount);
				$inp_item_amount_mysql = quote_smart($link, $inp_item_amount);
				if(empty($inp_item_amount)){
					$ft = "error";
					$fm = "amound_cant_be_empty";
				}
				else{
					if(!(is_numeric($inp_item_amount))){
						// Do we have math? Example 1/8 ts
						$check_for_fraction = explode("/", $inp_item_amount);

						if(isset($check_for_fraction[0]) && isset($check_for_fraction[1])){
							if(is_numeric($check_for_fraction[0]) && is_numeric($check_for_fraction[1])){
								$inp_item_amount = $check_for_fraction[0] / $check_for_fraction[1];
							}
							else{
								$ft = "error";
								$fm = "amound_has_to_be_a_number";
							}
						}
						else{
							$ft = "error";
							$fm = "amound_has_to_be_a_number";
						}
					}
				}

				$inp_item_measurement = $_POST['inp_item_measurement'];
				$inp_item_measurement = output_html($inp_item_measurement);
				$inp_item_measurement = str_replace(",", ".", $inp_item_measurement);
				$inp_item_measurement_mysql = quote_smart($link, $inp_item_measurement);
				if(empty($inp_item_measurement)){
					$ft = "error";
					$fm = "measurement_cant_be_empty";
				}

				$inp_item_grocery = $_POST['inp_item_grocery'];
				$inp_item_grocery = output_html($inp_item_grocery);
				$inp_item_grocery_mysql = quote_smart($link, $inp_item_grocery);
				if(empty($inp_item_grocery)){
					$ft = "error";
					$fm = "grocery_cant_be_empty";
				}

				$inp_item_food_id = $_POST['inp_item_food_id'];
				$inp_item_food_id = output_html($inp_item_food_id);
				if($inp_item_food_id == ""){
					$inp_item_food_id = "0";
				}
				$inp_item_food_id_mysql = quote_smart($link, $inp_item_food_id);

				// Calories per hundred
				if(isset($_POST['inp_item_calories_per_hundred'])){
					$inp_item_calories_per_hundred = $_POST['inp_item_calories_per_hundred'];
				}
				else{
					$inp_item_calories_per_hundred = "0";
				}
				$inp_item_calories_per_hundred = output_html($inp_item_calories_per_hundred);
				$inp_item_calories_per_hundred = str_replace(",", ".", $inp_item_calories_per_hundred);
				if(empty($inp_item_calories_per_hundred)){
					$inp_item_calories_per_hundred = "0";
				}
				else{
					if(!(is_numeric($inp_item_calories_per_hundred))){
						$ft = "error";
						$fm = "calories_have_to_be_a_number";
					}
				}
				$inp_item_calories_per_hundred = round($inp_item_calories_per_hundred, 0);
				$inp_item_calories_per_hundred_mysql = quote_smart($link, $inp_item_calories_per_hundred);


				$inp_item_calories_calculated = $_POST['inp_item_calories_calculated'];
				$inp_item_calories_calculated = output_html($inp_item_calories_calculated);
				$inp_item_calories_calculated = str_replace(",", ".", $inp_item_calories_calculated);
				if(empty($inp_item_calories_calculated)){
					$inp_item_calories_calculated = "0";
				}
				else{
					if(!(is_numeric($inp_item_calories_calculated))){
						$ft = "error";
						$fm = "calories_have_to_be_a_number";
					}
				}
				$inp_item_calories_calculated = round($inp_item_calories_calculated, 0);
				$inp_item_calories_calculated_mysql = quote_smart($link, $inp_item_calories_calculated);

				// Fat per hundred
				if(isset($_POST['inp_item_fat_per_hundred'])){
					$inp_item_fat_per_hundred = $_POST['inp_item_fat_per_hundred'];
				}
				else{
					$inp_item_fat_per_hundred = "0";
				}
				$inp_item_fat_per_hundred = output_html($inp_item_fat_per_hundred);
				$inp_item_fat_per_hundred = str_replace(",", ".", $inp_item_fat_per_hundred);
				if(empty($inp_item_fat_per_hundred)){
					$inp_item_fat_per_hundred = "0";
				}
				else{
					if(!(is_numeric($inp_item_calories_per_hundred))){
						$ft = "error";
						$fm = "fat_have_to_be_a_number";
					}
				}
				$inp_item_fat_per_hundred = round($inp_item_fat_per_hundred, 0);
				$inp_item_fat_per_hundred_mysql = quote_smart($link, $inp_item_fat_per_hundred);

				$inp_item_fat_calculated = $_POST['inp_item_fat_calculated'];
				$inp_item_fat_calculated = output_html($inp_item_fat_calculated);
				$inp_item_fat_calculated = str_replace(",", ".", $inp_item_fat_calculated);
				if(empty($inp_item_fat_calculated)){
					$inp_item_fat_calculated = "0";
				}
				else{
					if(!(is_numeric($inp_item_calories_calculated))){
						$ft = "error";
						$fm = "fat_have_to_be_a_number";
					}
				}
				$inp_item_fat_calculated = round($inp_item_fat_calculated, 0);
				$inp_item_fat_calculated_mysql = quote_smart($link, $inp_item_fat_calculated);


				// Fat saturated fatty acids
				if(isset($_POST['inp_item_fat_per_hundred'])){
					$inp_item_fat_of_which_saturated_fatty_acids_per_hundred = $_POST['inp_item_fat_of_which_saturated_fatty_acids_per_hundred'];
				}
				else{
					$inp_item_fat_of_which_saturated_fatty_acids_per_hundred = "0";
				}
				$inp_item_fat_of_which_saturated_fatty_acids_per_hundred = output_html($inp_item_fat_of_which_saturated_fatty_acids_per_hundred);
				$inp_item_fat_of_which_saturated_fatty_acids_per_hundred = str_replace(",", ".", $inp_item_fat_of_which_saturated_fatty_acids_per_hundred);
				if(empty($inp_item_fat_of_which_saturated_fatty_acids_per_hundred)){
					$inp_item_fat_of_which_saturated_fatty_acids_per_hundred = "0";
				}
				else{
					if(!(is_numeric($inp_item_fat_of_which_saturated_fatty_acids_per_hundred))){
						$ft = "error";
						$fm = "fat_of_which_saturated_fatty_acids_per_hundred_have_to_be_a_number";
					}
				}
				$inp_item_fat_of_which_saturated_fatty_acids_per_hundred = round($inp_item_fat_of_which_saturated_fatty_acids_per_hundred, 0);
				$inp_item_fat_of_which_saturated_fatty_acids_per_hundred_mysql = quote_smart($link, $inp_item_fat_of_which_saturated_fatty_acids_per_hundred);

				// Fat saturated fatty acids calculated
				$inp_item_fat_of_which_saturated_fatty_acids_calculated = $_POST['inp_item_fat_of_which_saturated_fatty_acids_calculated'];
				$inp_item_fat_of_which_saturated_fatty_acids_calculated = output_html($inp_item_fat_of_which_saturated_fatty_acids_calculated);
				$inp_item_fat_of_which_saturated_fatty_acids_calculated = str_replace(",", ".", $inp_item_fat_of_which_saturated_fatty_acids_calculated);
				if(empty($inp_item_fat_of_which_saturated_fatty_acids_calculated)){
					$inp_item_fat_of_which_saturated_fatty_acids_calculated = "0";
				}
				else{
					if(!(is_numeric($inp_item_fat_of_which_saturated_fatty_acids_calculated))){
						$ft = "error";
						$fm = "fat_of_which_saturated_fatty_acids_calculated_have_to_be_a_number";
					}
				}
				$inp_item_fat_of_which_saturated_fatty_acids_calculated = round($inp_item_fat_of_which_saturated_fatty_acids_calculated, 0);
				$inp_item_fat_of_which_saturated_fatty_acids_calculated_mysql = quote_smart($link, $inp_item_fat_of_which_saturated_fatty_acids_calculated);

				// Carbs per hundred
				if(isset($_POST['inp_item_carbs_per_hundred'])){
					$inp_item_carbs_per_hundred = $_POST['inp_item_carbs_per_hundred'];
				}
				else{
					$inp_item_carbs_per_hundred = "0";
				}				
				$inp_item_carbs_per_hundred = output_html($inp_item_carbs_per_hundred);
				$inp_item_carbs_per_hundred = str_replace(",", ".", $inp_item_carbs_per_hundred);
				if(empty($inp_item_carbs_per_hundred)){
					$inp_item_carbs_per_hundred = "0";
				}
				else{
					if(!(is_numeric($inp_item_calories_per_hundred))){
						$ft = "error";
						$fm = "calories_have_to_be_a_number";
					}
				}
				$inp_item_carbs_per_hundred = round($inp_item_carbs_per_hundred, 0);
				$inp_item_carbs_per_hundred_mysql = quote_smart($link, $inp_item_carbs_per_hundred);

				// Carbs calculated
				$inp_item_carbs_calculated = $_POST['inp_item_carbs_calculated'];
				$inp_item_carbs_calculated = output_html($inp_item_carbs_calculated);
				$inp_item_carbs_calculated = str_replace(",", ".", $inp_item_carbs_calculated);
				if(empty($inp_item_carbs_calculated)){
					$inp_item_carbs_calculated = "0";
				}
				else{
					if(!(is_numeric($inp_item_calories_calculated))){
						$ft = "error";
						$fm = "calories_have_to_be_a_number";
					}
				}
				$inp_item_carbs_calculated = round($inp_item_carbs_calculated, 0);
				$inp_item_carbs_calculated_mysql = quote_smart($link, $inp_item_carbs_calculated);


				// Fiber per hundred
				if(isset($_POST['inp_item_carbs_of_which_dietary_fiber_per_hundred'])){
					$inp_item_carbs_of_which_dietary_fiber_per_hundred = $_POST['inp_item_carbs_of_which_dietary_fiber_per_hundred'];
				}
				else{
					$inp_item_carbs_of_which_dietary_fiber_per_hundred = "0";
				}
				$inp_item_carbs_of_which_dietary_fiber_per_hundred = output_html($inp_item_carbs_of_which_dietary_fiber_per_hundred);
				$inp_item_carbs_of_which_dietary_fiber_per_hundred = str_replace(",", ".", $inp_item_carbs_of_which_dietary_fiber_per_hundred);
				if(empty($inp_item_carbs_of_which_dietary_fiber_per_hundred)){
					$inp_item_carbs_of_which_dietary_fiber_per_hundred = "0";
				}
				else{
					if(!(is_numeric($inp_item_carbs_of_which_dietary_fiber_per_hundred))){
						$ft = "error";
						$fm = "carbs_of_which_sugars_per_hundred_have_to_be_a_number";
					}
				}
				$inp_item_carbs_of_which_dietary_fiber_per_hundred = round($inp_item_carbs_of_which_dietary_fiber_per_hundred, 0);
				$inp_item_carbs_of_which_dietary_fiber_per_hundred_mysql = quote_smart($link, $inp_item_carbs_of_which_dietary_fiber_per_hundred);

				// Fiber calcualted
				if(isset($_POST['inp_item_carbs_of_which_dietary_fiber_calculated'])){
					$inp_item_carbs_of_which_dietary_fiber_calculated = $_POST['inp_item_carbs_of_which_dietary_fiber_calculated'];
				}
				else{
					$inp_item_carbs_of_which_dietary_fiber_calculated = "0";
				}
				$inp_item_carbs_of_which_dietary_fiber_calculated = output_html($inp_item_carbs_of_which_dietary_fiber_calculated);
				$inp_item_carbs_of_which_dietary_fiber_calculated = str_replace(",", ".", $inp_item_carbs_of_which_dietary_fiber_calculated);
				if(empty($inp_item_carbs_of_which_dietary_fiber_calculated)){
					$inp_item_carbs_of_which_dietary_fiber_calculated = "0";
				}
				else{
					if(!(is_numeric($inp_item_carbs_of_which_dietary_fiber_calculated))){
						$ft = "error";
						$fm = "carbs_of_which_sugars_per_hundred_have_to_be_a_number";
					}
				}
				$inp_item_carbs_of_which_dietary_fiber_calculated = round($inp_item_carbs_of_which_dietary_fiber_calculated, 0);
				$inp_item_carbs_of_which_dietary_fiber_calculated_mysql = quote_smart($link, $inp_item_carbs_of_which_dietary_fiber_calculated);



				// Carbs of which sugars
				if(isset($_POST['inp_item_carbs_of_which_sugars_per_hundred'])){
					$inp_item_carbs_of_which_sugars_per_hundred = $_POST['inp_item_carbs_of_which_sugars_per_hundred'];
				}
				else{
					$inp_item_carbs_of_which_sugars_per_hundred = "0";
				}
				$inp_item_carbs_of_which_sugars_per_hundred = output_html($inp_item_carbs_of_which_sugars_per_hundred);
				$inp_item_carbs_of_which_sugars_per_hundred = str_replace(",", ".", $inp_item_carbs_of_which_sugars_per_hundred);
				if(empty($inp_item_carbs_of_which_sugars_per_hundred)){
					$inp_item_carbs_of_which_sugars_per_hundred = "0";
				}
				else{
					if(!(is_numeric($inp_item_carbs_of_which_sugars_per_hundred))){
						$ft = "error";
						$fm = "carbs_of_which_sugars_per_hundred_have_to_be_a_number";
					}
				}
				$inp_item_carbs_of_which_sugars_per_hundred = round($inp_item_carbs_of_which_sugars_per_hundred, 0);
				$inp_item_carbs_of_which_sugars_per_hundred_mysql = quote_smart($link, $inp_item_carbs_of_which_sugars_per_hundred);

				// Carbs of which sugars calcualted
				$inp_item_carbs_of_which_sugars_calculated = $_POST['inp_item_carbs_of_which_sugars_calculated'];
				$inp_item_carbs_of_which_sugars_calculated = output_html($inp_item_carbs_of_which_sugars_calculated);
				$inp_item_carbs_of_which_sugars_calculated = str_replace(",", ".", $inp_item_carbs_of_which_sugars_calculated);
				if(empty($inp_item_carbs_of_which_sugars_calculated)){
					$inp_item_carbs_of_which_sugars_calculated = "0";
				}
				else{
					if(!(is_numeric($inp_item_carbs_of_which_sugars_calculated))){
						$ft = "error";
						$fm = "carbs_of_which_sugars_calculated_have_to_be_a_number";
					}
				}
				$inp_item_carbs_of_which_sugars_calculated = round($inp_item_carbs_of_which_sugars_calculated, 0);
				$inp_item_carbs_of_which_sugars_calculated_mysql = quote_smart($link, $inp_item_carbs_of_which_sugars_calculated);


				// Proteins
				if(isset($_POST['inp_item_proteins_per_hundred'])){
					$inp_item_proteins_per_hundred = $_POST['inp_item_proteins_per_hundred'];
				}
				else{
					$inp_item_proteins_per_hundred = "0";
				}
				$inp_item_proteins_per_hundred = output_html($inp_item_proteins_per_hundred);
				$inp_item_proteins_per_hundred = str_replace(",", ".", $inp_item_proteins_per_hundred);
				if(empty($inp_item_proteins_per_hundred)){
					$inp_item_proteins_per_hundred = "0";
				}
				else{
					if(!(is_numeric($inp_item_proteins_per_hundred))){
						$ft = "error";
						$fm = "proteins_have_to_be_a_number";
					}
				}
				$inp_item_proteins_per_hundred = round($inp_item_proteins_per_hundred, 0);
				$inp_item_proteins_per_hundred_mysql = quote_smart($link, $inp_item_proteins_per_hundred);

				// Proteins calculated
				$inp_item_proteins_calculated = $_POST['inp_item_proteins_calculated'];
				$inp_item_proteins_calculated = output_html($inp_item_proteins_calculated);
				$inp_item_proteins_calculated = str_replace(",", ".", $inp_item_proteins_calculated);
				if(empty($inp_item_proteins_calculated)){
					$inp_item_proteins_calculated = "0";
				}
				else{
					if(!(is_numeric($inp_item_proteins_calculated))){
						$ft = "error";
						$fm = "proteins_have_to_be_a_number";
					}
				}
				$inp_item_proteins_calculated = round($inp_item_proteins_calculated, 0);
				$inp_item_proteins_calculated_mysql = quote_smart($link, $inp_item_proteins_calculated);

				// Salt per hundred
				if(isset($_POST['inp_item_salt_per_hundred'])){
					$inp_item_salt_per_hundred = $_POST['inp_item_salt_per_hundred'];
				}
				else{
					$inp_item_salt_per_hundred = "0";
				}
				$inp_item_salt_per_hundred = output_html($inp_item_salt_per_hundred);
				$inp_item_salt_per_hundred = str_replace(",", ".", $inp_item_salt_per_hundred);
				if(empty($inp_item_salt_per_hundred)){
					$inp_item_salt_per_hundred = "0";
				}
				else{
					if(!(is_numeric($inp_item_salt_per_hundred))){
						$ft = "error";
						$fm = "salt_have_to_be_a_number";
					}
				}
				$inp_item_salt_per_hundred = round($inp_item_salt_per_hundred, 0);
				$inp_item_salt_per_hundred_mysql = quote_smart($link, $inp_item_salt_per_hundred);

				// Sodium per hundred
				if($inp_item_salt_per_hundred != "0"){
					$inp_item_sodium_per_hundred = ($inp_item_salt_per_hundred*40)/100; // 40 % of salt
					$inp_item_sodium_per_hundred = $inp_item_sodium_per_hundred/1000; // mg
				}
				else{
					$inp_item_sodium_per_hundred = 0;
				}
				$inp_item_sodium_per_hundred_mysql = quote_smart($link, $inp_item_sodium_per_hundred);

				// Salt calculated
				if(isset($_POST['inp_item_salt_calculated'])){
					$inp_item_salt_calculated = $_POST['inp_item_salt_calculated'];
				}
				else{
					$inp_item_salt_calculated = 0;
				}
				$inp_item_salt_calculated = output_html($inp_item_salt_calculated);
				$inp_item_salt_calculated = str_replace(",", ".", $inp_item_salt_calculated);
				if(empty($inp_item_salt_calculated)){
					$inp_item_salt_calculated = "0";
				}
				else{
					if(!(is_numeric($inp_item_salt_calculated))){
						$ft = "error";
						$fm = "salt_have_to_be_a_number";
					}
				}
				$inp_item_salt_calculated = round($inp_item_salt_calculated, 0);
				$inp_item_salt_calculated_mysql = quote_smart($link, $inp_item_salt_calculated);


				// Sodium calculated
				if(isset($_POST['inp_item_sodium_calculated'])){
					$inp_item_sodium_calculated = $_POST['inp_item_sodium_calculated'];
					$inp_item_sodium_calculated = output_html($inp_item_sodium_calculated);
					$inp_item_sodium_calculated = str_replace(",", ".", $inp_item_sodium_calculated);
				}
				else{
					$inp_item_sodium_calculated = ($inp_item_salt_calculated*40)/100; // 40 % of salt
					$inp_item_sodium_calculated = $inp_item_sodium_calculated/1000; // mg
				}
				$inp_item_sodium_calculated_mysql = quote_smart($link, $inp_item_sodium_calculated);



				if(isset($fm) && $fm != ""){
					$url = "index.php?open=recipes&page=edit_recipe_ingredients&action=add_item&recipe_id=$get_recipe_id&group_id=$get_group_id&editor_language=$editor_language&l=$l";
					$url = $url . "&ft=$ft&fm=$fm";
					$url = $url . "&amount=$inp_item_amount&measurement=$inp_item_measurement&grocery=$inp_item_grocery&calories=$inp_item_calories_calculated";
					$url = $url . "&proteins=$inp_item_proteins_calculated&fat=$inp_item_fat_calculated&carbs=$inp_item_carbs_calculated";

					header("Location: $url");
					exit;
				}


				// Have I already this item?
				$query = "SELECT item_id FROM $t_recipes_items WHERE item_recipe_id=$get_recipe_id AND item_group_id=$get_group_id AND item_grocery=$inp_item_grocery_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_item_id) = $row;
				if($get_item_id != ""){
					$ft = "error";
					$fm = "you_have_already_added_that_item";

					$url = "index.php?open=recipes&page=edit_recipe_ingredients&action=add_item&recipe_id=$get_recipe_id&group_id=$get_group_id&editor_language=$editor_language&l=$l";
					$url = $url . "&ft=$ft&fm=$fm";
					$url = $url . "&amount=$inp_item_amount&measurement=$inp_item_measurement&grocery=$inp_item_grocery&calories=$inp_item_calories_calculated";
					$url = $url . "&proteins=$inp_item_proteins_calculated&fat=$inp_item_fat_calculated&carbs=$inp_item_carbs_calculated";

					header("Location: $url");
					exit;
				}


				// Insert
				mysqli_query($link, "INSERT INTO $t_recipes_items
				(item_id, item_recipe_id, item_group_id, item_amount, item_measurement, item_grocery, item_grocery_explanation, item_food_id, 
				item_calories_per_hundred, item_fat_per_hundred, item_fat_of_which_saturated_fatty_acids_per_hundred, item_carbs_per_hundred, item_carbs_of_which_dietary_fiber_hundred, item_carbs_of_which_sugars_per_hundred, item_proteins_per_hundred, item_salt_per_hundred, item_sodium_per_hundred, 
				item_calories_calculated, item_fat_calculated, item_fat_of_which_saturated_fatty_acids_calculated, item_carbs_calculated, item_carbs_of_which_dietary_fiber_calculated, item_carbs_of_which_sugars_calculated, item_proteins_calculated, item_salt_calculated, item_sodium_calculated) 
				VALUES 
				(NULL, '$get_recipe_id', '$get_group_id', $inp_item_amount_mysql, $inp_item_measurement_mysql, $inp_item_grocery_mysql, '', $inp_item_food_id_mysql, 
				$inp_item_calories_per_hundred_mysql, $inp_item_fat_per_hundred_mysql, $inp_item_fat_of_which_saturated_fatty_acids_per_hundred_mysql, $inp_item_carbs_per_hundred_mysql, $inp_item_carbs_of_which_dietary_fiber_per_hundred_mysql, $inp_item_carbs_of_which_sugars_per_hundred_mysql, $inp_item_proteins_per_hundred_mysql, $inp_item_salt_per_hundred_mysql, $inp_item_sodium_per_hundred_mysql,
				$inp_item_calories_calculated_mysql, $inp_item_fat_calculated_mysql, $inp_item_fat_of_which_saturated_fatty_acids_calculated_mysql, $inp_item_carbs_calculated_mysql, $inp_item_carbs_of_which_dietary_fiber_calculated_mysql, $inp_item_carbs_of_which_sugars_calculated_mysql, $inp_item_proteins_calculated_mysql, $inp_item_salt_calculated_mysql, $inp_item_sodium_calculated_mysql)")
				or die(mysqli_error($link));
			

				// Calculating total numbers


				$inp_number_hundred_calories = 0;
				$inp_number_hundred_proteins = 0;
				$inp_number_hundred_fat = 0;
				$inp_number_hundred_fat_of_which_saturated_fatty_acids = 0;
				$inp_number_hundred_carbs = 0;
				$inp_number_hundred_carbs_of_which_dietary_fiber = 0;
				$inp_number_hundred_carbs_of_which_sugars = 0;
				$inp_number_hundred_salt = 0;
				$inp_number_hundred_sodium = 0;
					
				$inp_number_serving_calories = 0;
				$inp_number_serving_proteins = 0;
				$inp_number_serving_fat = 0;
				$inp_number_serving_fat_of_which_saturated_fatty_acids = 0;
				$inp_number_serving_carbs = 0;
				$inp_number_serving_carbs_of_which_dietary_fiber = 0;
				$inp_number_serving_carbs_of_which_sugars = 0;
				$inp_number_serving_salt = 0;
				$inp_number_serving_sodium = 0;
					
				$inp_number_total_weight = 0;

				$inp_number_total_calories 				= 0;
				$inp_number_total_proteins 				= 0;
				$inp_number_total_fat     				= 0;
				$inp_number_total_fat_of_which_saturated_fatty_acids 	= 0;
				$inp_number_total_carbs    				= 0;
				$inp_number_total_carbs_of_which_dietary_fiber 		= 0;
				$inp_number_total_carbs_of_which_sugars 		= 0;
				$inp_number_total_salt 					= 0;
				$inp_number_total_sodium 				= 0;
					
				$query_groups = "SELECT group_id, group_title FROM $t_recipes_groups WHERE group_recipe_id=$get_recipe_id";
				$result_groups = mysqli_query($link, $query_groups);
				while($row_groups = mysqli_fetch_row($result_groups)) {
					list($get_group_id, $get_group_title) = $row_groups;

					$query_items = "SELECT item_id, item_recipe_id, item_group_id, item_amount, item_measurement, item_grocery, item_grocery_explanation, item_food_id, item_calories_per_hundred, item_fat_per_hundred, item_fat_of_which_saturated_fatty_acids_per_hundred, item_carbs_per_hundred, item_carbs_of_which_dietary_fiber_hundred, item_carbs_of_which_sugars_per_hundred, item_proteins_per_hundred, item_salt_per_hundred, item_sodium_per_hundred, item_calories_calculated, item_fat_calculated, item_fat_of_which_saturated_fatty_acids_calculated, item_carbs_calculated, item_carbs_of_which_dietary_fiber_calculated, item_carbs_of_which_sugars_calculated, item_proteins_calculated, item_salt_calculated, item_sodium_calculated FROM $t_recipes_items WHERE item_group_id=$get_group_id";
					$result_items = mysqli_query($link, $query_items);
					$row_cnt = mysqli_num_rows($result_items);
					while($row_items = mysqli_fetch_row($result_items)) {
						list($get_item_id, $get_item_recipe_id, $get_item_group_id, $get_item_amount, $get_item_measurement, $get_item_grocery, $get_item_grocery_explanation, $get_item_food_id, $get_item_calories_per_hundred, $get_item_fat_per_hundred, $get_item_fat_of_which_saturated_fatty_acids_per_hundred, $get_item_carbs_per_hundred, $get_item_carbs_of_which_dietary_fiber_hundred, $get_item_carbs_of_which_sugars_per_hundred, $get_item_proteins_per_hundred, $get_item_salt_per_hundred, $get_item_sodium_per_hundred, $get_item_calories_calculated, $get_item_fat_calculated, $get_item_fat_of_which_saturated_fatty_acids_calculated, $get_item_carbs_calculated, $get_item_carbs_of_which_dietary_fiber_calculated, $get_item_carbs_of_which_sugars_calculated, $get_item_proteins_calculated, $get_item_salt_calculated, $get_item_sodium_calculated) = $row_items;

						$inp_number_hundred_calories 				= $inp_number_hundred_calories+$get_item_calories_per_hundred;
						$inp_number_hundred_proteins 				= $inp_number_hundred_proteins+$get_item_proteins_per_hundred;
						$inp_number_hundred_fat      				= $inp_number_hundred_fat+$get_item_fat_per_hundred;
						$inp_number_hundred_fat_of_which_saturated_fatty_acids 	= $inp_number_hundred_fat_of_which_saturated_fatty_acids+$get_item_fat_of_which_saturated_fatty_acids_per_hundred;
						$inp_number_hundred_carbs    				= $inp_number_hundred_carbs+$get_item_carbs_per_hundred;
						$inp_number_hundred_carbs_of_which_dietary_fiber 	= $inp_number_hundred_carbs_of_which_dietary_fiber+$get_item_carbs_of_which_dietary_fiber_hundred;
						$inp_number_hundred_carbs_of_which_sugars 		= $inp_number_hundred_carbs_of_which_sugars+$get_item_carbs_of_which_sugars_per_hundred;
						$inp_number_hundred_salt 				= $inp_number_hundred_salt+$get_item_salt_per_hundred;
						$inp_number_hundred_sodium 				= $inp_number_hundred_sodium+$get_item_sodium_per_hundred;
					
						$inp_number_total_weight     = $inp_number_total_weight+$get_item_amount;

						$inp_number_total_calories 				= $inp_number_total_calories+$get_item_calories_calculated;
						$inp_number_total_proteins 				= $inp_number_total_proteins+$get_item_proteins_calculated;
						$inp_number_total_fat     				= $inp_number_total_fat+$get_item_fat_calculated;
						$inp_number_total_fat_of_which_saturated_fatty_acids 	= $inp_number_total_fat_of_which_saturated_fatty_acids+$get_item_fat_of_which_saturated_fatty_acids_calculated;
						$inp_number_total_carbs    				= $inp_number_total_carbs+$get_item_carbs_calculated;
						$inp_number_total_carbs_of_which_dietary_fiber 		= $inp_number_total_carbs_of_which_dietary_fiber+$get_item_carbs_of_which_dietary_fiber_calculated;
						$inp_number_total_carbs_of_which_sugars 		= $inp_number_total_carbs_of_which_sugars+$get_item_carbs_of_which_sugars_calculated;
						$inp_number_total_salt 					= $inp_number_total_salt+$get_item_salt_calculated;
						$inp_number_total_sodium				= $inp_number_total_salt+$get_item_sodium_calculated;
	
					} // items
				} // groups
					
				

	
				// Numbers : Per hundred
				$inp_number_hundred_calories_mysql 				= quote_smart($link, $inp_number_hundred_calories);
				$inp_number_hundred_proteins_mysql 				= quote_smart($link, $inp_number_hundred_proteins);
				$inp_number_hundred_fat_mysql      				= quote_smart($link, $inp_number_hundred_fat);
				$inp_number_hundred_fat_of_which_saturated_fatty_acids_mysql 	= quote_smart($link, $inp_number_hundred_fat_of_which_saturated_fatty_acids);
				$inp_number_hundred_carbs_mysql   				= quote_smart($link, $inp_number_hundred_carbs);
				$inp_number_hundred_carbs_of_which_dietary_fiber_mysql 		= quote_smart($link, $inp_number_hundred_carbs_of_which_dietary_fiber);
				$inp_number_hundred_carbs_of_which_sugars_mysql			= quote_smart($link, $inp_number_hundred_carbs_of_which_sugars);
				$inp_number_hundred_salt_mysql					= quote_smart($link, $inp_number_hundred_salt);
				$inp_number_hundred_sodium_mysql				= quote_smart($link, $inp_number_hundred_sodium);
					
				// Numbers : Total 
				$inp_number_total_weight_mysql     = quote_smart($link, $inp_number_total_weight);

				$inp_number_total_calories_mysql 				= quote_smart($link, $inp_number_total_calories);
				$inp_number_total_proteins_mysql 				= quote_smart($link, $inp_number_total_proteins);
				$inp_number_total_fat_mysql      				= quote_smart($link, $inp_number_total_fat);
				$inp_number_total_fat_of_which_saturated_fatty_acids_mysql	= quote_smart($link, $inp_number_total_fat_of_which_saturated_fatty_acids);
				$inp_number_total_carbs_mysql    				= quote_smart($link, $inp_number_total_carbs);
				$inp_number_total_carbs_of_which_dietary_fiber_mysql    	= quote_smart($link, $inp_number_total_carbs_of_which_dietary_fiber);
				$inp_number_total_carbs_of_which_sugars_mysql    		= quote_smart($link, $inp_number_total_carbs_of_which_sugars);
				$inp_number_total_salt_mysql    				= quote_smart($link, $inp_number_total_salt);
				$inp_number_total_sodium_mysql    				= quote_smart($link, $inp_number_total_sodium);

				// Numbers : Per serving
				$inp_number_serving_calories = round($inp_number_total_calories/$get_number_servings);
				$inp_number_serving_calories_mysql = quote_smart($link, $inp_number_serving_calories);

				$inp_number_serving_proteins = round($inp_number_total_proteins/$get_number_servings);
				$inp_number_serving_proteins_mysql = quote_smart($link, $inp_number_serving_proteins);

				$inp_number_serving_fat		= round($inp_number_total_fat/$get_number_servings);
				$inp_number_serving_fat_mysql   = quote_smart($link, $inp_number_serving_fat);

				$inp_number_serving_fat_of_which_saturated_fatty_acids		= round($inp_number_hundred_fat_of_which_saturated_fatty_acids/$get_number_servings);
				$inp_number_serving_fat_of_which_saturated_fatty_acids_mysql 	= quote_smart($link, $inp_number_serving_fat_of_which_saturated_fatty_acids);

				$inp_number_serving_carbs    = round($inp_number_total_carbs/$get_number_servings);
				$inp_number_serving_carbs_mysql    = quote_smart($link, $inp_number_serving_carbs);

				$inp_number_serving_carbs_of_which_dietary_fiber = round($inp_number_serving_carbs_of_which_dietary_fiber/$get_number_servings);
				$inp_number_serving_carbs_of_which_dietary_fiber_mysql 	= quote_smart($link, $inp_number_serving_carbs_of_which_dietary_fiber); 

				$inp_number_serving_carbs_of_which_sugars 		= round($inp_number_serving_carbs_of_which_sugars /$get_number_servings);
				$inp_number_serving_carbs_of_which_sugars_mysql 	= quote_smart($link, $inp_number_serving_carbs_of_which_sugars); 

				$inp_number_serving_salt 	= round($inp_number_serving_salt/$get_number_servings);
				$inp_number_serving_salt_mysql 	= quote_smart($link, $inp_number_serving_salt); 

				$inp_number_serving_sodium 	 = round($inp_number_serving_sodium/$get_number_servings);
				$inp_number_serving_sodium_mysql = quote_smart($link, $inp_number_serving_sodium); 



				$result = mysqli_query($link, "UPDATE $t_recipes_numbers SET 
					number_hundred_calories=$inp_number_hundred_calories_mysql, 
					number_hundred_proteins=$inp_number_hundred_proteins_mysql, 
					number_hundred_fat=$inp_number_hundred_fat_mysql, 
					number_hundred_fat_of_which_saturated_fatty_acids=$inp_number_hundred_fat_of_which_saturated_fatty_acids_mysql,
					number_hundred_carbs=$inp_number_hundred_carbs_mysql, 
					number_hundred_carbs_of_which_dietary_fiber=$inp_number_hundred_carbs_of_which_dietary_fiber_mysql,
					number_hundred_carbs_of_which_sugars=$inp_number_hundred_carbs_of_which_sugars_mysql,
					number_hundred_salt=$inp_number_hundred_salt_mysql,
					number_hundred_sodium=$inp_number_hundred_sodium_mysql,

					number_serving_calories=$inp_number_serving_calories_mysql, 
					number_serving_proteins=$inp_number_serving_proteins_mysql, 
					number_serving_fat=$inp_number_serving_fat_mysql, 
					number_serving_fat_of_which_saturated_fatty_acids=$inp_number_serving_fat_of_which_saturated_fatty_acids_mysql,
					number_serving_carbs=$inp_number_serving_carbs_mysql,
					number_serving_carbs_of_which_dietary_fiber=$inp_number_serving_carbs_of_which_dietary_fiber_mysql, 
					number_serving_carbs_of_which_sugars=$inp_number_serving_carbs_of_which_sugars_mysql, 
					number_serving_salt=$inp_number_serving_salt_mysql,
					number_serving_sodium=$inp_number_serving_sodium_mysql,

					number_total_weight=$inp_number_total_weight_mysql, 
					number_total_calories=$inp_number_total_calories_mysql, 
					number_total_proteins=$inp_number_total_proteins_mysql, 
					number_total_fat=$inp_number_total_fat_mysql, 
					number_total_fat_of_which_saturated_fatty_acids=$inp_number_serving_fat_of_which_saturated_fatty_acids_mysql,
					number_total_carbs=$inp_number_total_carbs_mysql,
					number_total_carbs_of_which_dietary_fiber=$inp_number_serving_carbs_of_which_dietary_fiber_mysql,
					number_total_carbs_of_which_sugars=$inp_number_serving_carbs_of_which_sugars_mysql, 
					number_total_salt=$inp_number_serving_salt_mysql, 
					number_total_sodium=$inp_number_serving_sodium_mysql
					 WHERE number_recipe_id=$recipe_id_mysql") or die(mysqli_error($link));



				// Header
				$ft = "success";
				$fm = "ingredient_added";

				$url = "index.php?open=recipes&page=edit_recipe_ingredients&action=add_item&recipe_id=$get_recipe_id&group_id=$get_group_id&editor_language=$editor_language&l=$l";
				$url = $url . "&ft=$ft&fm=$fm";
				header("Location: $url");
				exit;

			}

			
			// Variables
			if(isset($_GET['amount'])){
				$inp_item_amount = $_GET['amount'];
				$inp_item_amount = output_html($inp_item_amount);
			}
			else{
				$inp_item_amount = "";
			}

			if(isset($_GET['measurement'])){
				$inp_item_measurement = $_GET['measurement'];
				$inp_item_measurement = output_html($inp_item_measurement);
			}
			else{
				$inp_item_measurement = "";
			}

			if(isset($_GET['grocery'])){
				$inp_item_grocery = $_GET['grocery'];
				$inp_item_grocery = output_html($inp_item_grocery);
			}
			else{
				$inp_item_grocery = "";
			}

			if(isset($_GET['calories_per_hundred'])){
				$inp_item_calories_per_hundred = $_GET['calories_per_hundred'];
				$inp_item_calories_per_hundred = output_html($inp_item_calories_per_hundred);
			}
			else{
				$inp_item_calories_per_hundred = "";
			}

			if(isset($_GET['proteins_per_hundred'])){
				$inp_item_proteins_per_hundred = $_GET['proteins_per_hundred'];
				$inp_item_proteins_per_hundred = output_html($inp_item_proteins_per_hundred);
			}
			else{
				$inp_item_proteins_per_hundred = "";
			}

			if(isset($_GET['fat_per_hundred'])){
				$inp_item_fat_per_hundred = $_GET['fat_per_hundred'];
				$inp_item_fat_per_hundred = output_html($inp_item_fat_per_hundred);
			}
			else{
				$inp_item_fat_per_hundred = "";
			}
			if(isset($_GET['fat_of_which_saturated_fatty_acids_per_hundred'])){
				$inp_item_fat_of_which_saturated_fatty_acids_per_hundred = $_GET['fat_of_which_saturated_fatty_acids_per_hundred'];
				$inp_item_fat_of_which_saturated_fatty_acids_per_hundred = output_html($inp_item_fat_of_which_saturated_fatty_acids_per_hundred);
			}
			else{
				$inp_item_fat_of_which_saturated_fatty_acids_per_hundred = "";
			}

			if(isset($_GET['carbs_per_hundred'])){
				$inp_item_carbs_per_hundred = $_GET['carbs_per_hundred'];
				$inp_item_carbs_per_hundred = output_html($inp_item_carbs_per_hundred);
			}
			else{
				$inp_item_carbs_per_hundred = "";
			}

			if(isset($_GET['carbs_of_which_dietary_fiber_calculated'])){
				$inp_item_carbs_of_which_dietary_fiber_calculated = $_GET['carbs_of_which_dietary_fiber_calculated'];
				$inp_item_carbs_of_which_dietary_fiber_calculated = output_html($inp_item_carbs_of_which_dietary_fiber_calculated);
			}
			else{
				$inp_item_carbs_of_which_dietary_fiber_calculated = "";
			}

			if(isset($_GET['carbs_of_which_dietary_fiber_hundred'])){
				$inp_item_carbs_of_which_dietary_fiber_hundred = $_GET['carbs_of_which_dietary_fiber_hundred'];
				$inp_item_carbs_of_which_dietary_fiber_hundred = output_html($inp_item_carbs_of_which_dietary_fiber_hundred);
			}
			else{
				$inp_item_carbs_of_which_dietary_fiber_hundred = "";
			}

			if(isset($_GET['carbs_of_which_sugars_per_hundred'])){
				$inp_item_carbs_of_which_sugars_per_hundred = $_GET['carbs_of_which_sugars_per_hundred'];
				$inp_item_carbs_of_which_sugars_per_hundred = output_html($inp_item_carbs_of_which_sugars_per_hundred);
			}
			else{
				$inp_item_carbs_of_which_sugars_per_hundred = "";
			}

			if(isset($_GET['salt_per_hundred'])){
				$inp_item_salt_per_hundred = $_GET['salt_per_hundred'];
				$inp_item_salt_per_hundred = output_html($inp_item_salt_per_hundred);
			}
			else{
				$inp_item_salt_per_hundred = "";
			}

			if(isset($_GET['calories'])){
				$inp_item_calories_calculated = $_GET['calories'];
				$inp_item_calories_calculated = output_html($inp_item_calories_calculated);
			}
			else{
				$inp_item_calories_calculated = "";
			}

			if(isset($_GET['proteins'])){
				$inp_item_proteins_calculated = $_GET['proteins'];
				$inp_item_proteins_calculated = output_html($inp_item_proteins_calculated);
			}
			else{
				$inp_item_proteins_calculated = "";
			}

			if(isset($_GET['fat'])){
				$inp_item_fat_calculated = $_GET['fat'];
				$inp_item_fat_calculated = output_html($inp_item_fat_calculated);
			}
			else{
				$inp_item_fat_calculated = "";
			}
			if(isset($_GET['fat_of_which_saturated_fatty_acids'])){
				$inp_item_fat_of_which_saturated_fatty_acids_calculated = $_GET['fat_of_which_saturated_fatty_acids'];
				$inp_item_fat_of_which_saturated_fatty_acids_calculated = output_html($inp_item_fat_of_which_saturated_fatty_acids_calculated);
			}
			else{
				$inp_item_fat_of_which_saturated_fatty_acids_calculated = "";
			}

			if(isset($_GET['carbs'])){
				$inp_item_carbs_calculated = $_GET['carbs'];
				$inp_item_carbs_calculated = output_html($inp_item_carbs_calculated);
			}
			else{
				$inp_item_carbs_calculated = "";
			}

			if(isset($_GET['carbs_of_which_sugars'])){
				$inp_item_carbs_of_which_sugars_calculated = $_GET['carbs_of_which_sugars'];
				$inp_item_carbs_of_which_sugars_calculated = output_html($inp_item_carbs_of_which_sugars_calculated);
			}
			else{
				$inp_item_carbs_of_which_sugars_calculated = "";
			}

			if(isset($_GET['salt'])){
				$inp_item_salt_calculated = $_GET['salt'];
				$inp_item_salt_calculated = output_html($inp_item_salt_calculated);
			}
			else{
				$inp_item_salt_calculated = "";
			}

			if(isset($_GET['sodium'])){
				$inp_item_sodium_calculated = $_GET['sodium'];
				$inp_item_sodium_calculated = output_html($inp_item_sodium_calculated);
			}
			else{
				$inp_item_sodium_calculated = "";
			}


			
			echo"
			<!-- Headline -->
				<div class=\"recipes_headline\">
					<h1>$get_recipe_title</h1>
				</div>
				<div class=\"recipes_buttons\">
					<p>
					<a href=\"../recipes/view_recipe.php?recipe_id=$get_recipe_id&amp;l=$get_recipe_language\" class=\"btn_default\">View</a>
					</p>
				</div>
				<div class=\"clear\"></div>
			<!-- //Headline -->


			<!-- Where am I ? -->
				<p><b>$l_you_are_here:</b><br />
				<a href=\"index.php?open=recipes&amp;page=default&amp;editor_language=$editor_language&amp;l=$l#recipe$recipe_id\">Recipes</a>
				&gt;
				<a href=\"index.php?open=$open&amp;page=edit_recipe_ingredients&amp;recipe_id=$recipe_id&amp;editor_language=$editor_language\">Ingredients</a>
				</p>
			<!-- //Where am I ? -->
	

			<!-- Menu -->
				<div class=\"tabs\">
					<ul>
						<li><a href=\"index.php?open=$open&amp;page=edit_recipe_general&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">General</a></li>
						<li><a href=\"index.php?open=$open&amp;page=edit_recipe_ingredients&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\" class=\"active\">Ingredients</a></li>
						<li><a href=\"index.php?open=$open&amp;page=edit_recipe_categorization&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Categorization</a></li>
						<li><a href=\"index.php?open=$open&amp;page=edit_recipe_image&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Image</a></li>
						<li><a href=\"index.php?open=$open&amp;page=edit_recipe_video&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Video</a></li>
						<li><a href=\"index.php?open=$open&amp;page=edit_recipe_tags&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Tags</a></li>
						<li><a href=\"index.php?open=$open&amp;page=edit_recipe_links&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Links</a></li>
						<li><a href=\"index.php?open=$open&amp;page=delete_recipe&amp;recipe_id=$recipe_id&amp;editor_language=$editor_language\">Delete</a>
					</ul>
				</div><p>&nbsp;</p>
			<!-- //Menu -->
		
			<!-- Feedback -->
				";
				if(isset($loading)){
					echo"$loading";
				}
				else{
					if($ft != ""){
						if($fm == "group_title_is_empty"){
							$fm = "$l_group_title_is_empty";
						}
						elseif($fm == "you_already_have_a_group_with_that_name"){
							$fm = "$l_you_already_have_a_group_with_that_name";
						}
						else{
							$fm = ucfirst($fm);
							$fm = str_replace("_", " ", $fm);
						}
						echo"<div class=\"$ft\"><span>$fm</span></div>";
					}
				}
				echo"	
			<!-- //Feedback -->


			<!-- Add ingredients form -->
				<!-- Focus -->
					<script>
					\$(document).ready(function(){
						\$('[name=\"inp_item_amount\"]').focus();
					});
					</script>
				<!-- //Focus -->

				<form method=\"post\" action=\"index.php?open=recipes&amp;page=edit_recipe_ingredients&amp;action=add_item&amp;recipe_id=$get_recipe_id&amp;group_id=$group_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">


				<h2 style=\"padding-bottom:0;margin-bottom:0;\">Food</h2>
				<table>
				 <tbody>
				  <tr>
				   <td style=\"padding: 0px 20px 0px 0px;\">
					<p>Amount<br />
					<input type=\"text\" name=\"inp_item_amount\" id=\"inp_item_amount\" size=\"3\" value=\"$inp_item_amount\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
					</p>
				   </td>
				   <td>
					<p>Measurement<br />
					<input type=\"text\" name=\"inp_item_measurement\" size=\"3\" value=\"$inp_item_measurement\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
					</p>
				   </td>
				  </tr>
				 </tbody>
				</table>
				<p>Grocery &middot; <a href=\"../food/new_food.php?l=$l\" target=\"_blank\">New food</a><br />
				<input type=\"text\" name=\"inp_item_grocery\" class=\"inp_item_grocery\" size=\"25\" value=\"$inp_item_grocery\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" id=\"nettport_inp_search_query\" />
				<input type=\"hidden\" name=\"inp_item_food_id\" id=\"inp_item_food_id\" /></p>


				<div id=\"nettport_search_results\">
				</div><div class=\"clear\"></div>

				<hr />
			
				<h2 style=\"padding-bottom:0;margin-bottom:0;\">Numbers</h2>
				<table class=\"hor-zebra\" style=\"width: 350px\">
				 <thead>
				  <tr>
				   <th scope=\"col\">
				   </th>";
				if($get_recipe_country != "United States"){
					echo"
				   <th scope=\"col\" style=\"text-align: center;padding: 6px 4px 6px 4px;vertical-align: bottom;\">
					<span>Per hundred</span>
				   </th>";
				}
				echo"
				   <th scope=\"col\" style=\"text-align: center;padding: 6px 4px 6px 4px;vertical-align: bottom;\">
					<span>Calculated</span>
				   </th>
				  </tr>
				 </thead>
				 <tbody>
				  <tr>
				   <td style=\"padding: 8px 4px 6px 8px;\">
					<span>Calories</span>
				   </td>";
				if($get_recipe_country != "United States"){
					echo"
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
					<span><input type=\"text\" name=\"inp_item_calories_per_hundred\" id=\"inp_item_calories_per_hundred\" size=\"5\" value=\"$inp_item_calories_per_hundred\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></span>
				   </td>";
				}
				echo"
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
					<span><input type=\"text\" name=\"inp_item_calories_calculated\" id=\"inp_item_calories_calculated\" size=\"5\" value=\"$inp_item_calories_calculated\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></span>
				   </td>
				  </tr>
				  <tr>
				   <td style=\"padding: 8px 4px 6px 8px;\">
					<p style=\"margin:0;padding: 0px 0px 4px 0px;\">Fat</p>
					<p style=\"margin:0;padding: 0;\">- of which saturated fatty acids</p>
				   </td>";
				if($get_recipe_country != "United States"){
					echo"
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
					<p style=\"margin:0;padding: 0px 0px 4px 0px;\"><input type=\"text\" name=\"inp_item_fat_per_hundred\" id=\"inp_item_fat_per_hundred\" size=\"5\" value=\"$inp_item_fat_per_hundred\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
					<p style=\"margin:0;padding: 0;\"><input type=\"text\" name=\"inp_item_fat_of_which_saturated_fatty_acids_per_hundred\" id=\"inp_item_fat_of_which_saturated_fatty_acids_per_hundred\" size=\"5\" value=\"$inp_item_fat_of_which_saturated_fatty_acids_per_hundred\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
				   </td>";
				}
				echo"
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
					<p style=\"margin:0;padding: 0px 0px 4px 0px;\"><input type=\"text\" name=\"inp_item_fat_calculated\" id=\"inp_item_fat_calculated\" size=\"5\" value=\"$inp_item_fat_calculated\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
					<p style=\"margin:0;padding: 0;\"><input type=\"text\" name=\"inp_item_fat_of_which_saturated_fatty_acids_calculated\" id=\"inp_item_fat_of_which_saturated_fatty_acids_calculated\" size=\"5\" value=\"$inp_item_fat_of_which_saturated_fatty_acids_calculated\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
				   </td>
				 </tr>
				  <tr>
		 		  <td style=\"padding: 8px 4px 6px 8px;\">
					<p style=\"margin:0;padding: 0px 0px 4px 0px;\">Carbs</p>
					<p style=\"margin:0;padding: 0;\">- of which sugars calculated</p>
				   </td>";
				if($get_recipe_country != "United States"){
					echo"
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
					<p style=\"margin:0;padding: 0px 0px 4px 0px;\"><input type=\"text\" name=\"inp_item_carbs_per_hundred\" id=\"inp_item_carbs_per_hundred\" size=\"5\" value=\"$inp_item_carbs_per_hundred\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
					<p style=\"margin:0;padding: 0;\"><input type=\"text\" name=\"inp_item_carbs_of_which_sugars_per_hundred\" id=\"inp_item_carbs_of_which_sugars_per_hundred\" size=\"5\" value=\"$inp_item_carbs_of_which_sugars_per_hundred\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
				   </td>";
				}
				echo"
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
					<p style=\"margin:0;padding: 0px 0px 4px 0px;\"><input type=\"text\" name=\"inp_item_carbs_calculated\" id=\"inp_item_carbs_calculated\" size=\"5\" value=\"$inp_item_carbs_calculated\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
					<p style=\"margin:0;padding: 0;\"><input type=\"text\" name=\"inp_item_carbs_of_which_sugars_calculated\" id=\"inp_item_carbs_of_which_sugars_calculated\" size=\"5\" value=\"$inp_item_carbs_of_which_sugars_calculated\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
				   </td>
				  </tr>

				 <tr>
	 			  <td style=\"padding: 8px 4px 6px 8px;\">
					<p style=\"margin:0;padding: 0;\">Dietary fiber</p>
				   </td>";
				if($get_recipe_country != "United States"){
					echo"
				 	  <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						<p style=\"margin:0;padding: 0;\"><input type=\"text\" name=\"inp_item_carbs_of_which_dietary_fiber_per_hundred\" id=\"inp_item_carbs_of_which_dietary_fiber_per_hundred\" size=\"5\" value=\"$inp_item_carbs_of_which_dietary_fiber_hundred\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
					   </td>";
					}
				echo"
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
					<p style=\"margin:0;padding: 0;\"><input type=\"text\" name=\"inp_item_carbs_of_which_dietary_fiber_calculated\" id=\"inp_item_carbs_of_which_dietary_fiber_calculated\" size=\"5\" value=\"$inp_item_carbs_of_which_dietary_fiber_calculated\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
				   </td>
				  </tr>


				  <tr>
				   <td style=\"padding: 8px 4px 6px 8px;\">
					<span>Proteins</span>
				   </td>";
				if($get_recipe_country != "United States"){
					echo"
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
					<span><input type=\"text\" name=\"inp_item_proteins_per_hundred\" id=\"inp_item_proteins_per_hundred\" size=\"5\" value=\"$inp_item_proteins_per_hundred\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></span>
				   </td>";
				}
				echo"
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
					<span><input type=\"text\" name=\"inp_item_proteins_calculated\" id=\"inp_item_proteins_calculated\" size=\"5\" value=\"$inp_item_proteins_calculated\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></span>
				   </td>
				  </tr>";
				if($get_recipe_country == "United States"){
					// US uses sodium only, while rest uses salt
					echo"
					  <tr>
					   <td style=\"padding: 8px 4px 6px 8px;\">
						<span>Sodium in mg</span>
					   </td>
				 	   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						<span><input type=\"text\" name=\"inp_item_sodium_calculated\" id=\"inp_item_sodium_calculated\" value=\"$inp_item_sodium_calculated\" size=\"5\" /></span>
					   </td>
					  </tr>
					";
				}
				else{
					echo"
					  <tr>
					   <td style=\"padding: 8px 4px 6px 8px;\">
						<span>Salt in gram</span>
					   </td>
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						<span><input type=\"text\" name=\"inp_item_salt_per_hundred\" id=\"inp_item_salt_per_hundred\" value=\"$inp_item_salt_per_hundred\" size=\"5\" /></span>
					   </td>
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						<span><input type=\"text\" name=\"inp_item_salt_calculated\" id=\"inp_item_salt_calculated\" value=\"$inp_item_salt_calculated\" size=\"5\" /></span>
					   </td>
					  </tr>
					";
				}
				echo"
				 </tbody>
				</table>




			

				<p>
				<input type=\"submit\" value=\"Add ingredient\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				</p>





				</form>

				<!-- Search script -->
					<script id=\"source\" language=\"javascript\" type=\"text/javascript\">
					\$(document).ready(function () {
						\$('#nettport_inp_search_query').keyup(function () {
							$(\"#nettport_search_results\").show();
       							// getting the value that user typed
       							var searchString    = $(\"#nettport_inp_search_query\").val();
 							// forming the queryString
      							var data            = 'l=$l&recipe_id=$recipe_id&q='+ searchString;

        						// if searchString is not empty
        						if(searchString) {
           							// ajax call
            							\$.ajax({
                							type: \"POST\",
               								url: \"_inc/recipes/edit_recipe_ingredients_search_jquery.php\",
                							data: data,
									beforeSend: function(html) { // this happens before actual call
										\$(\"#nettport_search_results\").html(''); 
									},
               								success: function(html){
                    								\$(\"#nettport_search_results\").append(html);
              								}
            							});
       							}
        						return false;
            					});
         				   });
					</script>
				<!-- //Search script -->


			";

		} // recipe group found
	} // add_item

	elseif($action == "edit_item"){
		// Find group
		$group_id_mysql = quote_smart($link, $group_id);
		$query = "SELECT group_id, group_recipe_id, group_title FROM $t_recipes_groups WHERE group_id=$group_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_group_id, $get_group_recipe_id, $get_group_title) = $row;
	
		if($get_group_id == ""){
			echo"
			<h1>Recipe group not found</h1>

			<p>
			<a href=\"index.php?open=$open&amp;page=edit_recipe_ingredients&amp;recipe_id=$recipe_id&amp;editor_language=$editor_language\" class=\"btn\">Back</a>
			</p>
			";
		}
		else{
			// Find item
			$item_id_mysql = quote_smart($link, $item_id);
			$query = "SELECT item_id, item_recipe_id, item_group_id, item_amount, item_measurement, item_grocery, item_grocery_explanation, item_food_id, item_calories_per_hundred, item_fat_per_hundred, item_fat_of_which_saturated_fatty_acids_per_hundred, item_carbs_per_hundred, item_carbs_of_which_dietary_fiber_hundred, item_carbs_of_which_sugars_per_hundred, item_proteins_per_hundred, item_salt_per_hundred, item_sodium_per_hundred, item_calories_calculated, item_fat_calculated, item_fat_of_which_saturated_fatty_acids_calculated, item_carbs_calculated, item_carbs_of_which_dietary_fiber_calculated, item_carbs_of_which_sugars_calculated, item_proteins_calculated, item_salt_calculated, item_sodium_calculated FROM $t_recipes_items WHERE item_id=$item_id_mysql AND item_recipe_id=$get_recipe_id AND item_group_id=$get_group_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_item_id, $get_item_recipe_id, $get_item_group_id, $get_item_amount, $get_item_measurement, $get_item_grocery, $get_item_grocery_explanation, $get_item_food_id, $get_item_calories_per_hundred, $get_item_fat_per_hundred, $get_item_fat_of_which_saturated_fatty_acids_per_hundred, $get_item_carbs_per_hundred, $get_item_carbs_of_which_dietary_fiber_hundred, $get_item_carbs_of_which_sugars_per_hundred, $get_item_proteins_per_hundred, $get_item_salt_per_hundred, $get_item_sodium_per_hundred, $get_item_calories_calculated, $get_item_fat_calculated, $get_item_fat_of_which_saturated_fatty_acids_calculated, $get_item_carbs_calculated, $get_item_carbs_of_which_dietary_fiber_calculated, $get_item_carbs_of_which_sugars_calculated, $get_item_proteins_calculated, $get_item_salt_calculated, $get_item_sodium_calculated) = $row;



			if($get_item_id == ""){
				echo"
				<h1>Item not found</h1>

				<p>
				<a href=\"index.php?open=$open&amp;page=edit_recipe_ingredients&amp;recipe_id=$recipe_id&amp;editor_language=$editor_language\" class=\"btn\">Back</a>
				</p>
				";
			}
			else{

				if($process == "1"){
					$inp_item_amount = $_POST['inp_item_amount'];
					$inp_item_amount = output_html($inp_item_amount);
					$inp_item_amount = str_replace(",", ".", $inp_item_amount);
					$inp_item_amount_mysql = quote_smart($link, $inp_item_amount);
					if(empty($inp_item_amount)){
						$ft = "error";
						$fm = "amound_cant_be_empty";
					}
					else{
						if(!(is_numeric($inp_item_amount))){
							// Do we have math? Example 1/8 ts
							$check_for_fraction = explode("/", $inp_item_amount);

							if(isset($check_for_fraction[0]) && isset($check_for_fraction[1])){
								if(is_numeric($check_for_fraction[0]) && is_numeric($check_for_fraction[1])){
									$inp_item_amount = $check_for_fraction[0] / $check_for_fraction[1];
								}
								else{
									$ft = "error";
									$fm = "amound_has_to_be_a_number";
								}
							}
							else{
								$ft = "error";
								$fm = "amound_has_to_be_a_number";
							}
						}
					}
	
					$inp_item_measurement = $_POST['inp_item_measurement'];
					$inp_item_measurement = output_html($inp_item_measurement);
					$inp_item_measurement = str_replace(",", ".", $inp_item_measurement);
					$inp_item_measurement_mysql = quote_smart($link, $inp_item_measurement);
					if(empty($inp_item_measurement)){
						$ft = "error";
						$fm = "measurement_cant_be_empty";
					}

					$inp_item_grocery = $_POST['inp_item_grocery'];
					$inp_item_grocery = output_html($inp_item_grocery);
					$inp_item_grocery_mysql = quote_smart($link, $inp_item_grocery);
					if(empty($inp_item_grocery)){
						$ft = "error";
						$fm = "grocery_cant_be_empty";
					}

					$inp_item_food_id = $_POST['inp_item_food_id'];
					$inp_item_food_id = output_html($inp_item_food_id);
					if($inp_item_food_id == ""){
						$inp_item_food_id = "0";
					}
					$inp_item_food_id_mysql = quote_smart($link, $inp_item_food_id);


					// Calories per hundred
					if(isset($_POST['inp_item_calories_per_hundred'])){
						$inp_item_calories_per_hundred = $_POST['inp_item_calories_per_hundred'];
					}
					else{
						$inp_item_calories_per_hundred = "0";
					}
					$inp_item_calories_per_hundred = output_html($inp_item_calories_per_hundred);
					$inp_item_calories_per_hundred = str_replace(",", ".", $inp_item_calories_per_hundred);
					if(empty($inp_item_calories_per_hundred)){
						$inp_item_calories_per_hundred = "0";
					}
					else{
						if(!(is_numeric($inp_item_calories_per_hundred))){
							$ft = "error";
							$fm = "calories_have_to_be_a_number";
						}
					}
					$inp_item_calories_per_hundred = round($inp_item_calories_per_hundred, 0);
					$inp_item_calories_per_hundred_mysql = quote_smart($link, $inp_item_calories_per_hundred);


					$inp_item_calories_calculated = $_POST['inp_item_calories_calculated'];
					$inp_item_calories_calculated = output_html($inp_item_calories_calculated);
					$inp_item_calories_calculated = str_replace(",", ".", $inp_item_calories_calculated);
					if(empty($inp_item_calories_calculated)){
						$inp_item_calories_calculated = "0";
					}
					else{
						if(!(is_numeric($inp_item_calories_calculated))){
							$ft = "error";
							$fm = "calories_have_to_be_a_number";
						}
					}
					$inp_item_calories_calculated = round($inp_item_calories_calculated, 0);
					$inp_item_calories_calculated_mysql = quote_smart($link, $inp_item_calories_calculated);

					// Fat per hundred
					if(isset($_POST['inp_item_fat_per_hundred'])){
						$inp_item_fat_per_hundred = $_POST['inp_item_fat_per_hundred'];
					}
					else{
						$inp_item_fat_per_hundred = "0";
					}
					$inp_item_fat_per_hundred = output_html($inp_item_fat_per_hundred);
					$inp_item_fat_per_hundred = str_replace(",", ".", $inp_item_fat_per_hundred);
					if(empty($inp_item_fat_per_hundred)){
						$inp_item_fat_per_hundred = "0";
					}
					else{
						if(!(is_numeric($inp_item_calories_per_hundred))){
							$ft = "error";
							$fm = "fat_have_to_be_a_number";
						}
					}
					$inp_item_fat_per_hundred = round($inp_item_fat_per_hundred, 0);
					$inp_item_fat_per_hundred_mysql = quote_smart($link, $inp_item_fat_per_hundred);

					$inp_item_fat_calculated = $_POST['inp_item_fat_calculated'];
					$inp_item_fat_calculated = output_html($inp_item_fat_calculated);
					$inp_item_fat_calculated = str_replace(",", ".", $inp_item_fat_calculated);
					if(empty($inp_item_fat_calculated)){
						$inp_item_fat_calculated = "0";
					}
					else{
						if(!(is_numeric($inp_item_calories_calculated))){
							$ft = "error";
							$fm = "fat_have_to_be_a_number";
						}
					}
					$inp_item_fat_calculated = round($inp_item_fat_calculated, 0);
					$inp_item_fat_calculated_mysql = quote_smart($link, $inp_item_fat_calculated);


					// Fat saturated fatty acids
					if(isset($_POST['inp_item_fat_per_hundred'])){
						$inp_item_fat_of_which_saturated_fatty_acids_per_hundred = $_POST['inp_item_fat_of_which_saturated_fatty_acids_per_hundred'];
					}
					else{
						$inp_item_fat_of_which_saturated_fatty_acids_per_hundred = "0";
					}
					$inp_item_fat_of_which_saturated_fatty_acids_per_hundred = output_html($inp_item_fat_of_which_saturated_fatty_acids_per_hundred);
					$inp_item_fat_of_which_saturated_fatty_acids_per_hundred = str_replace(",", ".", $inp_item_fat_of_which_saturated_fatty_acids_per_hundred);
					if(empty($inp_item_fat_of_which_saturated_fatty_acids_per_hundred)){
						$inp_item_fat_of_which_saturated_fatty_acids_per_hundred = "0";
					}
					else{
						if(!(is_numeric($inp_item_fat_of_which_saturated_fatty_acids_per_hundred))){
							$ft = "error";
							$fm = "fat_of_which_saturated_fatty_acids_per_hundred_have_to_be_a_number";
						}
					}
					$inp_item_fat_of_which_saturated_fatty_acids_per_hundred = round($inp_item_fat_of_which_saturated_fatty_acids_per_hundred, 0);
					$inp_item_fat_of_which_saturated_fatty_acids_per_hundred_mysql = quote_smart($link, $inp_item_fat_of_which_saturated_fatty_acids_per_hundred);
	
					// Fat saturated fatty acids calculated
					$inp_item_fat_of_which_saturated_fatty_acids_calculated = $_POST['inp_item_fat_of_which_saturated_fatty_acids_calculated'];
					$inp_item_fat_of_which_saturated_fatty_acids_calculated = output_html($inp_item_fat_of_which_saturated_fatty_acids_calculated);
					$inp_item_fat_of_which_saturated_fatty_acids_calculated = str_replace(",", ".", $inp_item_fat_of_which_saturated_fatty_acids_calculated);
					if(empty($inp_item_fat_of_which_saturated_fatty_acids_calculated)){
						$inp_item_fat_of_which_saturated_fatty_acids_calculated = "0";
					}
					else{
						if(!(is_numeric($inp_item_fat_of_which_saturated_fatty_acids_calculated))){
							$ft = "error";
							$fm = "fat_of_which_saturated_fatty_acids_calculated_have_to_be_a_number";
						}
					}
					$inp_item_fat_of_which_saturated_fatty_acids_calculated = round($inp_item_fat_of_which_saturated_fatty_acids_calculated, 0);
					$inp_item_fat_of_which_saturated_fatty_acids_calculated_mysql = quote_smart($link, $inp_item_fat_of_which_saturated_fatty_acids_calculated);

					// Carbs per hundred
					if(isset($_POST['inp_item_carbs_per_hundred'])){
						$inp_item_carbs_per_hundred = $_POST['inp_item_carbs_per_hundred'];
					}
					else{
						$inp_item_carbs_per_hundred = "0";
					}				
					$inp_item_carbs_per_hundred = output_html($inp_item_carbs_per_hundred);
					$inp_item_carbs_per_hundred = str_replace(",", ".", $inp_item_carbs_per_hundred);
					if(empty($inp_item_carbs_per_hundred)){
						$inp_item_carbs_per_hundred = "0";
					}
					else{
						if(!(is_numeric($inp_item_calories_per_hundred))){
							$ft = "error";
							$fm = "calories_have_to_be_a_number";
						}
					}
					$inp_item_carbs_per_hundred = round($inp_item_carbs_per_hundred, 0);
					$inp_item_carbs_per_hundred_mysql = quote_smart($link, $inp_item_carbs_per_hundred);

					// Carbs calculated
					$inp_item_carbs_calculated = $_POST['inp_item_carbs_calculated'];
					$inp_item_carbs_calculated = output_html($inp_item_carbs_calculated);
					$inp_item_carbs_calculated = str_replace(",", ".", $inp_item_carbs_calculated);
					if(empty($inp_item_carbs_calculated)){
						$inp_item_carbs_calculated = "0";
					}
					else{
						if(!(is_numeric($inp_item_calories_calculated))){
							$ft = "error";
							$fm = "calories_have_to_be_a_number";
						}
					}
					$inp_item_carbs_calculated = round($inp_item_carbs_calculated, 0);
					$inp_item_carbs_calculated_mysql = quote_smart($link, $inp_item_carbs_calculated);

					// Fiber per hundred
					if(isset($_POST['inp_item_carbs_of_which_dietary_fiber_per_hundred'])){
						$inp_item_carbs_of_which_dietary_fiber_per_hundred = $_POST['inp_item_carbs_of_which_dietary_fiber_per_hundred'];
					}
					else{
						$inp_item_carbs_of_which_dietary_fiber_per_hundred = "0";
					}
					$inp_item_carbs_of_which_dietary_fiber_per_hundred = output_html($inp_item_carbs_of_which_dietary_fiber_per_hundred);
					$inp_item_carbs_of_which_dietary_fiber_per_hundred = str_replace(",", ".", $inp_item_carbs_of_which_dietary_fiber_per_hundred);
					if(empty($inp_item_carbs_of_which_dietary_fiber_per_hundred)){
						$inp_item_carbs_of_which_dietary_fiber_per_hundred = "0";
					}
					else{
						if(!(is_numeric($inp_item_carbs_of_which_dietary_fiber_per_hundred))){
							$ft = "error";
							$fm = "carbs_of_which_sugars_per_hundred_have_to_be_a_number";
						}
					}
					$inp_item_carbs_of_which_dietary_fiber_per_hundred = round($inp_item_carbs_of_which_dietary_fiber_per_hundred, 0);
					$inp_item_carbs_of_which_dietary_fiber_per_hundred_mysql = quote_smart($link, $inp_item_carbs_of_which_dietary_fiber_per_hundred);

					// Fiber calcualted
					if(isset($_POST['inp_item_carbs_of_which_dietary_fiber_calculated'])){
						$inp_item_carbs_of_which_dietary_fiber_calculated = $_POST['inp_item_carbs_of_which_dietary_fiber_calculated'];
					}
					else{
						$inp_item_carbs_of_which_dietary_fiber_calculated = "0";
					}
					$inp_item_carbs_of_which_dietary_fiber_calculated = output_html($inp_item_carbs_of_which_dietary_fiber_calculated);
					$inp_item_carbs_of_which_dietary_fiber_calculated = str_replace(",", ".", $inp_item_carbs_of_which_dietary_fiber_calculated);
					if(empty($inp_item_carbs_of_which_dietary_fiber_calculated)){
						$inp_item_carbs_of_which_dietary_fiber_calculated = "0";
					}
					else{
						if(!(is_numeric($inp_item_carbs_of_which_dietary_fiber_calculated))){
							$ft = "error";
							$fm = "carbs_of_which_sugars_per_hundred_have_to_be_a_number";
						}
					}
					$inp_item_carbs_of_which_dietary_fiber_calculated = round($inp_item_carbs_of_which_dietary_fiber_calculated, 0);
					$inp_item_carbs_of_which_dietary_fiber_calculated_mysql = quote_smart($link, $inp_item_carbs_of_which_dietary_fiber_calculated);



					// Carbs of which sugars
					if(isset($_POST['inp_item_carbs_of_which_sugars_per_hundred'])){
						$inp_item_carbs_of_which_sugars_per_hundred = $_POST['inp_item_carbs_of_which_sugars_per_hundred'];
					}
					else{
						$inp_item_carbs_of_which_sugars_per_hundred = "0";
					}
					$inp_item_carbs_of_which_sugars_per_hundred = output_html($inp_item_carbs_of_which_sugars_per_hundred);
					$inp_item_carbs_of_which_sugars_per_hundred = str_replace(",", ".", $inp_item_carbs_of_which_sugars_per_hundred);
					if(empty($inp_item_carbs_of_which_sugars_per_hundred)){
						$inp_item_carbs_of_which_sugars_per_hundred = "0";
					}
					else{
						if(!(is_numeric($inp_item_carbs_of_which_sugars_per_hundred))){
							$ft = "error";
							$fm = "carbs_of_which_sugars_per_hundred_have_to_be_a_number";
						}
					}
					$inp_item_carbs_of_which_sugars_per_hundred = round($inp_item_carbs_of_which_sugars_per_hundred, 0);
					$inp_item_carbs_of_which_sugars_per_hundred_mysql = quote_smart($link, $inp_item_carbs_of_which_sugars_per_hundred);

					// Carbs of which sugars calcualted
					$inp_item_carbs_of_which_sugars_calculated = $_POST['inp_item_carbs_of_which_sugars_calculated'];
					$inp_item_carbs_of_which_sugars_calculated = output_html($inp_item_carbs_of_which_sugars_calculated);
					$inp_item_carbs_of_which_sugars_calculated = str_replace(",", ".", $inp_item_carbs_of_which_sugars_calculated);
					if(empty($inp_item_carbs_of_which_sugars_calculated)){
						$inp_item_carbs_of_which_sugars_calculated = "0";
					}
					else{
						if(!(is_numeric($inp_item_carbs_of_which_sugars_calculated))){
							$ft = "error";
							$fm = "carbs_of_which_sugars_calculated_have_to_be_a_number";
						}
					}
					$inp_item_carbs_of_which_sugars_calculated = round($inp_item_carbs_of_which_sugars_calculated, 0);
					$inp_item_carbs_of_which_sugars_calculated_mysql = quote_smart($link, $inp_item_carbs_of_which_sugars_calculated);


					// Proteins
					if(isset($_POST['inp_item_proteins_per_hundred'])){
						$inp_item_proteins_per_hundred = $_POST['inp_item_proteins_per_hundred'];
					}
					else{
						$inp_item_proteins_per_hundred = "0";
					}
					$inp_item_proteins_per_hundred = output_html($inp_item_proteins_per_hundred);
					$inp_item_proteins_per_hundred = str_replace(",", ".", $inp_item_proteins_per_hundred);
					if(empty($inp_item_proteins_per_hundred)){
						$inp_item_proteins_per_hundred = "0";
					}
					else{
						if(!(is_numeric($inp_item_proteins_per_hundred))){
							$ft = "error";
							$fm = "proteins_have_to_be_a_number";
						}
					}
					$inp_item_proteins_per_hundred = round($inp_item_proteins_per_hundred, 0);
					$inp_item_proteins_per_hundred_mysql = quote_smart($link, $inp_item_proteins_per_hundred);

					// Proteins calculated
					$inp_item_proteins_calculated = $_POST['inp_item_proteins_calculated'];
					$inp_item_proteins_calculated = output_html($inp_item_proteins_calculated);
					$inp_item_proteins_calculated = str_replace(",", ".", $inp_item_proteins_calculated);
					if(empty($inp_item_proteins_calculated)){
						$inp_item_proteins_calculated = "0";
					}
					else{
						if(!(is_numeric($inp_item_proteins_calculated))){
							$ft = "error";
							$fm = "proteins_have_to_be_a_number";
						}
					}
					$inp_item_proteins_calculated = round($inp_item_proteins_calculated, 0);
					$inp_item_proteins_calculated_mysql = quote_smart($link, $inp_item_proteins_calculated);

					// Salt per hundred
					if(isset($_POST['inp_item_salt_per_hundred'])){
						$inp_item_salt_per_hundred = $_POST['inp_item_salt_per_hundred'];
					}
					else{
						$inp_item_salt_per_hundred = "0";
					}
					$inp_item_salt_per_hundred = output_html($inp_item_salt_per_hundred);
					$inp_item_salt_per_hundred = str_replace(",", ".", $inp_item_salt_per_hundred);
					if(empty($inp_item_salt_per_hundred)){
						$inp_item_salt_per_hundred = "0";
					}
					else{
						if(!(is_numeric($inp_item_salt_per_hundred))){
							$ft = "error";
							$fm = "salt_have_to_be_a_number";
						}
					}
					$inp_item_salt_per_hundred = round($inp_item_salt_per_hundred, 0);
					$inp_item_salt_per_hundred_mysql = quote_smart($link, $inp_item_salt_per_hundred);


					// Sodium per hundred
					if($inp_item_salt_per_hundred != "0"){
						$inp_item_sodium_per_hundred = ($inp_item_salt_per_hundred*40)/100; // 40 % of salt
						$inp_item_sodium_per_hundred = $inp_item_sodium_per_hundred/1000; // mg
					}
					else{
						$inp_item_sodium_per_hundred = 0;
					}
					$inp_item_sodium_per_hundred_mysql = quote_smart($link, $inp_item_sodium_per_hundred);

					// Salt calculated
					if(isset($_POST['inp_item_salt_calculated'])){
						$inp_item_salt_calculated = $_POST['inp_item_salt_calculated'];
					}
					else{
						// Todo: Fix calcualte by sodium
						$inp_item_salt_calculated = 0;
					}
					$inp_item_salt_calculated = output_html($inp_item_salt_calculated);
					$inp_item_salt_calculated = str_replace(",", ".", $inp_item_salt_calculated);
					if(empty($inp_item_salt_calculated)){
						$inp_item_salt_calculated = "0";
					}
					else{
						if(!(is_numeric($inp_item_salt_calculated))){
							$ft = "error";
							$fm = "salt_have_to_be_a_number";
						}
					}
					$inp_item_salt_calculated = round($inp_item_salt_calculated, 0);
					$inp_item_salt_calculated_mysql = quote_smart($link, $inp_item_salt_calculated);

					// Sodium calculated
					if(isset($_POST['inp_item_sodium_calculated'])){
						$inp_item_sodium_calculated = $_POST['inp_item_sodium_calculated'];
						$inp_item_sodium_calculated = output_html($inp_item_sodium_calculated);
						$inp_item_sodium_calculated = str_replace(",", ".", $inp_item_sodium_calculated);
					}
					else{
						$inp_item_sodium_calculated = ($inp_item_salt_calculated*40)/100; // 40 % of salt
						$inp_item_sodium_calculated = $inp_item_sodium_calculated/1000; // mg
					}
					$inp_item_sodium_calculated_mysql = quote_smart($link, $inp_item_sodium_calculated);

					if(isset($fm) && $fm != ""){
						$url = "edit_recipe_ingredients.php?action=edit_item&recipe_id=$get_recipe_id&group_id=$get_group_id&item_id=$get_item_id&l=$l";
						$url = $url . "&ft=$ft&fm=$fm";

						header("Location: $url");
						exit;
					}



					// Update
					$result = mysqli_query($link, "UPDATE $t_recipes_items SET item_amount=$inp_item_amount_mysql, item_measurement=$inp_item_measurement_mysql, 
						item_grocery=$inp_item_grocery_mysql, item_food_id=$inp_item_food_id_mysql, 
						item_calories_per_hundred=$inp_item_calories_per_hundred_mysql,
						item_fat_per_hundred=$inp_item_fat_per_hundred_mysql,
						item_fat_of_which_saturated_fatty_acids_per_hundred=$inp_item_fat_of_which_saturated_fatty_acids_per_hundred_mysql,
						item_carbs_per_hundred=$inp_item_carbs_per_hundred_mysql, 
						item_carbs_of_which_sugars_per_hundred=$inp_item_carbs_of_which_sugars_per_hundred_mysql,
						item_carbs_of_which_dietary_fiber_hundred=$inp_item_carbs_of_which_dietary_fiber_per_hundred_mysql, 
						item_proteins_per_hundred=$inp_item_proteins_per_hundred_mysql,
						item_salt_per_hundred=$inp_item_salt_per_hundred_mysql,
						item_sodium_per_hundred=$inp_item_sodium_per_hundred_mysql,

						item_calories_calculated=$inp_item_calories_calculated_mysql, 
						item_fat_calculated=$inp_item_fat_calculated_mysql, 
						item_fat_of_which_saturated_fatty_acids_calculated=$inp_item_fat_of_which_saturated_fatty_acids_calculated_mysql,  
						item_carbs_calculated=$inp_item_carbs_calculated_mysql,
						item_carbs_of_which_dietary_fiber_calculated=$inp_item_carbs_of_which_dietary_fiber_calculated_mysql, 
						item_carbs_of_which_sugars_calculated=$inp_item_carbs_of_which_sugars_calculated_mysql, 
						item_proteins_calculated=$inp_item_proteins_calculated_mysql, 
						item_salt_calculated=$inp_item_salt_calculated_mysql,
						item_sodium_calculated=$inp_item_sodium_calculated_mysql
						 WHERE item_id=$get_item_id") or die(mysqli_error($link));

				// Calculating total numbers


				$inp_number_hundred_calories = 0;
				$inp_number_hundred_proteins = 0;
				$inp_number_hundred_fat = 0;
				$inp_number_hundred_fat_of_which_saturated_fatty_acids = 0;
				$inp_number_hundred_carbs = 0;
				$inp_number_hundred_carbs_of_which_dietary_fiber = 0;
				$inp_number_hundred_carbs_of_which_sugars = 0;
				$inp_number_hundred_salt = 0;
				$inp_number_hundred_sodium = 0;
					
				$inp_number_serving_calories = 0;
				$inp_number_serving_proteins = 0;
				$inp_number_serving_fat = 0;
				$inp_number_serving_fat_of_which_saturated_fatty_acids = 0;
				$inp_number_serving_carbs = 0;
				$inp_number_serving_carbs_of_which_dietary_fiber = 0;
				$inp_number_serving_carbs_of_which_sugars = 0;
				$inp_number_serving_salt = 0;
				$inp_number_serving_sodium = 0;
					
				$inp_number_total_weight = 0;

				$inp_number_total_calories 				= 0;
				$inp_number_total_proteins 				= 0;
				$inp_number_total_fat     				= 0;
				$inp_number_total_fat_of_which_saturated_fatty_acids 	= 0;
				$inp_number_total_carbs    				= 0;
				$inp_number_total_carbs_of_which_dietary_fiber 		= 0;
				$inp_number_total_carbs_of_which_sugars 		= 0;
				$inp_number_total_salt 					= 0;
				$inp_number_total_sodium 					= 0;
					
				$query_groups = "SELECT group_id, group_title FROM $t_recipes_groups WHERE group_recipe_id=$get_recipe_id";
				$result_groups = mysqli_query($link, $query_groups);
				while($row_groups = mysqli_fetch_row($result_groups)) {
					list($get_group_id, $get_group_title) = $row_groups;

					$query_items = "SELECT item_id, item_recipe_id, item_group_id, item_amount, item_measurement, item_grocery, item_grocery_explanation, item_food_id, item_calories_per_hundred, item_fat_per_hundred, item_fat_of_which_saturated_fatty_acids_per_hundred, item_carbs_per_hundred, item_carbs_of_which_dietary_fiber_hundred, item_carbs_of_which_sugars_per_hundred, item_proteins_per_hundred, item_salt_per_hundred, item_sodium_per_hundred, item_calories_calculated, item_fat_calculated, item_fat_of_which_saturated_fatty_acids_calculated, item_carbs_calculated, item_carbs_of_which_dietary_fiber_calculated, item_carbs_of_which_sugars_calculated, item_proteins_calculated, item_salt_calculated, item_sodium_calculated FROM $t_recipes_items WHERE item_group_id=$get_group_id";
					$result_items = mysqli_query($link, $query_items);
					$row_cnt = mysqli_num_rows($result_items);
					while($row_items = mysqli_fetch_row($result_items)) {
						list($get_item_id, $get_item_recipe_id, $get_item_group_id, $get_item_amount, $get_item_measurement, $get_item_grocery, $get_item_grocery_explanation, $get_item_food_id, $get_item_calories_per_hundred, $get_item_fat_per_hundred, $get_item_fat_of_which_saturated_fatty_acids_per_hundred, $get_item_carbs_per_hundred, $get_item_carbs_of_which_dietary_fiber_hundred, $get_item_carbs_of_which_sugars_per_hundred, $get_item_proteins_per_hundred, $get_item_salt_per_hundred, $get_item_sodium_per_hundred, $get_item_calories_calculated, $get_item_fat_calculated, $get_item_fat_of_which_saturated_fatty_acids_calculated, $get_item_carbs_calculated, $get_item_carbs_of_which_dietary_fiber_calculated, $get_item_carbs_of_which_sugars_calculated, $get_item_proteins_calculated, $get_item_salt_calculated, $get_item_sodium_calculated) = $row_items;

						$inp_number_hundred_calories 				= $inp_number_hundred_calories+$get_item_calories_per_hundred;
						$inp_number_hundred_proteins 				= $inp_number_hundred_proteins+$get_item_proteins_per_hundred;
						$inp_number_hundred_fat      				= $inp_number_hundred_fat+$get_item_fat_per_hundred;
						$inp_number_hundred_fat_of_which_saturated_fatty_acids 	= $inp_number_hundred_fat_of_which_saturated_fatty_acids+$get_item_fat_of_which_saturated_fatty_acids_per_hundred;
						$inp_number_hundred_carbs    				= $inp_number_hundred_carbs+$get_item_carbs_per_hundred;
						$inp_number_hundred_carbs_of_which_dietary_fiber 	= $inp_number_hundred_carbs_of_which_dietary_fiber+$get_item_carbs_of_which_dietary_fiber_hundred;
						$inp_number_hundred_carbs_of_which_sugars 		= $inp_number_hundred_carbs_of_which_sugars+$get_item_carbs_of_which_sugars_per_hundred;
						$inp_number_hundred_salt 				= $inp_number_hundred_salt+$get_item_salt_per_hundred;
						$inp_number_hundred_sodium 				= $inp_number_hundred_sodium+$get_item_sodium_per_hundred;
					
						$inp_number_total_weight     = $inp_number_total_weight+$get_item_amount;

						$inp_number_total_calories 				= $inp_number_total_calories+$get_item_calories_calculated;
						$inp_number_total_proteins 				= $inp_number_total_proteins+$get_item_proteins_calculated;
						$inp_number_total_fat     				= $inp_number_total_fat+$get_item_fat_calculated;
						$inp_number_total_fat_of_which_saturated_fatty_acids 	= $inp_number_total_fat_of_which_saturated_fatty_acids+$get_item_fat_of_which_saturated_fatty_acids_calculated;
						$inp_number_total_carbs    				= $inp_number_total_carbs+$get_item_carbs_calculated;
						$inp_number_total_carbs_of_which_dietary_fiber 		= $inp_number_total_carbs_of_which_dietary_fiber+$get_item_carbs_of_which_dietary_fiber_calculated;
						$inp_number_total_carbs_of_which_sugars 		= $inp_number_total_carbs_of_which_sugars+$get_item_carbs_of_which_sugars_calculated;
						$inp_number_total_salt 					= $inp_number_total_salt+$get_item_salt_calculated;
						$inp_number_total_sodium				= $inp_number_total_salt+$get_item_sodium_calculated;
	
					} // items
				} // groups
					
				

	
				// Numbers : Per hundred
				$inp_number_hundred_calories_mysql 				= quote_smart($link, $inp_number_hundred_calories);
				$inp_number_hundred_proteins_mysql 				= quote_smart($link, $inp_number_hundred_proteins);
				$inp_number_hundred_fat_mysql      				= quote_smart($link, $inp_number_hundred_fat);
				$inp_number_hundred_fat_of_which_saturated_fatty_acids_mysql 	= quote_smart($link, $inp_number_hundred_fat_of_which_saturated_fatty_acids);
				$inp_number_hundred_carbs_mysql   				= quote_smart($link, $inp_number_hundred_carbs);
				$inp_number_hundred_carbs_of_which_dietary_fiber_mysql 		= quote_smart($link, $inp_number_hundred_carbs_of_which_dietary_fiber);
				$inp_number_hundred_carbs_of_which_sugars_mysql			= quote_smart($link, $inp_number_hundred_carbs_of_which_sugars);
				$inp_number_hundred_salt_mysql					= quote_smart($link, $inp_number_hundred_salt);
				$inp_number_hundred_sodium_mysql				= quote_smart($link, $inp_number_hundred_sodium);
					
				// Numbers : Total 
				$inp_number_total_weight_mysql     = quote_smart($link, $inp_number_total_weight);

				$inp_number_total_calories_mysql 				= quote_smart($link, $inp_number_total_calories);
				$inp_number_total_proteins_mysql 				= quote_smart($link, $inp_number_total_proteins);
				$inp_number_total_fat_mysql      				= quote_smart($link, $inp_number_total_fat);
				$inp_number_total_fat_of_which_saturated_fatty_acids_mysql	= quote_smart($link, $inp_number_total_fat_of_which_saturated_fatty_acids);
				$inp_number_total_carbs_mysql    				= quote_smart($link, $inp_number_total_carbs);
				$inp_number_total_carbs_of_which_dietary_fiber_mysql    	= quote_smart($link, $inp_number_total_carbs_of_which_dietary_fiber);
				$inp_number_total_carbs_of_which_sugars_mysql    		= quote_smart($link, $inp_number_total_carbs_of_which_sugars);
				$inp_number_total_salt_mysql    				= quote_smart($link, $inp_number_total_salt);
				$inp_number_total_sodium_mysql    				= quote_smart($link, $inp_number_total_sodium);

				// Numbers : Per serving
				$inp_number_serving_calories = round($inp_number_total_calories/$get_number_servings);
				$inp_number_serving_calories_mysql = quote_smart($link, $inp_number_serving_calories);

				$inp_number_serving_proteins = round($inp_number_total_proteins/$get_number_servings);
				$inp_number_serving_proteins_mysql = quote_smart($link, $inp_number_serving_proteins);

				$inp_number_serving_fat		= round($inp_number_total_fat/$get_number_servings);
				$inp_number_serving_fat_mysql   = quote_smart($link, $inp_number_serving_fat);

				$inp_number_serving_fat_of_which_saturated_fatty_acids		= round($inp_number_hundred_fat_of_which_saturated_fatty_acids/$get_number_servings);
				$inp_number_serving_fat_of_which_saturated_fatty_acids_mysql 	= quote_smart($link, $inp_number_serving_fat_of_which_saturated_fatty_acids);

				$inp_number_serving_carbs    = round($inp_number_total_carbs/$get_number_servings);
				$inp_number_serving_carbs_mysql    = quote_smart($link, $inp_number_serving_carbs);

				$inp_number_serving_carbs_of_which_dietary_fiber = round($inp_number_serving_carbs_of_which_dietary_fiber/$get_number_servings);
				$inp_number_serving_carbs_of_which_dietary_fiber_mysql 	= quote_smart($link, $inp_number_serving_carbs_of_which_dietary_fiber); 

				$inp_number_serving_carbs_of_which_sugars 		= round($inp_number_serving_carbs_of_which_sugars /$get_number_servings);
				$inp_number_serving_carbs_of_which_sugars_mysql 	= quote_smart($link, $inp_number_serving_carbs_of_which_sugars); 

				$inp_number_serving_salt 	= round($inp_number_serving_salt/$get_number_servings);
				$inp_number_serving_salt_mysql 	= quote_smart($link, $inp_number_serving_salt); 

				$inp_number_serving_sodium 	 = round($inp_number_serving_sodium/$get_number_servings);
				$inp_number_serving_sodium_mysql = quote_smart($link, $inp_number_serving_sodium); 



				$result = mysqli_query($link, "UPDATE $t_recipes_numbers SET 
					number_hundred_calories=$inp_number_hundred_calories_mysql, 
					number_hundred_proteins=$inp_number_hundred_proteins_mysql, 
					number_hundred_fat=$inp_number_hundred_fat_mysql, 
					number_hundred_fat_of_which_saturated_fatty_acids=$inp_number_hundred_fat_of_which_saturated_fatty_acids_mysql,
					number_hundred_carbs=$inp_number_hundred_carbs_mysql, 
					number_hundred_carbs_of_which_dietary_fiber=$inp_number_hundred_carbs_of_which_dietary_fiber_mysql,
					number_hundred_carbs_of_which_sugars=$inp_number_hundred_carbs_of_which_sugars_mysql,
					number_hundred_salt=$inp_number_hundred_salt_mysql,
					number_hundred_sodium=$inp_number_hundred_sodium_mysql,

					number_serving_calories=$inp_number_serving_calories_mysql, 
					number_serving_proteins=$inp_number_serving_proteins_mysql, 
					number_serving_fat=$inp_number_serving_fat_mysql, 
					number_serving_fat_of_which_saturated_fatty_acids=$inp_number_serving_fat_of_which_saturated_fatty_acids_mysql,
					number_serving_carbs=$inp_number_serving_carbs_mysql,
					number_serving_carbs_of_which_dietary_fiber=$inp_number_serving_carbs_of_which_dietary_fiber_mysql, 
					number_serving_carbs_of_which_sugars=$inp_number_serving_carbs_of_which_sugars_mysql, 
					number_serving_salt=$inp_number_serving_salt_mysql,
					number_serving_sodium=$inp_number_serving_sodium_mysql,

					number_total_weight=$inp_number_total_weight_mysql, 
					number_total_calories=$inp_number_total_calories_mysql, 
					number_total_proteins=$inp_number_total_proteins_mysql, 
					number_total_fat=$inp_number_total_fat_mysql, 
					number_total_fat_of_which_saturated_fatty_acids=$inp_number_serving_fat_of_which_saturated_fatty_acids_mysql,
					number_total_carbs=$inp_number_total_carbs_mysql,
					number_total_carbs_of_which_dietary_fiber=$inp_number_serving_carbs_of_which_dietary_fiber_mysql,
					number_total_carbs_of_which_sugars=$inp_number_serving_carbs_of_which_sugars_mysql, 
					number_total_salt=$inp_number_serving_salt_mysql, 
					number_total_sodium=$inp_number_serving_sodium_mysql
					 WHERE number_recipe_id=$recipe_id_mysql") or die(mysqli_error($link));



					// Header
					$ft = "success";
					$fm = "changes_saved";

					$url = "index.php?open=recipes&page=edit_recipe_ingredients&action=edit_item&recipe_id=$get_recipe_id&group_id=$get_group_id&item_id=$get_item_id&editor_language=$editor_language&l=$l";
					$url = $url . "&ft=$ft&fm=$fm";
					header("Location: $url");
					exit;
				}

				echo"
				<!-- Headline -->
				<div class=\"recipes_headline\">
					<h1>$get_recipe_title</h1>
				</div>
				<div class=\"recipes_buttons\">
					<p>
					<a href=\"../recipes/view_recipe.php?recipe_id=$get_recipe_id&amp;l=$get_recipe_language\" class=\"btn_default\">View</a>
					</p>
				</div>
				<div class=\"clear\"></div>
				<!-- //Headline -->


				<!-- Where am I ? -->
				<p><b>$l_you_are_here:</b><br />
				<a href=\"index.php?open=recipes&amp;page=default&amp;editor_language=$editor_language&amp;l=$l#recipe$recipe_id\">Recipes</a>
				&gt;
				<a href=\"index.php?open=$open&amp;page=edit_recipe_ingredients&amp;recipe_id=$recipe_id&amp;editor_language=$editor_language\">Ingredients</a>
				</p>
				<!-- //Where am I ? -->
	

				<!-- Menu -->
				<div class=\"tabs\">
					<ul>
						<li><a href=\"index.php?open=$open&amp;page=edit_recipe_general&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">General</a></li>
						<li><a href=\"index.php?open=$open&amp;page=edit_recipe_ingredients&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\" class=\"active\">Ingredients</a></li>
						<li><a href=\"index.php?open=$open&amp;page=edit_recipe_categorization&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Categorization</a></li>
						<li><a href=\"index.php?open=$open&amp;page=edit_recipe_image&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Image</a></li>
						<li><a href=\"index.php?open=$open&amp;page=edit_recipe_video&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Video</a></li>
						<li><a href=\"index.php?open=$open&amp;page=edit_recipe_tags&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Tags</a></li>
						<li><a href=\"index.php?open=$open&amp;page=edit_recipe_links&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Links</a></li>
						<li><a href=\"index.php?open=$open&amp;page=delete_recipe&amp;recipe_id=$recipe_id&amp;editor_language=$editor_language\">Delete</a>
					</ul>
				</div><p>&nbsp;</p>
				<!-- //Menu -->
		
				<!-- Feedback -->
				";
				if(isset($loading)){
					echo"$loading";
				}
				else{
					if($ft != ""){
						if($fm == "group_title_is_empty"){
							$fm = "$l_group_title_is_empty";
						}
						elseif($fm == "you_already_have_a_group_with_that_name"){
							$fm = "$l_you_already_have_a_group_with_that_name";
						}
						else{
							$fm = ucfirst($fm);
							$fm = str_replace("_", " ", $fm);
						}
						echo"<div class=\"$ft\"><span>$fm</span></div>";
					}
				}
				echo"	
				<!-- //Feedback -->


				<!-- Edit ingredients form -->
					<!-- Focus -->
					<script>
					\$(document).ready(function(){
						\$('[name=\"inp_item_amount\"]').focus();
					});
					</script>
					<!-- //Focus -->

					<form method=\"post\" action=\"index.php?open=recipes&amp;page=edit_recipe_ingredients&amp;action=edit_item&amp;recipe_id=$get_recipe_id&amp;group_id=$group_id&amp;item_id=$get_item_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

					<h2 style=\"padding-bottom:0;margin-bottom:0;\">Food</h2>
					<table>
					 <tbody>
					  <tr>
					   <td style=\"padding: 0px 20px 0px 0px;\">
						<p>Amount<br />
						<input type=\"text\" name=\"inp_item_amount\" id=\"inp_item_amount\" size=\"3\" value=\"$get_item_amount\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
						</p>
					   </td>
					   <td>
						<p>Measurement<br />
						<input type=\"text\" name=\"inp_item_measurement\" size=\"3\" value=\"$get_item_measurement\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
						</p>
					   </td>
					  </tr>
					</table>
					<p>Grocery &middot; <a href=\"../food/new_food.php?l=$l\" target=\"_blank\">New food</a><br />

					<input type=\"text\" name=\"inp_item_grocery\" class=\"inp_item_grocery\" size=\"25\" value=\"$get_item_grocery\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" id=\"nettport_inp_search_query\" />
					<input type=\"hidden\" name=\"inp_item_food_id\" id=\"inp_item_food_id\" /></p>

					<div id=\"nettport_search_results\">
					</div><div class=\"clear\"></div></span>


					<h2 style=\"padding-bottom:0;margin-bottom:0;\">Numbers</h2>
					<table class=\"hor-zebra\" style=\"width: 350px\">
					 <thead>
					  <tr>
					   <th scope=\"col\">
					   </th>";
				if($get_recipe_country != "United States"){
					echo"
					   <th scope=\"col\" style=\"text-align: center;padding: 6px 4px 6px 4px;vertical-align: bottom;\">
						<span>Per hundred</span>
					   </th>
					";
				}
				echo"
					   <th scope=\"col\" style=\"text-align: center;padding: 6px 4px 6px 4px;vertical-align: bottom;\">
						<span>Calculated</span>
					   </th>
					  </tr>
					 </thead>
					 <tbody>
					  <tr>
					   <td style=\"padding: 8px 4px 6px 8px;\">
						<span>Calories</span>
					   </td>";
					if($get_recipe_country != "United States"){
						echo"
						   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
							<span><input type=\"text\" name=\"inp_item_calories_per_hundred\" id=\"inp_item_calories_per_hundred\" size=\"5\" value=\"$get_item_calories_per_hundred\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></span>
						   </td>";
					}
					echo"
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						<span><input type=\"text\" name=\"inp_item_calories_calculated\" id=\"inp_item_calories_calculated\" size=\"5\" value=\"$get_item_calories_calculated\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></span>
					   </td>
					  </tr>
					  <tr>
					   <td style=\"padding: 8px 4px 6px 8px;\">
						<p style=\"margin:0;padding: 0px 0px 4px 0px;\">Fat</p>
						<p style=\"margin:0;padding: 0;\">- of which saturated fatty acids</p>
					   </td>";
					if($get_recipe_country != "United States"){
						echo"
					 	  <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
							<p style=\"margin:0;padding: 0px 0px 4px 0px;\"><input type=\"text\" name=\"inp_item_fat_per_hundred\" id=\"inp_item_fat_per_hundred\" size=\"5\" value=\"$get_item_fat_per_hundred\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
							<p style=\"margin:0;padding: 0;\"><input type=\"text\" name=\"inp_item_fat_of_which_saturated_fatty_acids_per_hundred\" id=\"inp_item_fat_of_which_saturated_fatty_acids_per_hundred\" size=\"5\" value=\"$get_item_fat_of_which_saturated_fatty_acids_per_hundred\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
						   </td>";
						}
					echo"
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						<p style=\"margin:0;padding: 0px 0px 4px 0px;\"><input type=\"text\" name=\"inp_item_fat_calculated\" id=\"inp_item_fat_calculated\" size=\"5\" value=\"$get_item_fat_calculated\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
						<p style=\"margin:0;padding: 0;\"><input type=\"text\" name=\"inp_item_fat_of_which_saturated_fatty_acids_calculated\" id=\"inp_item_fat_of_which_saturated_fatty_acids_calculated\" size=\"5\" value=\"$get_item_fat_of_which_saturated_fatty_acids_calculated\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
					   </td>
					  </tr>
					  <tr>
		 			  <td style=\"padding: 8px 4px 6px 8px;\">
						<p style=\"margin:0;padding: 0px 0px 4px 0px;\">Carbs</p>
						<p style=\"margin:0;padding: 0;\">- of which sugars calculated</p>
					   </td>";
					if($get_recipe_country != "United States"){
						echo"
					 	  <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
							<p style=\"margin:0;padding: 0px 0px 4px 0px;\"><input type=\"text\" name=\"inp_item_carbs_per_hundred\" id=\"inp_item_carbs_per_hundred\" size=\"5\" value=\"$get_item_carbs_per_hundred\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
							<p style=\"margin:0;padding: 0;\"><input type=\"text\" name=\"inp_item_carbs_of_which_sugars_per_hundred\" id=\"inp_item_carbs_of_which_sugars_per_hundred\" size=\"5\" value=\"$get_item_carbs_of_which_sugars_per_hundred\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
						   </td>";
						}
					echo"
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						<p style=\"margin:0;padding: 0px 0px 4px 0px;\"><input type=\"text\" name=\"inp_item_carbs_calculated\" id=\"inp_item_carbs_calculated\" size=\"5\" value=\"$get_item_carbs_calculated\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
						<p style=\"margin:0;padding: 0;\"><input type=\"text\" name=\"inp_item_carbs_of_which_sugars_calculated\" id=\"inp_item_carbs_of_which_sugars_calculated\" size=\"5\" value=\"$get_item_carbs_of_which_sugars_calculated\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
					   </td>
					  </tr>


					 <tr>
		 			  <td style=\"padding: 8px 4px 6px 8px;\">
						<p style=\"margin:0;padding: 0;\">Dietary fiber</p>
					   </td>";
					if($get_recipe_country != "United States"){
						echo"
					 	  <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
							<p style=\"margin:0;padding: 0;\"><input type=\"text\" name=\"inp_item_carbs_of_which_dietary_fiber_per_hundred\" id=\"inp_item_carbs_of_which_dietary_fiber_per_hundred\" size=\"5\" value=\"$get_item_carbs_of_which_dietary_fiber_hundred\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
						   </td>";
						}
					echo"
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						<p style=\"margin:0;padding: 0;\"><input type=\"text\" name=\"inp_item_carbs_of_which_dietary_fiber_calculated\" id=\"inp_item_carbs_of_which_dietary_fiber_calculated\" size=\"5\" value=\"$get_item_carbs_of_which_dietary_fiber_calculated\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
					   </td>
					  </tr>

					  <tr>
					   <td style=\"padding: 8px 4px 6px 8px;\">
						<span>Proteins</span>
					   </td>";
					if($get_recipe_country != "United States"){
						echo"
						   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
							<span><input type=\"text\" name=\"inp_item_proteins_per_hundred\" id=\"inp_item_proteins_per_hundred\" size=\"5\" value=\"$get_item_proteins_per_hundred\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></span>
					 	  </td>";
						}
					echo"
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						<span><input type=\"text\" name=\"inp_item_proteins_calculated\" id=\"inp_item_proteins_calculated\" size=\"5\" value=\"$get_item_proteins_calculated\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></span>
					   </td>
					  </tr>";
					if($get_recipe_country == "United States"){
						echo"
						 <tr>
					 	  <td style=\"padding: 8px 4px 6px 8px;\">
							<span>Sodium in mg</span>
					 	   </td>
						   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
							<span><input type=\"text\" name=\"inp_item_sodium_calculated\" id=\"inp_item_sodium_calculated\" value=\"$get_item_sodium_calculated\" size=\"5\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></span>
					 	  </td>
						  </tr>
						";
					}
					else{
						echo"
						 <tr>
					 	  <td style=\"padding: 8px 4px 6px 8px;\">
							<span>Salt in gram</span>
					 	  </td>
					 	  <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
							<span><input type=\"text\" name=\"inp_item_salt_per_hundred\" id=\"inp_item_salt_per_hundred\" value=\"$get_item_salt_per_hundred\" size=\"5\" /></span>
						   </td>
						   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
							<span><input type=\"text\" name=\"inp_item_salt_calculated\" id=\"inp_item_salt_calculated\" value=\"$get_item_salt_calculated\" size=\"5\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></span>
					 	  </td>
						  </tr>
						";
					}
					echo"
					 </tbody>
					</table>

					<p>
					<input type=\"submit\" value=\"$l_save\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
					</p>



				</form>

				<!-- Search script -->
					<script id=\"source\" language=\"javascript\" type=\"text/javascript\">
					\$(document).ready(function () {
						\$('#nettport_inp_search_query').keyup(function () {
							$(\"#nettport_search_results\").show();
       							// getting the value that user typed
       							var searchString    = $(\"#nettport_inp_search_query\").val();
 							// forming the queryString
      							var data            = 'l=$l&recipe_id=$recipe_id&q='+ searchString;

        						// if searchString is not empty
        						if(searchString) {
           							// ajax call
            							\$.ajax({
                							type: \"POST\",
               								url: \"_inc/recipes/edit_recipe_ingredients_search_jquery.php\",
                							data: data,
									beforeSend: function(html) { // this happens before actual call
										\$(\"#nettport_search_results\").html(''); 
									},
               								success: function(html){
                    								\$(\"#nettport_search_results\").append(html);
              								}
            							});
       							}
        						return false;
            					});
         				   });
					</script>
				<!-- //Search script -->


				";
			} // item found
		} // recipe group found
	} // edit_item

} // recipe found
?>