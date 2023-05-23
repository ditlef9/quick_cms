<?php
/**
*
* File: _admin/_inc/comments/courses_category_scan.php
* Version 2
* Copyright (c) 2008-2023 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}
/*- Variables ------------------------------------------------------------------------ */
$tabindex = 0;
if(isset($_GET['category_id'])){
	$category_id = $_GET['category_id'];
	$category_id = strip_tags(stripslashes($category_id));
}
else{
	$category_id = "";
}
$category_id_mysql = quote_smart($link, $category_id);


if($action == ""){
	echo"
	<h1>Scan for categories</h1>
			

	<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
	<!-- //Feedback -->


	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=courses&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Courses</a>
		&gt;
		<a href=\"index.php?open=courses&amp;page=default&amp;editor_language=$editor_language&amp;l=$l\">Categories</a>
		&gt;
		<a href=\"index.php?open=courses&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Scan for categories</a>
		</p>
	<!-- //Where am I? -->

	<!-- About -->

		<div style=\"border: #ccc;\">
			<p>This looks for the file <a href=\"../courses/_categories.txt\">../courses/_categories.txt</a>. 
			The format of the file should be:<br />
			category_title | category_language | category_dir | category_description
		</div>
	<!-- //About -->

	<!-- Loop and list -->
		
        	
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span>Title</span>
		   </th>
		   <th scope=\"col\">
			<span>Language</span>
		   </th>
		   <th scope=\"col\">
			<span>Actions</span>
		   </th>
		  </tr>
		 </thead>
		 <tbody>
		";
		if(file_exists("../courses/_categories.txt")){
			$fh = fopen("../courses/_categories.txt", "r");
			$data = fread($fh, filesize("../courses/_categories.txt"));
			fclose($fh);
			
			$data_array = explode("\n", $data);
			for($x=0;$x<sizeof($data_array);$x++){
				$temp = explode("|", $data_array[$x]);

				$category_title = trim($temp[0]);
				$category_title_mysql = quote_smart($link, $category_title);

				$category_language = trim($temp[1]);
				$category_language_mysql = quote_smart($link, $category_language);

				$category_dir = trim($temp[2]);
				$category_description = trim($temp[3]);

				if(isset($odd) && $odd == false){
					$odd = true;
				}
				else{
					$odd = false;
				}

				// Check if exists
				$query = "SELECT category_id FROM $t_courses_categories WHERE category_title=$category_title_mysql AND category_language=$category_language_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_current_category_id) = $row;


				echo"
				<tr>
				  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
					<span>$category_title</span>
				  </td>
				  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
					<span>$category_language</span>
				  </td>
				  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
					<span>
					";
					if($get_current_category_id == ""){
						echo"<a href=\"index.php?open=$open&amp;page=$page&amp;action=add&amp;line=$x&amp;editor_language=$editor_language&amp;process=1\">Add</a>";
					}
					else{
						echo"$get_current_category_id";
					}
					echo"</span>
				 </td>
				</tr>
				";
			}
		} // file exists
		echo"
		 </tbody>
		</table>
	<!-- //Loop and list -->
	";
} // action
if($action == "add"){
	if(isset($_GET['line'])){
		$line = $_GET['line'];
		$line = strip_tags(stripslashes($line));
	}
	else{
		echo"error";
		die;
	}
	if(file_exists("../courses/_categories.txt")){
		$fh = fopen("../courses/_categories.txt", "r");
		$data = fread($fh, filesize("../courses/_categories.txt"));
		fclose($fh);
			
		$data_array = explode("\n", $data);
		for($x=0;$x<sizeof($data_array);$x++){
			$temp = explode("|", $data_array[$x]);


			if($x == "$line"){

				$inp_title = trim($temp[0]);
				$inp_title_mysql = quote_smart($link, $inp_title);

				$inp_language = trim($temp[1]);
				$inp_language_mysql = quote_smart($link, $inp_language);

				$inp_dir_name = trim($temp[2]);
				$inp_dir_name_mysql = quote_smart($link, $inp_dir_name);

				$inp_description = trim($temp[3]);
				$inp_description_mysql = quote_smart($link, $inp_description);



				$datetime = date("Y-m-d H:i:s");
		
		
				mysqli_query($link, "INSERT INTO $t_courses_categories
				(category_id, category_title, category_dir_name, category_description, category_language, category_created, category_updated) 
				VALUES 
				(NULL, $inp_title_mysql, $inp_dir_name_mysql, $inp_description_mysql, $inp_language_mysql, '$datetime', '$datetime')")
				or die(mysqli_error($link));


				// Get ID
				$query = "SELECT category_id FROM $t_courses_categories WHERE category_title=$inp_title_mysql AND category_created='$datetime'";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_current_category_id) = $row;



				// Header
				$url = "index.php?open=$open&page=courses_category_scan&editor_language=$editor_language&ft=success&fm=category_created";
				header("Location: $url");
				exit;

			}
		}

	}
	
}
?>