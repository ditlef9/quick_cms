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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_exercise_tags_cloud") or die(mysqli_error($link)); 


echo"

	<!-- exercise_tags_cloud -->
	";
	$query = "SELECT * FROM $t_exercise_tags_cloud";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_exercise_tags_cloud: $row_cnt</p>
		";
	}
	else{

		mysqli_query($link, "CREATE TABLE $t_exercise_tags_cloud(
	  	 cloud_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(cloud_id), 
	  	   cloud_language VARCHAR(20),
	  	   cloud_text VARCHAR(200),
	  	   cloud_clean VARCHAR(200),
	  	   cloud_occurrences INT,
	  	   cloud_unique_hits INT,
	  	   cloud_unique_hits_ipblock TEXT)")
		   or die(mysqli_error());

		mysqli_query($link, "INSERT INTO $t_exercise_tags_cloud(cloud_id, cloud_language, cloud_text, cloud_clean, cloud_occurrences)
					VALUES
(NULL, 'no', 'crossfit', 'crossfit', 3),
(NULL, 'no', 'dips', 'dips', 3),
(NULL, 'no', 'rompe', 'rompe', 3),
(NULL, 'no', 'intervaller', 'intervaller', 2),
(NULL, 'no', 'push-ups', 'push-ups', 0),
(NULL, 'no', 'isometrisk', 'isometrisk', 3),
(NULL, 'no', 'skulderpress', 'skulderpress', 4),
(NULL, 'no', 'skulder', 'skulder', 4),
(NULL, 'no', 'milit&aelig;rpress', 'militaerpress', 1),
(NULL, 'no', 'frontpress', 'frontpress', 1),
(NULL, 'no', 'fronthev', 'fronthev', 2),
(NULL, 'no', 'shrugs', 'shrugs', 2),
(NULL, 'no', 'kettlebell', 'kettlebell', 6),
(NULL, 'no', 'hjemmetrening', 'hjemmetrening', 24),
(NULL, 'en', 'kettlebell', 'kettlebell', 1),
(NULL, 'en', 'home gym', 'home_gym', 1),
(NULL, 'en', 'lunge', 'lunge', 2),
(NULL, 'no', 'hjemmetrenig-bryst', 'hjemmetrenig-bryst', 0),
(NULL, 'no', 'hjemmetrenig-skulder', 'hjemmetrenig-skulder', 0),
(NULL, 'no', 'hjemmetrening-skulde', 'hjemmetrening-skulder', 1),
(NULL, 'no', 'hjemmetrening-brys', 'hjemmetrening-brys', 0),
(NULL, 'no', 'hjemmetrening-bryst', 'hjemmetrening-bryst', 1),
(NULL, 'no', 'roing', 'roing', 2),
(NULL, 'no', 'flys', 'flys', 9),
(NULL, 'no', 'franskpress', 'franskpress', 2),
(NULL, 'no', 'benkpress', 'benkpress', 4),
(NULL, 'no', 'triceps', 'triceps', 35),
(NULL, 'no', 'fly', 'fly', 0),
(NULL, 'no', 'hantlepress', 'hantlepress', 3),
(NULL, 'no', 'flex', 'flex', 1),
(NULL, 'no', 'armhevninger', 'armhevninger', 7),
(NULL, 'no', 'bosu', 'bosu', 2),
(NULL, 'no', 'armhevninger-bosu', 'armhevninger-bosu', 2),
(NULL, 'no', 'chin-ups', 'chin-ups', 1),
(NULL, 'no', 'markl&oslash;ft', 'markloft', 3),
(NULL, 'no', 'god-morgen', 'god-morgen', 1),
(NULL, 'no', 'planken', 'planken', 2),
(NULL, 'no', 'leggcurl', 'leggcurl', 1),
(NULL, 'no', 'kneb&oslash;y', 'kneboy', 21),
(NULL, 'no', 'step-ups', 'step-ups', 1),
(NULL, 'no', 'tripces', 'tripces', 0),
(NULL, 'no', 'biceps', 'biceps', 26),
(NULL, 'no', 'biceps-curl', 'biceps-curl', 11),
(NULL, 'no', 'side-utfall', 'side-utfall', 1),
(NULL, 'no', 'utfall', 'utfall', 1),
(NULL, 'no', 'mage', 'mage', 1)")
		   or die(mysqli_error());

 	
	}
	echo"
	<!-- //exercise_tags_cloud -->

";
?>