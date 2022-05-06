<?php
/**
*
* File: _admin/_inc/recipes/_liquidbase_db_scripts/recipes_main_ingredients.php
* Version 1.0.0
* Date 20:56 10.02.2022
* Copyright (c) 2022 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

mysqli_query($link, "DROP TABLE IF EXISTS $t_recipes_main_ingredients") or die(mysqli_error($link));


echo"



	<!-- recipes_main_ingredients -->
	";
	$query = "SELECT * FROM $t_recipes_main_ingredients";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_recipes_main_ingredients: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_recipes_main_ingredients(
	  	ingredient_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(ingredient_id), 
	  	   ingredient_title VARCHAR(200), 
	  	   ingredient_title_clean VARCHAR(200), 
	  	   ingredient_icon_path VARCHAR(200), 
	  	   ingredient_icon_18x18_inactive VARCHAR(200), 
	  	   ingredient_icon_18x18_active VARCHAR(200), 
	  	   ingredient_icon_24x24_inactive VARCHAR(200), 
	  	   ingredient_icon_24x24_active VARCHAR(200), 
	  	   ingredient_category_id INT, 
	  	   ingredient_category_name VARCHAR(200), 
	  	   ingredient_unique_hits INT, 
	  	   ingredient_unique_hits_ipblock TEXT, 
	  	   ingredient_updated_datetime DATETIME,
	  	   ingredient_updated_by_user_id INT
		)")
		   or die(mysqli_error());


		$main_ingredients = array(
  array('ingredient_id' => '1','ingredient_title' => 'Chicken','ingredient_title_clean' => 'chicken','ingredient_icon_path' => '_uploads/recipes/main_ingredients','ingredient_icon_18x18_inactive' => 'chicken_18x18_inactive.png','ingredient_icon_18x18_active' => 'chicken_18x18_active.png','ingredient_icon_24x24_inactive' => 'chicken_24x24_inactive.png','ingredient_icon_24x24_active' => 'chicken_24x24_active.png','ingredient_category_id' => '2','ingredient_category_name' => 'Dinner','ingredient_unique_hits' => '16','ingredient_unique_hits_ipblock' => '114.119.141.35
54.36.148.47
114.119.141.34
185.191.171.12
185.191.171.23
157.90.181.222
54.36.148.72
185.191.171.37
185.191.171.24
114.119.141.4
54.36.148.35','ingredient_updated_datetime' => '2022-02-12 21:13:03','ingredient_updated_by_user_id' => '1'),
  array('ingredient_id' => '2','ingredient_title' => 'Cod','ingredient_title_clean' => 'cod','ingredient_icon_path' => '_uploads/recipes/main_ingredients','ingredient_icon_18x18_inactive' => 'cod_18x18_inactive.png','ingredient_icon_18x18_active' => 'cod_18x18_active.png','ingredient_icon_24x24_inactive' => 'cod_24x24_inactive.png','ingredient_icon_24x24_active' => 'cod_24x24_active.png','ingredient_category_id' => '2','ingredient_category_name' => 'Dinner','ingredient_unique_hits' => '21','ingredient_unique_hits_ipblock' => '2a02:2121:284:4d97:0:58:b06e:5401
54.36.148.246
185.191.171.22
185.191.171.34
185.191.171.5
114.119.141.35
185.191.171.42
114.119.141.4
54.36.148.217
185.191.171.20','ingredient_updated_datetime' => '2022-02-12 21:19:59','ingredient_updated_by_user_id' => '1'),
  array('ingredient_id' => '3','ingredient_title' => 'Egg','ingredient_title_clean' => 'egg','ingredient_icon_path' => '_uploads/recipes/main_ingredients','ingredient_icon_18x18_inactive' => 'egg_18x18_inactive.png','ingredient_icon_18x18_active' => 'egg_18x18_active.png','ingredient_icon_24x24_inactive' => 'egg_24x24_inactive.png','ingredient_icon_24x24_active' => 'egg_24x24_active.png','ingredient_category_id' => '2','ingredient_category_name' => 'Dinner','ingredient_unique_hits' => '10','ingredient_unique_hits_ipblock' => '54.36.148.12
185.191.171.35
185.191.171.42
54.36.149.103
185.191.171.7
185.191.171.18
54.36.149.18
185.191.171.36
157.90.181.222
45.88.116.68
','ingredient_updated_datetime' => '2022-02-12 21:24:40','ingredient_updated_by_user_id' => '1'),
  array('ingredient_id' => '4','ingredient_title' => 'Egg','ingredient_title_clean' => 'egg','ingredient_icon_path' => '_uploads/recipes/main_ingredients','ingredient_icon_18x18_inactive' => 'egg_18x18_inactive.png','ingredient_icon_18x18_active' => 'egg_18x18_active.png','ingredient_icon_24x24_inactive' => 'egg_24x24_inactive.png','ingredient_icon_24x24_active' => 'egg_24x24_active.png','ingredient_category_id' => '1','ingredient_category_name' => 'Breakfast','ingredient_unique_hits' => '17','ingredient_unique_hits_ipblock' => '114.119.141.4
185.191.171.20
114.119.141.30
114.119.141.27
157.90.181.222
185.191.171.37','ingredient_updated_datetime' => '2022-02-12 21:25:10','ingredient_updated_by_user_id' => '1'),
  array('ingredient_id' => '5','ingredient_title' => 'Vegetarian','ingredient_title_clean' => 'vegetarian','ingredient_icon_path' => '_uploads/recipes/main_ingredients','ingredient_icon_18x18_inactive' => 'vegan_18x18_inactive.png','ingredient_icon_18x18_active' => 'vegan_18x18_active.png','ingredient_icon_24x24_inactive' => 'vegan_24x24_inactive.png','ingredient_icon_24x24_active' => 'vegan_24x24_active.png','ingredient_category_id' => '1','ingredient_category_name' => 'Breakfast','ingredient_unique_hits' => '12','ingredient_unique_hits_ipblock' => '114.119.141.35
185.191.171.43
185.191.171.3
185.191.171.1
185.191.171.33
185.191.171.20
185.191.171.11','ingredient_updated_datetime' => '2022-02-13 13:30:41','ingredient_updated_by_user_id' => '1'),
  array('ingredient_id' => '6','ingredient_title' => 'Vegetarian','ingredient_title_clean' => 'vegetarian','ingredient_icon_path' => '_uploads/recipes/main_ingredients','ingredient_icon_18x18_inactive' => 'vegan_18x18_inactive.png','ingredient_icon_18x18_active' => 'vegan_18x18_active.png','ingredient_icon_24x24_inactive' => 'vegan_24x24_inactive.png','ingredient_icon_24x24_active' => 'vegan_24x24_active.png','ingredient_category_id' => '3','ingredient_category_name' => 'Snacks','ingredient_unique_hits' => '18','ingredient_unique_hits_ipblock' => '54.36.149.98
185.191.171.2
185.191.171.21
185.191.171.3
185.191.171.4
157.90.181.222
54.36.149.76','ingredient_updated_datetime' => '2022-02-13 13:30:44','ingredient_updated_by_user_id' => '1'),
  array('ingredient_id' => '7','ingredient_title' => 'Fruit','ingredient_title_clean' => 'fruit','ingredient_icon_path' => '_uploads/recipes/main_ingredients','ingredient_icon_18x18_inactive' => 'fruit_18x18_inactive.png','ingredient_icon_18x18_active' => 'fruit_18x18_active.png','ingredient_icon_24x24_inactive' => 'fruit_24x24_inactive.png','ingredient_icon_24x24_active' => 'fruit_24x24_active.png','ingredient_category_id' => '4','ingredient_category_name' => 'Dessert','ingredient_unique_hits' => '5','ingredient_unique_hits_ipblock' => '185.191.171.35
185.191.171.40
185.191.171.10
185.191.171.25
157.90.181.222
','ingredient_updated_datetime' => '2022-02-12 21:54:13','ingredient_updated_by_user_id' => '1'),
  array('ingredient_id' => '8','ingredient_title' => 'Trout','ingredient_title_clean' => 'trout','ingredient_icon_path' => '_uploads/recipes/main_ingredients','ingredient_icon_18x18_inactive' => 'trout_18x18_inactive.png','ingredient_icon_18x18_active' => 'trout_18x18_active.png','ingredient_icon_24x24_inactive' => 'trout_24x24_inactive.png','ingredient_icon_24x24_active' => 'trout_24x24_active.png','ingredient_category_id' => '2','ingredient_category_name' => 'Dinner','ingredient_unique_hits' => '18','ingredient_unique_hits_ipblock' => '114.119.141.27
54.36.149.5
185.191.171.40
185.191.171.3
185.191.171.44
157.90.181.222
54.36.148.166','ingredient_updated_datetime' => '2022-02-12 21:57:22','ingredient_updated_by_user_id' => '1'),
  array('ingredient_id' => '9','ingredient_title' => 'Vegetarian','ingredient_title_clean' => 'vegetarian','ingredient_icon_path' => '_uploads/recipes/main_ingredients','ingredient_icon_18x18_inactive' => 'vegan_18x18_inactive.png','ingredient_icon_18x18_active' => 'vegan_18x18_active.png','ingredient_icon_24x24_inactive' => 'vegan_24x24_inactive.png','ingredient_icon_24x24_active' => 'vegan_24x24_active.png','ingredient_category_id' => '5','ingredient_category_name' => 'Sides','ingredient_unique_hits' => '9','ingredient_unique_hits_ipblock' => '185.191.171.5
54.36.148.156
114.119.141.249
114.119.141.34
54.36.149.9
185.191.171.10
185.191.171.16
185.191.171.2
157.90.181.222
','ingredient_updated_datetime' => '2022-02-13 13:30:47','ingredient_updated_by_user_id' => '1'),
  array('ingredient_id' => '10','ingredient_title' => 'Extra lean ground beef','ingredient_title_clean' => 'extra_lean_ground_beef','ingredient_icon_path' => '_uploads/recipes/main_ingredients','ingredient_icon_18x18_inactive' => 'minced_meat_18x18_inactive.png','ingredient_icon_18x18_active' => 'minced_meat_18x18_active.png','ingredient_icon_24x24_inactive' => 'minced_meat_24x24_inactive.png','ingredient_icon_24x24_active' => 'minced_meat_24x24_active.png','ingredient_category_id' => '2','ingredient_category_name' => 'Dinner','ingredient_unique_hits' => '21','ingredient_unique_hits_ipblock' => '185.191.171.9
185.191.171.21
54.36.148.254
185.191.171.12
114.119.141.24
114.119.141.30
185.191.171.39
185.191.171.40
185.191.171.36
157.90.181.222','ingredient_updated_datetime' => '2022-02-13 17:31:20','ingredient_updated_by_user_id' => '1'),
  array('ingredient_id' => '11','ingredient_title' => 'Chicken mince','ingredient_title_clean' => 'chicken_mince','ingredient_icon_path' => '_uploads/recipes/main_ingredients','ingredient_icon_18x18_inactive' => 'chicken_mince_18x18_inactive.png','ingredient_icon_18x18_active' => 'chicken_mince_18x18_active.png','ingredient_icon_24x24_inactive' => 'chicken_mince_24x24_inactive.png','ingredient_icon_24x24_active' => 'chicken_mince_24x24_active.png','ingredient_category_id' => '2','ingredient_category_name' => 'Dinner','ingredient_unique_hits' => '14','ingredient_unique_hits_ipblock' => '54.36.148.237
185.191.171.23
157.90.181.222
185.191.171.24
185.191.171.39
54.36.149.0
185.191.171.43
185.191.171.38
185.191.171.26','ingredient_updated_datetime' => '2022-02-12 22:26:09','ingredient_updated_by_user_id' => '1'),
  array('ingredient_id' => '12','ingredient_title' => 'Lamb','ingredient_title_clean' => 'lamb','ingredient_icon_path' => '_uploads/recipes/main_ingredients','ingredient_icon_18x18_inactive' => 'lamb_18x18_inactive.png','ingredient_icon_18x18_active' => 'lamb_18x18_active.png','ingredient_icon_24x24_inactive' => 'lamb_24x24_inactive.png','ingredient_icon_24x24_active' => 'lamb_24x24_active.png','ingredient_category_id' => '2','ingredient_category_name' => 'Dinner','ingredient_unique_hits' => '13','ingredient_unique_hits_ipblock' => '185.191.171.42
185.191.171.39
185.191.171.17
54.36.148.167
185.191.171.1
185.191.171.24
54.36.148.187
185.191.171.4','ingredient_updated_datetime' => '2022-02-12 22:57:49','ingredient_updated_by_user_id' => '1'),
  array('ingredient_id' => '13','ingredient_title' => 'Chocolade','ingredient_title_clean' => 'chocolade','ingredient_icon_path' => '_uploads/recipes/main_ingredients','ingredient_icon_18x18_inactive' => 'chocolade_18x18_inactive.png','ingredient_icon_18x18_active' => 'chocolade_18x18_active.png','ingredient_icon_24x24_inactive' => 'chocolade_24x24_inactive.png','ingredient_icon_24x24_active' => 'chocolade_24x24_active.png','ingredient_category_id' => '4','ingredient_category_name' => 'Dessert','ingredient_unique_hits' => '7','ingredient_unique_hits_ipblock' => '114.119.141.27
185.191.171.22
185.191.171.16
185.191.171.43
185.191.171.40
185.191.171.18
157.90.181.222
','ingredient_updated_datetime' => '2022-02-13 09:12:17','ingredient_updated_by_user_id' => '1'),
  array('ingredient_id' => '14','ingredient_title' => 'Beef','ingredient_title_clean' => 'beef','ingredient_icon_path' => '_uploads/recipes/main_ingredients','ingredient_icon_18x18_inactive' => 'beef_18x18_inactive.png','ingredient_icon_18x18_active' => 'beef_18x18_active.png','ingredient_icon_24x24_inactive' => 'beef_24x24_inactive.png','ingredient_icon_24x24_active' => 'beef_24x24_active.png','ingredient_category_id' => '2','ingredient_category_name' => 'Dinner','ingredient_unique_hits' => '16','ingredient_unique_hits_ipblock' => '185.191.171.17
185.191.171.22
54.36.148.38
185.191.171.10
185.191.171.19
157.90.181.222
185.191.171.37
54.36.148.215
185.191.171.3
185.191.171.38
185.191.171.39','ingredient_updated_datetime' => '2022-02-13 09:15:07','ingredient_updated_by_user_id' => '1'),
  array('ingredient_id' => '15','ingredient_title' => 'Protein powder','ingredient_title_clean' => 'protein_powder','ingredient_icon_path' => '_uploads/recipes/main_ingredients','ingredient_icon_18x18_inactive' => 'protein_powder_18x18_inactive.png','ingredient_icon_18x18_active' => 'protein_powder_18x18_active.png','ingredient_icon_24x24_inactive' => 'protein_powder_24x24_inactive.png','ingredient_icon_24x24_active' => 'protein_powder_24x24_active.png','ingredient_category_id' => '3','ingredient_category_name' => 'Snacks','ingredient_unique_hits' => '12','ingredient_unique_hits_ipblock' => '54.36.148.145
185.191.171.15
185.191.171.2
185.191.171.13
185.191.171.35
54.36.148.102
54.36.148.41','ingredient_updated_datetime' => '2022-02-13 13:28:32','ingredient_updated_by_user_id' => '1'),
  array('ingredient_id' => '16','ingredient_title' => 'Vegetarian','ingredient_title_clean' => 'vegetarian','ingredient_icon_path' => '_uploads/recipes/main_ingredients','ingredient_icon_18x18_inactive' => 'vegan_18x18_inactive.png','ingredient_icon_18x18_active' => 'vegan_18x18_active.png','ingredient_icon_24x24_inactive' => 'vegan_24x24_inactive.png','ingredient_icon_24x24_active' => 'vegan_24x24_active.png','ingredient_category_id' => '2','ingredient_category_name' => 'Dinner','ingredient_unique_hits' => '22','ingredient_unique_hits_ipblock' => '185.191.171.13
114.119.141.34
54.36.148.22
185.191.171.16
185.191.171.18
185.191.171.26
114.119.141.27
54.36.148.41
185.191.171.4
114.119.141.249
157.90.181.222','ingredient_updated_datetime' => '2022-02-13 13:30:38','ingredient_updated_by_user_id' => '1'),
  array('ingredient_id' => '17','ingredient_title' => 'Chicken','ingredient_title_clean' => 'chicken','ingredient_icon_path' => '_uploads/recipes/main_ingredients','ingredient_icon_18x18_inactive' => 'chicken_18x18_inactive.png','ingredient_icon_18x18_active' => 'chicken_18x18_active.png','ingredient_icon_24x24_inactive' => 'chicken_24x24_inactive.png','ingredient_icon_24x24_active' => 'chicken_24x24_active.png','ingredient_category_id' => '1','ingredient_category_name' => 'Breakfast','ingredient_unique_hits' => '14','ingredient_unique_hits_ipblock' => '185.191.171.19
185.191.171.34
185.191.171.11
157.90.181.222
185.191.171.6
185.191.171.43
185.191.171.4
185.191.171.13
185.191.171.14','ingredient_updated_datetime' => '2022-02-13 13:32:10','ingredient_updated_by_user_id' => '1'),
  array('ingredient_id' => '18','ingredient_title' => 'Beef','ingredient_title_clean' => 'beef','ingredient_icon_path' => '_uploads/recipes/main_ingredients','ingredient_icon_18x18_inactive' => 'beef_18x18_inactive.png','ingredient_icon_18x18_active' => 'beef_18x18_active.png','ingredient_icon_24x24_inactive' => 'beef_24x24_inactive.png','ingredient_icon_24x24_active' => 'beef_24x24_active.png','ingredient_category_id' => '1','ingredient_category_name' => 'Breakfast','ingredient_unique_hits' => '15','ingredient_unique_hits_ipblock' => '185.191.171.19
185.191.171.23
185.191.171.20
185.191.171.16
157.90.181.222
185.191.171.11
185.191.171.38
185.191.171.5
185.191.171.43
185.191.171.24','ingredient_updated_datetime' => '2022-02-13 13:35:04','ingredient_updated_by_user_id' => '1'),
  array('ingredient_id' => '19','ingredient_title' => 'Protein powder','ingredient_title_clean' => 'protein_powder','ingredient_icon_path' => '_uploads/recipes/main_ingredients','ingredient_icon_18x18_inactive' => 'protein_powder_18x18_inactive.png','ingredient_icon_18x18_active' => 'protein_powder_18x18_active.png','ingredient_icon_24x24_inactive' => 'protein_powder_24x24_inactive.png','ingredient_icon_24x24_active' => 'protein_powder_24x24_active.png','ingredient_category_id' => '1','ingredient_category_name' => 'Breakfast','ingredient_unique_hits' => '19','ingredient_unique_hits_ipblock' => '114.119.141.30
185.191.171.14
185.191.171.10
185.191.171.7
114.119.141.35
114.119.141.24
114.119.141.34
157.90.181.222','ingredient_updated_datetime' => '2022-02-13 13:36:06','ingredient_updated_by_user_id' => '1'),
  array('ingredient_id' => '20','ingredient_title' => 'Salmon','ingredient_title_clean' => 'salmon','ingredient_icon_path' => '_uploads/recipes/main_ingredients','ingredient_icon_18x18_inactive' => 'salmon_18x18_inactive.png','ingredient_icon_18x18_active' => 'salmon_18x18_active.png','ingredient_icon_24x24_inactive' => 'salmon_24x24_inactive.png','ingredient_icon_24x24_active' => 'salmon_24x24_active.png','ingredient_category_id' => '2','ingredient_category_name' => 'Dinner','ingredient_unique_hits' => '18','ingredient_unique_hits_ipblock' => '54.36.149.26
185.191.171.19
114.119.141.4
185.191.171.24
157.90.181.222
54.36.148.202
114.119.141.30','ingredient_updated_datetime' => '2022-02-13 13:43:00','ingredient_updated_by_user_id' => '1'),
  array('ingredient_id' => '21','ingredient_title' => 'Protein powder','ingredient_title_clean' => 'protein_powder','ingredient_icon_path' => '_uploads/recipes/main_ingredients','ingredient_icon_18x18_inactive' => 'protein_powder_18x18_inactive.png','ingredient_icon_18x18_active' => 'protein_powder_18x18_active.png','ingredient_icon_24x24_inactive' => 'protein_powder_24x24_inactive.png','ingredient_icon_24x24_active' => 'protein_powder_24x24_active.png','ingredient_category_id' => '2','ingredient_category_name' => 'Dinner','ingredient_unique_hits' => '15','ingredient_unique_hits_ipblock' => '54.36.149.16
185.191.171.2
185.191.171.40
157.90.181.222
185.191.171.7
54.36.148.21
185.191.171.9
185.191.171.22
185.191.171.18
54.36.148.63','ingredient_updated_datetime' => '2022-02-13 13:44:07','ingredient_updated_by_user_id' => '1'),
  array('ingredient_id' => '22','ingredient_title' => 'Cake','ingredient_title_clean' => 'cake','ingredient_icon_path' => '_uploads/recipes/main_ingredients','ingredient_icon_18x18_inactive' => 'cake_18x18_inactive.png','ingredient_icon_18x18_active' => 'cake_18x18_active.png','ingredient_icon_24x24_inactive' => 'cake_24x24_inactive.png','ingredient_icon_24x24_active' => 'cake_24x24_active.png','ingredient_category_id' => '4','ingredient_category_name' => 'Dessert','ingredient_unique_hits' => '8','ingredient_unique_hits_ipblock' => '185.191.171.33
185.191.171.1
185.191.171.5
185.191.171.6
185.191.171.3
185.191.171.4
185.191.171.42
157.90.181.222
','ingredient_updated_datetime' => '2022-02-13 13:48:39','ingredient_updated_by_user_id' => '1'),
  array('ingredient_id' => '23','ingredient_title' => 'Yoghurt','ingredient_title_clean' => 'yoghurt','ingredient_icon_path' => '_uploads/recipes/main_ingredients','ingredient_icon_18x18_inactive' => 'yoghurt_18x18_inactive.png','ingredient_icon_18x18_active' => 'yoghurt_18x18_active.png','ingredient_icon_24x24_inactive' => 'yoghurt_24x24_inactive.png','ingredient_icon_24x24_active' => 'yoghurt_24x24_active.png','ingredient_category_id' => '1','ingredient_category_name' => 'Breakfast','ingredient_unique_hits' => '13','ingredient_unique_hits_ipblock' => '185.191.171.44
185.191.171.22
157.90.181.222
185.191.171.11
185.191.171.1
185.191.171.38
185.191.171.2
95.181.237.10','ingredient_updated_datetime' => '2022-02-13 13:51:15','ingredient_updated_by_user_id' => '1'),
  array('ingredient_id' => '24','ingredient_title' => 'Salmon','ingredient_title_clean' => 'salmon','ingredient_icon_path' => '_uploads/recipes/main_ingredients','ingredient_icon_18x18_inactive' => 'salmon_18x18_inactive.png','ingredient_icon_18x18_active' => 'salmon_18x18_active.png','ingredient_icon_24x24_inactive' => 'salmon_24x24_inactive.png','ingredient_icon_24x24_active' => 'salmon_24x24_active.png','ingredient_category_id' => '1','ingredient_category_name' => 'Breakfast','ingredient_unique_hits' => '18','ingredient_unique_hits_ipblock' => '114.119.141.24
185.191.171.16
185.191.171.18
185.191.171.1
114.119.141.4
185.191.171.4
185.191.171.15','ingredient_updated_datetime' => '2022-02-13 13:52:49','ingredient_updated_by_user_id' => '1'),
  array('ingredient_id' => '25','ingredient_title' => 'Baked goods','ingredient_title_clean' => 'baked_goods','ingredient_icon_path' => '_uploads/recipes/main_ingredients','ingredient_icon_18x18_inactive' => 'baked_goods_18x18_inactive.png','ingredient_icon_18x18_active' => 'baked_goods_18x18_active.png','ingredient_icon_24x24_inactive' => 'baked_goods_24x24_inactive.png','ingredient_icon_24x24_active' => 'baked_goods_24x24_active.png','ingredient_category_id' => '1','ingredient_category_name' => 'Breakfast','ingredient_unique_hits' => '17','ingredient_unique_hits_ipblock' => '185.191.171.44
185.191.171.34
185.191.171.18
114.119.141.30
185.191.171.38
157.90.181.222','ingredient_updated_datetime' => '2022-02-13 13:57:13','ingredient_updated_by_user_id' => '1'),
  array('ingredient_id' => '26','ingredient_title' => 'Wheat baking','ingredient_title_clean' => 'wheat_baking','ingredient_icon_path' => '_uploads/recipes/main_ingredients','ingredient_icon_18x18_inactive' => 'wheat_baking_18x18_inactive.png','ingredient_icon_18x18_active' => 'wheat_baking_18x18_active.png','ingredient_icon_24x24_inactive' => 'wheat_baking_24x24_inactive.jpg','ingredient_icon_24x24_active' => 'wheat_baking_24x24_active.png','ingredient_category_id' => '3','ingredient_category_name' => 'Snacks','ingredient_unique_hits' => '8','ingredient_unique_hits_ipblock' => '185.191.171.9
185.191.171.11
185.191.171.2
185.191.171.3
185.191.171.5
185.191.171.24
157.90.181.222
193.75.58.234
','ingredient_updated_datetime' => '2022-03-04 14:27:05','ingredient_updated_by_user_id' => '1'),
  array('ingredient_id' => '27','ingredient_title' => 'Haddock','ingredient_title_clean' => 'haddock','ingredient_icon_path' => '_uploads/recipes/main_ingredients','ingredient_icon_18x18_inactive' => 'haddock_18x18_inactive.png','ingredient_icon_18x18_active' => 'haddock_18x18_active.png','ingredient_icon_24x24_inactive' => 'haddock_24x24_inactive.png','ingredient_icon_24x24_active' => 'haddock_24x24_active.png','ingredient_category_id' => '2','ingredient_category_name' => 'Dinner','ingredient_unique_hits' => '12','ingredient_unique_hits_ipblock' => '185.191.171.23
54.36.148.116
185.191.171.4
185.191.171.38
54.36.149.29
185.191.171.21
185.191.171.11','ingredient_updated_datetime' => '2022-03-11 16:35:21','ingredient_updated_by_user_id' => '1'),
  array('ingredient_id' => '28','ingredient_title' => 'Pork','ingredient_title_clean' => 'pork','ingredient_icon_path' => '_uploads/recipes/main_ingredients','ingredient_icon_18x18_inactive' => 'pork_18x18_inactive.png','ingredient_icon_18x18_active' => 'pork_18x18_active.png','ingredient_icon_24x24_inactive' => 'pork_24x24_inactive.png','ingredient_icon_24x24_active' => 'pork_24x24_active.png','ingredient_category_id' => '2','ingredient_category_name' => 'Dinner','ingredient_unique_hits' => '1','ingredient_unique_hits_ipblock' => '185.191.171.3
','ingredient_updated_datetime' => '2022-04-30 15:09:54','ingredient_updated_by_user_id' => '1'),
  array('ingredient_id' => '29','ingredient_title' => 'Milk','ingredient_title_clean' => 'milk','ingredient_icon_path' => '_uploads/recipes/main_ingredients','ingredient_icon_18x18_inactive' => 'milk_18x18_inactive.png','ingredient_icon_18x18_active' => 'milk_18x18_active.png','ingredient_icon_24x24_inactive' => 'milk_24x24_inactive.png','ingredient_icon_24x24_active' => 'milk_24x24_active.png','ingredient_category_id' => '4','ingredient_category_name' => 'Dessert','ingredient_unique_hits' => '1','ingredient_unique_hits_ipblock' => '114.119.141.34
','ingredient_updated_datetime' => '2022-04-30 15:11:33','ingredient_updated_by_user_id' => '1'),
  array('ingredient_id' => '30','ingredient_title' => 'Milk','ingredient_title_clean' => 'milk','ingredient_icon_path' => '_uploads/recipes/main_ingredients','ingredient_icon_18x18_inactive' => 'milk_18x18_inactive.png','ingredient_icon_18x18_active' => 'milk_18x18_active.png','ingredient_icon_24x24_inactive' => 'milk_24x24_inactive.png','ingredient_icon_24x24_active' => 'milk_24x24_active.png','ingredient_category_id' => '3','ingredient_category_name' => 'Snacks','ingredient_unique_hits' => NULL,'ingredient_unique_hits_ipblock' => NULL,'ingredient_updated_datetime' => '2022-04-30 15:11:58','ingredient_updated_by_user_id' => '1')
);

		
		foreach ($main_ingredients as $v) {

			$ingredient_id_mysql = quote_smart($link, $v['ingredient_id']);
			$ingredient_title_mysql = quote_smart($link, $v['ingredient_title']);
			$ingredient_title_clean_mysql = quote_smart($link, $v['ingredient_title_clean']);
			$ingredient_icon_path_mysql = quote_smart($link, $v['ingredient_icon_path']);
			$ingredient_icon_18x18_inactive_mysql = quote_smart($link, $v['ingredient_icon_18x18_inactive']);
			$ingredient_icon_18x18_active_mysql = quote_smart($link, $v['ingredient_icon_18x18_active']);
			$ingredient_icon_24x24_inactive_mysql = quote_smart($link, $v['ingredient_icon_24x24_inactive']);
			$ingredient_icon_24x24_active_mysql = quote_smart($link, $v['ingredient_icon_24x24_active']);
			$ingredient_category_id_mysql = quote_smart($link, $v['ingredient_category_id']);
			$ingredient_category_name_mysql = quote_smart($link, $v['ingredient_category_name']);



			mysqli_query($link, "INSERT INTO $t_recipes_main_ingredients 
			(ingredient_id, ingredient_title, ingredient_title_clean, ingredient_icon_path, ingredient_icon_18x18_inactive, ingredient_icon_18x18_active, ingredient_icon_24x24_inactive, ingredient_icon_24x24_active, ingredient_category_id, ingredient_category_name) 
			VALUES
			(NULL, $ingredient_title_mysql, $ingredient_title_clean_mysql, $ingredient_icon_path_mysql, $ingredient_icon_18x18_inactive_mysql, 
			$ingredient_icon_18x18_active_mysql, $ingredient_icon_24x24_inactive_mysql, $ingredient_icon_24x24_active_mysql, $ingredient_category_id_mysql, $ingredient_category_name_mysql)")
			or die(mysqli_error($link));

		} // foreach
	}
	echo"
	<!-- //recipes_main_ingredients -->



";
?>