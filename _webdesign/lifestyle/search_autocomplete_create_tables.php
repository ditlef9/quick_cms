<?php
error_reporting(E_ALL & ~E_STRICT);


/*- MySQL ---------------------------------------------------------------------------- */
$mysqlHostSav   	= "localhost";
$mysqlUserNameSav   	= "root";
$mysqlPasswordSav	= "";
$mysqlDatabaseNameSav 	= "search";
$mysqlPrefixSav 	= "s_";

$link = mysqli_connect($mysqlHostSav, $mysqlUserNameSav, $mysqlPasswordSav, $mysqlDatabaseNameSav);
if (mysqli_connect_errno()){
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

/*- MySQL Tables -------------------------------------------------------------------- */
$t_links = $mysqlPrefixSav . "links";



/*- Links --------------------------------------------------------------------------- */
$query = "SELECT * FROM $t_links";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_links: $row_cnt</p>
	";
}
else{
	// Create books
	echo"<p>Table $t_links created.</p>";
	mysqli_query($link, "CREATE TABLE $t_links(
  	 link_id INT NOT NULL AUTO_INCREMENT,
 	  PRIMARY KEY(link_id), 
  	   link_name VARCHAR(250),
  	   link_url VARCHAR(250),
  	   link_description VARCHAR(250))")
	   or die(mysqli_error());

	// Insert some links
	mysqli_query($link, "INSERT INTO $t_links
	(link_id, link_name, link_url, link_description) 
	VALUES 
	(NULL, 'Stack Overflow', 'https://stackoverflow.com', 'Programming QA'),
	(NULL, 'Google', 'https://google.com', 'Search for things'),
	(NULL, 'Yahoo', 'https://yahoo.com', 'Search in Yahoo!'),
	(NULL, 'CiCo Life', 'https://cicolife.com', 'Recipes'),
	(NULL, 'VG', 'https://vg.no', 'News'),
	(NULL, 'ITAvisen', 'https://itavisen.no', 'IT News')")
	or die(mysqli_error($link));
	
	
}
?>