<?php

/*- Check if setup is run ------------------------------------------------------------ */
$server_name = $_SERVER['HTTP_HOST'];
$server_name = clean($server_name);
$setup_finished_file = "setup_finished_" . $server_name . ".php";
if(file_exists("../_data/$setup_finished_file")){
	echo"Setup is finished.";
	die;
}

/*- Functions  ------------------------------------------------------------ */
function chmod_r($path) {
    $dir = new DirectoryIterator($path);
    foreach ($dir as $item) {
        chmod($item->getPathname(), 0777);
        if ($item->isDir() && !$item->isDot()) {
            chmod_r($item->getPathname());
        }
    }
}


/*- Scriptstart ---------------------------------------------------------------------------- */
$directories = array(
			"../_data",
			"../_translations",
			"../../_cache",
			"../../_uploads"
);



echo"
<h1>$l_chmod</h1>

<p>
$l_the_server_will_now_try_to_give_write_permissions_to_the_directories
</p>

	<table class=\"hor-zebra\">
	 <thead>
	  <tr>
	   <th scope=\"col\">
		<span>$l_directory</span>
	   </th>
	   <th scope=\"col\">
		<span>$l_status</span>
	   </th>
	  </tr>
	</thead>
	<tbody>
";

for($x=0;$x<sizeof($directories);$x++){
	// Style
	if(isset($style) && $style == ""){
		$style = "odd";
	}
	else{
		$style = "";
	}

	echo"
	 <tr>
	  <td class=\"$style\">
		<span><a href=\"$directories[$x]\">$directories[$x]</a></span>
	  </td>
	  <td class=\"$style\">
		";
		// Does folder exist?
		if(!(is_dir("$directories[$x]"))){
			mkdir("$directories[$x]");
		}
		// Try to write
		$fh = fopen("$directories[$x]/index.php", "w") or die("can not open file");
		fwrite($fh, "Server error 403");
		fclose($fh);

		// Chmod the directory
		if(!(file_exists("$directories[$x]/index.php"))){
			chmod_r("$directories[$x]");

			// Try to rewrite now
			$fh = fopen("$directories[$x]/index.php", "w") or die("can not open file");
			fwrite($fh, "Server error 403");
			fclose($fh);

		}	

		if(file_exists("$directories[$x]/index.php")){
			echo"<span style=\"color: green;\">$l_writable</span>\n";
		}
		else{
			echo"<span style=\"color: red;\">$l_not_writable</span>\n";
		}
		echo"
	  </td>
	 </tr>
	";
} // for
echo"
	 </tbody>
	</table>

<p>
<a href=\"index.php?page=04_database&amp;language=$language\" class=\"btn_default\">$l_continue</a>
</p>
";

?>

