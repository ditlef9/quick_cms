<?php
/**
*
* File: _admin/_inc/blog/settings.php
* Version 109:15 21.01.2022
* Copyright (c) 2022 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Config ----------------------------------------------------------------------- */
if(!(file_exists("_data/blog.php"))){
	$update_file="<?php
\$blogActiveSav    	   = \"1\";
\$blogWhoCanHaveBlogSav    = \"everyone\";
\$blogEditModeSav 	   = \"wuciwug\";
\$blogPrintLogoOnImagesSav = \"0\";

\$blogPostsImageSizeXSav = \"1280\";
\$blogPostsImageSizeYSav = \"720\";

\$blogPostsThumbSmallSizeXSav = \"100\";
\$blogPostsThumbSmallSizeYSav = \"56\";

\$blogPostsThumbMediumSizeXSav = \"400\";
\$blogPostsThumbMediumSizeYSav = \"225\";

\$blogPostsThumbLargeSizeXSav = \"818\";
\$blogPostsThumbLargeSizeYSav = \"460\";
?>";

	$fh = fopen("_data/blog.php", "w+") or die("can not open file");
	fwrite($fh, $update_file);
	fclose($fh);
}
/*- Check if setup is run ------------------------------------------------------------- */
$t_blog_liquidbase			= $mysqlPrefixSav . "blog_liquidbase";
$query = "SELECT * FROM $t_blog_liquidbase LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
}
else{
	echo"
	<div class=\"info\"><p><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Running setup</p></div>
	<meta http-equiv=\"refresh\" content=\"1;url=index.php?open=blog&amp;page=tables&amp;refererer=default&amp;editor_language=$editor_language&amp;l=$l\" />
	";
}

/*- Variables ------------------------------------------------------------------------ */
$tabindex = 0;


/*- Process -------------------------------------------------------------------------- */
if($process == "1"){
	$inp_active = $_POST['inp_active'];
	$inp_active = output_html($inp_active);

	$inp_who_can_have_blog = $_POST['inp_who_can_have_blog'];
	$inp_who_can_have_blog = output_html($inp_who_can_have_blog);

	$inp_edit_mode = $_POST['inp_edit_mode'];
	$inp_edit_mode = output_html($inp_edit_mode);

	$inp_print_logo_on_images = $_POST['inp_print_logo_on_images'];
	$inp_print_logo_on_images = output_html($inp_print_logo_on_images);

	$inp_posts_image_size_x = $_POST['inp_posts_image_size_x'];
	$inp_posts_image_size_x = output_html($inp_posts_image_size_x);
	$inp_posts_image_size_y = $_POST['inp_posts_image_size_y'];
	$inp_posts_image_size_y = output_html($inp_posts_image_size_y);

	$inp_posts_thumb_small_size_x = $_POST['inp_posts_thumb_small_size_x'];
	$inp_posts_thumb_small_size_x = output_html($inp_posts_thumb_small_size_x);
	$inp_posts_thumb_small_size_y = $_POST['inp_posts_thumb_small_size_y'];
	$inp_posts_thumb_small_size_y = output_html($inp_posts_thumb_small_size_y);

	$inp_posts_thumb_medium_size_x = $_POST['inp_posts_thumb_medium_size_x'];
	$inp_posts_thumb_medium_size_x = output_html($inp_posts_thumb_medium_size_x);
	$inp_posts_thumb_medium_size_y = $_POST['inp_posts_thumb_medium_size_y'];
	$inp_posts_thumb_medium_size_y = output_html($inp_posts_thumb_medium_size_y);

	$inp_posts_thumb_large_size_x = $_POST['inp_posts_thumb_large_size_x'];
	$inp_posts_thumb_large_size_x = output_html($inp_posts_thumb_large_size_x);
	$inp_posts_thumb_large_size_y = $_POST['inp_posts_thumb_large_size_y'];
	$inp_posts_thumb_large_size_y = output_html($inp_posts_thumb_large_size_y);


	$update_file="<?php
\$blogActiveSav    	   = \"$inp_active\";
\$blogWhoCanHaveBlogSav = \"$inp_who_can_have_blog\";
\$blogEditModeSav 	= \"$inp_edit_mode\";
\$blogPrintLogoOnImagesSav = \"$inp_print_logo_on_images\";

\$blogPostsImageSizeXSav = \"$inp_posts_image_size_x\";
\$blogPostsImageSizeYSav = \"$inp_posts_image_size_y\";

\$blogPostsThumbSmallSizeXSav = \"$inp_posts_thumb_small_size_x\";
\$blogPostsThumbSmallSizeYSav = \"$inp_posts_thumb_small_size_y\";

\$blogPostsThumbMediumSizeXSav = \"$inp_posts_thumb_medium_size_x\";
\$blogPostsThumbMediumSizeYSav = \"$inp_posts_thumb_medium_size_y\";

\$blogPostsThumbLargeSizeXSav = \"$inp_posts_thumb_large_size_x\";
\$blogPostsThumbLargeSizeYSav = \"$inp_posts_thumb_large_size_y\";
?>";

	$fh = fopen("_data/blog.php", "w+") or die("can not open file");
	fwrite($fh, $update_file);
	fclose($fh);

	$datetime = date("Y-m-d H:i:s");
	header("Location: ?open=$open&page=$page&ft=success&fm=changes_saved&datetime=$datetime");
	exit;

}


/*- Include config ------------------------------------------------------------------------ */
include("_data/blog.php");

echo"
<h1>Blog</h1>

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


<!-- Blog module menu buttons -->
	";

	// Navigation
	$query = "SELECT navigation_id FROM $t_pages_navigation WHERE navigation_url_path='blog/index.php'";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_navigation_id) = $row;
	if($get_navigation_id == ""){
		echo"
		<p>
		<a href=\"index.php?open=pages&amp;page=navigation&amp;action=new_auto_insert&amp;module=blog&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_default\">Create navigation</a>
		</p>
		";
	}
	echo"
<!-- //Blog module menu buttons -->

<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;process=1\" enctype=\"multipart/form-data\">


	<p>Blog active:<br />
	<input type=\"radio\" name=\"inp_active\" value=\"1\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\""; if($blogActiveSav == "1"){ echo" checked=\"checked\""; } echo" /> Yes
	&nbsp;
	<input type=\"radio\" name=\"inp_active\" value=\"0\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\""; if($blogActiveSav == "0"){ echo" checked=\"checked\""; } echo" /> No
	</p>

	<p>Who can have blog:<br />
	<select name=\"inp_who_can_have_blog\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">
		<option value=\"admin\""; if($blogWhoCanHaveBlogSav == "admin"){ echo" selected=\"selected\""; } echo">Admin</option>
		<option value=\"admin_and_moderator\""; if($blogWhoCanHaveBlogSav == "admin_and_moderator"){ echo" selected=\"selected\""; } echo">Admin and moderator</option>
		<option value=\"admin_moderator_and_editor\""; if($blogWhoCanHaveBlogSav == "admin_moderator_and_editor"){ echo" selected=\"selected\""; } echo">Admin, moderator and editor</option>
		<option value=\"admin_moderator_editor_and_trusted\""; if($blogWhoCanHaveBlogSav == "admin_moderator_editor_and_trusted"){ echo" selected=\"selected\""; } echo">Admin, moderator, editor and trusted</option>
		<option value=\"everyone\""; if($blogWhoCanHaveBlogSav == "everyone"){ echo" selected=\"selected\""; } echo">Everyone</option>
	</select>
	</p>

	<p>Edit mode:<br />
	<select name=\"inp_edit_mode\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">
		<option value=\"wuciwug\""; if($blogEditModeSav == "wuciwug"){ echo" selected=\"selected\""; } echo">wuciwug</option>
		<option value=\"bbcode\""; if($blogEditModeSav == "bbcode"){ echo" selected=\"selected\""; } echo">BB Code</option>
	</select>
	</p>

	<p>Print logo on images:<br />
	(<a href=\"index.php?open=webdesign&amp;page=logo&amp;editor_language=$editor_language&amp;l=$l\" class=\"small\">Upload logo</a>)<br />
	<input type=\"radio\" name=\"inp_print_logo_on_images\" value=\"1\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\""; if($blogPrintLogoOnImagesSav == "1"){ echo" checked=\"checked\""; } echo" /> Yes
	&nbsp;
	<input type=\"radio\" name=\"inp_print_logo_on_images\" value=\"0\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\""; if($blogPrintLogoOnImagesSav == "0"){ echo" checked=\"checked\""; } echo" /> No
	</p>


	<p>Blog posts image size:<br />
	<input type=\"text\" name=\"inp_posts_image_size_x\" value=\"$blogPostsImageSizeXSav\" size=\"3\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /> x
	<input type=\"text\" name=\"inp_posts_image_size_y\" value=\"$blogPostsImageSizeYSav\" size=\"3\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
	</p>

	<p>Blog posts thumb small:<br />
	<input type=\"text\" name=\"inp_posts_thumb_small_size_x\" value=\"$blogPostsThumbSmallSizeXSav\" size=\"3\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /> x
	<input type=\"text\" name=\"inp_posts_thumb_small_size_y\" value=\"$blogPostsThumbSmallSizeYSav\" size=\"3\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
	</p>

	<p>Blog posts thumb medium:<br />
	<input type=\"text\" name=\"inp_posts_thumb_medium_size_x\" value=\"$blogPostsThumbMediumSizeXSav\" size=\"3\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /> x
	<input type=\"text\" name=\"inp_posts_thumb_medium_size_y\" value=\"$blogPostsThumbMediumSizeYSav\" size=\"3\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
	</p>

	<p>Blog posts thumb large:<br />
	<input type=\"text\" name=\"inp_posts_thumb_large_size_x\" value=\"$blogPostsThumbLargeSizeXSav\" size=\"3\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /> x
	<input type=\"text\" name=\"inp_posts_thumb_large_size_y\" value=\"$blogPostsThumbLargeSizeYSav\" size=\"3\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
	</p>

	<p><input type=\"submit\" value=\"$l_save_changes\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
</form>

";
?>