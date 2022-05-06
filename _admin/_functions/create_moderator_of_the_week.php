<?php
/**
*
* File: _admin/_functions/create_moderator_of_the_week.php
* Version: 2
* Date: 03.36 08.03.2017
* Copyright (c) 2017 Solo
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// Who is moderator of the week?
if(!(isset($week)) && !(isset($year))){
	$week = date("W");
	$year = date("Y");
}



$query = "SELECT moderator_user_id, moderator_user_email, moderator_user_name FROM $t_users_moderator_of_the_week WHERE moderator_week=$week AND moderator_year=$year";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_moderator_user_id, $get_moderator_user_email, $get_moderator_user_name) = $row;
if($get_moderator_user_id == ""){
	$inp_week = $week;



	// Pick a moderator
	$query = "SELECT user_id, user_email, user_name, user_alias, user_first_name, user_middle_name, user_last_name, user_language FROM $t_users WHERE user_rank='admin' OR user_rank='moderator'";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_mod_user_id, $get_mod_user_email, $get_mod_user_name, $get_mod_user_alias, $get_user_first_name, $get_user_middle_name, $get_user_last_name, $get_user_language) = $row;



		$inp_mod_email_mysql = quote_smart($link, $get_mod_user_email);
		$inp_mod_user_name_mysql = quote_smart($link, $get_mod_user_name);

		$inp_mod_user_alias = quote_smart($link, $get_mod_user_alias);
		$inp_mod_user_first_name = quote_smart($link, $get_user_first_name);
		$inp_mod_user_last_name = quote_smart($link, $get_user_last_name);
		$inp_mod_user_language = quote_smart($link, $get_user_language);


		if($inp_week < 54){
			
			// Find date of monday of the week
			$week_start = new DateTime();
			$week_start->setISODate($year,$inp_week);
			$start_date_saying = $week_start->format('j M Y');

			mysqli_query($link, "INSERT INTO $t_users_moderator_of_the_week
			(moderator_of_the_week_id, moderator_week, moderator_year, moderator_start_date_saying, moderator_user_id, 
			moderator_user_email, moderator_user_name, moderator_user_alias, moderator_user_first_name, moderator_user_last_name, 
			moderator_user_language) 
			VALUES 
			(NULL, '$inp_week', '$year', '$start_date_saying', '$get_mod_user_id', 
			$inp_mod_email_mysql, $inp_mod_user_name_mysql, $inp_mod_user_alias, $inp_mod_user_first_name, $inp_mod_user_last_name, 
			$inp_mod_user_language)")
			or die(mysqli_error($link));

			$get_moderator_user_id = "$get_mod_user_id";
			$get_moderator_user_email = "$get_mod_user_email";
			$get_moderator_user_name = "$get_mod_user_name";

			// Increment
			$inp_week = $week+1;
		}
	}
}


?>