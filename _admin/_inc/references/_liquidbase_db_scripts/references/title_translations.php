<?php
/**
*
* File: _admin/_inc/references/_liquibase/courses/001_references.php
* Version 1.0.0
* Date 21:19 28.08.2019
* Copyright (c) 2019 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}
mysqli_query($link, "DROP TABLE IF EXISTS $t_references_title_translations")  or die(mysqli_error($link));

echo"


<!-- references title translations -->
";

$query = "SELECT * FROM $t_references_title_translations LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_references_title_translations: $row_cnt</p>
	";
}
else{
	mysqli_query($link, "CREATE TABLE $t_references_title_translations(
	  reference_title_translation_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(reference_title_translation_id), 
	   reference_title_translation_title VARCHAR(500), 
	   reference_title_translation_language VARCHAR(10))")
	   or die(mysqli_error());


	// Insert all languages
	$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;
		
		mysqli_query($link, "INSERT INTO $t_references_title_translations
		(reference_title_translation_id, reference_title_translation_title, reference_title_translation_language) 
		VALUES 
		(NULL, 'References', '$get_language_active_iso_two')")
		or die(mysqli_error($link));
	}
}
echo"
<!-- //reference title translations -->




";
?>