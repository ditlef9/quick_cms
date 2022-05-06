<?php
/**
*
* File: _admin/_inc/references/backup.php
* Version 14:45 28.09.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}
/*- Tables ---------------------------------------------------------------------------- */
$t_references_liquidbase 	 = $mysqlPrefixSav . "references_liquidbase";

$t_references_title_translations 	= $mysqlPrefixSav . "references_title_translations";
$t_references_categories_main	 	= $mysqlPrefixSav . "references_categories_main";
$t_references_categories_sub 	 	= $mysqlPrefixSav . "references_categories_sub";

$t_references_index		 	= $mysqlPrefixSav . "references_index";
$t_references_index_groups	 	= $mysqlPrefixSav . "references_index_groups";
$t_references_index_groups_images 	= $mysqlPrefixSav . "references_index_groups_images";
$t_references_index_guides	 	= $mysqlPrefixSav . "references_index_guides";
$t_references_index_guides_comments	= $mysqlPrefixSav . "references_index_guides_comments";
$t_references_index_guides_images	= $mysqlPrefixSav . "references_index_guides_images";


/*- Variables ---------------------------------------------------------------------------- */
if(isset($_GET['mode'])) {
	$mode = $_GET['mode'];
	$mode  = strip_tags(stripslashes($mode));
}
else{
	$mode = "";
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
if(isset($_GET['backup_id'])) {
	$backup_id = $_GET['backup_id'];
	$backup_id = strip_tags(stripslashes($backup_id));
	if(!(is_numeric($backup_id))){
		echo"Backup id not numeric";
		die;
	}
}
else{
	$backup_id = "";
}



$tables_array = array("$t_references_liquidbase", 
"$t_references_title_translations", 
"$t_references_categories_main", 
"$t_references_categories_sub", 
"$t_references_index", 

"$t_references_index_groups", 
"$t_references_index_groups_images", 
"$t_references_index_guides", 
"$t_references_index_guides_comments", 
"$t_references_index_guides_images");

/*- Functions -------------------------------------------------------------------------- */
function delete_directory($dirname) {
	if (is_dir($dirname))
		$dir_handle = opendir($dirname);
	if (!$dir_handle)
		return false;
	while($file = readdir($dir_handle)) {
		if ($file != "." && $file != "..") {
			if (!is_dir($dirname."/".$file))
			unlink($dirname."/".$file);
		else
                	delete_directory($dirname.'/'.$file);
         	}
     	}
     	closedir($dir_handle);
     	rmdir($dirname);
    	return true;
}





if($action == ""){
	echo"
	<h1>Backup</h1>


	<!-- Where am I? -->
	<p><b>You are here:</b><br />
	<a href=\"index.php?open=references&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">References</a>
	&gt;
	<a href=\"index.php?open=references&amp;page=backup&amp;editor_language=$editor_language&amp;l=$l\">DB backup</a>
	</p>
	<!-- //Where am I? -->


	<p>
	<a href=\"index.php?open=references&amp;page=backup&amp;action=db_backup&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">Export DB backup</a>

	</p>
	";
}
elseif($action == "db_backup"){


	if($mode == ""){
		$backup_id = date("ymdhis");
		$backup_file = "references_" . $backup_id . ".sql";

		// Reset ips
		mysqli_query($link, "UPDATE $t_references_index SET reference_read_times_ip_block=''") or die(mysqli_error($link));
		mysqli_query($link, "UPDATE $t_references_index_groups SET group_read_times_ip_block=''") or die(mysqli_error($link));
		mysqli_query($link, "UPDATE $t_references_index_guides SET guide_read_ipblock=''") or die(mysqli_error($link));

		// Delete old files
		delete_directory("../_cache/");
		if(!(is_dir("../_cache"))){
			mkdir("../_cache");
		}


		echo"
		<h1>DB Backup</h1>

		<p>
		<b>Backup ID:</b> $backup_id<br />
		<b>Backup File:</b> <a href=\"../_cache/$backup_file\">../_cache/$backup_file</a><br />
		</p>

		<meta http-equiv=\"refresh\" content=\"2; url=index.php?open=references&amp;page=backup&amp;action=db_backup&amp;mode=header&amp;table_no=0&amp;backup_id=$backup_id&amp;editor_language=$editor_language&amp;l=$l\" />
		";


		$fh = fopen("../_cache/index.html", "a+") or die("can not open file");
		fwrite($fh, "");
		fclose($fh);
		
	} // mode ==""
	elseif($mode == "header"){
		$backup_file = "references_" . $backup_id . ".sql";
		if(isset($tables_array[$table_no])){
			echo"
			<h1>DB Backup</h1>

			<p>
			<b>Backup ID:</b> $backup_id<br />
			<b>Backup File:</b> <a href=\"../_cache/$backup_file\">../_cache/$backup_file</a><br />
			<b>Table:</b> $tables_array[$table_no]<br />
			</p>
			";


			// Head
			// Ready create table
			$create_table = "DROP TABLE IF EXISTS $tables_array[$table_no];

CREATE TABLE $tables_array[$table_no](
";
			// Fields
			$x = 0;
			$query = "SHOW COLUMNS FROM $tables_array[$table_no]";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_column_name) = $row;

				// Get information about that column
				$query_column = "SELECT COLUMN_NAME, COLUMN_DEFAULT, IS_NULLABLE, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH, NUMERIC_PRECISION, COLUMN_TYPE, COLUMN_KEY, EXTRA FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '$tables_array[$table_no]' AND COLUMN_NAME='$get_column_name'";
				$result_column = mysqli_query($link, $query_column);
				$row_column = mysqli_fetch_row($result_column);
				list($get_column_name, $get_column_default, $get_is_nullable, $get_data_type, $get_character_maximum_lenght, $get_mumeric_precision, $get_column_type, $get_column_key, $get_extra) = $row_column;

				$get_data_type = strtoupper($get_data_type);
				$get_extra = strtoupper($get_extra);


				if($x > 1){
					$create_table = $create_table . ",
";
				}

				$create_table = $create_table . " $get_column_name $get_data_type";
				
				if($get_data_type == "VARCHAR"){
					$create_table = $create_table . " ($get_character_maximum_lenght)";
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

				$x++;

			}
			// Create table footer
			$create_table = $create_table . ");



";

			// Echo
			echo"<pre>$create_table</pre>";
			if($x > 0){
				$fh = fopen("../_cache/$backup_file", "a+") or die("can not open file");
				fwrite($fh, "$create_table");
				fclose($fh);
			}


			// Next table
			$next_table_no = $table_no+1;
			echo"
			<meta http-equiv=\"refresh\" content=\"0; url=index.php?open=references&amp;page=backup&amp;action=db_backup&amp;mode=header&amp;table_no=$next_table_no&amp;backup_id=$backup_id&amp;editor_language=$editor_language&amp;l=$l\" />
			";


		}
		else{
			echo"Done with header, starting with content!";
			echo"
			<meta http-equiv=\"refresh\" content=\"0; url=index.php?open=references&amp;page=backup&amp;action=db_backup&amp;mode=content&amp;table_no=0&amp;backup_id=$backup_id&amp;editor_language=$editor_language&amp;l=$l\" />
			";
		}

	} // mode == header
	elseif($mode == "content"){
		$datetime = date("Y-m-d H:i:s");
		$backup_file = "references_" . $backup_id . ".sql";
		if(isset($tables_array[$table_no])){
			echo"
			<h1>DB Backup</h1>

			<p>
			<b>Backup ID:</b> $backup_id<br />
			<b>Backup File:</b> <a href=\"../_cache/$backup_file\">../_cache/$backup_file</a><br />
			<b>Table:</b> $tables_array[$table_no]<br />
			</p>
			";
			
			// Insert header :: Ready create table
			$insert_statement = "

INSERT INTO $tables_array[$table_no](
";
			// Fields
			$count_fields = 0;
			$table_column_types = array();
			$query = "SHOW COLUMNS FROM $tables_array[$table_no]";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_column_name) = $row;

				// Get information about that column
				$query_column = "SELECT COLUMN_NAME, COLUMN_DEFAULT, IS_NULLABLE, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH, NUMERIC_PRECISION, COLUMN_TYPE, COLUMN_KEY, EXTRA FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '$tables_array[$table_no]' AND COLUMN_NAME='$get_column_name'";
				$result_column = mysqli_query($link, $query_column);
				$row_column = mysqli_fetch_row($result_column);
				list($get_column_name, $get_column_default, $get_is_nullable, $get_data_type, $get_character_maximum_lenght, $get_mumeric_precision, $get_column_type, $get_column_key, $get_extra) = $row_column;

				$get_data_type = strtoupper($get_data_type);
				$get_extra = strtoupper($get_extra);


				if($count_fields > 0){
					$insert_statement = $insert_statement . ", ";
				}

				$insert_statement = $insert_statement . "$get_column_name";


				// Add to columns types
				$table_column_types[$count_fields] = "$get_data_type";

				$count_fields++;

			}
			// Create table footer
			$insert_statement = $insert_statement . ")
VALUES ";
			
			
			// Fetch data
			$count_rows = 0;
			$query = "SELECT * FROM $tables_array[$table_no]";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {

				if($count_rows > 0){
					$insert_statement = $insert_statement . ",";

				}
				$insert_statement = $insert_statement . "
(";

				$count = count($row, COUNT_RECURSIVE);
				for($x=0;$x<$count;$x++){

					// Data
					if($table_column_types[$x] == "INT" && $row[$x] == ""){
						$input_data = "0";
					}
					elseif($table_column_types[$x] == "DATE" && $row[$x] == ""){
						$input_data = "'" . "$current_date" . "'";
					}
					elseif($table_column_types[$x] == "DATETIME" && $row[$x] == ""){
						$input_data = "'" . "$datetime" . "'";
					}
					elseif($table_column_types[$x] == "TIME" && $row[$x] == ""){
						$input_data = "NULL";
					}
					elseif($table_column_types[$x] == "DOUBLE" && $row[$x] == ""){
						$input_data = "NULL";
					}
					else{
						$input_data =  quote_smart($link, $row[$x]);
					}


					if($x > 0){
						$insert_statement = $insert_statement . ", ";
					}
					$insert_statement = $insert_statement . $input_data;
				}
				$count_rows++;

				$insert_statement = $insert_statement . ")";
			} // fetch data
			$insert_statement = $insert_statement . ";";

			// Write
			echo"
			<pre>$insert_statement </pre>
			";
			if($count_rows > 0){
				$fh = fopen("../_cache/$backup_file", "a+") or die("can not open file");
				fwrite($fh, "$insert_statement");
				fclose($fh);
			}

			// Next table
			$next_table_no = $table_no+1;
			echo"
			<meta http-equiv=\"refresh\" content=\"1; url=index.php?open=references&amp;page=backup&amp;action=db_backup&amp;mode=content&amp;table_no=$next_table_no&amp;backup_id=$backup_id&amp;editor_language=$editor_language&amp;l=$l\" />
			";


		}
		else{
			echo"Done!";
			echo"
			<meta http-equiv=\"refresh\" content=\"1; url=index.php?open=references&amp;page=backup&amp;action=db_backup&amp;mode=download&amp;backup_id=$backup_id&amp;ft=success&fm=backup_created&amp;editor_language=$editor_language&amp;l=$l\" />
			";
		}

	} // content
	elseif($mode == "download"){
		$backup_file = "references_" . $backup_id . ".sql";
		echo"
		<h1>DB Backup</h1>

		<p>
		You can now download the database backup.
		</p>
		
		<p>
		<a href=\"../_cache/$backup_file\" class=\"btn_default\">../_cache/$backup_file</a>
		<a href=\"index.php?open=references&amp;page=db_backup\" class=\"btn_default\">Home</a>
		</p>
		";
	}
} // export


?>