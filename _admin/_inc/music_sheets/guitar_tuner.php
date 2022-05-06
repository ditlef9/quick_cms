<?php
/**
*
* File: _admin/_inc/music_sheets/guitar_tuner.php
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

if(isset($_GET['mode'])){
	$mode = $_GET['mode'];
	$mode = output_html($mode);
}
else {
	$mode = "";
}

if($action == ""){
	echo"
	<h1>Guitar tuner</h1>
				

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
		<a href=\"index.php?open=music_sheets&amp;page=guitar_tuner&amp;editor_language=$editor_language&amp;l=$l\">Guitar tuner</a>
		</p>
	<!-- //Where am I? -->

	<!-- Play sounds -->
		<script>
		a=new AudioContext() // browsers limit the number of concurrent audio contexts, so you better re-use'em

		function beep(vol, freq, duration){
			v=a.createOscillator()
			u=a.createGain()
			v.connect(u)
			v.frequency.value=freq
			v.type=\"square\"
			u.connect(a.destination)
			u.gain.value=vol*0.01
			v.start(a.currentTime)
			v.stop(a.currentTime+duration*0.001)
		}
		</script>
		<p>
		<a href=\"#\" onclick=\"beep(100, 329.63, 200)\" class=\"btn_default\">E</a>
		<a href=\"#\" onclick=\"beep(100, 440, 200)\" class=\"btn_default\">A</a>
		<a href=\"#\" onclick=\"beep(100, 587.33, 200)\" class=\"btn_default\">D</a>
		<a href=\"#\" onclick=\"beep(100, 783.99, 200)\" class=\"btn_default\">G</a>
		<a href=\"#\" onclick=\"beep(100, 987.77, 200)\" class=\"btn_default\">B</a>
		<a href=\"#\" onclick=\"beep(100, 1318.5, 200)\" class=\"btn_default\">A</a>
		</p>
	<!-- //Play sounds -->

	<!-- Record sounds -->
		<a id=\"recordButton\">recordButton</a>

		<script>
		var recorder, gumStream;
		var recordButton = document.getElementById(\"recordButton\");
		recordButton.addEventListener(\"click\", toggleRecording);

		function toggleRecording() {
			if (recorder && recorder.state == \"recording\") {
				recorder.stop();
				gumStream.getAudioTracks()[0].stop();
			} 
			else {
		        	navigator.mediaDevices.getUserMedia({
					audio: true
				}).then(function(stream) {
					gumStream = stream;
					recorder = new MediaRecorder(stream);
					recorder.ondataavailable = function(e) {
					var url = URL.createObjectURL(e.data);
						var preview = document.createElement('audio');
						preview.controls = true;
						preview.src = url;
						document.body.appendChild(preview);
					};
					recorder.start();
				});
			}
		}
		</script>
	<!-- //Record sounds -->

	";
} // action == ""
?>