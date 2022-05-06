<?php
/**
*
* File: _admin/_inc/rebus/_liquibase/rebus/games_ratings.php
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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_rebus_games_comments") or die(mysqli_error($link)); 


echo"
<!-- games_comments -->
";

$query = "SELECT * FROM $t_rebus_games_comments LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_rebus_games_comments: $row_cnt</p>
	";
}
else{


	mysqli_query($link, "CREATE TABLE $t_rebus_games_comments(
	  comment_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(comment_id), 
	   comment_game_id INT,
	   comment_rating INT,
	   comment_user_id INT,
	   comment_user_name VARCHAR(200),
	   comment_ip VARCHAR(200),
	   comment_image VARCHAR(200),
	   comment_created DATETIME,
	   comment_created_saying DATETIME,
	   comment_text TEXT,
	   comment_likes INT,
	   comment_dislikes INT,
	   comment_likes_dislikes_ipblock TEXT,
	   comment_marked INT,
	   comment_marked_checked INT
	   )")
	   or die(mysqli_error());

}
echo"
<!-- //games_comments -->

";
?>