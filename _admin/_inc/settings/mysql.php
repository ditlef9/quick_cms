<?php
/**
*
* File: _admin/_inc/settings/mysql.php
* Version 15.43 03.03.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Include MySQL config --------------------------------------------------------------- */
$server_name = $_SERVER['HTTP_HOST'];
$server_name_saying = ucfirst($server_name);
$server_name = clean($server_name);

$mysql_config_file = "_data/mysql_" . $server_name . ".php";
if(file_exists($mysql_config_file)){
	include("$mysql_config_file");
}


if($process == "1"){
	// General
	$inp_mysql_host = $_POST['inp_mysql_host'];
	$inp_mysql_host = output_html($inp_mysql_host);

	$inp_mysql_user_name = $_POST['inp_mysql_user_name'];
	$inp_mysql_user_name = output_html($inp_mysql_user_name);

	$inp_mysql_password = $_POST['inp_mysql_password'];
	$inp_mysql_password = output_html($inp_mysql_password);

	$inp_mysql_database_name = $_POST['inp_mysql_database_name'];
	$inp_mysql_database_name = output_html($inp_mysql_database_name);

	$inp_mysql_prefix = $_POST['inp_mysql_prefix'];
	$inp_mysql_prefix = output_html($inp_mysql_prefix);


	$update_file="<?php
\$mysqlHostSav   	= \"$inp_mysql_host\";
\$mysqlUserNameSav   	= \"$inp_mysql_user_name\";
\$mysqlPasswordSav	= \"$inp_mysql_password\";
\$mysqlDatabaseNameSav 	= \"$inp_mysql_database_name\";
\$mysqlPrefixSav 	= \"$inp_mysql_prefix\";

?>";

	$fh = fopen($mysql_config_file, "w+") or die("can not open file");
	fwrite($fh, $update_file);
	fclose($fh);

	header("Location: ?open=settings&page=mysql&ft=success&fm=changes_saved");
	exit;
}

$tabindex = 0;
echo"
<h1>$l_mysql_for $server_name_saying</h1>
<form method=\"post\" action=\"?open=settings&amp;page=mysql&amp;process=1\" enctype=\"multipart/form-data\">
				
	
<!-- Feedback -->
";
if($ft != ""){
	if($fm == "changes_saved"){
		$fm = "$l_changes_saved";
	}
	else{
		$fm = ucfrist($ft);
	}
	echo"<div class=\"$ft\"><span>$fm</span></div>";
}
echo"	
<!-- //Feedback -->

<!-- Test MySQL connection -->
	";
	if(isset($mysqlHostSav)){
		$link = @mysqli_connect($mysqlHostSav, $mysqlUserNameSav, $mysqlPasswordSav, $mysqlDatabaseNameSav);

		if (!$link) {
			echo "
			<div class=\"alert alert-danger\">
				 <span class=\"glyphicon glyphicon-exclamation-sign\" aria-hidden=\"true\"></span>
				<strong>$l_mysql_connection_failed</strong>

			";
			echo PHP_EOL;
    			echo "<br />Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    			echo "<br />Debugging error: " . mysqli_connect_error() . PHP_EOL;
    			echo"
			</div>
			";
		}
	}
	echo"
<!-- //Test MySQL connection -->


<!-- Focus -->
	<script>
	\$(document).ready(function(){
		\$('[name=\"inp_mysql_host\"]').focus();
	});
	</script>
<!-- //Focus -->


<table>
 <tr>
  <td style=\"text-align:right;padding-right: 4px;\">
	<p>$l_host:</p>
  </td>
  <td>
	<p><input type=\"text\" name=\"inp_mysql_host\" value=\""; if(isset($mysqlHostSav)){ echo"$mysqlHostSav"; } echo"\" size=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
  </td>
 </tr>
 <tr>
  <td style=\"text-align:right;padding-right: 4px;\">
	<p>$l_username:</p>
  </td>
  <td>
	<p><input type=\"text\" name=\"inp_mysql_user_name\" value=\""; if(isset($mysqlUserNameSav)){ echo"$mysqlUserNameSav"; } echo"\" size=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
  </td>
 </tr>
 <tr>
  <td style=\"text-align:right;padding-right: 4px;\">
	<p>$l_password:</p>
  </td>
  <td>
	<p><input type=\"text\" name=\"inp_mysql_password\" value=\""; if(isset($mysqlPasswordSav)){ echo"$mysqlPasswordSav"; } echo"\" size=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
  </td>
 </tr>
 <tr>
  <td style=\"text-align:right;padding-right: 4px;\">
	<p>$l_database_name:</p>
  </td>
  <td>
	<p><input type=\"text\" name=\"inp_mysql_database_name\" value=\""; if(isset($mysqlDatabaseNameSav)){ echo"$mysqlDatabaseNameSav"; } echo"\" size=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
  </td>
 </tr>
 <tr>
  <td style=\"text-align:right;padding-right: 4px;\">
	<p>$l_prefix:</p>
  </td>
  <td>
	<p><input type=\"text\" name=\"inp_mysql_prefix\" value=\""; if(isset($mysqlPrefixSav)){ echo"$mysqlPrefixSav"; } else{ echo"nettport_"; } echo"\" size=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
  </td>
 </tr>
 <tr>
  <td style=\"text-align:right;padding-right: 4px;\">
	
  </td>
  <td>
	<p><input type=\"submit\" value=\"$l_save_changes\" class=\"btn btn-success btn-sm\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>

  </td>
 </tr>
</table>



</form>

";
?>