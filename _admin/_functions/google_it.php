<?php
/**
*
* File: admin/common/functions/google_it.php
* Version 16:05 25.08.2011
* Copyright (c) 2008-2011 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/



/*- About ------------------------------------------------------------

 This function lets you search google. You should use this type of form:

 <form method=\"get\" action=\"admin/common/functions/google_it.php\" enctype=\"multipart/form-data\" id=\"searchform\">
 <p>
 <input type=\"text\" name=\"q\" size=\"20\" />
 <input type=\"text\" name=\"l\" size=\"$l\" />
 <input type=\"submit\" value=\"Search\" />
 </p>
 </form>

*/



/*- Variables ------------------------------------------------------------ */
if(isset($_GET['q'])) {
	$q = $_GET['q'];
	$q = strip_tags(stripslashes($q));
}
if(isset($_GET['l'])) {
	$l = $_GET['l'];
	$l = strip_tags(stripslashes($l));
}


/*- Settings ------------------------------------------------------------ */
$out = "http://www.google.com/search?q=";
$server = $_SERVER['HTTP_HOST'];
$new_url = "$out$q site:$server";


/*- Refer --------------------------------------------------------------- */
if($q == ""){
	header("Location: ../../../index.php?l=$l&q=nothing");
	exit;
}
else{
	header("Location: $new_url");
	exit;
}
?>