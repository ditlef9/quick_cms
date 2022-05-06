<?php
/**
*
* File: _admin/_inc/settings/slogan.php
* Version 1.0.0
* Date 16:09 04-Aug-18
* Copyright (c) 2008-2018 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

if($process == 1){



	$input_slogan = array();

	$dir = "_translations/site/";
	if ($handle = opendir($dir)) {
		while (false !== ($content = readdir($handle))) {
			if ($content === '.') continue;
			if ($content === '..') continue;

			$input = $_POST["inp_slogan_$content"];
			$input = output_html($input);

			$fh = fopen("_data/slogan/$content.php", "w+") or die("can not open file");
			fwrite($fh, "<?php
\$SloganSav = \"$input\";
?>");
			fclose($fh);


		}
	}
	
	$url = "?open=$open&page=$page&ft=success&fm=changes_saved";
	
	header("Location: $url");
	exit;
}
if(!(is_dir("_data/slogan"))){
	mkdir("_data/slogan");
}


echo"
<h1>Slogan</h1>


<!-- Feedback -->
	";
	if($ft != "" && $fm != ""){
		echo"<div class=\"$ft\"><p>$fm</p></div>";
	}
	echo"
<!-- //Feedback -->

<!-- Slogan -->
	
	<form method=\"post\" action=\"?open=$open&amp;page=$page&amp;process=1\" enctype=\"multipart/form-data\">

	";

	$x = 0;
	$dir = "_translations/site/";
	if ($handle = opendir($dir)) {
		while (false !== ($content = readdir($handle))) {
			if ($content === '.') continue;
			if ($content === '..') continue;


			
			if($x == 0){

				echo"<script>
				\$(document).ready(function(){
					\$('[name=\"inp_slogan_$content\"]').focus();
				});
				</script>
				";
			}

			echo"
			<p><img src=\"_translations/site/$content/$content.png\" alt=\"_translations/site/$content/$content.png\" />
			$content<br />
			<input type=\"text\" name=\"inp_slogan_$content\" value=\""; if(file_exists("_data/slogan/$content.php")){ include("_data/slogan/$content.php"); echo"$SloganSav"; } echo"\" size=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>

			";

			$x++;
		}
	}
	echo"	
	</p>

	<p>
	<input type=\"submit\" value=\"$l_save\" class=\"btn\" />
	</p>
<!-- //Slogan -->
";
			
?>