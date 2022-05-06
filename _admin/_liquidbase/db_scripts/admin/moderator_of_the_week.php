<?php
if(isset($_SESSION['admin_user_id'])){
	$t_users_moderator_of_the_week 	= $mysqlPrefixSav . "users_moderator_of_the_week";


	mysqli_query($link,"DROP TABLE IF EXISTS $t_users_moderator_of_the_week") or die(mysqli_error());


	mysqli_query($link, "CREATE TABLE $t_users_moderator_of_the_week(
	   moderator_of_the_week_id INT NOT NULL AUTO_INCREMENT,
	   PRIMARY KEY(moderator_of_the_week_id), 
	   moderator_week INT,
	   moderator_year INT,
	   moderator_start_date_saying VARCHAR(200),
	   moderator_user_id INT,
	   moderator_user_email VARCHAR(200),
	   moderator_user_name VARCHAR(200),
	   moderator_user_alias VARCHAR(200),
	   moderator_user_first_name VARCHAR(200),
	   moderator_user_last_name VARCHAR(200),
	   moderator_user_language VARCHAR(10),
	   moderator_comment VARCHAR(200))")
	or die(mysqli_error($link));


}
?>