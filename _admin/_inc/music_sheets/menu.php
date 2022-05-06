<?php
/**
*
* File: _admin/_inc/music_star/menu.php
* Version 02:10 28.12.2011
* Copyright (c) 2008-2012 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


if($page == "menu"){
	echo"
	<h1>Music Sheets</h1>
	<div class=\"vertical\">
		<ul>
			<li><a href=\"index.php?open=music_sheets&amp;editor_language=$editor_language\">Music Sheets</a></li>

	";
}



echo"
			<li><a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;editor_language=$editor_language\""; if($page == "guitar_chords"){ echo" class=\"selected\""; } echo">Guitar chords</a></li>
			<li><a href=\"index.php?open=music_sheets&amp;page=guitar_strumming&amp;editor_language=$editor_language\""; if($page == "guitar_strumming"){ echo" class=\"selected\""; } echo">Guitar strumming</a></li>
			<li><a href=\"index.php?open=music_sheets&amp;page=songs&amp;editor_language=$editor_language\""; if($page == "songs"){ echo" class=\"selected\""; } echo">Songs</a></li>
			<li><a href=\"index.php?open=music_sheets&amp;page=tables&amp;editor_language=$editor_language\""; if($page == "tables"){ echo" class=\"selected\""; } echo">Tables</a></li>
			<li><a href=\"index.php?open=music_sheets&amp;page=guitar_tuner&amp;editor_language=$editor_language\""; if($page == "tables"){ echo" class=\"selected\""; } echo">Guitar tuner</a></li>
		
";
?>