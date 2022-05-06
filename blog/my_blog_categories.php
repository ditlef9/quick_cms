<?php 
/**
*
* File: blog/my_blog_categories.php
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


if(isset($_GET['category_id'])) {
	$category_id = $_GET['category_id'];
	$category_id = strip_tags(stripslashes($category_id));
}
else{
	$category_id = "";
}


/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_my_blog - $l_blog";
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
		
	
			<!-- Where am I ? -->
				<p><b>$l_you_are_here:</b><br />
				<a href=\"index.php?l=$l\">$l_blog</a>
				&gt;
				<a href=\"view_blog.php?info_id=$get_blog_info_id&amp;l=$l\">$get_blog_title</a>
				&gt;
				<a href=\"my_blog.php?l=$l\">$l_my_blog</a>
				&gt;
				<a href=\"my_blog_categories.php?l=$l\">$l_categories</a>
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


			<p>
			<a href=\"my_blog_categories.php?action=new_category&amp;l=$l\" class=\"btn btn_default\">$l_new_category</a>
			</p>
			
			<!-- Categories -->

				<table class=\"hor-zebra\">
				 <thead>
				  <tr>
				   <th scope=\"col\">
					<span>$l_title</span>
				   </th>
				   <th scope=\"col\">
					<span>$l_posts</span>
				   </th>
				   <th scope=\"col\">
					<span>$l_actions</span>
				   </th>
				  </tr>
				 </thead>
				 <tbody>
				";
				
				$query = "SELECT blog_category_id, blog_category_title, blog_category_posts FROM $t_blog_categories WHERE blog_category_user_id=$my_user_id_mysql AND blog_category_language=$l_mysql";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_blog_category_id, $get_blog_category_title, $get_blog_category_posts) = $row;
			

					// Style
					if(isset($style) && $style == ""){
						$style = "odd";
					}
					else{
						$style = "";
					}
					echo"
					 <tr>
					  <td class=\"$style\">
						<a href=\"view_blog_open_category.php?info_id=$get_blog_info_id&amp;category_id=$get_blog_category_id\">$get_blog_category_title</a>
					  </td>
					  <td class=\"$style\">
						<span>$get_blog_category_posts</span>
					  </td>
					  <td class=\"$style\">
						<span>
						<a href=\"my_blog_categories.php?action=edit_category&amp;category_id=$get_blog_category_id&amp;l=$l\">$l_edit</a>
						&middot;
						<a href=\"my_blog_categories.php?action=delete_category&amp;category_id=$get_blog_category_id&amp;l=$l\">$l_delete</a>
						</span>
					  </td>
					 </tr>
					";
				}
				echo"
				 </tbody>
				</table>
			<!-- //Categories -->
			";
		} // action == ""
		elseif($action == "new_category"){
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

				// Duplicates?
				$query = "SELECT blog_category_id FROM $t_blog_categories WHERE blog_category_user_id=$my_user_id_mysql AND blog_category_language=$l_mysql AND blog_category_title=$inp_title_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_blog_category_id) = $row;
				if($get_blog_category_id != ""){
					$url = "my_blog_categories.php?action=$action&l=$l";
					$url = $url . "&ft=error&fm=you_already_have_that_category";
					header("Location: $url");
					exit;
				}


				mysqli_query($link, "INSERT INTO $t_blog_categories
				(blog_category_id, blog_category_user_id, blog_category_language, blog_category_title, blog_category_posts) 
				VALUES 
				(NULL, $my_user_id_mysql, $l_mysql, $inp_title_mysql, '0')")
				or die(mysqli_error($link));

				$url = "my_blog_categories.php?l=$l";
				$url = $url . "&ft=success&fm=category_created";
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
				<a href=\"my_blog_categories.php?l=$l\">$l_categories</a>
				&gt;
				<a href=\"my_blog_categories.php?action=$action&amp;l=$l\">$l_new_category</a>
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


			<!-- New category Form -->

				<h2>$l_new_category</h2>

				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_title\"]').focus();
				});
				</script>
		
				<form method=\"post\" action=\"my_blog_categories.php?action=$action&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
				

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
			$query = "SELECT blog_category_id, blog_category_title FROM $t_blog_categories WHERE blog_category_id=$category_id_mysql AND blog_category_user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_blog_category_id, $get_blog_category_title) = $row;
			if($get_blog_category_id == ""){
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

					
					$result = mysqli_query($link, "UPDATE $t_blog_categories SET blog_category_title=$inp_title_mysql
							WHERE blog_category_id='$get_blog_category_id'");
 
				
					$url = "my_blog_categories.php?l=$l";
					$url = $url . "&ft=success&fm=changes_saved";
					header("Location: $url");
					exit;
				
				}
				echo"
				<h1>$l_edit_category $get_blog_category_title</h1>
		
				<!-- Where am I ? -->
					<p><b>$l_you_are_here:</b><br />
					<a href=\"index.php?l=$l\">$l_blog</a>
					&gt;
					<a href=\"view_blog.php?info_id=$get_blog_info_id&amp;l=$l\">$get_blog_title</a>
					&gt;
					<a href=\"my_blog.php?l=$l\">$l_my_blog</a>
					&gt;
					<a href=\"my_blog_categories.php?l=$l\">$l_categories</a>
					&gt;
					<a href=\"my_blog_categories.php?action=$action&amp;category_id=$get_category_id&amp;l=$l\">$l_edit_category</a>
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


				<!-- Edit category Form -->

				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_title\"]').focus();
				});
				</script>
		
				<form method=\"post\" action=\"my_blog_categories.php?action=$action&amp;category_id=$category_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
				

				<p><b>$l_title:</b><br />
				<input type=\"text\" name=\"inp_title\" value=\"$get_blog_category_title\" size=\"40\" />
				</p>

				<p><input type=\"submit\" value=\"$l_save\" class=\"btn btn_default\" /></p>
				</form>

				<!-- //Edit category Form -->
				";
			} // category found
		} // edit
		elseif($action == "delete_category"){

			$category_id_mysql = quote_smart($link, $category_id);
			$query = "SELECT blog_category_id, blog_category_title FROM $t_blog_categories WHERE blog_category_id=$category_id_mysql AND blog_category_user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_blog_category_id, $get_blog_category_title) = $row;
			if($get_blog_category_id == ""){
				echo"<p>Category not found.</p>";
			}
			else{
				if($process == "1"){
					
					$result = mysqli_query($link, "DELETE FROM $t_blog_categories 
							WHERE blog_category_id='$get_blog_category_id'");
 
				
					$url = "my_blog_categories.php?l=$l";
					$url = $url . "&ft=success&fm=category_deleted";
					header("Location: $url");
					exit;
				
				}
				echo"
				<h1>$l_delete_category $get_blog_category_title</h1>

				<!-- Where am I ? -->
					<p><b>$l_you_are_here:</b><br />
					<a href=\"index.php?l=$l\">$l_blog</a>
					&gt;
					<a href=\"view_blog.php?info_id=$get_blog_info_id&amp;l=$l\">$get_blog_title</a>
					&gt;
					<a href=\"my_blog.php?l=$l\">$l_my_blog</a>
					&gt;
					<a href=\"my_blog_categories.php?l=$l\">$l_categories</a>
					&gt;
					<a href=\"my_blog_categories.php?action=$action&amp;category_id=$get_category_id&amp;l=$l\">$l_delete_category</a>
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


				<!-- Delete category Form -->

				<p>$l_are_you_sure</p>

				<p>
				<a href=\"my_blog_categories.php?action=$action&amp;category_id=$category_id&amp;l=$l&amp;process=1\" class=\"btn btn_warning\">$l_confirm</a>
				</p>
				<!-- //Delete category Form -->
				";
			} // category found
		} // delete
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