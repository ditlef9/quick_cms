<?php
if(isset($_SESSION['admin_user_id'])){


	$t_tasks_attachments	= $mysqlPrefixSav . "tasks_attachments";

	mysqli_query($link,"DROP TABLE IF EXISTS $t_tasks_attachments") or die(mysqli_error());


	$query = "SELECT * FROM $t_tasks_attachments LIMIT 1";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_tasks_attachments(
		  attachment_id INT NOT NULL AUTO_INCREMENT,
		   PRIMARY KEY(attachment_id), 
		   attachment_task_id INT,
		   attachment_title VARCHAR(250),
		   attachment_file_path VARCHAR(250),
		   attachment_file_name VARCHAR(250),
		   attachment_file_type VARCHAR(250),
		   attachment_file_thumb VARCHAR(250),
		   attachment_file_ext VARCHAR(250),
		   attachment_file_size VARCHAR(250),
		   attachment_uploaded_by_user_id INT,
		   attachment_uploaded_by_user_name VARCHAR(250),
		   attachment_uploaded_datetime DATETIME,
		   attachment_uploaded_saying VARCHAR(250)
		)")
		or die(mysqli_error($link));
	}


}
?>