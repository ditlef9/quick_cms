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
	echo"
	<h1>Songs</h1>
				

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


	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=music_sheets&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Music Sheets</a>
		&gt;
		<a href=\"index.php?open=music_sheets&amp;page=songs&amp;editor_language=$editor_language&amp;l=$l\">Songs</a>
		</p>
	<!-- //Where am I? -->

	<!-- Actions -->
		<p>
		<a href=\"index.php?open=music_sheets&amp;page=songs&amp;action=new_song&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">New song</a>
		</p>
	<!-- //Actions -->

	<!-- Songs -->
		<div class=\"vertical\">
			<ul>
		";
		$query = "SELECT song_id, song_artist, song_title FROM $t_music_songs_index ORDER BY song_artist ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_song_id, $get_song_artist, $get_song_title) = $row;

			echo"<li><a href=\"index.php?open=music_sheets&amp;page=view_song&amp;song_id=$get_song_id&amp;editor_language=$editor_language&amp;l=$l\">$get_song_artist - $get_song_title</a></li>\n";
		}	
		echo"
			</ul>
		</div>
	<!-- //Songs -->
	";
} // action == ""
elseif($action == "new_song"){
	if($process == "1"){


		$inp_artist = $_POST['inp_artist'];
		$inp_artist = output_html($inp_artist);
		$inp_artist_mysql = quote_smart($link, $inp_artist);

		$inp_artist_clean = clean($inp_artist);
		$inp_artist_clean_mysql = quote_smart($link, $inp_artist_clean);

		$inp_title = $_POST['inp_title'];
		$inp_title = output_html($inp_title);
		$inp_title_mysql = quote_smart($link, $inp_title);

		$inp_title_clean = clean($inp_title);
		$inp_title_clean_mysql = quote_smart($link, $inp_title_clean);

		$inp_category = $_POST['inp_category'];
		$inp_category = output_html($inp_category);
		$inp_category_mysql = quote_smart($link, $inp_category);

		$inp_language = $_POST['inp_language'];
		$inp_language = output_html($inp_language);
		$inp_language_mysql = quote_smart($link, $inp_language);

		// Me
		$my_user_id = $_SESSION['user_id'];
		$my_user_id = output_html($my_user_id);
		$my_user_id_mysql = quote_smart($link, $my_user_id);
		$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_rank) = $row;

		$my_user_alias_mysql = quote_smart($link, $get_my_user_alias);

		// Get my photo
		$query = "SELECT photo_id, photo_destination FROM $t_users_profile_photo WHERE photo_user_id='$get_my_user_id' AND photo_profile_image='1'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_photo_id, $get_photo_destination) = $row;

		$my_user_image_mysql = quote_smart($link, $get_photo_destination);
	

		// Date
		$datetime = date("Y-m-d H:i:s");
		$date_saying = date("j M Y");

		mysqli_query($link, "INSERT INTO $t_music_songs_index
		(song_id, song_artist, song_artist_clean, song_title, song_title_clean, song_category_id, song_language, song_author_user_id, song_author_user_alias, song_author_user_image, song_created, song_created_saying, song_updated, song_updated_saying, song_unique_hits) 
		VALUES 
		(NULL, $inp_artist_mysql, $inp_artist_clean_mysql, $inp_title_mysql, $inp_title_clean_mysql, $inp_category_mysql, $inp_language_mysql, $my_user_id_mysql, $my_user_alias_mysql, $my_user_image_mysql, '$datetime', '$date_saying', '$datetime', '$date_saying', 0)")
		or die(mysqli_error($link));

		// get it
		$query = "SELECT song_id FROM $t_music_songs_index WHERE song_created='$datetime'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_song_id) = $row;

		// Insert verse, chorus
		mysqli_query($link, "INSERT INTO $t_music_songs_lyrics
		(lyric_id, lyric_song_id, lyric_text, lyric_type, lyric_weight) 
		VALUES 
		(NULL, $get_current_song_id, '', 'Verse', '1')
		")
		or die(mysqli_error($link));

		// Header
		$url = "index.php?open=music_sheets&page=edit_song_lyrics&song_id=$get_current_song_id&editor_language=$editor_language&l=$l";
		header("Location: $url");
		exit;
	}
	echo"
	<h1>New song</h1>

	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=music_sheets&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Music Sheets</a>
		&gt;
		<a href=\"index.php?open=music_sheets&amp;page=songs&amp;editor_language=$editor_language&amp;l=$l\">Songs</a>
		&gt;
		<a href=\"index.php?open=music_sheets&amp;page=songs&amp;action=new_song&amp;editor_language=$editor_language&amp;l=$l\">New song</a>
		</p>
	<!-- //Where am I? -->



	<form method=\"post\" action=\"index.php?open=music_sheets&amp;page=songs&amp;action=new_song&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
	
		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_artist\"]').focus();
		});
		</script>

		<p>Artist:<br />
		<input type=\"text\" name=\"inp_artist\" value=\"\" size=\"25\" tabindex=\""; $tabindex=$tabindex+1;echo"$tabindex\" /></p>

		<p>Title:<br />
		<input type=\"text\" name=\"inp_title\" value=\"\" size=\"25\" tabindex=\""; $tabindex=$tabindex+1;echo"$tabindex\" /></p>


		<p>Category:<br />
		<select name=\"inp_category\" tabindex=\""; $tabindex=$tabindex+1;echo"$tabindex\">\n";
		$query = "SELECT category_id, category_title FROM $t_music_songs_categories ORDER BY category_title ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_category_id, $get_category_title) = $row;
			echo"		";
			echo"<option value=\"$get_category_id\">$get_category_title</option>\n";
		}	
		echo"
		</select></p>


		<p>Language:<br />
		<select name=\"inp_language\" tabindex=\""; $tabindex=$tabindex+1;echo"$tabindex\">\n";
		$query = "SELECT language_id, language_name, language_iso_two, language_flag FROM $t_languages";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_language_id, $get_language_name, $get_language_iso_two, $get_language_flag) = $row;

			echo"		";
			echo"<option value=\"$get_language_name\""; if($get_language_name == "English"){ echo" selected=\"selected\""; } echo">$get_language_name</option>\n";
		}	
		echo"
		</select></p>

		<p><input type=\"submit\" value=\"Create\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>


	</form>
	";
}
?>