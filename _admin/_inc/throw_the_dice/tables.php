<?php
/**
*
* File: _admin/_inc/throw_the_dice/tables.php
* Version 10:21 09.12.2021
* Copyright (c) 2008-2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}
/*- Tables ---------------------------------------------------------------------------- */
$t_dice_liquidbase 	 = $mysqlPrefixSav . "dice_liquidbase";

$t_dice_game_index	= $mysqlPrefixSav . "dice_game_index";
$t_dice_game_members	= $mysqlPrefixSav . "dice_game_members";


if($action == ""){
	echo"
	<h1>Tables</h1>


	<!-- Where am I? -->
	<p><b>You are here:</b><br />
	<a href=\"index.php?open=throw_the_dice&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Throw the Dice</a>
	&gt;
	<a href=\"index.php?open=throw_the_dice&amp;page=tables&amp;editor_language=$editor_language&amp;l=$l\">Tables</a>
	</p>
	<!-- //Where am I? -->

	<!-- dice_liquidbase -->
	";
	$query = "SELECT * FROM $t_dice_liquidbase LIMIT 1";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_dice_liquidbase: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_dice_liquidbase(
		  liquidbase_id INT NOT NULL AUTO_INCREMENT,
		  PRIMARY KEY(liquidbase_id), 
		   liquidbase_dir VARCHAR(200), 
		   liquidbase_file VARCHAR(200), 
		   liquidbase_run_datetime DATETIME, 
		   liquidbase_run_saying VARCHAR(200))")
	  	 or die(mysqli_error());

		// If refererer then refresh to that page
		if(isset($_GET['refererer'])) {
			$refererer = $_GET['refererer'];
			$refererer = strip_tags(stripslashes($refererer));

			echo"
			<table>
			 <tr> 
			  <td style=\"padding-right: 6px;\">
				<p>
				<img src=\"_design/gfx/loading_22.gif\" alt=\"Loading\" />
				</p>
			  </td>
			  <td>
				<h1>Loading...</h1>
			  </td>
			 </tr>
			</table>
		
			<meta http-equiv=\"refresh\" content=\"2;url=index.php?open=$open&amp;page=default&amp;editor_language=$editor_language&amp;l=$l&amp;ft=success&amp;fm=module_installed\">
			";
		}
	}
	echo"
	<!-- dice_liquidbase-->


	<!-- Feedback -->
		";
		if($ft != "" && $fm != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><p>$fm</p></div>";
		}
		echo"
	<!-- //Feedback -->

	<!-- Run -->
		";
		$path = "_inc/throw_the_dice/_liquidbase_db_scripts";
		if(!(is_dir("$path"))){
			echo"$path doesnt exists";
			die;
		}
		if ($handle = opendir($path)) {
			$modules = array();   
			while (false !== ($module = readdir($handle))) {
				if ($module === '.') continue;
				if ($module === '..') continue;
				array_push($modules, $module);
			}
	


			sort($modules);
			foreach ($modules as $module){
				$liquidbase_names = array();   

				// Open that year folder
				$path_module = "_inc/throw_the_dice/_liquidbase_db_scripts/$module";
				if ($handle_year = opendir($path_module)) {
					while (false !== ($liquidbase_name = readdir($handle_year))) {
						if ($liquidbase_name === '.') continue;
						if ($liquidbase_name === '..') continue;
						array_push($liquidbase_names, $liquidbase_name);
					}
					sort($liquidbase_names);
					foreach ($liquidbase_names as $liquidbase_name){
				
						if(!(is_dir("_inc/throw_the_dice/_liquidbase_db_scripts/$module/$liquidbase_name"))){

							// Has it been executed?
							$inp_liquidbase_module_mysql = quote_smart($link, $module);
							$inp_liquidbase_name_mysql = quote_smart($link, $liquidbase_name);
					
							$query = "SELECT liquidbase_id FROM $t_dice_liquidbase WHERE liquidbase_dir=$inp_liquidbase_module_mysql AND liquidbase_file=$inp_liquidbase_name_mysql";
							$result = mysqli_query($link, $query);
							$row = mysqli_fetch_row($result);
							list($get_liquidbase_id) = $row;
							if($get_liquidbase_id == ""){
								// Date
								$datetime = date("Y-m-d H:i:s");
								$run_saying = date("j M Y H:i");


								// Insert
								mysqli_query($link, "INSERT INTO $t_dice_liquidbase 
								(liquidbase_id, liquidbase_dir, liquidbase_file, liquidbase_run_datetime, liquidbase_run_saying) 
								VALUES 
								(NULL, $inp_liquidbase_module_mysql, $inp_liquidbase_name_mysql, '$datetime', '$run_saying')")
								or die(mysqli_error($link));

								// Run code
								include("_inc/throw_the_dice/_liquidbase_db_scripts/$module/$liquidbase_name");
							} // not runned before
						} // is dir
					} // whule open files
				} // handle modules
			} // while open modules
		} // handle opendir path
		echo"
	<!-- //Run -->

	<!-- liquidbase scripts -->
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span>Directory</span>
		   </th>
		   <th scope=\"col\">
			<span>File</span>
		   </th>
		   <th scope=\"col\">
			<span>Run date</span>
		   </th>
		   <th scope=\"col\">
			<span>Actions</span>
		   </th>
		  </tr>
		</thead>
		<tbody>
	";

	$query = "SELECT liquidbase_id, liquidbase_dir, liquidbase_file, liquidbase_run_datetime, liquidbase_run_saying FROM $t_dice_liquidbase ORDER BY liquidbase_id DESC";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_liquidbase_id, $get_liquidbase_dir, $get_liquidbase_file, $get_liquidbase_run_datetime, $get_liquidbase_run_saying) = $row;

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
			<span>$get_liquidbase_dir</span>
		  </td>
		  <td class=\"$style\">
			<span>$get_liquidbase_file</span>
		  </td>
		  <td class=\"$style\">
			<span>$get_liquidbase_run_saying</span>
		  </td>
		  <td class=\"$style\">
			<span>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete&amp;liquidbase_id=$get_liquidbase_id&amp;editor_language=$editor_language\">$l_delete</a></span>
		  </td>
		 </tr>
		";

	}
	echo"
		 </tbody>
		</table>

	<!-- //liquidbase scripts -->
	";
}
elseif($action == "delete"){
	if(isset($_GET['liquidbase_id'])) {
		$liquidbase_id = $_GET['liquidbase_id'];
		$liquidbase_id  = strip_tags(stripslashes($liquidbase_id));
	}
	else{
		$liquidbase_id = "";
	}
	$liquidbase_id_mysql = quote_smart($link, $liquidbase_id);
	$query = "SELECT liquidbase_id, liquidbase_file, liquidbase_run_datetime FROM $t_dice_liquidbase WHERE liquidbase_id=$liquidbase_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_liquidbase_id, $get_liquidbase_file, $get_liquidbase_run_datetime) = $row;

	if($get_liquidbase_id != ""){
		if($process == "1"){

			mysqli_query($link, "DELETE FROM $t_dice_liquidbase WHERE liquidbase_id=$get_liquidbase_id") or die(mysqli_error($link));

			$url = "index.php?open=$open&page=$page&ft=success&fm=deleted";
			header("Location: $url");
			exit;
		}

		echo"
		<h1>Delete_liquidbase $get_liquidbase_file</h1>


		<p>
		Are you sure you want to delete the liquidbase script run? 
		This will cause the script to run again after deletion. 
		</p>

		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete&amp;liquidbase_id=$get_liquidbase_id&amp;editor_language=$editor_language&amp;process=1\" class=\"btn_warning\">Confirm delete</a>
		</p>
		";
	}
}


?>