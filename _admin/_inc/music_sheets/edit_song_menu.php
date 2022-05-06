<?php
/**
*
* File: _admin/_inc/music_sheets/edit_song_menu.php
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


echo"

			<p>
			<a href=\"index.php?open=music_sheets&amp;page=view_song&amp;song_id=$get_current_song_id&amp;editor_language=$editor_language&amp;l=$l\""; if($page == "view_song"){ echo" style=\"font-weight: bold;\""; } echo">View</a>
			|
			<a href=\"index.php?open=music_sheets&amp;page=edit_song_lyrics&amp;song_id=$get_current_song_id&amp;editor_language=$editor_language&amp;l=$l\""; if($page == "edit_song_lyrics"){ echo" style=\"font-weight: bold;\""; } echo">Lyrics</a>
			|
			<a href=\"index.php?open=music_sheets&amp;page=edit_song_videoes&amp;song_id=$get_current_song_id&amp;editor_language=$editor_language&amp;l=$l\""; if($page == "edit_song_videoes"){ echo" style=\"font-weight: bold;\""; } echo">Videoes</a>
			|
			<a href=\"index.php?open=music_sheets&amp;page=edit_song_guitar_cords&amp;song_id=$get_current_song_id&amp;editor_language=$editor_language&amp;l=$l\""; if($page == "edit_song_guitar_cords"){ echo" style=\"font-weight: bold;\""; } echo">Guitar cords</a>
			</p>
			<div style=\"height: 10px;\"></div>
";

?>