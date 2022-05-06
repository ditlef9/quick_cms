<?php
/**
*
* File: _admin/_inc/recipes/backup.php
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
$t_recipes_liquidbase	 			= $mysqlPrefixSav . "recipes_liquidbase";

$t_recipes 	 			= $mysqlPrefixSav . "recipes";
$t_recipes_images			= $mysqlPrefixSav . "recipes_images";
$t_recipes_ingredients			= $mysqlPrefixSav . "recipes_ingredients";
$t_recipes_groups			= $mysqlPrefixSav . "recipes_groups";
$t_recipes_items			= $mysqlPrefixSav . "recipes_items";
$t_recipes_numbers			= $mysqlPrefixSav . "recipes_numbers";
$t_recipes_rating			= $mysqlPrefixSav . "recipes_rating";
$t_recipes_cuisines			= $mysqlPrefixSav . "recipes_cuisines";
$t_recipes_cuisines_translations	= $mysqlPrefixSav . "recipes_cuisines_translations";
$t_recipes_seasons			= $mysqlPrefixSav . "recipes_seasons";
$t_recipes_seasons_translations		= $mysqlPrefixSav . "recipes_seasons_translations";
$t_recipes_occasions			= $mysqlPrefixSav . "recipes_occasions";
$t_recipes_occasions_translations	= $mysqlPrefixSav . "recipes_occasions_translations";
$t_recipes_categories			= $mysqlPrefixSav . "recipes_categories";
$t_recipes_categories_translations	= $mysqlPrefixSav . "recipes_categories_translations";
$t_recipes_measurements			= $mysqlPrefixSav . "recipes_measurements";
$t_recipes_measurements_translations	= $mysqlPrefixSav . "recipes_measurements_translations";
$t_recipes_weekly_special		= $mysqlPrefixSav . "recipes_weekly_special";
$t_recipes_of_the_day			= $mysqlPrefixSav . "recipes_of_the_day";
$t_recipes_comments			= $mysqlPrefixSav . "recipes_comments";
$t_recipes_favorites			= $mysqlPrefixSav . "recipes_favorites";
$t_recipes_tags				= $mysqlPrefixSav . "recipes_tags";
$t_recipes_links			= $mysqlPrefixSav . "recipes_links";
$t_recipes_comments			= $mysqlPrefixSav . "recipes_comments";
$t_recipes_searches			= $mysqlPrefixSav . "recipes_searches";
$t_recipes_age_restrictions 	 	= $mysqlPrefixSav . "recipes_age_restrictions";
$t_recipes_age_restrictions_accepted	= $mysqlPrefixSav . "recipes_age_restrictions_accepted";
$t_recipes_tags_unique			= $mysqlPrefixSav . "recipes_tags_unique";

$t_recipes_pairing_loaded 		= $mysqlPrefixSav . "recipes_pairing_loaded";
$t_recipes_pairing_recipes		= $mysqlPrefixSav . "recipes_pairing_recipes";


$t_recipes_similar_loaded = $mysqlPrefixSav . "recipes_similar_loaded";
$t_recipes_similar_recipes = $mysqlPrefixSav . "recipes_similar_recipes";


$t_recipes_stats_views_per_month 	= $mysqlPrefixSav . "recipes_stats_views_per_month";
$t_recipes_stats_views_per_month_ips 	= $mysqlPrefixSav . "recipes_stats_views_per_month_ips";

$t_recipes_stats_views_per_year 	= $mysqlPrefixSav . "recipes_stats_views_per_year";
$t_recipes_stats_views_per_year_ips	= $mysqlPrefixSav . "recipes_stats_views_per_year_ips";

$t_recipes_stats_comments_per_month 	= $mysqlPrefixSav . "recipes_stats_comments_per_month";
$t_recipes_stats_comments_per_year 	= $mysqlPrefixSav . "recipes_stats_comments_per_year";

$t_recipes_stats_favorited_per_month 	= $mysqlPrefixSav . "recipes_stats_favorited_per_month";
$t_recipes_stats_favorited_per_year 	= $mysqlPrefixSav . "recipes_stats_favorited_per_year";

$t_recipes_stats_chef_of_the_month 	= $mysqlPrefixSav . "recipes_stats_chef_of_the_month";
$t_recipes_stats_chef_of_the_year 	= $mysqlPrefixSav . "recipes_stats_chef_of_the_year";

$t_recipes_user_adapted_view 	= $mysqlPrefixSav . "recipes_user_adapted_view";


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



$tables_array = array("$t_recipes_liquidbase", 
"$t_recipes",
"$t_recipes_images",
"$t_recipes_ingredients",
"$t_recipes_groups",
"$t_recipes_items",
"$t_recipes_numbers",
"$t_recipes_rating",
"$t_recipes_cuisines",
"$t_recipes_cuisines_translations",
"$t_recipes_seasons",
"$t_recipes_seasons_translations",
"$t_recipes_occasions",
"$t_recipes_occasions_translations",
"$t_recipes_categories",
"$t_recipes_categories_translations",
"$t_recipes_measurements",
"$t_recipes_measurements_translations",
"$t_recipes_weekly_special",
"$t_recipes_of_the_day",
"$t_recipes_comments",
"$t_recipes_favorites",
"$t_recipes_tags",
"$t_recipes_links",
"$t_recipes_comments",
"$t_recipes_searches",
"$t_recipes_age_restrictions",
"$t_recipes_age_restrictions_accepted",
"$t_recipes_tags_unique",

"$t_recipes_pairing_loaded",
"$t_recipes_pairing_recipes",

"$t_recipes_similar_loaded",
"$t_recipes_similar_recipes",

"$t_recipes_stats_views_per_month",
"$t_recipes_stats_views_per_month_ips",

"$t_recipes_stats_views_per_year",
"$t_recipes_stats_views_per_year_ips",

"$t_recipes_stats_comments_per_month",
"$t_recipes_stats_comments_per_year",

"$t_recipes_stats_favorited_per_month",
"$t_recipes_stats_favorited_per_year",

"$t_recipes_stats_chef_of_the_month",
"$t_recipes_stats_chef_of_the_year",

"$t_recipes_user_adapted_view");

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
	$open_saying = ucfirst($open);
	echo"
	<h1>Backup</h1>

	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=$open&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">$open_saying</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=backup&amp;editor_language=$editor_language&amp;l=$l\">Backup</a>
		</p>
	<!-- //Where am I? -->


	<p>
	<a href=\"index.php?open=$open&amp;page=backup&amp;action=export&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">Export backup</a>
	</p>
	";
}
elseif($action == "export"){

	if($mode == ""){
		$backup_id = date("ymdhis");
		$backup_file = $open . "_db_" . $backup_id . ".txt";

		// Reset ips
		mysqli_query($link, "TRUNCATE TABLE $t_recipes_searches") or die(mysqli_error($link));
		mysqli_query($link, "TRUNCATE TABLE $t_recipes_age_restrictions_accepted") or die(mysqli_error($link));
		mysqli_query($link, "TRUNCATE TABLE $t_recipes_pairing_loaded") or die(mysqli_error($link));
		mysqli_query($link, "TRUNCATE TABLE $t_recipes_pairing_recipes") or die(mysqli_error($link));
		mysqli_query($link, "TRUNCATE TABLE $t_recipes_similar_loaded") or die(mysqli_error($link));
		mysqli_query($link, "TRUNCATE TABLE $t_recipes_similar_recipes") or die(mysqli_error($link));
		mysqli_query($link, "TRUNCATE TABLE $t_recipes_stats_views_per_month_ips") or die(mysqli_error($link));
		mysqli_query($link, "TRUNCATE TABLE $t_recipes_stats_views_per_year_ips") or die(mysqli_error($link));
		mysqli_query($link, "TRUNCATE TABLE $t_recipes_stats_comments_per_year") or die(mysqli_error($link));
		mysqli_query($link, "TRUNCATE TABLE $t_recipes_stats_favorited_per_year") or die(mysqli_error($link));
		mysqli_query($link, "TRUNCATE TABLE $t_recipes_stats_chef_of_the_year") or die(mysqli_error($link));
		mysqli_query($link, "TRUNCATE TABLE $t_recipes_user_adapted_view") or die(mysqli_error($link));

		// Delete old files
		delete_directory("../_cache/");
		if(!(is_dir("../_cache"))){
			mkdir("../_cache");
		}


		echo"
		<h1>Backup</h1>

		<p>
		<b>Backup ID:</b> $backup_id<br />
		<b>Backup File:</b> <a href=\"../_cache/$backup_file\">../_cache/$backup_file</a><br />
		</p>

		<meta http-equiv=\"refresh\" content=\"2; url=index.php?open=$open&amp;page=backup&amp;action=export&amp;mode=header&amp;table_no=0&amp;backup_id=$backup_id&amp;editor_language=$editor_language&amp;l=$l\" />
		";


		$fh = fopen("../_cache/index.html", "a+") or die("can not open file");
		fwrite($fh, "");
		fclose($fh);
		
	} // mode ==""
	elseif($mode == "header"){
		$backup_file = "recipes_db_" . $backup_id . ".txt";
		if(isset($tables_array[$table_no])){
			echo"
			<h1>DB Backup</h1>

			<p>
			<b>Backup ID:</b> $backup_id<br />
			<b>Backup File:</b> <a href=\"../_cache/$backup_file\">../_cache/$backup_file</a><br />
			<b>Table:</b> $tables_array[$table_no]<br />
			</p>
			";

			// Check if table exists
			$query = "SELECT * FROM $tables_array[$table_no] LIMIT 1";
			$result = mysqli_query($link, $query);
			if($result !== FALSE){


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

			} // table exists
			else{
				echo"<p>Table $tables_array[$table_no] doesn't exists. Skipping it...</p>\n";
			}

			// Next table
			$next_table_no = $table_no+1;
			echo"
			<meta http-equiv=\"refresh\" content=\"1; url=index.php?open=$open&amp;page=backup&amp;action=export&amp;mode=header&amp;table_no=$next_table_no&amp;backup_id=$backup_id&amp;editor_language=$editor_language&amp;l=$l\" />
			";


		}
		else{
			echo"Done with header, starting with content!";
			echo"
			<meta http-equiv=\"refresh\" content=\"0; url=index.php?open=$open&amp;page=backup&amp;action=export&amp;mode=content&amp;table_no=0&amp;backup_id=$backup_id&amp;editor_language=$editor_language&amp;l=$l\" />
			";
		}

	} // mode == header
	elseif($mode == "content"){
		$datetime = date("Y-m-d H:i:s");
		$backup_file = "recipes_db_" . $backup_id . ".txt";
		if(isset($tables_array[$table_no])){
			echo"
			<h1>Backup</h1>

			<p>
			<b>Backup ID:</b> $backup_id<br />
			<b>Backup File:</b> <a href=\"../_cache/$backup_file\">../_cache/$backup_file</a><br />
			<b>Table:</b> $tables_array[$table_no]<br />
			</p>
			";
			
			// Check if table exists
			$query = "SELECT * FROM $tables_array[$table_no] LIMIT 1";
			$result = mysqli_query($link, $query);
			if($result !== FALSE){


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
							$input_data = "'" . "$row[$x]" . "'";
						}
						elseif($table_column_types[$x] == "DATETIME" && $row[$x] == ""){
							$input_data = "'" . "$row[$x]" . "'";
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
				else{
					echo"<p><em>Table $tables_array[$table_no] is empty. Skipping</em></p>\n";
				}
			}
			else{
				echo"<p><em>Table $tables_array[$table_no] doesnt exists. Skipping</em></p>\n";
		
			}
			// Next table
			$next_table_no = $table_no+1;
			echo"
			<meta http-equiv=\"refresh\" content=\"1; url=index.php?open=$open&amp;page=backup&amp;action=export&amp;mode=content&amp;table_no=$next_table_no&amp;backup_id=$backup_id&amp;editor_language=$editor_language&amp;l=$l\" />
			";


		}
		else{
			echo"Done!";
			echo"
			<meta http-equiv=\"refresh\" content=\"1; url=index.php?open=$open&amp;page=backup&amp;action=export&amp;mode=download&amp;backup_id=$backup_id&amp;ft=success&fm=backup_created&amp;editor_language=$editor_language&amp;l=$l\" />
			";
		}

	} // content
	elseif($mode == "download"){
		$backup_file = "recipes_db_" . $backup_id . ".txt";
		echo"
		<h1>DB Backup</h1>

		<p>
		You can now download the database backup.
		</p>
		
		<p>
		<a href=\"../_cache/$backup_file\" class=\"btn_default\">../_cache/$backup_file</a>
		<a href=\"index.php?open=$open&amp;page=backup\" class=\"btn_default\">Home</a>
		</p>
		";
	}
} // export


?>