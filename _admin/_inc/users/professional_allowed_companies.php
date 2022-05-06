<?php
/**
*
* File: _admin/_inc/users/professional_allowed_companies.php
* Version 1.0
* Date: 18:32 30.10.2017
* Copyright (c) 2008-2012 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Variables -------------------------------------------------------------------------- */
if(isset($_GET['row_id'])) {
	$row_id = $_GET['row_id'];
	$row_id = strip_tags(stripslashes($row_id));
	if(!(is_numeric($row_id))){
		echo"Row id not not numeric";
		die;
	}
}
else{
	$row_id = "";
}





/*- MySQL Tables -------------------------------------------------- */
$t_users 	 		= $mysqlPrefixSav . "users";
$t_users_profile 		= $mysqlPrefixSav . "users_profile";
$t_users_friends 		= $mysqlPrefixSav . "users_friends";
$t_users_friends_requests 	= $mysqlPrefixSav . "users_friends_requests";
$t_users_profile		= $mysqlPrefixSav . "users_profile";
$t_users_profile_photo 		= $mysqlPrefixSav . "users_profile_photo";
$t_users_status 		= $mysqlPrefixSav . "users_status";
$t_users_status_comments 	= $mysqlPrefixSav . "users_status_comments";
$t_users_status_comments_likes 	= $mysqlPrefixSav . "users_status_comments_likes";
$t_users_status_likes 		= $mysqlPrefixSav . "users_status_likes";

$t_users_professional 					= $mysqlPrefixSav . "users_professional";
$t_users_professional_allowed_companies			= $mysqlPrefixSav . "users_professional_allowed_companies";
$t_users_professional_allowed_company_locations		= $mysqlPrefixSav . "users_professional_allowed_company_locations";
$t_users_professional_allowed_departments		= $mysqlPrefixSav . "users_professional_allowed_departments";
$t_users_professional_allowed_positions			= $mysqlPrefixSav . "users_professional_allowed_positions";
$t_users_professional_allowed_districts			= $mysqlPrefixSav . "users_professional_allowed_districts";

if($action == ""){
	echo"
	<h1>Professional allowed companies</h1>


	<!-- Feedback -->
	";
	if($ft != "" && $fm != ""){
		$fm = ucfirst($fm);
		$fm = str_replace("_", " ", $fm);
		echo"<div class=\"$ft\"><p>$fm</p></div>";
	}
	echo"
	<!-- //Feedback -->


	<!-- Navigation -->
		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=new&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn\">New</a>
		</p>
	<!-- //Navigation -->

	<!-- Rows -->
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span>ID</span>
		   </th>
		   <th scope=\"col\">
			<span>Title</span>
		   </th>
		   <th scope=\"col\">
			<span>Actions</span>
		   </th>
		  </tr>
		</thead>
		<tbody>


	";
	$query = "SELECT allowed_company_id, allowed_company_title, allowed_company_title_clean FROM $t_users_professional_allowed_companies ORDER BY allowed_company_title ASC";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_row_id, $get_title,  $get_title_clean) = $row;

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
			<span>$get_row_id</span>
		  </td>
		  <td class=\"$style\">
			<span>$get_title</span>
		  </td>
		  <td class=\"$style\">
			<span>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit&amp;row_id=$get_row_id&amp;editor_language=$editor_language&amp;l=$l\">Edit</a>
			| 
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete&amp;row_id=$get_row_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
			</span>
		  </td>
		 </tr>
		";

	}
	echo"
	
		 </tbody>
		</table>
	<!--// Rows -->
	";
}
elseif($action == "new"){
	if($process == "1"){
		$inp_title = $_POST['inp_title'];
		$inp_title = output_html($inp_title);
		$inp_title_mysql = quote_smart($link, $inp_title);

		
		$inp_title_clean = clean($inp_title);
		$inp_title_clean_mysql = quote_smart($link, $inp_title_clean);

		mysqli_query($link, "INSERT INTO $t_users_professional_allowed_companies 
		(allowed_company_id, allowed_company_title, allowed_company_title_clean) 
		VALUES 
		(NULL, $inp_title_mysql, $inp_title_clean_mysql)")
		or die(mysqli_error($link));

		$url = "index.php?open=$open&page=$page&action=new&editor_language=$editor_language&l=$l&ft=success&fm=saved";
		header("Location: $url");
		exit;
	}
	echo"
	<h1>New</h1>

	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=$open&amp;page=menu&amp;action=new&amp;editor_language=$editor_language&amp;l=$l\">Users</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Professional allowed companies</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=new&amp;editor_language=$editor_language&amp;l=$l\">New</a>
		</p>
	<!-- //Where am I? -->
	
	<!-- Feedback -->
		";
		if($ft != "" && $fm != ""){
			$fm = ucfirst($fm);
			$fm = str_replace("_", " ", $fm);
			echo"<div class=\"$ft\"><p>$fm</p></div>";
		}
		echo"
	<!-- //Feedback -->

	<!-- New form -->
		<form method=\"POST\" action=\"index.php?open=$open&amp;page=$page&amp;action=new&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

		<!-- Focus -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_title\"]').focus();
			});
			</script>
		<!-- //Focus -->

		<p>
		Title:<br />
		<input type=\"text\" name=\"inp_title\" size=\"25\" style=\"width: 69%;\" />
		<input type=\"submit\" value=\"Create\" class=\"btn_default\" />
		</p>

		</form>
	<!-- //New form -->
	";
}
elseif($action == "edit"){
	$row_id_mysql = quote_smart($link, $row_id);
	$query = "SELECT allowed_company_id, allowed_company_title, allowed_company_title_clean FROM $t_users_professional_allowed_companies WHERE allowed_company_id=$row_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_row_id, $get_current_title, $get_current_title_clean) = $row;
	if($get_current_row_id == ""){
		echo"404";
	}
	else{
		if($process == "1"){
			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

		
			$inp_title_clean = clean($inp_title);
			$inp_title_clean_mysql = quote_smart($link, $inp_title_clean);



			$result = mysqli_query($link, "UPDATE $t_users_professional_allowed_companies SET 
					allowed_company_title=$inp_title_mysql,
					 allowed_company_title_clean=$inp_title_clean_mysql 
					WHERE allowed_company_id=$get_current_row_id") or die(mysqli_error($link));


			$url = "index.php?open=$open&page=$page&editor_language=$editor_language&l=$l&ft=success&fm=saved#row_id$get_current_row_id";
			header("Location: $url");
			exit;
		}
		echo"
		<h1>Edit</h1>

		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=menu&amp;action=new&amp;editor_language=$editor_language&amp;l=$l\">Users</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Professional allowed companies</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;row_id=$row_id&amp;editor_language=$editor_language&amp;l=$l\">Edit</a>
			</p>
		<!-- //Where am I? -->
	
		<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				$fm = ucfirst($fm);
				$fm = str_replace("_", " ", $fm);
				echo"<div class=\"$ft\"><p>$fm</p></div>";
			}
			echo"
		<!-- //Feedback -->

		<!-- Edit form -->
			<form method=\"POST\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;row_id=$row_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

			<!-- Focus -->
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_title\"]').focus();
				});
				</script>
			<!-- //Focus -->

			<p>
			Title:<br />
			<input type=\"text\" name=\"inp_title\" value=\"$get_current_title\" size=\"25\" style=\"width: 69%;\" />
			<input type=\"submit\" value=\"Save\" class=\"btn_default\" />
			</p>

			</form>
		<!-- //Edit form -->
		";
	} // row found
} // edit
elseif($action == "delete"){
	$row_id_mysql = quote_smart($link, $row_id);
	$query = "SELECT allowed_company_id, allowed_company_title, allowed_company_title_clean FROM $t_users_professional_allowed_companies WHERE allowed_company_id=$row_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_row_id, $get_current_title, $get_current_title_clean) = $row;
	if($get_current_row_id == ""){
		echo"404";
	}
	else{
		if($process == "1"){

			$result = mysqli_query($link, "DELETE FROM $t_users_professional_allowed_companies WHERE allowed_company_id=$get_current_row_id") or die(mysqli_error($link));


			$url = "index.php?open=$open&page=$page&editor_language=$editor_language&l=$l&ft=success&fm=deleted#row_id$get_current_row_id";
			header("Location: $url");
			exit;
		}
		echo"
		<h1>Edit</h1>

		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=menu&amp;action=new&amp;editor_language=$editor_language&amp;l=$l\">Users</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Professional allowed companies</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;row_id=$row_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
			</p>
		<!-- //Where am I? -->
	
		<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				$fm = ucfirst($fm);
				$fm = str_replace("_", " ", $fm);
				echo"<div class=\"$ft\"><p>$fm</p></div>";
			}
			echo"
		<!-- //Feedback -->

		<!-- Delete form -->
			<p>
			Are you sure you want to delete?
			</p>

			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;row_id=$row_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_default\">Confirm</a>
			</p>

		<!-- //Delete form -->
		";
	} // row found
} // delete
?>