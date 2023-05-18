<?php
/**
*
* File: contact_form/index.php
* Version 1.0.0.
* Date 22:07 23.01.2019
* Copyright (c) 2008-2019 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Configuration --------------------------------------------------------------------- */
$pageIdSav            = "2";
$pageNoColumnSav      = "2";
$pageAllowCommentsSav = "0";

/*- Root dir -------------------------------------------------------------------------- */
// This determine where we are
if(file_exists("favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
elseif(file_exists("../../../../favicon.ico")){ $root = "../../../.."; }
else{ $root = "../../.."; }

/*- Website config -------------------------------------------------------------------- */
include("$root/_admin/website_config.php");


/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);

/*- Tables ---------------------------------------------------------------------------- */
include("_tables_contact_forms.php");



/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_contact_forms";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");




echo"
<!-- Headline and language -->
	<h1>$l_contact_forms</h1>
<!-- //Headline and language -->



<!-- Contact forms -->
	<div class=\"vertical\">
		<ul>
	";
	
	$query = "SELECT form_id, form_title, form_language, form_mail_to, form_text_before_form, form_text_left_of_form, form_text_right_of_form, form_text_after_form, form_created_datetime, form_created_by_user_id, form_updated_datetime, form_updated_by_user_id, form_api_avaible, form_ipblock, form_used_times FROM $t_contact_forms_index WHERE form_language=$l_mysql";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_form_id, $get_form_title, $get_form_language, $get_form_mail_to, $get_form_text_before_form, $get_form_text_left_of_form, $get_form_text_right_of_form, $get_form_text_after_form, $get_form_created_datetime, $get_form_created_by_user_id, $get_form_updated_datetime, $get_form_updated_by_user_id, $get_form_api_avaible, $get_form_ipblock, $get_form_used_times) = $row;

		echo"
		<li><a href=\"view_form.php?form_id=$get_form_id&amp;l=$get_form_language\">$get_form_title</a></li>
		";
	}
	echo"
		</ul>
	</div>
<!-- //Contact forms -->
";


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>