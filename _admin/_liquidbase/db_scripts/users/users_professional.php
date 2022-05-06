<?php
if(isset($_SESSION['admin_user_id'])){
	$t_users_professional 	= $mysqlPrefixSav . "users_professional";




	$t_users_professional_allowed_companies			= $mysqlPrefixSav . "users_professional_allowed_companies";
	$t_users_professional_allowed_company_locations		= $mysqlPrefixSav . "users_professional_allowed_company_locations";
	$t_users_professional_allowed_departments		= $mysqlPrefixSav . "users_professional_allowed_departments";
	$t_users_professional_allowed_positions			= $mysqlPrefixSav . "users_professional_allowed_positions";
	$t_users_professional_allowed_districts			= $mysqlPrefixSav . "users_professional_allowed_districts";


	mysqli_query($link,"DROP TABLE IF EXISTS $t_users_professional") or die(mysqli_error());
	mysqli_query($link,"DROP TABLE IF EXISTS $t_users_professional_allowed_companies") or die(mysqli_error());
	mysqli_query($link,"DROP TABLE IF EXISTS $t_users_professional_allowed_company_locations") or die(mysqli_error());
	mysqli_query($link,"DROP TABLE IF EXISTS $t_users_professional_allowed_departments") or die(mysqli_error());
	mysqli_query($link,"DROP TABLE IF EXISTS $t_users_professional_allowed_positions") or die(mysqli_error());
	mysqli_query($link,"DROP TABLE IF EXISTS $t_users_professional_allowed_districts") or die(mysqli_error());


	mysqli_query($link, "CREATE TABLE $t_users_professional(
			   professional_id INT NOT NULL AUTO_INCREMENT,
			   PRIMARY KEY(professional_id), 
			   professional_user_id INT,
			   professional_company VARCHAR(200),
			   professional_company_location VARCHAR(200),
			   professional_department VARCHAR(200),
			   professional_work_email VARCHAR(200),
			   professional_position VARCHAR(200),
			   professional_position_abbr VARCHAR(200),
			   professional_district VARCHAR(200))")
			   or die(mysqli_error($link));


	mysqli_query($link, "CREATE TABLE $t_users_professional_allowed_companies(
			  allowed_company_id INT NOT NULL AUTO_INCREMENT,
			   PRIMARY KEY(allowed_company_id), 
			   allowed_company_title VARCHAR(200),
			   allowed_company_title_clean VARCHAR(200))")
			   or die(mysqli_error($link));

	mysqli_query($link, "CREATE TABLE $t_users_professional_allowed_company_locations(
			  allowed_company_location_id INT NOT NULL AUTO_INCREMENT,
			   PRIMARY KEY(allowed_company_location_id), 
			   allowed_company_location_title VARCHAR(200),
			   allowed_company_location_title_clean VARCHAR(200))")
			   or die(mysqli_error($link));


	mysqli_query($link, "CREATE TABLE $t_users_professional_allowed_departments(
			  allowed_department_id INT NOT NULL AUTO_INCREMENT,
			   PRIMARY KEY(allowed_department_id), 
			   allowed_department_title VARCHAR(200),
			   allowed_department_title_clean VARCHAR(200))")
			   or die(mysqli_error($link));

	mysqli_query($link, "CREATE TABLE $t_users_professional_allowed_positions(
			  allowed_position_id INT NOT NULL AUTO_INCREMENT,
			   PRIMARY KEY(allowed_position_id), 
			   allowed_position_title VARCHAR(200),
			   allowed_position_title_clean VARCHAR(200),
			   allowed_position_title_abbr VARCHAR(200),
			   allowed_position_title_abbr_clean VARCHAR(200))")
			   or die(mysqli_error($link));

	mysqli_query($link, "CREATE TABLE $t_users_professional_allowed_districts(
			  allowed_district_id INT NOT NULL AUTO_INCREMENT,
			   PRIMARY KEY(allowed_district_id), 
			   allowed_district_title VARCHAR(200),
			   allowed_district_title_clean VARCHAR(200),
			   allowed_district_title_abbr VARCHAR(200),
			   allowed_district_title_abbr_clean VARCHAR(200))")
			   or die(mysqli_error($link));

	// Settings
	

	$create_file="<?php
\$configUsersCanOnlyUseAllowedCompaniesSav		= \"0\";
\$configUsersCanOnlyUseAllowedCompanyLocationsSav	= \"0\";
\$configUsersCanOnlyUseAllowedDepartmentsSav		= \"0\";
\$configUsersCanOnlyUseAllowedPositionsSav		= \"0\";
\$configUsersCanOnlyUseAllowedDistrictsSav		= \"0\";
?>";

	$fh = fopen("../_data/user_professional_allowed_settings.php", "w+") or die("can not open file");
	fwrite($fh, $create_file);
	fclose($fh);

	echo"
	<p>Write to <a href=\"../_data/user_professional_allowed_settings.php\">../_data/config/user_professional_allowed_settings.php</a>
	</p>
	";
}
?>