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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_exercise_index") or die(mysqli_error($link)); 


echo"

	<!-- muscle_part_of_translations -->
	";
	$query = "SELECT * FROM $t_muscle_part_of_translations";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_muscle_part_of_translations: $row_cnt</p>
		";
	}
	else{

		mysqli_query($link, "CREATE TABLE $t_muscle_part_of_translations(
	  	 muscle_part_of_translation_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(muscle_part_of_translation_id), 
	  	   muscle_part_of_translation_muscle_part_of_id INT,
	  	   muscle_part_of_translation_language VARCHAR(25),
	  	   muscle_part_of_translation_name VARCHAR(250),
	  	   muscle_part_of_translation_text TEXT)")
		   or die(mysqli_error());

		$stram_muscle_part_of_translations = array(
  array('muscle_part_of_translation_id' => '1','muscle_part_of_translation_muscle_part_of_id' => '1','muscle_part_of_translation_language' => 'en','muscle_part_of_translation_name' => 'Gluteal muscles','muscle_part_of_translation_text' => ''),
  array('muscle_part_of_translation_id' => '2','muscle_part_of_translation_muscle_part_of_id' => '1','muscle_part_of_translation_language' => 'no','muscle_part_of_translation_name' => 'Gluteale muskler','muscle_part_of_translation_text' => '<p><span id="result_box" class="" lang="no" tabindex="-1"><span class="">Gluteal muskler er en gruppe med tre muskler som utgj&oslash;r rompen: gluteus maximus, gluteus medius og gluteus minimus.</span> De tre musklene stammer fra ilium og sakrum og setter p&aring; l&aring;rbenet. Funksjonene i musklene inkluderer forlengelse,&nbsp; ekstern rotasjon og intern rotasjon av hofteleddet.</span></p>'),
  array('muscle_part_of_translation_id' => '3','muscle_part_of_translation_muscle_part_of_id' => '2','muscle_part_of_translation_language' => 'no','muscle_part_of_translation_name' => 'Hamstring','muscle_part_of_translation_text' => '<p><strong><span id="result_box" class="" lang="no" tabindex="-1">Hva er hamstring?</span></strong></p>
<p><span id="result_box" class="" lang="no" tabindex="-1">Hamstring er en av de tre bakre l&aring;rmusklene mellom hoften og kneet <br /></span></p>
<p><strong><span class="" lang="no" tabindex="-1">Hva brukes den til?</span></strong></p>
<p><span class="" lang="no" tabindex="-1"><span id="result_box" class="" lang="no" tabindex="-1">Semimembranosus bidrar til &aring; forlenge (rette) hofteleddet og b&oslash;ye kn&aelig;leddet.<br /><br />Det bidrar ogs&aring; til &aring; rotere kneet medialt: tibia roterer medialt p&aring; l&aring;rbenet n&aring;r kneet er b&oslash;yd. Det roterer medialt l&aring;rbenet n&aring;r hoften er forlenget. Muskelen kan ogs&aring; hjelpe til med &aring; motvirke fremoverb&oslash;yningen i hofteleddet. <br /></span></span></p>
<p><strong><span class="" lang="no" tabindex="-1"><span class="" lang="no" tabindex="-1">Hvor er den plassert?</span></span></strong></p>
<p><span class="" lang="no" tabindex="-1"><span class="" lang="no" tabindex="-1"><span id="result_box" class="" lang="no" tabindex="-1">Fra medial til lateral: semimembranosus, semitendinosus og biceps femori. </span></span></span></p>'),
  array('muscle_part_of_translation_id' => '4','muscle_part_of_translation_muscle_part_of_id' => '2','muscle_part_of_translation_language' => 'en','muscle_part_of_translation_name' => 'Hamstring','muscle_part_of_translation_text' => NULL),
  array('muscle_part_of_translation_id' => '5','muscle_part_of_translation_muscle_part_of_id' => '3','muscle_part_of_translation_language' => 'no','muscle_part_of_translation_name' => 'Triceps surae','muscle_part_of_translation_text' => NULL),
  array('muscle_part_of_translation_id' => '6','muscle_part_of_translation_muscle_part_of_id' => '3','muscle_part_of_translation_language' => 'en','muscle_part_of_translation_name' => 'Triceps surae','muscle_part_of_translation_text' => NULL),
  array('muscle_part_of_translation_id' => '7','muscle_part_of_translation_muscle_part_of_id' => '4','muscle_part_of_translation_language' => 'en','muscle_part_of_translation_name' => 'Mobile wad','muscle_part_of_translation_text' => NULL),
  array('muscle_part_of_translation_id' => '8','muscle_part_of_translation_muscle_part_of_id' => '4','muscle_part_of_translation_language' => 'no','muscle_part_of_translation_name' => 'Mobile wad','muscle_part_of_translation_text' => NULL),
  array('muscle_part_of_translation_id' => '9','muscle_part_of_translation_muscle_part_of_id' => '5','muscle_part_of_translation_language' => 'no','muscle_part_of_translation_name' => 'Rhomboid','muscle_part_of_translation_text' => '<p><span id="result_box" class="" lang="no" tabindex="-1">Rhomboid muskler er rhombusformede muskler assosiert med scapulaen og er hovedsakelig ansvarlig for tilbaketrekningen. De er innervated av dorsal scapular nerve. <span class="">Det er to rhomboid muskler p&aring; hver side av &oslash;vre ryggen:</span><br /></span></p>
<ul>
<li><span id="result_box" class="" lang="no" tabindex="-1"><span class="">Rhomboid major muskel</span></span></li>
<li><span id="result_box" class="" lang="no" tabindex="-1"><span class="">Rhomboid minor muskel</span></span></li>
</ul>'),
  array('muscle_part_of_translation_id' => '10','muscle_part_of_translation_muscle_part_of_id' => '5','muscle_part_of_translation_language' => 'en','muscle_part_of_translation_name' => 'Rhomboid','muscle_part_of_translation_text' => NULL),
  array('muscle_part_of_translation_id' => '11','muscle_part_of_translation_muscle_part_of_id' => '6','muscle_part_of_translation_language' => 'en','muscle_part_of_translation_name' => 'Transverse abdominal','muscle_part_of_translation_text' => NULL),
  array('muscle_part_of_translation_id' => '12','muscle_part_of_translation_muscle_part_of_id' => '6','muscle_part_of_translation_language' => 'no','muscle_part_of_translation_name' => 'Transverse abdominal','muscle_part_of_translation_text' => NULL),
  array('muscle_part_of_translation_id' => '13','muscle_part_of_translation_muscle_part_of_id' => '7','muscle_part_of_translation_language' => 'no','muscle_part_of_translation_name' => 'Rectus sheath','muscle_part_of_translation_text' => NULL),
  array('muscle_part_of_translation_id' => '14','muscle_part_of_translation_muscle_part_of_id' => '7','muscle_part_of_translation_language' => 'en','muscle_part_of_translation_name' => 'Rectus sheath','muscle_part_of_translation_text' => NULL)
);


		foreach($stram_muscle_part_of_translations as $v){
			
			$muscle_part_of_translation_muscle_part_of_id = $v["muscle_part_of_translation_muscle_part_of_id"];
			$muscle_part_of_translation_language = $v["muscle_part_of_translation_language"];
			$muscle_part_of_translation_name = $v["muscle_part_of_translation_name"];
		
			mysqli_query($link, "INSERT INTO $t_muscle_part_of_translations
			(muscle_part_of_translation_id, muscle_part_of_translation_muscle_part_of_id, muscle_part_of_translation_language, muscle_part_of_translation_name) 
			VALUES 
			(NULL, '$muscle_part_of_translation_muscle_part_of_id', '$muscle_part_of_translation_language', '$muscle_part_of_translation_name')
			")
			or die(mysqli_error($link));


		}

	}
	echo"
	<!-- //muscle_part_of_translations -->



";
?>