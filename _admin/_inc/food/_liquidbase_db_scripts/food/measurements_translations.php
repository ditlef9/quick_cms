<?php
/**
*
* File: _admin/_inc/food/_liquibase/food/measurements_translations.php
* Version 1.0.0
* Date 15:43 18.10.2020
* Copyright (c) 2020 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ---------------------------------------------------------------------------- */

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_food_measurements_translations") or die(mysqli_error($link)); 


echo"


	<!-- food_measurements translations -->
	";
	$query = "SELECT * FROM $t_food_measurements_translations";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_food_measurements_translations: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_food_measurements_translations(
	  	measurement_translation_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(measurement_translation_id), 
	  	   measurement_id INT, 
	  	   measurement_translation_language VARCHAR(250), 
	  	   measurement_translation_value VARCHAR(250), 
	  	   measurement_translation_last_updated DATETIME)")
		   or die(mysqli_error());

$measurements_translations = array(
  array('measurement_translation_id' => '1','measurement_id' => '1','measurement_translation_language' => 'en','measurement_translation_value' => 'bag','measurement_translation_last_updated' => '2020-10-18 21:05:59'),
  array('measurement_translation_id' => '2','measurement_id' => '2','measurement_translation_language' => 'en','measurement_translation_value' => 'bowl','measurement_translation_last_updated' => '2020-10-18 21:05:59'),
  array('measurement_translation_id' => '3','measurement_id' => '3','measurement_translation_language' => 'en','measurement_translation_value' => 'box','measurement_translation_last_updated' => '2020-10-18 21:05:59'),
  array('measurement_translation_id' => '4','measurement_id' => '4','measurement_translation_language' => 'en','measurement_translation_value' => 'handful','measurement_translation_last_updated' => '2020-10-18 21:05:59'),
  array('measurement_translation_id' => '5','measurement_id' => '5','measurement_translation_language' => 'en','measurement_translation_value' => 'package','measurement_translation_last_updated' => '2020-10-18 21:05:59'),
  array('measurement_translation_id' => '6','measurement_id' => '6','measurement_translation_language' => 'en','measurement_translation_value' => 'piece','measurement_translation_last_updated' => '2020-10-18 21:05:59'),
  array('measurement_translation_id' => '7','measurement_id' => '7','measurement_translation_language' => 'en','measurement_translation_value' => 'pizza','measurement_translation_last_updated' => '2020-10-18 21:05:59'),
  array('measurement_translation_id' => '8','measurement_id' => '8','measurement_translation_language' => 'en','measurement_translation_value' => 'slice','measurement_translation_last_updated' => '2020-10-18 21:05:59'),
  array('measurement_translation_id' => '9','measurement_id' => '9','measurement_translation_language' => 'en','measurement_translation_value' => 'spoon','measurement_translation_last_updated' => '2020-10-18 21:05:59'),
  array('measurement_translation_id' => '10','measurement_id' => '10','measurement_translation_language' => 'en','measurement_translation_value' => 'teaspoon','measurement_translation_last_updated' => '2020-10-18 21:05:59'),
  array('measurement_translation_id' => '11','measurement_id' => '11','measurement_translation_language' => 'en','measurement_translation_value' => 'tablespoon','measurement_translation_last_updated' => '2020-10-18 21:05:59'),
  array('measurement_translation_id' => '12','measurement_id' => '1','measurement_translation_language' => 'no','measurement_translation_value' => 'pose','measurement_translation_last_updated' => '2020-10-18 21:05:59'),
  array('measurement_translation_id' => '13','measurement_id' => '2','measurement_translation_language' => 'no','measurement_translation_value' => 'bolle','measurement_translation_last_updated' => '2020-10-18 21:05:59'),
  array('measurement_translation_id' => '14','measurement_id' => '3','measurement_translation_language' => 'no','measurement_translation_value' => 'boks','measurement_translation_last_updated' => '2020-10-18 21:05:59'),
  array('measurement_translation_id' => '15','measurement_id' => '4','measurement_translation_language' => 'no','measurement_translation_value' => 'h&aring;ndfull','measurement_translation_last_updated' => '2020-10-18 21:05:59'),
  array('measurement_translation_id' => '16','measurement_id' => '5','measurement_translation_language' => 'no','measurement_translation_value' => 'pakke','measurement_translation_last_updated' => '2020-10-18 21:05:59'),
  array('measurement_translation_id' => '17','measurement_id' => '6','measurement_translation_language' => 'no','measurement_translation_value' => 'stk','measurement_translation_last_updated' => '2020-10-18 21:05:59'),
  array('measurement_translation_id' => '18','measurement_id' => '7','measurement_translation_language' => 'no','measurement_translation_value' => 'pizza','measurement_translation_last_updated' => '2020-10-18 21:05:59'),
  array('measurement_translation_id' => '19','measurement_id' => '8','measurement_translation_language' => 'no','measurement_translation_value' => 'skive','measurement_translation_last_updated' => '2020-10-18 21:05:59'),
  array('measurement_translation_id' => '20','measurement_id' => '9','measurement_translation_language' => 'no','measurement_translation_value' => 'skje','measurement_translation_last_updated' => '2020-10-18 21:05:59'),
  array('measurement_translation_id' => '21','measurement_id' => '10','measurement_translation_language' => 'no','measurement_translation_value' => 'ts','measurement_translation_last_updated' => '2020-10-18 21:05:59'),
  array('measurement_translation_id' => '22','measurement_id' => '11','measurement_translation_language' => 'no','measurement_translation_value' => 'ss','measurement_translation_last_updated' => '2020-10-18 21:05:59'),
  array('measurement_translation_id' => '23','measurement_id' => '12','measurement_translation_language' => 'no','measurement_translation_value' => 'beger','measurement_translation_last_updated' => '2021-01-04 19:09:59'),
  array('measurement_translation_id' => '24','measurement_id' => '12','measurement_translation_language' => 'en','measurement_translation_value' => 'cup','measurement_translation_last_updated' => '2021-01-04 19:09:59'),
  array('measurement_translation_id' => '25','measurement_id' => '1','measurement_translation_language' => 'sv','measurement_translation_value' => 'bag','measurement_translation_last_updated' => '2021-01-04 19:09:59'),
  array('measurement_translation_id' => '26','measurement_id' => '2','measurement_translation_language' => 'sv','measurement_translation_value' => 'bowl','measurement_translation_last_updated' => '2021-01-04 19:09:59'),
  array('measurement_translation_id' => '27','measurement_id' => '3','measurement_translation_language' => 'sv','measurement_translation_value' => 'box','measurement_translation_last_updated' => '2021-01-04 19:09:59'),
  array('measurement_translation_id' => '28','measurement_id' => '4','measurement_translation_language' => 'sv','measurement_translation_value' => 'handful','measurement_translation_last_updated' => '2021-01-04 19:09:59'),
  array('measurement_translation_id' => '29','measurement_id' => '5','measurement_translation_language' => 'sv','measurement_translation_value' => 'package','measurement_translation_last_updated' => '2021-01-04 19:09:59'),
  array('measurement_translation_id' => '30','measurement_id' => '6','measurement_translation_language' => 'sv','measurement_translation_value' => 'piece','measurement_translation_last_updated' => '2021-01-04 19:09:59'),
  array('measurement_translation_id' => '31','measurement_id' => '7','measurement_translation_language' => 'sv','measurement_translation_value' => 'pizza','measurement_translation_last_updated' => '2021-01-04 19:09:59'),
  array('measurement_translation_id' => '32','measurement_id' => '8','measurement_translation_language' => 'sv','measurement_translation_value' => 'slice','measurement_translation_last_updated' => '2021-01-04 19:09:59'),
  array('measurement_translation_id' => '33','measurement_id' => '9','measurement_translation_language' => 'sv','measurement_translation_value' => 'spoon','measurement_translation_last_updated' => '2021-01-04 19:09:59'),
  array('measurement_translation_id' => '34','measurement_id' => '10','measurement_translation_language' => 'sv','measurement_translation_value' => 'teaspoon','measurement_translation_last_updated' => '2021-01-04 19:09:59'),
  array('measurement_translation_id' => '35','measurement_id' => '11','measurement_translation_language' => 'sv','measurement_translation_value' => 'tablespoon','measurement_translation_last_updated' => '2021-01-04 19:09:59'),
  array('measurement_translation_id' => '36','measurement_id' => '12','measurement_translation_language' => 'sv','measurement_translation_value' => 'cup','measurement_translation_last_updated' => '2021-01-04 19:09:59'),
  array('measurement_translation_id' => '37','measurement_id' => '1','measurement_translation_language' => 'fr','measurement_translation_value' => 'bag','measurement_translation_last_updated' => '2021-01-04 19:09:59'),
  array('measurement_translation_id' => '38','measurement_id' => '2','measurement_translation_language' => 'fr','measurement_translation_value' => 'bowl','measurement_translation_last_updated' => '2021-01-04 19:09:59'),
  array('measurement_translation_id' => '39','measurement_id' => '3','measurement_translation_language' => 'fr','measurement_translation_value' => 'box','measurement_translation_last_updated' => '2021-01-04 19:09:59'),
  array('measurement_translation_id' => '40','measurement_id' => '4','measurement_translation_language' => 'fr','measurement_translation_value' => 'handful','measurement_translation_last_updated' => '2021-01-04 19:09:59'),
  array('measurement_translation_id' => '41','measurement_id' => '5','measurement_translation_language' => 'fr','measurement_translation_value' => 'package','measurement_translation_last_updated' => '2021-01-04 19:09:59'),
  array('measurement_translation_id' => '42','measurement_id' => '6','measurement_translation_language' => 'fr','measurement_translation_value' => 'piece','measurement_translation_last_updated' => '2021-01-04 19:09:59'),
  array('measurement_translation_id' => '43','measurement_id' => '7','measurement_translation_language' => 'fr','measurement_translation_value' => 'pizza','measurement_translation_last_updated' => '2021-01-04 19:09:59'),
  array('measurement_translation_id' => '44','measurement_id' => '8','measurement_translation_language' => 'fr','measurement_translation_value' => 'slice','measurement_translation_last_updated' => '2021-01-04 19:09:59'),
  array('measurement_translation_id' => '45','measurement_id' => '9','measurement_translation_language' => 'fr','measurement_translation_value' => 'spoon','measurement_translation_last_updated' => '2021-01-04 19:09:59'),
  array('measurement_translation_id' => '46','measurement_id' => '10','measurement_translation_language' => 'fr','measurement_translation_value' => 'teaspoon','measurement_translation_last_updated' => '2021-01-04 19:09:59'),
  array('measurement_translation_id' => '47','measurement_id' => '11','measurement_translation_language' => 'fr','measurement_translation_value' => 'tablespoon','measurement_translation_last_updated' => '2021-01-04 19:09:59'),
  array('measurement_translation_id' => '48','measurement_id' => '12','measurement_translation_language' => 'fr','measurement_translation_value' => 'cup','measurement_translation_last_updated' => '2021-01-04 19:09:59'),
  array('measurement_translation_id' => '49','measurement_id' => '1','measurement_translation_language' => 'es','measurement_translation_value' => 'bag','measurement_translation_last_updated' => '2021-01-04 19:09:59'),
  array('measurement_translation_id' => '50','measurement_id' => '2','measurement_translation_language' => 'es','measurement_translation_value' => 'bowl','measurement_translation_last_updated' => '2021-01-04 19:09:59'),
  array('measurement_translation_id' => '51','measurement_id' => '3','measurement_translation_language' => 'es','measurement_translation_value' => 'box','measurement_translation_last_updated' => '2021-01-04 19:09:59'),
  array('measurement_translation_id' => '52','measurement_id' => '4','measurement_translation_language' => 'es','measurement_translation_value' => 'handful','measurement_translation_last_updated' => '2021-01-04 19:09:59'),
  array('measurement_translation_id' => '53','measurement_id' => '5','measurement_translation_language' => 'es','measurement_translation_value' => 'package','measurement_translation_last_updated' => '2021-01-04 19:09:59'),
  array('measurement_translation_id' => '54','measurement_id' => '6','measurement_translation_language' => 'es','measurement_translation_value' => 'piece','measurement_translation_last_updated' => '2021-01-04 19:09:59'),
  array('measurement_translation_id' => '55','measurement_id' => '7','measurement_translation_language' => 'es','measurement_translation_value' => 'pizza','measurement_translation_last_updated' => '2021-01-04 19:09:59'),
  array('measurement_translation_id' => '56','measurement_id' => '8','measurement_translation_language' => 'es','measurement_translation_value' => 'slice','measurement_translation_last_updated' => '2021-01-04 19:09:59'),
  array('measurement_translation_id' => '57','measurement_id' => '9','measurement_translation_language' => 'es','measurement_translation_value' => 'spoon','measurement_translation_last_updated' => '2021-01-04 19:09:59'),
  array('measurement_translation_id' => '58','measurement_id' => '10','measurement_translation_language' => 'es','measurement_translation_value' => 'teaspoon','measurement_translation_last_updated' => '2021-01-04 19:09:59'),
  array('measurement_translation_id' => '59','measurement_id' => '11','measurement_translation_language' => 'es','measurement_translation_value' => 'tablespoon','measurement_translation_last_updated' => '2021-01-04 19:09:59'),
  array('measurement_translation_id' => '60','measurement_id' => '12','measurement_translation_language' => 'es','measurement_translation_value' => 'cup','measurement_translation_last_updated' => '2021-01-04 19:09:59'),
  array('measurement_translation_id' => '61','measurement_id' => '13','measurement_translation_language' => 'no','measurement_translation_value' => 'rundstykke','measurement_translation_last_updated' => '2021-01-04 19:09:59'),
  array('measurement_translation_id' => '62','measurement_id' => '13','measurement_translation_language' => 'es','measurement_translation_value' => 'roll','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '63','measurement_id' => '13','measurement_translation_language' => 'en','measurement_translation_value' => 'roll','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '64','measurement_id' => '13','measurement_translation_language' => 'sv','measurement_translation_value' => 'roll','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '65','measurement_id' => '13','measurement_translation_language' => 'fr','measurement_translation_value' => 'roll','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '66','measurement_id' => '14','measurement_translation_language' => 'en','measurement_translation_value' => 'whole','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '67','measurement_id' => '14','measurement_translation_language' => 'no','measurement_translation_value' => 'hel','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '68','measurement_id' => '15','measurement_translation_language' => 'en','measurement_translation_value' => 'spray','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '69','measurement_id' => '15','measurement_translation_language' => 'no','measurement_translation_value' => 'spr&oslash;yt','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '70','measurement_id' => '16','measurement_translation_language' => 'no','measurement_translation_value' => 'pinne','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '71','measurement_id' => '16','measurement_translation_language' => 'en','measurement_translation_value' => 'stick','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '72','measurement_id' => '17','measurement_translation_language' => 'no','measurement_translation_value' => 'baguett','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '73','measurement_id' => '17','measurement_translation_language' => 'en','measurement_translation_value' => 'baguette','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '74','measurement_id' => '14','measurement_translation_language' => 'sv','measurement_translation_value' => 'whole','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '75','measurement_id' => '15','measurement_translation_language' => 'sv','measurement_translation_value' => 'spray','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '76','measurement_id' => '16','measurement_translation_language' => 'sv','measurement_translation_value' => 'stick','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '77','measurement_id' => '17','measurement_translation_language' => 'sv','measurement_translation_value' => 'baguette','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '78','measurement_id' => '14','measurement_translation_language' => 'fr','measurement_translation_value' => 'whole','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '79','measurement_id' => '15','measurement_translation_language' => 'fr','measurement_translation_value' => 'spray','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '80','measurement_id' => '16','measurement_translation_language' => 'fr','measurement_translation_value' => 'stick','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '81','measurement_id' => '17','measurement_translation_language' => 'fr','measurement_translation_value' => 'baguette','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '82','measurement_id' => '14','measurement_translation_language' => 'es','measurement_translation_value' => 'whole','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '83','measurement_id' => '15','measurement_translation_language' => 'es','measurement_translation_value' => 'spray','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '84','measurement_id' => '16','measurement_translation_language' => 'es','measurement_translation_value' => 'stick','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '85','measurement_id' => '17','measurement_translation_language' => 'es','measurement_translation_value' => 'baguette','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '86','measurement_id' => '18','measurement_translation_language' => 'no','measurement_translation_value' => 'glass','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '87','measurement_id' => '18','measurement_translation_language' => 'en','measurement_translation_value' => 'glas','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '88','measurement_id' => '19','measurement_translation_language' => 'no','measurement_translation_value' => 'g','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '89','measurement_id' => '19','measurement_translation_language' => 'en','measurement_translation_value' => 'g','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '90','measurement_id' => '20','measurement_translation_language' => 'en','measurement_translation_value' => 'sausage bread','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '91','measurement_id' => '20','measurement_translation_language' => 'no','measurement_translation_value' => 'p&oslash;lsebr&oslash;d','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '92','measurement_id' => '21','measurement_translation_language' => 'en','measurement_translation_value' => 'tortilla','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '93','measurement_id' => '21','measurement_translation_language' => 'no','measurement_translation_value' => 'tortilla','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '94','measurement_id' => '23','measurement_translation_language' => 'en','measurement_translation_value' => 'filet','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '95','measurement_id' => '23','measurement_translation_language' => 'no','measurement_translation_value' => 'filet','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '96','measurement_id' => '24','measurement_translation_language' => 'no','measurement_translation_value' => 'lompe','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '97','measurement_id' => '24','measurement_translation_language' => 'en','measurement_translation_value' => 'lump','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '98','measurement_id' => '25','measurement_translation_language' => 'en','measurement_translation_value' => 'measuring spoon 60 ml','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '99','measurement_id' => '25','measurement_translation_language' => 'no','measurement_translation_value' => 'm&aring;leskje 60 ml','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '100','measurement_id' => '26','measurement_translation_language' => 'en','measurement_translation_value' => 'melon','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '101','measurement_id' => '26','measurement_translation_language' => 'no','measurement_translation_value' => 'melon','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '102','measurement_id' => '27','measurement_translation_language' => 'en','measurement_translation_value' => 'piece of cake','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '103','measurement_id' => '27','measurement_translation_language' => 'no','measurement_translation_value' => 'kakestykke','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '104','measurement_id' => '28','measurement_translation_language' => 'en','measurement_translation_value' => 'sausage','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '105','measurement_id' => '28','measurement_translation_language' => 'no','measurement_translation_value' => 'p&oslash;lse','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '106','measurement_id' => '29','measurement_translation_language' => 'en','measurement_translation_value' => 'potato','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '107','measurement_id' => '29','measurement_translation_language' => 'no','measurement_translation_value' => 'potet','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '108','measurement_id' => '30','measurement_translation_language' => 'en','measurement_translation_value' => 'cookie','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '109','measurement_id' => '30','measurement_translation_language' => 'no','measurement_translation_value' => 'cookie','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '110','measurement_id' => '31','measurement_translation_language' => 'en','measurement_translation_value' => 'ice stick','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '111','measurement_id' => '31','measurement_translation_language' => 'no','measurement_translation_value' => 'ispinne','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '112','measurement_id' => '32','measurement_translation_language' => 'no','measurement_translation_value' => 'porsjon','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '113','measurement_id' => '33','measurement_translation_language' => 'no','measurement_translation_value' => 'ost','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '114','measurement_id' => '32','measurement_translation_language' => 'en','measurement_translation_value' => 'porsjon','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '115','measurement_id' => '33','measurement_translation_language' => 'en','measurement_translation_value' => 'cheese','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '116','measurement_id' => '34','measurement_translation_language' => 'en','measurement_translation_value' => 'bottle','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '117','measurement_id' => '34','measurement_translation_language' => 'no','measurement_translation_value' => 'flaske','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '118','measurement_id' => '35','measurement_translation_language' => 'en','measurement_translation_value' => 'gingerbread','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '119','measurement_id' => '35','measurement_translation_language' => 'no','measurement_translation_value' => 'pepperkake','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '120','measurement_id' => '36','measurement_translation_language' => 'en','measurement_translation_value' => 'steak','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '121','measurement_id' => '36','measurement_translation_language' => 'no','measurement_translation_value' => 'biff','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '122','measurement_id' => '37','measurement_translation_language' => 'en','measurement_translation_value' => 'cake','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '123','measurement_id' => '37','measurement_translation_language' => 'no','measurement_translation_value' => 'kake','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '124','measurement_id' => '38','measurement_translation_language' => 'en','measurement_translation_value' => 'egg','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '125','measurement_id' => '38','measurement_translation_language' => 'no','measurement_translation_value' => 'egg','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '126','measurement_id' => '39','measurement_translation_language' => 'en','measurement_translation_value' => 'mushroom','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '127','measurement_id' => '39','measurement_translation_language' => 'no','measurement_translation_value' => 'sopp','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '128','measurement_id' => '40','measurement_translation_language' => 'en','measurement_translation_value' => 'burger','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '129','measurement_id' => '40','measurement_translation_language' => 'no','measurement_translation_value' => 'burger','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '130','measurement_id' => '41','measurement_translation_language' => 'en','measurement_translation_value' => 'stalks','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '131','measurement_id' => '41','measurement_translation_language' => 'no','measurement_translation_value' => 'stilker','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '132','measurement_id' => '42','measurement_translation_language' => 'en','measurement_translation_value' => 'piece of fish','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '133','measurement_id' => '42','measurement_translation_language' => 'no','measurement_translation_value' => 'fiskestykke','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '134','measurement_id' => '43','measurement_translation_language' => 'en','measurement_translation_value' => 'pita bread','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '135','measurement_id' => '43','measurement_translation_language' => 'no','measurement_translation_value' => 'pitabr&oslash;d','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '136','measurement_id' => '44','measurement_translation_language' => 'en','measurement_translation_value' => 'flat bread','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '137','measurement_id' => '44','measurement_translation_language' => 'no','measurement_translation_value' => 'flatbr&oslash;d','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '138','measurement_id' => '45','measurement_translation_language' => 'en','measurement_translation_value' => 'bar','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '139','measurement_id' => '45','measurement_translation_language' => 'no','measurement_translation_value' => 'bar','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '140','measurement_id' => '46','measurement_translation_language' => 'en','measurement_translation_value' => 'chips','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '141','measurement_id' => '46','measurement_translation_language' => 'no','measurement_translation_value' => 'chips','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '142','measurement_id' => '47','measurement_translation_language' => 'en','measurement_translation_value' => 'taco shell','measurement_translation_last_updated' => NULL),
  array('measurement_translation_id' => '143','measurement_id' => '47','measurement_translation_language' => 'no','measurement_translation_value' => 'tacoskjell','measurement_translation_last_updated' => NULL)
);
		$inp_date = date("Y-m-d H:i:s");
		foreach($measurements_translations as $v){
			
			$measurement_id = $v["measurement_id"];
			$measurement_translation_language = $v["measurement_translation_language"];
			$measurement_translation_value = $v["measurement_translation_value"];
		
			mysqli_query($link, "INSERT INTO $t_food_measurements_translations
			(measurement_translation_id, measurement_id, measurement_translation_language, measurement_translation_value, measurement_translation_last_updated) 
			VALUES 
			(NULL, '$measurement_id', '$measurement_translation_language', '$measurement_translation_value', '$inp_date')
			")
			or die(mysqli_error($link));


		}


	}
	echo"
	<!-- //food_measurements -->
";
?>