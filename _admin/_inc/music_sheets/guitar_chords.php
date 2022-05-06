<?php
/**
*
* File: _admin/_inc/music_sheets/guitar_chords.php
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


/*- Variables ---------------------------------------------------"--------------------- */
$tabindex = 0;

if(isset($_GET['chord_id'])){
	$chord_id = $_GET['chord_id'];
	$chord_id = output_html($chord_id);
}
else {
	$chord_id = "";
}
if(isset($_GET['mode'])){
	$mode = $_GET['mode'];
	$mode = output_html($mode);
}
else {
	$mode = "";
}
if(isset($_GET['letter'])){
	$letter = $_GET['letter'];
	$letter = output_html($letter);
}
else {
	$letter = "";
}

if($action == ""){
	echo"
	<h1>Guitar chords</h1>
				

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
		<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;editor_language=$editor_language&amp;l=$l\">Guitar chords</a>
		</p>
	<!-- //Where am I? -->

	<!-- Actions -->
		<p>
		<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=new_chord&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">New chords</a>
		</p>
	<!-- //Actions -->

	<!-- Select letter -->
		<div class=\"vertical\">
			<ul>
		";
		$query = "SELECT DISTINCT chord_letter_lower, chord_letter_upper FROM $t_music_guitar_chords_index ORDER BY chord_letter_lower ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_chord_letter_lower, $get_chord_letter_upper) = $row;

			echo"<li><a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=view_letter&amp;letter=$get_chord_letter_lower&amp;editor_language=$editor_language&amp;l=$l\">$get_chord_letter_upper</a></li>\n";
		}	
		echo"
			</ul>
		</div>
	<!-- //Select letter -->
	";
} // action == ""
elseif($action == "new_chord"){
	if($process == "1"){

		// Letter
		$inp_name = $_POST['inp_name'];
		$inp_name = output_html($inp_name);
		$inp_name_mysql = quote_smart($link, $inp_name);

		$inp_name_clean = clean($inp_name);
		$inp_name_clean_mysql = quote_smart($link, $inp_name_clean);

		$inp_letter_lower = strtolower(substr($inp_name, 0, 1));
		$inp_letter_lower_mysql = quote_smart($link, $inp_letter_lower);

		$inp_letter_upper = ucfirst($inp_letter_lower);
		$inp_letter_upper_mysql = quote_smart($link, $inp_letter_upper);

		$inp_image_file = $inp_name_clean  . ".jpg";
		$inp_image_file_mysql = quote_smart($link, $inp_image_file); 

		$inp_sound_file = $inp_name_clean  . "_sound" . ".mp3";
		$inp_sound_file_mysql = quote_smart($link, $inp_sound_file);

		$inp_video_short_file = $inp_name_clean . "_video_short" . ".mp4";
		$inp_video_short_file_mysql = quote_smart($link, $inp_video_short_file);

		$inp_video_tutorial_file = $inp_name_clean . "_video_tutorial" . ".mp4";
		$inp_video_tutorial_file_mysql = quote_smart($link, $inp_video_tutorial_file);

		$inp_difficulty = $_POST['inp_difficulty'];
		$inp_difficulty = output_html($inp_difficulty);
		$inp_difficulty_mysql = quote_smart($link, $inp_difficulty);

		// Make it
		$datetime = date("Y-m-d H:i:s");

		mysqli_query($link, "INSERT INTO $t_music_guitar_chords_index
		(chord_id, chord_letter_lower, chord_letter_upper, chord_name, chord_name_clean, chord_is_draft, chord_image_file, chord_sound_file, chord_video_short_file, chord_video_tutorial_file, chord_unique_hits, chord_difficulty, chord_created_datetime) 
		VALUES 
		(NULL, $inp_letter_lower_mysql, $inp_letter_upper_mysql, $inp_name_mysql, $inp_name_clean_mysql, '1', $inp_image_file_mysql, $inp_sound_file_mysql, $inp_video_short_file_mysql, $inp_video_tutorial_file_mysql, '0', $inp_difficulty_mysql, '$datetime')")
		or die(mysqli_error($link));

		// get it
		$query = "SELECT chord_id FROM $t_music_guitar_chords_index WHERE chord_created_datetime='$datetime'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_chord_id) = $row;

		// Header
		$url = "index.php?open=music_sheets&page=guitar_chords&action=edit_chord&chord_id=$get_current_chord_id&editor_language=$editor_language&l=$l";
		header("Location: $url");
		exit;
	}
	echo"
	<h1>New chrod</h1>

	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=music_sheets&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Music Sheets</a>
		&gt;
		<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;editor_language=$editor_language&amp;l=$l\">Guitar chords</a>
		&gt;
		<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=new_chord&amp;editor_language=$editor_language&amp;l=$l\">New chords</a>
		</p>
	<!-- //Where am I? -->



	<form method=\"post\" action=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=new_chord&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
	
		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_name\"]').focus();
		});
		</script>


		<p>Chord:<br />
		<input type=\"text\" name=\"inp_name\" value=\"\" size=\"25\" tabindex=\""; $tabindex=$tabindex+1;echo"$tabindex\" /></p>


		<p>Difficutly:<br />
		<select name=\"inp_difficulty\" tabindex=\""; $tabindex=$tabindex+1;echo"$tabindex\" />
			<option value=\"1\">Easy</option>
			<option value=\"2\">Medium</option>
			<option value=\"3\">Hard</option>
		</select></p>

		<p><input type=\"submit\" value=\"Create\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>


	</form>
	";
}
elseif($action == "view_letter"){
	echo"
	<h1>$letter</h1>

	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=music_sheets&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Music Sheets</a>
		&gt;
		<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;editor_language=$editor_language&amp;l=$l\">Guitar chords</a>
		&gt;
		<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=view_letter&amp;letter=$letter&amp;editor_language=$editor_language&amp;l=$l\">"; echo ucfirst($letter); echo"</a>
		</p>
	<!-- //Where am I? -->


	<!-- Chords -->
		";
		$letter_mysql = quote_smart($link, $letter);
		$query = "SELECT chord_id, chord_name, chord_name_clean, chord_image_file FROM $t_music_guitar_chords_index WHERE chord_letter_lower=$letter_mysql ORDER BY chord_name ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_chord_id, $get_chord_name, $get_chord_name_clean, $get_chord_image_file) = $row;

			echo"
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=view_chord&amp;chord_id=$get_chord_id&amp;editor_language=$editor_language&amp;l=$l\"><img src=\"../_uploads/music_sheets/guitar_chords/$get_chord_name_clean/$get_chord_image_file\" alt=\"$get_chord_image_file\" /></a>\n";
		}	
		echo"
	<!-- //Chords -->
	";
} // view letter
elseif($action == "view_chord"){
	// get it
	$chord_id_mysql = quote_smart($link, $chord_id);
	$chord_id_mysql = quote_smart($link, $chord_id);
	$query = "SELECT chord_id, chord_letter_lower, chord_letter_upper, chord_name, chord_name_clean, chord_is_draft, chord_a_head, chord_b_head, chord_c_head, chord_d_head, chord_e_head, chord_f_head, chord_image_file, chord_sound_file, chord_video_short_file, chord_video_short_embedded, chord_video_tutorial_file, chord_video_tutorial_embedded, chord_unique_hits, chord_unique_hits_ip_block, chord_difficulty, chord_created_datetime FROM $t_music_guitar_chords_index WHERE chord_id=$chord_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_chord_id, $get_current_chord_letter_lower, $get_current_chord_letter_upper, $get_current_chord_name, $get_current_chord_name_clean, $get_current_chord_is_draft, $get_current_chord_a_head, $get_current_chord_b_head, $get_current_chord_c_head, $get_current_chord_d_head, $get_current_chord_e_head, $get_current_chord_f_head, $get_current_chord_image_file, $get_current_chord_sound_file, $get_current_chord_video_short_file, $get_current_chord_video_short_embedded, $get_current_chord_video_tutorial_file, $get_current_chord_video_tutorial_embedded, $get_current_chord_unique_hits, $get_current_chord_unique_hits_ip_block, $get_current_chord_difficulty, $get_current_chord_created_datetime) = $row;

	if($get_current_chord_id == ""){
		echo"Not found ";
	}
	else{

		echo"
		<h1>Chord $get_current_chord_name</h1>

		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=music_sheets&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Music Sheets</a>
			&gt;
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;editor_language=$editor_language&amp;l=$l\">Guitar chords</a>
			&gt;
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=view_letter&amp;letter=$get_current_chord_letter_lower&amp;editor_language=$editor_language&amp;l=$l\">$get_current_chord_letter_upper</a>
			&gt;
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_chord_name</a>
			</p>
		<!-- //Where am I? -->


		<!-- Menu -->
			<p>
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=view_chord&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l\" style=\"font-weight: bold;\">View</a>
			|
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l\">Edit</a>
			|
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord_info&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l\">Info</a>
			|
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord_sound&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l\">Sound</a>
			|
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord_video_short&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l\">Video short</a>
			|
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord_video_tutorial&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l\">Video tutorial</a>
			</p>
			<div style=\"height: 10px;\"></div>
		<!-- //Menu -->
		

		<!-- Image -->
			";
			echo"

			<img src=\"../_uploads/music_sheets/guitar_chords/$get_current_chord_name_clean/$get_current_chord_image_file\" alt=\"$get_current_chord_image_file\" />
		<!-- //Image -->

		";
	} // chord found
} // view chord
elseif($action == "edit_chord"){
	// get it
	$chord_id_mysql = quote_smart($link, $chord_id);
	$query = "SELECT chord_id, chord_letter_lower, chord_letter_upper, chord_name, chord_name_clean, chord_is_draft, chord_a_head, chord_b_head, chord_c_head, chord_d_head, chord_e_head, chord_f_head, chord_image_file, chord_sound_file, chord_video_short_file, chord_video_short_embedded, chord_video_tutorial_file, chord_video_tutorial_embedded, chord_unique_hits, chord_unique_hits_ip_block, chord_difficulty, chord_created_datetime FROM $t_music_guitar_chords_index WHERE chord_id=$chord_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_chord_id, $get_current_chord_letter_lower, $get_current_chord_letter_upper, $get_current_chord_name,  $get_current_chord_name_clean, $get_current_chord_is_draft, $get_current_chord_a_head, $get_current_chord_b_head, $get_current_chord_c_head, $get_current_chord_d_head, $get_current_chord_e_head, $get_current_chord_f_head, $get_current_chord_image_file, $get_current_chord_sound_file, $get_current_chord_video_short_file, $get_current_chord_video_short_embedded, $get_current_chord_video_tutorial_file, $get_current_chord_video_tutorial_embedded, $get_current_chord_unique_hits, $get_current_chord_unique_hits_ip_block, $get_current_chord_difficulty, $get_current_chord_created_datetime) = $row;

	if($get_current_chord_id == ""){
		echo"Not found";
	}
	else{
		// Var
		if(isset($_GET['finger_no'])){
			$finger_no = $_GET['finger_no'];
			$finger_no = output_html($finger_no);
		}
		else {
			$finger_no = "";
		}


		if($process == "1"){
			if($mode == "head"){
				$inp_chord_a_head = "$get_current_chord_a_head";
				$inp_chord_b_head = "$get_current_chord_b_head";
				$inp_chord_c_head = "$get_current_chord_c_head";
				$inp_chord_d_head = "$get_current_chord_d_head";
				$inp_chord_e_head = "$get_current_chord_e_head";
				$inp_chord_f_head = "$get_current_chord_f_head";

				if($letter == "a"){
					if($get_current_chord_a_head == ""){
						$inp_chord_a_head = "X";
					}
					elseif($get_current_chord_a_head == "X"){
						$inp_chord_a_head = "O";
					}
					elseif($get_current_chord_a_head == "O"){
						$inp_chord_a_head = "";

					}
				}
				$inp_chord_a_head = output_html($inp_chord_a_head);
				$inp_chord_a_head_mysql = quote_smart($link, $inp_chord_a_head);

				if($letter == "b"){
					if($get_current_chord_b_head == ""){
						$inp_chord_b_head = "X";
					}
					elseif($get_current_chord_b_head == "X"){
						$inp_chord_b_head = "O";
					}
					elseif($get_current_chord_b_head == "O"){
						$inp_chord_b_head = "";

					}
				}
				$inp_chord_b_head = output_html($inp_chord_b_head);
				$inp_chord_b_head_mysql = quote_smart($link, $inp_chord_b_head);

				if($letter == "c"){
					if($get_current_chord_c_head == ""){
						$inp_chord_c_head = "X";
					}
					elseif($get_current_chord_c_head == "X"){
						$inp_chord_c_head = "O";
					}
					elseif($get_current_chord_c_head == "O"){
						$inp_chord_c_head = "";

					}
				}
				$inp_chord_c_head = output_html($inp_chord_c_head);
				$inp_chord_c_head_mysql = quote_smart($link, $inp_chord_c_head);

				if($letter == "d"){
					if($get_current_chord_d_head == ""){
						$inp_chord_d_head = "X";
					}
					elseif($get_current_chord_d_head == "X"){
						$inp_chord_d_head = "O";
					}
					elseif($get_current_chord_d_head == "O"){
						$inp_chord_d_head = "";

					}
				}
				$inp_chord_d_head = output_html($inp_chord_d_head);
				$inp_chord_d_head_mysql = quote_smart($link, $inp_chord_d_head);

				if($letter == "e"){
					if($get_current_chord_e_head == ""){
						$inp_chord_e_head = "X";
					}
					elseif($get_current_chord_e_head == "X"){
						$inp_chord_e_head = "O";
					}
					elseif($get_current_chord_e_head == "O"){
						$inp_chord_e_head = "";

					}
				}
				$inp_chord_e_head = output_html($inp_chord_e_head);
				$inp_chord_e_head_mysql = quote_smart($link, $inp_chord_e_head);

				if($letter == "f"){
					if($get_current_chord_f_head == ""){
						$inp_chord_f_head = "X";
					}
					elseif($get_current_chord_f_head == "X"){
						$inp_chord_f_head = "O";
					}
					elseif($get_current_chord_f_head == "O"){
						$inp_chord_f_head = "";

					}
				}
				$inp_chord_f_head = output_html($inp_chord_f_head);
				$inp_chord_f_head_mysql = quote_smart($link, $inp_chord_f_head);



				$result = mysqli_query($link, "UPDATE $t_music_guitar_chords_index SET 
								chord_a_head=$inp_chord_a_head_mysql,
								chord_b_head=$inp_chord_b_head_mysql,
								chord_c_head=$inp_chord_c_head_mysql,
								chord_d_head=$inp_chord_d_head_mysql,
								chord_e_head=$inp_chord_e_head_mysql,
								chord_f_head=$inp_chord_f_head_mysql WHERE chord_id=$chord_id_mysql") or die(mysqli_error($link));
				$url = "index.php?open=music_sheets&page=guitar_chords&action=edit_chord&chord_id=$get_current_chord_id&editor_language=$editor_language&l=$l&ft=success&fm=head_" . $letter . "_updated";
				header("Location: $url");
				exit;
			} // head
			elseif($mode == "fret" && isset($_GET['fret_no']) && isset($_GET['string_no'])){
			
				$inp_fret_no = $_GET['fret_no'];
				$inp_fret_no = output_html($inp_fret_no);
				$inp_fret_no_mysql = quote_smart($link, $inp_fret_no);

				$inp_string_no = $_GET['string_no'];
				$inp_string_no = output_html($inp_string_no);
				$inp_string_no_mysql = quote_smart($link, $inp_string_no);

				if($finger_no == ""){
					$finger_no = "0";
				}
				$inp_finger_no_mysql = quote_smart($link, $finger_no);

				// Does it exists?
				$query = "SELECT fretfinger_id, fretfinger_chord_id, fretfinger_fret_no, fretfinger_string_no, fretfinger_finger_no FROM $t_music_guitar_chords_frets_fingers WHERE fretfinger_chord_id=$get_current_chord_id AND fretfinger_fret_no=$inp_fret_no_mysql AND fretfinger_string_no=$inp_string_no_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_current_fretfinger_id, $get_current_fretfinger_chord_id, $get_current_fretfinger_fret_no, $get_current_fretfinger_string_no, $get_current_fretfinger_finger_no) = $row;
				if($get_current_fretfinger_id == ""){
					// Insert
					mysqli_query($link, "INSERT INTO $t_music_guitar_chords_frets_fingers 
					(fretfinger_id, fretfinger_chord_id, fretfinger_fret_no, fretfinger_string_no, fretfinger_finger_no) 
					VALUES 
					(NULL, $get_current_chord_id, $inp_fret_no_mysql, $inp_string_no_mysql, $inp_finger_no_mysql)")
					or die(mysqli_error($link));
				}
				else{
					if($finger_no == "0"){
						$result = mysqli_query($link, "DELETE FROM $t_music_guitar_chords_frets_fingers WHERE fretfinger_id=$get_current_fretfinger_id");
	
					} // Delete
					else{
						$result = mysqli_query($link, "UPDATE $t_music_guitar_chords_frets_fingers SET 
						fretfinger_fret_no=$inp_fret_no_mysql, fretfinger_string_no=$inp_string_no_mysql, fretfinger_finger_no=$inp_finger_no_mysql WHERE fretfinger_id=$get_current_fretfinger_id") or die(mysqli_error($link));

					} // update
				}

				$url = "index.php?open=music_sheets&page=guitar_chords&action=edit_chord&chord_id=$get_current_chord_id&finger_no=$finger_no&editor_language=$editor_language&l=$l&ft=success&fm=fret_" . $inp_fret_no . "-string_" . $inp_string_no . "-finger_" . $finger_no . "-updated";
				header("Location: $url");
				exit;
			} // fret
		} // process == 1

		// Draw image
		
		
			// Finnes mappen?
			if(!(is_dir("../uploads"))){
				mkdir("../uploads");
			}
			if(!(is_dir("../_uploads/music_sheets"))){
				mkdir("../_uploads/music_sheets");
			}
			if(!(is_dir("../_uploads/music_sheets/guitar_chords/"))){
				mkdir("../_uploads/music_sheets/guitar_chords/");
			}
			if(!(is_dir("../_uploads/music_sheets/guitar_chords/$get_current_chord_name_clean/"))){
				mkdir("../_uploads/music_sheets/guitar_chords/$get_current_chord_name_clean/");
			}


			// Create a blank image
			$im = imagecreatetruecolor(250, 320);

			$black = imagecolorallocate($im, 0, 0, 0);
			$white = imagecolorallocate($im, 255, 255, 255);

			ImageFilledRectangle($im,0,0,250,320,$white);

			// Header text
			imagestring($im, 5, 115, 5,  "$get_current_chord_name", $black);

			// Header
			if($get_current_chord_a_head != ""){
				imagestring($im, 4, 20-3, 30,  "$get_current_chord_a_head", $black);
			}
			if($get_current_chord_b_head != ""){
				imagestring($im, 4, 60-3, 30,  "$get_current_chord_b_head", $black); // 20+40=60
			}
			if($get_current_chord_c_head != ""){
				imagestring($im, 4, 100-3, 30,  "$get_current_chord_c_head", $black);
			}
			if($get_current_chord_d_head != ""){
				imagestring($im, 4, 140-3, 30,  "$get_current_chord_d_head", $black);
			}
			if($get_current_chord_e_head != ""){
				imagestring($im, 4, 180-3, 30,  "$get_current_chord_e_head", $black);
			}
			if($get_current_chord_f_head != ""){
				imagestring($im, 4, 220-3, 30,  "$get_current_chord_f_head", $black);
			}
			
			// Head fret
			imageline($im, 20, 50, 220, 50, $black); //  resource $image , int $x1 , int $y1 , int $x2 , int $y2 , int $color ) : bool

			// Frets
			imageline($im, 20, 50, 20, 300, $black); //  resource $image , int $x1 , int $y1 , int $x2 , int $y2 , int $color ) : bool
			imageline($im, 60, 50, 60, 300, $black); //  resource $image , int $x1 , int $y1 , int $x2 , int $y2 , int $color ) : bool
			imageline($im, 100, 50, 100, 300, $black); //  resource $image , int $x1 , int $y1 , int $x2 , int $y2 , int $color ) : bool
			imageline($im, 140, 50, 140, 300, $black); //  resource $image , int $x1 , int $y1 , int $x2 , int $y2 , int $color ) : bool
			imageline($im, 180, 50, 180, 300, $black); //  resource $image , int $x1 , int $y1 , int $x2 , int $y2 , int $color ) : bool
			imageline($im, 220, 50, 220, 300, $black); //  resource $image , int $x1 , int $y1 , int $x2 , int $y2 , int $color ) : bool


			// Sub head fret
			imagerectangle($im, 20, 53, 220, 53, $black); // resource $image , int $x1 , int $y1 , int $x2 , int $y2 , int $color ) : bool

			// Frets
			$fret_height = 50;



			for($x=0;$x<10;$x++){
				$fret_no = $x+1;

				$fret_height = $fret_height+40;
				$finger_height = $fret_height-20;

				// Draw fret
				imagerectangle($im, 20, $fret_height, 220, $fret_height, $black); // resource $image , int $x1 , int $y1 , int $x2 , int $y2 , int $color ) : bool
				
				// String 1
				$query = "SELECT fretfinger_id, fretfinger_chord_id, fretfinger_fret_no, fretfinger_string_no, fretfinger_finger_no FROM $t_music_guitar_chords_frets_fingers WHERE fretfinger_chord_id=$get_current_chord_id AND fretfinger_fret_no=$fret_no AND fretfinger_string_no=1";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_a_fretfinger_id, $get_a_fretfinger_chord_id, $get_a_fretfinger_fret_no, $get_a_fretfinger_string_no, $get_a_fretfinger_finger_no) = $row;

				if($get_a_fretfinger_id != ""){
					imagefilledellipse ($im, 20, $finger_height, 25, 25, $black); // imagefilledellipse ( resource $image , int $cx , int $cy , int $width , int $height , int $color ) : bool
					imagestring($im, 4, 16, $finger_height-8,  "$get_a_fretfinger_finger_no", $white);
				}

				// String 2
				$query = "SELECT fretfinger_id, fretfinger_chord_id, fretfinger_fret_no, fretfinger_string_no, fretfinger_finger_no FROM $t_music_guitar_chords_frets_fingers WHERE fretfinger_chord_id=$get_current_chord_id AND fretfinger_fret_no=$fret_no AND fretfinger_string_no=2";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_b_fretfinger_id, $get_b_fretfinger_chord_id, $get_b_fretfinger_fret_no, $get_b_fretfinger_string_no, $get_b_fretfinger_finger_no) = $row;

				if($get_b_fretfinger_id != ""){
					imagefilledellipse ($im, 60, $finger_height, 25, 25, $black); // imagefilledellipse ( resource $image , int $cx , int $cy , int $width , int $height , int $color ) : bool
					imagestring($im, 4, 56, $finger_height-8,  "$get_b_fretfinger_finger_no", $white);
				}

				// String 3
				$query = "SELECT fretfinger_id, fretfinger_chord_id, fretfinger_fret_no, fretfinger_string_no, fretfinger_finger_no FROM $t_music_guitar_chords_frets_fingers WHERE fretfinger_chord_id=$get_current_chord_id AND fretfinger_fret_no=$fret_no AND fretfinger_string_no=3";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_c_fretfinger_id, $get_c_fretfinger_chord_id, $get_c_fretfinger_fret_no, $get_c_fretfinger_string_no, $get_c_fretfinger_finger_no) = $row;

				if($get_c_fretfinger_id != ""){
					imagefilledellipse ($im, 100, $finger_height, 25, 25, $black); // imagefilledellipse ( resource $image , int $cx , int $cy , int $width , int $height , int $color ) : bool
					imagestring($im, 4, 96, $finger_height-8,  "$get_c_fretfinger_finger_no", $white);
				}

				// String 4
				$query = "SELECT fretfinger_id, fretfinger_chord_id, fretfinger_fret_no, fretfinger_string_no, fretfinger_finger_no FROM $t_music_guitar_chords_frets_fingers WHERE fretfinger_chord_id=$get_current_chord_id AND fretfinger_fret_no=$fret_no AND fretfinger_string_no=4";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_d_fretfinger_id, $get_d_fretfinger_chord_id, $get_d_fretfinger_fret_no, $get_d_fretfinger_string_no, $get_d_fretfinger_finger_no) = $row;

				if($get_d_fretfinger_id != ""){
					imagefilledellipse ($im, 140, $finger_height, 25, 25, $black); // imagefilledellipse ( resource $image , int $cx , int $cy , int $width , int $height , int $color ) : bool
					imagestring($im, 4, 136, $finger_height-8,  "$get_d_fretfinger_finger_no", $white);
				}

				// String 5
				$query = "SELECT fretfinger_id, fretfinger_chord_id, fretfinger_fret_no, fretfinger_string_no, fretfinger_finger_no FROM $t_music_guitar_chords_frets_fingers WHERE fretfinger_chord_id=$get_current_chord_id AND fretfinger_fret_no=$fret_no AND fretfinger_string_no=5";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_e_fretfinger_id, $get_e_fretfinger_chord_id, $get_e_fretfinger_fret_no, $get_e_fretfinger_string_no, $get_e_fretfinger_finger_no) = $row;

				if($get_e_fretfinger_id != ""){
					imagefilledellipse ($im, 180, $finger_height, 25, 25, $black); // imagefilledellipse ( resource $image , int $cx , int $cy , int $width , int $height , int $color ) : bool
					imagestring($im, 4, 176, $finger_height-8,  "$get_e_fretfinger_finger_no", $white);
				}

				// String 6
				$query = "SELECT fretfinger_id, fretfinger_chord_id, fretfinger_fret_no, fretfinger_string_no, fretfinger_finger_no FROM $t_music_guitar_chords_frets_fingers WHERE fretfinger_chord_id=$get_current_chord_id AND fretfinger_fret_no=$fret_no AND fretfinger_string_no=6";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_f_fretfinger_id, $get_f_fretfinger_chord_id, $get_f_fretfinger_fret_no, $get_f_fretfinger_string_no, $get_f_fretfinger_finger_no) = $row;

				if($get_f_fretfinger_id != ""){
					imagefilledellipse ($im, 220, $finger_height, 25, 25, $black); // imagefilledellipse ( resource $image , int $cx , int $cy , int $width , int $height , int $color ) : bool
					imagestring($im, 4, 216, $finger_height-8,  "$get_f_fretfinger_finger_no", $white);
				}

				// Bare?
				if($get_a_fretfinger_id != "" && $get_b_fretfinger_id != "" && $get_c_fretfinger_id != "" && $get_d_fretfinger_id != "" && $get_e_fretfinger_id != "" && $get_f_fretfinger_id != ""){
					// Fill 
					imagefilledrectangle($im, 18, $finger_height-12, 222, $finger_height+12, $black); // imagefilledrectangle ( resource $image , int $x1 , int $y1 , int $x2 , int $y2 , int $color )
					imagestring($im, 4, 115, $finger_height-8,  "$get_c_fretfinger_finger_no", $white);
				}
			}

			// Save the image as 'simpletext.jpg'
			imagejpeg($im, "../_uploads/music_sheets/guitar_chords/$get_current_chord_name_clean/$get_current_chord_image_file");

			// Free up memory
			imagedestroy($im);

		echo"
		<h1>Edit chord $get_current_chord_name</h1>

		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=music_sheets&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Music Sheets</a>
			&gt;
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;editor_language=$editor_language&amp;l=$l\">Guitar chords</a>
			&gt;
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=view_letter&amp;letter=$get_current_chord_letter_lower&amp;editor_language=$editor_language&amp;l=$l\">$get_current_chord_letter_upper</a>
			&gt;
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l\">Edit chord $get_current_chord_name</a>
			</p>
		<!-- //Where am I? -->


		<!-- Menu -->
			<p>
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=view_chord&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l\">View</a>
			|
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l\" style=\"font-weight: bold;\">Edit</a>
			|
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord_info&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l\">Info</a>
			|
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord_sound&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l\">Sound</a>
			|
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord_video_short&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l\">Video short</a>
			|
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord_video_tutorial&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l\">Video tutorial</a>
			</p>
			<div style=\"height: 10px;\"></div>
		<!-- //Menu -->
		

		<!-- Finger -->
			<table>
			 <tr>
			  <td style=\"padding-right: 4px;\">
				<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l\" style=\""; if($finger_no == ""){ echo"background:black;color:white;"; } else{ echo"background:#e9e9e9;color:black;"; }  echo"border-radius: 10px;padding:5px 8px 5px 8px;\">-</a>
			  </td>
			  <td style=\"padding-right: 4px;\">
				<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l&amp;finger_no=1\" style=\""; if($finger_no == "1"){ echo"background:black;color:white;"; } else{ echo"background:#e9e9e9;color:black;"; }  echo"border-radius: 10px;padding:5px 8px 5px 8px;\">1</a>
			  </td>
			  <td style=\"padding-right: 4px;\">
				<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l&amp;finger_no=2\" style=\""; if($finger_no == "2"){ echo"background:black;color:white;"; } else{ echo"background:#e9e9e9;color:black;"; }  echo"border-radius: 10px;padding:5px 8px 5px 8px;\">2</a>
			  </td>
			  <td style=\"padding-right: 4px;\">
				<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l&amp;finger_no=3\" style=\""; if($finger_no == "3"){ echo"background:black;color:white;"; } else{ echo"background:#e9e9e9;color:black;"; }  echo"border-radius: 10px;padding:5px 8px 5px 8px;\">3</a>
			  </td>
			  <td style=\"padding-right: 4px;\">
				<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l&amp;finger_no=4\" style=\""; if($finger_no == "4"){ echo"background:black;color:white;"; } else{ echo"background:#e9e9e9;color:black;"; }  echo"border-radius: 10px;padding:5px 8px 5px 8px;\">4</a>
			  </td>
			  <td style=\"padding-right: 4px;\">
				<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l&amp;finger_no=5\" style=\""; if($finger_no == "5"){ echo"background:black;color:white;"; } else{ echo"background:#e9e9e9;color:black;"; }  echo"border-radius: 10px;padding:5px 8px 5px 8px;\">5</a>
			  </td>
			 </tr>
			</table>
			<div style=\"height: 20px;\"></div>
		<!-- //Finger -->


		<!-- Image -->
			<img src=\"../_uploads/music_sheets/guitar_chords/$get_current_chord_name_clean/$get_current_chord_image_file\" alt=\"$get_current_chord_image_file\" style=\"float: right;\" />
		<!-- //Image -->

		<!-- Chord -->
			<table>

			<!-- Head -->
				 <tr>
				  <td style=\"width:30px;text-align:center;background: url('_inc/music_sheets/_gfx/15px_whte_15px_black.jpg') center bottom repeat-x;\">
				";
				if($get_current_chord_a_head == "X"){
					echo"
					<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;mode=head&amp;letter=a&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" style=\"color: black;\">X</a>
					";
				}
				elseif($get_current_chord_a_head == "O"){
					echo"
					<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;mode=head&amp;letter=a&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" style=\"color: black;\">O</a>
					";
				}
				else{
					echo"
					<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;mode=head&amp;letter=a&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"grey\">-</a>
					";
				}
				echo"
			 	 </td>

				  <td style=\"width:30px;text-align:center;background: url('_inc/music_sheets/_gfx/black.jpg') bottom repeat-x;\">
				";
				if($get_current_chord_b_head == "X"){
					echo"
					<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;mode=head&amp;letter=b&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" style=\"color: black;\">X</a>
					";
				}
				elseif($get_current_chord_b_head == "O"){
					echo"
					<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;mode=head&amp;letter=b&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" style=\"color: black;\">O</a>
					";
				}
				else{
					echo"
					<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;mode=head&amp;letter=b&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"grey\">-</a>
					";
				}
				echo"
			 	 </td>

				  <td style=\"width:30px;text-align:center;background: url('_inc/music_sheets/_gfx/black.jpg') bottom repeat-x;\">
				";
				if($get_current_chord_c_head == "X"){
					echo"
					<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;mode=head&amp;letter=c&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" style=\"color: black;\">X</a>
					";
				}
				elseif($get_current_chord_c_head == "O"){
					echo"
					<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;mode=head&amp;letter=c&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" style=\"color: black;\">O</a>
					";
				}
				else{
					echo"
					<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;mode=head&amp;letter=c&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"grey\">-</a>
					";
				}
				echo"
			 	 </td>

				  <td style=\"width:30px;text-align:center;background: url('_inc/music_sheets/_gfx/black.jpg') bottom repeat-x;\">
				";
				if($get_current_chord_d_head == "X"){
					echo"
					<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;mode=head&amp;letter=d&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" style=\"color: black;\">X</a>
					";
				}
				elseif($get_current_chord_d_head == "O"){
					echo"
					<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;mode=head&amp;letter=d&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" style=\"color: black;\">O</a>
					";
				}
				else{
					echo"
					<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;mode=head&amp;letter=d&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"grey\">-</a>
					";
				}
				echo"
			 	 </td>

				  <td style=\"width:30px;text-align:center;background: url('_inc/music_sheets/_gfx/black.jpg') bottom repeat-x;\">
				";
				if($get_current_chord_e_head == "X"){
					echo"
					<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;mode=head&amp;letter=e&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" style=\"color: black;\">X</a>
					";
				}
				elseif($get_current_chord_e_head == "O"){
					echo"
					<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;mode=head&amp;letter=e&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" style=\"color: black;\">O</a>
					";
				}
				else{
					echo"
					<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;mode=head&amp;letter=e&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"grey\">-</a>
					";
				}
				echo"
			 	 </td>
				  <td style=\"width:30px;text-align:center;background: url('_inc/music_sheets/_gfx/15px_black_15px_white.jpg') center bottom repeat-x;\">
				";
				if($get_current_chord_f_head == "X"){
					echo"
					<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;mode=head&amp;letter=f&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" style=\"color: black;\">X</a>
					";
				}
				elseif($get_current_chord_f_head == "O"){
					echo"
					<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;mode=head&amp;letter=f&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" style=\"color: black;\">O</a>
					";
				}
				else{
					echo"
					<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;mode=head&amp;letter=f&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"grey\">-</a>
					";
				}
				echo"
			 	  </td>
				 </tr>


				 <tr>
				  <td style=\"height:4px;background: url('_inc/music_sheets/_gfx/fret_left.jpg') center bottom no-repeat;\">
					
			 	  </td>
				  <td style=\"height:4px;background: url('_inc/music_sheets/_gfx/fret_center.jpg') center bottom no-repeat;\">
			 	  </td>
				  <td style=\"height:4px;background: url('_inc/music_sheets/_gfx/fret_center.jpg') center bottom no-repeat;\">
			 	  </td>
				  <td style=\"height:4px;background: url('_inc/music_sheets/_gfx/fret_center.jpg') center bottom no-repeat;\">
			 	  </td>
				  <td style=\"height:4px;background: url('_inc/music_sheets/_gfx/fret_center.jpg') center bottom no-repeat;\">
			 	  </td>
				  <td style=\"height:4px;background: url('_inc/music_sheets/_gfx/fret_right.jpg') center bottom no-repeat;\">
			 	  </td>
				 </tr>
			<!-- //Head -->

			";
			for($x=0;$x<10;$x++){
				$fret_no = $x+1;
				echo"
				 <tr>
				  <td style=\"height:40px;text-align:center;background: url('_inc/music_sheets/_gfx/fret_left.jpg') center bottom no-repeat;\">";
					$query = "SELECT fretfinger_id, fretfinger_chord_id, fretfinger_fret_no, fretfinger_string_no, fretfinger_finger_no FROM $t_music_guitar_chords_frets_fingers WHERE fretfinger_chord_id=$get_current_chord_id AND fretfinger_fret_no=$fret_no AND fretfinger_string_no=1";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_current_fretfinger_id, $get_current_fretfinger_chord_id, $get_current_fretfinger_fret_no, $get_current_fretfinger_string_no, $get_current_fretfinger_finger_no) = $row;

					if($get_current_fretfinger_id == ""){
						echo"
						<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;mode=fret&amp;fret_no=$fret_no&amp;string_no=1&amp;finger_no=$finger_no&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"grey\">-</a>
						";
					}
					else{
						echo"
						<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;mode=fret&amp;fret_no=$fret_no&amp;string_no=1&amp;finger_no=$finger_no&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" style=\"background:black;color:white;border-radius: 10px;padding:3px 6px 3px 6px;\">$get_current_fretfinger_finger_no</a>
						";
					}
					echo"
			 	  </td>
				  <td style=\"text-align:center;background: url('_inc/music_sheets/_gfx/fret_center.jpg') center bottom no-repeat;\">";
					$query = "SELECT fretfinger_id, fretfinger_chord_id, fretfinger_fret_no, fretfinger_string_no, fretfinger_finger_no FROM $t_music_guitar_chords_frets_fingers WHERE fretfinger_chord_id=$get_current_chord_id AND fretfinger_fret_no=$fret_no AND fretfinger_string_no=2";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_current_fretfinger_id, $get_current_fretfinger_chord_id, $get_current_fretfinger_fret_no, $get_current_fretfinger_string_no, $get_current_fretfinger_finger_no) = $row;

					if($get_current_fretfinger_id == ""){
						echo"
						<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;mode=fret&amp;fret_no=$fret_no&amp;string_no=2&amp;finger_no=$finger_no&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"grey\">-</a>
						";
					}
					else{
						echo"
						<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;mode=fret&amp;fret_no=$fret_no&amp;string_no=2&amp;finger_no=$finger_no&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" style=\"background:black;color:white;border-radius: 10px;padding:3px 6px 3px 6px;\">$get_current_fretfinger_finger_no</a>
						";
					}
					echo"
			 	  </td>
				  <td style=\"text-align:center;background: url('_inc/music_sheets/_gfx/fret_center.jpg') center bottom no-repeat;\">";
					$query = "SELECT fretfinger_id, fretfinger_chord_id, fretfinger_fret_no, fretfinger_string_no, fretfinger_finger_no FROM $t_music_guitar_chords_frets_fingers WHERE fretfinger_chord_id=$get_current_chord_id AND fretfinger_fret_no=$fret_no AND fretfinger_string_no=3";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_current_fretfinger_id, $get_current_fretfinger_chord_id, $get_current_fretfinger_fret_no, $get_current_fretfinger_string_no, $get_current_fretfinger_finger_no) = $row;

					if($get_current_fretfinger_id == ""){
						echo"
						<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;mode=fret&amp;fret_no=$fret_no&amp;string_no=3&amp;finger_no=$finger_no&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"grey\">-</a>
						";
					}
					else{
						echo"
						<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;mode=fret&amp;fret_no=$fret_no&amp;string_no=3&amp;finger_no=$finger_no&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" style=\"background:black;color:white;border-radius: 10px;padding:3px 6px 3px 6px;\">$get_current_fretfinger_finger_no</a>
						";
					}
					echo"
			 	  </td>
				  <td style=\"text-align:center;background: url('_inc/music_sheets/_gfx/fret_center.jpg') center bottom no-repeat;\">";
					$query = "SELECT fretfinger_id, fretfinger_chord_id, fretfinger_fret_no, fretfinger_string_no, fretfinger_finger_no FROM $t_music_guitar_chords_frets_fingers WHERE fretfinger_chord_id=$get_current_chord_id AND fretfinger_fret_no=$fret_no AND fretfinger_string_no=4";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_current_fretfinger_id, $get_current_fretfinger_chord_id, $get_current_fretfinger_fret_no, $get_current_fretfinger_string_no, $get_current_fretfinger_finger_no) = $row;

					if($get_current_fretfinger_id == ""){
						echo"
						<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;mode=fret&amp;fret_no=$fret_no&amp;string_no=4&amp;finger_no=$finger_no&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"grey\">-</a>
						";
					}
					else{
						echo"
						<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;mode=fret&amp;fret_no=$fret_no&amp;string_no=4&amp;finger_no=$finger_no&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" style=\"background:black;color:white;border-radius: 10px;padding:3px 6px 3px 6px;\">$get_current_fretfinger_finger_no</a>
						";
					}
					echo"
			 	  </td>
				  <td style=\"text-align:center;background: url('_inc/music_sheets/_gfx/fret_center.jpg') center bottom no-repeat;\">";
					$query = "SELECT fretfinger_id, fretfinger_chord_id, fretfinger_fret_no, fretfinger_string_no, fretfinger_finger_no FROM $t_music_guitar_chords_frets_fingers WHERE fretfinger_chord_id=$get_current_chord_id AND fretfinger_fret_no=$fret_no AND fretfinger_string_no=5";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_current_fretfinger_id, $get_current_fretfinger_chord_id, $get_current_fretfinger_fret_no, $get_current_fretfinger_string_no, $get_current_fretfinger_finger_no) = $row;

					if($get_current_fretfinger_id == ""){
						echo"
						<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;mode=fret&amp;fret_no=$fret_no&amp;string_no=5&amp;finger_no=$finger_no&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"grey\">-</a>
						";
					}
					else{
						echo"
						<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;mode=fret&amp;fret_no=$fret_no&amp;string_no=5&amp;finger_no=$finger_no&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" style=\"background:black;color:white;border-radius: 10px;padding:3px 6px 3px 6px;\">$get_current_fretfinger_finger_no</a>
						";
					}
					echo"
			 	  </td>
				  <td style=\"text-align:center;background: url('_inc/music_sheets/_gfx/fret_right.jpg') center bottom no-repeat;\">";
					$query = "SELECT fretfinger_id, fretfinger_chord_id, fretfinger_fret_no, fretfinger_string_no, fretfinger_finger_no FROM $t_music_guitar_chords_frets_fingers WHERE fretfinger_chord_id=$get_current_chord_id AND fretfinger_fret_no=$fret_no AND fretfinger_string_no=6";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_current_fretfinger_id, $get_current_fretfinger_chord_id, $get_current_fretfinger_fret_no, $get_current_fretfinger_string_no, $get_current_fretfinger_finger_no) = $row;

					if($get_current_fretfinger_id == ""){
						echo"
						<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;mode=fret&amp;fret_no=$fret_no&amp;string_no=6&amp;finger_no=$finger_no&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"grey\">-</a>
						";
					}
					else{
						echo"
						<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;mode=fret&amp;fret_no=$fret_no&amp;string_no=6&amp;finger_no=$finger_no&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" style=\"background:black;color:white;border-radius: 10px;padding:3px 6px 3px 6px;\">$get_current_fretfinger_finger_no</a>
						";
					}
					echo"
			 	  </td>
				 </tr>
				";
			}
			echo"
			</table>
		<!-- //Chord -->

		";
	} // chord found
} // edit chord
elseif($action == "edit_chord_info"){
	// get it
	$chord_id_mysql = quote_smart($link, $chord_id);
	$query = "SELECT chord_id, chord_letter_lower, chord_letter_upper, chord_name, chord_name_clean, chord_is_draft, chord_a_head, chord_b_head, chord_c_head, chord_d_head, chord_e_head, chord_f_head, chord_image_file, chord_sound_file, chord_video_short_file, chord_video_short_embedded, chord_video_tutorial_file, chord_video_tutorial_embedded, chord_unique_hits, chord_unique_hits_ip_block, chord_difficulty, chord_created_datetime FROM $t_music_guitar_chords_index WHERE chord_id=$chord_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_chord_id, $get_current_chord_letter_lower, $get_current_chord_letter_upper, $get_current_chord_name,  $get_current_chord_name_clean, $get_current_chord_is_draft, $get_current_chord_a_head, $get_current_chord_b_head, $get_current_chord_c_head, $get_current_chord_d_head, $get_current_chord_e_head, $get_current_chord_f_head, $get_current_chord_image_file, $get_current_chord_sound_file, $get_current_chord_video_short_file, $get_current_chord_video_short_embedded, $get_current_chord_video_tutorial_file, $get_current_chord_video_tutorial_embedded, $get_current_chord_unique_hits, $get_current_chord_unique_hits_ip_block, $get_current_chord_difficulty, $get_current_chord_created_datetime) = $row;

	if($get_current_chord_id == ""){
		echo"Not found";
	}
	else{
		if($process == "1"){
			// Letter
			$inp_name = $_POST['inp_name'];
			$inp_name = output_html($inp_name);
			$inp_name_mysql = quote_smart($link, $inp_name);

			$inp_name_clean = clean($inp_name);
			$inp_name_clean_mysql = quote_smart($link, $inp_name_clean);


			$inp_image_file = $inp_name_clean  . ".jpg";
			$inp_image_file_mysql = quote_smart($link, $inp_image_file); 

			$inp_letter_lower = strtolower(substr($inp_name, 0, 1));
			$inp_letter_lower_mysql = quote_smart($link, $inp_letter_lower);

			$inp_letter_upper = ucfirst($inp_letter_lower);
			$inp_letter_upper_mysql = quote_smart($link, $inp_letter_upper);



			$inp_draft = $_POST['inp_draft'];
			$inp_draft = output_html($inp_draft);
			$inp_draft_mysql = quote_smart($link, $inp_draft);

			$inp_difficulty = $_POST['inp_difficulty'];
			$inp_difficulty = output_html($inp_difficulty);
			$inp_difficulty_mysql = quote_smart($link, $inp_difficulty);



			$result = mysqli_query($link, "UPDATE $t_music_guitar_chords_index SET 
							chord_letter_lower=$inp_letter_lower_mysql,
							chord_letter_upper=$inp_letter_upper_mysql,
							chord_name=$inp_name_mysql,
							chord_name_clean=$inp_name_clean_mysql,
							chord_is_draft=$inp_draft_mysql,
							chord_image_file=$inp_image_file_mysql,
							chord_difficulty=$inp_difficulty_mysql WHERE chord_id=$chord_id_mysql") or die(mysqli_error($link));

			$url = "index.php?open=music_sheets&page=guitar_chords&action=edit_chord_info&chord_id=$get_current_chord_id&editor_language=$editor_language&l=$l&ft=success&fm=changes_saved";
			header("Location: $url");
			exit;
			

		} // process == 1

		echo"
		<h1>Edit chord $get_current_chord_name</h1>

		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=music_sheets&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Music Sheets</a>
			&gt;
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;editor_language=$editor_language&amp;l=$l\">Guitar chords</a>
			&gt;
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=view_letter&amp;letter=$get_current_chord_letter_lower&amp;editor_language=$editor_language&amp;l=$l\">$get_current_chord_letter_upper</a>
			&gt;
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l\">Edit chord $get_current_chord_name</a>
			</p>
		<!-- //Where am I? -->


		<!-- Menu -->
			<p>
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=view_chord&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l\">View</a>
			|
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l\">Edit</a>
			|
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord_info&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l\" style=\"font-weight: bold;\">Info</a>
			|
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord_sound&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l\">Sound</a>
			|
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord_video_short&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l\">Video short</a>
			|
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord_video_tutorial&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l\">Video tutorial</a>
			</p>
			<div style=\"height: 10px;\"></div>
		<!-- //Menu -->
		
		<!-- Feedback -->
			";
			if($ft != ""){
				if($fm == "changes_saved"){
					$fm = "$l_changes_saved";
				}
				elseif($fm == "navgation_item_deleted"){
					$fm = "$l_navgation_item_deleted";
				}
		
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
		<!-- //Feedback -->


		<!-- Info form -->
			
			<form method=\"post\" action=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord_info&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
	
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_name\"]').focus();
			});
			</script>



			<p>Name:<br />
			<input type=\"text\" name=\"inp_name\" value=\"$get_current_chord_name\" size=\"25\" tabindex=\""; $tabindex=$tabindex+1;echo"$tabindex\" /></p>

			<p>Draft:<br />
			<select name=\"inp_draft\" tabindex=\""; $tabindex=$tabindex+1;echo"$tabindex\" />
				<option value=\"1\""; if($get_current_chord_is_draft == "0"){ echo" selected=\"selected\""; } echo">Yes</option>
				<option value=\"0\""; if($get_current_chord_is_draft == "0"){ echo" selected=\"selected\""; } echo">No</option>
			</select></p>

			<p>Difficutly:<br />
			<select name=\"inp_difficulty\" tabindex=\""; $tabindex=$tabindex+1;echo"$tabindex\" />
				<option value=\"1\""; if($get_current_chord_difficulty == "1"){ echo" selected=\"selected\""; } echo">Easy</option>
				<option value=\"2\""; if($get_current_chord_difficulty == "2"){ echo" selected=\"selected\""; } echo">Medium</option>
				<option value=\"3\""; if($get_current_chord_difficulty == "3"){ echo" selected=\"selected\""; } echo">Hard</option>
			</select></p>

			
			<p>
			<input type=\"submit\" value=\"Save\" class=\"btn_default\" />
			</p>
			</form>
		<!-- //Info form -->

		";
	} // chord found
} // edit_chord_info
elseif($action == "edit_chord_sound"){
	$chord_id_mysql = quote_smart($link, $chord_id);
	$query = "SELECT chord_id, chord_letter_lower, chord_letter_upper, chord_name, chord_name_clean, chord_is_draft, chord_a_head, chord_b_head, chord_c_head, chord_d_head, chord_e_head, chord_f_head, chord_image_file, chord_sound_file, chord_video_short_file, chord_video_short_embedded, chord_video_tutorial_file, chord_video_tutorial_embedded, chord_unique_hits, chord_unique_hits_ip_block, chord_difficulty, chord_created_datetime FROM $t_music_guitar_chords_index WHERE chord_id=$chord_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_chord_id, $get_current_chord_letter_lower, $get_current_chord_letter_upper, $get_current_chord_name,  $get_current_chord_name_clean, $get_current_chord_is_draft, $get_current_chord_a_head, $get_current_chord_b_head, $get_current_chord_c_head, $get_current_chord_d_head, $get_current_chord_e_head, $get_current_chord_f_head, $get_current_chord_image_file, $get_current_chord_sound_file, $get_current_chord_video_short_file, $get_current_chord_video_short_embedded, $get_current_chord_video_tutorial_file, $get_current_chord_video_tutorial_embedded, $get_current_chord_unique_hits, $get_current_chord_unique_hits_ip_block, $get_current_chord_difficulty, $get_current_chord_created_datetime) = $row;

	if($get_current_chord_id == ""){
		echo"Not found";
	}
	else{
		if($process == "1"){
			

			// Sjekk filen
			$file_name = basename($_FILES['file']['name']);
			$file_exp = explode('.', $file_name); 
			$file_type = $file_exp[count($file_exp) -1]; 
			$file_type = strtolower("$file_type");

			// Finnes mappen?
			if(!(is_dir("../uploads"))){
				mkdir("../uploads");
			}
			if(!(is_dir("../_uploads/music_sheets"))){
				mkdir("../_uploads/music_sheets");
			}
			if(!(is_dir("../_uploads/music_sheets/guitar_chords/"))){
				mkdir("../_uploads/music_sheets/guitar_chords/");
			}
			if(!(is_dir("../_uploads/music_sheets/guitar_chords/$get_current_chord_letter_lowercase/"))){
				mkdir("../_uploads/music_sheets/guitar_chords/$get_current_chord_letter_lowercase/");
			}


			// Sett variabler
			$new_name = str_replace(".$file_type", "", $file_name);
			$new_name = $get_current_chord_letter_lowercase . "_sound" . ".$file_type";

			$target_path = "../_uploads/music_sheets/guitar_chords/$get_current_chord_letter_lowercase/" . $new_name;


			// Sjekk om det er en OK filendelse
			if($file_type == "mp3"){
				if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {



					$inp_chord_sound_file = output_html($new_name);
					$inp_chord_sound_file_mysql = quote_smart($link, $inp_chord_sound_file);

					$result = mysqli_query($link, "UPDATE $t_music_guitar_chords_index SET 
						chord_sound_file=$inp_chord_sound_file_mysql WHERE chord_id=$get_current_chord_id") or die(mysqli_error($link));

					$url = "index.php?open=music_sheets&page=guitar_chords&action=edit_chord_sound&chord_id=$get_current_chord_id&editor_language=$editor_language&l=$l&ft=success&fm=sound_updated";
					header("Location: $url");
					exit;
				}
				else{

					$url = "index.php?open=music_sheets&page=guitar_chords&action=edit_chord_sound&chord_id=$get_current_chord_id&editor_language=$editor_language&l=$l&ft=error&fm=upload_error";
					header("Location: $url");
					exit;
				}
			}
			else{
				$url = "index.php?open=music_sheets&page=guitar_chords&action=edit_chord_sound&chord_id=$get_current_chord_id&editor_language=$editor_language&l=$l&ft=error&fm=unknown_file_format";
				header("Location: $url");
				exit;
			}

		} // process == 1

		echo"
		<h1>Edit chord $get_current_chord_letter</h1>

		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=music_sheets&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Music Sheets</a>
			&gt;
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;editor_language=$editor_language&amp;l=$l\">Guitar chords</a>
			&gt;
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l\">Edit chord $get_current_chord_letter</a>
			</p>
		<!-- //Where am I? -->


		<!-- Menu -->
			<p>
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=view_chord&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l\">View</a>
			|
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l\">Edit</a>
			|
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord_info&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l\">Info</a>
			|
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord_sound&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l\" style=\"font-weight: bold;\">Sound</a>
			|
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord_video_short&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l\">Video short</a>
			|
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord_video_tutorial&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l\">Video tutorial</a>

			</p>
			<div style=\"height: 10px;\"></div>
		<!-- //Menu -->
		
		<!-- Feedback -->
			";
			if($ft != ""){
				if($fm == "changes_saved"){
					$fm = "$l_changes_saved";
				}
				elseif($fm == "navgation_item_deleted"){
					$fm = "$l_navgation_item_deleted";
				}
		
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
		<!-- //Feedback -->

		<!-- Existing sound -->
			";
			if(file_exists("../_uploads/music_sheets/guitar_chords/$get_current_chord_letter_lowercase/$get_current_chord_sound_file") && $get_current_chord_sound_file != ""){
				echo"
				<p><b>$get_current_chord_sound_file</b></p>
				<audio controls>
					<source src=\"../_uploads/music_sheets/guitar_chords/$get_current_chord_letter_lowercase/$get_current_chord_sound_file\" type=\"audio/mpeg\">
					Your browser does not support the audio element.
				</audio> 
				";
			}
			echo"
		<!-- Upload -->


			<form action=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord_sound&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" method=\"post\" enctype=\"multipart/form-data\">
			<p>
			<b>New sound (mp3):</b><br />
			<input type=\"file\" name=\"file\" />
			</p>

			<p>
			<input type=\"submit\" value=\"Upload\" class=\"btn_default\" />
			</p>
			</form>
		<!-- //Upload -->

		";
	} // chord found
} // edit_chord_sound
elseif($action == "edit_chord_video_short"){
	$chord_id_mysql = quote_smart($link, $chord_id);
	$query = "SELECT chord_id, chord_letter_lower, chord_letter_upper, chord_name, chord_name_clean, chord_is_draft, chord_a_head, chord_b_head, chord_c_head, chord_d_head, chord_e_head, chord_f_head, chord_image_file, chord_sound_file, chord_video_short_file, chord_video_short_embedded, chord_video_tutorial_file, chord_video_tutorial_embedded, chord_unique_hits, chord_unique_hits_ip_block, chord_difficulty, chord_created_datetime FROM $t_music_guitar_chords_index WHERE chord_id=$chord_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_chord_id, $get_current_chord_letter_lower, $get_current_chord_letter_upper, $get_current_chord_name,  $get_current_chord_name_clean, $get_current_chord_is_draft, $get_current_chord_a_head, $get_current_chord_b_head, $get_current_chord_c_head, $get_current_chord_d_head, $get_current_chord_e_head, $get_current_chord_f_head, $get_current_chord_image_file, $get_current_chord_sound_file, $get_current_chord_video_short_file, $get_current_chord_video_short_embedded, $get_current_chord_video_tutorial_file, $get_current_chord_video_tutorial_embedded, $get_current_chord_unique_hits, $get_current_chord_unique_hits_ip_block, $get_current_chord_difficulty, $get_current_chord_created_datetime) = $row;

	if($get_current_chord_id == ""){
		echo"Not found";
	}
	else{
		if($process == "1"){
			

			// Sjekk filen
			$file_name = basename($_FILES['file']['name']);
			$file_exp = explode('.', $file_name); 
			$file_type = $file_exp[count($file_exp) -1]; 
			$file_type = strtolower("$file_type");

			// Finnes mappen?
			if(!(is_dir("../uploads"))){
				mkdir("../uploads");
			}
			if(!(is_dir("../_uploads/music_sheets"))){
				mkdir("../_uploads/music_sheets");
			}
			if(!(is_dir("../_uploads/music_sheets/guitar_chords/"))){
				mkdir("../_uploads/music_sheets/guitar_chords/");
			}
			if(!(is_dir("../_uploads/music_sheets/guitar_chords/$get_current_chord_letter_lowercase/"))){
				mkdir("../_uploads/music_sheets/guitar_chords/$get_current_chord_letter_lowercase/");
			}


			// Sett variabler
			$new_name = str_replace(".$file_type", "", $file_name);
			$new_name = $get_current_chord_letter_lowercase . "_video_short.$file_type";

			$target_path = "../_uploads/music_sheets/guitar_chords/$get_current_chord_letter_lowercase/" . $new_name;


			// Sjekk om det er en OK filendelse
			if($file_type == "mp4"){
				if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {



					$inp_chord_video_short_file = output_html($new_name);
					$inp_chord_video_short_file_mysql = quote_smart($link, $inp_chord_video_short_file);

					$result = mysqli_query($link, "UPDATE $t_music_guitar_chords_index SET 
						chord_video_short_file=$inp_chord_video_short_file_mysql WHERE chord_id=$get_current_chord_id") or die(mysqli_error($link));

					$url = "index.php?open=music_sheets&page=guitar_chords&action=edit_chord_video_short&chord_id=$get_current_chord_id&editor_language=$editor_language&l=$l&ft=success&fm=video_short_updated";
					header("Location: $url");
					exit;
				}
				else{

   					switch ($_FILES['file'] ['error']){
						case 1:
							$fm = "to_big_file";
						case 2:
							$fm = "to_big_file";
						case 3:
							$fm = "only_parts_uploaded";
						case 4:
							$fm = "no_file_uploaded";
					}
					$url = "index.php?open=music_sheets&page=guitar_chords&action=edit_chord_video_short&chord_id=$get_current_chord_id&editor_language=$editor_language&l=$l&ft=error&fm=upload_error_" . $fm;
					header("Location: $url");
					exit;
				}
			}
			else{

   				$fm = "unkown_file_type";

				$url = "index.php?open=music_sheets&page=guitar_chords&action=edit_chord_video_short&chord_id=$get_current_chord_id&editor_language=$editor_language&l=$l&ft=error&fm=$fm";
				header("Location: $url");
				exit;
			}

		} // process == 1

		echo"
		<h1>Edit chord $get_current_chord_letter</h1>

		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=music_sheets&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Music Sheets</a>
			&gt;
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;editor_language=$editor_language&amp;l=$l\">Guitar chords</a>
			&gt;
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l\">Edit chord $get_current_chord_letter</a>
			</p>
		<!-- //Where am I? -->


		<!-- Menu -->
			<p>
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=view_chord&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l\">View</a>
			|
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l\">Edit</a>
			|
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord_info&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l\">Info</a>
			|
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord_sound&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l\">Sound</a>
			|
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord_video_short&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l\" style=\"font-weight: bold;\">Video short</a>
			|
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord_video_tutorial&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l\">Video tutorial</a>
			</p>
			<div style=\"height: 10px;\"></div>
		<!-- //Menu -->
		
		<!-- Feedback -->
			";
			if($ft != ""){
				if($fm == "changes_saved"){
					$fm = "$l_changes_saved";
				}
				elseif($fm == "navgation_item_deleted"){
					$fm = "$l_navgation_item_deleted";
				}
		
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
		<!-- //Feedback -->


		<!-- Short video -->
			<p><b>$get_current_chord_video_short_file</b></p>
			";
			if(file_exists("../_uploads/music_sheets/guitar_chords/$get_current_chord_letter_lowercase/$get_current_chord_video_short_file") && $get_current_chord_video_short_file != ""){
				echo"
				<!-- 1920/3 = 640. 1080/3=360 -->
				<video width=\"640\" height=\"360\" controls>
					<source src=\"../_uploads/music_sheets/guitar_chords/$get_current_chord_letter_lowercase/$get_current_chord_video_short_file\" type=\"video/mp4\">
					Your browser does not support the video tag.
				</video> 
				";
			}
			echo"


			<form action=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord_video_short&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" method=\"post\" enctype=\"multipart/form-data\">
			<p>
			<b>New video (mp4 1920x1080):</b><br />
			<input type=\"file\" name=\"file\" />
			</p>

			<p>
			<input type=\"submit\" value=\"Upload\" class=\"btn_default\" />
			</p>
			</form>
		<!-- //Short video -->

		";
	} // chord found
} // edit_chord_video_short
elseif($action == "edit_chord_video_tutorial"){
	$chord_id_mysql = quote_smart($link, $chord_id);
	$query = "SELECT chord_id, chord_letter_lower, chord_letter_upper, chord_name, chord_name_clean, chord_is_draft, chord_a_head, chord_b_head, chord_c_head, chord_d_head, chord_e_head, chord_f_head, chord_image_file, chord_sound_file, chord_video_short_file, chord_video_short_embedded, chord_video_tutorial_file, chord_video_tutorial_embedded, chord_unique_hits, chord_unique_hits_ip_block, chord_difficulty, chord_created_datetime FROM $t_music_guitar_chords_index WHERE chord_id=$chord_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_chord_id, $get_current_chord_letter_lower, $get_current_chord_letter_upper, $get_current_chord_name,  $get_current_chord_name_clean, $get_current_chord_is_draft, $get_current_chord_a_head, $get_current_chord_b_head, $get_current_chord_c_head, $get_current_chord_d_head, $get_current_chord_e_head, $get_current_chord_f_head, $get_current_chord_image_file, $get_current_chord_sound_file, $get_current_chord_video_short_file, $get_current_chord_video_short_embedded, $get_current_chord_video_tutorial_file, $get_current_chord_video_tutorial_embedded, $get_current_chord_unique_hits, $get_current_chord_unique_hits_ip_block, $get_current_chord_difficulty, $get_current_chord_created_datetime) = $row;

	if($get_current_chord_id == ""){
		echo"Not found";
	}
	else{
		if($process == "1"){
			

			// Sjekk filen
			$file_name = basename($_FILES['file']['name']);
			$file_exp = explode('.', $file_name); 
			$file_type = $file_exp[count($file_exp) -1]; 
			$file_type = strtolower("$file_type");

			// Finnes mappen?
			if(!(is_dir("../uploads"))){
				mkdir("../uploads");
			}
			if(!(is_dir("../_uploads/music_sheets"))){
				mkdir("../_uploads/music_sheets");
			}
			if(!(is_dir("../_uploads/music_sheets/guitar_chords/"))){
				mkdir("../_uploads/music_sheets/guitar_chords/");
			}
			if(!(is_dir("../_uploads/music_sheets/guitar_chords/$get_current_chord_letter_lowercase/"))){
				mkdir("../_uploads/music_sheets/guitar_chords/$get_current_chord_letter_lowercase/");
			}


			// Sett variabler
			$new_name = str_replace(".$file_type", "", $file_name);
			$new_name = $get_current_chord_letter_lowercase . "_video_tutorial.$file_type";

			$target_path = "../_uploads/music_sheets/guitar_chords/$get_current_chord_letter_lowercase/" . $new_name;


			// Sjekk om det er en OK filendelse
			if($file_type == "mp4"){
				if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {



					$inp_chord_video_tutorial_file = output_html($new_name);
					$inp_chord_video_tutorial_file_mysql = quote_smart($link, $inp_chord_video_tutorial_file);

					$result = mysqli_query($link, "UPDATE $t_music_guitar_chords_index SET 
						chord_video_tutorial_file=$inp_chord_video_tutorial_file_mysql WHERE chord_id=$get_current_chord_id") or die(mysqli_error($link));

					$url = "index.php?open=music_sheets&page=guitar_chords&action=edit_chord_video_tutorial&chord_id=$get_current_chord_id&editor_language=$editor_language&l=$l&ft=success&fm=video_tutorial_updated";
					header("Location: $url");
					exit;
				}
				else{

   					switch ($_FILES['file'] ['error']){
						case 1:
							$fm = "to_big_file";
						case 2:
							$fm = "to_big_file";
						case 3:
							$fm = "only_parts_uploaded";
						case 4:
							$fm = "no_file_uploaded";
					}
					$url = "index.php?open=music_sheets&page=guitar_chords&action=edit_chord_video_tutorial&chord_id=$get_current_chord_id&editor_language=$editor_language&l=$l&ft=error&fm=upload_error_" . $fm;
					header("Location: $url");
					exit;
				}
			}
			else{

   				$fm = "unkown_file_type";

				$url = "index.php?open=music_sheets&page=guitar_chords&action=edit_chord_video_tutorial&chord_id=$get_current_chord_id&editor_language=$editor_language&l=$l&ft=error&fm=$fm";
				header("Location: $url");
				exit;
			}

		} // process == 1

		echo"
		<h1>Edit chord $get_current_chord_letter</h1>

		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=music_sheets&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Music Sheets</a>
			&gt;
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;editor_language=$editor_language&amp;l=$l\">Guitar chords</a>
			&gt;
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l\">Edit chord $get_current_chord_letter</a>
			</p>
		<!-- //Where am I? -->


		<!-- Menu -->
			<p>
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=view_chord&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l\">View</a>
			|
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l\">Edit</a>
			|
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord_info&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l\">Info</a>
			|
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord_sound&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l\">Sound</a>
			|
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord_video_short&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l\">Video short</a>
			|
			<a href=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord_video_tutorial&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l\" style=\"font-weight: bold;\">Video tutorial</a>
			</p>
			<div style=\"height: 10px;\"></div>
		<!-- //Menu -->
		
		<!-- Feedback -->
			";
			if($ft != ""){
				if($fm == "changes_saved"){
					$fm = "$l_changes_saved";
				}
				elseif($fm == "navgation_item_deleted"){
					$fm = "$l_navgation_item_deleted";
				}
		
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
		<!-- //Feedback -->


		<!-- Short video -->
			<p><b>$get_current_chord_video_tutorial_file</b></p>
			";
			if(file_exists("../_uploads/music_sheets/guitar_chords/$get_current_chord_letter_lowercase/$get_current_chord_video_tutorial_file") && $get_current_chord_video_tutorial_file != ""){
				echo"
				<!-- 1920/3 = 640. 1080/3=360 -->
				<video width=\"640\" height=\"360\" controls>
					<source src=\"../_uploads/music_sheets/guitar_chords/$get_current_chord_letter_lowercase/$get_current_chord_video_tutorial_file\" type=\"video/mp4\">
					Your browser does not support the video tag.
				</video> 
				";
			}
			echo"


			<form action=\"index.php?open=music_sheets&amp;page=guitar_chords&amp;action=edit_chord_video_tutorial&amp;chord_id=$get_current_chord_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" method=\"post\" enctype=\"multipart/form-data\">
			<p>
			<b>New video (mp4 1920x1080):</b><br />
			<input type=\"file\" name=\"file\" />
			</p>

			<p>
			<input type=\"submit\" value=\"Upload\" class=\"btn_default\" />
			</p>
			</form>
		<!-- //Short video -->

		";
	} // chord found
} // edit_chord_video_tutorial
?>