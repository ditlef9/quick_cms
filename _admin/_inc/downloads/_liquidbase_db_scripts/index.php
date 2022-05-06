<?php
/**
*
* File: _admin/_inc/downloads/_liquibase/downloads_index.php
* Version 1.0.0
* Date 12:57 24.03.2021
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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_downloads_index") or die(mysqli_error($link)); 


echo"



	<!-- downloads_index -->
	";
	$query = "SELECT * FROM $t_downloads_index";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_downloads_index: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_downloads_index(
	  	 download_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(download_id), 
	  	   download_title VARCHAR(200),
	  	   download_title_short VARCHAR(200),
	  	   download_title_length INT,
	  	   download_language VARCHAR(200),
	  	   download_introduction VARCHAR(200),
	  	   download_description TEXT,
	  	   download_video VARCHAR(200),
	  	   download_image_path VARCHAR(200),
	  	   download_image_store VARCHAR(200),
	  	   download_image_store_thumb VARCHAR(200),
	  	   download_image_thumb_a VARCHAR(200),
	  	   download_image_thumb_b VARCHAR(200),
	  	   download_image_thumb_c VARCHAR(200),
	  	   download_image_thumb_d VARCHAR(200),
	  	   download_image_file_a VARCHAR(200),
	  	   download_image_file_b VARCHAR(200),
	  	   download_image_file_c VARCHAR(200),
	  	   download_image_file_d VARCHAR(200),
	  	   download_read_more_url VARCHAR(200),
	  	   download_main_category_id INT,
	  	   download_sub_category_id INT,
	  	   download_internal_external VARCHAR(250),
	  	   download_file_external_url VARCHAR(250),
	  	   download_dir VARCHAR(50),
	  	   download_file VARCHAR(250),
	  	   download_type VARCHAR(4),
	  	   download_version VARCHAR(20),
	  	   download_file_size VARCHAR(50),
	  	   download_file_date DATE,
	  	   download_file_date_print VARCHAR(50),
	  	   download_last_download DATE,
	  	   download_hits INT,
	  	   download_unique_hits INT,
	  	   download_ip_block TEXT,
	  	   download_tag_a VARCHAR(100),
	  	   download_tag_b VARCHAR(100),
	  	   download_tag_c VARCHAR(100),
	  	   download_created_datetime DATETIME,
	  	   download_updated_datetime DATETIME,
	  	   download_updated_print VARCHAR(50),
	  	   download_have_to_be_logged_in_to_download INT)");
	}
	echo"
	<!-- //downloads_index -->

";
?>