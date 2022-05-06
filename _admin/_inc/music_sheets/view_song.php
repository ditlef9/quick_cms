<?php
/**
*
* File: _admin/_inc/music_sheets/songs.php
* Version 
* Date 10:56 15.06.2019
* Copyright (c) 2019 Sindre Andre Ditlefsen
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

$t_music_songs_index				 = $mysqlPrefixSav . "music_songs_index";
$t_music_songs_categories			 = $mysqlPrefixSav . "music_songs_categories";
$t_music_songs_videoes				 = $mysqlPrefixSav . "music_songs_videoes";
$t_music_songs_lyrics				 = $mysqlPrefixSav . "music_songs_lyrics";
$t_music_songs_guitar_chords			 = $mysqlPrefixSav . "music_songs_guitar_chords";
$t_music_songs_guitar_tabs			 = $mysqlPrefixSav . "music_songs_guitar_tabs";
$t_music_songs_guitar_strumming			 = $mysqlPrefixSav . "music_songs_guitar_strumming";



/*- Variables ------------------------------------------------------------------------- */
$tabindex = 0;

if(isset($_GET['song_id'])){
	$song_id = $_GET['song_id'];
	$song_id = output_html($song_id);
}
else {
	$song_id = "";
}
if(isset($_GET['mode'])){
	$mode = $_GET['mode'];
	$mode = output_html($mode);
}
else {
	$mode = "";
}

// get song
$song_id_mysql = quote_smart($link, $song_id);
$query = "SELECT song_id, song_artist, song_artist_clean, song_title, song_title_clean, song_category_id, song_language, song_author_user_id, song_author_user_alias, song_author_user_image, song_created, song_created_saying, song_updated, song_updated_saying, song_unique_hits, song_unique_hits_ip_block FROM $t_music_songs_index WHERE song_id=$song_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_song_id, $get_current_song_artist, $get_current_song_artist_clean, $get_current_song_title, $get_current_song_title_clean, $get_current_song_category_id, $get_current_song_language, $get_current_song_author_user_id, $get_current_song_author_user_alias, $get_current_song_author_user_image, $get_current_song_created, $get_current_song_created_saying, $get_current_song_updated, $get_current_song_updated_saying, $get_current_song_unique_hits, $get_current_song_unique_hits_ip_block) = $row;

if($get_current_song_id == ""){
	echo"Not found ";
}
else{
	echo"
	<h1>$get_current_song_artist - $get_current_song_title</h1>

		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=music_sheets&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Music Sheets</a>
			&gt;
			<a href=\"index.php?open=music_sheets&amp;page=songs&amp;editor_language=$editor_language&amp;l=$l\">Songs</a>
			&gt;
			<a href=\"index.php?open=music_sheets&amp;page=songs&amp;action=view_song&amp;song_id=$get_current_song_id&amp;editor_language=$editor_language&amp;l=$l\">View</a>
			</p>
		<!-- //Where am I? -->


		<!-- Menu -->
			";
			include("_inc/music_sheets/edit_song_menu.php");
			echo"
		<!-- //Menu -->
		
		<!-- View lyrics -->
		";
			$course_is_present = 0;
			$query = "SELECT lyric_id, lyric_song_id, lyric_text, lyric_type, lyric_weight FROM $t_music_songs_lyrics WHERE lyric_song_id=$get_current_song_id ORDER BY lyric_weight ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_lyric_id, $get_lyric_song_id, $get_lyric_text, $get_lyric_type, $get_lyric_weight) = $row;

				echo"
				<p>[$get_lyric_type]<br />
				$get_lyric_text</p>
				";
			}
		echo"
		<!-- //View lyrics -->
		";
} // song found
?>