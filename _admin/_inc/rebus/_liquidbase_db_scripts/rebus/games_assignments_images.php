<?php
/**
*
* File: _admin/_inc/rebus/_liquibase/rebus/games_assignments_images.php
* Version 1.0.0
* Date 07:23 01.07.2021
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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_rebus_games_assignments_images") or die(mysqli_error($link)); 

echo"
<!-- games_assignments -->
";

$query = "SELECT * FROM $t_rebus_games_assignments_images LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_rebus_games_assignments_images: $row_cnt</p>
	";
}
else{


	mysqli_query($link, "CREATE TABLE $t_rebus_games_assignments_images(
	  image_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(image_id), 
	   image_game_id INT, 
	   image_path VARCHAR(200),
	   image_file VARCHAR(200),
	   image_name VARCHAR(200),
	   image_uploaded_by_user_id INT,
	   image_uploaded_by_ip VARCHAR(200),
	   image_uploaded_datetime DATETIME
	   )")
	   or die(mysqli_error());



}
echo"
<!-- //games_assignments -->

";
?>