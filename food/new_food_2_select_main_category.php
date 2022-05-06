<?php 
/**
*
* File: food/new_food_3_select_sub_category.php
* Version 1.0.0
* Date 17:23 24.10.2020
* Copyright (c) 2011-22020 Localhost
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
include("_tables_food.php");

/*- Tables ---------------------------------------------------------------------------- */
$t_search_engine_index 		= $mysqlPrefixSav . "search_engine_index";
$t_search_engine_access_control = $mysqlPrefixSav . "search_engine_access_control";



/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/food/ts_index.php");
include("$root/_admin/_translations/site/$l/food/ts_new_food.php");


/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['barcode'])){
	$barcode = $_GET['barcode'];
	$barcode = output_html($barcode);
	if(!(is_numeric($barcode))){
		echo"barcode_have_to_be_numeric";
		exit;
	}
}
else{
	$barcode = "";
}

$tabindex = 0;
$l_mysql = quote_smart($link, $l);

// Title
$query = "SELECT title_id, title_value FROM $t_food_titles WHERE title_language=$l_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_title_id, $get_current_title_value) = $row;

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_categorization - $l_new_food - $get_current_title_value";
include("$root/_webdesign/header.php");

/*- Content ---------------------------------------------------------------------------------- */

// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	if($process == "1"){
		$inp_main_category_id = $_POST['inp_main_category_id'];
		$inp_main_category_id = output_html($inp_main_category_id);

		$main_id = substr($inp_main_category_id, strrpos($inp_main_category_id, 'main_category_id=') + 1); // ain_category_id=7&l=no 
		$main_id = str_replace("ain_category_id=", "", $main_id);
		$main_id = str_replace("&amp;barcode=$barcode", "", $main_id);
		$main_id = str_replace("&amp;l=$l", "", $main_id);
		
		if(!(is_numeric($main_id))){
			echo"main_id_have_to_be_numeric";
			exit;
		}
		
		$url = "new_food_3_select_sub_category.php?main_category_id=$main_id"; 
		if($barcode != ""){ $url = $url . "&barcode=$barcode"; }
		$url = $url . "&l=$l";
		header("Location: $url");
		exit;
	}
	echo"
	<h1>$l_new_food</h1>
	


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
		
		<!-- Scripts-->
			<script>
			\$(document).ready(function(){
				\$('[name=\"main_category_id\"]').focus();
			});
			\$(function(){
					\$('.on_select_go_to_url').on('change', function () {
						var url = \$(this).val(); // get selected value
						if (url) { // require a URL
 							window.location = url; // redirect
						}
						return false;
					});
			});
			</script>
		<!-- //Scripts---->

		<h2>$l_categorization</h2>

		<form method=\"post\" action=\"new_food_2_select_main_category.php?l=$l"; if($barcode != ""){ echo"&amp;barcode=$barcode"; } echo"&amp;process=1\" enctype=\"multipart/form-data\">

		<table>
		 <tr>
		  <td style=\"text-align: right;padding: 0px 4px 0px 0px;\">
			<p><b>$l_category:</b></p>
		  </td>
		  <td>
			<p>
			<select name=\"inp_main_category_id\" class=\"on_select_go_to_url\">
			<option value=\"new_food_2_select_main_category.php?"; if($barcode != ""){ echo"&amp;barcode=$barcode&amp;l=$l"; } else{ echo"&amp;l=$l"; } echo"\">- $l_please_select -</option>
			<option value=\"new_food_2_select_main_category.php?"; if($barcode != ""){ echo"&amp;barcode=$barcode&amp;l=$l"; } else{ echo"&amp;l=$l"; } echo"\"> </option>
			";
			if(!(isset($main_category_id))){
				$main_category_id = "";
			}

			// Get all categories
			$query = "SELECT main_category_id, main_category_name FROM $t_food_categories_main ORDER BY main_category_name ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_main_category_id, $get_main_category_name) = $row;
				
				// Translation
				$query_t = "SELECT main_category_translation_id, main_category_translation_value FROM $t_food_categories_main_translations WHERE main_category_id=$get_main_category_id AND main_category_translation_language=$l_mysql";
				$result_t = mysqli_query($link, $query_t);
				$row_t = mysqli_fetch_row($result_t);
				list($get_main_category_translation_id, $get_main_category_translation_value) = $row_t;

				echo"
				<option value=\"new_food_3_select_sub_category.php?main_category_id=$get_main_category_id"; if($barcode != ""){ echo"&amp;barcode=$barcode"; } echo"&amp;l=$l\""; if($main_category_id == "$get_main_category_id"){ echo" selected=\"selected\""; } echo">$get_main_category_translation_value</option>\n";
				
			}
			echo"
			</select>
			</p>
		  </td>
		 </tr>
		</table>

		<p>
		<input type=\"submit\" value=\"$l_continue\" class=\"btn_default\" />
		</p>

		</form>
	<!-- //Form -->
	";
}
else{
	echo"
	<h1>
	<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/index.php?page=login&amp;l=$l&amp;refer=$root/food/new_food.php\">
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>