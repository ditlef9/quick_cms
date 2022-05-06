<?php
/**
*
* File: _admin/_inc/exercises/_liquibase/index.php
* Version 1.0.0
* Date 12:57 24.03.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ---------------------------------------------------------------------------- */

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_exercise_index_muscles") or die(mysqli_error($link)); 


echo"

	<!-- muscles -->
	";
	$query = "SELECT * FROM $t_exercise_index_muscles";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_exercise_index_muscles: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_exercise_index_muscles(
	  	 exercise_muscle_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(exercise_muscle_id), 
	  	   exercise_muscle_exercise_id INT,
	  	   exercise_muscle_muscle_id INT,
	  	   exercise_muscle_type VARCHAR(20))")
		   or die(mysqli_error());

$stram_exercise_index_muscles = array(
  array('exercise_muscle_id' => '1','exercise_muscle_exercise_id' => '3','exercise_muscle_muscle_id' => '1','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '2','exercise_muscle_exercise_id' => '3','exercise_muscle_muscle_id' => '2','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '3','exercise_muscle_exercise_id' => '3','exercise_muscle_muscle_id' => '3','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '4','exercise_muscle_exercise_id' => '4','exercise_muscle_muscle_id' => '1','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '5','exercise_muscle_exercise_id' => '4','exercise_muscle_muscle_id' => '2','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '6','exercise_muscle_exercise_id' => '4','exercise_muscle_muscle_id' => '3','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '7','exercise_muscle_exercise_id' => '2','exercise_muscle_muscle_id' => '4','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '8','exercise_muscle_exercise_id' => '1','exercise_muscle_muscle_id' => '24','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '9','exercise_muscle_exercise_id' => '1','exercise_muscle_muscle_id' => '25','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '10','exercise_muscle_exercise_id' => '1','exercise_muscle_muscle_id' => '17','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '11','exercise_muscle_exercise_id' => '1','exercise_muscle_muscle_id' => '18','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '12','exercise_muscle_exercise_id' => '1','exercise_muscle_muscle_id' => '20','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '13','exercise_muscle_exercise_id' => '5','exercise_muscle_muscle_id' => '31','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '14','exercise_muscle_exercise_id' => '5','exercise_muscle_muscle_id' => '12','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '15','exercise_muscle_exercise_id' => '5','exercise_muscle_muscle_id' => '4','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '16','exercise_muscle_exercise_id' => '5','exercise_muscle_muscle_id' => '14','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '17','exercise_muscle_exercise_id' => '5','exercise_muscle_muscle_id' => '1','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '18','exercise_muscle_exercise_id' => '5','exercise_muscle_muscle_id' => '2','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '19','exercise_muscle_exercise_id' => '6','exercise_muscle_muscle_id' => '1','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '20','exercise_muscle_exercise_id' => '6','exercise_muscle_muscle_id' => '12','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '21','exercise_muscle_exercise_id' => '7','exercise_muscle_muscle_id' => '41','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '22','exercise_muscle_exercise_id' => '7','exercise_muscle_muscle_id' => '32','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '23','exercise_muscle_exercise_id' => '7','exercise_muscle_muscle_id' => '8','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '24','exercise_muscle_exercise_id' => '7','exercise_muscle_muscle_id' => '31','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '25','exercise_muscle_exercise_id' => '7','exercise_muscle_muscle_id' => '42','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '26','exercise_muscle_exercise_id' => '7','exercise_muscle_muscle_id' => '10','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '27','exercise_muscle_exercise_id' => '7','exercise_muscle_muscle_id' => '33','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '28','exercise_muscle_exercise_id' => '7','exercise_muscle_muscle_id' => '1','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '29','exercise_muscle_exercise_id' => '7','exercise_muscle_muscle_id' => '2','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '30','exercise_muscle_exercise_id' => '8','exercise_muscle_muscle_id' => '12','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '34','exercise_muscle_exercise_id' => '8','exercise_muscle_muscle_id' => '31','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '35','exercise_muscle_exercise_id' => '8','exercise_muscle_muscle_id' => '1','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '36','exercise_muscle_exercise_id' => '8','exercise_muscle_muscle_id' => '4','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '37','exercise_muscle_exercise_id' => '9','exercise_muscle_muscle_id' => '41','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '38','exercise_muscle_exercise_id' => '10','exercise_muscle_muscle_id' => '12','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '39','exercise_muscle_exercise_id' => '10','exercise_muscle_muscle_id' => '13','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '40','exercise_muscle_exercise_id' => '10','exercise_muscle_muscle_id' => '14','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '41','exercise_muscle_exercise_id' => '10','exercise_muscle_muscle_id' => '4','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '42','exercise_muscle_exercise_id' => '11','exercise_muscle_muscle_id' => '12','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '43','exercise_muscle_exercise_id' => '11','exercise_muscle_muscle_id' => '4','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '44','exercise_muscle_exercise_id' => '11','exercise_muscle_muscle_id' => '31','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '45','exercise_muscle_exercise_id' => '11','exercise_muscle_muscle_id' => '33','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '46','exercise_muscle_exercise_id' => '12','exercise_muscle_muscle_id' => '31','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '48','exercise_muscle_exercise_id' => '12','exercise_muscle_muscle_id' => '8','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '49','exercise_muscle_exercise_id' => '12','exercise_muscle_muscle_id' => '4','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '50','exercise_muscle_exercise_id' => '12','exercise_muscle_muscle_id' => '14','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '51','exercise_muscle_exercise_id' => '13','exercise_muscle_muscle_id' => '12','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '52','exercise_muscle_exercise_id' => '13','exercise_muscle_muscle_id' => '1','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '53','exercise_muscle_exercise_id' => '13','exercise_muscle_muscle_id' => '31','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '54','exercise_muscle_exercise_id' => '13','exercise_muscle_muscle_id' => '33','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '55','exercise_muscle_exercise_id' => '14','exercise_muscle_muscle_id' => '24','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '56','exercise_muscle_exercise_id' => '14','exercise_muscle_muscle_id' => '21','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '57','exercise_muscle_exercise_id' => '14','exercise_muscle_muscle_id' => '22','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '58','exercise_muscle_exercise_id' => '14','exercise_muscle_muscle_id' => '23','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '59','exercise_muscle_exercise_id' => '14','exercise_muscle_muscle_id' => '39','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '60','exercise_muscle_exercise_id' => '14','exercise_muscle_muscle_id' => '6','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '61','exercise_muscle_exercise_id' => '14','exercise_muscle_muscle_id' => '40','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '62','exercise_muscle_exercise_id' => '14','exercise_muscle_muscle_id' => '35','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '63','exercise_muscle_exercise_id' => '15','exercise_muscle_muscle_id' => '21','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '64','exercise_muscle_exercise_id' => '15','exercise_muscle_muscle_id' => '22','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '65','exercise_muscle_exercise_id' => '15','exercise_muscle_muscle_id' => '23','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '66','exercise_muscle_exercise_id' => '15','exercise_muscle_muscle_id' => '8','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '67','exercise_muscle_exercise_id' => '15','exercise_muscle_muscle_id' => '42','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '68','exercise_muscle_exercise_id' => '15','exercise_muscle_muscle_id' => '10','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '69','exercise_muscle_exercise_id' => '15','exercise_muscle_muscle_id' => '25','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '70','exercise_muscle_exercise_id' => '16','exercise_muscle_muscle_id' => '31','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '71','exercise_muscle_exercise_id' => '16','exercise_muscle_muscle_id' => '34','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '72','exercise_muscle_exercise_id' => '16','exercise_muscle_muscle_id' => '32','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '73','exercise_muscle_exercise_id' => '16','exercise_muscle_muscle_id' => '8','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '74','exercise_muscle_exercise_id' => '17','exercise_muscle_muscle_id' => '24','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '75','exercise_muscle_exercise_id' => '17','exercise_muscle_muscle_id' => '25','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '76','exercise_muscle_exercise_id' => '17','exercise_muscle_muscle_id' => '21','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '77','exercise_muscle_exercise_id' => '17','exercise_muscle_muscle_id' => '22','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '78','exercise_muscle_exercise_id' => '17','exercise_muscle_muscle_id' => '23','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '79','exercise_muscle_exercise_id' => '17','exercise_muscle_muscle_id' => '30','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '80','exercise_muscle_exercise_id' => '18','exercise_muscle_muscle_id' => '24','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '82','exercise_muscle_exercise_id' => '18','exercise_muscle_muscle_id' => '21','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '83','exercise_muscle_exercise_id' => '18','exercise_muscle_muscle_id' => '22','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '84','exercise_muscle_exercise_id' => '18','exercise_muscle_muscle_id' => '23','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '85','exercise_muscle_exercise_id' => '18','exercise_muscle_muscle_id' => '30','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '86','exercise_muscle_exercise_id' => '18','exercise_muscle_muscle_id' => '25','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '87','exercise_muscle_exercise_id' => '19','exercise_muscle_muscle_id' => '24','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '88','exercise_muscle_exercise_id' => '19','exercise_muscle_muscle_id' => '21','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '89','exercise_muscle_exercise_id' => '19','exercise_muscle_muscle_id' => '22','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '90','exercise_muscle_exercise_id' => '19','exercise_muscle_muscle_id' => '23','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '91','exercise_muscle_exercise_id' => '19','exercise_muscle_muscle_id' => '30','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '92','exercise_muscle_exercise_id' => '19','exercise_muscle_muscle_id' => '26','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '93','exercise_muscle_exercise_id' => '19','exercise_muscle_muscle_id' => '27','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '94','exercise_muscle_exercise_id' => '19','exercise_muscle_muscle_id' => '28','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '95','exercise_muscle_exercise_id' => '19','exercise_muscle_muscle_id' => '25','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '96','exercise_muscle_exercise_id' => '19','exercise_muscle_muscle_id' => '17','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '97','exercise_muscle_exercise_id' => '19','exercise_muscle_muscle_id' => '18','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '98','exercise_muscle_exercise_id' => '20','exercise_muscle_muscle_id' => '24','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '99','exercise_muscle_exercise_id' => '20','exercise_muscle_muscle_id' => '28','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '100','exercise_muscle_exercise_id' => '20','exercise_muscle_muscle_id' => '27','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '101','exercise_muscle_exercise_id' => '20','exercise_muscle_muscle_id' => '26','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '102','exercise_muscle_exercise_id' => '20','exercise_muscle_muscle_id' => '30','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '103','exercise_muscle_exercise_id' => '20','exercise_muscle_muscle_id' => '21','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '104','exercise_muscle_exercise_id' => '20','exercise_muscle_muscle_id' => '22','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '105','exercise_muscle_exercise_id' => '20','exercise_muscle_muscle_id' => '23','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '106','exercise_muscle_exercise_id' => '20','exercise_muscle_muscle_id' => '25','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '107','exercise_muscle_exercise_id' => '20','exercise_muscle_muscle_id' => '17','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '108','exercise_muscle_exercise_id' => '20','exercise_muscle_muscle_id' => '18','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '109','exercise_muscle_exercise_id' => '21','exercise_muscle_muscle_id' => '24','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '110','exercise_muscle_exercise_id' => '21','exercise_muscle_muscle_id' => '28','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '111','exercise_muscle_exercise_id' => '21','exercise_muscle_muscle_id' => '27','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '112','exercise_muscle_exercise_id' => '21','exercise_muscle_muscle_id' => '26','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '113','exercise_muscle_exercise_id' => '21','exercise_muscle_muscle_id' => '30','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '114','exercise_muscle_exercise_id' => '21','exercise_muscle_muscle_id' => '21','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '115','exercise_muscle_exercise_id' => '21','exercise_muscle_muscle_id' => '22','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '116','exercise_muscle_exercise_id' => '21','exercise_muscle_muscle_id' => '23','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '117','exercise_muscle_exercise_id' => '21','exercise_muscle_muscle_id' => '25','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '118','exercise_muscle_exercise_id' => '21','exercise_muscle_muscle_id' => '17','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '119','exercise_muscle_exercise_id' => '21','exercise_muscle_muscle_id' => '18','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '120','exercise_muscle_exercise_id' => '22','exercise_muscle_muscle_id' => '24','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '121','exercise_muscle_exercise_id' => '22','exercise_muscle_muscle_id' => '28','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '122','exercise_muscle_exercise_id' => '22','exercise_muscle_muscle_id' => '27','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '123','exercise_muscle_exercise_id' => '22','exercise_muscle_muscle_id' => '26','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '124','exercise_muscle_exercise_id' => '22','exercise_muscle_muscle_id' => '30','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '125','exercise_muscle_exercise_id' => '22','exercise_muscle_muscle_id' => '21','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '126','exercise_muscle_exercise_id' => '22','exercise_muscle_muscle_id' => '22','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '127','exercise_muscle_exercise_id' => '22','exercise_muscle_muscle_id' => '23','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '128','exercise_muscle_exercise_id' => '22','exercise_muscle_muscle_id' => '25','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '129','exercise_muscle_exercise_id' => '22','exercise_muscle_muscle_id' => '17','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '130','exercise_muscle_exercise_id' => '22','exercise_muscle_muscle_id' => '18','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '131','exercise_muscle_exercise_id' => '23','exercise_muscle_muscle_id' => '1','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '132','exercise_muscle_exercise_id' => '23','exercise_muscle_muscle_id' => '2','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '133','exercise_muscle_exercise_id' => '24','exercise_muscle_muscle_id' => '21','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '134','exercise_muscle_exercise_id' => '24','exercise_muscle_muscle_id' => '22','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '135','exercise_muscle_exercise_id' => '24','exercise_muscle_muscle_id' => '23','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '136','exercise_muscle_exercise_id' => '24','exercise_muscle_muscle_id' => '8','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '137','exercise_muscle_exercise_id' => '24','exercise_muscle_muscle_id' => '25','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '138','exercise_muscle_exercise_id' => '24','exercise_muscle_muscle_id' => '42','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '139','exercise_muscle_exercise_id' => '25','exercise_muscle_muscle_id' => '24','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '140','exercise_muscle_exercise_id' => '25','exercise_muscle_muscle_id' => '25','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '141','exercise_muscle_exercise_id' => '25','exercise_muscle_muscle_id' => '21','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '142','exercise_muscle_exercise_id' => '25','exercise_muscle_muscle_id' => '22','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '143','exercise_muscle_exercise_id' => '25','exercise_muscle_muscle_id' => '23','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '144','exercise_muscle_exercise_id' => '26','exercise_muscle_muscle_id' => '40','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '145','exercise_muscle_exercise_id' => '26','exercise_muscle_muscle_id' => '39','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '146','exercise_muscle_exercise_id' => '26','exercise_muscle_muscle_id' => '6','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '147','exercise_muscle_exercise_id' => '26','exercise_muscle_muscle_id' => '4','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '148','exercise_muscle_exercise_id' => '27','exercise_muscle_muscle_id' => '12','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '149','exercise_muscle_exercise_id' => '27','exercise_muscle_muscle_id' => '13','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '150','exercise_muscle_exercise_id' => '27','exercise_muscle_muscle_id' => '4','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '151','exercise_muscle_exercise_id' => '27','exercise_muscle_muscle_id' => '31','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '152','exercise_muscle_exercise_id' => '28','exercise_muscle_muscle_id' => '12','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '153','exercise_muscle_exercise_id' => '28','exercise_muscle_muscle_id' => '13','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '154','exercise_muscle_exercise_id' => '28','exercise_muscle_muscle_id' => '14','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '155','exercise_muscle_exercise_id' => '28','exercise_muscle_muscle_id' => '4','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '156','exercise_muscle_exercise_id' => '28','exercise_muscle_muscle_id' => '31','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '158','exercise_muscle_exercise_id' => '29','exercise_muscle_muscle_id' => '35','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '160','exercise_muscle_exercise_id' => '30','exercise_muscle_muscle_id' => '12','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '161','exercise_muscle_exercise_id' => '30','exercise_muscle_muscle_id' => '4','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '162','exercise_muscle_exercise_id' => '30','exercise_muscle_muscle_id' => '31','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '163','exercise_muscle_exercise_id' => '31','exercise_muscle_muscle_id' => '4','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '164','exercise_muscle_exercise_id' => '31','exercise_muscle_muscle_id' => '31','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '165','exercise_muscle_exercise_id' => '31','exercise_muscle_muscle_id' => '33','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '166','exercise_muscle_exercise_id' => '32','exercise_muscle_muscle_id' => '12','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '167','exercise_muscle_exercise_id' => '32','exercise_muscle_muscle_id' => '4','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '168','exercise_muscle_exercise_id' => '32','exercise_muscle_muscle_id' => '33','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '169','exercise_muscle_exercise_id' => '33','exercise_muscle_muscle_id' => '24','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '170','exercise_muscle_exercise_id' => '33','exercise_muscle_muscle_id' => '25','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '171','exercise_muscle_exercise_id' => '33','exercise_muscle_muscle_id' => '21','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '172','exercise_muscle_exercise_id' => '33','exercise_muscle_muscle_id' => '22','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '173','exercise_muscle_exercise_id' => '33','exercise_muscle_muscle_id' => '23','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '174','exercise_muscle_exercise_id' => '34','exercise_muscle_muscle_id' => '31','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '175','exercise_muscle_exercise_id' => '34','exercise_muscle_muscle_id' => '34','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '176','exercise_muscle_exercise_id' => '34','exercise_muscle_muscle_id' => '32','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '177','exercise_muscle_exercise_id' => '34','exercise_muscle_muscle_id' => '8','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '178','exercise_muscle_exercise_id' => '34','exercise_muscle_muscle_id' => '14','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '179','exercise_muscle_exercise_id' => '34','exercise_muscle_muscle_id' => '11','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '180','exercise_muscle_exercise_id' => '35','exercise_muscle_muscle_id' => '24','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '181','exercise_muscle_exercise_id' => '35','exercise_muscle_muscle_id' => '28','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '182','exercise_muscle_exercise_id' => '36','exercise_muscle_muscle_id' => '24','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '183','exercise_muscle_exercise_id' => '37','exercise_muscle_muscle_id' => '4','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '184','exercise_muscle_exercise_id' => '37','exercise_muscle_muscle_id' => '33','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '185','exercise_muscle_exercise_id' => '37','exercise_muscle_muscle_id' => '31','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '186','exercise_muscle_exercise_id' => '38','exercise_muscle_muscle_id' => '21','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '187','exercise_muscle_exercise_id' => '38','exercise_muscle_muscle_id' => '22','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '188','exercise_muscle_exercise_id' => '38','exercise_muscle_muscle_id' => '23','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '190','exercise_muscle_exercise_id' => '39','exercise_muscle_muscle_id' => '4','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '191','exercise_muscle_exercise_id' => '39','exercise_muscle_muscle_id' => '12','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '192','exercise_muscle_exercise_id' => '40','exercise_muscle_muscle_id' => '1','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '193','exercise_muscle_exercise_id' => '41','exercise_muscle_muscle_id' => '4','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '195','exercise_muscle_exercise_id' => '42','exercise_muscle_muscle_id' => '24','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '196','exercise_muscle_exercise_id' => '42','exercise_muscle_muscle_id' => '25','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '197','exercise_muscle_exercise_id' => '43','exercise_muscle_muscle_id' => '1','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '198','exercise_muscle_exercise_id' => '44','exercise_muscle_muscle_id' => '6','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '199','exercise_muscle_exercise_id' => '44','exercise_muscle_muscle_id' => '39','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '200','exercise_muscle_exercise_id' => '44','exercise_muscle_muscle_id' => '40','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '201','exercise_muscle_exercise_id' => '44','exercise_muscle_muscle_id' => '31','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '202','exercise_muscle_exercise_id' => '45','exercise_muscle_muscle_id' => '41','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '203','exercise_muscle_exercise_id' => '45','exercise_muscle_muscle_id' => '8','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '205','exercise_muscle_exercise_id' => '45','exercise_muscle_muscle_id' => '42','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '206','exercise_muscle_exercise_id' => '45','exercise_muscle_muscle_id' => '4','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '207','exercise_muscle_exercise_id' => '46','exercise_muscle_muscle_id' => '35','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '208','exercise_muscle_exercise_id' => '47','exercise_muscle_muscle_id' => '41','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '209','exercise_muscle_exercise_id' => '47','exercise_muscle_muscle_id' => '8','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '210','exercise_muscle_exercise_id' => '47','exercise_muscle_muscle_id' => '31','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '211','exercise_muscle_exercise_id' => '47','exercise_muscle_muscle_id' => '1','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '212','exercise_muscle_exercise_id' => '48','exercise_muscle_muscle_id' => '28','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '213','exercise_muscle_exercise_id' => '49','exercise_muscle_muscle_id' => '31','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '214','exercise_muscle_exercise_id' => '50','exercise_muscle_muscle_id' => '35','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '215','exercise_muscle_exercise_id' => '50','exercise_muscle_muscle_id' => '37','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '216','exercise_muscle_exercise_id' => '50','exercise_muscle_muscle_id' => '36','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '217','exercise_muscle_exercise_id' => '51','exercise_muscle_muscle_id' => '37','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '218','exercise_muscle_exercise_id' => '51','exercise_muscle_muscle_id' => '31','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '219','exercise_muscle_exercise_id' => '51','exercise_muscle_muscle_id' => '24','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '220','exercise_muscle_exercise_id' => '52','exercise_muscle_muscle_id' => '12','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '221','exercise_muscle_exercise_id' => '52','exercise_muscle_muscle_id' => '4','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '222','exercise_muscle_exercise_id' => '52','exercise_muscle_muscle_id' => '31','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '223','exercise_muscle_exercise_id' => '53','exercise_muscle_muscle_id' => '1','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '224','exercise_muscle_exercise_id' => '54','exercise_muscle_muscle_id' => '41','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '226','exercise_muscle_exercise_id' => '54','exercise_muscle_muscle_id' => '4','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '227','exercise_muscle_exercise_id' => '55','exercise_muscle_muscle_id' => '4','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '228','exercise_muscle_exercise_id' => '56','exercise_muscle_muscle_id' => '41','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '229','exercise_muscle_exercise_id' => '56','exercise_muscle_muscle_id' => '32','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '230','exercise_muscle_exercise_id' => '56','exercise_muscle_muscle_id' => '1','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '231','exercise_muscle_exercise_id' => '57','exercise_muscle_muscle_id' => '41','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '232','exercise_muscle_exercise_id' => '57','exercise_muscle_muscle_id' => '1','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '233','exercise_muscle_exercise_id' => '57','exercise_muscle_muscle_id' => '32','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '234','exercise_muscle_exercise_id' => '57','exercise_muscle_muscle_id' => '2','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '235','exercise_muscle_exercise_id' => '58','exercise_muscle_muscle_id' => '35','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '236','exercise_muscle_exercise_id' => '59','exercise_muscle_muscle_id' => '35','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '237','exercise_muscle_exercise_id' => '60','exercise_muscle_muscle_id' => '35','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '238','exercise_muscle_exercise_id' => '61','exercise_muscle_muscle_id' => '37','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '239','exercise_muscle_exercise_id' => '61','exercise_muscle_muscle_id' => '36','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '240','exercise_muscle_exercise_id' => '61','exercise_muscle_muscle_id' => '35','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '241','exercise_muscle_exercise_id' => '62','exercise_muscle_muscle_id' => '35','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '242','exercise_muscle_exercise_id' => '63','exercise_muscle_muscle_id' => '35','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '243','exercise_muscle_exercise_id' => '64','exercise_muscle_muscle_id' => '37','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '244','exercise_muscle_exercise_id' => '64','exercise_muscle_muscle_id' => '35','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '245','exercise_muscle_exercise_id' => '65','exercise_muscle_muscle_id' => '35','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '246','exercise_muscle_exercise_id' => '66','exercise_muscle_muscle_id' => '35','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '247','exercise_muscle_exercise_id' => '67','exercise_muscle_muscle_id' => '35','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '248','exercise_muscle_exercise_id' => '68','exercise_muscle_muscle_id' => '35','exercise_muscle_type' => 'main'),
  array('exercise_muscle_id' => '249','exercise_muscle_exercise_id' => '68','exercise_muscle_muscle_id' => '24','exercise_muscle_type' => 'assistant'),
  array('exercise_muscle_id' => '250','exercise_muscle_exercise_id' => '69','exercise_muscle_muscle_id' => '41','exercise_muscle_type' => 'main')
);


		foreach($stram_exercise_index_muscles as $v){
			
			$exercise_muscle_exercise_id = $v["exercise_muscle_exercise_id"];
			$exercise_muscle_muscle_id = $v["exercise_muscle_muscle_id"];
			$exercise_muscle_type = $v["exercise_muscle_type"];
		
			mysqli_query($link, "INSERT INTO $t_exercise_index_muscles
			(exercise_muscle_id, exercise_muscle_exercise_id, exercise_muscle_muscle_id, exercise_muscle_type) 
			VALUES 
			(NULL, '$exercise_muscle_exercise_id', '$exercise_muscle_muscle_id', '$exercise_muscle_type')
			")
			or die(mysqli_error($link));


		}


	}
	echo"
	<!-- //muscles -->

";
?>