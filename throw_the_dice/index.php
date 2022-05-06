<?php
/**
*
* File: throw_the_dice/index.php
* Version 1.0.0.
* Date 10:33 09.12.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
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
include("_tables_dice.php");


/*- Variables ------------------------------------------------------------------------ */
if(isset($_GET['page'])) {
	$page = $_GET['page'];
	$page = strip_tags(stripslashes($page));
}
else{
	$page = "";
}


/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_throw_the_dice";
if($page != ""){
	$title = ucfirst($page);
	$title = str_replace("_", " ", $title);
	$website_title = $title . " - $website_title";
}
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("_design/header_dice.php");


// Includescript
	if($page != ""){
		if (preg_match('/(http:\/\/|^\/|\.+?\/)/', $page)){
			echo"Server error 403";
		}
		else{
			if(file_exists("_pages_dice/$page.php")){
				include("_pages_dice/$page.php");
			}
			else{
				echo"Server error 404";
			}
		}
	}
	else{
		include("_pages_dice/home.php");
	}




/*- Footer ----------------------------------------------------------------------------------- */
include("_design/footer_dice.php");
?>