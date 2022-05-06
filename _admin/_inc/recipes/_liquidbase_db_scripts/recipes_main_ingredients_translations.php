<?php
/**
*
* File: _admin/_inc/recipes/_liquidbase_db_scripts/recipes_main_ingredients_translations.php
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
mysqli_query($link, "DROP TABLE IF EXISTS $t_recipes_main_ingredients_translations") or die(mysqli_error($link));
echo"



	<!-- recipes_main_ingredients_translations -->
	";
	$query = "SELECT * FROM $t_recipes_main_ingredients_translations";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_recipes_main_ingredients_translations: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_recipes_main_ingredients_translations(
	  	translation_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(translation_id), 
	  	   translation_ingredient_id INT,
	  	   translation_category_id INT,
	  	   translation_language VARCHAR(5), 
	  	   translation_value VARCHAR(250))")
		   or die(mysqli_error());

		$main_ingredients_translations = array(
  array('translation_id' => '1','translation_ingredient_id' => '1','translation_category_id' => '2','translation_language' => 'en','translation_value' => 'Chicken'),
  array('translation_id' => '2','translation_ingredient_id' => '1','translation_category_id' => '2','translation_language' => 'no','translation_value' => 'Kylling'),
  array('translation_id' => '3','translation_ingredient_id' => '2','translation_category_id' => '2','translation_language' => 'no','translation_value' => 'Torsk'),
  array('translation_id' => '4','translation_ingredient_id' => '3','translation_category_id' => '2','translation_language' => 'no','translation_value' => 'Egg'),
  array('translation_id' => '5','translation_ingredient_id' => '4','translation_category_id' => '1','translation_language' => 'no','translation_value' => 'Egg'),
  array('translation_id' => '6','translation_ingredient_id' => '5','translation_category_id' => '1','translation_language' => 'no','translation_value' => 'Vegetarianer'),
  array('translation_id' => '7','translation_ingredient_id' => '6','translation_category_id' => '3','translation_language' => 'no','translation_value' => 'Vegetarianer'),
  array('translation_id' => '8','translation_ingredient_id' => '7','translation_category_id' => '4','translation_language' => 'no','translation_value' => 'Frukt'),
  array('translation_id' => '9','translation_ingredient_id' => '8','translation_category_id' => '2','translation_language' => 'no','translation_value' => '&Oslash;rret'),
  array('translation_id' => '10','translation_ingredient_id' => '9','translation_category_id' => '5','translation_language' => 'no','translation_value' => 'Vegetarianer'),
  array('translation_id' => '11','translation_ingredient_id' => '2','translation_category_id' => '2','translation_language' => 'en','translation_value' => 'Cod'),
  array('translation_id' => '12','translation_ingredient_id' => '3','translation_category_id' => '2','translation_language' => 'en','translation_value' => 'Egg'),
  array('translation_id' => '13','translation_ingredient_id' => '4','translation_category_id' => '1','translation_language' => 'en','translation_value' => 'Egg'),
  array('translation_id' => '14','translation_ingredient_id' => '7','translation_category_id' => '4','translation_language' => 'en','translation_value' => 'Fruit'),
  array('translation_id' => '15','translation_ingredient_id' => '8','translation_category_id' => '2','translation_language' => 'en','translation_value' => 'Trout'),
  array('translation_id' => '16','translation_ingredient_id' => '5','translation_category_id' => '1','translation_language' => 'en','translation_value' => 'Vegetarian'),
  array('translation_id' => '17','translation_ingredient_id' => '6','translation_category_id' => '3','translation_language' => 'en','translation_value' => 'Vegetarian'),
  array('translation_id' => '18','translation_ingredient_id' => '9','translation_category_id' => '5','translation_language' => 'en','translation_value' => 'Vegetarian'),
  array('translation_id' => '19','translation_ingredient_id' => '10','translation_category_id' => '2','translation_language' => 'no','translation_value' => 'Karbonadedeig'),
  array('translation_id' => '20','translation_ingredient_id' => '11','translation_category_id' => '2','translation_language' => 'no','translation_value' => 'Kyllingkj&oslash;ttdeig'),
  array('translation_id' => '21','translation_ingredient_id' => '12','translation_category_id' => '2','translation_language' => 'no','translation_value' => 'Lam'),
  array('translation_id' => '22','translation_ingredient_id' => '11','translation_category_id' => '2','translation_language' => 'en','translation_value' => 'Chicken mince'),
  array('translation_id' => '23','translation_ingredient_id' => '12','translation_category_id' => '2','translation_language' => 'en','translation_value' => 'Lamb'),
  array('translation_id' => '24','translation_ingredient_id' => '10','translation_category_id' => '2','translation_language' => 'en','translation_value' => 'Extra lean ground beef'),
  array('translation_id' => '25','translation_ingredient_id' => '13','translation_category_id' => '4','translation_language' => 'en','translation_value' => 'Chocolade'),
  array('translation_id' => '26','translation_ingredient_id' => '13','translation_category_id' => '4','translation_language' => 'no','translation_value' => 'Sjokolade'),
  array('translation_id' => '27','translation_ingredient_id' => '14','translation_category_id' => '2','translation_language' => 'no','translation_value' => 'Biff'),
  array('translation_id' => '28','translation_ingredient_id' => '15','translation_category_id' => '3','translation_language' => 'no','translation_value' => 'Proteinpulver'),
  array('translation_id' => '29','translation_ingredient_id' => '16','translation_category_id' => '2','translation_language' => 'no','translation_value' => 'Vegetarianer'),
  array('translation_id' => '30','translation_ingredient_id' => '14','translation_category_id' => '2','translation_language' => 'en','translation_value' => 'Beef'),
  array('translation_id' => '31','translation_ingredient_id' => '15','translation_category_id' => '3','translation_language' => 'en','translation_value' => 'Protein powder'),
  array('translation_id' => '32','translation_ingredient_id' => '16','translation_category_id' => '2','translation_language' => 'en','translation_value' => 'Vegetarian'),
  array('translation_id' => '33','translation_ingredient_id' => '17','translation_category_id' => '1','translation_language' => 'no','translation_value' => 'Kylling'),
  array('translation_id' => '34','translation_ingredient_id' => '18','translation_category_id' => '1','translation_language' => 'no','translation_value' => 'Storfekj&oslash;tt'),
  array('translation_id' => '35','translation_ingredient_id' => '19','translation_category_id' => '1','translation_language' => 'no','translation_value' => 'Proteinpulver'),
  array('translation_id' => '36','translation_ingredient_id' => '20','translation_category_id' => '2','translation_language' => 'no','translation_value' => 'Laks'),
  array('translation_id' => '37','translation_ingredient_id' => '21','translation_category_id' => '2','translation_language' => 'no','translation_value' => 'Proteinpulver'),
  array('translation_id' => '38','translation_ingredient_id' => '18','translation_category_id' => '1','translation_language' => 'en','translation_value' => 'Beef'),
  array('translation_id' => '39','translation_ingredient_id' => '17','translation_category_id' => '1','translation_language' => 'en','translation_value' => 'Chicken'),
  array('translation_id' => '40','translation_ingredient_id' => '19','translation_category_id' => '1','translation_language' => 'en','translation_value' => 'Protein powder'),
  array('translation_id' => '41','translation_ingredient_id' => '21','translation_category_id' => '2','translation_language' => 'en','translation_value' => 'Protein powder'),
  array('translation_id' => '42','translation_ingredient_id' => '20','translation_category_id' => '2','translation_language' => 'en','translation_value' => 'Salmon'),
  array('translation_id' => '43','translation_ingredient_id' => '22','translation_category_id' => '4','translation_language' => 'no','translation_value' => 'Kake'),
  array('translation_id' => '44','translation_ingredient_id' => '22','translation_category_id' => '4','translation_language' => 'en','translation_value' => 'Cake'),
  array('translation_id' => '45','translation_ingredient_id' => '23','translation_category_id' => '1','translation_language' => 'en','translation_value' => 'Yoghurt'),
  array('translation_id' => '46','translation_ingredient_id' => '23','translation_category_id' => '1','translation_language' => 'no','translation_value' => 'Yoghurt'),
  array('translation_id' => '47','translation_ingredient_id' => '24','translation_category_id' => '1','translation_language' => 'en','translation_value' => 'Salmon'),
  array('translation_id' => '48','translation_ingredient_id' => '24','translation_category_id' => '1','translation_language' => 'no','translation_value' => 'Laks'),
  array('translation_id' => '49','translation_ingredient_id' => '25','translation_category_id' => '1','translation_language' => 'no','translation_value' => 'Bakevarer'),
  array('translation_id' => '50','translation_ingredient_id' => '25','translation_category_id' => '1','translation_language' => 'en','translation_value' => 'Baked goods'),
  array('translation_id' => '51','translation_ingredient_id' => '26','translation_category_id' => '3','translation_language' => 'en','translation_value' => 'Wheat baking'),
  array('translation_id' => '52','translation_ingredient_id' => '26','translation_category_id' => '3','translation_language' => 'no','translation_value' => 'Hvetebakst'),
  array('translation_id' => '53','translation_ingredient_id' => '27','translation_category_id' => '2','translation_language' => 'en','translation_value' => 'Haddock'),
  array('translation_id' => '54','translation_ingredient_id' => '27','translation_category_id' => '2','translation_language' => 'no','translation_value' => 'Hyse'),
  array('translation_id' => '55','translation_ingredient_id' => '28','translation_category_id' => '2','translation_language' => 'en','translation_value' => 'Pork'),
  array('translation_id' => '56','translation_ingredient_id' => '28','translation_category_id' => '2','translation_language' => 'no','translation_value' => 'Svin'),
  array('translation_id' => '57','translation_ingredient_id' => '29','translation_category_id' => '4','translation_language' => 'en','translation_value' => 'Milk'),
  array('translation_id' => '58','translation_ingredient_id' => '29','translation_category_id' => '4','translation_language' => 'no','translation_value' => 'Melk'),
  array('translation_id' => '59','translation_ingredient_id' => '30','translation_category_id' => '3','translation_language' => 'en','translation_value' => 'Milk'),
  array('translation_id' => '60','translation_ingredient_id' => '30','translation_category_id' => '3','translation_language' => 'no','translation_value' => 'Melk')
);

		
		foreach ($main_ingredients_translations as $v) {

			$translation_id_mysql = quote_smart($link, $v['translation_id']);
			$translation_ingredient_id_mysql = quote_smart($link, $v['translation_ingredient_id']);
			$translation_category_id_mysql = quote_smart($link, $v['translation_category_id']);
			$translation_language_mysql = quote_smart($link, $v['translation_language']);
			$translation_value_mysql = quote_smart($link, $v['translation_value']);



			mysqli_query($link, "INSERT INTO $t_recipes_main_ingredients_translations
			(translation_id, translation_ingredient_id, translation_category_id, translation_language, translation_value) 
			VALUES
			(NULL, $translation_ingredient_id_mysql, $translation_category_id_mysql, $translation_language_mysql, $translation_value_mysql)")
			or die(mysqli_error($link));

		} // foreach

	}
	echo"
	<!-- //recipes_main_ingredients -->



";
?>