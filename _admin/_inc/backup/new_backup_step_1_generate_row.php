<?php
/**
*
* File: _admin/_inc/backup/new_backup_step_1_generate_row.php
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
$t_backup_last_backed_up_modules = $mysqlPrefixSav . "backup_last_backed_up_modules";


// Truncate temp tables
mysqli_query($link, "TRUNCATE $t_backup_last_backed_up_modules");

/*- Script start ------------------------------------------------------------------------ */

// Create new backup
// Date
$date = date("Y-m-d");
$datetime = date("Y-m-d H:i:s");
$datetime_saying = date("j M Y H:i");
$datetime_clean = date("Y-m-d_H-i");
$time = time();

// Create secret
$alphabet = 'abcdefghijklmnopqrstuvwxyz';
$pass = array(); //remember to declare $pass as an array
$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
for ($i = 0; $i < 8; $i++) {
	$n = rand(0, $alphaLength);
	$pass[] = $alphabet[$n];
}
$inp_secret = implode($pass); 
$inp_secret_mysql = quote_smart($link, $inp_secret);

// Directory name
$inp_zip_dir = "";
$inp_zip_dir_mysql = quote_smart($link, $inp_zip_dir);

// File name
$inp_zip_file = $configWebsiteTitleCleanSav . "_" . $datetime_clean . "_" . $inp_secret . ".zip";
$inp_zip_file_mysql = quote_smart($link, $inp_zip_file);

// Test
$inp_test = $_POST["inp_test"];
$inp_test = output_html($inp_test);
$inp_test_mysql = quote_smart($link, $inp_test);
if($inp_test == "1"){
	mysqli_query($link, "INSERT INTO $t_backup_last_backed_up_modules
	(module_id, module_name, module_name_clean) 
	VALUES 
	(NULL, 'Test', 'test')")
	or die(mysqli_error($link));
}

// Insert
mysqli_query($link, "INSERT INTO $t_backup_index
(backup_id, backup_created_datetime, backup_created_datetime_saying, backup_created_date, backup_zip_dir, backup_zip_file, 
backup_secret, backup_is_finished, backup_no_of_modules_finished, backup_start, backup_test) 
VALUES 
(NULL, '$datetime', '$datetime_saying', '$date', $inp_zip_dir_mysql, $inp_zip_file_mysql, 
$inp_secret_mysql, 0, 0, '$time', $inp_test)")
or die(mysqli_error($link));

// Get ID
$query_t = "SELECT backup_id, backup_created_datetime, backup_created_datetime_saying, backup_secret FROM $t_backup_index WHERE backup_created_datetime='$datetime'";
$result_t = mysqli_query($link, $query_t);
$row_t = mysqli_fetch_row($result_t);
list($get_current_backup_id, $get_current_backup_created_datetime, $get_current_backup_created_datetime_saying, $get_current_backup_secret) = $row_t;

// Show all modules that has backup functions
$no_of_modules_available = 0;
$inp_backup_no_of_modules_total = 0;
$filenames = "";
$dir = "_inc/";
if ($handle = opendir($dir)) {
	$files = array();   
	while (false !== ($file = readdir($handle))) {
		if ($file === '.') continue;
		if ($file === '..') continue;
		if ($file === "admin_cms") continue;
		if ($file === "dashboard") continue;
		if ($file === "backup") continue;
		if ($file === "login") continue;
		if ($file === "ucp") continue;
		if ($file === "setup") continue;
		array_push($files, $file);
	}
			
	sort($files);
	foreach ($files as $file){
		// $content_saying = 
		$admin_navigation_clean = "$file";

		if(isset($_POST["inp_$admin_navigation_clean"])){
			// Inser it 
			$inp_name = ucfirst($file);
			$inp_name = str_replace("_", " ", $inp_name);
			$inp_name_mysql = quote_smart($link, $inp_name);

			$inp_name_clean_mysql = quote_smart($link, $file);

			$inp_icon_black_small = $file . "_black_18x18.png";
			$inp_icon_black_small_mysql = quote_smart($link, $inp_icon_black_small);

			// Does module exists? (Backup of module can be marked as finished finished if file doesnt exits)
			$inp_finished = 0;
			if(!(file_exists("_inc/backup/_tables_and_dirs_to_backup/$file.php"))){
				$inp_finished = 1;
			}
			$inp_finished_mysql = quote_smart($link, $inp_finished);

			mysqli_query($link, "INSERT INTO $t_backup_modules
			(module_id, module_backup_id, module_name, module_name_clean, module_icon_black_18x18, 
			module_tables_finished, module_files_finished, module_backup_completed) 
			VALUES 
			(NULL, $get_current_backup_id, $inp_name_mysql, $inp_name_clean_mysql, $inp_icon_black_small_mysql, 
			$inp_finished_mysql, $inp_finished_mysql, $inp_finished_mysql)")
			or die(mysqli_error($link));

			mysqli_query($link, "INSERT INTO $t_backup_last_backed_up_modules
			(module_id, module_name, module_name_clean) 
			VALUES 
			(NULL, $inp_name_mysql, $inp_name_clean_mysql)")
			or die(mysqli_error($link));



			$inp_backup_no_of_modules_total++;
		}
		$no_of_modules_available++;

	} // files
	
} // modules
if($no_of_modules_available == "$inp_backup_no_of_modules_total"){
	mysqli_query($link, "INSERT INTO $t_backup_last_backed_up_modules
	(module_id, module_name, module_name_clean) 
	VALUES 
	(NULL, 'All', 'all')")
	or die(mysqli_error($link));
}

// Update no of modules
mysqli_query($link, "UPDATE $t_backup_index SET 
			backup_no_of_modules_total=$inp_backup_no_of_modules_total
			WHERE backup_id=$get_current_backup_id")
			or die(mysqli_error($link));

// Check if number of modules is over 0
if($inp_backup_no_of_modules_total < 0){
	// No modules selected
	$url = "index.php?open=backup&page=new_backup&editor_language=$editor_language&l=$l&ft=warning&fm=no_modules_selected";
	header("Location: $url");
	exit;
}
else{
	// x modules selected
	
	// Create dir
	if(!(is_dir("_data/backup"))){
		mkdir("_data/backup");
	}
	$fh = fopen("_data/backup/index.html", "w+") or die("can not open file");
	fwrite($fh, "Server error 403");
	fclose($fh);

	if(!(is_dir("_data/backup/$inp_secret"))){
		mkdir("_data/backup/$inp_secret");
	}
	$fh = fopen("_data/backup/$inp_secret/index.html", "w+") or die("can not open file");
	fwrite($fh, "Server error 403");
	fclose($fh);



	// Header
	$url = "index.php?open=backup&page=new_backup_step_2_list_modules&backup_id=$get_current_backup_id&editor_language=$editor_language&l=$l";
	header("Location: $url");
	exit;

}

?>