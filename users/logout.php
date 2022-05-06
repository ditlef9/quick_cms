<?php
/**
*
* File: users/index.php
* Version 17.46 18.02.2017
* Copyright (c) 2009-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Configuration ---------------------------------------------------------------------------- */
$pageIdSav            = "0";
$pageNoColumnSav      = "2";
$pageAllowCommentsSav = "0";

/*- Root dir --------------------------------------------------------------------------------- */
// This determine where we are
if(file_exists("favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
elseif(file_exists("../../../../favicon.ico")){ $root = "../../../.."; }
else{ $root = "../../.."; }

/*- Website config --------------------------------------------------------------------------- */
include("$root/_admin/website_config.php");

/*- Translation ------------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/users/ts_index.php");

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_users";
include("$root/_webdesign/header.php");



/*- Content --------------------------------------------------------------------------- */


if($process == "1"){
	if (isset($_SERVER['HTTP_COOKIE'])){
    		$cookies = explode(';', $_SERVER['HTTP_COOKIE']);
    		foreach($cookies as $cookie) {
        		$parts = explode('=', $cookie);
        		$name = trim($parts[0]);
        		setcookie($name, '', time()-1000);
        		setcookie($name, '', time()-1000, '/');
    		}
	}

	$_SESSION = array();
	session_destroy();


	$host = $_SERVER['HTTP_HOST'];
	unset($_COOKIE['remember_user']);
	setcookie ('remember_user', 'unset', strtotime( '+10 months' ), '/', $host);
		
	header("Location: index.php?l=$l&ft=success&fm=you_are_now_logged_out_see_you");
	exit;
}
else{
	echo"
	<table>
	 <tr> 
	  <td style=\"padding-right: 6px;vertical-align: top;\">
		<span>
		<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"margin:0;padding: 23px 0px 0px 0px;\" />
		</span>
	  </td>
	  <td>
		<h1 style=\"border:0;margin:0;padding: 20px 0px 0px 0px;\">$l_users_log_out</h1>
	  </td>
	 </tr>
	</table>


	<p>
	$l_users_you_are_now_logged_out_see_you
	</p>
	
	<meta http-equiv=\"refresh\" content=\"1;url=logout.php?process=1&amp;l=$l\">
	";
}
/*- Footer ---------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");

?>