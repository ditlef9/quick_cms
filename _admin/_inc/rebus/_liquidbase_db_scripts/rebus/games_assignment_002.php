<?php
/**
*
* File: _admin/_inc/rebus/_liquibase/rebus/games_assignments.php
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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_rebus_games_assignments") or die(mysqli_error($link)); 

echo"
<!-- games_assignments -->
";

$query = "SELECT * FROM $t_rebus_games_assignments LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_rebus_games_assignments: $row_cnt</p>
	";
}
else{


	mysqli_query($link, "CREATE TABLE $t_rebus_games_assignments(
	  assignment_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(assignment_id), 
	   assignment_game_id INT, 
	   assignment_number INT,
	   assignment_type VARCHAR(200),
	   assignment_value TEXT,
	   assignment_value_short VARCHAR(100),
	   assignment_address VARCHAR(200),
	   assignment_answer_a VARCHAR(200),
	   assignment_answer_a_clean VARCHAR(200),
	   assignment_answer_b VARCHAR(200),
	   assignment_answer_b_clean VARCHAR(200),
	   assignment_answer_c VARCHAR(200),
	   assignment_answer_c_clean VARCHAR(200),
	   assignment_answer_d VARCHAR(200),
	   assignment_answer_d_clean VARCHAR(200),
	   assignment_correct_alternative VARCHAR(1),
	   assignment_radius_metric INT,
	   assignment_radius_imperial INT,
	   assignment_radius_user_measurment VARCHAR(20),
	   assignment_hint_a_type VARCHAR(20),
	   assignment_hint_a_value TEXT,
	   assignment_hint_a_price DOUBLE,
	   assignment_hint_b_type VARCHAR(20),
	   assignment_hint_b_value TEXT,
	   assignment_hint_b_price DOUBLE,
	   assignment_hint_c_type VARCHAR(20),
	   assignment_hint_c_value TEXT,
	   assignment_hint_c_price DOUBLE,
	   assignment_points DOUBLE,
	   assignment_text_when_correct_answer TEXT,
	   assignment_time_to_solve_seconds INT,
	   assignment_time_to_solve_saying VARCHAR(20),
	   assignment_created_by_user_id INT,
	   assignment_created_by_ip VARCHAR(200),
	   assignment_created_datetime DATETIME,
	   assignment_updated_by_user_id INT,
	   assignment_updated_by_ip VARCHAR(200),
	   assignment_updated_datetime DATETIME
	   )")
	   or die(mysqli_error());

	// Sofiemyr - Hellerasten - Tårnåsen game assignments
	/*
	mysqli_query($link, "INSERT INTO $t_rebus_games_assignments 
	(`assignment_id`, `assignment_game_id`, `assignment_number`, `assignment_type`, `assignment_value`, `assignment_address`, `assignment_answer_a`, `assignment_answer_a_clean`, `assignment_answer_b`, `assignment_answer_b_clean`, `assignment_radius_metric`, `assignment_radius_imperial`, `assignment_radius_user_measurment`, `assignment_hint_a_type`, `assignment_hint_a_value`, `assignment_hint_a_price`, `assignment_hint_b_type`, `assignment_hint_b_value`, `assignment_hint_b_price`, `assignment_hint_c_type`, `assignment_hint_c_value`, `assignment_hint_c_price`, `assignment_points`, `assignment_text_when_correct_answer`, `assignment_time_to_solve_seconds`, `assignment_time_to_solve_saying`, `assignment_created_by_user_id`, `assignment_created_by_ip`, `assignment_created_datetime`, `assignment_updated_by_user_id`, `assignment_updated_by_ip`, `assignment_updated_datetime`)
	 VALUES
(1, 1, 1, 'answer_a_question', 'We start at Sofiemyr center (S&oslash;nsterudveien 32). What color is the dentists office at the center in its logo?', '', 'blue', 'blue', '', '', 20, NULL, NULL, 'text', 'Same color as the sea', 0.5, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Congratulations! The logo is blue as the sea!', 0, '', 1, '127.0.0.1', '2021-07-13 19:42:02', 1, '127.0.0.1', '2021-07-18 11:43:28'),
(2, 1, 2, 'take_a_picture_with_coordinates', 'Your next task is to take a picture of a place where it goes around and around and around ...', '', '59.799519', '59799519', '10.819009', '10819009', 40, 13, 'metric', 'text', 'The surface is red', 0.5, 'text', '', 0.5, 'text', '', 0.5, 1, 'Congratulations! Your answer was approved! There are many who have run here!', 0, '', 1, '127.0.0.1', '2021-07-13 19:49:36', 1, '81.166.21.168', '2021-07-22 13:11:22'),
(3, 1, 3, 'take_a_picture_with_coordinates', 'Take a picture of something squeaking.', 'Fl&oslash;ysbonnveien 5, 1412 Sofiemyr', '59.799695', '59799695', '10.827699', '10827699', 41, 13, 'metric', 'text', 'This animal creaks and squeaks', 0.5, 'text', '', 0.5, 'text', '', 0.5, 1, 'Horse is the best! Are you ready for the next question?', 0, '', 1, '127.0.0.1', '2021-07-13 19:56:00', 1, '81.166.21.168', '2021-07-22 13:11:31'),
(4, 1, 4, 'answer_a_question', 'Go to Fl&oslash;ysbonnveien 5. At the end of this road is a path / road, but what is the name of the path?', '', 'Oldtidsveien', 'oldtidsveien', '', '', 20, NULL, NULL, 'text', 'It is very old', 0.5, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'The ancient road is just right! Very well done!', 0, '', 1, '127.0.0.1', '2021-07-13 19:57:07', 1, '127.0.0.1', '2021-07-18 11:45:14'),
(5, 1, 5, 'take_a_picture_with_coordinates', 'Follow the path until you come to a gravel road. The path continues along the gravel road. The gravel road is on Hellerasten. Take a picture of the trail when you stand on the gravel road.', 'Kongeveien 12, 1412 Sofiemyr', '59.807462', '59807462', '10.823772', '10823772', 39, 12, 'metric', 'text', 'Stand on the gravel road in the middle of the paths. Take a picture of the path from where you stand.', 0.5, 'text', '', 0.5, 'text', '', 0.5, 1, 'What a great picture! Good work!', 0, '', 1, '127.0.0.1', '2021-07-13 20:25:19', 1, '81.166.21.168', '2021-07-22 13:11:41'),
(6, 1, 6, 'take_a_picture_with_coordinates', 'Take a picture of The fire tower (Brannt&aring;rnet) located in the forest.', '', '59.817512', '59817512', '10.824228', '10824228', 42, 13, 'metric', 'text', 'Follow the path until you reach the Fire Tower. Take the picture of it.', 0.5, 'text', '', 0.5, 'text', '', 0.5, 1, 'Super! Youre good!', 0, '', 1, '127.0.0.1', '2021-07-13 20:28:08', 1, '81.166.21.168', '2021-07-22 13:11:14'),
(7, 1, 7, 'answer_a_question', 'Are you ready for the last task? What does it say on the top step? You can answer in English or Norwgian. In English the answer is three words, while it is two words in Norwegian.', '', 'Stopp St&oslash;yen', 'stopp_stoyen', 'Stop the Noise', 'stop_the_noise', 20, NULL, NULL, 'text', 'Stop ...', 0.5, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Congratulations!', 0, '', 1, '127.0.0.1', '2021-07-13 20:28:48', 1, '127.0.0.1', '2021-07-18 11:47:28'),
(8, 2, 1, 'answer_a_question', 'Vi starter p&aring; Sofiemyr senter (S&oslash;nsterudveien 32). Hvilken farge har tannlegekontoret p&aring; senteret i sin logo?', '', 'bl&aring;', 'bla', '', '', 20, NULL, NULL, 'text', 'Samme farge som havet', 0.5, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Gratulerer! Logoen er bl&aring; som havet!', 0, '', 1, '127.0.0.1', '2021-07-13 19:42:02', NULL, NULL, NULL),
(9, 2, 2, 'take_a_picture_with_coordinates', 'Din neste oppgave er &aring; ta et bilde av et sted hvor det g&aring;r rundt og rundt og rundt...', '', '59.799519', '59799519', '10.819009', '10819009', 128, 40, NULL, 'text', 'Underlaget er r&oslash;dt', 0.5, 'text', '', 0.5, 'text', '', 0.5, 1, 'Gratulerer! Svaret ditt ble godkjent! Her er det mange som har l&oslash;pt!', 0, '', 1, '127.0.0.1', '2021-07-13 19:49:36', 1, '81.166.21.168', '2021-07-22 13:10:39'),
(10, 2, 3, 'take_a_picture_with_coordinates', 'Ta bilde av noe som vrinsker.', 'Fl&oslash;ysbonnveien 5, 1412 Sofiemyr', '59.799695', '59799695', '10.827699', '10827699', 41, 13, 'metric', 'text', 'Dette dyret knegger og vrinsker', 0.5, 'text', '', 0.5, 'text', '', 0.5, 1, 'Hest er best! Er du klar for neste sp&oslash;rsm&aring;l?', 0, '', 1, '127.0.0.1', '2021-07-13 19:56:00', 1, '81.166.21.168', '2021-07-22 13:10:46'),
(11, 2, 4, 'answer_a_question', 'G&aring; til Fl&oslash;ysbonnveien 5. Ved enden av denne veien ligger en sti/vei, men hva heter stien?', '', 'Oldtidsveien', 'oldtidsveien', '', '', 20, NULL, NULL, 'text', 'Den er veldig gammel', 0.5, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Oldtidsveien er helt riktig! Veldig bra jobbet!', 0, '', 1, '127.0.0.1', '2021-07-13 19:57:07', NULL, NULL, NULL),
(12, 2, 5, 'take_a_picture_with_coordinates', 'F&oslash;lg stien til du kommer til en grusvei. Stien g&aring;r videre etter grusveien. Grusveien er p&aring; Hellerasten. Ta et bilde av stien n&aring;r du st&aring;r p&aring; grusveien.', 'Kongeveien 12, 1412 Sofiemyr', '59.807462', '59807462', '10.823772', '10823772', 38, 12, 'metric', 'text', 'St&aring; p&aring; grusveien midt mellom stiene. Ta bilde av stien fra der du st&aring;r.', 0.5, 'text', '', 0.5, 'text', '', 0.5, 1, 'For et flott bilde! Bra jobbet!', 0, '', 1, '127.0.0.1', '2021-07-13 20:25:19', 1, '81.166.21.168', '2021-07-22 13:10:54'),
(13, 2, 6, 'take_a_picture_with_coordinates', 'Brannt&aring;rnet', '', '59.817512', '59817512', '10.824228', '10824228', 40, 13, 'metric', 'text', 'F&oslash;lg stien til du kommer til Brannt&aring;rnet. Ta bildet av det.', 0.5, 'text', '', 0.5, 'text', '', 0.5, 1, 'Supert! Du er flink!', 0, '', 1, '127.0.0.1', '2021-07-13 20:28:08', 1, '81.166.21.168', '2021-07-22 13:10:28'),
(14, 2, 7, 'answer_a_question', 'Er du klar for siste oppgave? Hva st&aring;r det p&aring; det &oslash;verste trappetrinn?', '', 'Stopp St&oslash;yen', 'stopp_stoyen', '', '', 20, NULL, NULL, 'text', 'Stopp...', 0.5, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Gratulerer!', 0, '', 1, '127.0.0.1', '2021-07-13 20:28:48', NULL, NULL, NULL)
	")
	or die(mysqli_error());
	*/

	// Branntårnet
	/*
	mysqli_query($link, "INSERT INTO $t_rebus_games_assignments 
	(`assignment_id`, `assignment_game_id`, `assignment_number`, `assignment_type`, `assignment_value`, `assignment_address`, `assignment_answer_a`, `assignment_answer_a_clean`, `assignment_answer_b`, `assignment_answer_b_clean`, `assignment_radius_metric`, `assignment_radius_imperial`, `assignment_radius_user_measurment`, `assignment_hint_a_type`, `assignment_hint_a_value`, `assignment_hint_a_price`, `assignment_hint_b_type`, `assignment_hint_b_value`, `assignment_hint_b_price`, `assignment_hint_c_type`, `assignment_hint_c_value`, `assignment_hint_c_price`, `assignment_points`, `assignment_text_when_correct_answer`, `assignment_time_to_solve_seconds`, `assignment_time_to_solve_saying`, `assignment_created_by_user_id`, `assignment_created_by_ip`, `assignment_created_datetime`, `assignment_updated_by_user_id`, `assignment_updated_by_ip`, `assignment_updated_datetime`)
	 VALUES
(15, 3, 1, 'answer_a_question', 'Hvilken husnr og bokstav har treningssenteret p&aring; Sofiemyr Senter?', '', '32B', '32b', '32 B', '32_b', 20, NULL, NULL, 'text', '3...', 0.5, 'text', '32', 0.5, 'text', 'Bokstav', 0.5, 1, 'Det var helt riktig! Du kan dette med husnr og bokstaver!', 0, '', 1, '127.0.0.1', '2021-07-19 21:44:39', 1, '127.0.0.1', '2021-07-19 22:01:55'),
(16, 3, 2, 'take_a_picture_with_coordinates', 'Vi fortsetter med &aring; g&aring; til Kongeveien 12, 1412 Sofiemyr. Her vil du m&oslash;te p&aring; en snuplass og videre er en grusvei. F&oslash;lg grusveien til du kommer til et kryss. N&aring;r du kommer til krysset skal du g&aring; opp bakken. \r<br />\r<br />Ta et bilde fra toppen av bakken.', 'Kongeveien 12, 1412 Sofiemyr', '59.807473', '59807473', '10.823767', '10823767', 38, 12, 'metric', 'text', '', 0.5, 'text', '', 0.5, 'text', '', 0.5, 1, 'For et flott bilde!', 0, '', 1, '127.0.0.1', '2021-07-19 21:47:16', 1, '81.166.21.168', '2021-07-22 13:10:02'),
(17, 3, 3, 'answer_a_question', 'Du st&aring;r p&aring; toppen av bakken og skal f&oslash;lge en sti herfra mot nord. Hva heter stien?', '', 'Oldtidsveien', 'oldtidsveien', '', '', 20, NULL, NULL, '', 'Den er gammel', 0.5, 'text', 'Old...', 0.5, 'text', '', 0.5, 1, 'Helt riktig! Stien heter oldtidsveien.', 0, '', 1, '127.0.0.1', '2021-07-19 21:48:13', NULL, NULL, NULL),
(18, 3, 4, 'answer_a_question', 'Forsett nordover p&aring; stien til du kommer til brannt&aring;rnet. \r<br />P&aring; veien er det gravr&oslash;yser fra jernalderen, men i hvilket &aring;r startet jernalderen i Norge? Svar i tall, f.eks. &quot;X f.Kr&quot;', '', '500 f.Kr', '500_fkr', '500', '500', 20, NULL, NULL, '', 'https://no.wikipedia.org/wiki/Jernalderen', 0.5, 'text', '', 0.5, 'text', '', 0.5, 1, 'Jernalderen i Norge regnes som perioden ca. 500 fvt.&acirc;&euro;&ldquo;1050', 0, '', 1, '127.0.0.1', '2021-07-19 21:52:41', NULL, NULL, NULL),
(19, 3, 5, 'take_a_picture_with_coordinates', 'Ta et bilde av brannt&aring;rnet', '', '59.817544', '59817544', '10.824201', '10824201', 41, 13, 'metric', 'text', 'Lat 59.817544', 0.5, 'text', 'Lng 10.824201', 0.5, 'text', '', 0.5, 1, 'For et bilde!', 0, '', 1, '127.0.0.1', '2021-07-19 21:53:28', 1, '81.166.21.168', '2021-07-22 13:09:52'),
(20, 3, 6, 'answer_a_question', 'Hva st&aring;r det p&aring; det &oslash;verste trappetrinnet i brannt&aring;rnet?', '', 'Stopp St&oslash;yen', 'stopp_stoyen', '', '', 20, NULL, NULL, 'text', 'Stopp...', 0.5, 'text', 'G&aring; helt opp og les', 0.5, 'text', '', 0.5, 1, 'Helt riktig! Du er god!', 0, '', 1, '127.0.0.1', '2021-07-19 21:54:09', 1, '127.0.0.1', '2021-07-19 22:00:39'),
(21, 4, 1, 'answer_a_question', 'Hvilken husnr og bokstav har treningssenteret p&aring; Sofiemyr Senter?', '', '32B', '32b', '32 B', '32_b', 20, NULL, NULL, 'text', '3...', 0.5, 'text', '32', 0.5, 'text', 'Bokstav', 0.5, 1, 'Det var helt riktig! Du kan dette med husnr og bokstaver!', 0, '', 1, '127.0.0.1', '2021-07-19 21:44:39', 1, '127.0.0.1', '2021-07-19 22:01:55'),
(22, 4, 2, 'take_a_picture_with_coordinates', 'Vi fortsetter med &aring; g&aring; til Kongeveien 12, 1412 Sofiemyr. Her vil du m&oslash;te p&aring; en snuplass og videre er en grusvei. F&oslash;lg grusveien til du kommer til et kryss. N&aring;r du kommer til krysset skal du g&aring; opp bakken. \r<br />\r<br />Ta et bilde fra toppen av bakken.', 'Kongeveien 12, 1412 Sofiemyr', '59.807473', '59807473', '10.823767', '10823767', 30, 9, 'metric', 'text', '', 0.5, 'text', '', 0.5, 'text', '', 0.5, 1, 'For et flott bilde!', 0, '', 1, '127.0.0.1', '2021-07-19 21:47:16', 1, '81.166.21.168', '2021-07-22 13:07:23'),
(23, 4, 3, 'answer_a_question', 'Du st&aring;r p&aring; toppen av bakken og skal f&oslash;lge en sti herfra mot nord. Hva heter stien?', '', 'Oldtidsveien', 'oldtidsveien', '', '', 20, NULL, NULL, '', 'Den er gammel', 0.5, 'text', 'Old...', 0.5, 'text', '', 0.5, 1, 'Helt riktig! Stien heter oldtidsveien.', 0, '', 1, '127.0.0.1', '2021-07-19 21:48:13', NULL, NULL, NULL),
(24, 4, 4, 'answer_a_question', 'Forsett nordover p&aring; stien til du kommer til brannt&aring;rnet. \r<br />P&aring; veien er det gravr&oslash;yser fra jernalderen, men i hvilket &aring;r startet jernalderen i Norge? Svar i tall, f.eks. &quot;X f.Kr&quot;', '', '500 f.Kr', '500_fkr', '500', '500', 20, NULL, NULL, '', 'https://no.wikipedia.org/wiki/Jernalderen', 0.5, 'text', '', 0.5, 'text', '', 0.5, 1, 'Jernalderen i Norge regnes som perioden ca. 500 fvt.&acirc;&euro;&ldquo;1050', 0, '', 1, '127.0.0.1', '2021-07-19 21:52:41', NULL, NULL, NULL),
(25, 4, 5, 'take_a_picture_with_coordinates', 'Ta et bilde av brannt&aring;rnet', '', '59.817544', '59817544', '10.824201', '10824201', 41, 13, 'metric', 'text', 'Lat 59.817544', 0.5, 'text', 'Lng 10.824201', 0.5, 'text', '', 0.5, 1, 'For et bilde!', 0, '', 1, '127.0.0.1', '2021-07-19 21:53:28', 1, '81.166.21.168', '2021-07-22 13:09:29'),
(26, 4, 6, 'answer_a_question', 'Hva st&aring;r det p&aring; det &oslash;verste trappetrinnet i brannt&aring;rnet?', '', 'Stopp St&oslash;yen', 'stopp_stoyen', '', '', 20, NULL, NULL, 'text', 'Stopp...', 0.5, 'text', 'G&aring; helt opp og les', 0.5, 'text', '', 0.5, 1, 'Helt riktig! Du er god!', 0, '', 1, '127.0.0.1', '2021-07-19 21:54:09', 1, '127.0.0.1', '2021-07-19 22:00:39')
	")
	or die(mysqli_error());
	*/


	mysqli_query($link, "UPDATE $t_rebus_games_assignments SET assignment_radius_metric=30") or die(mysqli_error());


}
echo"
<!-- //games_assignments -->

";
?>