<?php

/*- Current page ----------------------------------------------------------------------- */
$self 		= $_SERVER['PHP_SELF'];
$request_url 	= $_SERVER["REQUEST_URI"];
$self_array     = explode("/", $self);
$array_size     = sizeof($self_array);

$minus_one	= $array_size-1;
$minus_one	= $self_array[$minus_one];

$minus_two	= $array_size-2;
$minus_two	= $self_array[$minus_two];

$complex	= $minus_two . "/" . $minus_one;



/*- Tables ---------------------------------------------------------------------------- */
include("_tables_food.php");

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/food/ts_index.php");


/*- Special main mode ------------------------------------------------------------------ */
if(!(isset($include_as_navigation_main_mode))){
	$include_as_navigation_main_mode = 0;
}

/*- Variables -------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);

if(isset($_GET['main_category_id'])){
	$main_category_id= $_GET['main_category_id'];
	$main_category_id = strip_tags(stripslashes($main_category_id));
}
else{
	$main_category_id = "";
}
if(isset($_GET['sub_category_id'])){
	$sub_category_id= $_GET['sub_category_id'];
	$sub_category_id = strip_tags(stripslashes($sub_category_id));
}
else{
	$sub_category_id = "";
}


if($include_as_navigation_main_mode == 0){
	echo"
	<ul class=\"toc\">
		<li class=\"header_home\"><a href=\"$root/food/index.php?l=$l\""; if($minus_one == "index.php" && $minus_two == "android"){ echo" class=\"navigation_active\"";}echo">$l_food</a></li>

	";
}


	// Get all categories
	$query = "SELECT $t_food_categories.category_id, $t_food_categories.category_name, $t_food_categories.category_parent_id FROM $t_food_categories";
	$query = $query . " WHERE category_user_id='0' AND category_parent_id='0' ORDER BY category_name ASC";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_category_id, $get_category_name, $get_category_parent_id) = $row;

		// Translation
		$query_t = "SELECT category_translation_value FROM $t_food_categories_translations WHERE category_id=$get_category_id AND category_translation_language=$l_mysql";
		$result_t = mysqli_query($link, $query_t);
		$row_t = mysqli_fetch_row($result_t);
		list($get_category_translation_value) = $row_t;

		echo"						";
		echo"<li><a href=\"$root/food/open_main_category.php?main_category_id=$get_category_id&amp;l=$l\""; if($main_category_id == "$get_category_id"){ echo" class=\"navigation_active\"";}echo">$get_category_translation_value</a></li>\n";


		if($main_category_id == "$get_category_id"){


			// Get sub categories
			$queryb = "SELECT category_id, category_name, category_parent_id FROM $t_food_categories WHERE category_user_id='0' AND category_parent_id='$get_category_id' ORDER BY category_name ASC";
			$resultb = mysqli_query($link, $queryb);
			while($rowb = mysqli_fetch_row($resultb)) {
				list($get_sub_category_id, $get_sub_category_name, $get_sub_category_parent_id) = $rowb;

				// Translation
				$query_t = "SELECT category_translation_value FROM $t_food_categories_translations WHERE category_id=$get_sub_category_id AND category_translation_language=$l_mysql";
				$result_t = mysqli_query($link, $query_t);
				$row_t = mysqli_fetch_row($result_t);
				list($get_sub_category_translation_value) = $row_t;

				$get_sub_category_translation_value_len = strlen($get_sub_category_translation_value);
				if($get_sub_category_translation_value_len > 19){
					$get_sub_category_translation_value = substr($get_sub_category_translation_value, 0,18);
					$check_for_tag = substr($get_sub_category_translation_value, 0,17);
					if($check_for_tag == "&"){
						$get_sub_category_translation_value = substr($get_sub_category_translation_value, 0,19);
					}
					$get_sub_category_translation_value = $get_sub_category_translation_value . "...";
				}

				echo"						";
				echo"<li><a href=\"$root/food/open_sub_category.php?main_category_id=$main_category_id&amp;sub_category_id=$get_sub_category_id&amp;l=$l\""; if($sub_category_id == "$get_sub_category_id"){ echo" class=\"navigation_active\"";}echo" style=\"margin-left: 20px;\">$get_sub_category_translation_value</a></li>\n";


			}
		}

	}
	echo"
							<li class=\"header_up\"><a href=\"$root/food/views.php?l=$l\""; if($minus_one == "views.php"){ echo" class=\"navigation_active\"";}echo">$l_views</a></li>
							<li><a href=\"$root/food/food_without_image.php?l=$l\""; if($minus_one == "food_without_image.php"){ echo" class=\"navigation_active\"";}echo">$l_without_image</a></li>


							<li class=\"header_up\"><a href=\"$root/food/user_pages.php?l=$l\""; if($minus_one == "index.php" && $minus_two == "android"){ echo" class=\"navigation_active\"";}echo">$l_user_pages</a></li>
							<li><a href=\"$root/food/new_food.php?l=$l\""; if($minus_one == "new_food.php"){ echo" class=\"navigation_active\"";}echo">$l_new_food</a></li>
							<li><a href=\"$root/food/my_food.php?l=$l\""; if($minus_one == "my_food.php"){ echo" class=\"navigation_active\"";}echo">$l_my_food</a></li>
							<li><a href=\"$root/food/my_favorites.php?l=$l\""; if($minus_one == "my_favorites.php"){ echo" class=\"navigation_active\"";}echo">$l_my_favorites</a></li>
							<li><a href=\"$root/food/my_stores.php?l=$l\""; if($minus_one == "my_stores.php"){ echo" class=\"navigation_active\"";}echo">$l_my_stores</a></li>

";

if($include_as_navigation_main_mode == 0){
	echo"
	</ul>
	\n";
}
?>