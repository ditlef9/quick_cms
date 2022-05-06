<?php 
/**
*
* File: howto/space_categories.php
* Version 1.0
* Date 14:55 30.06.2019
* Copyright (c) 2019 S. A. Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Configuration ---------------------------------------------------------------------------- */
$pageIdSav            = "2";
$pageNoColumnSav      = "2";
$pageAllowCommentsSav = "1";
$pageAuthorUserIdSav  = "1";

/*- Root dir --------------------------------------------------------------------------------- */
// This determine where we are
if(file_exists("favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
elseif(file_exists("../../../../favicon.ico")){ $root = "../../../.."; }
else{ $root = "../../.."; }

/*- Website config --------------------------------------------------------------------------- */
include("$root/_admin/website_config.php");

/*- Translation ------------------------------------------------------------------------------- */
include("$root/_admin/_translations/site/$l/knowledge/ts_new_page.php");
include("$root/_admin/_translations/site/$l/knowledge/ts_view_page.php");
include("$root/_admin/_translations/site/$l/knowledge/ts_edit_space.php");

/*- Functions ------------------------------------------------------------------------- */
include("$root/_admin/_functions/encode_national_letters.php");
include("$root/_admin/_functions/decode_national_letters.php");

/*- Variables -------------------------------------------------------------------------------- */
$tabindex = 0;

// Find me
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$l_space_categories";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
	include("$root/_webdesign/header.php");


	// Access?
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);

	// Get my user
	$query = "SELECT user_id, user_email, user_name, user_alias, user_language, user_last_online, user_rank, user_login_tries FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_language, $get_my_user_last_online, $get_my_user_rank, $get_my_user_login_tries) = $row;
	
	if($get_my_user_rank == "admin" OR $get_my_user_rank == "moderator"){
		if($action == ""){
			echo"
			<h1>$l_space_categories</h1>

			<!-- Where am I ? -->
				<p><b>$l_you_are_here:</b><br />
				<a href=\"index.php?l=$l\">$l_spaces</a>
				&gt;
				<a href=\"space_categories.php?l=$l\">$l_space_categories</a>
				</p>
			<!-- //Where am I ? -->

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

			<!-- Categories list -->
				<p><a href=\"space_categories.php?action=new_category&amp;l=$l\" class=\"btn_default\">$l_new_category</a></p>
				<table class=\"hor-zebra\">
				 <thead>
				  <tr>
				   <th scope=\"col\">
					<span>$l_category</span>
				   </th>
				  </tr>
			 	 </thead>
				<tbody>";
				// Select
				$query = "SELECT category_id, category_title FROM $t_knowledge_spaces_categories ORDER BY category_title ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_category_id, $get_category_title) = $row;

					// Style
					if(isset($odd) && $odd == false){
						$odd = true;
					}
					else{
						$odd = false;
					}	

					echo"
					 <tr>
       					  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
						<span><a href=\"space_categories.php?action=edit_category&amp;category_id=$get_category_id&amp;l=$l\">$get_category_title</a></span>
					  </td>
     					 </tr>";
				}
				echo"
				 </tbody>
				</table>
			<!-- //Categories list -->

			";
		} // action == ""
		elseif($action == "new_category"){
			if($process == "1"){
				$inp_title = $_POST['inp_title'];
				$inp_title = output_html($inp_title);
				$inp_title_mysql = quote_smart($link, $inp_title);

				$inp_title_clean = clean($inp_title);
				$inp_title_clean_mysql = quote_smart($link, $inp_title_clean);

				$datetime = date("Y-m-d H:i:s");

				mysqli_query($link, "INSERT INTO $t_knowledge_spaces_categories
				(category_id, category_title, category_title_clean, category_created_datetime) 
				VALUES 
				(NULL, $inp_title_mysql, $inp_title_clean_mysql, '$datetime')")
				or die(mysqli_error($link));

				$url = "space_categories.php?l=$l&ft=success&fm=category_created";
				header("Location: $url");
				exit;
			}
			echo"
			<h1>$l_new_space_category</h1>

			<!-- Where am I ? -->
				<p><b>$l_you_are_here:</b><br />
				<a href=\"index.php?l=$l\">$l_spaces</a>
				&gt;
				<a href=\"space_categories.php?l=$l\">$l_space_categories</a>
				&gt;
				<a href=\"space_categories.php?action=new_category&amp;l=$l\">$l_new_category</a>
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
					$fm = str_replace("_", " ", $fm);
				}
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
			<!-- //Feedback -->

			<!-- New category form -->
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_title\"]').focus();
				});
				</script>
				
				

				<form method=\"POST\" action=\"space_categories.php?action=new_category&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

				<p><b>$l_title</b><br />
				<input type=\"text\" name=\"inp_title\" value=\"\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				</p>

				<p>
				<input type=\"submit\" value=\"$l_create\" class=\"btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				</p>

				</form>
			<!-- //Add member form -->
			";
		} // action == "new_category"
		elseif($action == "edit_category"){
			if(isset($_GET['category_id'])) {
				$category_id = $_GET['category_id'];
				$category_id = stripslashes(strip_tags($category_id));
			}
			else{
				$category_id = "";
			}
			$category_id_mysql = quote_smart($link, $category_id);

			
			$query = "SELECT category_id, category_title FROM $t_knowledge_spaces_categories WHERE category_id=$category_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_category_id, $get_current_category_title) = $row;
			
			if($get_current_category_id == ""){
				echo"
				<h1>Category not found</h1>

				<p>
				<a href=\"space_categories.php?l=$l\">$l_categories</a>
				</p>
				";
			}
			else{


				if($process == "1"){
					$inp_title = $_POST['inp_title'];
					$inp_title = output_html($inp_title);
					$inp_title_mysql = quote_smart($link, $inp_title);

					$inp_title_clean = clean($inp_title);
					$inp_title_clean_mysql = quote_smart($link, $inp_title_clean);


					$result = mysqli_query($link, "UPDATE $t_knowledge_spaces_categories SET 
									category_title=$inp_title_mysql,
									category_title_clean=$inp_title_clean_mysql
									 WHERE category_id=$get_current_category_id");



					$url = "space_categories.php?l=$l&ft=success&fm=category_changes_saved";
					header("Location: $url");
					exit;
				}
				echo"
				<h1>$l_edit_category $get_current_category_title</h1>

				<!-- Where am I ? -->
				<p><b>$l_you_are_here:</b><br />
				<a href=\"index.php?l=$l\">$l_spaces</a>
				&gt;
				<a href=\"space_categories.php?l=$l\">$l_space_categories</a>
				&gt;
				<a href=\"space_categories.php?action=edit_category&amp;category_id=$get_current_category_id&amp;l=$l\">$l_edit_category</a>
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
					$fm = str_replace("_", " ", $fm);
				}
				echo"<div class=\"$ft\"><span>$fm</span></div>";
				}
				echo"	
				<!-- //Feedback -->

				<!-- Edit category form -->
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_title\"]').focus();
				});
				</script>
				
				

				<form method=\"POST\" action=\"space_categories.php?action=edit_category&amp;category_id=$get_current_category_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

				<p><b>$l_title</b><br />
				<input type=\"text\" name=\"inp_title\" value=\"$get_current_category_title\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				</p>

				<p>
				<input type=\"submit\" value=\"$l_edit\" class=\"btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				<a href=\"space_categories.php?action=delete_category&amp;category_id=$get_current_category_id&amp;l=$l\" class=\"btn_warning\">$l_delete</a>
				</p>

				</form>
				<!-- //Edit category form -->
				";
			} // category found
		} // action == "edit_category"
		elseif($action == "delete_category"){
			if(isset($_GET['category_id'])) {
				$category_id = $_GET['category_id'];
				$category_id = stripslashes(strip_tags($category_id));
			}
			else{
				$category_id = "";
			}
			$category_id_mysql = quote_smart($link, $category_id);

			
			$query = "SELECT category_id, category_title FROM $t_knowledge_spaces_categories WHERE category_id=$category_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_category_id, $get_current_category_title) = $row;
			
			if($get_current_category_id == ""){
				echo"
				<h1>Category not found</h1>

				<p>
				<a href=\"space_categories.php?l=$l\">$l_categories</a>
				</p>
				";
			}
			else{


				if($process == "1"){
					
					$result = mysqli_query($link, "DELETE FROM $t_knowledge_spaces_categories WHERE category_id=$get_current_category_id");



					$url = "space_categories.php?l=$l&ft=success&fm=category_deleted";
					header("Location: $url");
					exit;
				}
				echo"
				<h1>$l_delete_category $get_current_category_title</h1>

				<!-- Where am I ? -->
					<p><b>$l_you_are_here:</b><br />
					<a href=\"index.php?l=$l\">$l_spaces</a>
					&gt;
					<a href=\"space_categories.php?l=$l\">$l_space_categories</a>
					&gt;
					<a href=\"space_categories.php?action=delete_category&amp;category_id=$get_current_category_id&amp;l=$l\">$l_delete_category</a>
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
					$fm = str_replace("_", " ", $fm);
				}
				echo"<div class=\"$ft\"><span>$fm</span></div>";
				}
				echo"	
				<!-- //Feedback -->

				<!-- Delete category form -->
					<p>$l_are_you_sure</p>

					<p>
					<a href=\"space_categories.php?action=delete_category&amp;category_id=$get_current_category_id&amp;l=$l&amp;process=1\" class=\"btn_warning\">$l_confirm_delete</a>
					</p>

				<!-- //Delete category form -->
				";
			} // category found
		} // action == "delete_category"
	
	} // admin or mdoerator
	else{
		echo"
		<h1>Access denied</h1>

		<p>
		Only admins and moderators can edit space categories.
		</p>
		";
	} // ! admin or mdoerator
}
else{
	$url = "$root/users/login.php?l=$l&amp;referer=$root/knowledge/space_categories.php";
	header("Location: $url");
	exit;
} // not logged in


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/$webdesignSav/footer.php");
?>