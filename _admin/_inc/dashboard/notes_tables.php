<?php
/**
*
* File: _admin/_inc/dashboard/notes_tables.php
* Version 1.0.0
* Copyright (c) 2008-2018 Sindre Andre Ditlefsen
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
$t_notes_categories   = $mysqlPrefixSav . "notes_categories";
$t_notes_pages	      = $mysqlPrefixSav . "notes_pages";
$t_notes_pages_images = $mysqlPrefixSav . "notes_pages_images";
$t_notes_pages_files  = $mysqlPrefixSav . "notes_pages_files";


echo"
<!-- notes_categories -->
";
$query = "SELECT * FROM $t_notes_categories LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_notes_categories: $row_cnt</p>
	";
}
else{
	mysqli_query($link, "CREATE TABLE $t_notes_categories(
	  category_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(category_id), 
	   category_title VARCHAR(200), 
	   category_weight INT,
	   category_bg_color VARCHAR(20), 
	   category_border_color VARCHAR(20), 
	   category_title_color VARCHAR(20), 
	   category_pages_bg_color VARCHAR(20), 
	   category_pages_bg_color_hover VARCHAR(20), 
	   category_pages_bg_color_active VARCHAR(20), 
	   category_pages_border_color VARCHAR(20), 
	   category_pages_border_color_hover VARCHAR(20), 
	   category_pages_border_color_active VARCHAR(20), 
	   category_pages_title_color VARCHAR(20), 
	   category_pages_title_color_hover VARCHAR(20), 
	   category_pages_title_color_active VARCHAR(20), 
	   category_created_datetime DATETIME, 
	   category_created_by_user_id INT, 
	   category_updated_datetime DATETIME, 
	   category_updated_by_user_id INT)")
  	 or die(mysqli_error());

	/*
	mysqli_query($link, "INSERT INTO $t_notes_categories (
			moderator_user_id=$inp_user_id_mysql, moderator_user_email=$inp_mod_email_mysql, moderator_user_name=$inp_mod_user_name_mysql,
			moderator_user_alias=$inp_mod_user_alias, moderator_user_first_name=$inp_mod_user_first_name, moderator_user_last_name=$inp_mod_user_last_name, 
			moderator_user_language=$inp_mod_user_language, moderator_comment=$inp_comment_mysql 
			WHERE moderator_of_the_week_id=$get_moderator_of_the_week_id")
			or die(mysqli_error($link));
	*/

	echo"
	<div class=\"info\"><p><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Running setup</p></div>
	<meta http-equiv=\"refresh\" content=\"1;url=index.php?open=$open&amp;page=notes&amp;refererer=default&amp;editor_language=$editor_language&amp;l=$l\" />
	";


}
echo"
<!-- notes_categories -->

<!-- notes_pages -->
";
$query = "SELECT * FROM $t_notes_pages LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_notes_pages: $row_cnt</p>
	";
}
else{
	mysqli_query($link, "CREATE TABLE $t_notes_pages(
	  page_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(page_id), 
	   page_title VARCHAR(200),
	   page_category_id INT, 
	   page_weight INT,
	   page_parent_id INT,
	   page_text TEXT,
	   page_created_datetime DATETIME, 
	   page_created_by_user_id INT, 
	   page_updated_datetime DATETIME, 
	   page_updated_by_user_id INT)")
  	 or die(mysqli_error());	
}
echo"
<!-- notes_pages -->

<!-- notes_pages_images -->
";
$query = "SELECT * FROM $t_notes_pages_images LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_notes_pages_images: $row_cnt</p>
	";
}
else{
	mysqli_query($link, "CREATE TABLE $t_notes_pages_images(
	  image_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(image_id), 
	   image_category_id INT, 
	   image_page_id INT, 
	   image_title VARCHAR(200),
	   image_text VARCHAR(200),
	   image_path VARCHAR(200),
	   image_file VARCHAR(200),
	   image_uploaded_user_id INT,
	   image_uploaded_ip VARCHAR(200),
	   image_uploaded_datetime DATETIME)")
  	 or die(mysqli_error());	
}
echo"
<!-- notes_pages_images -->
";
?>