<?php
/**
*
* File: _admin/_inc/music_sheets/tables.php
* Version 11:55 30.12.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}
/*- Tables ---------------------------------------------------------------------------- */
$t_music_guitar_chords_index			 = $mysqlPrefixSav . "music_guitar_chords_index";
$t_music_guitar_chords_frets_fingers		 = $mysqlPrefixSav . "music_guitar_chords_frets_fingers";
$t_music_guitar_strumming_index			 = $mysqlPrefixSav . "music_guitar_strumming_index";

$t_music_songs_index				 = $mysqlPrefixSav . "music_songs_index";
$t_music_songs_categories			 = $mysqlPrefixSav . "music_songs_categories";
$t_music_songs_videoes				 = $mysqlPrefixSav . "music_songs_videoes";
$t_music_songs_lyrics				 = $mysqlPrefixSav . "music_songs_lyrics";
$t_music_songs_guitar_chords			 = $mysqlPrefixSav . "music_songs_guitar_chords";
$t_music_songs_guitar_tabs			 = $mysqlPrefixSav . "music_songs_guitar_tabs";
$t_music_songs_guitar_strumming			 = $mysqlPrefixSav . "music_songs_guitar_strumming";


echo"
<h1>Tables</h1>


<!-- Where am I? -->
	<p><b>You are here:</b><br />
	<a href=\"index.php?open=music_sheets&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Music Sheets</a>
	&gt;
	<a href=\"index.php?open=guitar&amp;page=tables&amp;editor_language=$editor_language&amp;l=$l\">Tables</a>
	</p>
<!-- //Where am I? -->

<!-- guitar chords -->
";

$query = "SELECT * FROM $t_music_guitar_chords_index LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_music_guitar_chords_index: $row_cnt</p>
	";
}
else{

	mysqli_query($link, "CREATE TABLE $t_music_guitar_chords_index(
	  chord_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(chord_id), 
	   chord_letter_lower VARCHAR(50), 
	   chord_letter_upper VARCHAR(50), 
	   chord_name VARCHAR(50), 
	   chord_name_clean VARCHAR(50), 
	   chord_is_draft INT, 
	   chord_a_head VARCHAR(50), 
	   chord_b_head VARCHAR(50), 
	   chord_c_head VARCHAR(50), 
	   chord_d_head VARCHAR(50), 
	   chord_e_head VARCHAR(50), 
	   chord_f_head VARCHAR(50), 
	   chord_image_file VARCHAR(200),
	   chord_sound_file VARCHAR(200),
	   chord_video_short_file VARCHAR(200),
	   chord_video_short_embedded VARCHAR(200),
	   chord_video_tutorial_file VARCHAR(200),
	   chord_video_tutorial_embedded VARCHAR(200),
	   chord_unique_hits INT, 
	   chord_unique_hits_ip_block TEXT, 
	   chord_difficulty INT,
	   chord_created_datetime DATETIME)")
	   or die(mysqli_error());	

		
	mysqli_query($link, "INSERT INTO $t_music_guitar_chords_index
	(`chord_id`, `chord_letter_lower`, `chord_letter_upper`, `chord_name`, `chord_name_clean`, `chord_is_draft`, `chord_a_head`, `chord_b_head`, `chord_c_head`, `chord_d_head`, `chord_e_head`, `chord_f_head`, `chord_image_file`, `chord_sound_file`, `chord_video_short_file`, `chord_video_short_embedded`, `chord_video_tutorial_file`, `chord_video_tutorial_embedded`, `chord_unique_hits`, `chord_unique_hits_ip_block`, `chord_difficulty`, `chord_created_datetime`) 
	VALUES 
(1, 'a', 'A', 'A', 'a', 1, 'X', 'O', '', '', '', 'O', 'a.jpg', 'a_sound.mp3', 'a_video_short.mp4', NULL, 'a_video_tutorial.mp4', NULL, 0, NULL, 1, '2019-06-16 19:56:35'),
(2, 'a', 'A', 'A7', 'a7', 1, 'X', 'O', '', 'O', '', 'O', 'a7.jpg', 'a7_sound.mp3', 'a7_video_short.mp4', NULL, 'a7_video_tutorial.mp4', NULL, 0, NULL, 1, '2019-06-16 20:04:41'),
(3, 'a', 'A', 'Am', 'am', 1, 'X', 'O', '', '', '', 'O', 'am.jpg', 'am_sound.mp3', 'am_video_short.mp4', NULL, 'am_video_tutorial.mp4', NULL, 0, NULL, 1, '2019-06-16 20:06:11'),
(4, 'a', 'A', 'Am7', 'am7', 1, 'X', 'O', '', '', '', 'O', 'am7.jpg', 'am7_sound.mp3', 'am7_video_short.mp4', NULL, 'am7_video_tutorial.mp4', NULL, 0, NULL, 2, '2019-06-16 20:06:59'),
(5, 'a', 'A', 'Amaj7', 'amaj7', 1, 'X', 'O', '', '', '', 'O', 'amaj7.jpg', 'amaj7_sound.mp3', 'amaj7_video_short.mp4', NULL, 'amaj7_video_tutorial.mp4', NULL, 0, NULL, 2, '2019-06-16 20:07:34'),
(6, 'b', 'B', 'B7', 'b7', 1, 'X', '', '', '', 'O', '', 'b7.jpg', 'b7_sound.mp3', 'b7_video_short.mp4', NULL, 'b7_video_tutorial.mp4', NULL, 0, NULL, 2, '2019-06-16 20:08:18'),
(7, 'b', 'B', 'Bm', 'bm', 1, 'X', '', '', '', '', 'X', 'bm.jpg', 'bm_sound.mp3', 'bm_video_short.mp4', NULL, 'bm_video_tutorial.mp4', NULL, 0, NULL, 2, '2019-06-16 20:08:56'),
(8, 'c', 'C', 'C', 'c', 1, 'X', '', '', 'O', '', 'O', 'c.jpg', 'c_sound.mp3', 'c_video_short.mp4', NULL, 'c_video_tutorial.mp4', NULL, 0, NULL, 1, '2019-06-16 20:09:29'),
(9, 'c', 'C', 'C7', 'c7', 1, 'X', '', '', '', '', 'O', 'c7.jpg', 'c7_sound.mp3', 'c7_video_short.mp4', NULL, 'c7_video_tutorial.mp4', NULL, 0, NULL, 2, '2019-06-16 20:09:59'),
(10, 'd', 'D', 'D', 'd', 1, 'X', 'X', 'O', '', '', '', 'd.jpg', 'd_sound.mp3', 'd_video_short.mp4', NULL, 'd_video_tutorial.mp4', NULL, 0, NULL, 1, '2019-06-16 20:10:28'),
(11, 'd', 'D', 'D7', 'd7', 1, 'X', 'X', 'O', '', '', '', 'd7.jpg', 'd7_sound.mp3', 'd7_video_short.mp4', NULL, 'd7_video_tutorial.mp4', NULL, 0, NULL, 2, '2019-06-16 20:10:52'),
(12, 'd', 'D', 'Dm', 'dm', 1, 'X', 'X', 'O', '', '', '', 'dm.jpg', 'dm_sound.mp3', 'dm_video_short.mp4', NULL, 'dm_video_tutorial.mp4', NULL, 0, NULL, 2, '2019-06-16 20:11:15'),
(13, 'd', 'D', 'Dm7', 'dm7', 1, 'X', 'X', 'X', '', '', '', 'dm7.jpg', 'dm7_sound.mp3', 'dm7_video_short.mp4', NULL, 'dm7_video_tutorial.mp4', NULL, 0, NULL, 2, '2019-06-16 20:11:46'),
(14, 'd', 'D', 'Dmaj7', 'dmaj7', 1, 'X', 'X', 'O', '', '', '', 'dmaj7.jpg', 'dmaj7_sound.mp3', 'dmaj7_video_short.mp4', NULL, 'dmaj7_video_tutorial.mp4', NULL, 0, NULL, 2, '2019-06-16 20:12:15'),
(15, 'e', 'E', 'E', 'e', 1, 'O', '', '', '', 'O', 'O', 'e.jpg', 'e_sound.mp3', 'e_video_short.mp4', NULL, 'e_video_tutorial.mp4', NULL, 0, NULL, 1, '2019-06-16 20:12:33'),
(16, 'e', 'E', 'E7', 'e7', 1, 'O', '', 'O', '', 'O', 'O', 'e7.jpg', 'e7_sound.mp3', 'e7_video_short.mp4', NULL, 'e7_video_tutorial.mp4', NULL, 0, NULL, 2, '2019-06-16 20:13:01'),
(17, 'e', 'E', 'Em', 'em', 1, 'O', '', '', 'O', 'O', 'O', 'em.jpg', 'em_sound.mp3', 'em_video_short.mp4', NULL, 'em_video_tutorial.mp4', NULL, 0, NULL, 1, '2019-06-16 20:13:29'),
(18, 'e', 'E', 'Em7', 'em7', 1, 'O', '', 'O', 'O', 'O', 'O', 'em7.jpg', 'em7_sound.mp3', 'em7_video_short.mp4', NULL, 'em7_video_tutorial.mp4', NULL, 0, NULL, 2, '2019-06-16 20:13:57'),
(19, 'f', 'F', 'F', 'f', 1, 'X', 'X', '', '', '', '', 'f.jpg', 'f_sound.mp3', 'f_video_short.mp4', NULL, 'f_video_tutorial.mp4', NULL, 0, NULL, 1, '2019-06-16 20:14:18'),
(20, 'f', 'F', 'Fmaj7', 'fmaj7', 1, '', '', '', '', '', 'O', 'fmaj7.jpg', 'fmaj7_sound.mp3', 'fmaj7_video_short.mp4', NULL, 'fmaj7_video_tutorial.mp4', NULL, 0, NULL, 2, '2019-06-16 20:14:43'),
(21, 'g', 'G', 'G', 'g', 1, '', '', 'O', 'O', 'O', '', 'g.jpg', 'g_sound.mp3', 'g_video_short.mp4', NULL, 'g_video_tutorial.mp4', NULL, 0, NULL, 1, '2019-06-16 20:15:03'),
(22, 'g', 'G', 'G7', 'g7', 1, '', '', 'O', 'O', 'O', '', 'g7.jpg', 'g7_sound.mp3', 'g7_video_short.mp4', NULL, 'g7_video_tutorial.mp4', NULL, 0, NULL, 2, '2019-06-16 20:15:26'),
(23, 'g', 'G', 'Gmaj7', 'gmaj7', 1, '', '', 'O', 'O', 'O', '', 'gmaj7.jpg', 'gmaj7_sound.mp3', 'gmaj7_video_short.mp4', NULL, 'gmaj7_video_tutorial.mp4', NULL, 0, NULL, 2, '2019-06-18 09:37:25'),
(24, 'f', 'F', 'F#m', 'f_m', 1, NULL, NULL, NULL, NULL, NULL, NULL, 'f_m.jpg', 'f_m_sound.mp3', 'f_m_video_short.mp4', NULL, 'f_m_video_tutorial.mp4', NULL, 0, NULL, 3, '2019-06-18 09:39:54'),
(25, 'b', 'B', 'Bm/A', 'bm_a', 1, 'X', 'O', 'O', '', 'O', '', 'bm_a.jpg', 'bm_a_sound.mp3', 'bm_a_video_short.mp4', NULL, 'bm_a_video_tutorial.mp4', NULL, 0, NULL, 3, '2019-06-18 09:41:49'),
(26, 'b', 'B', 'Bm/G#', 'bm_g_', 1, NULL, NULL, NULL, NULL, NULL, NULL, 'bm_g_.jpg', 'bm_g__sound.mp3', 'bm_g__video_short.mp4', NULL, 'bm_g__video_tutorial.mp4', NULL, 0, NULL, 3, '2019-06-18 09:42:37'),
(27, 'f', 'F', 'F#', 'f_', 1, NULL, NULL, NULL, NULL, NULL, NULL, 'f_.jpg', 'f__sound.mp3', 'f__video_short.mp4', NULL, 'f__video_tutorial.mp4', NULL, 0, NULL, 3, '2019-06-18 09:43:41'),
(28, 'a', 'A', 'A/C#', 'a_c_', 1, 'X', '', '', '', '', '', 'a_c_.jpg', 'a_c__sound.mp3', 'a_c__video_short.mp4', NULL, 'a_c__video_tutorial.mp4', NULL, 0, NULL, 3, '2019-06-18 09:44:59')")
	or die(mysqli_error($link));

}
echo"
<!-- //guitar chords -->


<!-- guitar chords frets fingers -->
";

$query = "SELECT * FROM $t_music_guitar_chords_frets_fingers LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_music_guitar_chords_frets_fingers: $row_cnt</p>
	";
}
else{

	mysqli_query($link, "CREATE TABLE $t_music_guitar_chords_frets_fingers(
	  fretfinger_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(fretfinger_id), 
	   fretfinger_chord_id INT, 
	   fretfinger_fret_no INT, 
	   fretfinger_string_no INT, 
	   fretfinger_finger_no INT)")
	   or die(mysqli_error());

		
	mysqli_query($link, "INSERT INTO $t_music_guitar_chords_frets_fingers
	(`fretfinger_id`, `fretfinger_chord_id`, `fretfinger_fret_no`, `fretfinger_string_no`, `fretfinger_finger_no`) 
	VALUES 
(NULL, 7, 2, 2, 1),
(NULL, 1, 2, 3, 1),
(NULL, 1, 2, 4, 2),
(NULL, 5, 2, 3, 2),
(NULL, 2, 2, 5, 2),
(NULL, 2, 2, 3, 1),
(NULL, 3, 2, 3, 2),
(NULL, 3, 1, 5, 1),
(NULL, 3, 2, 4, 3),
(NULL, 4, 1, 5, 1),
(NULL, 4, 2, 3, 2),
(NULL, 5, 1, 4, 1),
(NULL, 6, 2, 6, 4),
(NULL, 6, 2, 4, 3),
(NULL, 6, 2, 2, 2),
(NULL, 6, 1, 3, 1),
(NULL, 5, 2, 5, 3),
(NULL, 1, 2, 5, 3),
(NULL, 7, 3, 5, 2),
(NULL, 7, 4, 3, 3),
(NULL, 7, 4, 4, 4),
(NULL, 8, 1, 5, 1),
(NULL, 8, 2, 3, 2),
(NULL, 8, 3, 2, 3),
(NULL, 9, 1, 5, 1),
(NULL, 9, 2, 3, 2),
(NULL, 9, 3, 2, 3),
(NULL, 9, 3, 4, 4),
(NULL, 10, 2, 4, 1),
(NULL, 10, 2, 6, 2),
(NULL, 10, 3, 5, 3),
(NULL, 11, 1, 5, 1),
(NULL, 11, 2, 4, 2),
(NULL, 11, 2, 6, 3),
(NULL, 12, 1, 6, 1),
(NULL, 12, 2, 4, 2),
(NULL, 12, 3, 5, 3),
(NULL, 13, 1, 6, 1),
(NULL, 13, 1, 5, 1),
(NULL, 13, 2, 4, 2),
(NULL, 14, 2, 4, 1),
(NULL, 14, 2, 5, 2),
(NULL, 14, 2, 6, 3),
(NULL, 16, 1, 4, 1),
(NULL, 15, 2, 2, 2),
(NULL, 15, 2, 3, 3),
(NULL, 15, 1, 4, 1),
(NULL, 16, 2, 2, 2),
(NULL, 17, 2, 2, 2),
(NULL, 17, 2, 3, 3),
(NULL, 18, 2, 2, 2),
(NULL, 19, 1, 5, 1),
(NULL, 19, 1, 6, 1),
(NULL, 19, 2, 4, 2),
(NULL, 19, 3, 3, 3),
(NULL, 20, 1, 5, 1),
(NULL, 20, 2, 4, 2),
(NULL, 20, 3, 3, 3),
(NULL, 21, 2, 2, 1),
(NULL, 21, 3, 1, 2),
(NULL, 21, 3, 6, 3),
(NULL, 22, 1, 6, 1),
(NULL, 22, 2, 2, 2),
(NULL, 22, 3, 1, 3),
(NULL, 24, 2, 1, 1),
(NULL, 23, 2, 6, 1),
(NULL, 23, 3, 1, 3),
(NULL, 23, 2, 2, 2),
(NULL, 24, 2, 2, 1),
(NULL, 24, 2, 3, 1),
(NULL, 24, 2, 4, 1),
(NULL, 24, 2, 5, 1),
(NULL, 24, 2, 6, 1),
(NULL, 24, 4, 2, 2),
(NULL, 24, 4, 3, 3),
(NULL, 25, 2, 6, 1),
(NULL, 25, 3, 4, 3),
(NULL, 26, 5, 2, 2),
(NULL, 26, 6, 5, 3),
(NULL, 26, 6, 6, 3),
(NULL, 27, 2, 1, 1),
(NULL, 27, 2, 2, 1),
(NULL, 27, 2, 3, 1),
(NULL, 27, 2, 4, 1),
(NULL, 27, 2, 5, 1),
(NULL, 27, 2, 6, 1),
(NULL, 27, 3, 4, 2),
(NULL, 27, 4, 2, 3),
(NULL, 27, 4, 3, 4),
(NULL, 28, 2, 3, 1),
(NULL, 28, 2, 4, 1),
(NULL, 28, 2, 5, 1),
(NULL, 28, 2, 6, 1),
(NULL, 28, 4, 2, 3),
(NULL, 28, 5, 6, 4)")
	or die(mysqli_error($link));
}
echo"
<!-- //guitar chords frets fingers -->




<!-- guitar strumming index -->
";

$query = "SELECT * FROM $t_music_guitar_strumming_index LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_music_guitar_strumming_index: $row_cnt</p>
	";
}
else{

	mysqli_query($link, "CREATE TABLE $t_music_guitar_strumming_index (
	  strumming_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(strumming_id), 
	   strumming_title VARCHAR(200), 
	   strumming_type VARCHAR(200), 
	   strumming_data TEXT,
	   strumming_image_file VARCHAR(200),
	   strumming_sound_file VARCHAR(200),
	   strumming_video_short_file VARCHAR(200),
	   strumming_video_short_embedded VARCHAR(200),
	   strumming_video_tutorial_file VARCHAR(200),
	   strumming_video_tutorial_embedded VARCHAR(200),
	   strumming_unique_hits INT, 
	   strumming_unique_hits_ip_block TEXT, 
	   strumming_difficulty INT,
	   strumming_created_datetime DATETIME)")
	   or die(mysqli_error());	



}
echo"
<!-- //guitar strumming index -->


<!-- songs categories -->

<!-- songs index -->
";

$query = "SELECT * FROM $t_music_songs_index LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_music_songs_index: $row_cnt</p>
	";
}
else{

	mysqli_query($link, "CREATE TABLE $t_music_songs_index(
	  song_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(song_id), 
	   song_artist VARCHAR(200), 
	   song_artist_clean VARCHAR(200), 
	   song_title VARCHAR(200), 
	   song_title_clean VARCHAR(200), 
	   song_category_id INT, 
	   song_language VARCHAR(200), 
	   song_author_user_id INT,
	   song_author_user_alias VARCHAR(200), 
	   song_author_user_image VARCHAR(200), 
	   song_created DATETIME,
	   song_created_saying VARCHAR(50), 
	   song_updated DATETIME,
	   song_updated_saying VARCHAR(50), 
	   song_unique_hits INT, 
	   song_unique_hits_ip_block TEXT)")
	   or die(mysqli_error());	

		
	mysqli_query($link, "INSERT INTO $t_music_songs_index
	(`song_id`, `song_artist`, `song_artist_clean`, `song_title`, `song_title_clean`, `song_category_id`, `song_language`, `song_author_user_id`, `song_author_user_alias`, `song_author_user_image`, `song_created`, `song_created_saying`, `song_updated`, `song_updated_saying`, `song_unique_hits`, `song_unique_hits_ip_block`)
	VALUES 
	(1, 'Elton John', 'elton_john', 'Your Song', 'your_song', 6, 'English', 1, 'Administrator', '', '2019-06-23 18:21:44', '23 Jun 2019', '2019-06-23 18:21:44', '23 Jun 2019', 0, NULL),
	(2, 'Pink Floyd', 'pink_floyd', 'Another Brick In The Wall', 'another_brick_in_the_wall', 4, 'English', 1, 'Administrator', '', '2019-06-23 18:22:28', '23 Jun 2019', '2019-06-23 18:22:28', '23 Jun 2019', 0, NULL),
	(3, 'Eric Clapton', 'eric_clapton', 'Wonderful Tonight', 'wonderful_tonight', 6, 'English', 1, 'Administrator', '', '2019-06-23 18:22:43', '23 Jun 2019', '2019-06-23 18:22:43', '23 Jun 2019', 0, NULL)
	")
	or die(mysqli_error($link));

}
echo"
<!-- //songs index -->


<!-- songs categories -->
";

$query = "SELECT * FROM $t_music_songs_categories LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_music_songs_categories: $row_cnt</p>
	";
}
else{

	mysqli_query($link, "CREATE TABLE $t_music_songs_categories(
	  category_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(category_id), 
	   category_title VARCHAR(200), 
	   category_title_clean VARCHAR(200), 
	   category_songs_count INT,
	   category_unique_hits INT, 
	   category_unique_hits_ip_block TEXT)")
	   or die(mysqli_error());	

		
	mysqli_query($link, "INSERT INTO $t_music_songs_categories
	(category_id, category_title, category_title_clean, category_songs_count, category_unique_hits) 
	VALUES 
	(NULL, 'Rock', 'rock', '0', '0'),
	(NULL, 'Pop', 'pop', '0', '0'),
	(NULL, 'Classical', 'classical', '0', '0'),
	(NULL, 'Classic rock', 'classic_rock', '0', '0'),
	(NULL, 'Country', 'country', '0', '0'),
	(NULL, 'Love songs', 'love_songs', '0', '0')")
	or die(mysqli_error($link));

}
echo"
<!-- //songs categories -->



<!-- songs lyrics -->
";

$query = "SELECT * FROM $t_music_songs_lyrics LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_music_songs_lyrics: $row_cnt</p>
	";
}
else{

	mysqli_query($link, "CREATE TABLE $t_music_songs_lyrics(
	  lyric_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(lyric_id), 
	   lyric_song_id INT,
	   lyric_text TEXT,
	   lyric_type VARCHAR(200), 
	   lyric_weight INT)")
	   or die(mysqli_error());	

		
	mysqli_query($link, "INSERT INTO $t_music_songs_lyrics
	(`lyric_id`, `lyric_song_id`, `lyric_text`, `lyric_type`, `lyric_weight`) 
	VALUES 
	(NULL, 3, 'I feel wonderful because I see the love light in your eyes,\r<br />And the wonder of it all is that you just don&#039;t realize,\r<br />How much I love you!\r<br />', 'Bridge', 3),
	(NULL, 3, '', 'Instrumental', 1),
	(NULL, 3, 'It&#039;s late in the evening; she&#039;s wondering what clothes to wear\r<br />She puts on her make-up and brushes her long blonde hair\r<br />And then she asks me, &quot;Do I look all right?&quot;\r<br />And I say, &quot;Yes, you look wonderful tonight&quot;', 'Verse', 0),
	(NULL, 3, 'We go to a party and everyone turns to see\r<br />This beautiful lady that&#039;s walking around with me\r<br />And then she asks me, &quot;Do you feel all right?&quot;\r<br />And I say, &quot;Yes, I feel wonderful tonight&quot;', 'Verse', 2),
	(NULL, 3, '', 'Instrumental', 4),
	(NULL, 3, 'It&#039;s time to go home now, and I&#039;ve got an aching head\r<br />So I give her the carkeys, and she helps me to bed.\r<br />And then I tell her, as I turn off the light:\r<br />I say: &quot;My darling, you are wonderful tonight! \r<br />\r<br />\r<br />', 'Verse', 5),
	(NULL, 3, '', 'Outro', 6)")
	or die(mysqli_error($link));

}
echo"
<!-- //songs lyrics -->

<!-- songs videoes -->
";

$query = "SELECT * FROM $t_music_songs_videoes LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_music_songs_videoes: $row_cnt</p>
	";
}
else{

	mysqli_query($link, "CREATE TABLE $t_music_songs_videoes(
	  video_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(video_id), 
	   video_song_id INT,
	   video_embedded VARCHAR(200),
	   video_lenght_minutes INT,
	   video_lenght_seconds INT,
	   video_lenght_total_seconds INT,
	   video_service VARCHAR(200),
	   video_type VARCHAR(200), 
	   video_weight INT)")
	   or die(mysqli_error());	

		
	mysqli_query($link, "INSERT INTO $t_music_songs_videoes
	(`lyric_id`, `lyric_song_id`, `lyric_text`, `lyric_type`, `lyric_weight`) 
	VALUES 
	(NULL, 3, 'I feel wonderful because I see the love light in your eyes,\r<br />And the wonder of it all is that you just don&#039;t realize,\r<br />How much I love you!\r<br />', 'Bridge', 3),
	(NULL, 3, '', 'Instrumental', 1),
	(NULL, 3, 'It&#039;s late in the evening; she&#039;s wondering what clothes to wear\r<br />She puts on her make-up and brushes her long blonde hair\r<br />And then she asks me, &quot;Do I look all right?&quot;\r<br />And I say, &quot;Yes, you look wonderful tonight&quot;', 'Verse', 0),
	(NULL, 3, 'We go to a party and everyone turns to see\r<br />This beautiful lady that&#039;s walking around with me\r<br />And then she asks me, &quot;Do you feel all right?&quot;\r<br />And I say, &quot;Yes, I feel wonderful tonight&quot;', 'Verse', 2),
	(NULL, 3, '', 'Instrumental', 4),
	(NULL, 3, 'It&#039;s time to go home now, and I&#039;ve got an aching head\r<br />So I give her the carkeys, and she helps me to bed.\r<br />And then I tell her, as I turn off the light:\r<br />I say: &quot;My darling, you are wonderful tonight! \r<br />\r<br />\r<br />', 'Verse', 5),
	(NULL, 3, '', 'Outro', 6)")
	or die(mysqli_error($link));

}
echo"
<!-- //songs videoes -->


<!-- songs guitar_chords -->
";

$query = "SELECT * FROM $t_music_songs_guitar_chords LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_music_songs_guitar_chords: $row_cnt</p>
	";
}
else{

	mysqli_query($link, "CREATE TABLE $t_music_songs_guitar_chords(
	  song_guitar_chord_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(song_guitar_chord_id), 
	   song_guitar_chord_song_id INT,
	   song_guitar_chord_lyric_id INT,
	   song_guitar_chord_line_no INT,
	   song_guitar_chord_word_no INT,
	   song_guitar_chord_chord_id INT,
	   song_guitar_chord_chord_name VARCHAR(200))")
	   or die(mysqli_error());	
}
echo"
<!-- //songs guitar_chords -->
<!-- songs guitar strumming -->
";

$query = "SELECT * FROM $t_music_songs_guitar_strumming LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_music_songs_guitar_strumming: $row_cnt</p>
	";
}
else{

	mysqli_query($link, "CREATE TABLE $t_music_songs_guitar_strumming(
	  strumming_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(strumming_id), 
	   strumming_song_id INT,
	   strumming_title VARCHAR(200),
	   strumming_song_part VARCHAR(200),
	   strumming_type VARCHAR(200),
	   strumming_pattern TEXT,
	   strumming_tempo VARCHAR(200),
	   strumming_note_length VARCHAR(200),
	   strumming_audio_file VARCHAR(200),
	   strumming_video_embedded VARCHAR(200),
	   strumming_video_embedded_service VARCHAR(200),
	   strumming_video_file VARCHAR(200),
	   strumming_weight INT)")
	   or die(mysqli_error());	
}
echo"
<!-- //songs guitar strumming -->

";


?>