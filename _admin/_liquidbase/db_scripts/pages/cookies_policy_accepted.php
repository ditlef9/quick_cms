<?php
/**
*
* File: _admin/_liquidbase/db_scripts/webdesign/cookies_policy_accepted.php
* Version 1.0.0
* Date 21:19 28.08.2019
* Copyright (c) 2019 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// Access check
if(isset($_SESSION['admin_user_id'])){

	/*- Tables ---------------------------------------------------------------------------- */
	$t_pages_cookies_policy_accepted = $mysqlPrefixSav . "pages_cookies_policy_accepted";


	$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_pages_cookies_policy_accepted") or die(mysqli_error($link)); 



	echo"

	<!-- pages_cookies_policy_accepted -->
	";

	$query = "SELECT * FROM $t_pages_cookies_policy_accepted LIMIT 1";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_pages_cookies_policy: $row_cnt</p>
		";
		}
		else{

		mysqli_query($link, "CREATE TABLE $t_pages_cookies_policy_accepted(
		  cookies_policy_accepted_id INT NOT NULL AUTO_INCREMENT,
		  PRIMARY KEY(cookies_policy_accepted_id), 
		   cookies_policy_accepted_year YEAR,
		   cookies_policy_accepted_datetime DATETIME,
		   cookies_policy_accepted_ip VARCHAR(200)
		   )")
		   or die(mysqli_error());

	}
	echo"
	<!-- /pages_cookies_policy_accepted -->
	";
} // access
?>