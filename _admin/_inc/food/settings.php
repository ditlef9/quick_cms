<?php
/**
*
* File: _admin/_inc/food/default.php
* Version 11:28 05.04.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Config ----------------------------------------------------------------------- */
if(!(file_exists("_data/food.php"))){
	$update_file="<?php
\$foodPrintLogoOnImagesSav = \"0\";
?>";

	$fh = fopen("_data/food.php", "w+") or die("can not open file");
	fwrite($fh, $update_file);
	fclose($fh);
}


/*- Variables ------------------------------------------------------------------------ */
$tabindex = 0;


/*- Process -------------------------------------------------------------------------- */
if($process == "1"){

	$inp_print_logo_on_images = $_POST['inp_print_logo_on_images'];
	$inp_print_logo_on_images = output_html($inp_print_logo_on_images);

	$update_file="<?php
\$foodPrintLogoOnImagesSav = \"$inp_print_logo_on_images\";
?>";

	$fh = fopen("_data/food.php", "w+") or die("can not open file");
	fwrite($fh, $update_file);
	fclose($fh);

	$datetime = date("Y-m-d H:i:s");
	header("Location: ?open=$open&page=$page&ft=success&fm=changes_saved&datetime=$datetime");
	exit;

}


/*- Include config ------------------------------------------------------------------------ */
include("_data/food.php");

echo"
<h1>Food</h1>

<!-- Feedback -->
";
if($ft != ""){
	if($fm == "changes_saved"){
		$fm = "$l_changes_saved";
	}
	else{
		$fm = ucfirst($fm);
		$fm = str_replace("_", " ", $fm);
	}
	echo"<div class=\"$ft\"><span>$fm</span></div>";
}
echo"	
<!-- //Feedback -->



<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;process=1\" enctype=\"multipart/form-data\">


	<p>Print logo on images:<br />
	(<a href=\"index.php?open=webdesign&amp;page=logo&amp;editor_language=$editor_language&amp;l=$l\" class=\"small\">Upload logo</a>)<br />
	<input type=\"radio\" name=\"inp_print_logo_on_images\" value=\"1\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\""; if($foodPrintLogoOnImagesSav == "1"){ echo" checked=\"checked\""; } echo" /> Yes
	&nbsp;
	<input type=\"radio\" name=\"inp_print_logo_on_images\" value=\"0\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\""; if($foodPrintLogoOnImagesSav == "0"){ echo" checked=\"checked\""; } echo" /> No
	</p>


	<p><input type=\"submit\" value=\"$l_save_changes\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
</form>

";
?>