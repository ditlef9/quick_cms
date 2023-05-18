<?php 
/**
*
* File: blog/my_blog_links.php
* Version 1.0.0
* Date 17:19 15.03.2019
* Copyright (c) 2019 S. A. Ditlefsen
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


if(isset($_GET['category_id'])) {
	$category_id = $_GET['category_id'];
	$category_id = strip_tags(stripslashes($category_id));
}
else{
	$category_id = "";
}
if(isset($_GET['link_id'])) {
	$link_id = $_GET['link_id'];
	$link_id = strip_tags(stripslashes($link_id));
}
else{
	$link_id = "";
}


/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_links - $l_my_blog - $l_blog";
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
	$query = "SELECT blog_info_id, blog_user_id, blog_language, blog_title, blog_description, blog_created, blog_updated, blog_posts, blog_comments, blog_views, blog_user_ip FROM $t_blog_info WHERE blog_user_id=$my_user_id_mysql AND blog_language=$l_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_blog_info_id, $get_blog_user_id, $get_blog_language, $get_blog_title, $get_blog_description, $get_blog_created, $get_blog_updated, $get_blog_posts, $get_blog_comments, $get_blog_views, $get_blog_user_ip) = $row;

	if($get_blog_info_id == ""){
		echo"
		<h1><img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />Loading...</h1>
		<meta http-equiv=\"refresh\" content=\"1;url=$root/blog/my_blog_setup.php?l=$l\">
		<p>$l_creating_your_blog</p>
		";
	}
	else{
		if($action == ""){
			echo"
			<h1>$l_my_blog</h1>
		
			<!-- Where am I? -->
				<p><b>$l_you_are_here:</b><br />
				<a href=\"index.php?l=$l\">$l_blog</a>
				&gt;
				<a href=\"view_blog.php?info_id=$get_blog_info_id&amp;l=$l\">$get_blog_title</a>
				&gt;
				<a href=\"my_blog.php?l=$l\">$l_my_blog</a>
				&gt;
				<a href=\"my_blog_links.php?l=$l\">$l_links</a>
				</p>
			<!-- //Where am I? -->
				
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


			<p>
			<a href=\"my_blog_links.php?action=new_category&amp;l=$l\" class=\"btn btn_default\">$l_new_category</a>
			</p>
			
			<!-- Categories and links -->

				";

				$query = "SELECT category_id, category_blog_info_id, category_user_id, category_title FROM $t_blog_links_categories WHERE category_blog_info_id=$get_blog_info_id AND category_user_id=$my_user_id_mysql";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_category_id, $get_category_blog_info_id, $get_category_user_id, $get_category_title) = $row;
			
					echo"
					<p><b>$get_category_title</b>
					<a href=\"my_blog_links.php?action=new_link&amp;category_id=$get_category_id&amp;l=$l\"><img src=\"_gfx/icons/list-add.png\" alt=\"list-add.png\" /></a>
					<a href=\"my_blog_links.php?action=edit_category&amp;category_id=$get_category_id&amp;l=$l\"><img src=\"_gfx/icons/edit.png\" alt=\"edit.png\" /></a>
					<a href=\"my_blog_links.php?action=delete_category&amp;category_id=$get_category_id&amp;l=$l\"><img src=\"_gfx/icons/delete.png\" alt=\"delete.png\" /></a>
					</p>
					";

					// Links
					$query_links = "SELECT link_id, link_blog_info_id, link_user_id, link_category_id, link_title, link_url_real, link_url_display, link_description, link_clicks_unique, link_clicks_unique_ipblock, link_added, link_edited FROM $t_blog_links_index WHERE link_blog_info_id=$get_blog_info_id AND link_user_id=$my_user_id_mysql AND link_category_id=$get_category_id";
					$result_links = mysqli_query($link, $query_links);
					while($row_links = mysqli_fetch_row($result_links)) {
						list($get_link_id, $get_link_blog_info_id, $get_link_user_id, $get_link_category_id, $get_link_title, $get_link_url_real, $get_link_url_display, $get_link_description, $get_link_clicks_unique, $get_link_clicks_unique_ipblock, $get_link_added, $get_link_edited) = $row_links;

						echo"
						<p class=\"link\"><a href=\"$get_link_url_real\" class=\"link_title_a\">$get_link_title</a>
						<a href=\"my_blog_links.php?action=edit_link&amp;link_id=$get_link_id&amp;l=$l\"><img src=\"_gfx/icons/edit.png\" alt=\"edit.png\" /></a>
						<a href=\"my_blog_links.php?action=delete_link&amp;link_id=$get_link_id&amp;l=$l\"><img src=\"_gfx/icons/delete.png\" alt=\"delete.png\" /></a>
						<br />
						<a href=\"$get_link_url_real\" class=\"link_url_a\">$get_link_url_display</a>
						</p>
						<p class=\"link_description\">$get_link_description</p>
						";
					}
				}
				echo"
			<!-- //Categories and links -->
			";
		} // action == ""
		elseif($action == "new_category"){
			if($process == "1"){
				$inp_title = $_POST['inp_title'];
				$inp_title = output_html($inp_title);
				$inp_title_mysql = quote_smart($link, $inp_title);
				if(empty($inp_title)){
					$url = "my_blog_links.php?action=$action&l=$l";
					$url = $url . "&ft=error&fm=missing_title";
					header("Location: $url");
					exit;
				}

				// Duplicates?
				$query = "SELECT category_id FROM $t_blog_links_categories WHERE category_blog_info_id=$get_blog_info_id AND category_user_id=$my_user_id_mysql AND category_title=$inp_title_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_blog_category_id) = $row;
				if($get_blog_category_id != ""){
					$url = "my_blog_links.php?action=$action&l=$l";
					$url = $url . "&ft=error&fm=you_already_have_that_category";
					header("Location: $url");
					exit;
				}


				mysqli_query($link, "INSERT INTO $t_blog_links_categories 
				(category_id, category_blog_info_id, category_user_id, category_title) 
				VALUES 
				(NULL, $get_blog_info_id, $my_user_id_mysql, $inp_title_mysql)")
				or die(mysqli_error($link));

				$url = "my_blog_links.php?l=$l";
				$url = $url . "&ft=success&fm=category_created";
				header("Location: $url");
				exit;
				
			}
			echo"
			<h1>$l_my_blog</h1>
		


			<!-- Where am I? -->
				<p><b>$l_you_are_here:</b><br />
				<a href=\"index.php?l=$l\">$l_blog</a>
				&gt;
				<a href=\"view_blog.php?info_id=$get_blog_info_id&amp;l=$l\">$get_blog_title</a>
				&gt;
				<a href=\"my_blog.php?l=$l\">$l_my_blog</a>
				&gt;
				<a href=\"my_blog_links.php?l=$l\">$l_links</a>
				&gt;
				<a href=\"my_blog_links.php?action=new_category&amp;l=$l\">$l_new_category</a>
				</p>
			<!-- //Where am I? -->

				
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


			<!-- New category Form -->

				<h2>$l_new_category</h2>

				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_title\"]').focus();
				});
				</script>
		
				<form method=\"post\" action=\"my_blog_links.php?action=$action&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
				

				<p><b>$l_title:</b><br />
				<input type=\"text\" name=\"inp_title\" value=\"\" size=\"40\" />
				</p>

				<p><input type=\"submit\" value=\"$l_create\" class=\"btn btn_default\" /></p>
				</form>

			<!-- //New category Form -->
			";
		}
		elseif($action == "edit_category"){
			$category_id_mysql = quote_smart($link, $category_id);
			$query = "SELECT category_id, category_blog_info_id, category_user_id, category_title FROM $t_blog_links_categories WHERE category_id=$category_id_mysql AND category_blog_info_id=$get_blog_info_id AND category_user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_category_id, $get_category_blog_info_id, $get_category_user_id, $get_category_title) = $row;
			if($get_category_id == ""){
				echo"<p>Category not found.</p>";
			}
			else{
				if($process == "1"){
					$inp_title = $_POST['inp_title'];
					$inp_title = output_html($inp_title);
					$inp_title_mysql = quote_smart($link, $inp_title);
					if(empty($inp_title)){
						$url = "my_blog_categories.php?action=$action&l=$l";
						$url = $url . "&ft=error&fm=missing_title";
						header("Location: $url");
						exit;
					}

					
					$result = mysqli_query($link, "UPDATE $t_blog_links_categories SET category_title=$inp_title_mysql
							WHERE category_id=$category_id_mysql");
 
				
					$url = "my_blog_links.php?l=$l";
					$url = $url . "&ft=success&fm=changes_saved";
					header("Location: $url");
					exit;
				
				}
				echo"
				<h1>$l_my_blog</h1>
		

				<!-- Where am I? -->
					<p><b>$l_you_are_here:</b><br />
					<a href=\"index.php?l=$l\">$l_blog</a>
					&gt;
					<a href=\"view_blog.php?info_id=$get_blog_info_id&amp;l=$l\">$get_blog_title</a>
					&gt;
					<a href=\"my_blog.php?l=$l\">$l_my_blog</a>
					&gt;
					<a href=\"my_blog_links.php?l=$l\">$l_links</a>
					&gt;
					<a href=\"my_blog_links.php?action=edit_category&amp;category_id=$category_id&amp;l=$l\">$get_category_title</a>
					</p>
				<!-- //Where am I? -->
		
				
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


				<!-- Edit category Form -->

				<h2>$l_edit_category</h2>

				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_title\"]').focus();
				});
				</script>
		
				<form method=\"post\" action=\"my_blog_links.php?action=$action&amp;category_id=$category_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
				

				<p><b>$l_title:</b><br />
				<input type=\"text\" name=\"inp_title\" value=\"$get_category_title\" size=\"40\" />
				</p>

				<p><input type=\"submit\" value=\"$l_save\" class=\"btn btn_default\" /></p>
				</form>

				<!-- //Edit category Form -->
				";
			} // category found
		} // edit
		elseif($action == "delete_category"){

			$category_id_mysql = quote_smart($link, $category_id);
			$query = "SELECT category_id, category_blog_info_id, category_user_id, category_title FROM $t_blog_links_categories WHERE category_id=$category_id_mysql AND category_blog_info_id=$get_blog_info_id AND category_user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_category_id, $get_category_blog_info_id, $get_category_user_id, $get_category_title) = $row;
			if($get_category_id == ""){
				echo"<p>Category not found.</p>";
			}
			else{
				if($process == "1"){
					
					$result = mysqli_query($link, "DELETE FROM $t_blog_links_categories 
							WHERE category_id=$category_id_mysql");
 
				
					$url = "my_blog_links.php?l=$l";
					$url = $url . "&ft=success&fm=category_deleted";
					header("Location: $url");
					exit;
				
				}
				echo"
				<h1>$l_my_blog</h1>
		
				<!-- Where am I? -->
					<p><b>$l_you_are_here:</b><br />
					<a href=\"index.php?l=$l\">$l_blog</a>
					&gt;
					<a href=\"view_blog.php?info_id=$get_blog_info_id&amp;l=$l\">$get_blog_title</a>
					&gt;
					<a href=\"my_blog.php?l=$l\">$l_my_blog</a>
					&gt;
					<a href=\"my_blog_links.php?l=$l\">$l_links</a>
					&gt;
					<a href=\"my_blog_links.php?action=delete_category&amp;category_id=$category_id&amp;l=$l\">$get_category_title</a>
					</p>
				<!-- //Where am I? -->
				
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


				<!-- Delete category Form -->

				<h2>$l_delete_category $get_category_title</h2>

				<p>$l_are_you_sure</p>

				<p>
				<a href=\"my_blog_links.php?action=$action&amp;category_id=$category_id&amp;l=$l&amp;process=1\" class=\"btn btn_warning\">$l_confirm</a>
				</p>
				<!-- //Delete category Form -->
				";
			} // category found
		} // delete category
		elseif($action == "new_link"){

			$category_id_mysql = quote_smart($link, $category_id);
			$query = "SELECT category_id, category_blog_info_id, category_user_id, category_title FROM $t_blog_links_categories WHERE category_id=$category_id_mysql AND category_blog_info_id=$get_blog_info_id AND category_user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_category_id, $get_category_blog_info_id, $get_category_user_id, $get_category_title) = $row;
			if($get_category_id == ""){
				echo"<p>Category not found.</p>";
			}
			else{
				if($process == "1"){
					$inp_title = $_POST['inp_title'];
					$inp_title = output_html($inp_title);
					$inp_title_mysql = quote_smart($link, $inp_title);

					$inp_url_real = $_POST['inp_url_real'];
					$inp_url_real = output_html($inp_url_real);
					$inp_url_real_mysql = quote_smart($link, $inp_url_real);

					$inp_url_display = $_POST['inp_url_display'];
					$inp_url_display = output_html($inp_url_display);
					$inp_url_display_mysql = quote_smart($link, $inp_url_display);

					$inp_description = $_POST['inp_description'];
					$inp_description = output_html($inp_description);
					$inp_description_mysql = quote_smart($link, $inp_description);

					$inp_is_ad = "0";
					if(isset($_POST['inp_is_ad'])){
						$inp_is_ad = $_POST['inp_is_ad'];
						if($inp_is_ad == "on"){
							$inp_is_ad = "1";
						}
					}
					$inp_is_ad_mysql = quote_smart($link, $inp_is_ad);

					$datetime = date("Y-m-d H:i:s");

					if(empty($inp_title)){
						$url = "my_blog_links.php?action=$action&l=$l&category_id=$category_id&ft=error&fm=missing_title&inp_url_real=$inp_url_real&inp_url_display=$inp_url_display&inp_link_is_ad=$inp_link_is_ad&inp_description=$inp_description";
						header("Location: $url");
						exit;
					}
					if(empty($inp_url_real)){
						$url = "my_blog_links.php?action=$action&l=$l&category_id=$category_id&ft=error&fm=missing_url&inp_url_real=$inp_url_real&inp_url_display=$inp_url_display&inp_link_is_ad=$inp_link_is_ad&inp_description=$inp_description";
						header("Location: $url");
						exit;
					}

					mysqli_query($link, "INSERT INTO $t_blog_links_index
					(link_id, link_blog_info_id, link_user_id, link_category_id, link_title, link_url_real, link_url_display, link_description, link_is_ad, link_clicks_unique, link_clicks_unique_ipblock, link_added, link_edited) 
					VALUES 
					(NULL, $get_blog_info_id, $my_user_id_mysql, $get_category_id, $inp_title_mysql, $inp_url_real_mysql, $inp_url_display_mysql, $inp_description_mysql, $inp_is_ad_mysql, '0', '', '$datetime', '$datetime')")
					or die(mysqli_error($link));

				
					$url = "my_blog_links.php?action=new_link&category_id=$category_id&ft=success&fm=link_saved&l=$l";
					header("Location: $url");
					exit;
				
				}
				echo"
				<h1>$l_my_blog</h1>
		

				<!-- Where am I? -->
					<p><b>$l_you_are_here:</b><br />
					<a href=\"index.php?l=$l\">$l_blog</a>
					&gt;
					<a href=\"view_blog.php?info_id=$get_blog_info_id&amp;l=$l\">$get_blog_title</a>
					&gt;
					<a href=\"my_blog.php?l=$l\">$l_my_blog</a>
					&gt;
					<a href=\"my_blog_links.php?l=$l\">$l_links</a>
					&gt;
					<a href=\"my_blog_links.php?action=new_link&amp;category_id=$category_id&amp;l=$l\">$l_new_link</a>
					</p>
				<!-- //Where am I? -->
		
				
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


				<!-- New link Form -->

				<h2>$l_new_link</h2>

				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_title\"]').focus();
				});
				</script>
		
				<form method=\"post\" action=\"my_blog_links.php?action=$action&amp;category_id=$category_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
				

				<p><b>$l_title:</b><br />
				<input type=\"text\" name=\"inp_title\" value=\"\" size=\"25\" />
				</p>

				<p><b>$l_url:</b><br />
				<input type=\"text\" name=\"inp_url_real\" value=\"http://\" size=\"25\" />
				</p>

				<p><b>$l_display_url:</b><br />
				<input type=\"text\" name=\"inp_url_display\" value=\"http://\" size=\"25\" />
				</p>

				<p><b>$l_link_is_ad:</b><br />
				<input type=\"checkbox\" name=\"inp_is_ad\" /> $l_yes
				</p>


				<p><b>$l_description:</b><br />
				<textarea name=\"inp_description\" rows=\"5\" cols=\"30\"></textarea>
				</p>

				<p><input type=\"submit\" value=\"$l_save\" class=\"btn btn_default\" /></p>
				</form>

				<!-- //New link Form -->
				";
			} // category found
		} // new_link
		elseif($action == "edit_link"){
			$link_id_mysql = quote_smart($link, $link_id);
			$query = "SELECT link_id, link_blog_info_id, link_user_id, link_category_id, link_title, link_url_real, link_url_display, link_description, link_is_ad, link_clicks_unique, link_clicks_unique_ipblock, link_added, link_edited FROM $t_blog_links_index WHERE link_id=$link_id_mysql AND link_blog_info_id=$get_blog_info_id AND link_user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_link_id, $get_link_blog_info_id, $get_link_user_id, $get_link_category_id, $get_link_title, $get_link_url_real, $get_link_url_display, $get_link_description, $get_link_is_ad, $get_link_clicks_unique, $get_link_clicks_unique_ipblock, $get_link_added, $get_link_edited) = $row;
			if($get_link_id == ""){
				echo"<p>Link not found.</p>";
			}
			else{
				if($process == "1"){
					
					$inp_title = $_POST['inp_title'];
					$inp_title = output_html($inp_title);
					$inp_title_mysql = quote_smart($link, $inp_title);

					$inp_url_real = $_POST['inp_url_real'];
					$inp_url_real = output_html($inp_url_real);
					$inp_url_real_mysql = quote_smart($link, $inp_url_real);

					$inp_url_display = $_POST['inp_url_display'];
					$inp_url_display = output_html($inp_url_display);
					$inp_url_display_mysql = quote_smart($link, $inp_url_display);

					$inp_description = $_POST['inp_description'];
					$inp_description = output_html($inp_description);
					$inp_description_mysql = quote_smart($link, $inp_description);

					$inp_category = $_POST['inp_category'];
					$inp_category = output_html($inp_category);
					$inp_category_mysql = quote_smart($link, $inp_category);

					$inp_is_ad = "0";
					if(isset($_POST['inp_is_ad'])){
						$inp_is_ad = $_POST['inp_is_ad'];
						if($inp_is_ad == "on"){
							$inp_is_ad = "1";
						}
					}
					$inp_is_ad_mysql = quote_smart($link, $inp_is_ad);

					$datetime = date("Y-m-d H:i:s");

					if(empty($inp_title)){
						$url = "my_blog_links.php?action=$action&l=$l&link_id=$link_id&ft=error&fm=missing_title&inp_url_real=$inp_url_real&inp_url_display=$inp_url_display&inp_link_is_ad=$inp_link_is_ad&inp_description=$inp_description";
						header("Location: $url");
						exit;
					}
					if(empty($inp_url_real)){
						$url = "my_blog_links.php?action=$action&l=$l&link_id=$link_id&ft=error&fm=missing_url&inp_url_real=$inp_url_real&inp_url_display=$inp_url_display&inp_link_is_ad=$inp_link_is_ad&inp_description=$inp_description";
						header("Location: $url");
						exit;
					}
					
					$result = mysqli_query($link, "UPDATE $t_blog_links_index SET 
						link_category_id=$inp_category_mysql, link_title=$inp_title_mysql, link_url_real=$inp_url_real_mysql, 
						link_url_display=$inp_url_display_mysql, link_description=$inp_description_mysql, link_is_ad=$inp_is_ad_mysql
						WHERE link_id=$link_id_mysql AND link_blog_info_id=$get_blog_info_id AND link_user_id=$my_user_id_mysql") or die(mysqli_error($link));
 
					$url = "my_blog_links.php?l=$l";
					$url = $url . "&ft=success&fm=changes_saved";
					header("Location: $url");
					exit;
				
				}
				echo"
				<h1>$l_my_blog</h1>
		

				<!-- Where am I? -->
					<p><b>$l_you_are_here:</b><br />
					<a href=\"index.php?l=$l\">$l_blog</a>
					&gt;
					<a href=\"view_blog.php?info_id=$get_blog_info_id&amp;l=$l\">$get_blog_title</a>
					&gt;
					<a href=\"my_blog.php?l=$l\">$l_my_blog</a>
					&gt;
					<a href=\"my_blog_links.php?l=$l\">$l_links</a>
					&gt;
					<a href=\"my_blog_links.php?action=edit_link&amp;link_id=$link_id&amp;l=$l\">$l_edit_link</a>
					</p>
				<!-- //Where am I? -->
		
				
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


				<!-- Edit link Form -->

				<h2>$l_edit_link</h2>

				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_title\"]').focus();
				});
				</script>
		
				<form method=\"post\" action=\"my_blog_links.php?action=$action&amp;link_id=$link_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
				
				<p><b>$l_title:</b><br />
				<input type=\"text\" name=\"inp_title\" value=\"$get_link_title\" size=\"25\" />
				</p>

				<p><b>$l_url:</b><br />
				<input type=\"text\" name=\"inp_url_real\" value=\"$get_link_url_real\" size=\"25\" />
				</p>

				<p><b>$l_display_url:</b><br />
				<input type=\"text\" name=\"inp_url_display\" value=\"$get_link_url_display\" size=\"25\" />
				</p>

				<p><b>$l_description:</b><br />
				<textarea name=\"inp_description\" rows=\"5\" cols=\"30\">"; 
				$get_link_description = str_replace("<br />", "\n", $get_link_description);
				echo"$get_link_description</textarea>
				</p>

				<p><b>$l_category:</b><br />
				<select name=\"inp_category\">";
				$query = "SELECT category_id, category_blog_info_id, category_user_id, category_title FROM $t_blog_links_categories WHERE category_blog_info_id=$get_blog_info_id AND category_user_id=$my_user_id_mysql";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_category_id, $get_category_blog_info_id, $get_category_user_id, $get_category_title) = $row;
					echo"				";
					echo"<option value=\"$get_category_id\""; if($get_link_category_id == "$get_category_id"){ echo" selected=\"selected\""; } echo">$get_category_title</option>\n";
				}
				echo"</select>
				</p>

				<p><b>$l_link_is_ad:</b><br />
				<input type=\"checkbox\" name=\"inp_is_ad\""; if($get_link_is_ad == "1"){ echo" checked=\"checked\""; } echo" /> $l_yes
				</p>



				<p><input type=\"submit\" value=\"$l_save\" class=\"btn btn_default\" /></p>
				</form>

				<!-- //Edit link Form -->
				";
			} // category found
		} // edit link
		elseif($action == "delete_link"){
			$link_id_mysql = quote_smart($link, $link_id);
			$query = "SELECT link_id, link_blog_info_id, link_user_id, link_category_id, link_title, link_url_real, link_url_display, link_description, link_is_ad, link_clicks_unique, link_clicks_unique_ipblock, link_added, link_edited FROM $t_blog_links_index WHERE link_id=$link_id_mysql AND link_blog_info_id=$get_blog_info_id AND link_user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_link_id, $get_link_blog_info_id, $get_link_user_id, $get_link_category_id, $get_link_title, $get_link_url_real, $get_link_url_display, $get_link_description, $get_link_is_ad, $get_link_clicks_unique, $get_link_clicks_unique_ipblock, $get_link_added, $get_link_edited) = $row;
			if($get_link_id == ""){
				echo"<p>Link not found.</p>";
			}
			else{
				if($process == "1"){
					
					
					$result = mysqli_query($link, "DELETE FROM $t_blog_links_index 
						WHERE link_id=$link_id_mysql AND link_blog_info_id=$get_blog_info_id AND link_user_id=$my_user_id_mysql");
 
				
					$url = "my_blog_links.php?l=$l";
					$url = $url . "&ft=success&fm=link_deleted";
					header("Location: $url");
					exit;
				
				}
				echo"
				<h1>$l_my_blog</h1>
		

				<!-- Where am I? -->
					<p><b>$l_you_are_here:</b><br />
					<a href=\"index.php?l=$l\">$l_blog</a>
					&gt;
					<a href=\"view_blog.php?info_id=$get_blog_info_id&amp;l=$l\">$get_blog_title</a>
					&gt;
					<a href=\"my_blog.php?l=$l\">$l_my_blog</a>
					&gt;
					<a href=\"my_blog_links.php?l=$l\">$l_links</a>
					&gt;
					<a href=\"my_blog_links.php?action=delete_link&amp;link_id=$link_id&amp;l=$l\">$l_delete_link</a>
					</p>
				<!-- //Where am I? -->
		
				
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


				<!-- Delete link Form -->

				<h2>$l_delete_link</h2>

					<div style=\"border: #000 1px solid;padding: 0px 8px 0px 8px;margin-left: 10px;\">
						<p>
						$get_link_title<br />
						$get_link_url_display<br />
						$get_link_description
						</p>
					</div>

				<p>$l_are_you_sure</p>

				<p>
				<a href=\"my_blog_links.php?action=$action&amp;link_id=$link_id&amp;l=$l&amp;process=1\" class=\"btn_warning\">$l_confirm</a>
				</p>

				<!-- //Delete link Form -->
				";
			} // category found
		} // delete link
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