<?php
/**
*
* File: _admin/_inc/backup/restore_step_2_db.php
* Version 20:18 12.01.2022
* Copyright (c) 2022 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ------------------------------------------------------------------------ */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables Backup ----------------------------------------------------------------------- */
$t_backup_liquidbase	 = $mysqlPrefixSav . "backup_liquidbase";
$t_backup_index 	 = $mysqlPrefixSav . "backup_index";
$t_backup_modules	 = $mysqlPrefixSav . "backup_modules";
$t_backup_directories	 = $mysqlPrefixSav . "backup_directories";
$t_backup_files		 = $mysqlPrefixSav . "backup_files";



/*- Variables -------------------------------------------------------------------------- */
if(isset($_GET['file'])) {
	$file = $_GET['file'];
	$file = strip_tags(stripslashes($file));
	if (strpos($file, '/') !== FALSE) {
		echo"File has illegal character";
		die;
	}
}
else{
	echo"Missing file";
	die;
	$file = "";
}


/*- Script start ------------------------------------------------------------------------ */
if(!(file_exists("../$file"))){
	echo"File doesnt exists.";
	die;
} // file exists

// Correct file type
$mime = mime_content_type("../$file");
if($mime != "application/zip"){
	echo"Invalid mime";
	die;
}

// Start
echo"
<h1><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Restore backup $file</h1>

<p>
Now looking for SQL files to process.
</p>


<div class=\"backup_row\">
	<div class=\"backup_column_left\">
		<!-- SQL files -->
			
			<table class=\"hor-zebra\">
			 <thead>
			  <tr>
			   <th scope=\"col\">
				<span><b>Record</b></span>
			   </th>
			  </tr>
			 </thead>
			 <tbody>";

			// Show all modules that has backup functions
			$no_of_sql_files = 0;
			$selected_sql_file = "";
			$filenames = "";
			$dir = "../_DATABASE_$file/";
			$dir = str_replace(".zip", "", $dir);
			if ($handle = opendir($dir)) {
				$records = array();   
				while (false !== ($record = readdir($handle))) {
					if ($record === '.') continue;
					if ($record === '..') continue;
					if ($record === 'index.html') continue;
					array_push($records, $record);
				}
			}

			sort($records);
			foreach ($records as $record){
				echo"
				  <tr>
				   <td>
					<span>$record</span>
				   </td>
				  </tr>
				";

				if($selected_sql_file == ""){
					$selected_sql_file = "$dir$record";
				}

				$no_of_sql_files++;
			} // foreach
			echo"
			 </tbody>
			</table>
		<!-- //SQL files -->
	</div>
	<div class=\"backup_column_right\">
		";
		if($selected_sql_file != ""){
			echo"
			<h2>$selected_sql_file</h2>

			<!-- Manually delete and continue -->
				<p>
				If the restore process stops you can skip the file by deleting it manually and continue to next file. 
				To do this click here:<br /><br />
				<a href=\"index.php?open=backup&amp;page=restore_step_2_db&amp;file=$file&amp;editor_language=$editor_language&amp;l=$l&amp;rand=-1&amp;mode=manually_delete_and_continue\" class=\"btn_default\">Manually delete and continue</a>
				</p>
			<!-- //Manually delete and continue -->
			";
			// Manually delete and continue 
			if(isset($_GET['mode'])){
				$mode = $_GET['mode'];
				$mode = strip_tags(stripslashes($mode));
				if($mode == "manually_delete_and_continue"){
					if(file_exists("$selected_sql_file")){
						unlink("$selected_sql_file");
					}
					echo"
					<p style=\"color: red\">Deleting file...</p>
					<meta http-equiv=refresh content=\"10; URL=index.php?open=$open&amp;page=$page&amp;file=$file&amp;editor_language=$editor_language&amp;l=$l\">
					";

			

				}
			}


			// Read file
			$myfile = fopen("$selected_sql_file", "r") or die("Unable to open file!");
			$data = fread($myfile,filesize("$selected_sql_file"));
			fclose($myfile);
	
			// Colors
			$colors = array('red', "Cyan", "Blue", "DarkBlue", "LightBlue", "Purple", "Lime", "Magenta", "Pink", "Silver", "Grey", "Black", "Orange", "Brown", "Maroon", "Green", "Olive", "Aquamarine");
			$colors_size = sizeof($colors);
			
			// SQL
			$results = array();

			// Get date and prefix
			$data_lines = explode("\n", $data);
			$first_line = $data_lines[0];
			$first_line = str_replace("-- SQL BACKUP ", "", $first_line);
			
			$first_line_array = explode(" ", $first_line);
			$date = $first_line_array[0];
			$prefix = $first_line_array[1];

			$prefix = str_replace("(", "", $prefix);
			$prefix = str_replace(")", "", $prefix);
			$prefix = trim($prefix);

			// Get prefix
			echo"<p><b>Date:</b> $date<br />
			<b>Prefix:</b> $prefix
			</p>";


			// Get data			
			$data_array = explode("-- SQL BACKUP $date ($prefix)", $data);
			$data_array_size = sizeof($data_array);
			echo"<p>Number of queries is $data_array_size.</p>";
			for($x=0;$x<$data_array_size;$x++){

				$data_array_trim = trim($data_array[$x]);
				if($data_array_trim != ""){

					// Replace prefix
					$data_array[$x] = str_replace("DROP TABLE IF EXISTS $prefix", "DROP TABLE IF EXISTS $mysqlPrefixSav", $data_array[$x]);
					$data_array[$x] = str_replace("CREATE TABLE $prefix", "CREATE TABLE $mysqlPrefixSav", $data_array[$x]);
					$data_array[$x] = str_replace("INSERT INTO $prefix", "INSERT INTO $mysqlPrefixSav", $data_array[$x]);

					echo"
					<pre>$data_array[$x]</pre>
					";
					mysqli_query($link, $data_array[$x]) or die(mysqli_error($link));
				}
			}

			unlink("$selected_sql_file");

			// Refresh
			$rand = rand(0,2);
			echo"
			<meta http-equiv=refresh content=\"$rand; URL=index.php?open=$open&amp;page=$page&amp;file=$file&amp;editor_language=$editor_language&amp;l=$l&amp;rand=$rand\">
			<!-- Jquery go to URL after x seconds -->
				<!-- In case meta refresh doesnt work -->
				<script>
				\$(document).ready(function(){
					window.setTimeout(function(){
						// Move to a new location or you can do something else
						window.location.href = \"index.php?open=$open&page=restore_step_2_db&file=$file&editor_language=$editor_language&l=$l&rand=$rand\";
					}, 10000);
				});
				</script>
			<!-- //Jquery go to URL after x seconds -->
			";


		} // sql file 
		else{
			echo"
			<p>Now deleting index.html, database dir and zip file.</p>
			";

			// Rm database dir
			$dir = "../_DATABASE_$file/";
			$dir = str_replace(".zip", "", $dir);
			unlink("$dir/index.html");
			rmdir("$dir");

			// Unlink zip
			unlink("../$file");
			

			// Refresh
			$rand = rand(3,4);
			echo"
			<meta http-equiv=refresh content=\"$rand; URL=index.php?open=$open&amp;page=restore&amp;editor_language=$editor_language&amp;l=$l&amp;ft=success&amp;fm=backup_restored\">
			<!-- Jquery go to URL after x seconds -->
				<!-- In case meta refresh doesnt work -->
				<script>
				\$(document).ready(function(){
					window.setTimeout(function(){
						// Move to a new location or you can do something else
						window.location.href = \"index.php?open=$open&page=restore_step_3_users&editor_language=$editor_language&l=$l\";
					}, 10000);
				});
				</script>
			<!-- //Jquery go to URL after x seconds -->
			";
		}
		echo"
	</div> <!-- //backup_column_right -->
</div> <!-- //backup_row -->
";
					
?>