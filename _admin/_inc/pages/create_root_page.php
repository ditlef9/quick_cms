<?php
/**
*
* File: _admin/_inc/pages/create_root_page.php
* Version 1.0 
* Date 18:50 29.10.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Variables ------------------------------------------------------------------------ */


$page_language_mysql = quote_smart($link, $editor_language);
$query = "SELECT page_id FROM $t_pages WHERE page_language=$page_language_mysql AND page_path=''";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_page_id) = $row;

if($get_page_id == ""){
	$inp_page_title = "Welcome";
	$inp_page_title = output_html($inp_page_title);
	$inp_page_title_mysql = quote_smart($link, $inp_page_title);

	$inp_page_language = $editor_language;
	$inp_page_language = output_html($inp_page_language);
	$inp_page_language_mysql = quote_smart($link, $inp_page_language);

	$inp_page_slug = clean($inp_page_title);
	$inp_page_slug_mysql = quote_smart($link, $inp_page_slug);

	$inp_page_created = date("Y-m-d H:i:s");
	$inp_page_created_mysql = quote_smart($link, $inp_page_created);

	$inp_page_created_by_user_id = $_SESSION['admin_user_id'];
	$inp_page_created_by_user_id = output_html($inp_page_created_by_user_id);
	$inp_page_created_by_user_id_mysql = quote_smart($link, $inp_page_created_by_user_id);

	$inp_page_comments_active = "0";
	$inp_page_comments_active_mysql = quote_smart($link, $inp_page_comments_active);
	
	// Insert
	mysqli_query($link, "INSERT INTO $t_pages
	(page_id, page_title, page_language, page_path, page_file_name, page_slug, page_parent_id, page_content, page_no_of_children, page_child_level, page_created, page_created_by_user_id, page_updated, page_updated_by_user_id, page_allow_comments, page_no_of_comments, page_uniqe_hits) 
	VALUES 
	(NULL, $inp_page_title_mysql, $inp_page_language_mysql, '', 'index.php', $inp_page_slug_mysql, '0', '<h1>Welcome</h1><p>Content coming!</p>', '0', '0', $inp_page_created_mysql, $inp_page_created_by_user_id_mysql, $inp_page_created_mysql, $inp_page_created_by_user_id_mysql, '0', '0', '0')")
	or die(mysqli_error($link));


	// Get page ID
	$query = "SELECT page_id FROM $t_pages WHERE page_created=$inp_page_created_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_page_id) = $row;


	// Make flat file
	$text_value="<?php 
/**
*
* File: index.php
* Version 
* Date $inp_page_created
* $configWebsiteCopyrightSav
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Configuration ---------------------------------------------------------------------------- */
\$pageIdSav            = \"$get_page_id\";
\$pageNoColumnSav      = \"1\";
\$pageAllowCommentsSav = \"0\";

/*- Root dir --------------------------------------------------------------------------------- */
// This determine where we are
if(file_exists(\"favicon.ico\")){ \$root = \".\"; }
elseif(file_exists(\"../favicon.ico\")){ \$root = \"..\"; }
elseif(file_exists(\"../../favicon.ico\")){ \$root = \"../..\"; }
elseif(file_exists(\"../../../favicon.ico\")){ \$root = \"../../..\"; }
elseif(file_exists(\"../../../../favicon.ico\")){ \$root = \"../../../..\"; }
else{ \$root = \"../../..\"; }

/*- Website config --------------------------------------------------------------------------- */
include(\"\$root/_admin/website_config.php\");

/*- Headers ---------------------------------------------------------------------------------- */
\$website_title = \"$inp_page_title\";
if(file_exists(\"./favicon.ico\")){ \$root = \".\"; }
elseif(file_exists(\"../favicon.ico\")){ \$root = \"..\"; }
elseif(file_exists(\"../../favicon.ico\")){ \$root = \"../..\"; }
elseif(file_exists(\"../../../favicon.ico\")){ \$root = \"../../..\"; }
include(\"\$root/_webdesign/header.php\");

/*- Content ---------------------------------------------------------------------------------- */
echo\"
<h1>Welcome</h1><p>Content coming!</p>
\";

/*- Footer ----------------------------------------------------------------------------------- */
include(\"\$root/_webdesign/footer.php\");
?>";
	if(!(file_exists("../index.php"))){
		$fh = fopen("../index.php", "w") or die("can not open file");
		fwrite($fh, $text_value);
		fclose($fh);
	}

	// Go back
	echo"<div class=\"info\"><span>L O A D I N G</span></div>";
	echo"
 	<meta http-equiv=\"refresh\" content=\"1;URL='index.php?open=pages&amp;editor_language=$editor_language'\" />
	";
} // page_id
?>