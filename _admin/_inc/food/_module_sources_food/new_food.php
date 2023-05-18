<?php 
/**
*
* File: food/new_food.php
* Version 1.0.0
* Date 23:59 27.11.2017
* Copyright (c) 2011-2017 Localhost
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



/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['mode'])){
	$mode = $_GET['mode'];
	$mode = output_html($mode);
}
else{
	$mode = "";
}
if(isset($_GET['food_id'])){
	$food_id = $_GET['food_id'];
	$food_id = output_html($food_id);
}
else{
	$food_id = "";
}
$tabindex = 0;
$l_mysql = quote_smart($link, $l);

// Title
$query = "SELECT title_id, title_value FROM $t_food_titles WHERE title_language=$l_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_title_id, $get_current_title_value) = $row;
/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_new_food - $get_current_title_value";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");

/*- Content ---------------------------------------------------------------------------------- */

// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	if($process == "1"){
		$inp_food_barcode = $_POST['inp_food_barcode'];
		$inp_food_barcode = output_html($inp_food_barcode);
		if(!(is_numeric($inp_food_barcode))){
			$url = "new_food.php?l=$l&ft=error&fm=barcode_have_to_be_numeric";
			header("Location: $url");
			exit;
		}
		$inp_food_barcode_mysql = quote_smart($link, $inp_food_barcode);
		
		// Do we have this food?
		$inp_l_mysql = quote_smart($link, $l);
		$query_t = "SELECT food_id FROM $t_food_index WHERE food_barcode=$inp_food_barcode_mysql AND food_language=$inp_l_mysql";
		$result_t = mysqli_query($link, $query_t);
		$row_t = mysqli_fetch_row($result_t);
		list($get_food_id) = $row_t;
		if($get_food_id == ""){
			$url = "new_food_2_select_main_category.php?barcode=$inp_food_barcode&l=$l";
			header("Location: $url");
			exit;
		}
		else{
			$url = "new_food.php?food_id=$get_food_id&l=$l&ft=error&fm=we_already_have_that_food&food_id=$get_food_id";
			header("Location: $url");
			exit;
		}		

	} // process

	
	echo"
	<h1>$l_new_food</h1>
	

	<!-- Focus -->
		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_food_barcode\"]').focus();
		});
		</script>
	<!-- //Focus -->

	<!-- Feedback -->
			";
			if($ft != ""){
				if($fm == "changes_saved"){
					$fm = "$l_changes_saved";
				}
				else{
					if($fm == "we_already_have_that_food"){
						// Find the food
						$food_id = $_GET['food_id'];
						$food_id = output_html($food_id);
						$food_id_mysql = quote_smart($link, $food_id);
						$query_t = "SELECT food_id, food_name, food_manufacturer_name, food_main_category_id, food_sub_category_id FROM $t_food_index WHERE food_id=$food_id_mysql";
						$result_t = mysqli_query($link, $query_t);
						$row_t = mysqli_fetch_row($result_t);
						list($get_food_id, $get_food_name, $get_food_manufacturer_name, $get_food_main_category_id, $get_food_sub_category_id) = $row_t;
						if($get_food_id != ""){
							$fm = "$l_we_already_have_that_food. $l_view_it: <a href=\"view_food.php?main_category_id=$get_food_main_category_id&amp;sub_category_id=$get_food_sub_category_id&amp;food_id=$get_food_id&amp;l=$l\">$get_food_manufacturer_name $get_food_name</a>.";
						}
					}
					else{
						$fm = str_replace("_", " ", $fm);
						$fm = ucfirst($fm);
					}
				}
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
	<!-- //Feedback -->

	<!-- Form -->
		<form method=\"post\" action=\"new_food.php?l=$l&amp;process=1\" enctype=\"multipart/form-data\">
			

		<h2>$l_barcode</h2>
		<table>
		 <tr>
		  <td style=\"text-align: right;padding: 0px 4px 0px 0px;\">
			<p><b>$l_language:</b></p>
		  </td>
		  <td>
			<script>
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
			<p>
			<select name=\"l\" class=\"on_select_go_to_url\">
			<option value=\"$l\">- $l_please_select -</option>
			<option value=\"$l\"></option>\n";
			$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;
				$flag_path 	= "_design/gfx/flags/16x16/$get_language_active_flag" . "_16x16.png";
	
				// No language selected?
				if($l == ""){
					$l = "$get_language_active_iso_two";
				}
				echo"	<option value=\"new_food.php?l=$get_language_active_iso_two\" style=\"background: url('$flag_path') no-repeat;padding-left: 20px;\"";if($l == "$get_language_active_iso_two"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";
			}
			echo"
			</select>
			</p>
		  </td>
		  <td>
		  </td>
		 </tr>
		 <tr>
		  <td style=\"text-align: right;padding: 0px 4px 0px 0px;\">
			<p><b>$l_barcode:</b></p>
		  </td>
		  <td>
			<p>
			<input type=\"text\" name=\"inp_food_barcode\" value=\"";if(isset($_GET['inp_food_barcode'])){
				$inp_food_barcode = $_GET['inp_food_barcode'];
				$inp_food_barcode = strip_tags(stripslashes($inp_food_barcode));
				$inp_food_barcode = output_html($inp_food_barcode);
				echo"$inp_food_barcode";
			}
			echo"\" size=\"40\" />
			</p>
		  </td>
		  <td>
			<p>
			<input type=\"submit\" value=\"$l_next\" class=\"btn_default\" />
			</p>
		  </td>
		 </tr>


		 <tr>
		  <td style=\"text-align: right;padding: 0px 4px 0px 0px;\">
		  </td>
		  <td>
		  </td>
		 </tr>
		</table>
		<p style=\"margin-top: 40px;\">
		<a href=\"new_food_2_select_main_category.php?l=$l\" class=\"btn_default\">$l_continue_without_barcode</a>
		</p>
	<!-- //Form -->
	";
}
else{
	echo"
	<h1>
	<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=food/new_food.php\">
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>