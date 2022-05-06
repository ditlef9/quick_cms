<?php
/**
*
* File: _admin/_inc/rebus/settings.php
* Version 
* Date 07:54 01.07.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}




/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['mode'])){
	$mode = $_GET['mode'];
	$mode = output_html($mode);
}
else{
	$mode = "";
}


/*- Config ------------------------------------------------------------------------------- */
if(!(file_exists("_data/rebus.php"))){
	$datetime = date("ymdhis");
	$update_file="<?php
\$rebusApiActiveSav 	= \"1\";
\$rebusApiPasswordSav 	= \"$datetime\";
?>";

	$fh = fopen("_data/rebus.php", "w+") or die("can not open file");
	fwrite($fh, $update_file);
	fclose($fh);
}
include("_data/rebus.php");

/*- Check if setup is run ------------------------------------------------------------- */
$t_rebus_liquidbase = $mysqlPrefixSav . "rebus_liquidbase";
$query = "SELECT * FROM $t_rebus_liquidbase LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
}
else{
	echo"
	<div class=\"info\"><p><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Running setup</p></div>
	<meta http-equiv=\"refresh\" content=\"1;url=index.php?open=rebus&amp;page=tables&amp;refererer=default&amp;editor_language=$editor_language&amp;l=$l\" />
	";
}


/*- Variables ------------------------------------------------------------------------ */
$tabindex = 0;


if($action == ""){

	if($mode == "save"){
		$inp_api_active = $_POST['inp_api_active'];
		$inp_api_active = output_html($inp_api_active);

		$inp_api_password = $_POST['inp_api_password'];
		$inp_api_password = output_html($inp_api_password);

	$update_file="<?php
\$pictureItApiActiveSav 	= \"$inp_api_active\";
\$pictureItApiPasswordSav 	= \"$inp_api_password\";
?>";

		$fh = fopen("_data/rebus.php", "w+") or die("can not open file");
		fwrite($fh, $update_file);
		fclose($fh);


		echo"<h1><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Saving..</h1>
		<meta http-equiv=refresh content=\"3; url=index.php?open=$open&page=$page&ft=success&fm=changes_saved\">";
	}
	elseif($mode == ""){
		echo"
		<h1>Rebus Settings</h1>
				

		<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
		<!-- //Feedback -->


		<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=rebus&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Rebus</a>
		&gt;
		<a href=\"index.php?open=rebus&amp;page=default&amp;editor_language=$editor_language&amp;l=$l\">Default</a>
		</p>
		<!-- //Where am I? -->


		<!-- Focus -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_api_active\"]').focus();
			});
			</script>
		<!-- //Focus -->

		<!-- Settings -->
		<form method=\"post\" action=\"?open=$open&page=$page&amp;mode=save\" enctype=\"multipart/form-data\">
		

		<p>API Active:<br />
		<input type=\"radio\" name=\"inp_api_active\" value=\"1\" "; if($rebusApiActiveSav == "1"){ echo" checked=\"checked\""; } echo" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		Yes
		&nbsp;
		<input type=\"radio\" name=\"inp_api_active\" value=\"0\" "; if($rebusApiActiveSav == "0"){ echo" checked=\"checked\""; } echo" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		No
		</p>


		<p>API Password:<br />
		<input type=\"text\" name=\"inp_api_password\" value=\"$rebusApiPasswordSav\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		<p><input type=\"submit\" value=\"Save changes\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
		</form>		
		<!-- //Settings -->
		";
	} 
}
?>