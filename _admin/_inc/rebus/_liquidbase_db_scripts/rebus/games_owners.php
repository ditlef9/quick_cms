<?php
/**
*
* File: _admin/_inc/rebus/_liquibase/rebus/games_owners.php
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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_rebus_games_owners") or die(mysqli_error($link)); 


echo"
<!-- games_owners -->
";

$query = "SELECT * FROM $t_rebus_games_owners LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_rebus_games_owners: $row_cnt</p>
	";
}
else{


	mysqli_query($link, "CREATE TABLE $t_rebus_games_owners(
	  owner_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(owner_id), 
	   owner_game_id INT,
	   owner_user_id INT,
	   owner_user_name VARCHAR(200),
	   owner_user_email VARCHAR(200)
	   )")
	   or die(mysqli_error());

	// Sofiemyr - Hellerasten - T&aring;rn&aring;sen
	mysqli_query($link, "INSERT INTO $t_rebus_games_owners(`owner_id`, `owner_game_id`, `owner_user_id`, `owner_user_name`, `owner_user_email`) VALUES
	(NULL, 1, 1, 'Sigma', 'siditlef@gmail.com'),
	(NULL, 2, 1, 'Sigma', 'siditlef@gmail.com'),
	(NULL, 3, 1, 'Sigma', 'siditlef@gmail.com'),
	(NULL, 4, 1, 'Sigma', 'siditlef@gmail.com')
	")
	or die(mysqli_error());

}
echo"
<!-- //games_owners -->

";
?>