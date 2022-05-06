<?php

/*- Check if setup is run ------------------------------------------------------------ */
$server_name = $_SERVER['HTTP_HOST'];
$server_name = clean($server_name);
$setup_finished_file = "setup_finished_" . $server_name . ".php";
if(file_exists("../_data/$setup_finished_file")){
	echo"Setup is finished.";
	die;
}

/*- Read licence -*/
$file = "../../LICENSE";
$fh = fopen($file, "r");
$read_licence = fread($fh, filesize($file));
fclose($fh); 
$read_licence = str_replace("<", "&lt;", $read_licence);
$read_licence = str_replace(">", "&gt;", $read_licence);
$read_licence = str_replace('"', "&quot;", $read_licence);


echo"
<h1>$l_licence</h1>

<pre>
$read_licence
</pre>

<p>
<a href=\"$cmsWebsiteSav\" class=\"btn_default\">$l_decline</a>
<a href=\"index.php?page=03_chmod&amp;language=$language\" class=\"btn_default\">$l_agree</a>
</p>
";

?>

