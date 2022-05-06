<?php 
/**
*
* File: food/store_new.php
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



/*- Variables ------------------------------------------------------------------------- */
$tabindex = 0;
$l_mysql = quote_smart($link, $l);

if(isset($_GET['store_id'])){
	$store_id= $_GET['store_id'];
	$store_id = strip_tags(stripslashes($store_id));
}
else{
	$store_id = "";
}
$store_id_mysql = quote_smart($link, $store_id);

// Title
$query = "SELECT title_id, title_value FROM $t_food_titles WHERE title_language=$l_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_title_id, $get_current_title_value) = $row;
// Fetch store
$query = "SELECT store_id, store_user_id, store_name, store_country, store_language, store_website, store_logo FROM $t_food_stores WHERE store_id=$store_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_store_id, $get_current_store_user_id, $get_current_store_name, $get_current_store_country, $get_current_store_language, $get_current_store_website, $get_current_store_logo) = $row;





/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_delete_store $get_current_store_name - $get_current_title_value";
include("$root/_webdesign/header.php");

/*- Content ---------------------------------------------------------------------------------- */

// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
	// My user id
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);

	if($get_current_store_id == ""){
		echo"
		<p>Store not found</p>
		";
	}
	else{
		if($get_current_store_user_id == "$my_user_id"){

			if($process == "1"){
				mysqli_query($link, "DELETE FROM $t_food_stores WHERE store_id=$get_current_store_id") or die(mysqli_error($link));

		

				$url = "my_stores.php?store_id=$store_id&l=$l&ft=success&fm=store_deleted";
				header("Location: $url");
				exit;
				
			} // process == 1

			echo"
			<h1>$get_current_store_name</h1>

			<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				if($fm == "missing_fat"){
					$fm = "Please enter fat";
				}
				else{
						$fm = ucfirst($fm);
				}
				echo"<div class=\"$ft\"><p>$fm</p></div>";	
			}
			echo"
			<!-- //Feedback -->

			<!-- Delete store form -->
				<p>$l_are_you_sure</p>

				<p><a href=\"my_stores_delete.php?store_id=$store_id&amp;l=$l&amp;process=1\" class=\"btn_warning\">$l_delete</a></p>
			<!-- //Delete store form -->
			<p>
			<a href=\"my_stores.php?l=$l\"><img src=\"_gfx/icons/go-previous.png\" alt=\"go-previous.png\" /></a>
			<a href=\"my_stores.php?l=$l\">$l_go_back</a>
			</p>
			";
		}
		else{
			echo"<p>Access denied</p";
		} // inncorrect user
	} // store found

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