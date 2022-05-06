<?php
/**
*
* File: _admin/_inc/diet/measurements.php
* Version 00.28 20.03.2017
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

/*- Variables -------------------------------------------------------------------------- */
if(isset($_GET['editor_language'])){
	$editor_language = $_GET['editor_language'];
	$editor_language = strip_tags(stripslashes($editor_language));
}
else{
	$editor_language = "en";
}
$editor_language_mysql = quote_smart($link, $editor_language);

if(isset($_GET['measurement_id'])){
	$measurement_id = $_GET['measurement_id'];
	$measurement_id = strip_tags(stripslashes($measurement_id));
}
else{
	$measurement_id = "";
}
$measurement_id_mysql = quote_smart($link, $measurement_id);

if($action == ""){
	echo"
	<h1>Measurements</h1>


	<!-- Language -->
		
		<p><a href=\"index.php?open=$open&amp;page=$page&amp;action=new_measurement&amp;editor_language=$editor_language\">New measurement</a>
		|
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=translations&amp;editor_language=$editor_language\">Translations</a>
		|
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=sqlite_code&amp;editor_language=$editor_language\">SQLite code</a>
		|
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=strings&amp;editor_language=$editor_language\">Strings</a></p>
		
	<!-- //Language -->

	<!-- Measurement -->
		
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span><b>Name</b></span>
		   </td>
		   <th scope=\"col\">
			<span><b>Actions</b></span>
		  </td>
		  </tr>
		 </thead>";

		// Get all categories
		$query = "SELECT measurement_id, measurement_name, measurement_last_updated FROM $t_food_measurements ORDER BY measurement_name ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_measurement_id, $get_measurement_name, $get_measurement_last_updated) = $row;
				
				
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
				<span>$get_measurement_name</span>
			  </td>
			  <td class=\"$style\">
				<span>
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_measurement&amp;measurement_id=$get_measurement_id&amp;editor_language=$editor_language\">Edit</a>
				|
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_measurement&amp;measurement_id=$get_measurement_id&amp;editor_language=$editor_language\">Delete</a>
				</span>
			  </td>
			 </tr>";

			}

			echo"
			</table>
		  </td>
		 </tr>
		</table>
	<!-- //Measurments -->
	";
}
elseif($action == "edit_measurement" && isset($_GET['measurement_id'])){
	

	// Select measurement
	$query = "SELECT measurement_id, measurement_name, measurement_last_updated FROM $t_food_measurements WHERE measurement_id=$measurement_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_measurement_id, $get_measurement_name, $get_measurement_last_updated) = $row;

	if($get_measurement_id == ""){
		echo"
		<h1>Server error 404</h1>

		<p>Measurement not found.</p>

		<p><a href=\"index.php?open=$open&amp;page=$page&amp;language=$language\">Measurements</a></p>
		";
	}
	else{
		if($process == "1"){
			$inp_measurement_name = $_POST['inp_measurement_name'];
			$inp_measurement_name = output_html($inp_measurement_name);
			$inp_measurement_name = strtolower($inp_measurement_name);
			$inp_measurement_name_mysql = quote_smart($link, $inp_measurement_name);


			// Update
			$result = mysqli_query($link, "UPDATE $t_food_measurements SET measurement_name=$inp_measurement_name_mysql WHERE measurement_id=$measurement_id_mysql");

			// Send success
			$url = "index.php?open=$open&page=measurements&action=edit_measurement&measurement_id=$get_measurement_id&ft=success&fm=changes_saved&editor_language=$editor_language";
			header("Location: $url");
			exit;
		}


		echo"
		<h1>$get_measurement_name</h1>

		<!-- Where am I ? -->
			<p>
			<a href=\"index.php?open=$open&amp;page=measurements&amp;editor_language=$editor_language\">Measurements</a>
			&gt;
			$get_measurement_name
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
					\$('[name=\"inp_measurement_name\"]').focus();
				});
			</script>
			<!-- //Focus -->

			<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;measurement_id=$measurement_id&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">


			<p><b>Name:</b><br />
			<input type=\"text\" name=\"inp_measurement_name\" value=\"$get_measurement_name\" size=\"40\" />
			</p>
			<p>
			<input type=\"submit\" value=\"Save\" />
			</p>

			</form>
		<!-- //Edit category form -->
		
		";
	}
} // edit_measurement
elseif($action == "delete_measurement" && isset($_GET['measurement_id'])){
	
	// Select measurement
	$query = "SELECT measurement_id, measurement_name, measurement_last_updated FROM $t_food_measurements WHERE measurement_id=$measurement_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_measurement_id, $get_measurement_name, $get_measurement_last_updated) = $row;

	if($get_measurement_id == ""){
		echo"
		<h1>Server error 404</h1>

		<p>Measurement not found.</p>

		<p><a href=\"index.php?open=$open&amp;page=$page&amp;language=$language\">Measurements</a></p>
		";
	}
	else{
		if($process == "1"){
			
			// Delete
			$result = mysqli_query($link, "DELETE FROM $t_food_measurements WHERE measurement_id=$measurement_id_mysql");

			// Send success
			$url = "index.php?open=$open&page=measurements&ft=success&fm=measurement_deleted&editor_language=$editor_language";
			header("Location: $url");
			exit;
		}


		echo"
		<h1>$get_measurement_name</h1>

		<!-- Where am I ? -->
			<p>
			<a href=\"index.php?open=$open&amp;page=measurements&amp;editor_language=$editor_language\">Measurements</a>
			&gt;
			$get_measurement_name
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

		<!-- Delete measurement form -->
			
			<p>
			Are you sure you want to delete the measurement?
			</p>

			<p>
			<a href=\"index.php?open=$open&amp;page=measurements&amp;editor_language=$editor_language\">Cancel</a>
			|
			<a href=\"index.php?open=$open&amp;page=measurements&amp;action=delete_measurement&amp;measurement_id=$measurement_id&amp;editor_language=$editor_language&amp;process=1\">Delete</a>
			</p>
		<!-- //Delete measurement form -->
		
		";
	}
} // delete_category
elseif($action == "new_measurement"){
	if($process == "1"){
		$inp_measurement_name = $_POST['inp_measurement_name'];
		$inp_measurement_name = output_html($inp_measurement_name);
		$inp_measurement_name = strtolower($inp_measurement_name);
		$inp_measurement_name_mysql = quote_smart($link, $inp_measurement_name);
		if(empty($inp_measurement_name)){
			echo"No measurement name";die;
		}

		// Check for duplicates 
		$query = "SELECT measurement_id FROM $t_food_measurements WHERE measurement_name=$inp_measurement_name_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_check_measurement_id) = $row;
		if($get_check_measurement_id != ""){
			$url = "index.php?open=$open&page=$page&action=$action&editor_language=$editor_language&ft=error&fm=measurement_already_exists";
			header("Location: $url");
			exit;
		}


		$inp_date = date("Y-m-d H:i:s");

		// Insert
		mysqli_query($link, "INSERT INTO $t_food_measurements
		(measurement_id, measurement_name, measurement_last_updated) 
		VALUES 
		(NULL, $inp_measurement_name_mysql, '$inp_date')")
		or die(mysqli_error($link));

		// Send success
		$url = "index.php?open=$open&page=$page&editor_language=$editor_language&ft=success&fm=measurement_created";
		header("Location: $url");
		exit;
	}

	echo"
	<h1>New measurement</h1>

	<!-- Where am I ? -->
		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language\">Measurements</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=new_category&amp;editor_language=$editor_language\">New measurement</a>
		</p>
	<!-- //Where am I ? -->

	<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "Changes saved";
			}
			else{
				$fm = ucfirst($fm);
				$fm = str_replace("_", " ", $fm);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
	echo"
	<!-- //Feedback -->

		
	<!-- New measurement form -->
			
		<!-- Focus -->
		<script>
			\$(document).ready(function(){
				\$('[name=\"inp_measurement_name\"]').focus();
			});
		</script>
		<!-- //Focus -->

		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=new_measurement&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

		<p><b>Name:</b><br />
		<span class=\"grey\">All letters will be converted to lower caps.</span><br />
		<input type=\"text\" name=\"inp_measurement_name\" value=\"\" size=\"40\" />
		</p>


		<p>
		<input type=\"submit\" value=\"Save\" />
		</p>

		</form>
	<!-- //New measurement form -->
		
	";
} // new_measurement
elseif($action == "sqlite_code"){
	echo"
	<h1>SQLite code</h1>

	<p>
	String categoryName = &quot;&quot;;<br /><br />
	";


	// Get all categories
	$query = "SELECT measurement_id, measurement_name, measurement_last_updated FROM $t_food_measurements";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_measurement_id, $get_measurement_name, $get_measurement_last_updated) = $row;


		echo"
		categoryName = context.getResources().getString(R.string.$get_measurement_name);<br />
		setupInsertToCategories(&quot;NULL, '&quot; + categoryName + &quot;', '0', '', NULL&quot;);<br />
		";

		echo"

		<br />
		";
	}

}

elseif($action == "strings"){
	echo"
	<h1>Strings</h1>

	<p>
	";


	// Get all categories
	$query = "SELECT measurement_id, measurement_name, measurement_last_updated FROM $t_food_measurements";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_measurement_id, $get_measurement_name, $get_measurement_last_updated) = $row;

		$name_lowercase = strtolower($get_measurement_name);
		$name_lowercase = str_replace(" ", "_", $name_lowercase);
		$name_lowercase = str_replace(",", "", $name_lowercase);

		echo"
		&lt;string name=&quot;$name_lowercase&quot;&gt;$get_measurement_name&lt;/string&gt;<br />
		";

		echo"

		<br />
		";
	}

}
elseif($action == "translations"){
	if($process == 1){
		$editor_language_mysql = quote_smart($link, $editor_language);
		$query = "SELECT measurement_id, measurement_name FROM $t_food_measurements";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_measurement_id, $get_measurement_name) = $row;

			$inp_measurement_translation_value = $_POST["inp_measurement_translation_value_$get_measurement_id"];
			$inp_measurement_translation_value = output_html($inp_measurement_translation_value);
			$inp_measurement_translation_value_mysql = quote_smart($link, $inp_measurement_translation_value);

			// Update
			$result_update = mysqli_query($link, "UPDATE $t_food_measurements_translations SET measurement_translation_value=$inp_measurement_translation_value_mysql WHERE measurement_id=$get_measurement_id AND measurement_translation_language=$editor_language_mysql") or die(mysqli_error($link));
		}

		$url = "index.php?open=$open&page=$page&action=$action&editor_language=$editor_language&l=$l&ft=success&fm=changes_saved";
		header("Location: $url");
		exit;

	}


	echo"
	<h1>Translations</h1>


	<!-- Feedback -->
	";
	if($ft != ""){
		if($fm == "changes_saved"){
			$fm = "$l_changes_saved";
		}
		else{
			$fm = ucfirst($ft);
		}
		echo"<div class=\"$ft\"><span>$fm</span></div>";
	}
	echo"	
	<!-- //Feedback -->

	<!-- Select language -->

		<script>
		\$(function(){
			// bind change event to select
			\$('#inp_l').on('change', function () {
				var url = \$(this).val(); // get selected value
				if (url) { // require a URL
 					window.location = url; // redirect
				}
				return false;
			});
		});
		</script>

		<form method=\"get\" enctype=\"multipart/form-data\">
		<p>
		$l_language:
		<select id=\"inp_l\">
			<option value=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">$l_editor_language</option>
			<option value=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">-</option>\n";


			$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;


				// No language selected?
				if($editor_language == ""){
						$editor_language = "$get_language_active_iso_two";
				}
				
				
				echo"	<option value=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;editor_language=$get_language_active_iso_two&amp;l=$l\"";if($editor_language == "$get_language_active_iso_two"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";
			}
		echo"
		</select>
		</p>
		</form>
	<!-- //Select language -->

	

	<!-- Translate form -->
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
	

		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span>Name</span>
		   </th>
		   <th scope=\"col\">
			<span>Translation</span>
		   </th>
		  </tr>
		</thead>
		<tbody>
		";
	


		$editor_language_mysql = quote_smart($link, $editor_language);
		$query = "SELECT measurement_id, measurement_name FROM $t_food_measurements";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_measurement_id, $get_measurement_name) = $row;

			// Style
			if(isset($style) && $style == ""){
				$style = "odd";
			}
			else{
				$style = "";
			}	

			// Translation
			$query_translation = "SELECT measurement_translation_id, measurement_translation_language, measurement_translation_value FROM $t_food_measurements_translations WHERE measurement_id=$get_measurement_id AND measurement_translation_language=$editor_language_mysql";
			$result_translation = mysqli_query($link, $query_translation);
			$row_translation = mysqli_fetch_row($result_translation);
			list($get_measurement_translation_id, $get_measurement_translation_language, $get_measurement_translation_value) = $row_translation;
			if($get_measurement_translation_id == ""){
				// It doesnt exists, create it.

				mysqli_query($link, "INSERT INTO $t_food_measurements_translations
				(measurement_translation_id, measurement_id, measurement_translation_language, measurement_translation_value) 
				VALUES 
				(NULL, '$get_measurement_id', $editor_language_mysql, '$get_measurement_name')")
				or die(mysqli_error($link));

				echo"<div class=\"info\"><span>L O A D I N G</span></div>";
				echo"
 				<meta http-equiv=\"refresh\" content=\"0;URL='index.php?open=$open&amp;page=$page&amp;action=$action&amp;editor_language=$editor_language&amp;l=$l'\" />
				";

				
			}

			echo"
			<tr>
			  <td class=\"$style\">
				<span>$get_measurement_name</span>
			  </td>
			  <td class=\"$style\">
				<span><input type=\"text\" name=\"inp_measurement_translation_value_$get_measurement_id\" value=\"$get_measurement_translation_value\" size=\"40\" /></span>
			  </td>
			</tr>
			";
		}
		echo"
		 </tbody>
		</table>

		<p>
		<input type=\"submit\" value=\"$l_save_changes\" class=\"btn\" />
		</p>
		</form>

	<!-- //List all measurements -->

	<!-- Back -->
		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn\">$l_back</a>
		</p>
	<!-- //Back -->
 	";
} // action == "";
?>