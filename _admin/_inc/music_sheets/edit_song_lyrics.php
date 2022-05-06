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
			if($mode == "add_intro" OR $mode == "add_verse" OR $mode == "add_course" OR $mode == "add_bridge" OR $mode == "add_solo" OR $mode == "add_instrumental" OR $mode == "add_outro"){
				$inp_type = "Intro";
				if($mode == "add_verse"){
					$inp_type = "Verse";
				}
				elseif($mode == "add_course"){
					$inp_type = "Course";
				}
				elseif($mode == "add_bridge"){
					$inp_type = "Bridge";
				}
				elseif($mode == "add_solo"){
					$inp_type = "Solo";
				}
				elseif($mode == "add_instrumental"){
					$inp_type = "Instrumental";
				}
				elseif($mode == "add_outro"){
					$inp_type = "Outro";
				}


				mysqli_query($link, "INSERT INTO $t_music_songs_lyrics
				(lyric_id, lyric_song_id, lyric_text, lyric_type, lyric_weight) 
				VALUES 
				(NULL, $get_current_song_id, '', '$inp_type', '99')") or die(mysqli_error($link));
			} // add intro etc
			elseif($mode == "save"){

				$query = "SELECT lyric_id, lyric_song_id, lyric_text, lyric_type, lyric_weight FROM $t_music_songs_lyrics WHERE lyric_song_id=$get_current_song_id ORDER BY lyric_weight ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_lyric_id, $get_lyric_song_id, $get_lyric_text, $get_lyric_type, $get_lyric_weight) = $row;

					$inp_text = $_POST["inp_text_$get_lyric_id"];
					$inp_text = output_html($inp_text);
					$inp_text_mysql = quote_smart($link, $inp_text);

					$result_update = mysqli_query($link, "UPDATE $t_music_songs_lyrics SET lyric_text=$inp_text_mysql WHERE lyric_id=$get_lyric_id");
				}


			} // save
			elseif($mode == "move_lyric_down"){
				if(isset($_GET['lyric_id'])){
					$lyric_id = $_GET['lyric_id'];
					$lyric_id = output_html($lyric_id);
				}
				else {
					$lyric_id = "";
				}
				$lyric_id_mysql = quote_smart($link, $lyric_id);

				// Fetch current
				$query = "SELECT lyric_id, lyric_song_id, lyric_text, lyric_type, lyric_weight FROM $t_music_songs_lyrics WHERE lyric_id=$lyric_id_mysql AND lyric_song_id=$get_current_song_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_current_lyric_id, $get_current_lyric_song_id, $get_current_lyric_text, $get_current_lyric_type, $get_current_lyric_weight) = $row;

				if($get_current_lyric_id == ""){
					$url = "index.php?open=music_sheets&page=edit_song_lyrics&song_id=$get_current_song_id&editor_language=$editor_language&l=$l&ft=error&fm=lyric_not_found";
					header("Location: $url");
					exit;
				}

				// Fetch change
				$lyric_weight_change = $get_current_lyric_weight+1;
				$query = "SELECT lyric_id, lyric_song_id, lyric_text, lyric_type, lyric_weight FROM $t_music_songs_lyrics WHERE lyric_song_id=$get_current_song_id AND lyric_weight=$lyric_weight_change";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_change_lyric_id, $get_change_lyric_song_id, $get_change_lyric_text, $get_change_lyric_type, $get_change_lyric_weight) = $row;

				if($get_change_lyric_id == ""){
					$url = "index.php?open=music_sheets&page=edit_song_lyrics&song_id=$get_current_song_id&editor_language=$editor_language&l=$l&ft=error&fm=change_not_found";
					header("Location: $url");
					exit;
				}
				
				// Swap
				$result_update = mysqli_query($link, "UPDATE $t_music_songs_lyrics SET lyric_weight=$lyric_weight_change WHERE lyric_id=$get_current_lyric_id");
				$result_update = mysqli_query($link, "UPDATE $t_music_songs_lyrics SET lyric_weight=$get_current_lyric_weight WHERE lyric_id=$get_change_lyric_id");
			}
			elseif($mode == "move_lyric_up"){
				if(isset($_GET['lyric_id'])){
					$lyric_id = $_GET['lyric_id'];
					$lyric_id = output_html($lyric_id);
				}
				else {
					$lyric_id = "";
				}
				$lyric_id_mysql = quote_smart($link, $lyric_id);

				// Fetch current
				$query = "SELECT lyric_id, lyric_song_id, lyric_text, lyric_type, lyric_weight FROM $t_music_songs_lyrics WHERE lyric_id=$lyric_id_mysql AND lyric_song_id=$get_current_song_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_current_lyric_id, $get_current_lyric_song_id, $get_current_lyric_text, $get_current_lyric_type, $get_current_lyric_weight) = $row;

				if($get_current_lyric_id == ""){
					$url = "index.php?open=music_sheets&page=edit_song_lyrics&song_id=$get_current_song_id&editor_language=$editor_language&l=$l&ft=error&fm=lyric_not_found";
					header("Location: $url");
					exit;
				}

				// Fetch change
				$lyric_weight_change = $get_current_lyric_weight-1;
				$query = "SELECT lyric_id, lyric_song_id, lyric_text, lyric_type, lyric_weight FROM $t_music_songs_lyrics WHERE lyric_song_id=$get_current_song_id AND lyric_weight=$lyric_weight_change";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_change_lyric_id, $get_change_lyric_song_id, $get_change_lyric_text, $get_change_lyric_type, $get_change_lyric_weight) = $row;

				if($get_change_lyric_id == ""){
					$url = "index.php?open=music_sheets&page=edit_song_lyrics&song_id=$get_current_song_id&editor_language=$editor_language&l=$l&ft=error&fm=change_not_found";
					header("Location: $url");
					exit;
				}
				
				// Swap
				$result_update = mysqli_query($link, "UPDATE $t_music_songs_lyrics SET lyric_weight=$lyric_weight_change WHERE lyric_id=$get_current_lyric_id");
				$result_update = mysqli_query($link, "UPDATE $t_music_songs_lyrics SET lyric_weight=$get_current_lyric_weight WHERE lyric_id=$get_change_lyric_id");
			}
			elseif($mode == "delete_lyric"){
				if(isset($_GET['lyric_id'])){
					$lyric_id = $_GET['lyric_id'];
					$lyric_id = output_html($lyric_id);
				}
				else {
					$lyric_id = "";
				}
				$lyric_id_mysql = quote_smart($link, $lyric_id);

				// Fetch current
				$query = "SELECT lyric_id, lyric_song_id, lyric_text, lyric_type, lyric_weight FROM $t_music_songs_lyrics WHERE lyric_id=$lyric_id_mysql AND lyric_song_id=$get_current_song_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_current_lyric_id, $get_current_lyric_song_id, $get_current_lyric_text, $get_current_lyric_type, $get_current_lyric_weight) = $row;

				if($get_current_lyric_id != ""){
					$result_update = mysqli_query($link, "DELETE FROM $t_music_songs_lyrics WHERE lyric_id=$get_current_lyric_id");
					$url = "index.php?open=music_sheets&page=edit_song_lyrics&song_id=$get_current_song_id&editor_language=$editor_language&l=$l&ft=success&fm=lyric_deleted";
					header("Location: $url");
					exit;
				}
			}


			$url = "index.php?open=music_sheets&page=edit_song_lyrics&song_id=$get_current_song_id&editor_language=$editor_language&l=$l&ft=success&fm=changes_saved";
			header("Location: $url");
			exit;
		} // process
		if($mode == "delete_lyric"){
			if(isset($_GET['lyric_id'])){
				$lyric_id = $_GET['lyric_id'];
				$lyric_id = output_html($lyric_id);
			}
			else {
				$lyric_id = "";
			}
			$lyric_id_mysql = quote_smart($link, $lyric_id);

			$query = "SELECT lyric_id, lyric_song_id, lyric_text, lyric_type, lyric_weight FROM $t_music_songs_lyrics WHERE lyric_id=$lyric_id_mysql AND lyric_song_id=$get_current_song_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_lyric_id, $get_current_lyric_song_id, $get_current_lyric_text, $get_current_lyric_type, $get_current_lyric_weight) = $row;

			if($get_current_lyric_id != ""){
				echo"
				<h1>Confirm delete</h1>
				<p>Are you sure you want to delete?</p>

				<div style=\"border: #ccc 1px dashed;padding: 0px 10px 0px 10px;\">
					<p>$get_current_lyric_text</p>
				</div>
				<p>
				<a href=\"index.php?open=music_sheets&amp;page=edit_song_lyrics&amp;mode=delete_lyric&amp;song_id=$get_current_song_id&amp;lyric_id=$get_current_lyric_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_warning\">Delete</a>
				</p>
			
				";
			}
		}
		if($mode == ""){
			echo"
			<h1>$get_current_song_artist - $get_current_song_title</h1>

			<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=music_sheets&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Music Sheets</a>
			&gt;
			<a href=\"index.php?open=music_sheets&amp;page=songs&amp;editor_language=$editor_language&amp;l=$l\">Songs</a>
			&gt;
			<a href=\"index.php?open=music_sheets&amp;page=edit_song_lyrics&amp;song_id=$get_current_song_id&amp;editor_language=$editor_language&amp;l=$l\">Edit</a>
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

			<!-- Edit Lyrics -->

				<p>Add:
				
				<a href=\"index.php?open=music_sheets&amp;page=edit_song_lyrics&amp;mode=add_intro&amp;song_id=$get_current_song_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\">intro</a>
				-
				<a href=\"index.php?open=music_sheets&amp;page=edit_song_lyrics&amp;mode=add_verse&amp;song_id=$get_current_song_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\">verse</a>
				-
				<a href=\"index.php?open=music_sheets&amp;page=edit_song_lyrics&amp;mode=add_course&amp;song_id=$get_current_song_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\">course</a>
				-
				<a href=\"index.php?open=music_sheets&amp;page=edit_song_lyrics&amp;mode=add_bridge&amp;song_id=$get_current_song_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\">bridge</a>
				-
				<a href=\"index.php?open=music_sheets&amp;page=edit_song_lyrics&amp;mode=add_solo&amp;song_id=$get_current_song_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\">solo</a>
				-
				<a href=\"index.php?open=music_sheets&amp;page=edit_song_lyrics&amp;mode=add_instrumental&amp;song_id=$get_current_song_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\">instrumental</a>
				-
				<a href=\"index.php?open=music_sheets&amp;page=edit_song_lyrics&amp;mode=add_outro&amp;song_id=$get_current_song_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\">outro</a>
				</p>


			<form method=\"post\" action=\"index.php?open=music_sheets&amp;page=edit_song_lyrics&amp;mode=save&amp;song_id=$get_current_song_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
	
				";
				// Fetch all
				$x = 0;
				$course_is_present = 0;
				$query = "SELECT lyric_id, lyric_song_id, lyric_text, lyric_type, lyric_weight FROM $t_music_songs_lyrics WHERE lyric_song_id=$get_current_song_id ORDER BY lyric_weight ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_lyric_id, $get_lyric_song_id, $get_lyric_text, $get_lyric_type, $get_lyric_weight) = $row;

					echo"<p>";
					echo ucfirst($get_lyric_type);
					
					echo"
					";
					if($x != 0){
						echo"
						&middot; <a href=\"index.php?open=music_sheets&amp;page=edit_song_lyrics&amp;mode=move_lyric_up&amp;song_id=$get_current_song_id&amp;lyric_id=$get_lyric_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\">Up</a>";
					}
					
					echo"
					&middot; <a href=\"index.php?open=music_sheets&amp;page=edit_song_lyrics&amp;mode=move_lyric_down&amp;song_id=$get_current_song_id&amp;lyric_id=$get_lyric_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\">Down</a>
					&middot; <a href=\"index.php?open=music_sheets&amp;page=edit_song_lyrics&amp;mode=delete_lyric&amp;song_id=$get_current_song_id&amp;lyric_id=$get_lyric_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
					
					<br />
					";

					// Focus
					if($x == 0){
						echo"
						<script>
						\$(document).ready(function(){
							\$('[name=\"inp_text_$get_lyric_id\"]').focus();
						});
						</script>

						";
					}
					if($course_is_present == 1 && $get_lyric_type == "Course"){
						echo"
						
						</p>
						";
					}
					else{
						echo"
						<textarea name=\"inp_text_$get_lyric_id\" rows=\"";
						if($get_lyric_type == "Intro" OR $get_lyric_type == "Instrumental" OR $get_lyric_type == "Outro"){ echo"2"; } else{ echo"6"; } echo"\" cols=\"80\" tabindex=\""; $tabindex=$tabindex+1;echo"$tabindex\">";
						$get_lyric_text = str_replace("<br />", "\n", $get_lyric_text);
						echo"$get_lyric_text</textarea>
						</p>
						";
					}

					// Count
					if($get_lyric_weight != $x){
						$result_update = mysqli_query($link, "UPDATE $t_music_songs_lyrics SET lyric_weight=$x WHERE lyric_id=$get_lyric_id");
					}

					$x++;

					// Smart course
					if($get_lyric_type == "course"){
						$course_is_present = 1;
					}

				}
				echo"
				<p>
				<input type=\"submit\" value=\"Save\" tabindex=\""; $tabindex=$tabindex+1;echo"$tabindex\" class=\"btn_default\" />
				</p>

			</form>

			<!-- //Edit lyrics -->
			";
		} // mode == ""
	} // song found
?>