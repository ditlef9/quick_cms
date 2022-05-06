<?php
/**
*
* File: _admin/_inc/music_sheets/edit_song_guitar_strumming_pattern.php
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



/*- Variables ---------------------------------------------------"--------------------- */
$tabindex = 0;

if(isset($_GET['song_id'])){
	$song_id = $_GET['song_id'];
	$song_id = output_html($song_id);
}
else {
	$song_id = "";
}


if($action == ""){
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
		if($process == "1"){
			
			
			$url = "index.php?open=music_sheets&page=edit_song_guitar_cords&song_id=$get_current_song_id&editor_language=$editor_language&l=$l&ft=success&fm=changes_saved";
			header("Location: $url");
			exit;
		} // process == 1
		echo"
		<h1>$get_current_song_artist - $get_current_song_title</h1>

		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=music_sheets&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Music Sheets</a>
			&gt;
			<a href=\"index.php?open=music_sheets&amp;page=songs&amp;editor_language=$editor_language&amp;l=$l\">Songs</a>
			&gt;
			<a href=\"index.php?open=music_sheets&amp;page=edit_song_guitar_cords&amp;song_id=$get_current_song_id&amp;editor_language=$editor_language&amp;l=$l\">Guitar cords</a>
			&gt;
			<a href=\"index.php?open=music_sheets&amp;page=edit_song_guitar_strumming_pattern&amp;song_id=$get_current_song_id&amp;editor_language=$editor_language&amp;l=$l\">Add strumming pattern</a>
			</p>
		<!-- //Where am I? -->



		<!-- Menu -->
			";
			include("_inc/music_sheets/edit_song_menu.php");
			echo"
		<!-- //Menu -->

		<!-- Feedback -->
			";
			if($ft != ""){
				if($fm == "changes_saved"){
					$fm = "$l_changes_saved";
				}
				else{
					$fm = ucfirst($fm);
				}
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
		<!-- //Feedback -->

		<!-- Strumming patterns -->
			<p>
			<a href=\"index.php?open=music_sheets&amp;page=edit_song_guitar_strumming_pattern&amp;action=new_strumming_pattern&amp;song_id=$get_current_song_id&amp;editor_language=$editor_language&amp;l=$l\">New strumming pattern</a>
			</p>
		<!-- //Strumming patterns -->
		";
	} // song found
} // edit_song_guitar chords
elseif($action == "new_strumming_pattern"){
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
			<a href=\"index.php?open=music_sheets&amp;page=edit_song_guitar_cords&amp;song_id=$get_current_song_id&amp;editor_language=$editor_language&amp;l=$l\">Guitar cords</a>
			&gt;
			<a href=\"index.php?open=music_sheets&amp;page=edit_song_guitar_strumming_pattern&amp;song_id=$get_current_song_id&amp;editor_language=$editor_language&amp;l=$l\">Add strumming pattern</a>
			</p>
		<!-- //Where am I? -->


		<!-- Menu -->
			";
			include("_inc/music_sheets/edit_song_menu.php");
			echo"
		<!-- //Menu -->

		<!-- Feedback -->
			";
			if($ft != ""){
				if($fm == "changes_saved"){
					$fm = "$l_changes_saved";
				}
				else{
					$fm = ucfirst($fm);
				}
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
		<!-- //Feedback -->
	
		<!-- New strumming pattern -->
			<form method=\"post\" action=\"index.php?open=music_sheets&amp;page=edit_song_guitar_strumming_pattern&amp;mode=new_strumming_pattern&amp;song_id=$get_current_song_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
			
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_artist\"]').focus();
			});
			</script>

			<p>Type:<br />
			<select name=\"inp_type\" tabindex=\""; $tabindex=$tabindex+1;echo"$tabindex\">
				<option value=\"finger_picking\">Finger picking</option>
				<option value=\"strumming\">Strumming</option>
			</select></p>

			<p>Song part:<br />
			<input type=\"text\" name=\"inp_song_part\" value=\"\" tabindex=\""; $tabindex=$tabindex+1;echo"$tabindex\" />
			</p>

			<p>Pattern:<br />
			<input type=\"text\" name=\"inp_song_part\" value=\"\" tabindex=\""; $tabindex=$tabindex+1;echo"$tabindex\" />
			</p>

			<p>Tempo (bpm):<br />
			<input type=\"text\" name=\"inp_tempo\" value=\"\" tabindex=\""; $tabindex=$tabindex+1;echo"$tabindex\" />
			</p>

			<p>Note length:<br />
			<input type=\"text\" name=\"inp_note_length\" value=\"\" tabindex=\""; $tabindex=$tabindex+1;echo"$tabindex\" />
			</p>

			<p><input type=\"submit\" value=\"Create\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
			</form>
		<!-- //New strumming pattern -->
		";
	} // song found
} // strumming patterns
?>