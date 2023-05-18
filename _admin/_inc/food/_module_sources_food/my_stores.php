<?php
/**
*
* File: _food/my_stores.php
* Version 1.0.0.
* Date 12:42 21.01.2018
* Copyright (c) 2008-2018 Sindre Andre Ditlefsen
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
$l_mysql = quote_smart($link, $l);

if(isset($_GET['order_by'])) {
	$order_by = $_GET['order_by'];
	$order_by = strip_tags(stripslashes($order_by));
}
else{
	$order_by = "food_id";
}
if(isset($_GET['order_method'])) {
	$order_method = $_GET['order_method'];
	$order_method = strip_tags(stripslashes($order_method));
}
else{
	$order_method = "DESC";
}
// Title
$query = "SELECT title_id, title_value FROM $t_food_titles WHERE title_language=$l_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_title_id, $get_current_title_value) = $row;

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/food/ts_index.php");
include("$root/_admin/_translations/site/$l/food/ts_my_stores_new.php");




/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_my_stores - $get_current_title_value";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");




// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);

	echo"
	<!-- Headline -->
		<h1>$l_my_stores</h1>
	<!-- //Headline -->

	<!-- Where am I ? -->
		<p><b>$l_you_are_here:</b><br />
		<a href=\"index.php?l=$l\">$get_current_title_value</a>
		&gt;
		<a href=\"my_stores.php?l=$l\">$l_my_stores</a>
		</p>
	<!-- //Where am I ? -->

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

	<!-- Menu -->
		<p>
		<a href=\"my_stores_new.php?l=$l\" class=\"btn_default\">$l_new_store</a>
		</p>
	<!-- //Menu -->


	<!-- My stores -->
		<div class=\"vertical\">
			<ul>
		";

		// Get stores
		$query = "SELECT store_id, store_name, store_icon_18x18 FROM $t_food_stores WHERE store_user_id=$my_user_id_mysql AND store_language=$l_mysql ORDER BY store_name ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_store_id, $get_store_name, $get_store_icon_18x18) = $row;
			

			echo"
				<li><a href=\"my_stores_edit.php?store_id=$get_store_id&amp;l=$l\">";
				if(file_exists("$root/_uploads/food/stores/$get_store_icon_18x18") && $get_store_icon_18x18 != ""){
					echo"<img src=\"$root/_uploads/food/stores/$get_store_icon_18x18\" alt=\"$get_store_icon_18x18\" /> ";
				}
				echo"$get_store_name</a></li>
			";
		}

		echo"
			</ul>
		</div>
	<!-- //My stores -->
	";

}
else{
	echo"
	<h1>
	<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=food/my_food.php\">

	<p>Please log in...</p>
	";
}

/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>