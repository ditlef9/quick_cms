<?php
/**
*
* File: _admin/_inc/muscles/_liquibase/muscle_part_of.php
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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_muscle_part_of") or die(mysqli_error($link)); 


echo"


	<!-- muscle_part_of -->
	";
	$query = "SELECT * FROM $t_muscle_part_of";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_muscle_part_of: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_muscle_part_of(
	  	 muscle_part_of_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(muscle_part_of_id), 
	  	   muscle_part_of_latin_name VARCHAR(250),
	  	   muscle_part_of_latin_name_clean VARCHAR(250),
	  	   muscle_part_of_name VARCHAR(250),
	  	   muscle_part_of_name_clean VARCHAR(250),
	  	   muscle_part_of_muscle_group_id_main INT,
	  	   muscle_part_of_muscle_group_id_sub INT,
	  	   muscle_part_of_image_path VARCHAR(250),
	  	   muscle_part_of_image_file VARCHAR(250))")
		   or die(mysqli_error());

		$stram_muscle_part_of = array(
  array('muscle_part_of_id' => '1','muscle_part_of_latin_name' => 'Gluteal muscles','muscle_part_of_latin_name_clean' => 'gluteal_muscles','muscle_part_of_name' => 'Gluteal muscles','muscle_part_of_name_clean' => 'gluteal_muscles','muscle_part_of_muscle_group_id_main' => '13','muscle_part_of_muscle_group_id_sub' => '14','muscle_part_of_image_path' => '_uploads/muscles/hip_and_legs/buttocks','muscle_part_of_image_file' => 'gluteal_muscles.png'),
  array('muscle_part_of_id' => '2','muscle_part_of_latin_name' => 'Hamstring','muscle_part_of_latin_name_clean' => 'hamstring','muscle_part_of_name' => 'Hamstring','muscle_part_of_name_clean' => 'hamstring','muscle_part_of_muscle_group_id_main' => '13','muscle_part_of_muscle_group_id_sub' => '16','muscle_part_of_image_path' => '_uploads/muscles/rompe__lar_og_legger/posterior','muscle_part_of_image_file' => 'hamstring.png'),
  array('muscle_part_of_id' => '3','muscle_part_of_latin_name' => NULL,'muscle_part_of_latin_name_clean' => NULL,'muscle_part_of_name' => 'Triceps surae','muscle_part_of_name_clean' => 'triceps_surae','muscle_part_of_muscle_group_id_main' => NULL,'muscle_part_of_muscle_group_id_sub' => NULL,'muscle_part_of_image_path' => NULL,'muscle_part_of_image_file' => NULL),
  array('muscle_part_of_id' => '4','muscle_part_of_latin_name' => NULL,'muscle_part_of_latin_name_clean' => NULL,'muscle_part_of_name' => 'Mobile wad','muscle_part_of_name_clean' => 'mobile_wad','muscle_part_of_muscle_group_id_main' => NULL,'muscle_part_of_muscle_group_id_sub' => NULL,'muscle_part_of_image_path' => NULL,'muscle_part_of_image_file' => NULL),
  array('muscle_part_of_id' => '5','muscle_part_of_latin_name' => 'Rhomboid','muscle_part_of_latin_name_clean' => 'rhomboid','muscle_part_of_name' => 'Rhomboid','muscle_part_of_name_clean' => 'rhomboid','muscle_part_of_muscle_group_id_main' => '5','muscle_part_of_muscle_group_id_sub' => '9','muscle_part_of_image_path' => '_uploads/muscles/back/vertebral_column','muscle_part_of_image_file' => 'rhomboid.png'),
  array('muscle_part_of_id' => '6','muscle_part_of_latin_name' => NULL,'muscle_part_of_latin_name_clean' => NULL,'muscle_part_of_name' => 'Transverse abdominal','muscle_part_of_name_clean' => 'transverse_abdominal','muscle_part_of_muscle_group_id_main' => NULL,'muscle_part_of_muscle_group_id_sub' => NULL,'muscle_part_of_image_path' => NULL,'muscle_part_of_image_file' => NULL),
  array('muscle_part_of_id' => '7','muscle_part_of_latin_name' => NULL,'muscle_part_of_latin_name_clean' => NULL,'muscle_part_of_name' => 'Rectus sheath','muscle_part_of_name_clean' => 'rectus_sheath','muscle_part_of_muscle_group_id_main' => NULL,'muscle_part_of_muscle_group_id_sub' => NULL,'muscle_part_of_image_path' => NULL,'muscle_part_of_image_file' => NULL)
		);



		foreach($stram_muscle_part_of as $v){
			
			$muscle_part_of_latin_name 		= $v["muscle_part_of_latin_name"];
			$muscle_part_of_latin_name_clean 	= $v["muscle_part_of_latin_name_clean"];
			$muscle_part_of_name			= $v["muscle_part_of_name"];
			$muscle_part_of_name_clean		= $v["muscle_part_of_name_clean"];
			$muscle_part_of_muscle_group_id_main	= $v["muscle_part_of_muscle_group_id_main"];
			if($muscle_part_of_muscle_group_id_main == ""){ $muscle_part_of_muscle_group_id_main = "0"; }
			$muscle_part_of_muscle_group_id_sub	= $v["muscle_part_of_muscle_group_id_sub"];
			if($muscle_part_of_muscle_group_id_sub == ""){ $muscle_part_of_muscle_group_id_sub = "0"; }
			$muscle_part_of_image_path 		= $v["muscle_part_of_image_path"];
			$muscle_part_of_image_file 		= $v["muscle_part_of_image_file"];
		
			mysqli_query($link, "INSERT INTO $t_muscle_part_of
			(muscle_part_of_id, muscle_part_of_latin_name, muscle_part_of_latin_name_clean, muscle_part_of_name, muscle_part_of_name_clean, muscle_part_of_muscle_group_id_main, 
			muscle_part_of_muscle_group_id_sub, muscle_part_of_image_path, muscle_part_of_image_file) 
			VALUES 
			(NULL, '$muscle_part_of_latin_name', '$muscle_part_of_latin_name_clean', '$muscle_part_of_name', '$muscle_part_of_name_clean', '$muscle_part_of_muscle_group_id_main', '$muscle_part_of_muscle_group_id_sub', '$muscle_part_of_image_path', '$muscle_part_of_image_file')
			")
			or die(mysqli_error($link));
		}
	}
	echo"
	<!-- //muscle_part_of -->


";
?>