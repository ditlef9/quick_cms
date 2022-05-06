<?php
/**
*
* File: _admin/_inc/social_media/tables.php
* Version 1.0.0
* Date 11:50 03.03.2018
* Copyright (c) 2008-2018 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}
/*- Refererer ------------------------------------------------------------------------- */

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
	<meta http-equiv=\"refresh\" content=\"2;url=index.php?open=$open&amp;page=$refererer&amp;editor_language=$editor_language&amp;l=$l&amp;ft=success&amp;fm=module_installed\">
	";
}


/*- Tables ---------------------------------------------------------------------------- */
$t_social_media 	= $mysqlPrefixSav . "social_media";
$t_social_media_sites	= $mysqlPrefixSav . "social_media_sites";

echo"
<h1>Tables</h1>


	<!-- social_media_sites -->
	";

	
	$query = "SELECT * FROM $t_social_media_sites";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_social_media_sites: $row_cnt</p>
		";
	}
	else{
		echo"<p>Create table $t_social_media_sites</p>
		<pre>CREATE TABLE $t_social_media_sites(
	  	 site_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(site_id), 
	  	   site_title VARCHAR(250),
	  	   site_logo VARCHAR(250))</pre>";

		mysqli_query($link, "CREATE TABLE $t_social_media_sites(
	  	 site_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(site_id), 
	  	   site_title VARCHAR(250),
	  	   site_logo VARCHAR(250))")
		   or die(mysqli_error());

		
		mysqli_query($link, "INSERT INTO $t_social_media_sites
		(site_id, site_title, site_logo) 
		VALUES 
		(NULL, 'Facebook', 'facebook.png'),
		(NULL, 'Snapchat', 'snapchat.png'),
		(NULL, 'Youtube', 'youtube.png'),
		(NULL, 'Instagram', 'instagram.png')
		")
		or die(mysqli_error($link));



	}



	echo"
	<!-- //social_media -->


	<!-- social_media -->
	";

	
	$query = "SELECT * FROM $t_social_media";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_social_media: $row_cnt</p>
		";
	}
	else{


		mysqli_query($link, "CREATE TABLE $t_social_media(
	  	 social_media_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(social_media_id), 
	  	   social_media_site_id INT,
	  	   social_media_site_title VARCHAR(50),
	  	   social_media_site_logo VARCHAR(50),
	  	   social_media_language VARCHAR(50),
	  	   social_media_link_title VARCHAR(250),
	  	   social_media_link_url VARCHAR(250),
	  	   social_media_placement VARCHAR(250),
	  	   social_media_code TEXT,
	  	   social_media_updated DATETIME,
	  	   social_media_active INT,
	  	   social_media_hits INT,
	  	   social_media_hits_unique INT,
	  	   social_media_hits_ipblock TEXT)")
		   or die(mysqli_error());

		$nettport_social_media = array(
		  array('social_media_id' => '1','social_media_site_id' => '1','social_media_site_title' => 'Facebook','social_media_site_logo' => 'facebook.png','social_media_language' => 'en','social_media_link_title' => 'Facebook','social_media_link_url' => 'https://www.facebook.com/nettport/','social_media_code' => '<div class="fb-like-box" data-href="https://www.facebook.com/pages/Nettportcom/177091425760639?fref=ts" data-width="250" data-colorscheme="light" data-show-faces="true" data-header="false" data-stream="false" data-show-border="false"></div>','social_media_active' => '0','social_media_hits' => '0'),
		  array('social_media_id' => '2','social_media_site_id' => '2','social_media_site_title' => 'Youtube','social_media_site_logo' => 'youtube.png','social_media_language' => 'en','social_media_link_title' => '','social_media_link_url' => '','social_media_code' => '','social_media_active' => '1','social_media_hits' => '0')
		);


		foreach($nettport_social_media as $v){
			
			$social_media_site_id    = $v["social_media_site_id"];
			$social_media_site_title = $v["social_media_site_title"];
			$social_media_site_logo  = $v["social_media_site_logo"];
			$social_media_language   = $v["social_media_language"];
			$social_media_link_title = $v["social_media_link_title"];
			$social_media_link_url   = $v["social_media_link_url"];
			$social_media_code       = $v["social_media_code"];
			$social_media_active     = $v["social_media_active"];
		
			mysqli_query($link, "INSERT INTO $t_social_media
			(social_media_id, social_media_site_id, social_media_site_title, social_media_site_logo, social_media_language, social_media_link_title, social_media_link_url, social_media_active, social_media_hits) 
			VALUES 
			(NULL, '$social_media_site_id', '$social_media_site_title', '$social_media_site_logo', '$social_media_language', '$social_media_link_title', '$social_media_link_url', '$social_media_active', '0')")
			or die(mysqli_error($link));

			$sql = "UPDATE $t_social_media SET social_media_code=? WHERE social_media_site_id='$social_media_site_id' AND social_media_language='$social_media_language'";
			$stmt = $link->prepare($sql);
			$stmt->bind_param("s", $inp_code);
			$stmt->execute();
			if ($stmt->errno) {
				echo "FAILURE!!! " . $stmt->error; die;
			}

		}
	}



	echo"
	<!-- //social_media -->




";
?>