<?php 
/**
*
* File: howto/search.php
* Version 1.0
* Date 14:55 30.06.2019
* Copyright (c) 2019 S. A. Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Configuration ---------------------------------------------------------------------------- */
$pageIdSav            = "2";
$pageNoColumnSav      = "2";
$pageAllowCommentsSav = "1";
$pageAuthorUserIdSav  = "1";

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

/*- Functions ------------------------------------------------------------------------- */
include("$root/_admin/_functions/encode_national_letters.php");
include("$root/_admin/_functions/decode_national_letters.php");

/*- Variables -------------------------------------------------------------------------------- */
$tabindex = 0;

if (isset($_GET['inp_q'])) {
	$inp_q = $_GET['inp_q'];
	$inp_q = stripslashes(strip_tags($inp_q));
}
else{
	$inp_q = "";
}
$inp_q_mysql = quote_smart($link, $inp_q);


/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_search";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");


// Check for user
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);

	

	echo"
	<h1>$l_search</h1>

	<!-- Search form -->
		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_q\"]').focus();
		});
		</script>
		
		<form method=\"GET\" action=\"search.php\" enctype=\"multipart/form-data\">

		<p><b>$l_query</b><br />
		<input type=\"hidden\" name=\"action\" value=\"search\" />
		<input type=\"text\" name=\"inp_q\" value=\"$inp_q\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		<input type=\"submit\" value=\"$l_search\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>
		</form>
	<!-- //Search form -->

	<!-- Results -->";

		if($inp_q != ""){
			$q = "%" . $inp_q . "%";
			$q_mysql = quote_smart($link, $q);
	
			$x = 0;
			$query = "SELECT $t_knowledge_pages_index.page_id, $t_knowledge_pages_index.page_space_id, $t_knowledge_pages_index.page_title, $t_knowledge_pages_index.page_title_clean, $t_knowledge_pages_index.page_description FROM $t_knowledge_pages_index ";
			$query = $query . "JOIN $t_knowledge_spaces_members ON $t_knowledge_pages_index.page_space_id=$t_knowledge_spaces_members.member_space_id ";
			$query = $query . "WHERE $t_knowledge_pages_index.page_title LIKE $q_mysql AND member_user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_page_id, $get_page_space_id, $get_page_title, $get_page_title_clean, $get_page_description) = $row;

				echo"
				<p>
				<a href=\"view_page.php?space_id=$get_page_space_id&amp;page_id=$get_page_id&amp;l=$l\">$get_page_title</a><br />
				$get_page_description
				</p>
				";
			}
		} // $inp_q != ""
	echo"
	<!-- //Results -->
	";
} // logged in
else{
	echo"
	<h1><img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Please log in...</h1>
		
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=$root/knowledge/search.php\">
	";
	
}

/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/$webdesignSav/footer.php");
?>