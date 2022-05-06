<?php
/**
*
* File: _admin/_inc/rss_news/category_new.php
* Version 1.0
* Date: 11:41 22.02.2020
* Copyright (c) 2008-2020 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Variables -------------------------------------------------------------------------- */
if(isset($_GET['order_by'])) {
	$order_by = $_GET['order_by'];
	$order_by = strip_tags(stripslashes($order_by));
}
else{
	$order_by = "";
}
if(isset($_GET['order_method'])) {
	$order_method = $_GET['order_method'];
	$order_method = strip_tags(stripslashes($order_method));
	if($order_method != "asc" && $order_method != "desc"){
		echo"Wrong order method";
		die;
	}
}
else{
	$order_method = "asc";
}
if(isset($_GET['category_id'])) {
	$category_id = $_GET['category_id'];
	$category_id = strip_tags(stripslashes($category_id));
	if(!(is_numeric($category_id))){
		echo"category_id not numeric"; die;
	}
}
else{
	$category_id = "";
}
$category_id_mysql = quote_smart($link, $category_id);
/*- Tables ---------------------------------------------------------------------------- */
$t_hash_db_liquidbase	= $mysqlPrefixSav . "rss_news_liquidbase";

$t_hash_db_entries	= $mysqlPrefixSav . "hash_db_entries";
$t_hash_db_categories	= $mysqlPrefixSav . "hash_db_categories";


if($action == ""){
	if($process == "1"){
		$inp_title = $_POST['inp_title'];
		$inp_title = output_html($inp_title);
		$inp_title_mysql = quote_smart($link, $inp_title);

		$inp_bg_color = $_POST['inp_bg_color'];
		$inp_bg_color = output_html($inp_bg_color);
		$inp_bg_color_mysql = quote_smart($link, $inp_bg_color);

		$inp_border_color = $_POST['inp_border_color'];
		$inp_border_color = output_html($inp_border_color);
		$inp_border_color_mysql = quote_smart($link, $inp_border_color);

		$inp_text_color = $_POST['inp_text_color'];
		$inp_text_color = output_html($inp_text_color);
		$inp_text_color_mysql = quote_smart($link, $inp_text_color);

		$inp_is_illegal = $_POST['inp_is_illegal'];
		$inp_is_illegal = output_html($inp_is_illegal);
		$inp_is_illegal_mysql = quote_smart($link, $inp_is_illegal);

		$inp_is_interesting = $_POST['inp_is_interesting'];
		$inp_is_interesting = output_html($inp_is_interesting);
		$inp_is_interesting_mysql = quote_smart($link, $inp_is_interesting);

		// Insert
		mysqli_query($link, "INSERT INTO $t_hash_db_categories 
					(category_id, category_title, category_bg_color, category_border_color, category_text_color, category_is_illegal, category_is_interesting) 
					VALUES
					(NULL, $inp_title_mysql, $inp_bg_color_mysql, $inp_border_color_mysql, $inp_text_color_mysql, $inp_is_illegal_mysql, $inp_is_interesting_mysql)") or die(mysqli_error($link)); 

		$url = "index.php?open=$open&page=$page&order_by=$order_by&order_method=$order_method&l=$l&editor_language=$editor_language&ft=success&fm=category_created";
		header("Location: $url");
		exit;
	}
	echo"
	<h1>New category</h1>


	<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				$fm = ucfirst($fm);
				echo"<div class=\"$ft\"><p>$fm</p></div>";
			}
			echo"
	<!-- //Feedback -->

	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=hash_db&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Hash Db</a>
		&gt;
		<a href=\"index.php?open=hash_db&amp;page=categories&amp;order_by=$order_by&amp;order_method=$order_method&amp;editor_language=$editor_language&amp;l=$l\">Categories</a>
		&gt;
		<a href=\"index.php?open=hash_db&amp;page=$page&amp;order_by=$order_by&amp;order_method=$order_method&amp;editor_language=$editor_language&amp;l=$l\">New category</a>
		</p>
	<!-- //Where am I? -->

	<!-- New category form -->
		<form method=\"post\" action=\"index.php?open=hash_db&amp;page=$page&amp;order_by=$order_by&amp;order_method=$order_method&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

		<!-- Focus -->
		<script>
			\$(document).ready(function(){
				\$('[name=\"inp_title\"]').focus();
			});
		</script>
		<!-- //Focus -->
			
		<p>Title:<br />
		<input type=\"text\" name=\"inp_title\" value=\"\" size=\"25\" style=\"width: 99%;\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
		</p>

		<p>Bg color:<br />
		<input type=\"text\" name=\"inp_bg_color\" value=\"#e1d5e7\" size=\"25\" style=\"width: 99%;\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
		</p>

		<p>Border color:<br />
		<input type=\"text\" name=\"inp_border_color\" value=\"#9673a6\" size=\"25\" style=\"width: 99%;\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
		</p>

		<p>Text color:<br />
		<input type=\"text\" name=\"inp_text_color\" value=\"#000000\" size=\"25\" style=\"width: 99%;\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
		</p>
			
		<p>Is illegal:<br />
		<input type=\"radio\" name=\"inp_is_illegal\" value=\"1\" "; if($get_current_category_is_illegal == "1"){ echo" checked=\"checked\""; } echo" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
		Yes
		&nbsp;
		<input type=\"radio\" name=\"inp_is_illegal\" value=\"0\" "; if($get_current_category_is_illegal == "0"){ echo" checked=\"checked\""; } echo" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
		No
		</p>

		<p>Is interesting:<br />
		<input type=\"radio\" name=\"inp_is_interesting\" value=\"1\" "; if($get_current_category_is_interesting == "1"){ echo" checked=\"checked\""; } echo" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
		Yes
		&nbsp;
		<input type=\"radio\" name=\"inp_is_interesting\" value=\"0\" "; if($get_current_category_is_interesting == "0"){ echo" checked=\"checked\""; } echo" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
		No
		</p>
			
		<p>
		<input type=\"submit\" value=\"Save\" class=\"btn_default\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
		</p>

		</form>
	<!-- //New category form -->
	";
} // action == ""
?>