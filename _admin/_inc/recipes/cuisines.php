<?php
/**
*
* File: _admin/_inc/recipes/cuisines.php
* Version 1.0
* Date 13:41 04.11.2017
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
$t_recipes 	 			= $mysqlPrefixSav . "recipes";
$t_recipes_ingredients			= $mysqlPrefixSav . "recipes_ingredients";
$t_recipes_groups			= $mysqlPrefixSav . "recipes_groups";
$t_recipes_items			= $mysqlPrefixSav . "recipes_items";
$t_recipes_numbers			= $mysqlPrefixSav . "recipes_numbers";
$t_recipes_rating			= $mysqlPrefixSav . "recipes_rating";
$t_recipes_cuisines			= $mysqlPrefixSav . "recipes_cuisines";
$t_recipes_cuisines_translations	= $mysqlPrefixSav . "recipes_cuisines_translations";
$t_recipes_seasons			= $mysqlPrefixSav . "recipes_seasons";
$t_recipes_seasons_translations		= $mysqlPrefixSav . "recipes_seasons_translations";
$t_recipes_occasions			= $mysqlPrefixSav . "recipes_occasions";
$t_recipes_occasions_translations	= $mysqlPrefixSav . "recipes_occasions_translations";
$t_recipes_categories			= $mysqlPrefixSav . "recipes_categories";
$t_recipes_categories_translations	= $mysqlPrefixSav . "recipes_categories_translations";
$t_recipes_weekly_special		= $mysqlPrefixSav . "recipes_weekly_special";
$t_recipes_of_the_day			= $mysqlPrefixSav . "recipes_of_the_day";

/*- Variables ------------------------------------------------------------------------ */
if(isset($_GET['cuisine_id'])) {
	$cuisine_id= $_GET['cuisine_id'];
	$cuisine_id = strip_tags(stripslashes($cuisine_id));
}
else{
	$cuisine_id = "";
}


/*- Script start --------------------------------------------------------------------- */
if($action == ""){
	echo"
	<h1>$l_cuisines</h1>


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

	<!-- Add -->
		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=add&amp;editor_language=$editor_language\" class=\"btn\">$l_add</a>
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=translations&amp;editor_language=$editor_language\" class=\"btn\">$l_translations</a>
		</p>
	<!-- //Add -->


	<!-- List all cuisines -->
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span>$l_id</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_name</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_actions</span>
		   </th>
		  </tr>
		</thead>
		<tbody>
		";
	


		$editor_language_mysql = quote_smart($link, $editor_language);
		$query = "SELECT cuisine_id, cuisine_name FROM $t_recipes_cuisines";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_cuisine_id, $get_cuisine_name) = $row;

			if(isset($style) && $style == ""){
				$style = "odd";
			}
			else{
				$style = "";
			}

			echo"
			<tr>
			  <td class=\"$style\">
				<span>$get_cuisine_id</span>
			  </td>
			  <td class=\"$style\">
				<span>$get_cuisine_name</span>
			  </td>
			  <td class=\"$style\">
				<span>
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit&amp;cuisine_id=$get_cuisine_id&amp;editor_language=$editor_language\">$l_edit</a>
				&middot;
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete&amp;cuisine_id=$get_cuisine_id&amp;editor_language=$editor_language\">$l_delete</a>
				</span>
			 </td>
			</tr>
			";
		}
		echo"
		 </tbody>
		</table>
	<!-- //List all cuisines -->
 	";
} // action == "";
elseif($action == "add"){
	if($process == "1"){
		$inp_name = $_POST['inp_name'];
		$inp_name = output_html($inp_name);
		$inp_name_mysql = quote_smart($link, $inp_name);

		mysqli_query($link, "INSERT INTO $t_recipes_cuisines
		(cuisine_id, cuisine_name) 
		VALUES 
		(NULL, $inp_name_mysql)")
		or die(mysqli_error($link));

		$url = "index.php?open=$open&page=$page&editor_language=$editor_language&l=$l&ft=success&fm=changes_saved";
		header("Location: $url");
		exit;
	}
	echo"
	<h1>$l_add</h1>

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


	<!-- Focus -->
		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_name\"]').focus();
		});
		</script>
	<!-- //Focus -->


	<!-- Form -->
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">


		<p>$l_name:<br />
		<input type=\"text\" name=\"inp_name\" value=\"\" size=\"40\" />
		</p>

		<p><input type=\"submit\" value=\"$l_save\" class=\"btn\" /></p>
		</form>
	<!-- //Form -->
	

	";
} // add
elseif($action == "edit"){
	// Find
	$cuisine_id_mysql = quote_smart($link, $cuisine_id);
	$query = "SELECT cuisine_id, cuisine_name FROM $t_recipes_cuisines WHERE cuisine_id=$cuisine_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_cuisine_id, $get_cuisine_name) = $row;
	
	if($get_cuisine_id == ""){
		echo"
		<h1>Server error 404</h1>

		<p>Not found in database.</p>

		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language\">Back</a>
		</p>
		";
	} // not found
	else{
		if($process == 1){
			$inp_name = $_POST['inp_name'];
			$inp_name = output_html($inp_name);
			$inp_name_mysql = quote_smart($link, $inp_name);

			$result = mysqli_query($link, "UPDATE $t_recipes_cuisines SET cuisine_name=$inp_name_mysql WHERE cuisine_id=$cuisine_id_mysql") or die(mysqli_error($link));


			$url = "index.php?open=$open&page=$page&editor_language=$editor_language&l=$l&ft=success&fm=changes_saved";
			header("Location: $url");
			exit;

		}


		echo"
		<h1>$l_edit</h1>


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
	

		<!-- Focus -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_name\"]').focus();
			});
			</script>
		<!-- //Focus -->

	
		<!-- Form -->
			<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;cuisine_id=$cuisine_id&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">


			<p>$l_name:<br />
			<input type=\"text\" name=\"inp_name\" value=\"$get_cuisine_name\" size=\"40\" />
			</p>

			<p><input type=\"submit\" value=\"$l_save\" class=\"btn\" /></p>
			</form>
		<!-- //Form -->
		";
	} // found
	
} // edit
elseif($action == "delete"){
	// Find
	$cuisine_id_mysql = quote_smart($link, $cuisine_id);
	$query = "SELECT cuisine_id, cuisine_name FROM $t_recipes_cuisines WHERE cuisine_id=$cuisine_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_cuisine_id, $get_cuisine_name) = $row;
	
	if($get_cuisine_id == ""){
		echo"
		<h1>Server error 404</h1>

		<p>Not found in database.</p>

		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language\">Back</a>
		</p>
		";
	} // not found
	else{
		if($process == 1){
			$result = mysqli_query($link, "DELETE FROM $t_recipes_cuisines WHERE cuisine_id=$cuisine_id_mysql") or die(mysqli_error($link));


			$url = "index.php?open=$open&page=$page&editor_language=$editor_language&l=$l&ft=success&fm=changes_saved";
			header("Location: $url");
			exit;

		}


		echo"
		<h1>$l_delete</h1>


		<p>$l_are_you_sure_you_want_to_delete $l_this_action_cannot_be_undone</p>

		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;cuisine_id=$cuisine_id&amp;editor_language=$editor_language&amp;process=1\">$l_delete</a>
		&middot;
		<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;process=1\">$l_cancel</a>
		</p>
		";
	} // found
	
} // delete
elseif($action == "translations"){
	if($process == 1){
		$editor_language_mysql = quote_smart($link, $editor_language);
		$query = "SELECT cuisine_id, cuisine_name FROM $t_recipes_cuisines";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_cuisine_id, $get_cuisine_name) = $row;

			$inp_cuisine_translation_value = $_POST["inp_cuisine_translation_value_$get_cuisine_id"];
			$inp_cuisine_translation_value = output_html($inp_cuisine_translation_value);
			$inp_cuisine_translation_value_mysql = quote_smart($link, $inp_cuisine_translation_value);

			// Update
			$result_update = mysqli_query($link, "UPDATE $t_recipes_cuisines_translations SET cuisine_translation_value=$inp_cuisine_translation_value_mysql WHERE cuisine_id=$get_cuisine_id AND cuisine_translation_language=$editor_language_mysql") or die(mysqli_error($link));
		}

		$url = "index.php?open=$open&page=$page&action=$action&editor_language=$editor_language&l=$l&ft=success&fm=changes_saved";
		header("Location: $url");
		exit;

	}


	echo"
	<h1>$l_translations</h1>


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


			$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_flag, language_active_default FROM $t_languages_active";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_flag, $get_language_active_default) = $row;

				$flag_path 	= "_design/gfx/flags/16x16/$get_language_active_flag" . "_16x16.png";

				// No language selected?
				if($editor_language == ""){
						$editor_language = "$get_language_active_iso_two";
				}
				
				
				echo"	<option value=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;editor_language=$get_language_active_iso_two&amp;l=$l\" style=\"background: url('$flag_path') no-repeat;padding-left: 20px;\"";if($editor_language == "$get_language_active_iso_two"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";
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
			<span>$l_name</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_translation</span>
		   </th>
		  </tr>
		</thead>
		<tbody>
		";
	


		$editor_language_mysql = quote_smart($link, $editor_language);
		$query = "SELECT cuisine_id, cuisine_name FROM $t_recipes_cuisines";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_cuisine_id, $get_cuisine_name) = $row;

			// Style
			if(isset($style) && $style == ""){
				$style = "odd";
			}
			else{
				$style = "";
			}	

			// Translation
			$query_translation = "SELECT cuisine_translation_id, cuisine_translation_value FROM $t_recipes_cuisines_translations WHERE cuisine_id=$get_cuisine_id AND cuisine_translation_language=$editor_language_mysql";
			$result_translation = mysqli_query($link, $query_translation);
			$row_translation = mysqli_fetch_row($result_translation);
			list($get_cuisine_translation_id, $get_cuisine_translation_value) = $row_translation;
			if($get_cuisine_translation_id == ""){
				// It doesnt exists, create it.

				mysqli_query($link, "INSERT INTO $t_recipes_cuisines_translations
				(cuisine_translation_id, cuisine_id, cuisine_translation_language, cuisine_translation_value) 
				VALUES 
				(NULL, '$get_cuisine_id', $editor_language_mysql, '')")
				or die(mysqli_error($link));

				echo"<div class=\"info\"><span>L O A D I N G</span></div>";
				echo"
 				<meta http-equiv=\"refresh\" content=\"0;URL='index.php?open=$open&amp;page=$page&amp;action=$action&amp;editor_language=$editor_language&amp;l=$l'\" />
				";

				
			}

			echo"
			<tr>
			  <td class=\"$style\">
				<span>$get_cuisine_name</span>
			  </td>
			  <td class=\"$style\">
				<span><input type=\"text\" name=\"inp_cuisine_translation_value_$get_cuisine_id\" value=\"$get_cuisine_translation_value\" size=\"40\" /></span>
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

	<!-- //List all categories -->

	<!-- Back -->
		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn\">$l_back</a>
		</p>
	<!-- //Back -->
 	";
} // action == "";
?>