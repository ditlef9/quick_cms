<?php
/**
*
* File: _admin/_inc/rebus/_liquibase/rebus/games_index.php
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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_rebus_games_index") or die(mysqli_error($link)); 


echo"
<!-- games_index -->
";

$query = "SELECT * FROM $t_rebus_games_index LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_rebus_games_index: $row_cnt</p>
	";
}
else{

	echo"<p><em>Creating table $t_rebus_games_index</em></p>\n";

	mysqli_query($link, "CREATE TABLE $t_rebus_games_index(
	  game_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(game_id), 
	   game_title VARCHAR(250), 
	   game_language VARCHAR(2), 
	   game_introduction TEXT, 
	   game_description TEXT, 
	   game_privacy VARCHAR(20),
	   game_difficulty VARCHAR(20),
	   game_age_limit INT,
	   game_published INT, 
	   game_playable_after_datetime DATETIME,
	   game_playable_after_datetime_saying VARCHAR(50),
	   game_playable_after_time VARCHAR(50),
	   game_group_id INT,
	   game_group_name VARCHAR(200),
	   game_times_played INT, 
	   game_times_finished INT, 
	   game_finished_percentage INT, 
	   game_time_used_seconds INT,
	   game_time_used_saying VARCHAR(200),
	   game_image_path VARCHAR(200),
	   game_image_file VARCHAR(200),
	   game_image_thumb_278x156 VARCHAR(200),
	   game_image_thumb_570x321 VARCHAR(200),
	   game_image_thumb_570x380 VARCHAR(200),
	   game_country_id INT,
	   game_country_name VARCHAR(200),
	   game_county_id INT,
	   game_county_name VARCHAR(200),
	   game_municipality_id INT,
	   game_municipality_name VARCHAR(200),
	   game_city_id INT,
	   game_city_name VARCHAR(200),
	   game_place_id INT,
	   game_place_name VARCHAR(200),
	   game_place_latitude DOUBLE,
	   game_place_longitude DOUBLE,
	   game_latitude DOUBLE,
	   game_longitude DOUBLE,
	   game_number_of_assignments INT, 
	   game_rating INT,
	   game_created_by_user_id INT,
	   game_created_by_user_name VARCHAR(200),
	   game_created_by_user_email VARCHAR(200), 
	   game_created_by_ip VARCHAR(200), 
	   game_created_by_hostname VARCHAR(200), 
	   game_created_by_user_agent VARCHAR(200), 
	   game_created_datetime DATETIME, 
	   game_created_date_saying VARCHAR(50),
	   game_updated_by_user_id INT,
	   game_updated_by_user_name VARCHAR(200),
	   game_updated_by_user_email VARCHAR(200), 
	   game_updated_by_ip VARCHAR(200), 
	   game_updated_by_hostname VARCHAR(200), 
	   game_updated_by_user_agent VARCHAR(200), 
	   game_updated_datetime DATETIME, 
	   game_updated_date_saying VARCHAR(50)
	   )")
	   or die(mysqli_error());

	// Sofiemyr - Hellerasten - T&aring;rn&aring;sen
	mysqli_query($link, "INSERT INTO $t_rebus_games_index 
	(`game_id`, `game_title`, `game_language`, `game_introduction`, `game_description`, `game_privacy`, `game_published`, `game_playable_after_datetime`, `game_playable_after_datetime_saying`, `game_playable_after_time`, `game_group_id`, `game_group_name`, `game_times_played`, `game_times_finished`, `game_finished_percentage`, `game_time_used_seconds`, `game_time_used_saying`, `game_image_path`, `game_image_file`, `game_image_thumb_278x156`, `game_country_id`, `game_country_name`, `game_county_id`, `game_county_name`, `game_municipality_id`, `game_municipality_name`, `game_city_id`, `game_city_name`, `game_place_id`, `game_place_name`, `game_number_of_assignments`, `game_rating`, `game_created_by_user_id`, `game_created_by_user_name`, `game_created_by_user_email`, `game_created_by_ip`, `game_created_by_hostname`, `game_created_by_user_agent`, `game_created_datetime`, `game_created_date_saying`, `game_updated_by_user_id`, `game_updated_by_user_name`, `game_updated_by_user_email`, `game_updated_by_ip`, `game_updated_by_hostname`, `game_updated_by_user_agent`, `game_updated_datetime`, `game_updated_date_saying`) 
	VALUES
	(NULL, 'Sofiemyr - Hellerasten - T&aring;rn&aring;sen', 'en', 'Welcome to the game that deals with the places Sofiemyr, Hellerasten and TÃ¥rnÃ¥sen. This game is meant for everyone of all ages. Parts of the game go on a path that is not suitable for wheelchairs / prams. The game lasts for about 1.5 hours.', '', 'public', 1, '2021-07-13 20:00:00', '13 July 2021 20:00', '1626206400', 0, '', 0, NULL, NULL, NULL, NULL, '_uploads/rebus/games/1', '210713073818.jpg', NULL, 166, 'Norway', 1, 'Viken', 1, 'Nordre Follo', 1, 'Sofiemyr', 1, 'Sofiemyr', 7, NULL, 1, 'Sigma', 'siditlef@gmail.com', '127.0.0.1', 'DESKTOP-B', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:89.0) Gecko/20100101 Firefox/89.0', '2021-07-13 19:34:16', '13 Jul 2021', 1, 'Sigma', 'siditlef@gmail.com', '127.0.0.1', 'DESKTOP-B', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:89.0) Gecko/20100101 Firefox/89.0', '2021-07-13 20:29:00', '13 Jul 2021'),
	(NULL, 'Sofiemyr - Hellerasten - T&aring;rn&aring;sen', 'no', 'Velkommen til spillet som tar for seg stedene Sofiemyr, Hellerasten og T&aring;rn&aring;sen. Dette spillet er ment for alle i alle aldre. Deler av spillet g&aring;r p&aring; en sti som ikke egner seg for rullestol/barnevogn. Spillet varer i rundt 1,5 timer.', '', 'public', 1, '2021-07-13 20:00:00', '13 July 2021 20:00', '1626206400', 0, '', 0, NULL, NULL, NULL, NULL, '_uploads/rebus/games/1', '210713073818.jpg', NULL, 166, 'Norway', 1, 'Viken', 1, 'Nordre Follo', 1, 'Sofiemyr', 1, 'Sofiemyr', 7, NULL, 1, 'Sigma', 'siditlef@gmail.com', '127.0.0.1', 'DESKTOP-B', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:89.0) Gecko/20100101 Firefox/89.0', '2021-07-13 19:34:16', '13 Jul 2021', 1, 'Sigma', 'siditlef@gmail.com', '127.0.0.1', 'DESKTOP-B', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:89.0) Gecko/20100101 Firefox/89.0', '2021-07-13 20:29:00', '13 Jul 2021'),
	(NULL, 'Brannt&aring;rnet on Sofiemyr', 'en', 'Dette spillet starter p&aring; Sofiemyr senter (S&oslash;nsterudveien 32, 1412 Sofiemyr) og slutter p&aring; Brannt&aring;ret i skogen. Det tar ca 1 time &aring; spiller. Spillet g&aring;r p&aring; sti i skogen, s&aring; det passer ikke for handikappede.', '', 'public', 1, '2021-07-19 22:00:00', '19 July 2021 22:00', '1626732000', 0, '', 5, 4, 80, 142, '2   22', '_uploads/rebus/games/4', '210719093340.jpg', NULL, 166, 'Norway', 1, 'Viken', 1, 'Nordre Follo', 1, 'Sofiemyr', 1, 'Sofiemyr', 6, NULL, 1, 'Sigma', 'siditlef@gmail.com', '127.0.0.1', 'DESKTOP-B', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:89.0) Gecko/20100101 Firefox/89.0', '2021-07-19 21:31:58', '19 Jul 2021', 1, 'Sigma', 'siditlef@gmail.com', '127.0.0.1', 'DESKTOP-B', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:89.0) Gecko/20100101 Firefox/89.0', '2021-07-19 22:00:54', '19 Jul 2021'),
	(NULL, 'Brannt&aring;rnet p&aring; Sofiemyr', 'no', 'Dette spillet starter p&aring; Sofiemyr senter (S&oslash;nsterudveien 32, 1412 Sofiemyr) og slutter p&aring; Brannt&aring;ret i skogen. Det tar ca 1 time &aring; spiller. Spillet g&aring;r p&aring; sti i skogen, s&aring; det passer ikke for handikappede.', '', 'public', 1, '2021-07-19 22:00:00', '19 July 2021 22:00', '1626732000', 0, '', 5, 4, 80, 142, '2   22', '_uploads/rebus/games/4', '210719093340.jpg', NULL, 166, 'Norway', 1, 'Viken', 1, 'Nordre Follo', 1, 'Sofiemyr', 1, 'Sofiemyr', 6, NULL, 1, 'Sigma', 'siditlef@gmail.com', '127.0.0.1', 'DESKTOP-B', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:89.0) Gecko/20100101 Firefox/89.0', '2021-07-19 21:31:58', '19 Jul 2021', 1, 'Sigma', 'siditlef@gmail.com', '127.0.0.1', 'DESKTOP-B', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:89.0) Gecko/20100101 Firefox/89.0', '2021-07-19 22:00:54', '19 Jul 2021')
	")
	or die(mysqli_error());

}
echo"
<!-- //games_index -->

";
?>