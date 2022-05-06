<?php
/**
*
* File: _admin/_inc/blog/tables.php
* Version 11:55 30.12.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Functions ------------------------------------------------------------------------ */
function fix_utf($value){
	$value = str_replace("Ã¸", "�", $value);
	$value = str_replace("Ã¥", "�", $value);

        return $value;
}
function fix_local($value){
	$value = htmlentities($value);

        return $value;
}



/*- Tables ---------------------------------------------------------------------------- */
$t_blog_liquidbase			= $mysqlPrefixSav . "blog_liquidbase";

$t_blog_titles 				= $mysqlPrefixSav . "blog_titles";
$t_blog_info 				= $mysqlPrefixSav . "blog_info";
$t_blog_default_categories		= $mysqlPrefixSav . "blog_default_categories";
$t_blog_categories			= $mysqlPrefixSav . "blog_categories";
$t_blog_posts 				= $mysqlPrefixSav . "blog_posts";
$t_blog_posts_tags 			= $mysqlPrefixSav . "blog_posts_tags";
$t_blog_posts_comments			= $mysqlPrefixSav . "blog_posts_comments";
$t_blog_posts_comments_likes_dislikes	= $mysqlPrefixSav . "blog_posts_comments_likes_dislikes";

$t_blog_posts_comments_replies			= $mysqlPrefixSav . "blog_posts_comments_replies";
$t_blog_posts_comments_replies_likes_dislikes	= $mysqlPrefixSav . "blog_posts_comments_replies_likes_dislikes";

$t_blog_images 				= $mysqlPrefixSav . "blog_images";
$t_blog_logos				= $mysqlPrefixSav . "blog_logos";

$t_blog_links_index			= $mysqlPrefixSav . "blog_links_index";
$t_blog_links_categories		= $mysqlPrefixSav . "blog_links_categories";

$t_blog_ping_list_per_blog		= $mysqlPrefixSav . "blog_ping_list_per_blog";

$t_blog_stats_most_used_categories	= $mysqlPrefixSav . "blog_stats_most_used_categories";



if($action == ""){
	echo"
	<h1>Tables</h1>



	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=blog&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Hash DB</a>
		&gt;
		<a href=\"index.php?open=blog&amp;page=tables&amp;editor_language=$editor_language&amp;l=$l\">Tables</a>
		</p>
	<!-- //Where am I? -->



	<!-- liquidbase-->
	";
	$query = "SELECT * FROM $t_blog_liquidbase LIMIT 1";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_blog_liquidbase: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_blog_liquidbase(
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

		
			<meta http-equiv=\"refresh\" content=\"2;url=index.php?open=$open&amp;page=$refererer&amp;editor_language=$editor_language&amp;l=$l&amp;ft=success&amp;fm=blog_module_installed\">
			";
		}
	}
	echo"
	<!-- liquidbase-->


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

		// Open that year folder
		$path = "_inc/blog/_liquidbase_db_scripts";
		if ($handle = opendir($path)) {
			while (false !== ($liquidbase_name = readdir($handle))) {
				if ($liquidbase_name === '.') continue;
				if ($liquidbase_name === '..') continue;
				
				if(!(is_dir("_inc/blog/_liquidbase_db_scripts/$liquidbase_name"))){

					// Has it been executed?
					$inp_liquidbase_module_mysql = quote_smart($link, "");
					$inp_liquidbase_name_mysql = quote_smart($link, $liquidbase_name);
					
					$query = "SELECT liquidbase_id FROM $t_blog_liquidbase WHERE liquidbase_dir=$inp_liquidbase_module_mysql AND liquidbase_file=$inp_liquidbase_name_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_liquidbase_id) = $row;
					if($get_liquidbase_id == ""){
						// Date
						$datetime = date("Y-m-d H:i:s");
						$run_saying = date("j M Y H:i");


						// Insert
						mysqli_query($link, "INSERT INTO $t_blog_liquidbase
						(liquidbase_id, liquidbase_dir, liquidbase_file, liquidbase_run_datetime, liquidbase_run_saying) 
						VALUES 
						(NULL, $inp_liquidbase_module_mysql, $inp_liquidbase_name_mysql, '$datetime', '$run_saying')")
						or die(mysqli_error($link));

						// Run code
						include("_inc/blog/_liquidbase_db_scripts/$liquidbase_name");
					} // not runned before
				} // is dir
			} // whule open files
		} // handle modules
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

	$query = "SELECT liquidbase_id, liquidbase_dir, liquidbase_file, liquidbase_run_datetime, liquidbase_run_saying FROM $t_blog_liquidbase ORDER BY liquidbase_id DESC";
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
	$query = "SELECT liquidbase_id, liquidbase_file, liquidbase_run_datetime FROM $t_blog_liquidbase WHERE liquidbase_id=$liquidbase_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_liquidbase_id, $get_liquidbase_file, $get_liquidbase_run_datetime) = $row;

	if($get_liquidbase_id != ""){
		if($process == "1"){

			mysqli_query($link, "DELETE FROM $t_blog_liquidbase WHERE liquidbase_id=$get_liquidbase_id") or die(mysqli_error($link));

			$url = "index.php?open=$open&page=$page&ft=success&fm=deleted";
			header("Location: $url");
			exit;
		}

		echo"
		<h1>Delete_liquidbase $get_liquidbase_file</h1>


		<p>
		Are you sure you want to dlete the liquidbase script run? 
		This will cause the script to run again after deletion. 
		</p>

		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete&amp;liquidbase_id=$get_liquidbase_id&amp;editor_language=$editor_language&amp;process=1\" class=\"btn_warning\">Confirm delete</a>
		</p>
		";
	}
}
?>