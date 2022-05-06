<?php
if(isset($_SESSION['admin_user_id'])){
	$t_backup_directories	= $mysqlPrefixSav . "backup_directories";

	mysqli_query($link,"DROP TABLE IF EXISTS $t_backup_directories") or die(mysqli_error());


	mysqli_query($link, "CREATE TABLE $t_backup_directories(
			   directory_id INT NOT NULL AUTO_INCREMENT,
			   PRIMARY KEY(directory_id), 
			   directory_backup_id INT,
			   directory_module_id INT,
			   directory_file_path VARCHAR(300),
			   directory_relative_path VARCHAR(300),
			   directory_size VARCHAR(300))")
			   or die(mysqli_error($link));


}
?>