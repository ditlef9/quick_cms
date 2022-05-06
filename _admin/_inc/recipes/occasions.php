<?php
/**
*
* File: _admin/_inc/recipes/occasions.php
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
if(isset($_GET['occasion_id'])) {
	$occasion_id= $_GET['occasion_id'];
	$occasion_id = strip_tags(stripslashes($occasion_id));
}
else{
	$occasion_id = "";
}


/*- Script start --------------------------------------------------------------------- */
if($action == ""){
	echo"
	<h1>$l_occasions</h1>


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


	<!-- List all categories -->
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
			<span>$l_day</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_month</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_actions</span>
		   </th>
		  </tr>
		</thead>
		<tbody>
		";
	


		$editor_language_mysql = quote_smart($link, $editor_language);
		$query = "SELECT occasion_id, occasion_name, occasion_day, occasion_month FROM $t_recipes_occasions ORDER BY occasion_month, occasion_day ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_occasion_id, $get_occasion_name, $get_occasion_day, $get_occasion_month) = $row;

			if(isset($style) && $style == ""){
				$style = "odd";
			}
			else{
				$style = "";
			}			

			echo"
			<tr>
			  <td class=\"$style\">
				<span>$get_occasion_id</span>
			  </td>
			  <td class=\"$style\">
				<span>$get_occasion_name</span>
			  </td>
			  <td class=\"$style\">
				<span>$get_occasion_day</span>
			  </td>
			  <td class=\"$style\">
				<span>";
				if($get_occasion_month == 1){
					echo $l_january;
				}
				elseif($get_occasion_month == 2){
					echo $l_february;
				}
				elseif($get_occasion_month == 3){
					echo $l_march;
				}
				elseif($get_occasion_month == 4){
					echo $l_april;
				}
				elseif($get_occasion_month == 5){
					echo $l_may;
				}
				elseif($get_occasion_month == 6){
					echo $l_june;
				}
				elseif($get_occasion_month == 7){
					echo $l_juli;
				}
				elseif($get_occasion_month == 8){
					echo $l_august;
				}
				elseif($get_occasion_month == 9){
					echo $l_september;
				}
				elseif($get_occasion_month == 10){
					echo $l_october;
				}
				elseif($get_occasion_month == 11){
					echo $l_november;
				}
				elseif($get_occasion_month == 12){
					echo $l_december;
				}

				echo"</span>
			  </td>
			  <td class=\"$style\">
				<span>
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit&amp;occasion_id=$get_occasion_id&amp;editor_language=$editor_language\">$l_edit</a>
				&middot;
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete&amp;occasion_id=$get_occasion_id&amp;editor_language=$editor_language\">$l_delete</a>
				</span>
			 </td>
			</tr>
			";
		}
		echo"
		 </tbody>
		</table>
	<!-- //List all categories -->
 	";
} // action == "";
elseif($action == "add"){
	if($process == "1"){
		$inp_name = $_POST['inp_name'];
		$inp_name = output_html($inp_name);
		$inp_name_mysql = quote_smart($link, $inp_name);

		$inp_day = $_POST['inp_day'];
		$inp_day = output_html($inp_day);
		$inp_day_mysql = quote_smart($link, $inp_day);

		$inp_month = $_POST['inp_month'];
		$inp_month = output_html($inp_month);
		$inp_month_mysql = quote_smart($link, $inp_month);

		mysqli_query($link, "INSERT INTO $t_recipes_occasions
		(occasion_id, occasion_name, occasion_day, occasion_month) 
		VALUES 
		(NULL, $inp_name_mysql, $inp_day_mysql, $inp_month_mysql)")
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

		<p>$l_day:<br />
		<select name=\"inp_day\">";
		for($x=1;$x<32;$x++){
			echo"			";
			echo"<option value=\"$x\">$x</option>\n";
		}
		echo"
		</select>
		</p>

		<p>$l_month:<br />
		<select name=\"inp_month\">";
		for($x=1;$x<13;$x++){
			echo"			";
			echo"<option value=\"$x\">$x</option>\n";
		}
		echo"
		</select>
		</p>

		<p><input type=\"submit\" value=\"$l_save\" class=\"btn\" /></p>
		</form>
	<!-- //Form -->
	

	";
} // add
elseif($action == "edit"){
	// Find
	$occasion_id_mysql = quote_smart($link, $occasion_id);
	$query = "SELECT occasion_id, occasion_name, occasion_day, occasion_month FROM $t_recipes_occasions WHERE occasion_id=$occasion_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_occasion_id, $get_occasion_name, $get_occasion_day, $get_occasion_month) = $row;
	
	if($get_occasion_id == ""){
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

			$inp_day = $_POST['inp_day'];
			$inp_day = output_html($inp_day);
			$inp_day_mysql = quote_smart($link, $inp_day);

			$inp_month = $_POST['inp_month'];
			$inp_month = output_html($inp_month);
			$inp_month_mysql = quote_smart($link, $inp_month);

			$result = mysqli_query($link, "UPDATE $t_recipes_occasions SET occasion_name=$inp_name_mysql, occasion_day=$inp_day_mysql, occasion_month=$inp_month_mysql WHERE occasion_id=$occasion_id_mysql") or die(mysqli_error($link));


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
			<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;occasion_id=$occasion_id&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">


			<p>$l_name:<br />
			<input type=\"text\" name=\"inp_name\" value=\"$get_occasion_name\" size=\"40\" />
			</p>

			<p>$l_day:<br />
			<select name=\"inp_day\">";
			for($x=1;$x<32;$x++){
				echo"			";
				echo"<option value=\"$x\""; if($get_occasion_day == "$x"){ echo" selected=\"selected\""; } echo">$x</option>\n";
			}
			echo"
			</select>
			</p>

			<p>$l_month:<br />
			<select name=\"inp_month\">";
			for($x=1;$x<13;$x++){
				echo"			";
				echo"<option value=\"$x\""; if($get_occasion_month == "$x"){ echo" selected=\"selected\""; } echo">$x</option>\n";
			}
			echo"
			</select>
			</p>

			<p><input type=\"submit\" value=\"$l_save\" class=\"btn\" /></p>
			</form>
		<!-- //Form -->
		";
	} // found
	
} // edit
elseif($action == "delete"){
	// Find
	$occasion_id_mysql = quote_smart($link, $occasion_id);
	$query = "SELECT occasion_id, occasion_name, occasion_day, occasion_month FROM $t_recipes_occasions WHERE occasion_id=$occasion_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_occasion_id, $get_occasion_name, $get_occasion_day, $get_occasion_month) = $row;
	
	if($get_occasion_id == ""){
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
			$result = mysqli_query($link, "DELETE FROM $t_recipes_occasions WHERE occasion_id=$occasion_id_mysql") or die(mysqli_error($link));


			$url = "index.php?open=$open&page=$page&editor_language=$editor_language&l=$l&ft=success&fm=changes_saved";
			header("Location: $url");
			exit;

		}


		echo"
		<h1>$l_delete</h1>


		<p>$l_are_you_sure_you_want_to_delete $l_this_action_cannot_be_undone</p>

		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;occasion_id=$occasion_id&amp;editor_language=$editor_language&amp;process=1\">$l_delete</a>
		&middot;
		<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;process=1\">$l_cancel</a>
		</p>
		";
	} // found
	
} // delete
elseif($action == "translations"){
	if($process == 1){
		$editor_language_mysql = quote_smart($link, $editor_language);
		$query = "SELECT occasion_id, occasion_name FROM $t_recipes_occasions";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_occasion_id, $get_occasion_name) = $row;

			$inp_occasion_translation_value = $_POST["inp_occasion_translation_value_$get_occasion_id"];
			$inp_occasion_translation_value = output_html($inp_occasion_translation_value);
			$inp_occasion_translation_value_mysql = quote_smart($link, $inp_occasion_translation_value);

			// Update
			$result_update = mysqli_query($link, "UPDATE $t_recipes_occasions_translations SET occasion_translation_value=$inp_occasion_translation_value_mysql WHERE occasion_id=$get_occasion_id AND occasion_translation_language=$editor_language_mysql") or die(mysqli_error($link));
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
		$query = "SELECT occasion_id, occasion_name, occasion_day, occasion_month FROM $t_recipes_occasions ORDER BY occasion_month, occasion_day ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_occasion_id, $get_occasion_name, $get_occasion_day, $get_occasion_month) = $row;

			// Style
			if(isset($style) && $style == ""){
				$style = "odd";
			}
			else{
				$style = "";
			}	

			// Translation
			$query_translation = "SELECT occasion_translation_id, occasion_translation_language, occasion_translation_value FROM $t_recipes_occasions_translations WHERE occasion_id=$get_occasion_id AND occasion_translation_language=$editor_language_mysql";
			$result_translation = mysqli_query($link, $query_translation);
			$row_translation = mysqli_fetch_row($result_translation);
			list($get_occasion_translation_id, $get_occasion_translation_language, $get_occasion_translation_value) = $row_translation;
			if($get_occasion_translation_id == ""){
				// It doesnt exists, create it.

				mysqli_query($link, "INSERT INTO $t_recipes_occasions_translations
				(occasion_translation_id, occasion_id, occasion_translation_language, occasion_translation_value) 
				VALUES 
				(NULL, '$get_occasion_id', $editor_language_mysql, '')")
				or die(mysqli_error($link));

				echo"<div class=\"info\"><span>L O A D I N G</span></div>";
				echo"
 				<meta http-equiv=\"refresh\" content=\"1;URL='index.php?open=$open&amp;page=$page&amp;action=$action&amp;editor_language=$editor_language&amp;l=$l'\" />
				";

				
			}

			echo"
			<tr>
			  <td class=\"$style\">
				<span>$get_occasion_name</span>
			  </td>
			  <td class=\"$style\">
				<span><input type=\"text\" name=\"inp_occasion_translation_value_$get_occasion_id\" value=\"$get_occasion_translation_value\" size=\"40\" /></span>
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