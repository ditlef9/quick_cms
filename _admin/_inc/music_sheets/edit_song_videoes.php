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
			if($mode =="add"){
				$inp_type = $_GET['type'];
				$inp_type = output_html($inp_type);
				$inp_type = str_replace("_", " ", $inp_type);
				$inp_type = ucfirst($inp_type);
				$inp_type_mysql = quote_smart($link, $inp_type);

				mysqli_query($link, "INSERT INTO $t_music_songs_videoes	
				(video_id, video_song_id, video_service, video_type, video_weight) 
				VALUES 
				(NULL, $get_current_song_id, 'YouTube', $inp_type_mysql, 99)")
				or die(mysqli_error($link));

			}
			elseif($mode == "delete"){

				$video_id = $_GET['video_id'];
				$video_id = output_html($video_id);
				$video_id_mysql = quote_smart($link, $video_id);
				
				$result_update = mysqli_query($link, "DELETE FROM $t_music_songs_videoes WHERE video_id=$video_id_mysql AND video_song_id=$get_current_song_id") or die(mysqli_error($link));

				$url = "index.php?open=music_sheets&page=edit_song_videoes&song_id=$get_current_song_id&editor_language=$editor_language&l=$l&ft=success&fm=deleted";
				header("Location: $url");
				exit;

			}
			elseif($mode == "save"){



				$query = "SELECT video_id, video_song_id, video_embedded, video_lenght_minutes, video_lenght_seconds, video_lenght_total_seconds, video_service, video_type, video_weight FROM $t_music_songs_videoes WHERE video_song_id=$get_current_song_id ORDER BY video_weight ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_video_id, $get_video_song_id, $get_video_embedded, $get_video_lenght_minutes, $get_video_lenght_seconds, $get_video_lenght_total_seconds, $get_video_service, $get_video_type, $get_video_weight) = $row;
				


					// Music video
					$inp_video_embedded = $_POST["inp_video_embedded_$get_video_id"];
					$inp_video_embedded = output_html($inp_video_embedded);
					$inp_video_embedded = str_replace("https://www.youtube.com/watch?v=", "", $inp_video_embedded);
					$inp_video_embedded_mysql = quote_smart($link, $inp_video_embedded);


					$inp_video_lenght_minutes = $_POST["inp_video_lenght_minutes_$get_video_id"];
					$inp_video_lenght_minutes = output_html($inp_video_lenght_minutes);
					if($inp_video_lenght_minutes == ""){
						$inp_video_lenght_minutes = 0;
					}
					$inp_video_lenght_minutes_mysql = quote_smart($link, $inp_video_lenght_minutes);

					$inp_video_lenght_seconds = $_POST["inp_video_lenght_seconds_$get_video_id"];
					$inp_video_lenght_seconds = output_html($inp_video_lenght_seconds);
					if($inp_video_lenght_seconds == ""){
						$inp_video_lenght_seconds = 0;
					}
					$inp_video_lenght_seconds_mysql = quote_smart($link, $inp_video_lenght_seconds);
			

					$inp_video_lenght_total_seconds = round(($inp_video_lenght_minutes*60) + $inp_video_lenght_seconds, 0);
					$inp_video_lenght_total_second_mysql = quote_smart($link, $inp_video_lenght_total_seconds);



					$result_update = mysqli_query($link, "UPDATE $t_music_songs_videoes SET 
					video_embedded=$inp_video_embedded_mysql,
					video_lenght_minutes=$inp_video_lenght_minutes_mysql,
					video_lenght_seconds=$inp_video_lenght_seconds_mysql,
					video_lenght_total_seconds=$inp_video_lenght_total_second_mysql
					 WHERE video_id=$get_video_id") or die(mysqli_error($link));
				} // save
			} // process

			$url = "index.php?open=music_sheets&page=edit_song_videoes&song_id=$get_current_song_id&editor_language=$editor_language&l=$l&ft=success&fm=changes_saved";
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


		<!-- Add video -->

			<p>Add:
			<a href=\"index.php?open=music_sheets&amp;page=edit_song_videoes&amp;mode=add&amp;type=music_video&amp;song_id=$get_current_song_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\">music video</a>
			-
			<a href=\"index.php?open=music_sheets&amp;page=edit_song_videoes&amp;mode=add&amp;type=lyrics_video&amp;song_id=$get_current_song_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\">lyrics video</a>
			-
			<a href=\"index.php?open=music_sheets&amp;page=edit_song_videoes&amp;mode=add&amp;type=karaoke_video&amp;song_id=$get_current_song_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\">karaoke video</a>
			-
			<a href=\"index.php?open=music_sheets&amp;page=edit_song_videoes&amp;mode=add&amp;type=piano_cover&amp;song_id=$get_current_song_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\">piano cover</a>
			-
			<a href=\"index.php?open=music_sheets&amp;page=edit_song_videoes&amp;mode=add&amp;type=piano_tutorial&amp;song_id=$get_current_song_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\">piano tutorial</a>
			-
			<a href=\"index.php?open=music_sheets&amp;page=edit_song_videoes&amp;mode=add&amp;type=guitar_cover&amp;song_id=$get_current_song_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\">guitar cover</a>
			-
			<a href=\"index.php?open=music_sheets&amp;page=edit_song_videoes&amp;mode=add&amp;type=guitar_tutorial&amp;song_id=$get_current_song_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\">guitar tutorial</a>

			</p>
		<!-- //Add video -->
		


		<!-- Form -->
			<form method=\"post\" action=\"index.php?open=music_sheets&amp;page=edit_song_videoes&amp;mode=save&amp;song_id=$get_current_song_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
	
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_music_video_embedded\"]').focus();
			});
			</script>
			";

			// Delete?
			if($mode == "delete"){
				$video_id = $_GET['video_id'];
				$video_id = output_html($video_id);
				echo"
				<div class=\"warning\">

					<p>Are you sure you want to delete?<br />
					</p>

					<p>
					<a href=\"index.php?open=music_sheets&amp;page=edit_song_videoes&amp;mode=delete&amp;song_id=$get_current_song_id&amp;video_id=$video_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_warning\">Delete</a>
					</p>

				</div>
				";
			}

			// Fetch all
			$x = 0;
			$query = "SELECT video_id, video_song_id, video_embedded, video_lenght_minutes, video_lenght_seconds, video_lenght_total_seconds, video_service, video_type, video_weight FROM $t_music_songs_videoes WHERE video_song_id=$get_current_song_id ORDER BY video_weight ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_video_id, $get_video_song_id, $get_video_embedded, $get_video_lenght_minutes, $get_video_lenght_seconds, $get_video_lenght_total_seconds, $get_video_service, $get_video_type, $get_video_weight) = $row;
				

				echo"
				<h2>$get_video_type</h2>
				";
				if($get_video_embedded != ""){
					echo"
					<div style=\"float: left;padding-right: 10px;\">
					<iframe width=\"280\" height=\"158\" src=\"https://www.youtube-nocookie.com/embed/$get_video_embedded\" frameborder=\"0\" allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>
					</div>
				";
				}
				echo"
				<p>Embedded ID:
				<a href=\"https://www.youtube.com/results?search_query=$get_current_song_artist+$get_current_song_title+$get_video_type\">YouTube</a><br />
				<input type=\"text\" name=\"inp_video_embedded_$get_video_id\" value=\"$get_video_embedded\" size=\"25\" tabindex=\""; $tabindex=$tabindex+1;echo"$tabindex\" />
				</p>

				<p>Lenght<br />
				<input type=\"text\" name=\"inp_video_lenght_minutes_$get_video_id\" value=\"$get_video_lenght_minutes\" size=\"2\" tabindex=\""; $tabindex=$tabindex+1;echo"$tabindex\" />
				: 
				<input type=\"text\" name=\"inp_video_lenght_seconds_$get_video_id\" value=\"$get_video_lenght_seconds\" size=\"2\" tabindex=\""; $tabindex=$tabindex+1;echo"$tabindex\" />
				</p>
			
				<p>Actions<br />
				<a href=\"index.php?open=music_sheets&amp;page=edit_song_videoes&amp;mode=delete&amp;song_id=$get_current_song_id&amp;video_id=$get_video_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
				</p>
				<div class=\"clear\"></div>
			
				<hr />
				
				";

				if($x != "$get_video_weight"){

					$result_update = mysqli_query($link, "UPDATE $t_music_songs_videoes SET 
					video_weight=$x
					 WHERE video_id=$get_video_id") or die(mysqli_error($link));
				}
				$x++;

			} // while
			echo"

			<p>
			<input type=\"submit\" value=\"Save\" tabindex=\""; $tabindex=$tabindex+1;echo"$tabindex\" class=\"btn_default\" />
			</p>
			</form>
		<!-- //Form -->
		";
	} // song found
?>