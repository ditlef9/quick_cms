<?php
/**
*
* File: _admin/_inc/muscles/_liquibase/index.php
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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_muscle_groups") or die(mysqli_error($link)); 


echo"

	<!-- muscle_groups -->
	";
	$query = "SELECT * FROM $t_muscle_groups";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_muscle_groups: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_muscle_groups(
	  	 muscle_group_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(muscle_group_id), 
	  	   muscle_group_latin_name VARCHAR(250),
	  	   muscle_group_latin_name_clean VARCHAR(250),
	  	   muscle_group_name VARCHAR(250),
	  	   muscle_group_name_clean VARCHAR(250),
	  	   muscle_group_parent_id INT,
	  	   muscle_group_image_path VARCHAR(250),
	  	   muscle_group_image_file VARCHAR(250))")
		   or die(mysqli_error());
$stram_muscle_groups = array(
  array('muscle_group_id' => '1','muscle_group_latin_name' => NULL,'muscle_group_latin_name_clean' => NULL,'muscle_group_name' => 'Arms','muscle_group_name_clean' => 'arms','muscle_group_parent_id' => '0','muscle_group_image_path' => '_uploads/muscles/arms/','muscle_group_image_file' => 'arms.png'),
  array('muscle_group_id' => '2','muscle_group_latin_name' => NULL,'muscle_group_latin_name_clean' => NULL,'muscle_group_name' => 'Anterior','muscle_group_name_clean' => 'anterior','muscle_group_parent_id' => '1','muscle_group_image_path' => '_uploads/muscles/arms/anterior/','muscle_group_image_file' => 'anterior.png'),
  array('muscle_group_id' => '3','muscle_group_latin_name' => NULL,'muscle_group_latin_name_clean' => NULL,'muscle_group_name' => 'Posterior','muscle_group_name_clean' => 'posterior','muscle_group_parent_id' => '1','muscle_group_image_path' => '_uploads/muscles/arms/posterior','muscle_group_image_file' => 'posterior.png'),
  array('muscle_group_id' => '5','muscle_group_latin_name' => NULL,'muscle_group_latin_name_clean' => NULL,'muscle_group_name' => 'Back','muscle_group_name_clean' => 'back','muscle_group_parent_id' => '0','muscle_group_image_path' => NULL,'muscle_group_image_file' => NULL),
  array('muscle_group_id' => '6','muscle_group_latin_name' => NULL,'muscle_group_latin_name_clean' => NULL,'muscle_group_name' => 'Splenius','muscle_group_name_clean' => 'splenius','muscle_group_parent_id' => '5','muscle_group_image_path' => '_uploads/muscles/back/splenius','muscle_group_image_file' => 'splenius.png'),
  array('muscle_group_id' => '7','muscle_group_latin_name' => NULL,'muscle_group_latin_name_clean' => NULL,'muscle_group_name' => 'Erector spinae','muscle_group_name_clean' => 'erector_spinae','muscle_group_parent_id' => '5','muscle_group_image_path' => '_uploads/muscles/back/erector_spinae','muscle_group_image_file' => 'erector_spinae.png'),
  array('muscle_group_id' => '8','muscle_group_latin_name' => NULL,'muscle_group_latin_name_clean' => NULL,'muscle_group_name' => 'Transversospinales','muscle_group_name_clean' => 'transversospinales','muscle_group_parent_id' => '5','muscle_group_image_path' => '_uploads/muscles/back/transversospinales','muscle_group_image_file' => 'transversospinales.png'),
  array('muscle_group_id' => '9','muscle_group_latin_name' => NULL,'muscle_group_latin_name_clean' => NULL,'muscle_group_name' => 'Vertebral column','muscle_group_name_clean' => 'vertebral_column','muscle_group_parent_id' => '5','muscle_group_image_path' => '_uploads/muscles/back/vertebral_column','muscle_group_image_file' => 'vertebral_column.png'),
  array('muscle_group_id' => '10','muscle_group_latin_name' => NULL,'muscle_group_latin_name_clean' => NULL,'muscle_group_name' => 'Chest','muscle_group_name_clean' => 'chest','muscle_group_parent_id' => '0','muscle_group_image_path' => NULL,'muscle_group_image_file' => NULL),
  array('muscle_group_id' => '11','muscle_group_latin_name' => NULL,'muscle_group_latin_name_clean' => NULL,'muscle_group_name' => 'Muscles','muscle_group_name_clean' => 'muscles','muscle_group_parent_id' => '10','muscle_group_image_path' => '_uploads/muscles/chest/muscles','muscle_group_image_file' => 'muscles.png'),
  array('muscle_group_id' => '12','muscle_group_latin_name' => NULL,'muscle_group_latin_name_clean' => NULL,'muscle_group_name' => 'Thoracic cavity','muscle_group_name_clean' => 'thoracic_cavity','muscle_group_parent_id' => '10','muscle_group_image_path' => '_uploads/muscles/chest/thoracic_cavity','muscle_group_image_file' => 'thoracic_cavity.png'),
  array('muscle_group_id' => '13','muscle_group_latin_name' => NULL,'muscle_group_latin_name_clean' => NULL,'muscle_group_name' => 'Rompe, l&aring;r og legger','muscle_group_name_clean' => 'rompe__lar_og_legger','muscle_group_parent_id' => '0','muscle_group_image_path' => NULL,'muscle_group_image_file' => NULL),
  array('muscle_group_id' => '14','muscle_group_latin_name' => NULL,'muscle_group_latin_name_clean' => NULL,'muscle_group_name' => 'Buttocks','muscle_group_name_clean' => 'buttocks','muscle_group_parent_id' => '13','muscle_group_image_path' => '_uploads/muscles/hip_and_legs/buttocks','muscle_group_image_file' => 'buttocks.png'),
  array('muscle_group_id' => '15','muscle_group_latin_name' => NULL,'muscle_group_latin_name_clean' => NULL,'muscle_group_name' => 'Anterior','muscle_group_name_clean' => 'anterior','muscle_group_parent_id' => '13','muscle_group_image_path' => '_uploads/muscles/hip_and_legs/thigh_anterior','muscle_group_image_file' => 'anterior.png'),
  array('muscle_group_id' => '16','muscle_group_latin_name' => NULL,'muscle_group_latin_name_clean' => NULL,'muscle_group_name' => 'Posterior','muscle_group_name_clean' => 'posterior','muscle_group_parent_id' => '13','muscle_group_image_path' => '_uploads/muscles/rompe__lar_og_legger/posterior','muscle_group_image_file' => 'posterior.png'),
  array('muscle_group_id' => '20','muscle_group_latin_name' => NULL,'muscle_group_latin_name_clean' => NULL,'muscle_group_name' => 'Sholder','muscle_group_name_clean' => 'sholder','muscle_group_parent_id' => '0','muscle_group_image_path' => NULL,'muscle_group_image_file' => NULL),
  array('muscle_group_id' => '21','muscle_group_latin_name' => NULL,'muscle_group_latin_name_clean' => NULL,'muscle_group_name' => 'Muscles','muscle_group_name_clean' => 'muscles','muscle_group_parent_id' => '20','muscle_group_image_path' => '_uploads/muscles/sholder/muscles','muscle_group_image_file' => 'muscles.png'),
  array('muscle_group_id' => '22','muscle_group_latin_name' => NULL,'muscle_group_latin_name_clean' => NULL,'muscle_group_name' => 'Stomach','muscle_group_name_clean' => 'stomach','muscle_group_parent_id' => '0','muscle_group_image_path' => NULL,'muscle_group_image_file' => NULL),
  array('muscle_group_id' => '23','muscle_group_latin_name' => NULL,'muscle_group_latin_name_clean' => NULL,'muscle_group_name' => 'Abdominal wall','muscle_group_name_clean' => 'abdominal_wall','muscle_group_parent_id' => '22','muscle_group_image_path' => '_uploads/muscles/stomach/abdominal_wall','muscle_group_image_file' => 'abdominal_wall.png')
);
		$datetime = date("Y-m-d H:i:s");
		foreach($stram_muscle_groups as $v){
			
			$muscle_group_latin_name	= $v["muscle_group_latin_name"];
			$muscle_group_latin_name_clean	= $v["muscle_group_latin_name_clean"];
			$muscle_group_name		= $v["muscle_group_name"];
			$muscle_group_name_clean	= $v["muscle_group_name_clean"];
			$muscle_group_parent_id		= $v["muscle_group_parent_id"];
			$muscle_group_image_path	 = $v["muscle_group_image_path"];
			$muscle_group_image_file  	= $v["muscle_group_image_file"];
		
			mysqli_query($link, "INSERT INTO $t_muscle_groups
			(muscle_group_id, muscle_group_latin_name, muscle_group_latin_name_clean, muscle_group_name, muscle_group_name_clean, muscle_group_parent_id, 
			muscle_group_image_path, muscle_group_image_file) 
			VALUES 
			(NULL, '$muscle_group_latin_name', '$muscle_group_latin_name_clean', '$muscle_group_name', '$muscle_group_name_clean', '$muscle_group_parent_id', '$muscle_group_image_path', '$muscle_group_image_file')
			")
			or die(mysqli_error($link));

		}
		

	}
	echo"
	<!-- //muscle_groups -->



";
?>