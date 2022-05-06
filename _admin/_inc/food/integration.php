<?php
/**
*
* File: _admin/_inc/stram/food.php
* Version 23:07 09.07.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}
/*- Tables ---------------------------------------------------------------------------- */
$t_food_categories		  = $mysqlPrefixSav . "food_categories";
$t_food_categories_translations	  = $mysqlPrefixSav . "food_categories_translations";
$t_food_index			  = $mysqlPrefixSav . "food_index";
$t_food_index_stores		  = $mysqlPrefixSav . "food_index_stores";
$t_food_index_ads		  = $mysqlPrefixSav . "food_index_ads";
$t_food_index_tags		  = $mysqlPrefixSav . "food_index_tags";
$t_food_index_prices		  = $mysqlPrefixSav . "food_index_prices";
$t_food_index_contents		  = $mysqlPrefixSav . "food_index_contents";
$t_food_stores		  	  = $mysqlPrefixSav . "food_stores";
$t_food_prices_currencies	  = $mysqlPrefixSav . "food_prices_currencies";
$t_food_favorites 		  = $mysqlPrefixSav . "food_favorites";
$t_food_measurements	 	  = $mysqlPrefixSav . "food_measurements";
$t_food_measurements_translations = $mysqlPrefixSav . "food_measurements_translations";
$t_food_integration	 	  = $mysqlPrefixSav . "food_integration";



/*- Variables -------------------------------------------------------------------------- */
if(isset($_GET['language'])){
	$language = $_GET['language'];
	$language = strip_tags(stripslashes($language));
}
else{
	$language = "en";
}
if(isset($_GET['integration_id'])){
	$integration_id= $_GET['integration_id'];
	$integration_id = strip_tags(stripslashes($integration_id));
}
else{
	$integration_id = "";
}

/*- Settings ---------------------------------------------------------------------------- */
if($action == ""){
	echo"
	<h1>Integration</h1>

	<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "Changes saved";
			}
			else{
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
	echo"
	<!-- //Feedback -->

	<p>
	<a href=\"index.php?open=$open&amp;page=$page&amp;action=new&amp;editor_language=$editor_language\"><img src=\"_design/gfx/icons/16x16/x-office-document_new.png\" alt=\"x-office-document_new.png\" /></a>
	<a href=\"index.php?open=$open&amp;page=$page&amp;action=new&amp;editor_language=$editor_language\">New</a>
	</p>

	<!-- Integrations -->
		<div class=\"vertical\">
			<ul>";

			// food_integration
			$query = "SELECT integration_id, integration_title FROM $t_food_integration";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_integration_id, $get_integration_title ) = $row;
				
				echo"<li><a href=\"index.php?open=$open&amp;page=$page&amp;action=open_integration&amp;integration_id=$get_integration_id&amp;editor_language=$editor_language\">$get_integration_title</a></li>\n";
			}
			echo"
			</ul>
		</div>
	<!-- //Integrations -->
			
			";

} // action == ""
elseif($action == "new"){
	
	if($process == "1"){
		$inp_title = $_POST['inp_title'];
		$inp_title = output_html($inp_title);
		$inp_title_mysql = quote_smart($link, $inp_title);

		$inp_url = $_POST['inp_url'];
		$inp_url = output_html($inp_url);
		$inp_url_mysql = quote_smart($link, $inp_url);

		$inp_password = $_POST['inp_password'];
		$inp_password = output_html($inp_password);
		$inp_password_mysql = quote_smart($link, $inp_password);


		// Create
		mysqli_query($link, "INSERT INTO $t_food_integration
		(integration_id, integration_title, integration_url, integration_password, integration_last_downloaded, integration_last_on_server, integration_last_checked_week, integration_last_checked_datetime) 
		VALUES 
		(NULL, $inp_title_mysql, $inp_url_mysql, $inp_password_mysql, '0', '0', '1', '2018-02-08 20:00:00')
		")
		or die(mysqli_error($link));


		$integration_id_mysql = quote_smart($link, $integration_id);
		$query = "SELECT integration_id, integration_title, integration_url, integration_password, integration_last_downloaded, integration_last_on_server, integration_last_checked_week, integration_last_checked_datetime FROM $t_food_integration WHERE integration_title=$inp_title_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_integration_id, $get_integration_title, $get_integration_url, $get_integration_password, $get_integration_last_downloaded, $get_integration_last_on_server, $get_integration_last_checked_week, $get_integration_last_checked_datetime) = $row;


		// Send success
		$url = "index.php?open=$open&page=$page&action=open_integration&integration_id=$get_integration_id&ft=success&fm=changes_saved&editor_language=$editor_language";
		header("Location: $url");
		exit;
	}
	echo"
	<h1>New</h1>

	<!-- Where am I ? -->
		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language\">Integrations</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=new&amp;editor_language=$editor_language\">New</a>
		</p>
	<!-- //Where am I ? -->



	<!-- Feedback -->
			";
			if($ft != ""){
				if($fm == "changes_saved"){
					$fm = "Changes saved";
				}
				else{
					$fm = ucfirst($ft);
				}
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
		echo"
	<!-- //Feedback -->

	<!-- New form -->
			
		<!-- Focus -->
		<script>
			\$(document).ready(function(){
				\$('[name=\"inp_title\"]').focus();
			});
		</script>
		<!-- //Focus -->

		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=new&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

		<p><b>Title:</b><br />
		<input type=\"text\" name=\"inp_title\" value=\"\" size=\"25\" />
		</p>

		<p><b>URL:</b><br />
		<input type=\"text\" name=\"inp_url\" value=\"\" size=\"25\" />
		</p>

		<p><b>Password:</b><br />
		<input type=\"text\" name=\"inp_password\" value=\"\" size=\"25\" />
		</p>

		<p>
		<input type=\"submit\" value=\"Create\" class=\"btn\" />
		</p>

		</form>
	<!-- //New form -->


	";
} // action == "new"
elseif($action == "open_integration"){
	
	// Select integration
	$integration_id_mysql = quote_smart($link, $integration_id);
	$query = "SELECT integration_id, integration_title, integration_url, integration_password, integration_last_downloaded, integration_last_on_server, integration_last_checked_week, integration_last_checked_datetime FROM $t_food_integration WHERE integration_id=$integration_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_integration_id, $get_integration_title, $get_integration_url, $get_integration_password, $get_integration_last_downloaded, $get_integration_last_on_server, $get_integration_last_checked_week, $get_integration_last_checked_datetime) = $row;

	if($get_integration_id == ""){
		echo"
		<h1>Server error 404</h1>

		<p>Not found.</p>

		<p><a href=\"index.php?open=$open&amp;page=$page&amp;language=$language\">Home</a></p>
		";
	}
	else{
		if($process == "1"){
			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			$inp_url = $_POST['inp_url'];
			$inp_url = output_html($inp_url);
			$inp_url_mysql = quote_smart($link, $inp_url);

			$inp_password = $_POST['inp_password'];
			$inp_password = output_html($inp_password);
			$inp_password_mysql = quote_smart($link, $inp_password);


			// Update
			$result = mysqli_query($link, "UPDATE $t_food_integration SET 
							integration_title=$inp_title_mysql, 
							integration_url=$inp_url_mysql, 
							integration_password=$inp_password_mysql WHERE integration_id=$get_integration_id");

			// Send success
			$url = "index.php?open=$open&page=$page&action=$action&integration_id=$get_integration_id&ft=success&fm=changes_saved&editor_language=$editor_language";
			header("Location: $url");
			exit;
		}
		echo"
		<h1>$get_integration_title</h1>

		<!-- Where am I ? -->
			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language\">Integrations</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_integration&amp;integration_id=$get_integration_id&amp;editor_language=$editor_language\">$get_integration_title</a>
			</p>
		<!-- //Where am I ? -->



		<!-- Feedback -->
			";
			if($ft != ""){
				if($fm == "changes_saved"){
					$fm = "Changes saved";
				}
				else{
					$fm = ucfirst($ft);
				}
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
		echo"
		<!-- //Feedback -->

		<!-- Edit form -->
			
			<!-- Focus -->
			<script>
				\$(document).ready(function(){
					\$('[name=\"inp_title\"]').focus();
				});
			</script>
			<!-- //Focus -->

			<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=open_integration&amp;integration_id=$get_integration_id&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">


			<p><b>Title:</b><br />
			<input type=\"text\" name=\"inp_title\" value=\"$get_integration_title\" size=\"25\" />
			</p>

			<p><b>URL:</b><br />
			<input type=\"text\" name=\"inp_url\" value=\"$get_integration_url\" size=\"25\" />
			</p>

			<p><b>Password:</b><br />
			<input type=\"text\" name=\"inp_password\" value=\"$get_integration_password\" size=\"25\" />
			</p>
			<p>
			<input type=\"submit\" value=\"Save\" class=\"btn\" />
			</p>

			</form>
		<!-- //Edit form -->

		<!-- Action sone -->
			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language\"><img src=\"_design/gfx/icons/16x16/go-previous.png\" alt=\"go-previous.png\" /></a>
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language\">Go back</a>
			&nbsp;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_integration&amp;integration_id=$get_integration_id&amp;editor_language=$editor_language\"><img src=\"_design/gfx/icons/16x16/delete.png\" alt=\"delete.png\" /></a>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_integration&amp;integration_id=$get_integration_id&amp;editor_language=$editor_language\">Delete</a>
			</p>
		<!-- //Action sone -->

		";

	} // found
} // action == "open_integration"
elseif($action == "delete_integration"){
	
	// Select integration
	$integration_id_mysql = quote_smart($link, $integration_id);
	$query = "SELECT integration_id, integration_title, integration_url, integration_password, integration_last_downloaded, integration_last_on_server, integration_last_checked_week, integration_last_checked_datetime FROM $t_food_integration WHERE integration_id=$integration_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_integration_id, $get_integration_title, $get_integration_url, $get_integration_password, $get_integration_last_downloaded, $get_integration_last_on_server, $get_integration_last_checked_week, $get_integration_last_checked_datetime) = $row;

	if($get_integration_id == ""){
		echo"
		<h1>Server error 404</h1>

		<p>Not found.</p>

		<p><a href=\"index.php?open=$open&amp;page=$page&amp;language=$language\">Home</a></p>
		";
	}
	else{
		if($process == "1"){
			

			// Delete
			$result = mysqli_query($link, "DELETE FROM $t_food_integration WHERE integration_id=$get_integration_id");

			// Send success
			$url = "index.php?open=$open&page=$page&ft=success&fm=integration_deleted&editor_language=$editor_language";
			header("Location: $url");
			exit;
		}
		echo"
		<h1>$get_integration_title</h1>

		<!-- Where am I ? -->
			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language\">Integrations</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_integration&amp;integration_id=$get_integration_id&amp;editor_language=$editor_language\">$get_integration_title</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_integration&amp;integration_id=$get_integration_id&amp;editor_language=$editor_language\">Delete</a>
			</p>
		<!-- //Where am I ? -->



		<!-- Feedback -->
			";
			if($ft != ""){
				if($fm == "changes_saved"){
					$fm = "Changes saved";
				}
				else{
					$fm = ucfirst($ft);
				}
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
		echo"
		<!-- //Feedback -->

		<!-- Delete form -->
			
			<p>
			Are you sure you want to delete?
			</p>

			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_integration&amp;integration_id=$get_integration_id&amp;editor_language=$editor_language&amp;process=1\" class=\"btn_warning\">Delete</a>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_integration&amp;integration_id=$get_integration_id&amp;editor_language=$editor_language\" class=\"btn\">Go back</a>
			</p>

		<!-- //Delete form -->


		";

	} // found
} // action == "delete_integration"
?>