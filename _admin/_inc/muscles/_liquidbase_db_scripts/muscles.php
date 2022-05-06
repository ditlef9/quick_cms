<?php
/**
*
* File: _admin/_inc/muscles/_liquibase/muscles.php
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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_muscles") or die(mysqli_error($link)); 


echo"

	<!-- muscles -->
	";
	$query = "SELECT * FROM $t_muscles";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_muscles: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_muscles(
	  	 muscle_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(muscle_id), 
	  	   muscle_user_id INT,
	  	   muscle_latin_name VARCHAR(250),
	  	   muscle_latin_name_clean VARCHAR(250),
	  	   muscle_simple_name VARCHAR(250),
	  	   muscle_short_name VARCHAR(250),
	  	   muscle_group_id_main INT,
	  	   muscle_group_id_sub INT,
	  	   muscle_part_of_id INT,
	  	   muscle_text TEXT,
	  	   muscle_image_path VARCHAR(250),
	  	   muscle_image_file VARCHAR(250),
	  	   muscle_video_path VARCHAR(250),
	  	   muscle_video_file VARCHAR(250),
	  	   muscle_video_embedded VARCHAR(250),
	  	   muscle_unique_hits INT,
	  	   muscle_unique_hits_ip_block TEXT)")
		   or die(mysqli_error());
		

$stram_muscles = array(
  array('muscle_id' => '1','muscle_user_id' => '1','muscle_latin_name' => 'Biceps brachii','muscle_latin_name_clean' => 'biceps_brachii','muscle_simple_name' => 'Biceps brachii','muscle_short_name' => 'Biceps brachii','muscle_group_id_main' => '1','muscle_group_id_sub' => '2','muscle_part_of_id' => '0','muscle_text' => '','muscle_image_path' => '_uploads/muscles/arms/anterior','muscle_image_file' => 'biceps_brachii.png','muscle_video_path' => NULL,'muscle_video_file' => NULL,'muscle_video_embedded' => NULL,'muscle_unique_hits' => NULL,'muscle_unique_hits_ip_block' => NULL),
  array('muscle_id' => '2','muscle_user_id' => '1','muscle_latin_name' => 'Brachialis','muscle_latin_name_clean' => 'brachialis','muscle_simple_name' => 'Brachialis','muscle_short_name' => 'Brachialis','muscle_group_id_main' => '1','muscle_group_id_sub' => '2','muscle_part_of_id' => '0','muscle_text' => '','muscle_image_path' => '_uploads/muscles/arms/anterior','muscle_image_file' => 'brachialis.png','muscle_video_path' => NULL,'muscle_video_file' => NULL,'muscle_video_embedded' => NULL,'muscle_unique_hits' => NULL,'muscle_unique_hits_ip_block' => NULL),
  array('muscle_id' => '4','muscle_user_id' => '1','muscle_latin_name' => 'Triceps brachii','muscle_latin_name_clean' => 'triceps_brachii','muscle_simple_name' => 'Triceps brachii','muscle_short_name' => 'Triceps brachii','muscle_group_id_main' => '1','muscle_group_id_sub' => '3','muscle_part_of_id' => '0','muscle_text' => '','muscle_image_path' => '_uploads/muscles/arms/posterior','muscle_image_file' => 'triceps_brachii.png','muscle_video_path' => NULL,'muscle_video_file' => NULL,'muscle_video_embedded' => NULL,'muscle_unique_hits' => NULL,'muscle_unique_hits_ip_block' => NULL),
  array('muscle_id' => '5','muscle_user_id' => '1','muscle_latin_name' => 'Brachioradialis','muscle_latin_name_clean' => 'brachioradialis','muscle_simple_name' => 'Brachioradialis','muscle_short_name' => 'Brachioradialis','muscle_group_id_main' => '1','muscle_group_id_sub' => '3','muscle_part_of_id' => '0','muscle_text' => '','muscle_image_path' => '_uploads/muscles/arms/posterior','muscle_image_file' => 'brachioradialis.png','muscle_video_path' => NULL,'muscle_video_file' => NULL,'muscle_video_embedded' => NULL,'muscle_unique_hits' => NULL,'muscle_unique_hits_ip_block' => NULL),
  array('muscle_id' => '6','muscle_user_id' => '1','muscle_latin_name' => 'Iliocostalis','muscle_latin_name_clean' => 'iliocostalis','muscle_simple_name' => 'Iliocostalis','muscle_short_name' => 'Iliocostalis','muscle_group_id_main' => '5','muscle_group_id_sub' => '7','muscle_part_of_id' => '0','muscle_text' => '','muscle_image_path' => '_uploads/muscles/back/erector_spinae','muscle_image_file' => 'iliocostalis.png','muscle_video_path' => NULL,'muscle_video_file' => NULL,'muscle_video_embedded' => NULL,'muscle_unique_hits' => NULL,'muscle_unique_hits_ip_block' => NULL),
  array('muscle_id' => '8','muscle_user_id' => '1','muscle_latin_name' => 'Trapezius','muscle_latin_name_clean' => 'trapezius','muscle_simple_name' => 'Trapezius','muscle_short_name' => 'Trapezius','muscle_group_id_main' => '5','muscle_group_id_sub' => '9','muscle_part_of_id' => '0','muscle_text' => '','muscle_image_path' => '_uploads/muscles/back/vertebral_column','muscle_image_file' => 'trapezius.png','muscle_video_path' => NULL,'muscle_video_file' => NULL,'muscle_video_embedded' => NULL,'muscle_unique_hits' => NULL,'muscle_unique_hits_ip_block' => NULL),
  array('muscle_id' => '10','muscle_user_id' => '1','muscle_latin_name' => 'Rhomboid minor','muscle_latin_name_clean' => 'rhomboid_minor','muscle_simple_name' => 'Rhomboid minor','muscle_short_name' => 'Minor','muscle_group_id_main' => '5','muscle_group_id_sub' => '9','muscle_part_of_id' => '5','muscle_text' => '','muscle_image_path' => '_uploads/muscles/back/vertebral_column','muscle_image_file' => 'rhomboid_minor.png','muscle_video_path' => NULL,'muscle_video_file' => NULL,'muscle_video_embedded' => NULL,'muscle_unique_hits' => NULL,'muscle_unique_hits_ip_block' => NULL),
  array('muscle_id' => '11','muscle_user_id' => '1','muscle_latin_name' => 'Levator scapulae','muscle_latin_name_clean' => 'levator_scapulae','muscle_simple_name' => 'Levator scapulae','muscle_short_name' => 'Levator scapulae','muscle_group_id_main' => '5','muscle_group_id_sub' => '9','muscle_part_of_id' => '0','muscle_text' => '','muscle_image_path' => '_uploads/muscles/back/vertebral_column','muscle_image_file' => 'levator_scapulae.png','muscle_video_path' => NULL,'muscle_video_file' => NULL,'muscle_video_embedded' => NULL,'muscle_unique_hits' => NULL,'muscle_unique_hits_ip_block' => NULL),
  array('muscle_id' => '12','muscle_user_id' => '1','muscle_latin_name' => 'Pectoralis major','muscle_latin_name_clean' => 'pectoralis_major','muscle_simple_name' => 'Pectoralis major','muscle_short_name' => 'Pectoralis major','muscle_group_id_main' => '10','muscle_group_id_sub' => '12','muscle_part_of_id' => '0','muscle_text' => '','muscle_image_path' => '_uploads/muscles/chest/thoracic_cavity','muscle_image_file' => 'pectoralis_major.png','muscle_video_path' => NULL,'muscle_video_file' => NULL,'muscle_video_embedded' => NULL,'muscle_unique_hits' => NULL,'muscle_unique_hits_ip_block' => NULL),
  array('muscle_id' => '13','muscle_user_id' => '1','muscle_latin_name' => 'Pectoralis minor','muscle_latin_name_clean' => 'pectoralis_minor','muscle_simple_name' => 'Pectoralis minor','muscle_short_name' => 'Pectoralis minor','muscle_group_id_main' => '10','muscle_group_id_sub' => '12','muscle_part_of_id' => '0','muscle_text' => '','muscle_image_path' => '_uploads/muscles/chest/thoracic_cavity','muscle_image_file' => 'pectoralis_minor.png','muscle_video_path' => NULL,'muscle_video_file' => NULL,'muscle_video_embedded' => NULL,'muscle_unique_hits' => NULL,'muscle_unique_hits_ip_block' => NULL),
  array('muscle_id' => '14','muscle_user_id' => '1','muscle_latin_name' => 'Serratus anterior','muscle_latin_name_clean' => 'serratus_anterior','muscle_simple_name' => 'Serratus anterior','muscle_short_name' => 'Serratus anterior','muscle_group_id_main' => '10','muscle_group_id_sub' => '12','muscle_part_of_id' => '0','muscle_text' => '','muscle_image_path' => '_uploads/muscles/chest/thoracic_cavity','muscle_image_file' => 'serratus_anterior.png','muscle_video_path' => NULL,'muscle_video_file' => NULL,'muscle_video_embedded' => NULL,'muscle_unique_hits' => NULL,'muscle_unique_hits_ip_block' => NULL),
  array('muscle_id' => '15','muscle_user_id' => '1','muscle_latin_name' => 'Serratus posterior inferior','muscle_latin_name_clean' => 'serratus_posterior_inferior','muscle_simple_name' => 'Serratus posterior inferior','muscle_short_name' => 'Serratus posterior inferior','muscle_group_id_main' => '10','muscle_group_id_sub' => '11','muscle_part_of_id' => '0','muscle_text' => '','muscle_image_path' => '_uploads/muscles/chest/muscles','muscle_image_file' => 'serratus_posterior_inferior.png','muscle_video_path' => NULL,'muscle_video_file' => NULL,'muscle_video_embedded' => NULL,'muscle_unique_hits' => NULL,'muscle_unique_hits_ip_block' => NULL),
  array('muscle_id' => '17','muscle_user_id' => '1','muscle_latin_name' => 'Gluteus medius','muscle_latin_name_clean' => 'gluteus_medius','muscle_simple_name' => 'Gluteus medius','muscle_short_name' => 'Medius','muscle_group_id_main' => '13','muscle_group_id_sub' => '14','muscle_part_of_id' => '1','muscle_text' => '','muscle_image_path' => '_uploads/muscles/hip_and_legs/buttocks','muscle_image_file' => 'gluteus_medius.png','muscle_video_path' => NULL,'muscle_video_file' => NULL,'muscle_video_embedded' => NULL,'muscle_unique_hits' => NULL,'muscle_unique_hits_ip_block' => NULL),
  array('muscle_id' => '18','muscle_user_id' => '1','muscle_latin_name' => 'Gluteus minimus','muscle_latin_name_clean' => 'gluteus_minimus','muscle_simple_name' => 'Gluteus minimus','muscle_short_name' => 'Minimus','muscle_group_id_main' => '13','muscle_group_id_sub' => '14','muscle_part_of_id' => '1','muscle_text' => '','muscle_image_path' => '_uploads/muscles/hip_and_legs/buttocks','muscle_image_file' => 'gluteus_minimus.png','muscle_video_path' => NULL,'muscle_video_file' => NULL,'muscle_video_embedded' => NULL,'muscle_unique_hits' => NULL,'muscle_unique_hits_ip_block' => NULL),
  array('muscle_id' => '19','muscle_user_id' => '1','muscle_latin_name' => 'Tensor fasciae latae','muscle_latin_name_clean' => 'tensor_fasciae_latae','muscle_simple_name' => 'Tensor fasciae latae','muscle_short_name' => 'Tensor fasciae latae','muscle_group_id_main' => '13','muscle_group_id_sub' => '14','muscle_part_of_id' => '0','muscle_text' => '','muscle_image_path' => '_uploads/muscles/hip_and_legs/buttocks','muscle_image_file' => 'tensor_fasciae_latae.png','muscle_video_path' => NULL,'muscle_video_file' => NULL,'muscle_video_embedded' => NULL,'muscle_unique_hits' => NULL,'muscle_unique_hits_ip_block' => NULL),
  array('muscle_id' => '21','muscle_user_id' => '1','muscle_latin_name' => 'Biceps femoris','muscle_latin_name_clean' => 'biceps_femoris','muscle_simple_name' => 'Biceps femoris','muscle_short_name' => 'Biceps femoris','muscle_group_id_main' => '13','muscle_group_id_sub' => '16','muscle_part_of_id' => '2','muscle_text' => '','muscle_image_path' => '_uploads/muscles/rompe__lar_og_legger/posterior','muscle_image_file' => 'biceps_femoris.png','muscle_video_path' => NULL,'muscle_video_file' => NULL,'muscle_video_embedded' => NULL,'muscle_unique_hits' => NULL,'muscle_unique_hits_ip_block' => NULL),
  array('muscle_id' => '22','muscle_user_id' => '1','muscle_latin_name' => 'Semimembranosus','muscle_latin_name_clean' => 'semimembranosus','muscle_simple_name' => 'Semimembranosus','muscle_short_name' => 'Semimembranosus','muscle_group_id_main' => '13','muscle_group_id_sub' => '16','muscle_part_of_id' => '2','muscle_text' => '','muscle_image_path' => '_uploads/muscles/rompe__lar_og_legger/posterior','muscle_image_file' => 'semimembranosus.png','muscle_video_path' => NULL,'muscle_video_file' => NULL,'muscle_video_embedded' => NULL,'muscle_unique_hits' => NULL,'muscle_unique_hits_ip_block' => NULL),
  array('muscle_id' => '23','muscle_user_id' => '1','muscle_latin_name' => 'Semitendinosus','muscle_latin_name_clean' => 'semitendinosus','muscle_simple_name' => 'Semitendinosus','muscle_short_name' => 'Semitendinosus','muscle_group_id_main' => '13','muscle_group_id_sub' => '16','muscle_part_of_id' => '2','muscle_text' => '','muscle_image_path' => '_uploads/muscles/rompe__lar_og_legger/posterior','muscle_image_file' => 'semitendinosus.png','muscle_video_path' => NULL,'muscle_video_file' => NULL,'muscle_video_embedded' => NULL,'muscle_unique_hits' => NULL,'muscle_unique_hits_ip_block' => NULL),
  array('muscle_id' => '24','muscle_user_id' => '1','muscle_latin_name' => 'Quadriceps','muscle_latin_name_clean' => 'quadriceps','muscle_simple_name' => 'Quadriceps','muscle_short_name' => 'Quadriceps','muscle_group_id_main' => '13','muscle_group_id_sub' => '15','muscle_part_of_id' => '0','muscle_text' => '','muscle_image_path' => '_uploads/muscles/hip_and_legs/anterior','muscle_image_file' => 'quadriceps.png','muscle_video_path' => NULL,'muscle_video_file' => NULL,'muscle_video_embedded' => NULL,'muscle_unique_hits' => NULL,'muscle_unique_hits_ip_block' => NULL),
  array('muscle_id' => '25','muscle_user_id' => '1','muscle_latin_name' => 'Gluteus maximus','muscle_latin_name_clean' => 'gluteus_maximus','muscle_simple_name' => 'Gluteus maximus','muscle_short_name' => 'Maximus','muscle_group_id_main' => '13','muscle_group_id_sub' => '14','muscle_part_of_id' => '1','muscle_text' => '','muscle_image_path' => '_uploads/muscles/hip_and_legs/buttocks','muscle_image_file' => 'gluteus_maximus.png','muscle_video_path' => NULL,'muscle_video_file' => NULL,'muscle_video_embedded' => NULL,'muscle_unique_hits' => NULL,'muscle_unique_hits_ip_block' => NULL),
  array('muscle_id' => '26','muscle_user_id' => '1','muscle_latin_name' => 'Soleus','muscle_latin_name_clean' => 'soleus','muscle_simple_name' => 'Soleus','muscle_short_name' => 'Soleus','muscle_group_id_main' => '13','muscle_group_id_sub' => '16','muscle_part_of_id' => '0','muscle_text' => '','muscle_image_path' => '_uploads/muscles/rompe__lar_og_legger/posterior','muscle_image_file' => 'soleus.png','muscle_video_path' => NULL,'muscle_video_file' => NULL,'muscle_video_embedded' => NULL,'muscle_unique_hits' => NULL,'muscle_unique_hits_ip_block' => NULL),
  array('muscle_id' => '27','muscle_user_id' => '1','muscle_latin_name' => 'Gastrocnemius','muscle_latin_name_clean' => 'gastrocnemius','muscle_simple_name' => 'Gastrocnemius','muscle_short_name' => 'Gastrocnemius','muscle_group_id_main' => '13','muscle_group_id_sub' => '16','muscle_part_of_id' => '0','muscle_text' => '','muscle_image_path' => '_uploads/muscles/rompe__lar_og_legger/posterior','muscle_image_file' => 'gastrocnemius.png','muscle_video_path' => NULL,'muscle_video_file' => NULL,'muscle_video_embedded' => NULL,'muscle_unique_hits' => NULL,'muscle_unique_hits_ip_block' => NULL),
  array('muscle_id' => '28','muscle_user_id' => '1','muscle_latin_name' => 'Tibialis anterior','muscle_latin_name_clean' => 'tibialis_anterior','muscle_simple_name' => 'Tibialis anterior','muscle_short_name' => 'Tibialis anterior','muscle_group_id_main' => '13','muscle_group_id_sub' => '15','muscle_part_of_id' => '0','muscle_text' => '','muscle_image_path' => '_uploads/muscles/hip_and_legs/anterior','muscle_image_file' => 'tibialis_anterior.png','muscle_video_path' => NULL,'muscle_video_file' => NULL,'muscle_video_embedded' => NULL,'muscle_unique_hits' => NULL,'muscle_unique_hits_ip_block' => NULL),
  array('muscle_id' => '30','muscle_user_id' => '1','muscle_latin_name' => 'Triceps surae','muscle_latin_name_clean' => 'triceps_surae','muscle_simple_name' => 'Triceps surae','muscle_short_name' => 'Triceps surae','muscle_group_id_main' => '13','muscle_group_id_sub' => '16','muscle_part_of_id' => '0','muscle_text' => '','muscle_image_path' => '_uploads/muscles/rompe__lar_og_legger/posterior','muscle_image_file' => 'triceps_surae.png','muscle_video_path' => NULL,'muscle_video_file' => NULL,'muscle_video_embedded' => NULL,'muscle_unique_hits' => NULL,'muscle_unique_hits_ip_block' => NULL),
  array('muscle_id' => '31','muscle_user_id' => '1','muscle_latin_name' => 'Deltoid','muscle_latin_name_clean' => 'deltoid','muscle_simple_name' => 'Deltoid','muscle_short_name' => 'Deltoid','muscle_group_id_main' => '20','muscle_group_id_sub' => '21','muscle_part_of_id' => '0','muscle_text' => '','muscle_image_path' => '_uploads/muscles/sholder/muscles','muscle_image_file' => 'deltoid.png','muscle_video_path' => NULL,'muscle_video_file' => NULL,'muscle_video_embedded' => NULL,'muscle_unique_hits' => NULL,'muscle_unique_hits_ip_block' => NULL),
  array('muscle_id' => '32','muscle_user_id' => '1','muscle_latin_name' => 'Teres major','muscle_latin_name_clean' => 'teres_major','muscle_simple_name' => 'Teres major','muscle_short_name' => 'Teres major','muscle_group_id_main' => '20','muscle_group_id_sub' => '21','muscle_part_of_id' => '0','muscle_text' => '','muscle_image_path' => '_uploads/muscles/sholder/muscles','muscle_image_file' => 'teres_major.png','muscle_video_path' => NULL,'muscle_video_file' => NULL,'muscle_video_embedded' => NULL,'muscle_unique_hits' => NULL,'muscle_unique_hits_ip_block' => NULL),
  array('muscle_id' => '33','muscle_user_id' => '1','muscle_latin_name' => 'Rotator cuff','muscle_latin_name_clean' => 'rotator_cuff','muscle_simple_name' => 'Rotator cuff','muscle_short_name' => 'Rotator cuff','muscle_group_id_main' => '20','muscle_group_id_sub' => '21','muscle_part_of_id' => '0','muscle_text' => '','muscle_image_path' => '_uploads/muscles/sholder/muscles','muscle_image_file' => 'rotator_cuff.png','muscle_video_path' => NULL,'muscle_video_file' => NULL,'muscle_video_embedded' => NULL,'muscle_unique_hits' => NULL,'muscle_unique_hits_ip_block' => NULL),
  array('muscle_id' => '34','muscle_user_id' => '1','muscle_latin_name' => 'Supraspinatus','muscle_latin_name_clean' => 'supraspinatus','muscle_simple_name' => 'Supraspinatus','muscle_short_name' => 'Supraspinatus','muscle_group_id_main' => '20','muscle_group_id_sub' => '21','muscle_part_of_id' => '0','muscle_text' => '','muscle_image_path' => '_uploads/muscles/sholder/muscles','muscle_image_file' => 'supraspinatus.png','muscle_video_path' => NULL,'muscle_video_file' => NULL,'muscle_video_embedded' => NULL,'muscle_unique_hits' => NULL,'muscle_unique_hits_ip_block' => NULL),
  array('muscle_id' => '35','muscle_user_id' => '1','muscle_latin_name' => 'Rectus abdominis','muscle_latin_name_clean' => 'rectus_abdominis','muscle_simple_name' => 'Rectus abdominis','muscle_short_name' => 'Rectus abdominis','muscle_group_id_main' => '22','muscle_group_id_sub' => '23','muscle_part_of_id' => '0','muscle_text' => '','muscle_image_path' => '_uploads/muscles/stomach/abdominal_wall','muscle_image_file' => 'rectus_abdominis.png','muscle_video_path' => NULL,'muscle_video_file' => NULL,'muscle_video_embedded' => NULL,'muscle_unique_hits' => NULL,'muscle_unique_hits_ip_block' => NULL),
  array('muscle_id' => '36','muscle_user_id' => '1','muscle_latin_name' => 'Transverse abdominal','muscle_latin_name_clean' => 'transverse_abdominal','muscle_simple_name' => 'Transverse abdominal','muscle_short_name' => 'Transverse abdominal','muscle_group_id_main' => '22','muscle_group_id_sub' => '23','muscle_part_of_id' => '0','muscle_text' => '','muscle_image_path' => '_uploads/muscles/stomach/abdominal_wall','muscle_image_file' => 'transverse_abdominal.png','muscle_video_path' => NULL,'muscle_video_file' => NULL,'muscle_video_embedded' => NULL,'muscle_unique_hits' => NULL,'muscle_unique_hits_ip_block' => NULL),
  array('muscle_id' => '37','muscle_user_id' => '1','muscle_latin_name' => 'Oblique','muscle_latin_name_clean' => 'oblique','muscle_simple_name' => 'Oblique','muscle_short_name' => 'Oblique','muscle_group_id_main' => '22','muscle_group_id_sub' => '23','muscle_part_of_id' => '0','muscle_text' => '','muscle_image_path' => '_uploads/muscles/stomach/abdominal_wall','muscle_image_file' => 'oblique.png','muscle_video_path' => NULL,'muscle_video_file' => NULL,'muscle_video_embedded' => NULL,'muscle_unique_hits' => NULL,'muscle_unique_hits_ip_block' => NULL),
  array('muscle_id' => '39','muscle_user_id' => '1','muscle_latin_name' => 'Longissimus','muscle_latin_name_clean' => 'longissimus','muscle_simple_name' => 'Longissimus','muscle_short_name' => 'Longissimus','muscle_group_id_main' => '5','muscle_group_id_sub' => '7','muscle_part_of_id' => '0','muscle_text' => NULL,'muscle_image_path' => '_uploads/muscles/back/erector_spinae','muscle_image_file' => 'longissimus.png','muscle_video_path' => NULL,'muscle_video_file' => NULL,'muscle_video_embedded' => NULL,'muscle_unique_hits' => NULL,'muscle_unique_hits_ip_block' => NULL),
  array('muscle_id' => '40','muscle_user_id' => '1','muscle_latin_name' => 'Spinalis','muscle_latin_name_clean' => 'spinalis','muscle_simple_name' => 'Spinalis','muscle_short_name' => 'Spinalis','muscle_group_id_main' => '5','muscle_group_id_sub' => '7','muscle_part_of_id' => '0','muscle_text' => NULL,'muscle_image_path' => '_uploads/muscles/back/erector_spinae','muscle_image_file' => 'spinalis.png','muscle_video_path' => NULL,'muscle_video_file' => NULL,'muscle_video_embedded' => NULL,'muscle_unique_hits' => NULL,'muscle_unique_hits_ip_block' => NULL),
  array('muscle_id' => '41','muscle_user_id' => '1','muscle_latin_name' => 'Latissimus dorsi','muscle_latin_name_clean' => 'latissimus_dorsi','muscle_simple_name' => 'Latissimus dorsi','muscle_short_name' => 'Latissimus dorsi','muscle_group_id_main' => '5','muscle_group_id_sub' => '9','muscle_part_of_id' => '0','muscle_text' => NULL,'muscle_image_path' => '_uploads/muscles/back/vertebral_column','muscle_image_file' => 'latissimus_dorsi.png','muscle_video_path' => NULL,'muscle_video_file' => NULL,'muscle_video_embedded' => NULL,'muscle_unique_hits' => NULL,'muscle_unique_hits_ip_block' => NULL),
  array('muscle_id' => '42','muscle_user_id' => '1','muscle_latin_name' => 'Rhomboid major','muscle_latin_name_clean' => 'rhomboid_major','muscle_simple_name' => 'Rhomboid major','muscle_short_name' => 'Major','muscle_group_id_main' => '5','muscle_group_id_sub' => '9','muscle_part_of_id' => '5','muscle_text' => NULL,'muscle_image_path' => '_uploads/muscles/back/vertebral_column','muscle_image_file' => 'rhomboid_major.png','muscle_video_path' => NULL,'muscle_video_file' => NULL,'muscle_video_embedded' => NULL,'muscle_unique_hits' => NULL,'muscle_unique_hits_ip_block' => NULL)
);

		$datetime = date("Y-m-d H:i:s");
		foreach($stram_muscles as $v){
			
			$muscle_user_id = $v["muscle_user_id"];
			$muscle_latin_name = $v["muscle_latin_name"];
			$muscle_latin_name_clean = $v["muscle_latin_name_clean"];
			$muscle_simple_name = $v["muscle_simple_name"];
			$muscle_short_name = $v["muscle_short_name"];

			$muscle_group_id_main = $v["muscle_group_id_main"];
			$muscle_group_id_sub = $v["muscle_group_id_sub"];
			$muscle_part_of_id = $v["muscle_part_of_id"];
			$muscle_text = $v["muscle_text"];
			$muscle_image_path = $v["muscle_image_path"];
			$muscle_image_file = $v["muscle_image_file"];
			$muscle_video_path = $v["muscle_video_path"];
			$muscle_video_file = $v["muscle_video_file"];
			$muscle_video_embedded  = $v["muscle_video_embedded"];
		
			mysqli_query($link, "INSERT INTO $t_muscles
			(muscle_id, muscle_user_id, muscle_latin_name, muscle_latin_name_clean, muscle_simple_name, muscle_short_name, 
			muscle_group_id_main, muscle_group_id_sub, muscle_part_of_id, muscle_text, muscle_image_path, muscle_image_file, muscle_video_path, muscle_video_file, muscle_video_embedded) 
			VALUES 
			(NULL, '$muscle_user_id', '$muscle_latin_name', '$muscle_latin_name_clean', '$muscle_simple_name', '$muscle_short_name', 
			'$muscle_group_id_main', '$muscle_group_id_sub', '$muscle_part_of_id', '$muscle_text', 
			'$muscle_image_path', '$muscle_image_file', '$muscle_video_path', '$muscle_video_file', '$muscle_video_embedded')
			")
			or die(mysqli_error($link));


		}

	}
	echo"
	<!-- //muscles -->

";
?>