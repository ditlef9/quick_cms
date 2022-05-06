<?php
if(isset($_SESSION['admin_user_id'])){
	$t_backup_files		= $mysqlPrefixSav . "backup_files";

	mysqli_query($link,"DROP TABLE IF EXISTS $t_backup_files") or die(mysqli_error());


	mysqli_query($link, "CREATE TABLE $t_backup_files(
			   file_id INT NOT NULL AUTO_INCREMENT,
			   PRIMARY KEY(file_id), 
			   file_backup_id INT,
			   file_module_id INT,
			   file_directory_id INT,
			   file_file_path VARCHAR(300),
			   file_relative_path VARCHAR(300),
			   file_size VARCHAR(300))")
			   or die(mysqli_error($link));


}
?>