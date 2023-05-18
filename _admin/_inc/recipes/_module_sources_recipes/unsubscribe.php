<?php 
/**
*
* File: recipes/unsubscribe.php
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
include("_tables.php");


/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/recipes/ts_index.php");

/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['user_id'])){
	$user_id = $_GET['user_id'];
	$user_id = output_html($user_id);
}
else{
	$user_id = "";
}
$tabindex = 0;
$l_mysql = quote_smart($link, $l);

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_recipes";
include("$root/_webdesign/header.php");

/*- Content ---------------------------------------------------------------------------------- */


// Check users subscription
$user_id_mysql = quote_smart($link, $user_id);
$query = "SELECT es_id, es_user_id, es_type, es_on_off FROM $t_users_email_subscriptions WHERE es_user_id=$user_id_mysql AND es_type='comments'";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_es_id, $get_es_user_id, $get_es_type, $get_es_on_off) = $row;
if($get_es_id == ""){
	echo"
	<h1>Subscription not found</h1>

	<p>
	That subscription doesnt exists.
	</p>
	";
}			
else{
	$result = mysqli_query($link, "UPDATE $t_users_email_subscriptions SET es_on_off='0' WHERE es_user_id=$user_id_mysql AND es_type='comments'");


	echo"
	<h1>Unsubscribed successfully</h1>

	<p>
	You will no longer recive emails from us.
	</p>

	<p>
	<a href=\"$root/users/index.php?page=edit_subscriptions&amp;l=$l\">Subscriptions overview</a>
	</p>
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>