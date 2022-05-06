<?php
/**
*
* File: _admin/_inc/rebus/_liquibase/rebus/games_sessions.php
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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_rebus_games_sessions_answers") or die(mysqli_error($link)); 

echo"
<!-- games_sessions_answers -->
";

$query = "SELECT * FROM $t_rebus_games_sessions_answers LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_rebus_games_sessions_answers: $row_cnt</p>
	";
}
else{
	mysqli_query($link, "CREATE TABLE $t_rebus_games_sessions_answers(
	  answer_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(answer_id), 
	   answer_session_id INT, 
	   answer_assignment_id INT, 
	   answer_assignment_number INT, 
	   answer_by_user_group_team VARCHAR(20),
	   answer_by_user_id INT,
	   answer_by_group_id INT,
	   answer_by_team_id INT,
	   answer_by_ip VARCHAR(200),
	   answer_datetime DATETIME,
	   answer_path VARCHAR(200),
	   answer_file VARCHAR(200),
	   answer_text VARCHAR(200),
	   answer_i_have_flagged_it INT,
	   answer_is_checked INT,
	   answer_is_correct INT,
	   answer_used_hint_a INT,
	   answer_used_hint_b INT,
	   answer_used_hint_c INT, 
	   answer_score INT
	   )")
	   or die(mysqli_error());


}
echo"
<!-- //games_sessions_answers -->

";
?>