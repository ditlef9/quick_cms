<?php
/**
*
* File: _admin/_inc/recipes/recipes.php
* Version 23:07 09.07.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ---------------------------------------------------------------------------- */
$t_recipes 	 	= $mysqlPrefixSav . "recipes";
$t_recipes_ingredients	= $mysqlPrefixSav . "recipes_ingredients";
$t_recipes_groups	= $mysqlPrefixSav . "recipes_groups";
$t_recipes_items	= $mysqlPrefixSav . "recipes_items";
$t_recipes_numbers	= $mysqlPrefixSav . "recipes_numbers";
$t_recipes_rating	= $mysqlPrefixSav . "recipes_rating";
$t_recipes_cuisines	= $mysqlPrefixSav . "recipes_cuisines";
$t_recipes_seasons	= $mysqlPrefixSav . "recipes_seasons";
$t_recipes_occasions	= $mysqlPrefixSav . "recipes_occasions";


/*- Get extention ---------------------------------------------------------------------- */
include("_functions/get_extension.php");

/*- Variables -------------------------------------------------------------------------- */
if(isset($_GET['language'])){
	$language = $_GET['language'];
	$language = strip_tags(stripslashes($language));
}
else{
	$language = "en";
}
if(isset($_GET['view'])) {
	$view = $_GET['view'];
	$view = strip_tags(stripslashes($view));
}
else{
	$view = "";
}



/*- Settings ---------------------------------------------------------------------------- */
$settings_image_width = "1050";
$settings_image_height = "1113";

if($action == ""){
	echo"
	<h1>$l_recipes</h1>


	<!-- Feedback -->
	";
	if($ft != ""){
		if($fm == "changes_saved"){
			$fm = "$l_changes_saved";
		}
		else{
			$fm = ucfirst($fm);
			$fm = str_replace("_", " ", $fm);
		}
		echo"<div class=\"$ft\"><span>$fm</span></div>";
	}
	echo"	
	<!-- //Feedback -->

	<!-- Select language -->

		<script>
		\$(function(){
			// bind change event to select
			\$('#inp_l').on('change', function () {
				var url = \$(this).val(); // get selected value
				if (url) { // require a URL
 					window.location = url; // redirect
				}
				return false;
			});
		});
		</script>

		<form method=\"get\" enctype=\"multipart/form-data\">
		<p>
		$l_language:
		<select id=\"inp_l\">
			<option value=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">$l_editor_language</option>
			<option value=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">-</option>\n";


			$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;

				// No language selected?
				if($editor_language == ""){
						$editor_language = "$get_language_active_iso_two";
				}
				
				
				echo"	<option value=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;editor_language=$get_language_active_iso_two&amp;l=$l\"";if($editor_language == "$get_language_active_iso_two"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";
			}
		echo"
		</select>
		</p>
		</form>
	<!-- //Select language -->

	

	<!-- Recipes buttons -->";
		// Navigation
		$query = "SELECT navigation_id FROM $t_pages_navigation WHERE navigation_url_path='recipes/index.php'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_navigation_id) = $row;
		if($get_navigation_id == ""){
			echo"
			<p>
			<a href=\"index.php?open=pages&amp;page=navigation&amp;action=new_auto_insert&amp;module=recipes&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_default\">Create navigation</a>
			</p>
			";
		}
		echo"
	<!-- //Recipes buttons -->

	<!-- Views -->
		<div class=\"tabs\">
			<ul>
				<li><a href=\"index.php?open=$open&amp;editor_language=$editor_language\""; if($view == ""){ echo" class=\"active\""; } echo">$l_all</a>
				<li><a href=\"index.php?open=$open&amp;view=marked_as_spam&amp;editor_language=$editor_language\""; if($view == "marked_as_spam"){ echo" class=\"active\""; } echo">$l_marked_as_spam</a>
				<li><a href=\"index.php?open=$open&amp;page=recipes_to_sql&amp;editor_language=$editor_language\">Recipes to SQL</a>
				<li><a href=\"index.php?open=$open&amp;page=recipes_to_sqlite\">Recipes to SQLite</a>
				<li><a href=\"index.php?open=$open&amp;page=rest_to_sqlite&amp;editor_language=$editor_language\">Rest to SQLite</a>
			</ul>
		</div><p>&nbsp;</p>
	<!-- //Views -->


	<!-- List all recipes -->
		";
	
		$x = 0;
		$editor_language_mysql = quote_smart($link, $editor_language);
		$query = "SELECT $t_recipes.recipe_id, $t_recipes.recipe_user_id, $t_recipes.recipe_title, $t_recipes.recipe_introduction, $t_recipes.recipe_image_path, $t_recipes.recipe_image_h_a, $t_recipes.recipe_thumb_h_a_278x156, $t_recipes.recipe_date, $t_recipes.recipe_unique_hits FROM $t_recipes WHERE recipe_language=$editor_language_mysql";
		if($view == "marked_as_spam"){
			$query = $query  . " AND recipe_marked_as_spam='1'";
		}
		$query = $query . " ORDER BY recipe_title ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_recipe_id, $get_recipe_user_id, $get_recipe_title, $get_recipe_introduction, $get_recipe_image_path, $get_recipe_image_h_a, $get_recipe_thumb_h_a_278x156, $get_recipe_date, $get_recipe_unique_hits) = $row;

			// Style
			if(isset($odd) && $odd == false){
				$odd = true;
			}
			else{
				$odd = false;
			}
			
			// Author
			$query_u = "SELECT user_id, user_name FROM $t_users WHERE user_id=$get_recipe_user_id";
			$result_u = mysqli_query($link, $query_u);
			$row_u = mysqli_fetch_row($result_u);
			list($get_author_user_id, $get_author_user_name) = $row_u;


			// Thumb
			if($get_recipe_image_h_a != ""){
				if($get_recipe_thumb_h_a_278x156 == "" OR !(file_exists("../$get_recipe_image_path/$get_recipe_thumb_h_a_278x156")) && file_exists("../$get_recipe_image_path/$get_recipe_image_h_a")){
					$inp_new_x = 278; // 278x156
					$inp_new_y = 156;

					$ext = get_extension($get_recipe_image);

					echo"<div class=\"info\"><p>Creating recipe thumb $inp_new_x x $inp_new_y  px</p></div>";

					$thumb = $get_recipe_id . "_thumb_" . $inp_new_x . "x" . $inp_new_y . ".$ext";
					$thumb_mysql = quote_smart($link, $thumb);
					resize_crop_image($inp_new_x, $inp_new_y, "../$get_recipe_image_path/$get_recipe_image_h_a", "../$get_recipe_image_path/$thumb");
					mysqli_query($link, "UPDATE $t_recipes SET recipe_thumb_h_a_278x156=$thumb_mysql WHERE recipe_id=$get_recipe_id") or die(mysqli_error($link));
				}
			}


			if($x == "0"){
				echo"
				<div class=\"left_center_center_right_left\">
				";
			}
			elseif($x == "1"){
				echo"
				<div class=\"left_center_center_left_right_center\">
				";
			}
			elseif($x == "2"){
				echo"
				<div class=\"left_center_center_right_right_center\">
				";
			}
			elseif($x == "3"){
				echo"
				<div class=\"left_center_center_right_right\">
				";
			}


			echo"
					
				<p class=\"recipe_image_and_text\">
					<a id=\"recipe$get_recipe_id\"></a>
					<a href=\"index.php?open=$open&amp;page=edit_recipe_general&amp;recipe_id=$get_recipe_id&amp;editor_language=$editor_language&amp;l=$l\"><img src=\"../$get_recipe_image_path/$get_recipe_thumb_h_a_278x156\" alt=\"$get_recipe_image_h_a\" /></a><br />
					<a href=\"index.php?open=$open&amp;page=edit_recipe_general&amp;recipe_id=$get_recipe_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"recipe_title\">$get_recipe_title</a>
				</p>
				
				<p class=\"recipe_actions\">
					<a href=\"../recipes/view_recipe.php?recipe_id=$get_recipe_id&amp;l=$l\" class=\"btn_default\">View</a>
					<a href=\"index.php?open=$open&amp;page=edit_recipe_general&amp;recipe_id=$get_recipe_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">Edit</a>
					<a href=\"index.php?open=$open&amp;page=delete_recipe&amp;recipe_id=$get_recipe_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">Delete</a>
				</p>
					
			</div>

			";
			// Increment
			$x = $x+1;
			if($x == "4"){ 
				echo"<div class=\"clear\"></div>\n";
				$x = 0; 
			}
		}


	if($x == "0"){
	}
	elseif($x == "1"){
		echo"
		</div> <!-- //left_center_center_left_right_left -->

		<div class=\"left_center_center_right_right_center\">
		</div> <!-- //left_center_center_right_right_center -->

		<div class=\"left_center_center_right_right_center\">
		</div> <!-- //left_center_center_right_right_center -->

		<div class=\"left_center_center_right_right\">
		</div> <!-- //left_center_center_right_right -->
		";
	}
	elseif($x == "2"){
		echo"
		<div class=\"left_center_center_right_right_center\">
		</div> <!-- //left_center_center_right_right_center -->

		<div class=\"left_center_center_right_right\">
		</div> <!-- //left_center_center_right_right -->
		";
	}
	elseif($x == "3"){
		echo"
		<div class=\"left_center_center_right_right\">
		</div> <!-- //left_center_center_right_right -->
		";
	}
		echo"
		
	<!-- //List all recipes -->
	";
}
elseif($action == "open_main_category" && isset($_GET['main_category_id'])){
	
	// Get variables
	$main_category_id = $_GET['main_category_id'];
	$main_category_id = strip_tags(stripslashes($main_category_id));
	$main_category_id_mysql = quote_smart($link, $main_category_id);

	$language_mysql = quote_smart($link, $language);

	// Select category
	$query = "SELECT category_id, category_user_id, category_name, category_parent_id FROM $t_diet_categories WHERE category_id=$main_category_id_mysql AND category_language=$language_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_main_category_id, $get_current_main_category_user_id, $get_current_main_category_name, $get_current_main_category_parent_id) = $row;

	if($get_current_main_category_id == ""){
		echo"
		<h1>Server error 404</h1>

		<p>Category not found.</p>

		<p><a href=\"index.php?open=stram&amp;page=categories&amp;language=$language\">Categories</a></p>
		";
	}
	else{
		echo"
		<h1>$get_current_main_category_name</h1>


		<!-- Language -->
			<table style=\"width: 100%;\">
			 <tr>
			  <td>
				<p>
				<a href=\"index.php?open=stram&amp;page=food&amp;action=new_food&amp;language=$language\">New food</a>
				</p>
			  </td>
			  <td style=\"text-align: right;\">
				<form>
				<select name=\"inp_language\" id=\"inp_language\">\n";

				if(file_exists("_data/config/active_languages.php")){
					$fh = @fopen("_data/config/active_languages.php", "r");
					$active_languages = @fread($fh, @filesize("_data/config/active_languages.php"));
					@fclose($fh);
			
					$active_languages_array = explode("\n", $active_languages);
					$active_languages_size = sizeof($active_languages_array);
				}
				else{
					$active_languages_size = "0";
				}
				for($x=0;$x<$active_languages_size;$x++){
					$temp = explode("|", $active_languages_array[$x]);
					$id 		= $temp[0];
					$name 		= $temp[1];
					$slug 		= $temp[2];
					$native_name	= $temp[3];
					$iso_two 	= $temp[4];
					$iso_three 	= $temp[5];
					$flag	 	= $temp[6];
					$charset	= $temp[7];
					echo"<option value=\"index.php?open=stram&amp;page=food&amp;action=open_main_category&amp;main_category_id=$main_category_id&amp;language=$iso_two\""; if($language == "$iso_two"){ echo" selected=\"selected\""; } echo">$name</option>\n";
				}
				echo"
				</select>
				</form>

				<script>
   				 $(function(){
    				  // bind change event to select
    				  $('#inp_language').on('change', function () {
     				     var url = $(this).val(); // get selected value
      				    if (url) { // require a URL
       			    	   window.location = url; // redirect
      			 	   }
      				    return false;
   				   });
  				  });
				</script>

			  </td>
			 </tr>
			</table>
		<!-- //Language -->


		<!-- Left and right -->
			<div style=\"float: left;\">
				<!-- Main and sub categories -->
					<table style=\"width: 100%;\">
					 <tr>
					  <td class=\"outline\">
						<table style=\"width: 100%; border-spacing: 1px;border-collapse: separate;\">
						 <tr>
						  <td class=\"bodycell\">
							<p>";

							// Get all categories
							$language_mysql = quote_smart($link, $language);
							$query = "SELECT _id, category_id, category_name, category_parent_id FROM $t_diet_categories WHERE category_user_id='0' AND category_parent_id='0' AND category_language=$language_mysql ORDER BY category_name ASC";
							$result = mysqli_query($link, $query);
							while($row = mysqli_fetch_row($result)) {
								list($get_main_id, $get_main_category_id, $get_main_category_name, $get_main_category_parent_id) = $row;
				
								echo"
								<a href=\"index.php?open=stram&amp;page=food&amp;action=open_main_category&amp;main_category_id=$get_main_category_id&amp;language=$language\""; if($get_main_category_id == "$get_current_main_category_id"){ echo" style=\"font-weight: bold;\""; } echo">$get_main_category_name</a><br />
								";


								// Get sub
								if($get_main_category_id == "$get_current_main_category_id"){
									$queryb = "SELECT _id, category_id, category_name, category_parent_id FROM $t_diet_categories WHERE category_user_id='0' AND category_parent_id='$get_main_category_id' AND category_language=$language_mysql ORDER BY category_name ASC";
									$resultb = mysqli_query($link, $queryb);
									while($rowb = mysqli_fetch_row($resultb)) {
										list($get_sub_id, $get_sub_category_id, $get_sub_category_name, $get_sub_category_parent_id) = $rowb;
				
										echo"
										&nbsp; &nbsp; <a href=\"index.php?open=stram&amp;page=food&amp;action=open_sub_category&amp;main_category_id=$get_main_category_id&amp;sub_category_id=$get_sub_category_id&amp;language=$language\">$get_sub_category_name</a><br />
										";
									}

								}

							}

							echo"
							</p>
						  </td>
						 </tr>
						</table>
					  </td>
					 </tr>
					</table>
				<!-- //Main and sub categories -->
			</div>
			<div style=\"float: left;padding: 0px 0px 0px 20px;\">
				<p>Select a sub category.</p>
			</div>
		<!-- //Left and right -->


		";
	}
} // open main category

elseif($action == "open_sub_category" && isset($_GET['main_category_id']) && isset($_GET['sub_category_id'])){
	
	// Get variables
	$main_category_id = $_GET['main_category_id'];
	$main_category_id = strip_tags(stripslashes($main_category_id));
	$main_category_id_mysql = quote_smart($link, $main_category_id);

	$sub_category_id = $_GET['sub_category_id'];
	$sub_category_id = strip_tags(stripslashes($sub_category_id));
	$sub_category_id_mysql = quote_smart($link, $sub_category_id);

	$language_mysql = quote_smart($link, $language);

	// Select main category
	$query = "SELECT category_id, category_user_id, category_name, category_parent_id FROM $t_diet_categories WHERE category_id=$main_category_id_mysql AND category_language=$language_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_main_category_id, $get_current_main_category_user_id, $get_current_main_category_name, $get_current_main_category_parent_id) = $row;

	// Select sub category
	$query = "SELECT category_id, category_user_id, category_name, category_parent_id FROM $t_diet_categories WHERE category_id=$sub_category_id_mysql AND category_language=$language_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_sub_category_id, $get_current_sub_category_user_id, $get_current_sub_category_name, $get_current_sub_category_parent_id) = $row;

	
	if($get_current_main_category_id == ""){
		echo"
		<h1>Server error 404</h1>

		<p>Main category not found.</p>

		<p><a href=\"index.php?open=stram&amp;page=food&amp;language=$language\">Categories</a></p>
		";
	}
	else{

		if($get_current_sub_category_id == ""){
			echo"
			<h1>Server error 404</h1>

			<p>Sub category not found.</p>
	
			<p><a href=\"index.php?open=stram&amp;page=food&amp;language=$language\">Categories</a></p>
			";
		}
		else{
			// Directory for storing
			$get_current_main_category_name_clean = clean($get_current_main_category_name);
			$get_current_sub_category_name_clean = clean($get_current_sub_category_name);

			if(!(is_dir("../food"))){
				mkdir("../food");
			}
			if(!(is_dir("../food/_img"))){
				mkdir("../food/_img");
			}
			if(!(is_dir("../food/_img"))){
				mkdir("../food/_img");
			}
			if(!(is_dir("../food/_img/$language"))){
				mkdir("../food/_img/$language");
			}
			if(!(is_dir("../food/_img/$language/$get_current_main_category_name_clean"))){
				mkdir("../food/_img/$language/$get_current_main_category_name_clean");
			}
			if(!(is_dir("../food/_img/$language/$get_current_main_category_name_clean/$get_current_sub_category_name_clean"))){
				mkdir("../food/_img/$language/$get_current_main_category_name_clean/$get_current_sub_category_name_clean");
			}



			echo"
			<h1>$get_current_sub_category_name</h1>


			<!-- Language -->
				<table style=\"width: 100%;\">
				 <tr>
				  <td>
					<p>
					<a href=\"index.php?open=stram&amp;page=food&amp;action=new_food&amp;language=$language\">New food</a>
					</p>
				  </td>
				  <td style=\"text-align: right;\">
					<form>
					<select name=\"inp_language\" id=\"inp_language\">\n";

					if(file_exists("_data/config/active_languages.php")){
						$fh = @fopen("_data/config/active_languages.php", "r");
						$active_languages = @fread($fh, @filesize("_data/config/active_languages.php"));
						@fclose($fh);
			
						$active_languages_array = explode("\n", $active_languages);
						$active_languages_size = sizeof($active_languages_array);
					}
					else{
						$active_languages_size = "0";
					}
					for($x=0;$x<$active_languages_size;$x++){
						$temp = explode("|", $active_languages_array[$x]);
						$id 		= $temp[0];
						$name 		= $temp[1];
						$slug 		= $temp[2];
						$native_name	= $temp[3];
						$iso_two 	= $temp[4];
						$iso_three 	= $temp[5];
						$flag	 	= $temp[6];
						$charset	= $temp[7];
						echo"<option value=\"index.php?open=stram&amp;page=food&amp;action=open_sub_category&amp;main_category_id=$main_category_id&amp;sub_category_id=$sub_category_id&amp;language=$iso_two\""; if($language == "$iso_two"){ echo" selected=\"selected\""; } echo">$name</option>\n";
					}
					echo"
					</select>
					</form>

					<script>
   					 $(function(){
    					  // bind change event to select
    					  $('#inp_language').on('change', function () {
     					     var url = $(this).val(); // get selected value
      					    if (url) { // require a URL
       			    		   window.location = url; // redirect
      			 		   }
      					    return false;
   					   });
  					  });
					</script>
				  </td>
				 </tr>
				</table>
			<!-- //Language -->


			<!-- Left and right -->
				<div style=\"float: left;\">
					<!-- Main and sub categories -->
						<table style=\"width: 100%;\">
						 <tr>
						  <td class=\"outline\">
							<table style=\"width: 100%; border-spacing: 1px;border-collapse: separate;\">
							 <tr>
							  <td class=\"bodycell\">
								<p>";

								// Get all categories
								$language_mysql = quote_smart($link, $language);
								$query = "SELECT _id, category_id, category_name, category_parent_id FROM $t_diet_categories WHERE category_user_id='0' AND category_parent_id='0' AND category_language=$language_mysql ORDER BY category_name ASC";
								$result = mysqli_query($link, $query);
								while($row = mysqli_fetch_row($result)) {
									list($get_main_id, $get_main_category_id, $get_main_category_name, $get_main_category_parent_id) = $row;
				
									echo"
									<a href=\"index.php?open=stram&amp;page=food&amp;action=open_main_category&amp;main_category_id=$get_main_category_id&amp;language=$language\""; if($get_main_category_id == "$get_current_main_category_id"){ echo" style=\"font-weight: bold;\""; } echo">$get_main_category_name</a><br />
									";

									// Get sub
									if($get_main_category_id == "$get_current_main_category_id"){
										$queryb = "SELECT _id, category_id, category_name, category_parent_id FROM $t_diet_categories WHERE category_user_id='0' AND category_parent_id='$get_main_category_id' AND category_language=$language_mysql ORDER BY category_name ASC";
										$resultb = mysqli_query($link, $queryb);
										while($rowb = mysqli_fetch_row($resultb)) {
											list($get_sub_id, $get_sub_category_id, $get_sub_category_name, $get_sub_category_parent_id) = $rowb;
				
											echo"
											&nbsp; &nbsp; <a href=\"index.php?open=stram&amp;page=food&amp;action=open_sub_category&amp;main_category_id=$get_main_category_id&amp;sub_category_id=$get_sub_category_id&amp;language=$language\""; if($get_sub_category_id == "$get_current_sub_category_id"){ echo" style=\"font-weight: bold;\""; } echo">$get_sub_category_name</a><br />
											";
										}

									}

								}

								echo"
								</p>
							  </td>
							 </tr>
							</table>
						  </td>
						 </tr>
						</table>
					<!-- //Main and sub categories -->
				</div>
				<div style=\"float: left;padding: 0px 0px 0px 20px;\">

					<!-- Feedback -->
						";
						if(isset($fm)){
							echo"
							<div class=\"$ft\">
								<p>
								";
								if($fm == "food_deleted"){
									echo"Food deleted.<br />\n";
								}
								echo"
								</p>
							</div>";
						}
						echo"
					<!-- //Feedback -->


					<!-- Show food from sub category -->


								<table style=\"width: 100%;\">
						";
						// Set layout
						$layout = 0;

						// Get all food
						$query = "SELECT _id, food_id, food_user_id, food_name, food_manufacturer_name, food_store, food_description, food_serving_size_gram, food_serving_size_gram_mesurment, food_serving_size_pcs, food_serving_size_pcs_mesurment, food_energy, food_proteins, food_carbohydrates, food_fat, food_energy_calculated, food_proteins_calculated, food_carbohydrates_calculated, food_fat_calculated, food_barcode, food_category_id, food_thumb, food_image_a, food_image_b, food_image_c, food_last_used, food_language, food_synchronized, food_notes FROM $t_diet_food WHERE food_user_id='0' AND food_category_id='$get_current_sub_category_id' AND food_language=$language_mysql ORDER BY food_last_used ASC";
						$result = mysqli_query($link, $query);
						while($row = mysqli_fetch_row($result)) {
							list($get__id, $get_food_id, $get_food_user_id, $get_food_name, $get_food_manufacturer_name, $get_food_store, $get_food_description, $get_food_serving_size_gram, $get_food_serving_size_gram_mesurment, $get_food_serving_size_pcs, $get_food_serving_size_pcs_mesurment, $get_food_energy, $get_food_proteins, $get_food_carbohydrates, $get_food_fat, $get_food_energy_calculated, $get_food_proteins_calculated, $get_food_carbohydrates_calculated, $get_food_fat_calculated, $get_food_barcode, $get_food_category_id, $get_food_thumb, $get_food_image_a, $get_food_image_b, $get_food_image_c, $get_food_last_used, $get_food_language, $get_food_synchronized, $get_food_notes) = $row;
				
							// Check food ID
							if($get_food_id == ""){
								$r = mysqli_query($link, "UPDATE $t_diet_food SET food_id='$get__id' WHERE _id='$get__id'");
							}

							// Name saying
							
							$check = strlen($get_food_name);
							if($check > 20){
								$get_food_name = substr($get_food_name, 0, 23);
								$get_food_name = $get_food_name . "...";
							}	
							
							// Image
							$img = "../food/_img/$language/$get_current_main_category_name_clean/$get_current_sub_category_name_clean/$get_food_thumb";
							if($get_food_thumb == ""){
								$img = "_design/gfx/no_thumb.jpg";
							}
							else{ 
								if(!(file_exists("$img"))){
									echo"Removing thumb from path";
									$res = mysqli_query($link, "UPDATE $t_diet_food SET food_thumb='', food_image_a='' WHERE _id='$get__id'");

								}
							}



							if($layout == 0){
								echo"
								 <tr>
								  <td style=\"width: 143px;padding: 0px 10px 0px 0px;vertical-align: top;text-align: center;\">
									<p style=\"padding-bottom:0;\">
									<a href=\"index.php?open=stram&amp;page=food&amp;action=view_food&amp;main_category_id=$main_category_id&amp;sub_category_id=$sub_category_id&amp;food_id=$get__id&amp;language=$language\"><img src=\"$img\" alt=\"$img\" style=\"margin-bottom: 5px;\" width=\"132\" height=\"140\" /></a><br />
					
									<a href=\"index.php?open=stram&amp;page=food&amp;action=view_food&amp;main_category_id=$main_category_id&amp;sub_category_id=$sub_category_id&amp;food_id=$get__id&amp;language=$language\">$get_food_manufacturer_name</a><br />
									<a href=\"index.php?open=stram&amp;page=food&amp;action=view_food&amp;main_category_id=$main_category_id&amp;sub_category_id=$sub_category_id&amp;food_id=$get__id&amp;language=$language\">$get_food_name</a><br />";
									if($check < 20){	
										echo"<br />";
									}					
									echo"
									</p>

									<div style=\"width: 127px; margin: 0 auto;\">
										<table>
										 <tr>
										  <td style=\"padding-right: 5px;\">
											<span class=\"smal\">$get_food_energy kcal</span>
										  </td>
										  <td style=\"padding-right: 5px;\">
											<span class=\"smal\">$get_food_proteins proteins</span>
										  </td>
										  <td style=\"padding-right: 5px;\">
											<span class=\"smal\">$get_food_carbohydrates carb</span>
										  </td>
										  <td style=\"padding-right: 5px;\">
											<span class=\"smal\">$get_food_fat fat</span>
										  </td>
										 </tr>
										</table>
									</div>
								  </td>
								";
							}
							elseif($layout == 1){
								echo"
								  <td style=\"width: 143px;padding: 0px 10px 0px 0px;vertical-align: top;text-align: center;\">
									<p style=\"padding-bottom:0;\">
									<a href=\"index.php?open=stram&amp;page=food&amp;action=view_food&amp;main_category_id=$main_category_id&amp;sub_category_id=$sub_category_id&amp;food_id=$get__id&amp;language=$language\"><img src=\"$img\" alt=\"$img\" style=\"margin-bottom: 5px;\" width=\"132\" height=\"140\" /></a><br />
					
									<a href=\"index.php?open=stram&amp;page=food&amp;action=view_food&amp;main_category_id=$main_category_id&amp;sub_category_id=$sub_category_id&amp;food_id=$get__id&amp;language=$language\">$get_food_manufacturer_name</a><br />
									<a href=\"index.php?open=stram&amp;page=food&amp;action=view_food&amp;main_category_id=$main_category_id&amp;sub_category_id=$sub_category_id&amp;food_id=$get__id&amp;language=$language\">$get_food_name</a><br />";
									if($check < 20){	
										echo"<br />";
									}					
									echo"
									</p>

									<div style=\"width: 127px; margin: 0 auto;\">
										<table>
										 <tr>
										  <td style=\"padding-right: 5px;\">
											<span class=\"smal\">$get_food_energy kcal</span>
										  </td>
										  <td style=\"padding-right: 5px;\">
											<span class=\"smal\">$get_food_proteins proteins</span>
										  </td>
										  <td style=\"padding-right: 5px;\">
											<span class=\"smal\">$get_food_carbohydrates carb</span>
										  </td>
										  <td style=\"padding-right: 5px;\">
											<span class=\"smal\">$get_food_fat fat</span>
										  </td>
										 </tr>
										</table>
									</div>
								  </td>
								";
							}
							elseif($layout == 2){
								echo"
								  <td style=\"width: 143px;padding: 0px 10px 0px 0px;vertical-align: top;text-align: center;\">
									<p style=\"padding-bottom:0;\">
									<a href=\"index.php?open=stram&amp;page=food&amp;action=view_food&amp;main_category_id=$main_category_id&amp;sub_category_id=$sub_category_id&amp;food_id=$get__id&amp;language=$language\"><img src=\"$img\" alt=\"$img\" style=\"margin-bottom: 5px;\" width=\"132\" height=\"140\" /></a><br />
					
									<a href=\"index.php?open=stram&amp;page=food&amp;action=view_food&amp;main_category_id=$main_category_id&amp;sub_category_id=$sub_category_id&amp;food_id=$get__id&amp;language=$language\">$get_food_manufacturer_name</a><br />
									<a href=\"index.php?open=stram&amp;page=food&amp;action=view_food&amp;main_category_id=$main_category_id&amp;sub_category_id=$sub_category_id&amp;food_id=$get__id&amp;language=$language\">$get_food_name</a><br />";
									if($check < 20){	
										echo"<br />";
									}					
									echo"
									</p>

									<div style=\"width: 127px; margin: 0 auto;\">
										<table>
										 <tr>
										  <td style=\"padding-right: 5px;\">
											<span class=\"smal\">$get_food_energy kcal</span>
										  </td>
										  <td style=\"padding-right: 5px;\">
											<span class=\"smal\">$get_food_proteins proteins</span>
										  </td>
										  <td style=\"padding-right: 5px;\">
											<span class=\"smal\">$get_food_carbohydrates carb</span>
										  </td>
										  <td style=\"padding-right: 5px;\">
											<span class=\"smal\">$get_food_fat fat</span>
										  </td>
										 </tr>
										</table>
									</div>
								  </td>
								 </tr>
								";
								$layout = -1;
							}
							$layout++;
						} // while

						echo"
						</table>
					<!-- //Show food from sub category -->
				</div>
			<!-- //Left and right -->


			";
		} // sub category found
	} // main category found
} // open sub category
elseif($action == "view_food" OR $action == "edit_food" OR $action == "delete_food" && isset($_GET['food_id'])){
	
	// Get variables
	$food_id = $_GET['food_id'];
	$food_id = strip_tags(stripslashes($food_id));
	$food_id_mysql = quote_smart($link, $food_id);

	$language_mysql = quote_smart($link, $language);

	// Select food
	$query = "SELECT _id, food_id, food_user_id, food_name, food_manufacturer_name, food_store, food_description, food_serving_size_gram, food_serving_size_gram_mesurment, food_serving_size_pcs, food_serving_size_pcs_mesurment, food_energy, food_proteins, food_carbohydrates, food_fat, food_energy_calculated, food_proteins_calculated, food_carbohydrates_calculated, food_fat_calculated, food_barcode, food_category_id, food_image_path, food_thumb, food_image_a, food_image_b, food_image_c, food_last_used, food_language, food_synchronized, food_notes FROM $t_diet_food WHERE food_id=$food_id_mysql AND food_language=$language_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get__id, $get_food_id, $get_food_user_id, $get_food_name, $get_food_manufacturer_name, $get_food_store, $get_food_description, $get_food_serving_size_gram, $get_food_serving_size_gram_mesurment, $get_food_serving_size_pcs, $get_food_serving_size_pcs_mesurment, $get_food_energy, $get_food_proteins, $get_food_carbohydrates, $get_food_fat, $get_food_energy_calculated, $get_food_proteins_calculated, $get_food_carbohydrates_calculated, $get_food_fat_calculated, $get_food_barcode, $get_food_category_id, $get_food_image_path, $get_food_thumb, $get_food_image_a, $get_food_image_b, $get_food_image_c, $get_food_last_used, $get_food_language, $get_food_synchronized, $get_food_notes) = $row;

	if($get__id == ""){
		echo"
		<h1>Food not found</h1>

		<p>
		Sorry, the food was not found.
		</p>

		<p>
		<a href=\"index.php?open=stram&amp;page=food\">Back</a>
		</p>
		";
	}
	else{
		// Get sub category
		$query = "SELECT category_id, category_user_id, category_name, category_parent_id FROM $t_diet_categories WHERE category_id=$get_food_category_id AND category_language=$language_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_sub_category_id, $get_current_sub_category_user_id, $get_current_sub_category_name, $get_current_sub_category_parent_id) = $row;

		// Get main category
		$query = "SELECT category_id, category_user_id, category_name, category_parent_id FROM $t_diet_categories WHERE category_id=$get_current_sub_category_parent_id AND category_language=$language_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_main_category_id, $get_current_main_category_user_id, $get_current_main_category_name, $get_current_main_category_parent_id) = $row;

		// Directory for storing
		$get_current_main_category_name_clean = clean($get_current_main_category_name);
		$get_current_sub_category_name_clean = clean($get_current_sub_category_name);
		if($get_food_image_path != "food/_img/$language/$get_current_main_category_name_clean/$get_current_sub_category_name_clean"){
			$inp_food_image_path = "food/_img/$language/$get_current_main_category_name_clean/$get_current_sub_category_name_clean";
			$inp_food_image_path_mysql = quote_smart($link, $inp_food_image_path);

			echo"<p>Updating food path from $get_food_image_path to $inp_food_image_path</p>";

			$result = mysqli_query($link, "UPDATE $t_diet_food SET food_image_path=$inp_food_image_path_mysql WHERE _id='$get__id'");

		}
		




		if($process != "1"){
			echo"
			<h1>$get_food_manufacturer_name $get_food_name";

			if($get_food_store != ""){
				echo"<br />$get_food_store";
			}
			echo"</h1>





			<!-- Left and right -->
			<div style=\"float: left;\">
				<!-- Main and sub categories -->
					<table style=\"width: 100%;\">
					 <tr>
					  <td class=\"outline\">
						<table style=\"width: 100%; border-spacing: 1px;border-collapse: separate;\">
						 <tr>
						  <td class=\"bodycell\">
							<p>";
							// Get all categories
							$language_mysql = quote_smart($link, $language);
							$query = "SELECT _id, category_id, category_name, category_parent_id FROM $t_diet_categories WHERE category_user_id='0' AND category_parent_id='0' AND category_language=$language_mysql ORDER BY category_name ASC";
							$result = mysqli_query($link, $query);
							while($row = mysqli_fetch_row($result)) {
								list($get_main_id, $get_main_category_id, $get_main_category_name, $get_main_category_parent_id) = $row;
			
								echo"
								<a href=\"index.php?open=stram&amp;page=food&amp;action=open_main_category&amp;main_category_id=$get_main_category_id&amp;language=$language\""; if($get_main_category_id == "$get_current_main_category_id"){ echo" style=\"font-weight: bold;\""; } echo">$get_main_category_name</a><br />
								";

								// Get sub
								if($get_main_category_id == "$get_current_main_category_id"){
									$queryb = "SELECT _id, category_id, category_name, category_parent_id FROM $t_diet_categories WHERE category_user_id='0' AND category_parent_id='$get_main_category_id' AND category_language=$language_mysql ORDER BY category_name ASC";
									$resultb = mysqli_query($link, $queryb);
									while($rowb = mysqli_fetch_row($resultb)) {
										list($get_sub_id, $get_sub_category_id, $get_sub_category_name, $get_sub_category_parent_id) = $rowb;
			
										echo"
										&nbsp; &nbsp; <a href=\"index.php?open=stram&amp;page=food&amp;action=open_sub_category&amp;main_category_id=$get_main_category_id&amp;sub_category_id=$get_sub_category_id&amp;language=$language\""; if($get_sub_category_id == "$get_current_sub_category_id"){ echo" style=\"font-weight: bold;\""; } echo">$get_sub_category_name</a><br />
										";
									}
								}
							}

							echo"
							</p>
						  </td>
						 </tr>
						</table>
					  </td>
					 </tr>
					</table>
				<!-- //Main and sub categories -->
			</div>
			<div style=\"float: left;padding: 0px 0px 0px 20px;\">

				<!-- Right -->
				
					<p>
					<a href=\"index.php?open=stram&amp;page=food&amp;action=open_sub_category&amp;main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;language=$language#food_id$food_id\">$get_current_sub_category_name</a>
					|
					<a href=\"index.php?open=stram&amp;page=food&amp;action=view_food&amp;main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;food_id=$food_id&amp;language=$language\""; if($action == "view_food"){ echo" style=\"font-weight: bold;\""; } echo">View</a>
					|
					<a href=\"index.php?open=stram&amp;page=food&amp;action=edit_food&amp;main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;food_id=$food_id&amp;language=$language\""; if($action == "edit_food"){ echo" style=\"font-weight: bold;\""; } echo">Edit</a>
					|
					<a href=\"index.php?open=stram&amp;page=food&amp;action=delete_food&amp;main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;food_id=$food_id&amp;language=$language\""; if($action == "delete_food"){ echo" style=\"font-weight: bold;\""; } echo">Delete</a>
					</p>
					";
			
		} // if($process != "1"){
					if($action == "view_food"){
						echo"


						<!-- Images -->
						<p>";
						if($get_food_image_a != ""){
							if(file_exists("../food/_img/$language/$get_current_main_category_name_clean/$get_current_sub_category_name_clean/$get_food_image_a")){
							echo"<img src=\"../food/_img/$language/$get_current_main_category_name_clean/$get_current_sub_category_name_clean/$get_food_image_a\" alt=\"../food/_img/$language/$get_current_main_category_name_clean/$get_current_sub_category_name_clean/$get_food_image_a\" width=\"400\" height=\"424\" />";
							}
							else{
								echo"<p>Image A not found. Deleting from database.</p>\n";
								$result = mysqli_query($link, "UPDATE $t_diet_food SET food_image_a='' WHERE _id='$get__id'");
							}
						}
						echo"
						";
						if($get_food_image_b != ""){

							if(file_exists("../food/_img/$language/$get_current_main_category_name_clean/$get_current_sub_category_name_clean/$get_food_image_b")){
								echo"<img src=\"../food/_img/$language/$get_current_main_category_name_clean/$get_current_sub_category_name_clean/$get_food_image_b\" alt=\"../food/_img/$language/$get_current_main_category_name_clean/$get_current_sub_category_name_clean/$get_food_image_b\" width=\"400\" height=\"424\" />";
							}
							else{
								echo"<p>Image B not found. Deleting from database.</p>\n";
								$result = mysqli_query($link, "UPDATE $t_diet_food SET food_image_b='' WHERE _id='$get__id'");
							}
						}
						echo"
						";
						if($get_food_image_c != ""){
							if(file_exists("../food/_img/$language/$get_current_main_category_name_clean/$get_current_sub_category_name_clean/$get_food_image_c")){
								echo"<img src=\"../food/_img/$language/$get_current_main_category_name_clean/$get_current_sub_category_name_clean/$get_food_image_c\" alt=\"../food/_img/$language/$get_current_main_category_name_clean/$get_current_sub_category_name_clean/$get_food_image_c\" width=\"400\" height=\"424\" />";
							}
							else{
								echo"<p>Image C not found. Deleting from database.</p>\n";
								$result = mysqli_query($link, "UPDATE $t_diet_food SET food_image_c='' WHERE _id='$get__id'");
							}
						}
						echo"
						</p>
					<!-- //Images -->
		
					<!-- About -->
						<p>
						$get_food_serving_size_gram $get_food_serving_size_gram_mesurment is equivalent with
						$get_food_serving_size_pcs $get_food_serving_size_pcs_mesurment. 
						$get_food_description
						</p>
					<!-- //About -->

					<!-- Numbers -->
						<h2>Numbers</h2>

						<table>
						 <tr>
						  <td class=\"outline\">

							<table style=\"width: 100%;border-spacing: 1px;border-collapse: separate;\">
							 <tr>
							  <td class=\"headcell\" style=\"text-align: right;padding: 0px 4px 0px 4px;\">
				
							  </td>
							  <td class=\"headcell\" style=\"padding: 0px 4px 0px 4px;text-align:center;\">
								<p>Energy</p>
							  </td>
							  <td class=\"headcell\" style=\"padding: 0px 4px 0px 4px;text-align:center;\">
								<p>Proteins</p>
							  </td>
							  <td class=\"headcell\" style=\"padding: 0px 4px 0px 4px;text-align:center;\">
								<p>Carbs</p>
							  </td>
							  <td class=\"headcell\" style=\"padding: 0px 4px 0px 4px;text-align:center;\">
								<p>Fat</p>
							  </td>
							 </tr>

							 <tr>
							  <td class=\"bodycell\" style=\"text-align: right;padding: 0px 4px 0px 4px;\">
								<p><b>Per 100:</b></p>
							  </td>
							  <td class=\"bodycell\" style=\"padding: 0px 4px 0px 0px;text-align:center;\">
								<span>$get_food_energy</span>
							  </td>
							  <td class=\"bodycell\" style=\"padding: 0px 4px 0px 0px;text-align:center;\">
								<span>$get_food_proteins</span>
							  </td>
							  <td class=\"bodycell\" style=\"padding: 0px 4px 0px 0px;text-align:center;\">
								<span>$get_food_carbohydrates</span>
				 			 </td>
				 			 <td class=\"bodycell\" style=\"padding: 0px 4px 0px 0px;text-align:center;\">
								<span>$get_food_fat</span>
				 			 </td>
				 			</tr>

							 <tr>
							  <td class=\"subcell\" style=\"text-align: right;padding: 0px 4px 0px 4px;\">
								<p><b>Per serving:</b></p>
							  </td>
							  <td class=\"subcell\" style=\"padding: 0px 4px 0px 0px;text-align:center;\">
								<span>$get_food_energy_calculated</span>
							  </td>
							  <td class=\"subcell\" style=\"padding: 0px 4px 0px 0px;text-align:center;\">
								<span>$get_food_proteins_calculated</span>
							  </td>
							  <td class=\"subcell\" style=\"padding: 0px 4px 0px 0px;text-align:center;\">
								<span>$get_food_carbohydrates_calculated</span>
							  </td>
							  <td class=\"subcell\" style=\"padding: 0px 4px 0px 0px;text-align:center;\">
								<span>$get_food_fat_calculated</span>
							  </td>
							 </tr>
							</table>
						  </td>
						 </tr>
						</table>

						<!-- //Numbers -->
						";
					} // action == "view_food"
					elseif($action == "edit_food" && isset($_GET['food_id'])){
	
						// Get variables
						$food_id = $_GET['food_id'];
						$food_id = strip_tags(stripslashes($food_id));
						$food_id_mysql = quote_smart($link, $food_id);

						$language_mysql = quote_smart($link, $language);

						// Select food
						$query = "SELECT _id, food_id, food_user_id, food_name, food_manufacturer_name, food_store, food_description, food_serving_size_gram, food_serving_size_gram_mesurment, food_serving_size_pcs, food_serving_size_pcs_mesurment, food_energy, food_proteins, food_carbohydrates, food_fat, food_energy_calculated, food_proteins_calculated, food_carbohydrates_calculated, food_fat_calculated, food_barcode, food_category_id, food_thumb, food_image_a, food_image_b, food_image_c, food_last_used, food_language, food_synchronized, food_notes FROM $t_diet_food WHERE food_id=$food_id_mysql AND food_language=$language_mysql";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get__id, $get_food_id, $get_food_user_id, $get_food_name, $get_food_manufacturer_name, $get_food_store, $get_food_description, $get_food_serving_size_gram, $get_food_serving_size_gram_mesurment, $get_food_serving_size_pcs, $get_food_serving_size_pcs_mesurment, $get_food_energy, $get_food_proteins, $get_food_carbohydrates, $get_food_fat, $get_food_energy_calculated, $get_food_proteins_calculated, $get_food_carbohydrates_calculated, $get_food_fat_calculated, $get_food_barcode, $get_food_category_id, $get_food_thumb, $get_food_image_a, $get_food_image_b, $get_food_image_c, $get_food_last_used, $get_food_language, $get_food_synchronized, $get_food_notes) = $row;

						if($get__id == ""){
							echo"
							<h1>Food not found</h1>

							<p>
							Sorry, the food was not found.
							</p>

							<p>
							<a href=\"index.php?open=stram&amp;page=food\">Back</a>
							</p>
							";
						}
						else{
							// Get sub category
							$query = "SELECT category_id, category_user_id, category_name, category_parent_id FROM $t_diet_categories WHERE category_id=$get_food_category_id AND category_language=$language_mysql";
							$result = mysqli_query($link, $query);
							$row = mysqli_fetch_row($result);
							list($get_current_sub_category_id, $get_current_sub_category_user_id, $get_current_sub_category_name, $get_current_sub_category_parent_id) = $row;

							// Get main category
							$query = "SELECT category_id, category_user_id, category_name, category_parent_id FROM $t_diet_categories WHERE category_id=$get_current_sub_category_parent_id AND category_language=$language_mysql";
							$result = mysqli_query($link, $query);
							$row = mysqli_fetch_row($result);
							list($get_current_main_category_id, $get_current_main_category_user_id, $get_current_main_category_name, $get_current_main_category_parent_id) = $row;
			
							// Directory for storing
							$get_current_main_category_name_clean = clean($get_current_main_category_name);
							$get_current_sub_category_name_clean = clean($get_current_sub_category_name);




							// Process
							if($process == "1"){
								$inp_food_name = $_POST['inp_food_name'];
								$inp_food_name = output_html($inp_food_name);
								$inp_food_name_mysql = quote_smart($link, $inp_food_name);

								$inp_food_name_clean = clean($inp_food_name);
								$inp_food_name_clean = substr($inp_food_name_clean, 0, 30);

								$inp_food_manufacturer_name = $_POST['inp_food_manufacturer_name'];
								$inp_food_manufacturer_name = output_html($inp_food_manufacturer_name);
								$inp_food_manufacturer_name_mysql = quote_smart($link, $inp_food_manufacturer_name);

								$inp_food_store = $_POST['inp_food_store'];
								$inp_food_store = output_html($inp_food_store);
								$inp_food_store_mysql = quote_smart($link, $inp_food_store);

								$inp_food_description = $_POST['inp_food_description'];
								$inp_food_description = output_html($inp_food_description);
								$inp_food_description_mysql = quote_smart($link, $inp_food_description);

								$inp_food_barcode = $_POST['inp_food_barcode'];
								$inp_food_barcode = output_html($inp_food_barcode);
								$inp_food_barcode_mysql = quote_smart($link, $inp_food_barcode);
	
								// Category
								$inp_food_category_id = $_POST['inp_food_category_id'];
								$inp_food_category_id = output_html($inp_food_category_id);
								$inp_food_category_id_mysql = quote_smart($link, $inp_food_category_id);
								if($inp_food_category_id == ""){
									$fm_category_error = "invalid_category";
								}
								else{
									$result = mysqli_query($link, "UPDATE $t_diet_food SET food_category_id=$inp_food_category_id_mysql WHERE _id='$get__id'");

								}

								// Serving
								$inp_food_serving_size_gram = $_POST['inp_food_serving_size_gram'];
								$inp_food_serving_size_gram = output_html($inp_food_serving_size_gram);
								$inp_food_serving_size_gram = str_replace(",", ".", $inp_food_serving_size_gram);
								$inp_food_serving_size_gram_mysql = quote_smart($link, $inp_food_serving_size_gram);

								$inp_food_serving_size_gram_mesurment = $_POST['inp_food_serving_size_gram_mesurment'];
													$inp_food_serving_size_gram_mesurment = output_html($inp_food_serving_size_gram_mesurment);
								$inp_food_serving_size_gram_mesurment_mysql = quote_smart($link, $inp_food_serving_size_gram_mesurment);

								$inp_food_serving_size_pcs = $_POST['inp_food_serving_size_pcs'];
								$inp_food_serving_size_pcs = output_html($inp_food_serving_size_pcs);
								$inp_food_serving_size_pcs = str_replace(",", ".", $inp_food_serving_size_pcs);
								$inp_food_serving_size_pcs_mysql = quote_smart($link, $inp_food_serving_size_pcs);

								$inp_food_serving_size_pcs_mesurment = $_POST['inp_food_serving_size_pcs_mesurment'];
								$inp_food_serving_size_pcs_mesurment = output_html($inp_food_serving_size_pcs_mesurment);
								$inp_food_serving_size_pcs_mesurment_mysql = quote_smart($link, $inp_food_serving_size_pcs_mesurment);

								// per 100 
								$inp_food_energy = $_POST['inp_food_energy'];
								$inp_food_energy = output_html($inp_food_energy);
								$inp_food_energy = str_replace(",", ".", $inp_food_energy);
								$inp_food_energy_mysql = quote_smart($link, $inp_food_energy);

								$inp_food_proteins = $_POST['inp_food_proteins'];
								$inp_food_proteins = output_html($inp_food_proteins);
								$inp_food_proteins = str_replace(",", ".", $inp_food_proteins);
								$inp_food_proteins_mysql = quote_smart($link, $inp_food_proteins);

								$inp_food_carbohydrates = $_POST['inp_food_carbohydrates'];
								$inp_food_carbohydrates = output_html($inp_food_carbohydrates);
								$inp_food_carbohydrates = str_replace(",", ".", $inp_food_carbohydrates);
								$inp_food_carbohydrates_mysql = quote_smart($link, $inp_food_carbohydrates);

								$inp_food_fat = $_POST['inp_food_fat'];
								$inp_food_fat = output_html($inp_food_fat);
								$inp_food_fat = str_replace(",", ".", $inp_food_fat);
								$inp_food_fat_mysql = quote_smart($link, $inp_food_fat);

								// Calculated
								$inp_food_energy_calculated = round($inp_food_energy*$inp_food_serving_size_gram/100, 0);
								$inp_food_proteins_calculated = round($inp_food_proteins*$inp_food_serving_size_gram/100, 0);
								$inp_food_carbohydrates_calculated = round($inp_food_carbohydrates*$inp_food_serving_size_gram/100, 0);
								$inp_food_fat_calculated = round($inp_food_fat*$inp_food_serving_size_gram/100, 0);
	
								// Food path
								$inp_food_image_path = "food/_img/$language/$get_current_main_category_name_clean/$get_current_sub_category_name_clean";
								$inp_food_image_path_mysql = quote_smart($link, $inp_food_image_path);


								// Update
								$result = mysqli_query($link, "UPDATE $t_diet_food SET food_name=$inp_food_name_mysql, food_manufacturer_name=$inp_food_manufacturer_name_mysql, food_store=$inp_food_store_mysql, food_description=$inp_food_description_mysql, food_serving_size_gram=$inp_food_serving_size_gram_mysql, food_serving_size_gram_mesurment=$inp_food_serving_size_gram_mesurment_mysql, food_serving_size_pcs=$inp_food_serving_size_pcs_mysql, food_serving_size_pcs_mesurment=$inp_food_serving_size_pcs_mesurment_mysql, food_energy=$inp_food_energy_mysql, food_proteins=$inp_food_proteins_mysql, food_carbohydrates=$inp_food_carbohydrates_mysql, food_fat=$inp_food_fat_mysql, food_energy_calculated='$inp_food_energy_calculated', food_proteins_calculated='$inp_food_proteins_calculated', food_carbohydrates_calculated='$inp_food_carbohydrates_calculated', food_fat_calculated='$inp_food_fat_calculated', food_barcode=$inp_food_barcode_mysql, food_image_path=$inp_food_image_path_mysql WHERE _id='$get__id'") or die(mysqli_error($link));



			/*- Image A ------------------------------------------------------------------------------------------ */
			// $tmp_name = $_FILES['inp_food_image_a']['tmp_name'];
			$name = stripslashes($_FILES['inp_food_image_a']['name']);
			$extension = getExtension($name);
			$extension = strtolower($extension);

			if($name){
				if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
					$ft_image_a = "warning";
					$fm_image_a = "unknown_file_extension";
				}
				else{
 
					// Give new name
					$new_name = $inp_food_name_clean . "_a.png";
					$new_path = "../food/_img/$language/$get_current_main_category_name_clean/$get_current_sub_category_name_clean/";
					$uploaded_file = $new_path . $new_name;

					// Upload file
					if (move_uploaded_file($_FILES['inp_food_image_a']['tmp_name'], $uploaded_file)) {


						// Get image size
						$file_size = filesize($uploaded_file);
						
 
						// Check with and height
						list($width,$height) = getimagesize($uploaded_file);
	
						if($width == "" OR $height == ""){
							$ft_image_a = "warning";
							$fm_image_a = "getimagesize_failed";
						}
						else{

							// Orientation
							if ($width > $height) {
								// Landscape
							} else {
								// Portrait or Square
							}


							// Resize to 700x742
							$newwidth=$settings_image_width;
							$newheight=($height/$width)*$newwidth;
							$tmp=imagecreatetruecolor($newwidth,$newheight);
						
							if($extension=="jpg" || $extension=="jpeg" ){
								$src = imagecreatefromjpeg($uploaded_file);
							}
							else if($extension=="png"){
								$src = imagecreatefrompng($uploaded_file);
							}
							else{
								$src = imagecreatefromgif($uploaded_file);
							}

							imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight, $width,$height);
							imagepng($tmp,$uploaded_file);
							imagedestroy($tmp);
						

							// Make thumb 132x140
							$thumb_name = $inp_food_name_clean . "_thumb.png";
							$thumb_file = $new_path . $thumb_name;

							$thumb_with = 132;
							$thumb_height = ($height/$width)*$thumb_with;
							$tmp=imagecreatetruecolor($thumb_with,$thumb_height);


							imagecopyresampled($tmp,$src,0,0,0,0,$thumb_with,$thumb_height, $width,$height);

							imagepng($tmp,$thumb_file);

							imagedestroy($tmp);
							

							// Update MySQL
							$inp_food_thumb_mysql = quote_smart($link, $thumb_name);
							$inp_food_image_a_mysql = quote_smart($link, $new_name);
							$result = mysqli_query($link, "UPDATE $t_diet_food SET food_thumb=$inp_food_thumb_mysql, food_image_a=$inp_food_image_a_mysql WHERE _id='$get__id'");


						}  // if($width == "" OR $height == ""){
					} // move_uploaded_file
					else{
						switch ($_FILES['inp_food_image_a']['error']) {
							case UPLOAD_ERR_OK:
           							$fm_image_a = "image_to_big";
								break;
							case UPLOAD_ERR_NO_FILE:
           							// $fm_image_a = "no_file_uploaded";
								break;
							case UPLOAD_ERR_INI_SIZE:
           							$fm_image_a = "to_big_size_in_configuration";
								break;
							case UPLOAD_ERR_FORM_SIZE:
           							$fm_image_a = "to_big_size_in_form";
								break;
							default:
           							$fm_image_a = "unknown_error";
								break;

						}	
					}
	
				} // extension check
			} // if($image){


								/*- Image B ------------------------------------------------------------------------------------------ */
								// $tmp_name = $_FILES['inp_food_image_b']['tmp_name'];
								$name = stripslashes($_FILES['inp_food_image_b']['name']);
								$extension = getExtension($name);
								$extension = strtolower($extension);

								if($name){
				if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
					$ft_image_a = "warning";
					$fm_image_a = "unknown_file_extension";
				}
				else{
 
					// Give new name
					$new_name = $inp_food_name_clean . "_b.png";
					$new_path = "../food/_img/$language/$get_current_main_category_name_clean/$get_current_sub_category_name_clean/";
					$uploaded_file = $new_path . $new_name;

					// Upload file
					if (move_uploaded_file($_FILES['inp_food_image_b']['tmp_name'], $uploaded_file)) {


						// Get image size
						$file_size = filesize($uploaded_file);
 
						// Check with and height
						list($width,$height) = getimagesize($uploaded_file);
	
						if($width == "" OR $height == ""){
							$ft_image_b = "warning";
							$fm_image_b = "getimagesize_failed";
						}
						else{

							// Orientation
							if ($width > $height) {
								// Landscape
							} else {
								// Portrait or Square
							}


							// Resize to $settings_image_width
							$newwidth=$settings_image_width;
							$newheight=($height/$width)*$newwidth;
							$tmp=imagecreatetruecolor($newwidth,$newheight);
						
							if($extension=="jpg" || $extension=="jpeg" ){
								$src = imagecreatefromjpeg($uploaded_file);
							}
							else if($extension=="png"){
								$src = imagecreatefrompng($uploaded_file);
							}
							else{
								$src = imagecreatefromgif($uploaded_file);
							}

							imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight, $width,$height);
	


							imagepng($tmp,$uploaded_file);

							imagedestroy($tmp);
						
							// Update MySQL
							$inp_food_image_b_mysql = quote_smart($link, $new_name);
							$result = mysqli_query($link, "UPDATE $t_diet_food SET food_image_b=$inp_food_image_b_mysql WHERE _id='$get__id'");


						}  // if($width == "" OR $height == ""){
					} // move_uploaded_file
					else{
						switch ($_FILES['inp_food_image_b']['error']) {
							case UPLOAD_ERR_OK:
           							$fm_image_b = "image_to_big";
								break;
							case UPLOAD_ERR_NO_FILE:
           							// $fm_image_b = "no_file_uploaded";
								break;
							case UPLOAD_ERR_INI_SIZE:
           							$fm_image_b = "to_big_size_in_configuration";
								break;
							case UPLOAD_ERR_FORM_SIZE:
           							$fm_image_b = "to_big_size_in_form";
								break;
							default:
           												$fm_image_b = "unknown_error";
													break;

											}	
										}
	
									} // extension check
								} // if($image){
			

								/*- Image C ------------------------------------------------------------------------------------------ */
								// $tmp_name = $_FILES['inp_food_image_c']['tmp_name'];
								$name = stripslashes($_FILES['inp_food_image_c']['name']);
								$extension = getExtension($name);
								$extension = strtolower($extension);

			if($name){
				if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
					$ft_image_c = "warning";
					$fm_image_c = "unknown_file_extension";
				}
				else{
 
					// Give new name
					$new_name = $inp_food_name_clean . "_c.png";
					$new_path = "../food/_img/$language/$get_current_main_category_name_clean/$get_current_sub_category_name_clean/";
					$uploaded_file = $new_path . $new_name;

					// Upload file
					if (move_uploaded_file($_FILES['inp_food_image_c']['tmp_name'], $uploaded_file)) {


						// Get image size
						$file_size = filesize($uploaded_file);
 
						// Check with and height
						list($width,$height) = getimagesize($uploaded_file);
	
						if($width == "" OR $height == ""){
							$ft_image_c = "warning";
							$fm_image_c = "getimagesize_failed";
						}
						else{

							// Orientation
							if ($width > $height) {
								// Landscape
							} else {
								// Portrait or Square
							}


							// Resize to 700x
							$newwidth=$settings_image_width;
							$newheight=($height/$width)*$newwidth;
							$tmp=imagecreatetruecolor($newwidth,$newheight);
						
							if($extension=="jpg" || $extension=="jpeg" ){
								$src = imagecreatefromjpeg($uploaded_file);
							}
							else if($extension=="png"){
								$src = imagecreatefrompng($uploaded_file);
							}
							else{
								$src = imagecreatefromgif($uploaded_file);
							}

							imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight, $width,$height);
	
							imagepng($tmp,$uploaded_file);

							imagedestroy($tmp);
						
							// Update MySQL
							$inp_food_image_c_mysql = quote_smart($link, $new_name);
							$result = mysqli_query($link, "UPDATE $t_diet_food SET food_image_c=$inp_food_image_c_mysql WHERE _id='$get__id'");


						}  // if($width == "" OR $height == ""){
					} // move_uploaded_file
					else{
						switch ($_FILES['inp_food_image_c']['error']) {
							case UPLOAD_ERR_OK:
           							$fm_image_c = "image_to_big";
								break;
							case UPLOAD_ERR_NO_FILE:
           							// $fm_image_c = "no_file_uploaded";
								break;
							case UPLOAD_ERR_INI_SIZE:
           							$fm_image_c = "to_big_size_in_configuration";
								break;
							case UPLOAD_ERR_FORM_SIZE:
           							$fm_image_c = "to_big_size_in_form";
								break;
							default:
           							$fm_image_c = "unknown_error";
								break;

						}	
					}
	
				} // extension check
			} // if($image){




								// Feedback
								$url = "index.php?open=stram&page=food&action=edit_food&main_category_id=$main_category_id&sub_category_id=$sub_category_id&food_id=$food_id&language=$language&ft=success&fm=changes_saved";
			
								if(isset($fm_category_error)){
									$url = $url . "&ft_category_error=$ft_category_error&fm_category_error=$fm_category_error";
								}

								if(isset($fm_image_a)){
									$url = $url . "&ft_image_a=$ft_image_a&fm_image_a=$fm_image_a";
								}
								if(isset($fm_image_b)){
									$url = $url . "&ft_image_b=$ft_image_a&fm_image_b=$fm_image_c";
								}
								if(isset($fm_image_c)){
									$url = $url . "&ft_image_c=$ft_image_a&fm_image_c=$fm_image_c";
								}

								header("Location: $url");
								exit;

							}

							echo"
		
							<!-- Feedback -->
								";
								if(isset($fm)){
									echo"
									<div class=\"info\">
										<p>
										";
										if($fm == "changes_saved"){
											echo"Changes saved<br />\n";
										}
										if(isset($_GET['fm_image_a'])){
											$fm_image_a = $_GET['fm_image_a'];

											if($fm_image_a == "unknown_file_extension"){
												echo"Image 1: Unknown file extension<br />\n";
											}
											elseif($fm_image_a == "getimagesize_failed"){
												echo"Image 1: Could not get with and height of image<br />\n";
											}
											elseif($fm_image_a == "image_to_big"){
							echo"Image 1: Image file size to big<br />\n";
						}
						elseif($fm_image_a == "to_big_size_in_configuration"){
							echo"Image 1: Image file size to big (in config)<br />\n";
						}
						elseif($fm_image_a == "to_big_size_in_form"){
							echo"Image 1: Image file size to big (in form)<br />\n";
						}
						elseif($fm_image_a == "unknown_error"){
							echo"Image 1: Unknown error<br />\n";
						}

					}
					if(isset($_GET['fm_image_b'])){
						$fm_image_b = $_GET['fm_image_b'];

						if($fm_image_b == "unknown_file_extension"){
							echo"Image 2: Unknown file extension<br />\n";
						}
						elseif($fm_image_b == "getimagesize_failed"){
							echo"Image 2: Could not get with and height of image<br />\n";
						}
						elseif($fm_image_b == "image_to_big"){
							echo"Image 2: Image file size to big<br />\n";
						}
						elseif($fm_image_b == "to_big_size_in_configuration"){
							echo"Image 2: Image file size to big (in config)<br />\n";
						}
						elseif($fm_image_b == "to_big_size_in_form"){
							echo"Image 2: Image file size to big (in form)<br />\n";
						}
						elseif($fm_image_b == "unknown_error"){
							echo"Image 2: Unknown error<br />\n";
						}

					}
					if(isset($_GET['fm_image_c'])){
						$fm_image_c = $_GET['fm_image_c'];

						if($fm_image_c == "unknown_file_extension"){
							echo"Image 3: Unknown file extension<br />\n";
						}
						elseif($fm_image_c == "getimagesize_failed"){
							echo"Image 3: Could not get with and height of image<br />\n";
						}
						elseif($fm_image_c == "image_to_big"){
							echo"Image 3: Image file size to big<br />\n";
						}
						elseif($fm_image_c == "to_big_size_in_configuration"){
							echo"Image 3: Image file size to big (in config)<br />\n";
						}
						elseif($fm_image_c == "to_big_size_in_form"){
							echo"Image 3: Image file size to big (in form)<br />\n";
						}
						elseif($fm_image_c == "unknown_error"){
							echo"Image 3: Unknown error<br />\n";
						}

					}
					echo"
					</p>
				</div>";
			}
			echo"
							<!-- //Feedback -->

		
							<!-- Focus -->
							<script>
								\$(document).ready(function(){
									\$('[name=\"inp_food_name\"]').focus();
								});
							</script>
							<!-- //Focus -->

		<form method=\"post\" action=\"index.php?open=stram&amp;page=food&amp;action=edit_food&amp;main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;food_id=$food_id&amp;language=$language&amp;process=1\" enctype=\"multipart/form-data\">

		<h2>General information</h2>
		<table>
		 <tr>
		  <td style=\"text-align: right;padding: 0px 4px 0px 0px;\">
			<p><b>Name:</b></p>
		  </td>
		  <td>
			<p><input type=\"text\" name=\"inp_food_name\" value=\"$get_food_name\" size=\"40\" /></p>
		  </td>
		 </tr>
		 <tr>
		  <td style=\"text-align: right;padding: 0px 4px 0px 0px;\">
			<p><b>Manufacturer:</b></p>
		  </td>
		  <td>
			<p><input type=\"text\" name=\"inp_food_manufacturer_name\" value=\"$get_food_manufacturer_name\" size=\"40\" /></p>
		  </td>
		 </tr>
		 <tr>
		  <td style=\"text-align: right;padding: 0px 4px 0px 0px;\">
			<p><b>Store:</b></p>
		  </td>
		  <td>
			<p><input type=\"text\" name=\"inp_food_store\" value=\"$get_food_store\" size=\"40\" /></p>
		  </td>
		 </tr>
		 <tr>
		  <td style=\"text-align: right;padding: 0px 4px 0px 0px;\">
			<p><b>Description:</b></p>
		  </td>
		  <td>
			<p><input type=\"text\" name=\"inp_food_description\" value=\"$get_food_description\" size=\"40\" /></p>
		  </td>
		 </tr>
		 <tr>
		  <td style=\"text-align: right;padding: 0px 4px 0px 0px;\">
			<p><b>Barcode:</b></p>
		  </td>
		  <td>
			<p><input type=\"text\" name=\"inp_food_barcode\" value=\"$get_food_barcode\" size=\"40\" /></p>
		  </td>
		 </tr>
		 <tr>
		  <td style=\"text-align: right;padding: 0px 4px 0px 0px;\">
			<p><b>Category:</b></p>
		  </td>
		  <td>
			<p>
			<select name=\"inp_food_category_id\">\n";

			// Get all categories
			$language_mysql = quote_smart($link, $language);
			$query = "SELECT _id, category_id, category_name, category_parent_id FROM $t_diet_categories WHERE category_user_id='0' AND category_parent_id='0' AND category_language=$language_mysql ORDER BY category_name ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_main_id, $get_main_category_id, $get_main_category_name, $get_main_category_parent_id) = $row;

				echo"			";
				echo"<option value=\"\">$get_main_category_name</option>\n";
				
				$queryb = "SELECT _id, category_id, category_name, category_parent_id FROM $t_diet_categories WHERE category_user_id='0' AND category_parent_id='$get_main_category_id' AND category_language=$language_mysql ORDER BY category_name ASC";
				$resultb = mysqli_query($link, $queryb);
				while($rowb = mysqli_fetch_row($resultb)) {
					list($get_sub_id, $get_sub_category_id, $get_sub_category_name, $get_sub_category_parent_id) = $rowb;
				
					echo"			";
					echo"<option value=\"$get_sub_category_id\""; if($get_sub_category_id == "$get_current_sub_category_id"){ echo" selected=\"selected\""; } echo">&nbsp; &nbsp; $get_sub_category_name</option>\n";
				}
				echo"			";
				echo"<option value=\"\"> </option>\n";
			}
			echo"

			</select>
			</p>
		  </td>
		 </tr>
		 <tr>
		  <td style=\"text-align: right;padding: 0px 4px 0px 0px;\">
			<p><b>Serving:</b></p>
		  </td>
		  <td>
			<p><input type=\"text\" name=\"inp_food_serving_size_gram\" value=\"$get_food_serving_size_gram\" size=\"3\" />
			<select name=\"inp_food_serving_size_gram_mesurment\">
				<option value=\"g\""; if($get_food_serving_size_gram_mesurment == "g"){ echo" selected=\"selected\""; } echo">g</option>
				<option value=\"ml\""; if($get_food_serving_size_gram_mesurment == "ml"){ echo" selected=\"selected\""; } echo">ml</option>
			</select>
		  </td>
		 </tr>
		 <tr>
		  <td style=\"text-align: right;padding: 0px 4px 0px 0px;\">
			<p><b>Serving pcs:</b></p>
		  </td>
		  <td>
			<p><input type=\"text\" name=\"inp_food_serving_size_pcs\" value=\"$get_food_serving_size_pcs\" size=\"3\" />
			<select name=\"inp_food_serving_size_gram_mesurment\">\n";

			// Get measurements
			$language_mysql = quote_smart($link, $language);
			$query = "SELECT measurement_name FROM $t_diet_measurements WHERE measurement_language=$language_mysql ORDER BY measurement_name ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_measurement_name) = $row;
				echo"				";
				echo"<option value=\"$get_measurement_name\""; if($get_food_serving_size_pcs_mesurment == "$get_measurement_name"){ echo" selected=\"selected\""; } echo">$get_measurement_name</option>\n";
			}
			echo"
			</select>
		  </td>
		 </tr>
		</table>

		<h2>Numbers</h2>
			<table>
			 <tr>
			  <td style=\"text-align: right;padding: 0px 4px 0px 0px;\">
				
			  </td>
			  <td style=\"padding: 0px 4px 0px 0px;\">
				<p>Energy</p>
			  </td>
			  <td style=\"padding: 0px 4px 0px 0px;\">
				<p>Proteins</p>
			  </td>
			  <td style=\"padding: 0px 4px 0px 0px;\">
				<p>Carbs</p>
			  </td>
			  <td style=\"padding: 0px 4px 0px 0px;\">
				<p>Fat</p>
			  </td>
			 </tr>

			 <tr>
			  <td style=\"text-align: right;padding: 0px 4px 0px 0px;\">
				<p><b>Per 100:</b></p>
			  </td>
			  <td style=\"padding: 0px 4px 0px 0px;\">
				<span><input type=\"text\" name=\"inp_food_energy\" value=\"$get_food_energy\" size=\"3\" /></span>
			  </td>
			  <td style=\"padding: 0px 4px 0px 0px;\">
				<span><input type=\"text\" name=\"inp_food_proteins\" value=\"$get_food_proteins\" size=\"3\" /></span>
			  </td>
			  <td style=\"padding: 0px 4px 0px 0px;\">
				<span><input type=\"text\" name=\"inp_food_carbohydrates\" value=\"$get_food_carbohydrates\" size=\"3\" /></span>
			  </td>
			  <td style=\"padding: 0px 4px 0px 0px;\">
				<span><input type=\"text\" name=\"inp_food_fat\" value=\"$get_food_fat\" size=\"3\" /></span>
			  </td>
			 </tr>

			 <tr>
			  <td style=\"text-align: right;padding: 0px 4px 0px 0px;\">
				<p><b>Per serving:</b></p>
			  </td>
			  <td style=\"padding: 0px 4px 0px 0px;\">
				<span>$get_food_energy_calculated</span>
			  </td>
			  <td style=\"padding: 0px 4px 0px 0px;\">
				<span>$get_food_proteins_calculated</span>
			  </td>
			  <td style=\"padding: 0px 4px 0px 0px;\">
				<span>$get_food_carbohydrates_calculated</span>
			  </td>
			  <td style=\"padding: 0px 4px 0px 0px;\">
				<span>$get_food_fat_calculated</span>
			  </td>
			 </tr>
			</table>


		<h2>Images ($settings_image_width";echo"x"; echo"$settings_image_height)</h2>

			<table>
			 <tr>
			  <td style=\"text-align: right;padding: 0px 4px 0px 0px;vertical-align: top;\">
				<p><b>Product image:</b></p>
			  </td>
			  <td>
				<p>";
				if($get_food_image_a != "" && file_exists("../food/_img/$language/$get_current_main_category_name_clean/$get_current_sub_category_name_clean/$get_food_image_a")){
					echo"<img src=\"../food/_img/$language/$get_current_main_category_name_clean/$get_current_sub_category_name_clean/$get_food_image_a\" alt=\"../food/_img/$language/$get_current_main_category_name_clean/$get_current_sub_category_name_clean/$get_food_image_a\" width=\"500\" height=\"530\" />";
				}
				else{
					echo"<i>$get_food_image_a</i>";
				}
				echo"	</p>
			  </td>
			 </tr>
			 <tr>
			  <td style=\"text-align: right;padding: 0px 4px 0px 0px;\">
				
			  </td>
			  <td>
    				<p>
				<input type=\"file\" name=\"inp_food_image_a\" />
				</p>
			  </td>
			 </tr>
			 <tr>
			  <td style=\"text-align: right;padding: 10px 4px 0px 0px;vertical-align: top;\">
				<p><b>Food table image:</b></p>
			  </td>
			  <td style=\"padding: 10px 0px 0px 0px;\">
				<p>
				";
				if($get_food_image_b != "" && file_exists("../food/_img/$language/$get_current_main_category_name_clean/$get_current_sub_category_name_clean/$get_food_image_b")){
					echo"<img src=\"../food/_img/$language/$get_current_main_category_name_clean/$get_current_sub_category_name_clean/$get_food_image_b\" alt=\"../food/_img/$language/$get_current_main_category_name_clean/$get_current_sub_category_name_clean/$get_food_image_b\" width=\"500\" height=\"530\" />";
				}
				echo"</p>
			  </td>
			 </tr>
			 <tr>
			  <td style=\"text-align: right;padding: 0px 4px 0px 0px;\">
				
			  </td>
			  <td>
    				<p>
				<input type=\"file\" name=\"inp_food_image_b\" />
				</p>
			  </td>
			 </tr>
			 <tr>
			  <td style=\"text-align: right;padding: 10px 4px 0px 0px;vertical-align: top;\">
				<p><b>Inspiration image:</b></p>
			  </td>
			  <td style=\"padding: 10px 0px 0px 0px;\">
				<p>
				";
				if($get_food_image_c != "" && file_exists("../food/_img/$language/$get_current_main_category_name_clean/$get_current_sub_category_name_clean/$get_food_image_c")){
					echo"<img src=\"../food/_img/$language/$get_current_main_category_name_clean/$get_current_sub_category_name_clean/$get_food_image_c\" alt=\"../food/_img/$language/$get_current_main_category_name_clean/$get_current_sub_category_name_clean/$get_food_image_c\" width=\"500\" height=\"530\" />";
				}
				echo"</p>
			  </td>
			 </tr>
			 <tr>
			  <td style=\"text-align: right;padding: 0px 4px 0px 0px;\">
				
			  </td>
			  <td>
    				<p>
				<input type=\"file\" name=\"inp_food_image_c\" />
				</p>
			  </td>
			 </tr>
			</table>
		<p>
		<input type=\"submit\" value=\"Save changes\" class=\"btn btn-success btn-sm\" />
		</p>
		
		</form>
		";

						} // food found
					} // edit_food
					elseif($action == "delete_food" && isset($_GET['food_id'])){
	
						// Get variables
						$food_id = $_GET['food_id'];
						$food_id = strip_tags(stripslashes($food_id));
						$food_id_mysql = quote_smart($link, $food_id);

						$language_mysql = quote_smart($link, $language);

						// Select food
						$query = "SELECT _id, food_id, food_user_id, food_name, food_manufacturer_name, food_store, food_description, food_serving_size_gram, food_serving_size_gram_mesurment, food_serving_size_pcs, food_serving_size_pcs_mesurment, food_energy, food_proteins, food_carbohydrates, food_fat, food_energy_calculated, food_proteins_calculated, food_carbohydrates_calculated, food_fat_calculated, food_barcode, food_category_id, food_thumb, food_image_a, food_image_b, food_image_c, food_last_used, food_language, food_synchronized, food_notes FROM $t_diet_food WHERE food_id=$food_id_mysql AND food_language=$language_mysql";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get__id, $get_food_id, $get_food_user_id, $get_food_name, $get_food_manufacturer_name, $get_food_store, $get_food_description, $get_food_serving_size_gram, $get_food_serving_size_gram_mesurment, $get_food_serving_size_pcs, $get_food_serving_size_pcs_mesurment, $get_food_energy, $get_food_proteins, $get_food_carbohydrates, $get_food_fat, $get_food_energy_calculated, $get_food_proteins_calculated, $get_food_carbohydrates_calculated, $get_food_fat_calculated, $get_food_barcode, $get_food_category_id, $get_food_thumb, $get_food_image_a, $get_food_image_b, $get_food_image_c, $get_food_last_used, $get_food_language, $get_food_synchronized, $get_food_notes) = $row;

						if($get__id == ""){
							echo"
							<h1>Food not found</h1>

							<p>
							Sorry, the food was not found.
							</p>

							<p>
							<a href=\"index.php?open=stram&amp;page=food\">Back</a>
							</p>
							";
						}
						else{
							// Get sub category
							$query = "SELECT category_id, category_user_id, category_name, category_parent_id FROM $t_diet_categories WHERE category_id=$get_food_category_id AND category_language=$language_mysql";
							$result = mysqli_query($link, $query);
							$row = mysqli_fetch_row($result);
							list($get_current_sub_category_id, $get_current_sub_category_user_id, $get_current_sub_category_name, $get_current_sub_category_parent_id) = $row;

							// Get main category
							$query = "SELECT category_id, category_user_id, category_name, category_parent_id FROM $t_diet_categories WHERE category_id=$get_current_sub_category_parent_id AND category_language=$language_mysql";
							$result = mysqli_query($link, $query);
							$row = mysqli_fetch_row($result);
							list($get_current_main_category_id, $get_current_main_category_user_id, $get_current_main_category_name, $get_current_main_category_parent_id) = $row;
		
							// Process
							if($process == "1"){
			// Delete
			$result = mysqli_query($link, "DELETE FROM $t_diet_food WHERE _id='$get__id'");

			// Delete images
			if($get_food_thumb != "" && file_exists("../food/_img/$language/$get_current_main_category_name_clean/$get_current_sub_category_name_clean/$get_food_thumb")){
				unlink("../food/_img/$language/$get_current_main_category_name_clean/$get_current_sub_category_name_clean/$get_food_thumb");
			}
			if($get_food_image_a != "" && file_exists("../food/_img/$language/$get_current_main_category_name_clean/$get_current_sub_category_name_clean/$get_food_image_a")){
				unlink("../food/_img/$language/$get_current_main_category_name_clean/$get_current_sub_category_name_clean/$get_food_image_a");
			}
			if($get_food_image_b != "" && file_exists("../food/_img/$language/$get_current_main_category_name_clean/$get_current_sub_category_name_clean/$get_food_image_b")){
				unlink("../food/_img/$language/$get_current_main_category_name_clean/$get_current_sub_category_name_clean/$get_food_image_b");
			}
			if($get_food_image_c != "" && file_exists("../food/_img/$language/$get_current_main_category_name_clean/$get_current_sub_category_name_clean/$get_food_image_c")){
				unlink("../food/_img/$language/$get_current_main_category_name_clean/$get_current_sub_category_name_clean/$get_food_image_c");
			}

			// Feedback
			$url = "index.php?open=stram&page=food&action=open_sub_category&main_category_id=$get_current_main_category_id&sub_category_id=$get_current_sub_category_id&language=$language&ft=success&fm=food_deleted#&food_id$food_id";
			

			header("Location: $url");
			exit;

		}

		echo"


		<p>
		Are you sure you want to delete the food?
		</p>

		<p>
		<a href=\"index.php?open=stram&amp;page=food&amp;action=open_sub_category&amp;main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;language=$language#food_id$food_id\">Go back</a>
		|
		<a href=\"index.php?open=stram&amp;page=food&amp;action=delete_food&amp;main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;food_id=$food_id&amp;language=$language&amp;process=1\">Delete</a>
		</p>
		";

						} // food found
					}

					echo"

				<!-- //Right -->
			</div>
		";

	} // food found


} // view_food
elseif($action == "new_food"){


	if(isset($_GET['step'])){
		$step = $_GET['step'];
		$step = strip_tags(stripslashes($step));
	}
	else{
		$step = "";
	}


	if($step == ""){
		echo"
		<h1>New food</h1>

		<p>
		Please select a main category.
		</p>

		<!-- Main categories -->
			<ul>
			";

			// Get all categories
			$language_mysql = quote_smart($link, $language);
			$query = "SELECT _id, category_id, category_name, category_parent_id FROM $t_diet_categories WHERE category_user_id='0' AND category_parent_id='0' AND category_language=$language_mysql ORDER BY category_name ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_main_id, $get_main_category_id, $get_main_category_name, $get_main_category_parent_id) = $row;
				
				echo"
				<li><span><a href=\"index.php?open=stram&amp;page=food&amp;action=new_food&amp;step=select_sub_category&amp;main_category_id=$get_main_category_id&amp;language=$language\">$get_main_category_name</a></span></li>
				";
			}
			echo"
			</ul>
		<!-- //Main categories -->

		<p>
		<a href=\"index.php?open=stram&amp;page=food&amp;language=$language\"><img src=\"_design/gfx/icons/16x16/go-previous.png\" alt=\"go-previous.png\" /></a>
		<a href=\"index.php?open=stram&amp;page=food&amp;language=$language\">Go back</a>
		</p>

		";
	} // select main category
	elseif($step == "select_sub_category"){
		// Variables
		$language_mysql = quote_smart($link, $language);

		// Find main category
		$main_category_id = $_GET['main_category_id'];
		$main_category_id = strip_tags(stripslashes($main_category_id));
		$main_category_id_mysql = quote_smart($link, $main_category_id);
		$query = "SELECT category_id, category_user_id, category_name, category_parent_id FROM $t_diet_categories WHERE category_id=$main_category_id_mysql AND category_language=$language_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_main_category_id, $get_current_main_category_user_id, $get_current_main_category_name, $get_current_main_category_parent_id) = $row;
			
		if($get_current_main_category_id == ""){
			echo"<p>Main category not found.</p>";
		}
		else{
			echo"
			<h1>New food</h1>

			<p>
			Please select a sub category.
			</p>

			<!-- Sub categories -->
				<ul>
				<li><span><a href=\"index.php?open=stram&amp;page=food&amp;action=new_food&amp;language=$language\">View all categories</a></span></li>

				";

				// Get all categories
				$language_mysql = quote_smart($link, $language);
				$query = "SELECT _id, category_id, category_name, category_parent_id FROM $t_diet_categories WHERE category_user_id='0' AND category_parent_id=$main_category_id_mysql AND category_language=$language_mysql ORDER BY category_name ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_sub_id, $get_sub_category_id, $get_sub_category_name, $get_sub_category_parent_id) = $row;
				
					echo"
					<li><span><a href=\"index.php?open=stram&amp;page=food&amp;action=new_food&amp;step=general_information&amp;main_category_id=$main_category_id&amp;sub_category_id=$get_sub_category_id&amp;language=$language\">$get_sub_category_name</a></span></li>
					";
				}
				echo"
				</ul>
			<!-- //Sub categories -->


			<p>
			<a href=\"index.php?open=stram&amp;page=food&amp;language=$language\"><img src=\"_design/gfx/icons/16x16/go-previous.png\" alt=\"go-previous.png\" /></a>
			<a href=\"index.php?open=stram&amp;page=food&amp;language=$language\">Go back</a>
			</p>

			";
		}
	} // select sub category
	elseif($step == "general_information"){
		// Variables
		$language_mysql = quote_smart($link, $language);

		// Find main category
		$main_category_id = $_GET['main_category_id'];
		$main_category_id = strip_tags(stripslashes($main_category_id));
		$main_category_id_mysql = quote_smart($link, $main_category_id);
		$query = "SELECT category_id, category_user_id, category_name, category_parent_id FROM $t_diet_categories WHERE category_id=$main_category_id_mysql AND category_language=$language_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_main_category_id, $get_current_main_category_user_id, $get_current_main_category_name, $get_current_main_category_parent_id) = $row;

		if($get_current_main_category_id == ""){
			echo"<p>Main category not found.</p>";
		}
		else{

			// Find sub category
			$sub_category_id = $_GET['sub_category_id'];
			$sub_category_id = strip_tags(stripslashes($sub_category_id));
			$sub_category_id_mysql = quote_smart($link, $sub_category_id);
			$query = "SELECT category_id, category_user_id, category_name, category_parent_id FROM $t_diet_categories WHERE category_id=$sub_category_id_mysql AND category_language=$language_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_sub_category_id, $get_current_sub_category_user_id, $get_current_sub_category_name, $get_current_sub_category_parent_id) = $row;
	
			if($get_current_sub_category_id == ""){
				echo"<p>Sub category not found.</p>";
			}
			else{
				if($process == "1"){
					$inp_food_name = $_POST['inp_food_name'];
					$inp_food_name = output_html($inp_food_name);
					$inp_food_name_mysql = quote_smart($link, $inp_food_name);
					if(empty($inp_food_name)){
						$ft = "error";
						$fm = "missing_name";
					}

					$inp_food_manufacturer_name = $_POST['inp_food_manufacturer_name'];
					$inp_food_manufacturer_name = output_html($inp_food_manufacturer_name);
					$inp_food_manufacturer_name_mysql = quote_smart($link, $inp_food_manufacturer_name);

					$inp_food_store = $_POST['inp_food_store'];
					$inp_food_store = output_html($inp_food_store);
					$inp_food_store_mysql = quote_smart($link, $inp_food_store);

					$inp_food_description = $_POST['inp_food_description'];
					$inp_food_description = output_html($inp_food_description);
					$inp_food_description_mysql = quote_smart($link, $inp_food_description);

					$inp_food_barcode = $_POST['inp_food_barcode'];
					$inp_food_barcode = output_html($inp_food_barcode);
					$inp_food_barcode_mysql = quote_smart($link, $inp_food_barcode);
	
					$inp_food_serving_size_gram = $_POST['inp_food_serving_size_gram'];
					$inp_food_serving_size_gram = output_html($inp_food_serving_size_gram);
					$inp_food_serving_size_gram = str_replace(",", ".", $inp_food_serving_size_gram);
					$inp_food_serving_size_gram_mysql = quote_smart($link, $inp_food_serving_size_gram);
					if(empty($inp_food_serving_size_gram)){
						$ft = "error";
						$fm = "missing_serving_size_gram";
					}

					$inp_food_serving_size_gram_mesurment = $_POST['inp_food_serving_size_gram_mesurment'];
					$inp_food_serving_size_gram_mesurment = output_html($inp_food_serving_size_gram_mesurment);
					$inp_food_serving_size_gram_mesurment_mysql = quote_smart($link, $inp_food_serving_size_gram_mesurment);
					if(empty($inp_food_serving_size_gram_mesurment)){
						$ft = "error";
						$fm = "missing_serving_size_gram_mesurment";
					}

					$inp_food_serving_size_pcs = $_POST['inp_food_serving_size_pcs'];
					$inp_food_serving_size_pcs = output_html($inp_food_serving_size_pcs);
					$inp_food_serving_size_pcs = str_replace(",", ".", $inp_food_serving_size_pcs);
					$inp_food_serving_size_pcs_mysql = quote_smart($link, $inp_food_serving_size_pcs);
					if(empty($inp_food_serving_size_pcs)){
						$ft = "error";
						$fm = "missing_serving_size_pcs";
					}

					$inp_food_serving_size_pcs_mesurment = $_POST['inp_food_serving_size_pcs_mesurment'];
					$inp_food_serving_size_pcs_mesurment = output_html($inp_food_serving_size_pcs_mesurment);
					$inp_food_serving_size_pcs_mesurment_mysql = quote_smart($link, $inp_food_serving_size_pcs_mesurment);
					if(empty($inp_food_serving_size_pcs_mesurment)){
						$ft = "error";
						$fm = "missing_serving_size_pcs_mesurment";
					}


					// Food path
					$get_current_main_category_name_clean = clean($get_current_main_category_name);
					$get_current_sub_category_name_clean = clean($get_current_sub_category_name);
					$inp_food_image_path = "food/_img/$language/$get_current_main_category_name_clean/$get_current_sub_category_name_clean";
					$inp_food_image_path_mysql = quote_smart($link, $inp_food_image_path);


					if($ft == ""){
						// Datetime (notes)
						$datetime = date("Y-m-d H:i:s");
						$inp_notes = "Started on $datetime";
						$inp_notes_mysql = quote_smart($link, $inp_notes);
						
						mysqli_query($link, "INSERT INTO $t_diet_food
						(_id, food_user_id, food_name, food_manufacturer_name, food_store, food_description, food_serving_size_gram, food_serving_size_gram_mesurment, food_serving_size_pcs, food_serving_size_pcs_mesurment, food_barcode, food_category_id, food_image_path, food_language, food_notes) 
						VALUES 
						(NULL, '0', $inp_food_name_mysql, $inp_food_manufacturer_name_mysql, $inp_food_store_mysql, $inp_food_description_mysql, $inp_food_serving_size_gram_mysql, $inp_food_serving_size_gram_mesurment_mysql, $inp_food_serving_size_pcs_mysql, $inp_food_serving_size_pcs_mesurment_mysql, $inp_food_barcode_mysql, '$get_current_sub_category_id', $inp_food_image_path_mysql, $language_mysql, $inp_notes_mysql)")
						or die(mysqli_error($link));

						// Get _id
						$query = "SELECT _id FROM $t_diet_food WHERE food_notes=$inp_notes_mysql";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get__id) = $row;
	
						// Update food_id
						$result = mysqli_query($link, "UPDATE $t_diet_food SET food_id='$get__id' WHERE _id='$get__id'");


						$url = "index.php?open=stram&page=food&action=new_food&step=numbers&main_category_id=$main_category_id&sub_category_id=$sub_category_id&food_id=$get__id&language=$language";
						header("Location: $url");
						exit;
					}
					else{
						$url = "index.php?open=stram&page=food&action=new_food&step=general_information&main_category_id=$main_category_id&sub_category_id=$sub_category_id&language=$language";
						$url = $url . "&ft=$ft&fm=$fm";
						$url = $url . "&inp_food_name=$inp_food_name";
						$url = $url . "&inp_food_manufacturer_name=$inp_food_manufacturer_name";
						$url = $url . "&inp_food_store=$inp_food_store";
						$url = $url . "&inp_food_description=$inp_food_description";
						$url = $url . "&inp_food_barcode=$inp_food_barcode";
						$url = $url . "&inp_food_serving_size_gram=$inp_food_serving_size_gram";
						$url = $url . "&inp_food_serving_size_gram_mesurment=$inp_food_serving_size_gram_mesurment";
						$url = $url . "&inp_food_serving_size_pcs=$inp_food_serving_size_pcs";
						$url = $url . "&inp_food_serving_size_pcs_mesurment=$inp_food_serving_size_pcs_mesurment";

						header("Location: $url");
						exit;
					}
				}			


	
				echo"
				<h1>New food</h1>

				<!-- Feedback -->
					";
					if($ft != "" && $fm != ""){
						if($fm == "missing_name"){
							$fm = "Please enter a name";
						}
						elseif($fm == "missing_serving_size_gram"){
							$fm = "Please enter serving (field 1)";
						}
						elseif($fm == "missing_serving_size_gram_mesurment"){
							$fm = "Please enter serving (field 2)";
						}
						elseif($fm == "missing_serving_size_pcs"){
							$fm = "Please enter serving pcs (field 1)";
						}
						elseif($fm == "missing_serving_size_pcs_mesurment"){
							$fm = "Please enter serving pcs (field 2)";
						}
						else{
							$fm = ucfirst($fm);
						}
						echo"<div class=\"$ft\"><p>$fm</p></div>";	
					}
					echo"
				<!-- //Feedback -->

				<!-- General information -->
					<!-- Focus -->
					<script>
						\$(document).ready(function(){
							\$('[name=\"inp_food_name\"]').focus();
						});
					</script>
					<!-- //Focus -->

					<form method=\"post\" action=\"index.php?open=stram&amp;page=food&amp;action=new_food&amp;step=general_information&amp;main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;language=$language&amp;process=1\" enctype=\"multipart/form-data\">

					<h2>General information</h2>
					<table>
					 <tr>
					  <td style=\"text-align: right;padding: 0px 4px 0px 0px;\">
						<p><b>Name:</b></p>
					  </td>
					  <td>
						<p><input type=\"text\" name=\"inp_food_name\" value=\"\" size=\"40\" /></p>
					  </td>
					 </tr>
					 <tr>
					  <td style=\"text-align: right;padding: 0px 4px 0px 0px;\">
						<p><b>Manufacturer:</b></p>
					  </td>
					  <td>
						<p><input type=\"text\" name=\"inp_food_manufacturer_name\" value=\"\" size=\"40\" /></p>
					  </td>
					 </tr>
					 <tr>
					  <td style=\"text-align: right;padding: 0px 4px 0px 0px;\">
						<p><b>Store:</b></p>
					  </td>
					  <td>
						<p><input type=\"text\" name=\"inp_food_store\" value=\"\" size=\"40\" /></p>
					  </td>
					 </tr>
					 <tr>
					  <td style=\"text-align: right;padding: 0px 4px 0px 0px;\">
						<p><b>Description:</b></p>
					  </td>
					  <td>
						<p><input type=\"text\" name=\"inp_food_description\" value=\"\" size=\"40\" /></p>
					  </td>
					 </tr>
					 <tr>
					  <td style=\"text-align: right;padding: 0px 4px 0px 0px;\">
						<p><b>Barcode:</b></p>
					  </td>
					  <td>
						<p><input type=\"text\" name=\"inp_food_barcode\" value=\"\" size=\"40\" /></p>
					  </td>
					 </tr>
					 <tr>
					  <td style=\"text-align: right;padding: 0px 4px 0px 0px;vertical-align:top;\">
						<p><b>Serving:</b></p>
					  </td>
					  <td>
						<p><input type=\"text\" name=\"inp_food_serving_size_gram\" value=\"\" size=\"3\" />
						
						<select name=\"inp_food_serving_size_gram_mesurment\">
							<option value=\"g\""; if($get_food_serving_size_gram_mesurment == "g"){ echo" selected=\"selected\""; } echo">g</option>
							<option value=\"ml\""; if($get_food_serving_size_gram_mesurment == "ml"){ echo" selected=\"selected\""; } echo">ml</option>
						</select><br />
						<span class=\"smal\">Examples: 72 g, 90 ml</span>
						</p>
					  </td>
					 </tr>
					 <tr>
					  <td style=\"text-align: right;padding: 0px 4px 0px 0px;vertical-align:top;\">
						<p><b>Serving pcs:</b></p>
					  </td>
					  <td>
						<p><input type=\"text\" name=\"inp_food_serving_size_pcs\" value=\"\" size=\"3\" />
						<select name=\"inp_food_serving_size_gram_mesurment\">\n";

						// Get measurements
						$language_mysql = quote_smart($link, $language);
						$query = "SELECT measurement_name FROM $t_diet_measurements WHERE measurement_language=$language_mysql ORDER BY measurement_name ASC";
						$result = mysqli_query($link, $query);
						while($row = mysqli_fetch_row($result)) {
							list($get_measurement_name) = $row;
							echo"				";
							echo"<option value=\"$get_measurement_name\""; if($get_food_serving_size_pcs_mesurment == "$get_measurement_name"){ echo" selected=\"selected\""; } echo">$get_measurement_name</option>\n";
						}
						echo"
						</select><br />
						<span class=\"smal\">Examples: 1 package, 1 slice, 1 pcs, 1 plate</span>
						</p>
					  </td>
					 </tr>
					 <tr>
					  <td style=\"text-align: right;padding: 0px 4px 0px 0px;\">
						
					  </td>
					  <td>
						<p><input type=\"submit\" value=\"Save\" class=\"btn btn-success btn-sm\" /></p>
					  </td>
					 </tr>
					</table>
				<!-- //General information -->


				<p>
				<a href=\"index.php?open=stram&amp;page=food&amp;language=$language\"><img src=\"_design/gfx/icons/16x16/go-previous.png\" alt=\"go-previous.png\" /></a>
				<a href=\"index.php?open=stram&amp;page=food&amp;language=$language\">Go back</a>
				</p>
				";
			} // sub category found
		} // main category found
	} // general information
	elseif($step == "numbers"){
		// Variables
		$language_mysql = quote_smart($link, $language);
		
		$food_id = $_GET['food_id'];
		$food_id = strip_tags(stripslashes($food_id));
		$food_id_mysql = quote_smart($link, $food_id);

		// Find food
		$query = "SELECT _id, food_id, food_user_id, food_name, food_manufacturer_name, food_store, food_description, food_serving_size_gram, food_serving_size_gram_mesurment, food_serving_size_pcs, food_serving_size_pcs_mesurment, food_energy, food_proteins, food_carbohydrates, food_fat, food_energy_calculated, food_proteins_calculated, food_carbohydrates_calculated, food_fat_calculated, food_barcode, food_category_id, food_thumb, food_image_a, food_image_b, food_image_c, food_last_used, food_language, food_synchronized, food_notes FROM $t_diet_food WHERE food_id=$food_id_mysql AND food_language=$language_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get__id, $get_food_id, $get_food_user_id, $get_food_name, $get_food_manufacturer_name, $get_food_store, $get_food_description, $get_food_serving_size_gram, $get_food_serving_size_gram_mesurment, $get_food_serving_size_pcs, $get_food_serving_size_pcs_mesurment, $get_food_energy, $get_food_proteins, $get_food_carbohydrates, $get_food_fat, $get_food_energy_calculated, $get_food_proteins_calculated, $get_food_carbohydrates_calculated, $get_food_fat_calculated, $get_food_barcode, $get_food_category_id, $get_food_thumb, $get_food_image_a, $get_food_image_b, $get_food_image_c, $get_food_last_used, $get_food_language, $get_food_synchronized, $get_food_notes) = $row;

		if($get__id == ""){
			echo"
			<h1>Food not found</h1>

			<p>
			Sorry, the food was not found.
			</p>

			<p>
			<a href=\"index.php?open=stram&amp;page=food\">Back</a>
			</p>
			";
		}
		else{
			// Get sub category
			$query = "SELECT category_id, category_user_id, category_name, category_parent_id FROM $t_diet_categories WHERE category_id=$get_food_category_id AND category_language=$language_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_sub_category_id, $get_current_sub_category_user_id, $get_current_sub_category_name, $get_current_sub_category_parent_id) = $row;

			// Get main category
			$query = "SELECT category_id, category_user_id, category_name, category_parent_id FROM $t_diet_categories WHERE category_id=$get_current_sub_category_parent_id AND category_language=$language_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_main_category_id, $get_current_main_category_user_id, $get_current_main_category_name, $get_current_main_category_parent_id) = $row;

			// Process
			if($process == "1"){
				// per 100 
				$inp_food_energy = $_POST['inp_food_energy'];
				$inp_food_energy = output_html($inp_food_energy);
				$inp_food_energy = str_replace(",", ".", $inp_food_energy);
				$inp_food_energy_mysql = quote_smart($link, $inp_food_energy);
				if($inp_food_energy == ""){
					$ft = "error";
					$fm = "mising_energy";
				}

				$inp_food_proteins = $_POST['inp_food_proteins'];
				$inp_food_proteins = output_html($inp_food_proteins);
				$inp_food_proteins = str_replace(",", ".", $inp_food_proteins);
				$inp_food_proteins_mysql = quote_smart($link, $inp_food_proteins);
				if($inp_food_proteins == ""){
					$ft = "error";
					$fm = "mising_proteins";
				}

	
				$inp_food_carbohydrates = $_POST['inp_food_carbohydrates'];
				$inp_food_carbohydrates = output_html($inp_food_carbohydrates);
				$inp_food_carbohydrates = str_replace(",", ".", $inp_food_carbohydrates);
				$inp_food_carbohydrates_mysql = quote_smart($link, $inp_food_carbohydrates);
				if($inp_food_carbohydrates == ""){
					$ft = "error";
					$fm = "mising_carbohydrates";
				}


				$inp_food_fat = $_POST['inp_food_fat'];
				$inp_food_fat = output_html($inp_food_fat);
				$inp_food_fat = str_replace(",", ".", $inp_food_fat);
				$inp_food_fat_mysql = quote_smart($link, $inp_food_fat);
				if($inp_food_fat == ""){
					$ft = "error";
					$fm = "mising_fat";
				}


				// Calculated
				$inp_food_energy_calculated = round($inp_food_energy*$get_food_serving_size_gram/100, 0);
				$inp_food_proteins_calculated = round($inp_food_proteins*$get_food_serving_size_gram/100, 0);
				$inp_food_carbohydrates_calculated = round($inp_food_carbohydrates*$get_food_serving_size_gram/100, 0);
				$inp_food_fat_calculated = round($inp_food_fat*$get_food_serving_size_gram/100, 0);
		

				if($ft == ""){	
					// Update
					$result = mysqli_query($link, "UPDATE $t_diet_food SET food_energy=$inp_food_energy_mysql, food_proteins=$inp_food_proteins_mysql, food_carbohydrates=$inp_food_carbohydrates_mysql, food_fat=$inp_food_fat_mysql, food_energy_calculated='$inp_food_energy_calculated', food_proteins_calculated='$inp_food_proteins_calculated', food_carbohydrates_calculated='$inp_food_carbohydrates_calculated', food_fat_calculated='$inp_food_fat_calculated' WHERE _id='$get__id'");

					$url = "index.php?open=stram&page=food&action=new_food&step=images&main_category_id=$main_category_id&sub_category_id=$sub_category_id&food_id=$get_food_id&language=$language";
					header("Location: $url");
					exit;
				}
				else{
					$url = "index.php?open=stram&page=food&action=new_food&step=numbers&main_category_id=$main_category_id&sub_category_id=$sub_category_id&food_id=$get_food_id&language=$language";
					$url = $url . "&ft=$ft&fm=$fm";
					$url = $url . "&inp_food_energy=$inp_food_energy";
					$url = $url . "&inp_food_proteins=$inp_food_proteins";
					$url = $url . "&inp_food_carbohydrates=$inp_food_carbohydrates";
					$url = $url . "&inp_food_fat=$inp_food_fat";

					header("Location: $url");
					exit;
				}
			}


			echo"
			<h1>New food</h1>

			<!-- Feedback -->
				";
				if($ft != "" && $fm != ""){
					if($fm == "missing_energy"){
						$fm = "Please enter energy";
					}
					elseif($fm == "missing_proteins"){
						$fm = "Please enter proteins";
					}
					elseif($fm == "missing_carbohydrates"){
						$fm = "Please enter carbohydrates";
					}
					elseif($fm == "missing_fat"){
						$fm = "Please enter fat";
					}
					else{
						$fm = ucfirst($fm);
					}
					echo"<div class=\"$ft\"><p>$fm</p></div>";	
				}
				echo"
			<!-- //Feedback -->

			<!-- Numbers -->
				<!-- Focus -->
				<script>
					\$(document).ready(function(){
						\$('[name=\"inp_food_energy\"]').focus();
					});
				</script>
				<!-- //Focus -->

				<form method=\"post\" action=\"index.php?open=stram&amp;page=food&amp;action=new_food&amp;step=numbers&amp;main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;food_id=$food_id&amp;language=$language&amp;process=1\" enctype=\"multipart/form-data\">
				<h2>Numbers</h2>

				<table>
				 <tr>
				  <td style=\"text-align: right;padding: 0px 4px 0px 0px;\">
					<p>Kcal pr 100:</p>
				  </td>
				  <td>
					<p><input type=\"text\" name=\"inp_food_energy\" value=\"$get_food_energy\" size=\"3\" /></p>
				  </td>
				 </tr>
				 <tr>
				  <td style=\"text-align: right;padding: 0px 4px 0px 0px;\">
					<p>Proteins pr 100:</p>
				  </td>
				  <td>
					<p><input type=\"text\" name=\"inp_food_proteins\" value=\"$get_food_proteins\" size=\"3\" /></p>
				  </td>
				 </tr>
				 <tr>
				  <td style=\"text-align: right;padding: 0px 4px 0px 0px;\">
					<p>Carbs pr 100:</p>
				  </td>
				  <td>
					<p><input type=\"text\" name=\"inp_food_carbohydrates\" value=\"$get_food_carbohydrates\" size=\"3\" /></p>
				  </td>
				 </tr>
				 <tr>
				  <td style=\"text-align: right;padding: 0px 4px 0px 0px;\">
					<p>Fat pr 100:</p>
				  </td>
				  <td>
					<p><input type=\"text\" name=\"inp_food_fat\" value=\"$get_food_fat\" size=\"3\" /></p>
				  </td>
				 </tr>
				 <tr>
				  <td style=\"text-align: right;padding: 0px 4px 0px 0px;\">
					
				  </td>
				  <td>
					<p><input type=\"submit\" value=\"Save\" class=\"btn btn-success btn-sm\" /></p>
				  </td>
				 </tr>
				</table>
			<!-- //Numbers -->


			<p>
			<a href=\"index.php?open=stram&amp;page=food&amp;language=$language\"><img src=\"_design/gfx/icons/16x16/go-previous.png\" alt=\"go-previous.png\" /></a>
			<a href=\"index.php?open=stram&amp;page=food&amp;language=$language\">Go back</a>
			</p>
			";

		}
	} // numbers
	elseif($step == "images"){
		// Variables
		$language_mysql = quote_smart($link, $language);
		
		$food_id = $_GET['food_id'];
		$food_id = strip_tags(stripslashes($food_id));
		$food_id_mysql = quote_smart($link, $food_id);

		// Find food
		$query = "SELECT _id, food_id, food_user_id, food_name, food_manufacturer_name, food_store, food_description, food_serving_size_gram, food_serving_size_gram_mesurment, food_serving_size_pcs, food_serving_size_pcs_mesurment, food_energy, food_proteins, food_carbohydrates, food_fat, food_energy_calculated, food_proteins_calculated, food_carbohydrates_calculated, food_fat_calculated, food_barcode, food_category_id, food_thumb, food_image_a, food_image_b, food_image_c, food_last_used, food_language, food_synchronized, food_notes FROM $t_diet_food WHERE food_id=$food_id_mysql AND food_language=$language_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get__id, $get_food_id, $get_food_user_id, $get_food_name, $get_food_manufacturer_name, $get_food_store, $get_food_description, $get_food_serving_size_gram, $get_food_serving_size_gram_mesurment, $get_food_serving_size_pcs, $get_food_serving_size_pcs_mesurment, $get_food_energy, $get_food_proteins, $get_food_carbohydrates, $get_food_fat, $get_food_energy_calculated, $get_food_proteins_calculated, $get_food_carbohydrates_calculated, $get_food_fat_calculated, $get_food_barcode, $get_food_category_id, $get_food_thumb, $get_food_image_a, $get_food_image_b, $get_food_image_c, $get_food_last_used, $get_food_language, $get_food_synchronized, $get_food_notes) = $row;

		if($get__id == ""){
			echo"
			<h1>Food not found</h1>

			<p>
			Sorry, the food was not found.
			</p>

			<p>
			<a href=\"index.php?open=stram&amp;page=food\">Back</a>
			</p>
			";
		}
		else{
			// Get sub category
			$query = "SELECT category_id, category_user_id, category_name, category_parent_id FROM $t_diet_categories WHERE category_id=$get_food_category_id AND category_language=$language_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_sub_category_id, $get_current_sub_category_user_id, $get_current_sub_category_name, $get_current_sub_category_parent_id) = $row;

			// Get main category
			$query = "SELECT category_id, category_user_id, category_name, category_parent_id FROM $t_diet_categories WHERE category_id=$get_current_sub_category_parent_id AND category_language=$language_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_main_category_id, $get_current_main_category_user_id, $get_current_main_category_name, $get_current_main_category_parent_id) = $row;

			// Directory for storing
			$get_current_main_category_name_clean = clean($get_current_main_category_name);
			$get_current_sub_category_name_clean = clean($get_current_sub_category_name);
			
			// Clean name
			$food_name_clean = clean($get_food_name);

			// Process
			if($process == "1"){
				
				/*- Image A ------------------------------------------------------------------------------------------ */
				// $tmp_name = $_FILES['inp_food_image_a']['tmp_name'];
				$name = stripslashes($_FILES['inp_food_image_a']['name']);
				$extension = getExtension($name);
				$extension = strtolower($extension);

				if($name){
					if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
						$ft_image_a = "warning";
						$fm_image_a = "unknown_file_extension";
					}
					else{
 
						// Give new name
						$new_name = $food_name_clean . "_a.png";
						$new_path = "../food/_img/$language/$get_current_main_category_name_clean/$get_current_sub_category_name_clean/";
						$uploaded_file = $new_path . $new_name;

						// Upload file
						if (move_uploaded_file($_FILES['inp_food_image_a']['tmp_name'], $uploaded_file)) {


							// Get image size
							$file_size = filesize($uploaded_file);
						
 
							// Check with and height
							list($width,$height) = getimagesize($uploaded_file);
	
							if($width == "" OR $height == ""){
								$ft_image_a = "warning";
								$fm_image_a = "getimagesize_failed";
							}
							else{

								// Orientation
								if ($width > $height) {
									// Landscape
								} else {
									// Portrait or Square
								}


								// Resize to 700x742
								$newwidth=$settings_image_width;
								$newheight=($height/$width)*$newwidth;
								$tmp=imagecreatetruecolor($newwidth,$newheight);
						
								if($extension=="jpg" || $extension=="jpeg" ){
									$src = imagecreatefromjpeg($uploaded_file);
								}
								else if($extension=="png"){
									$src = imagecreatefrompng($uploaded_file);
								}
								else{
									$src = imagecreatefromgif($uploaded_file);
								}
	
								imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight, $width,$height);
								imagepng($tmp,$uploaded_file);
								imagedestroy($tmp);
						

								// Make thumb 132x140
								$thumb_name = $food_name_clean . "_thumb.png";
								$thumb_file = $new_path . $thumb_name;

								$thumb_with = 132;
								$thumb_height = ($height/$width)*$thumb_with;
								$tmp=imagecreatetruecolor($thumb_with,$thumb_height);


								imagecopyresampled($tmp,$src,0,0,0,0,$thumb_with,$thumb_height, $width,$height);

								imagepng($tmp,$thumb_file);

								imagedestroy($tmp);
							

								// Update MySQL
								$inp_food_thumb_mysql = quote_smart($link, $thumb_name);
								$inp_food_image_a_mysql = quote_smart($link, $new_name);
								$result = mysqli_query($link, "UPDATE $t_diet_food SET food_thumb=$inp_food_thumb_mysql, food_image_a=$inp_food_image_a_mysql WHERE _id='$get__id'");


							}  // if($width == "" OR $height == ""){
						} // move_uploaded_file
						else{
							switch ($_FILES['inp_food_image_a']['error']) {
								case UPLOAD_ERR_OK:
           								$fm_image_a = "image_to_big";
									break;
								case UPLOAD_ERR_NO_FILE:
           								// $fm_image_a = "no_file_uploaded";
									break;
								case UPLOAD_ERR_INI_SIZE:
           								$fm_image_a = "to_big_size_in_configuration";
									break;
								case UPLOAD_ERR_FORM_SIZE:
           								$fm_image_a = "to_big_size_in_form";
									break;
								default:
           								$fm_image_a = "unknown_error";
									break;

							}	
						}
	
					} // extension check
				} // if($image){


				/*- Image B ------------------------------------------------------------------------------------------ */
				// $tmp_name = $_FILES['inp_food_image_b']['tmp_name'];
				$name = stripslashes($_FILES['inp_food_image_b']['name']);
				$extension = getExtension($name);
				$extension = strtolower($extension);

				if($name){
					if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
						$ft_image_a = "warning";
						$fm_image_a = "unknown_file_extension";
					}
					else{
 
						// Give new name
						$new_name = $food_name_clean . "_b.png";
						$new_path = "../food/_img/$language/$get_current_main_category_name_clean/$get_current_sub_category_name_clean/";
						$uploaded_file = $new_path . $new_name;

						// Upload file
						if (move_uploaded_file($_FILES['inp_food_image_b']['tmp_name'], $uploaded_file)) {


							// Get image size
							$file_size = filesize($uploaded_file);
 
							// Check with and height
							list($width,$height) = getimagesize($uploaded_file);
	
							if($width == "" OR $height == ""){
								$ft_image_b = "warning";
								$fm_image_b = "getimagesize_failed";
							}
							else{

								// Orientation
								if ($width > $height) {
								// Landscape
								} else {
									// Portrait or Square
								}


								// Resize to 700x742
								$newwidth=$settings_image_width;
								$newheight=($height/$width)*$newwidth;
								$tmp=imagecreatetruecolor($newwidth,$newheight);
						
								if($extension=="jpg" || $extension=="jpeg" ){
									$src = imagecreatefromjpeg($uploaded_file);
								}
								else if($extension=="png"){
									$src = imagecreatefrompng($uploaded_file);
								}
								else{
									$src = imagecreatefromgif($uploaded_file);
								}

								imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight, $width,$height);
	


								imagepng($tmp,$uploaded_file);

								imagedestroy($tmp);
						
								// Update MySQL
								$inp_food_image_b_mysql = quote_smart($link, $new_name);
								$result = mysqli_query($link, "UPDATE $t_diet_food SET food_image_b=$inp_food_image_b_mysql WHERE _id='$get__id'");


							}  // if($width == "" OR $height == ""){
						} // move_uploaded_file
						else{
							switch ($_FILES['inp_food_image_b']['error']) {
								case UPLOAD_ERR_OK:
           								$fm_image_b = "image_to_big";
									break;
								case UPLOAD_ERR_NO_FILE:
           								// $fm_image_b = "no_file_uploaded";
									break;
								case UPLOAD_ERR_INI_SIZE:
           								$fm_image_b = "to_big_size_in_configuration";
									break;
								case UPLOAD_ERR_FORM_SIZE:
           								$fm_image_b = "to_big_size_in_form";
									break;
								default:
           								$fm_image_b = "unknown_error";
									break;
							}	
						}
					} // extension check
				} // if($image){
			

				/*- Image C ------------------------------------------------------------------------------------------ */
				// $tmp_name = $_FILES['inp_food_image_c']['tmp_name'];
				$name = stripslashes($_FILES['inp_food_image_c']['name']);
				$extension = getExtension($name);
				$extension = strtolower($extension);
	
				if($name){
					if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
						$ft_image_c = "warning";
						$fm_image_c = "unknown_file_extension";
					}
					else{
						// Give new name
						$new_name = $food_name_clean . "_c.png";
						$new_path = "../food/_img/$language/$get_current_main_category_name_clean/$get_current_sub_category_name_clean/";
						$uploaded_file = $new_path . $new_name;

						// Upload file
						if (move_uploaded_file($_FILES['inp_food_image_c']['tmp_name'], $uploaded_file)) {


							// Get image size
							$file_size = filesize($uploaded_file);
 
							// Check with and height
							list($width,$height) = getimagesize($uploaded_file);
	
							if($width == "" OR $height == ""){
								$ft_image_c = "warning";
								$fm_image_c = "getimagesize_failed";
							}
							else{

								// Orientation
								if ($width > $height) {
									// Landscape
								} else {
									// Portrait or Square
								}


								// Resize to 700x742
								$newwidth=$settings_image_width;
								$newheight=($height/$width)*$newwidth;
								$tmp=imagecreatetruecolor($newwidth,$newheight);
						
								if($extension=="jpg" || $extension=="jpeg" ){
									$src = imagecreatefromjpeg($uploaded_file);
								}
								else if($extension=="png"){
									$src = imagecreatefrompng($uploaded_file);
								}
								else{
									$src = imagecreatefromgif($uploaded_file);
								}

								imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight, $width,$height);
	
								imagepng($tmp,$uploaded_file);

								imagedestroy($tmp);
						
								// Update MySQL
								$inp_food_image_c_mysql = quote_smart($link, $new_name);
								$result = mysqli_query($link, "UPDATE $t_diet_food SET food_image_c=$inp_food_image_c_mysql WHERE _id='$get__id'");

							}  // if($width == "" OR $height == ""){
						} // move_uploaded_file
						else{
							switch ($_FILES['inp_food_image_c']['error']) {
								case UPLOAD_ERR_OK:
           								$fm_image_c = "image_to_big";
									break;
								case UPLOAD_ERR_NO_FILE:
           								// $fm_image_c = "no_file_uploaded";
									break;
								case UPLOAD_ERR_INI_SIZE:
           								$fm_image_c = "to_big_size_in_configuration";
									break;
								case UPLOAD_ERR_FORM_SIZE:
           								$fm_image_c = "to_big_size_in_form";
									break;
								default:
           								$fm_image_c = "unknown_error";
									break;

							}	
						}
	
					} // extension check
				} // if($image){



				// Feedback
				if(isset($fm_image_a) OR isset($fm_image_b) OR isset($fm_image_c)){
					// Feedback with error

					$url = "index.php?open=stram&page=food&action=new_food&step=images&main_category_id=$get_current_main_category_id&sub_category_id=$get_current_sub_category_id&food_id=$food_id&language=$language";
					if(isset($fm_image_a)){
						$url = $url . "&fm_image_a=$fm_image_a";
					}
					if(isset($fm_image_b)){
						$url = $url . "&fm_image_b=$fm_image_c";
					}
					if(isset($fm_image_c)){
						$url = $url . "&fm_image_c=$fm_image_c";
					}
					header("Location: $url");
					exit;
				}
				else{
					// Feedback without error
					$url = "index.php?open=stram&page=food&action=view_food&step=images&main_category_id=$main_category_id&sub_category_id=$sub_category_id&food_id=$food_id&language=$language&ft=success&fm=changes_saved";


					header("Location: $url");
					exit;
				}


				
			} // process


			echo"
			<h1>New food</h1>

			<!-- Feedback -->
			";
			if(isset($_GET['fm_image_a']) OR isset($_GET['fm_image_b']) OR isset($_GET['fm_image_c'])){
				echo"
				<div class=\"info\">
					<p>
					";
					if(isset($_GET['fm_image_a'])){
						$fm_image_a = $_GET['fm_image_a'];

						if($fm_image_a == "unknown_file_extension"){
							echo"Product image: Unknown file extension<br />\n";
						}
						elseif($fm_image_a == "getimagesize_failed"){
							echo"Product image: Could not get with and height of image<br />\n";
						}
						elseif($fm_image_a == "image_to_big"){
							echo"Product image: Image file size to big<br />\n";
						}
						elseif($fm_image_a == "to_big_size_in_configuration"){
							echo"Product image: Image file size to big (in config)<br />\n";
						}
						elseif($fm_image_a == "to_big_size_in_form"){
							echo"Product image: Image file size to big (in form)<br />\n";
						}
						elseif($fm_image_a == "unknown_error"){
							echo"Product image: Unknown error<br />\n";
						}

					}
					if(isset($_GET['fm_image_b'])){
						$fm_image_b = $_GET['fm_image_b'];

						if($fm_image_b == "unknown_file_extension"){
							echo"Food table image: Unknown file extension<br />\n";
						}
						elseif($fm_image_b == "getimagesize_failed"){
							echo"Food table image: Could not get with and height of image<br />\n";
						}
						elseif($fm_image_b == "image_to_big"){
							echo"Food table image: Image file size to big<br />\n";
						}
						elseif($fm_image_b == "to_big_size_in_configuration"){
							echo"Food table image: Image file size to big (in config)<br />\n";
						}
						elseif($fm_image_b == "to_big_size_in_form"){
							echo"Food table image: Image file size to big (in form)<br />\n";
						}
						elseif($fm_image_b == "unknown_error"){
							echo"Food table image: Unknown error<br />\n";
						}

					}
					if(isset($_GET['fm_image_c'])){
						$fm_image_c = $_GET['fm_image_c'];

						if($fm_image_c == "unknown_file_extension"){
							echo"Inspiration image: Unknown file extension<br />\n";
						}
						elseif($fm_image_c == "getimagesize_failed"){
							echo"Inspiration image: Could not get with and height of image<br />\n";
						}
						elseif($fm_image_c == "image_to_big"){
							echo"Inspiration image: Image file size to big<br />\n";
						}
						elseif($fm_image_c == "to_big_size_in_configuration"){
							echo"Inspiration image: Image file size to big (in config)<br />\n";
						}
						elseif($fm_image_c == "to_big_size_in_form"){
							echo"Inspiration image: Image file size to big (in form)<br />\n";
						}
						elseif($fm_image_c == "unknown_error"){
							echo"Inspiration image: Unknown error<br />\n";
						}

					}
					echo"
					</p>
				</div>";
			}
			echo"
			<!-- //Feedback -->

			<!-- Images -->

				<h2>Images ($settings_image_width";echo"x"; echo"$settings_image_height)</h2>

				<form method=\"post\" action=\"index.php?open=stram&amp;page=food&amp;action=new_food&amp;step=images&amp;main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;food_id=$food_id&amp;language=$language&amp;process=1\" enctype=\"multipart/form-data\">
				
				<table>";
				if($get_food_image_a == ""){
					echo"
					 <tr>
					  <td style=\"text-align: right;padding: 0px 4px 0px 0px;vertical-align: top;\">
						<p><b>Product image:</b></p>
					  </td>
					  <td>
    						<p>
						<input type=\"file\" name=\"inp_food_image_a\" />
						</p>
					  </td>
					 </tr>
					";
				}
				if($get_food_image_b == ""){
					echo"
					 <tr>
					  <td style=\"text-align: right;padding: 10px 4px 0px 0px;vertical-align: top;\">
						<p><b>Food table image:</b></p>
					  </td>
					  <td>
    						<p>
						<input type=\"file\" name=\"inp_food_image_b\" />
						</p>
					  </td>
					 </tr>
					";
				}
				if($get_food_image_c == ""){
					echo"
					 <tr>
					  <td style=\"text-align: right;padding: 10px 4px 0px 0px;vertical-align: top;\">
						<p><b>Inspiration image:</b></p>
					  </td>
					  <td>
    						<p>
						<input type=\"file\" name=\"inp_food_image_c\" />
						</p>
					  </td>
					 </tr>
					";
				}
				echo"
				 <tr>
				  <td style=\"text-align: right;padding: 10px 4px 0px 0px;vertical-align: top;\">
					
				  </td>
				  <td>
					<p>
					<input type=\"submit\" value=\"Upload images\"  class=\"btn btn-success btn-sm\"  />
					<input type=\"submit\" value=\"Skip this step\"  class=\"btn btn-sm\"  />
					</p>
				  </td>
				 </tr>
				</table>

			<!-- //Images -->


			<p>
			<a href=\"index.php?open=stram&amp;page=food&amp;language=$language\"><img src=\"_design/gfx/icons/16x16/go-previous.png\" alt=\"go-previous.png\" /></a>
			<a href=\"index.php?open=stram&amp;page=food&amp;language=$language\">Go back</a>
			</p>
			";

		}
	} // numbers
	else{
		echo"<p>Unknown step.</p>";
	}
}
?>