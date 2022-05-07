<?php
/**
*
* File: _admin/_inc/backup/new_backup_step_3_take_backup_of_tables.php
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
$t_backup_files		 = $mysqlPrefixSav . "backup_files";


/*- Variables -------------------------------------------------------------------------- */
if(isset($_GET['module_id'])) {
	$module_id = $_GET['module_id'];
	$module_id = strip_tags(stripslashes($module_id));
	if(!(is_numeric($module_id))){
		echo"Module id not numeric";
		die;
	}
}
else{
	echo"Missing module id";
	die;
	$module_id = "";
}
if(isset($_GET['table_no'])) {
	$table_no = $_GET['table_no'];
	$table_no = strip_tags(stripslashes($table_no));
	if(!(is_numeric($table_no))){
		echo"table_no not numeric";
		die;
	}
}
else{
	$table_no = "";
}


/*- Script start ------------------------------------------------------------------------ */
// Find module
$module_id_mysql = quote_smart($link, $module_id);
$query_t = "SELECT module_id, module_backup_id, module_name, module_name_clean, module_icon_black_18x18, module_tables_finished, module_files_finished, module_backup_completed FROM $t_backup_modules WHERE module_id=$module_id_mysql";
$result_t = mysqli_query($link, $query_t);
$row_t = mysqli_fetch_row($result_t);
list($get_current_module_id, $get_current_module_backup_id, $get_current_module_name, $get_current_module_name_clean, $get_current_module_icon_black_18x18, $get_current_module_tables_finished, $get_current_module_files_finished, $get_current_module_backup_completed) = $row_t;
if($get_current_module_id == ""){
	echo"
	<h1>Module not found</h1>
	<p>
	<a href=\"index.php?open=$open&amp;page=backup&amp;editor_language=$editor_language&amp;l=$l\">Backup</a>
	</p>
	";
}
else{
	// Find backup
	$query_t = "SELECT backup_id, backup_created_datetime, backup_created_datetime_saying, backup_created_date, backup_zip_dir, backup_zip_file, backup_zip_size, backup_zip_size_human, backup_zip_md5, backup_secret, backup_is_finished, backup_no_of_modules_total, backup_no_of_modules_finished, backup_start, backup_end, backup_time_used FROM $t_backup_index WHERE backup_id=$get_current_module_backup_id";
	$result_t = mysqli_query($link, $query_t);
	$row_t = mysqli_fetch_row($result_t);
	list($get_current_backup_id, $get_current_backup_created_datetime, $get_current_backup_created_datetime_saying, $get_current_backup_created_date, $get_current_backup_zip_dir, $get_current_backup_zip_file, $get_current_backup_zip_size, $get_current_backup_zip_size_human, $get_current_backup_zip_md5, $get_current_backup_secret, $get_current_backup_is_finished, $get_current_backup_no_of_modules_total, $get_current_backup_no_of_modules_finished, $get_current_backup_start, $get_current_backup_end, $get_current_backup_time_used) = $row_t;
	if($get_current_backup_id == ""){
		echo"
		<p>Backup not found</p>
		<p>
		<a href=\"index.php?open=$open&amp;page=backup&amp;editor_language=$editor_language&amp;l=$l\">Backup</a>
		</p>
		";
	}
	else{
		echo"
		<h1><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" /> New Backup no $get_current_backup_id</h1>


		<div class=\"backup_row\">
			<div class=\"backup_column_left\">
				";
				include("new_backup_step_include_list_modules_status.php");
				echo"
			</div>
			<div class=\"backup_column_right\">
		";


		// Include tables and directories
		if(!(file_exists("_inc/backup/_tables_and_dirs_to_backup/$get_current_module_name_clean.php"))){
			// Update done with content
			mysqli_query($link, "UPDATE $t_backup_modules  SET module_tables_finished=1, module_files_finished=1, module_backup_completed=1 WHERE module_id=$get_current_module_id") or die(mysqli_error($link));


			echo"
			<p>Tables and directories files doesnt exists for the module $get_current_module_name_clean 
			(<a href=\"_inc/backup/_tables_and_dirs_to_backup/$get_current_module_name_clean.php\">_inc/backup/_tables_and_dirs_to_backup/$get_current_module_name_clean.php</a>)</p>
			
			<!-- Refresh -->
				<meta http-equiv=refresh content=\"1; URL=index.php?open=$open&amp;page=new_backup_step_2_list_modules&amp;backup_id=$get_current_backup_id&amp;editor_language=$editor_language&amp;l=$l\">
				<!-- Jquery go to URL after x seconds -->
				<!-- In case meta refresh doesnt work -->
   					<script>
					\$(document).ready(function(){
						window.setTimeout(function(){
        						// Move to a new location or you can do something else
							window.location.href = \"index.php?open=$open&page=new_backup_step_2_list_modules&backup_id=$get_current_backup_id&editor_language=$editor_language&l=$l\";
						}, 10000);
					});
   					</script>
				<!-- //Jquery go to URL after x seconds -->
			<!-- //Refresh -->";
		}
		else{
			// Include tables file
			include("_inc/backup/_tables_and_dirs_to_backup/$get_current_module_name_clean.php");
			

			if($action == ""){


				echo"
				<!-- Operation -->
					<h2>$get_current_module_name</h2>
				<!-- //Operation -->


				<!-- Feedback -->
				";
					if($ft != ""){
						if($fm == "changes_saved"){
							$fm = "$l_changes_saved";
						}
						else{
							$fm = ucfirst($fm);
							$fm = str_replace("_", " ", $fm);
						}
						echo"<div class=\"$ft\"><span>$fm</span></div>";
					}
					echo"	
				<!-- //Feedback -->

				<!-- Header -->
					";
					$create_table = "";
					$size = sizeof($tables_backup_array);
					for($x=0;$x<$size;$x++){
						if(isset($tables_backup_array[$x])){
							echo"
							<h3>$tables_backup_array[$x]</h3>
							";

							// Check if table exists
							$query = "SELECT * FROM $tables_backup_array[$x] LIMIT 1";
							$result = mysqli_query($link, $query);
							if($result !== FALSE){
								

								// Head
								// Ready create table
								$create_table_name = "$tables_backup_array[$x]";
								if($tables_backup_array[$x] == "$t_users"){
									$create_table_name = $t_users . "_tmp";
								}

								$create_table = "-- SQL BACKUP $get_current_backup_created_date ($mysqlPrefixSav)

DROP TABLE IF EXISTS $create_table_name;

-- SQL BACKUP $get_current_backup_created_date ($mysqlPrefixSav)
CREATE TABLE $create_table_name(
";

								// Fields
								$y = 0;
								$query = "SHOW COLUMNS FROM $tables_backup_array[$x]";
								$result = mysqli_query($link, $query);
								while($row = mysqli_fetch_row($result)) {
									list($get_column_name) = $row;

									// Get information about that column
									$query_column = "SELECT COLUMN_NAME, COLUMN_DEFAULT, IS_NULLABLE, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH, NUMERIC_PRECISION, COLUMN_TYPE, COLUMN_KEY, EXTRA FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '$tables_backup_array[$x]' AND COLUMN_NAME='$get_column_name'";
									$result_column = mysqli_query($link, $query_column);
									$row_column = mysqli_fetch_row($result_column);
									list($get_column_name, $get_column_default, $get_is_nullable, $get_data_type, $get_character_maximum_lenght, $get_mumeric_precision, $get_column_type, $get_column_key, $get_extra) = $row_column;

									$get_data_type = strtoupper($get_data_type);
									$get_extra = strtoupper($get_extra);


									if($y > 1){
										$create_table = $create_table . ",
";
									}

									$create_table = $create_table . " $get_column_name $get_data_type";
				
									if($get_data_type == "VARCHAR"){
										$create_table = $create_table . "($get_character_maximum_lenght)";
									}
									if($get_is_nullable == "NO"){
										$create_table = $create_table . " NOT NULL";
									}
									if($get_extra == "AUTO_INCREMENT"){
										$create_table = $create_table . " AUTO_INCREMENT";
									}




									if($get_column_key == "PRI"){
										$create_table = $create_table . ",
 PRIMARY KEY($get_column_name), 
";
									}

									$y++;

								}
								// Create table footer
								$create_table = $create_table . ");



";

								// Echo
								echo"<pre>$create_table</pre>
								";

								// Write to file
								$db_table_backup_file = "$get_current_module_name_clean" . "_db_" . $tables_backup_array[$x] . ".sql.txt";


								$fh = fopen("_data/backup/$get_current_backup_secret/$db_table_backup_file", "w+") or die("can not open file");
								fwrite($fh, $create_table);
								fclose($fh);

								// Add file to files so we will take backup of it
								$inp_file_path = "_data/backup/$get_current_backup_secret/$db_table_backup_file";
								$inp_file_path = realpath($inp_file_path);
								$inp_file_path_mysql = quote_smart($link, $inp_file_path);

								$datetime_clean = str_replace(" ", "_", $get_current_backup_created_datetime);
								$datetime_clean = str_replace(":", "-", $datetime_clean);
								$datetime_clean = substr($datetime_clean, 0, -3);

								$inp_relative_path = "_DATABASE_" . $configWebsiteTitleCleanSav . "_" . $datetime_clean . "_" . $get_current_backup_secret . "/$db_table_backup_file";
								if (strpos($inp_relative_path, '\\') !== FALSE OR strpos($inp_relative_path, 'backup/') !== FALSE) {
									$inp_relative_path = "_DATABASE_" . $configWebsiteTitleCleanSav . "_" . $datetime_clean . "_" . $get_current_backup_secret . "\\$db_table_backup_file";
								}
								$inp_relative_path_mysql = quote_smart($link, $inp_relative_path);

								mysqli_query($link, "INSERT INTO $t_backup_files 
								(file_id, file_backup_id, file_module_id, file_directory_id, file_file_path, 
								file_relative_path, file_size) 
								VALUES 
								(NULL, $get_current_backup_id, 0, 0, $inp_file_path_mysql, 
								$inp_relative_path_mysql, 0)") or die(mysqli_error($link));


							} // table exists
							else{
								echo"<p>Table $tables_backup_array[$x] doesn't exists. Skipping it...</p>\n";
							}
							echo"
							<span><hr /></span>
							";
						} // isset table
					} // for tables
					echo"
				<!-- //Header -->

				<!-- Refresh -->";

					if($create_table != ""){

						$rand = rand(0,1);
						echo"<p>
						Done with header, starting with content!
						</p>
						<meta http-equiv=\"refresh\" content=\"$rand; url=index.php?open=$open&amp;page=$page&amp;action=content&amp;table_no=0&amp;module_id=$get_current_module_id&amp;editor_language=$editor_language&amp;l=$l\" />
						";
					}
					else{
						// Update done with content
						mysqli_query($link, "UPDATE $t_backup_modules SET module_tables_finished=1 WHERE module_id=$get_current_module_id") or die(mysqli_error($link));

						// Next page
						$rand = rand(0,1);
						echo"
						<p>
						Has no tables, starting with files...
						</p>

						<meta http-equiv=\"refresh\" content=\"$rand; url=index.php?open=$open&amp;page=new_backup_step_2_list_modules&amp;backup_id=$get_current_backup_id&amp;editor_language=$editor_language&amp;l=$l\" />
						";
					}
					echo"
				<!-- //Refresh -->";

			} // action == ""
			elseif($action == "content"){
				if(isset($_GET['content_file_no'])) {
					$content_file_no = $_GET['content_file_no'];
					$content_file_no = strip_tags(stripslashes($content_file_no));
					if(!(is_numeric($content_file_no))){
						echo"content_file_ not numeric";
						die;
					}
				}
				else{
					$content_file_no = "0";
				}

				echo"
				
				<!-- Operation -->
					<h2>$get_current_module_name Tables Content $table_no</h2>
				<!-- //Operation -->


				<!-- Feedback -->
				";
					if($ft != ""){
						if($fm == "changes_saved"){
							$fm = "$l_changes_saved";
						}
						else{
							$fm = ucfirst($fm);
							$fm = str_replace("_", " ", $fm);
						}
						echo"<div class=\"$ft\"><span>$fm</span></div>";
					}
					echo"	
				<!-- //Feedback -->

				
				";


				if(isset($tables_backup_array[$table_no])){
					echo"
					<h3>$tables_backup_array[$table_no]</h3>
					";

					// Check if table exists
					$query = "SELECT * FROM $tables_backup_array[$table_no] LIMIT 1";
					$result = mysqli_query($link, $query);
					if($result !== FALSE){

						$insert_table_name = "$tables_backup_array[$table_no]";
						if($tables_backup_array[$x] == "$t_users"){
							$insert_table_name = $t_users . "_tmp";
						}

						// Insert header :: Ready create table
						$insert_statement_header = "-- SQL BACKUP $get_current_backup_created_date ($mysqlPrefixSav)
INSERT INTO $insert_table_name(
";
						// Fields
						$count_fields = 0;
						$table_column_types = array();
						$query = "SHOW COLUMNS FROM $tables_backup_array[$table_no]";
						$result = mysqli_query($link, $query);
						while($row = mysqli_fetch_row($result)) {
							list($get_column_name) = $row;

							// Get information about that column
							$query_column = "SELECT COLUMN_NAME, COLUMN_DEFAULT, IS_NULLABLE, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH, NUMERIC_PRECISION, COLUMN_TYPE, COLUMN_KEY, EXTRA FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '$tables_backup_array[$table_no]' AND COLUMN_NAME='$get_column_name'";
							$result_column = mysqli_query($link, $query_column);
							$row_column = mysqli_fetch_row($result_column);
							list($get_column_name, $get_column_default, $get_is_nullable, $get_data_type, $get_character_maximum_lenght, $get_mumeric_precision, $get_column_type, $get_column_key, $get_extra) = $row_column;

							$get_data_type = strtoupper($get_data_type);
							$get_extra = strtoupper($get_extra);


							if($count_fields > 0){
								$insert_statement_header = $insert_statement_header . ", ";
							}

							$insert_statement_header = $insert_statement_header . "$get_column_name";


							// Add to columns types
							$table_column_types[$count_fields] = "$get_data_type";

							$count_fields++;

						}
						// Create table footer
						$insert_statement_header = $insert_statement_header . ")
VALUES ";
			
			
						// Fetch data
						$count_rows = 0;
						$date = date("Y-m-d");
						$datetime = date("Y-m-d H:i:s");
						$time = time();
						$insert_statement_body = "";
						$query = "SELECT * FROM $tables_backup_array[$table_no]";
						$result = mysqli_query($link, $query);
						while($row = mysqli_fetch_row($result)) {

							if($count_rows > 0){
								$insert_statement_body = $insert_statement_body . ",";

							}
							$insert_statement_body = $insert_statement_body . "
(";

							$count = count($row, COUNT_RECURSIVE);
							for($x=0;$x<$count;$x++){
	
								// Data
								if($table_column_types[$x] == "INT" && $row[$x] == ""){
									$input_data = "0";
								}
								elseif($table_column_types[$x] == "DATE" && $row[$x] == ""){
									if($row[$x] == ""){
										$row[$x] = "$date";
									}
									$input_data = "'" . "$row[$x]" . "'";
								}
								elseif($table_column_types[$x] == "DATETIME" && $row[$x] == ""){
									if($row[$x] == ""){
										$row[$x] = "$datetime";
									}
									$input_data = "'" . "$row[$x]" . "'";
								}
								elseif($table_column_types[$x] == "TIME" && $row[$x] == ""){
									if($row[$x] == ""){
										$row[$x] = "$time";
									}
									$input_data = "NULL";
								}
								elseif($table_column_types[$x] == "DOUBLE" && $row[$x] == ""){
									$input_data = "NULL";
								}
								else{
									$input_data =  quote_smart($link, $row[$x]);
								}


								if($x > 0){
									$insert_statement_body = $insert_statement_body . ", ";
								}
								$insert_statement_body = $insert_statement_body . $input_data;
							}
							$count_rows++;

							$insert_statement_body = $insert_statement_body . ")";

							if($count_rows == "20"){
								// Start on a new file!
								$insert_statement = $insert_statement_header . $insert_statement_body;
								$db_table_backup_file = "$get_current_module_name_clean" . "_db_" . $tables_backup_array[$table_no] . "_file_" . $content_file_no. ".sql.txt";
								$fh = fopen("_data/backup/$get_current_backup_secret/$db_table_backup_file", "a+") or die("can not open file");
								fwrite($fh, "$insert_statement");
								fclose($fh);

								echo"
								<span><b>Write to $db_table_backup_file</b></span>
								<pre>$insert_statement</pre>
								";



								// Add file to files so we will take backup of it
								$inp_file_path = "_data/backup/$get_current_backup_secret/$db_table_backup_file";
								$inp_file_path = realpath($inp_file_path);
								$inp_file_path_mysql = quote_smart($link, $inp_file_path);

								$datetime_clean = str_replace(" ", "_", $get_current_backup_created_datetime);
								$datetime_clean = str_replace(":", "-", $datetime_clean);
								$datetime_clean = substr($datetime_clean, 0, -3);

								$inp_relative_path = "_DATABASE_" . $configWebsiteTitleCleanSav . "_" . $datetime_clean . "_" . $get_current_backup_secret . "/$db_table_backup_file";
								if (strpos($inp_relative_path, '\\') !== FALSE OR strpos($inp_relative_path, 'backup/') !== FALSE) {
									$inp_relative_path = "_DATABASE_" . $configWebsiteTitleCleanSav . "_" . $datetime_clean . "_" . $get_current_backup_secret . "\\$db_table_backup_file";
								}
								$inp_relative_path_mysql = quote_smart($link, $inp_relative_path);

								mysqli_query($link, "INSERT INTO $t_backup_files 
								(file_id, file_backup_id, file_module_id, file_directory_id, file_file_path, 
								file_relative_path, file_size) 
								VALUES 
								(NULL, $get_current_backup_id, 0, 0, $inp_file_path_mysql, 
								$inp_relative_path_mysql, 0)") or die(mysqli_error($link));
								

								$insert_statement_body = "";
								$content_file_no++;
								$count_rows = 0;
							}

						} // fetch data

						// Write rest
						if($count_rows > 0){
							$insert_statement = $insert_statement_header . $insert_statement_body;
							$db_table_backup_file = "$get_current_module_name_clean" . "_db_" . $tables_backup_array[$table_no] . "_file_" . $content_file_no. ".sql.txt";
							$fh = fopen("_data/backup/$get_current_backup_secret/$db_table_backup_file", "a+") or die("can not open file");
							fwrite($fh, "$insert_statement");
							fclose($fh);



							// Add file to files so we will take backup of it
							$inp_file_path = "_data/backup/$get_current_backup_secret/$db_table_backup_file";
							$inp_file_path = realpath($inp_file_path);
							$inp_file_path_mysql = quote_smart($link, $inp_file_path);

							$datetime_clean = str_replace(" ", "_", $get_current_backup_created_datetime);
							$datetime_clean = str_replace(":", "-", $datetime_clean);
							$datetime_clean = substr($datetime_clean, 0, -3);

							$inp_relative_path = "_DATABASE_" . $configWebsiteTitleCleanSav . "_" . $datetime_clean . "_" . $get_current_backup_secret . "/$db_table_backup_file";
							if (strpos($inp_relative_path, '\\') !== FALSE OR strpos($inp_relative_path, 'backup/') !== FALSE) {
								$inp_relative_path = "_DATABASE_" . $configWebsiteTitleCleanSav . "_" . $datetime_clean . "_" . $get_current_backup_secret . "\\$db_table_backup_file";
							}
							$inp_relative_path_mysql = quote_smart($link, $inp_relative_path);

							mysqli_query($link, "INSERT INTO $t_backup_files 
							(file_id, file_backup_id, file_module_id, file_directory_id, file_file_path, 
							file_relative_path, file_size) 
							VALUES 
							(NULL, $get_current_backup_id, 0, 0, $inp_file_path_mysql, 
							$inp_relative_path_mysql, 0)") or die(mysqli_error($link));
							

							echo"
							<span><b>Write rest to $db_table_backup_file</b></span>
							<pre>$insert_statement</pre>
							";
						}
						else{
							echo"<p><em>Table $tables_backup_array[$table_no] is empty. Skipping</em></p>\n";
						}
					}
					else{
						echo"<p><em>Table $tables_backup_array[$table_no] doesnt exists. Skipping</em></p>\n";
		
					}

					// Next table
					$next_table_no = $table_no+1;
					$rand = rand(0,1);
					echo"
					<meta http-equiv=\"refresh\" content=\"$rand; url=index.php?open=$open&amp;page=$page&amp;action=content&amp;table_no=$next_table_no&amp;module_id=$get_current_module_id&amp;editor_language=$editor_language&amp;l=$l\" />
					";

	
				}
				else{
					// Table doesnt exists
					// Update done with content
					mysqli_query($link, "UPDATE $t_backup_modules SET module_tables_finished=1 WHERE module_id=$get_current_module_id") or die(mysqli_error($link));

					// Next page
					$rand = rand(0,1);
					echo"
					<p>
					Done with content, checking for next task
					</p>

					<meta http-equiv=\"refresh\" content=\"$rand; url=index.php?open=$open&amp;page=new_backup_step_2_list_modules&amp;backup_id=$get_current_backup_id&amp;editor_language=$editor_language&amp;l=$l\" />
					";
				}



			} // action == "content"

		} // tables and dir file exists


		echo"
			</div> <!-- //backup_column_right -->
		</div> <!-- //backup_row -->
		";
	} // backup found
} // module found
?>