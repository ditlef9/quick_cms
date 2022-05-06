<?php

/*- Check if setup is run ------------------------------------------------------------ */
$server_name = $_SERVER['HTTP_HOST'];
$server_name = clean($server_name);
$setup_finished_file = "setup_finished_" . $server_name . ".php";
if(file_exists("../_data/$setup_finished_file")){
	echo"Setup is finished.";
	die;
}


if($process == 1){
	$inp_language = $_POST['inp_language'];
	$inp_language = output_html($inp_language);
	
	// Header
	header("Location: index.php?page=02_licence&language=$inp_language");
	exit;
}

echo"
<h1>$l_select_language</h1>

<form method=\"post\" action=\"index.php?page=01_select_language&amp;process=1\" enctype=\"multipart/form-data\">

	
	<select name=\"inp_language\">
		";
		$path = "../_translations/admin/";
		if (is_dir($path)) {
			if ($dh = opendir($path)) {
				while (($file = readdir($dh)) !== false) {
					if($file != ".." && $file != "."){

						if(!(is_dir("../_translations/admin/$file/setup"))){
							mkdir("../_translations/admin/$file/setup");
						}

						if(!(file_exists("../_translations/admin/$file/setup/setup.php"))){
							$input="<?php \$l_form_option_language = \"Please edit ../_translations/admin/$file/setup/setup.php\"; ?>";
							$fh = fopen("../_translations/admin/$file/setup/setup.php", "w+") or die("can not open file");
							fwrite($fh, $input);
							fclose($fh);
						}

						include("../_translations/admin/$file/setup/setup.php");
						
						echo"<option value=\"$file\">$file</option>\n";

					}
				}
			}
		}
	echo"</select>

	<p>
	<input type=\"submit\" value=\"$l_next\" class=\"submit\" />
	</p>

</form>

";
?>

