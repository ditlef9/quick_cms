<?php
/**
*
* File: _admin/_inc/domains_monitoring/default.php
* Version 02:10 28.12.2011
* Copyright (c) 2008-2012 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Settings ------------------------------------------------------------------------ */
if(!(file_exists("_data/domain_monitoring.php"))){
	$update_file="<?php
// General
\$daysToKeepDomainsSav = \"30\";
\$lastCheckedDeleteRoutineSav = \"2000-01-01\";
?>";

	$fh = fopen("_data/domain_monitoring.php", "w+") or die("can not open file");
	fwrite($fh, $update_file);
	fclose($fh);
}
include("_data/domain_monitoring.php");

/*- Functions ------------------------------------------------------------------------ */
include("_includes/delete_old_domains.php");


/*- Variables ------------------------------------------------------------------------ */
if (isset($_GET['mode'])) {
	$mode = $_GET['mode'];
	$mode = stripslashes(strip_tags($mode));
}
else{
	$mode = "";
}


if($mode == "save"){

	$inp_days_to_keep_domains = $_POST['inp_days_to_keep_domains'];
	$inp_days_to_keep_domains = output_html($inp_days_to_keep_domains);


	$update_file="<?php
// General
\$daysToKeepDomainsSav = \"$inp_days_to_keep_domains\";
\$lastCheckedDeleteRoutineSav = \"2000-01-01\";
?>";

	$fh = fopen("_data/domain_monitoring.php", "w+") or die("can not open file");
	fwrite($fh, $update_file);
	fclose($fh);


	echo"
	<h1>Domain monitoring settings</h1>
	<h2><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Saving...</h2>
	<meta http-equiv=refresh content=\"3; url=index.php?open=$open&page=$page&ft=success&fm=changes_saved\">
	";
	// header("Location: ?open=$open&page=$page&ft=success&fm=changes_saved");
	// exit;
}
if($mode == ""){

	$tabindex = 0;
	echo"
	<h1>Domain monitoring settings</h1>
	<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;mode=save\" enctype=\"multipart/form-data\">
				
	
	<!-- Feedback -->
	";
	if($ft != ""){
		if($fm == "changes_saved"){
			$fm = "$l_changes_saved";
		}
		else{
			$fm = ucfirst($ft);
		}
		echo"<div class=\"$ft\"><span>$fm</span></div>";
	}
	echo"	
	<!-- //Feedback -->

	<!-- Focus -->
		<script>
		window.onload = function() {
			document.getElementById(\"inp_days_to_keep_domains\").focus();
		}
		</script>
	<!-- //Focus -->

	
	<p>Days to keep domains:<br />
	<input type=\"text\" name=\"inp_days_to_keep_domains\" id=\"inp_days_to_keep_domains\" value=\"$daysToKeepDomainsSav\" size=\"5\" tabindex=\""; $tabindex=0; $tabindex=$tabindex+1;echo"$tabindex\" /></p>


	<p><input type=\"submit\" value=\"Save changes\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>

	</form>

	";
} // mode == ""
?>