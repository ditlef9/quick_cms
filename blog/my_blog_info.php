<?php 
/**
*
* File: blog/my_blog_info.php
* Version 1.0.0
* Date 12:05 10.02.2018
* Copyright (c) 2011-2018 S. A. Ditlefsen
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

/*- Tables ---------------------------------------------------------------------------- */
include("_tables_blog.php");

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/blog/ts_index.php");
include("$root/_admin/_translations/site/$l/blog/ts_my_blog.php");

/*- Variables ------------------------------------------------------------------------- */


$tabindex = 0;
$l_mysql = quote_smart($link, $l);




/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_info - $l_my_blog - $l_blog";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");

/*- Content ---------------------------------------------------------------------------------- */
// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
	// Get my user
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_user_id, $get_user_email, $get_user_name, $get_user_alias, $get_user_rank) = $row;

	// Get blog info
	$query = "SELECT blog_info_id, blog_user_id, blog_language, blog_title, blog_description, blog_created, blog_updated, blog_posts, blog_comments, blog_views, blog_new_comments_email_warning, blog_user_ip FROM $t_blog_info WHERE blog_user_id=$my_user_id_mysql AND blog_language=$l_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_blog_info_id, $get_blog_user_id, $get_blog_language, $get_blog_title, $get_blog_description, $get_blog_created, $get_blog_updated, $get_blog_posts, $get_blog_comments, $get_blog_views, $get_blog_new_comments_email_warning, $get_blog_user_ip) = $row;

	if($get_blog_info_id == ""){

		echo"
		<h1><img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />Loading...</h1>
		<meta http-equiv=\"refresh\" content=\"1;url=$root/blog/my_blog_setup.php?l=$l\">
	

		<p>$l_creating_your_blog</p>
		";
	}
	else{
		if($process == "1"){
			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			$inp_description = $_POST['inp_description'];
			$inp_description = output_html($inp_description);
			$inp_description_mysql = quote_smart($link, $inp_description);

			if(isset($_POST['inp_new_comments_email_warning'])){
				$inp_new_comments_email_warning = $_POST['inp_new_comments_email_warning'];
				$inp_new_comments_email_warning = 1;
			}
			else{
				$inp_new_comments_email_warning = 0;
			}
			$inp_new_comments_email_warning = output_html($inp_new_comments_email_warning);
			$inp_new_comments_email_warning_mysql = quote_smart($link, $inp_new_comments_email_warning);

			// Update
			$result = mysqli_query($link, "UPDATE $t_blog_info SET blog_title=$inp_title_mysql, 
							blog_description=$inp_description_mysql,
							blog_new_comments_email_warning=$inp_new_comments_email_warning_mysql WHERE blog_info_id='$get_blog_info_id'");
 
				


			$url = "my_blog_info.php?l=$l";
			$url = $url . "&ft=success&fm=changes_saved";
			header("Location: $url");
			exit;

		}
		echo"
		<h1>$l_my_blog</h1>
	
		<!-- Where am I ? -->
			<p><b>$l_you_are_here:</b><br />
			<a href=\"index.php?l=$l\">$l_blog</a>
			&gt;
			<a href=\"view_blog.php?info_id=$get_blog_info_id&amp;l=$l\">$get_blog_title</a>
			&gt;
			<a href=\"my_blog.php?l=$l\">$l_my_blog</a>
			&gt;
			<a href=\"my_blog_info.php?l=$l\">$l_info</a>
			</p>
		<!-- Where am I ? -->
				
				
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


		<!-- Form -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_title\"]').focus();
			});
			</script>
		
			<form method=\"post\" action=\"my_blog_info.php?l=$l&amp;process=1\" enctype=\"multipart/form-data\">
				

			<p><b>$l_title:</b><br />
			<input type=\"text\" name=\"inp_title\" value=\"$get_blog_title\" size=\"40\" />
			</p>

			<p><b>$l_description:</b><br />
			<textarea name=\"inp_description\" rows=\"5\" cols=\"50\">";
			$get_blog_description = str_replace("<br />", "\n", $get_blog_description);
			echo"$get_blog_description</textarea>
			</p>

			<p><b>$l_email_warning_on_new_comments:</b><br />
			<input type=\"checkbox\" name=\"inp_new_comments_email_warning\" "; if($get_blog_new_comments_email_warning == "1"){ echo" checked=\"checked\""; } echo" />
			</p>

			<p><input type=\"submit\" value=\"$l_save\" class=\"btn btn_default\" /></p>
			</form>

		<!-- //Form -->
		";
	} // found
}
else{
	echo"
	<h1>
	<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/index.php?page=login&amp;l=$l&amp;refer=$root/blog/my_blog.php\">
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>