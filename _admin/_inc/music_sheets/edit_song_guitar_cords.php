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



/*- Variables ---------------------------------------------------"--------------------- */
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
			// Delete old
			$result_delete = mysqli_query($link, "DELETE FROM $t_music_songs_guitar_chords WHERE song_guitar_chord_song_id=$get_current_song_id") or die(mysqli_error($link));


			$xycounter = 0;
			$query = "SELECT lyric_id, lyric_song_id, lyric_text, lyric_type, lyric_weight FROM $t_music_songs_lyrics WHERE lyric_song_id=$get_current_song_id ORDER BY lyric_weight ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_lyric_id, $get_lyric_song_id, $get_lyric_text, $get_lyric_type, $get_lyric_weight) = $row;
				$lines = explode("<br />", $get_lyric_text);

				
				for($x=0;$x<sizeof($lines);$x++){
					$words = explode(" ", $lines[$x]);
					

					for($y=0;$y<sizeof($words);$y++){
						$inp_chord_name = $_POST["inp_chord_name$xycounter"];
						$inp_chord_name = output_html($inp_chord_name);
						$inp_chord_name_mysql = quote_smart($link, $inp_chord_name);
						if($inp_chord_name == "0"){
							$inp_chord_name = "";
						}


						if($inp_chord_name != ""){
							// Get chord id
							$query_c = "SELECT chord_id FROM $t_music_guitar_chords_index WHERE chord_name=$inp_chord_name_mysql";
							$result_c = mysqli_query($link, $query_c);
							$row_c = mysqli_fetch_row($result_c);
							list($get_chord_id) = $row_c;
							if($get_chord_id == ""){
								$get_chord_id = "0";
							}

						
							mysqli_query($link, "INSERT INTO $t_music_songs_guitar_chords
							(song_guitar_chord_id, song_guitar_chord_song_id, song_guitar_chord_lyric_id, song_guitar_chord_line_no, song_guitar_chord_word_no, song_guitar_chord_chord_id, song_guitar_chord_chord_name) 
							VALUES 
							(NULL, $get_current_song_id, $get_lyric_id, $x, $y, $get_chord_id, $inp_chord_name_mysql)")
							or die(mysqli_error($link));
						}

						$xycounter++;
					} // words
				} // lines
			} // while
			
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
			<a href=\"index.php?open=music_sheets&amp;page=view_song&amp;song_id=$get_current_song_id&amp;editor_language=$editor_language&amp;l=$l\">Edit</a>
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
			<a href=\"index.php?open=music_sheets&amp;page=edit_song_guitar_strumming_pattern&amp;song_id=$get_current_song_id&amp;editor_language=$editor_language&amp;l=$l\">Add strumming pattern</a>
			</p>
		<!-- //Strumming patterns -->

		<!-- Form -->
			<form method=\"post\" action=\"index.php?open=music_sheets&amp;page=edit_song_guitar_cords&amp;mode=save&amp;song_id=$get_current_song_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
			";

			// Fetch all
			$xycounter = 0;
			$query = "SELECT lyric_id, lyric_song_id, lyric_text, lyric_type, lyric_weight FROM $t_music_songs_lyrics WHERE lyric_song_id=$get_current_song_id ORDER BY lyric_weight ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_lyric_id, $get_lyric_song_id, $get_lyric_text, $get_lyric_type, $get_lyric_weight) = $row;
				$lines = explode("<br />", $get_lyric_text);

				echo"
				<p>[$get_lyric_type]</p>
				";
				for($x=0;$x<sizeof($lines);$x++){
					$words = explode(" ", $lines[$x]);
					echo"
					<table>
					 <tr>
					";

					for($y=0;$y<sizeof($words);$y++){
						// Look for data
						$query_c = "SELECT song_guitar_chord_id, song_guitar_chord_chord_id, song_guitar_chord_chord_name FROM $t_music_songs_guitar_chords WHERE song_guitar_chord_song_id=$get_current_song_id AND song_guitar_chord_lyric_id=$get_lyric_id AND song_guitar_chord_line_no=$x AND song_guitar_chord_word_no=$y";
						$result_c = mysqli_query($link, $query_c);
						$row_c = mysqli_fetch_row($result_c);
						list($get_song_guitar_chord_id, $get_song_guitar_chord_chord_id, $get_song_guitar_chord_chord_name) = $row_c;
						
						echo"
						  <td style=\"padding-right: 3px;\">
							<input type=\"text\" name=\"inp_chord_name$xycounter\" value=\"$get_song_guitar_chord_chord_name\" size=\"2\" style=\"border: #fff 1px solid;border-bottom: #ccc 1px dashed;font-weight:bold;\" />
						  </td>
						";
						$xycounter++;
					} // words
					echo"
					 </tr>

					 <tr>
					";
					for($y=0;$y<sizeof($words);$y++){
						echo"
						  <td style=\"padding-right: 3px;\">
							<span>$words[$y]</span>
						  </td>
						";
					} // words
					echo"
					 </tr>
					</table>
					";
					
				} // lines
				echo"
				";

			} // while
			echo"

			<p>
			<input type=\"submit\" value=\"Save\" class=\"btn_default\" />
			</p>

			</form>
		<!-- //Form -->
		";
	} // song found
} // edit_song_guitar chords
elseif($action == "edit_song_guitar_strumming_pattern"){
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
				<a href=\"index.php?open=music_sheets&amp;page=view_song&amp;song_id=$get_current_song_id&amp;editor_language=$editor_language&amp;l=$l\">Edit</a>
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
		";
		if($mode == ""){

			echo"
			<!-- Strumming patterns -->
			<p>
			<a href=\"index.php?open=music_sheets&amp;page=edit_song_guitar_strumming_pattern&amp;mode=new_strumming_pattern&amp;song_id=$get_current_song_id&amp;editor_language=$editor_language&amp;l=$l\">Add strumming pattern</a>
			</p>
			<!-- //Strumming patterns -->
			";	
		} // mode == ""
		elseif($mode == "new_strumming_pattern"){
			echo"
			<form method=\"post\" action=\"index.php?open=music_sheets&amp;page=edit_song_guitar_strumming_pattern&amp;mode=new_strumming_pattern&amp;song_id=$get_current_song_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
			
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_artist\"]').focus();
			});
			</script>

			<p>Artist:<br />
			<input type=\"text\" name=\"inp_artist\" value=\"\" size=\"25\" tabindex=\""; $tabindex=$tabindex+1;echo"$tabindex\" /></p>

			<p><input type=\"submit\" value=\"Create\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
			</form>
			";
		} // mode == "new_strumming_pattern"
	} // song found
} // strumming patterns
?>