<?php 
/**
*
* File: knowledge/spaces_overview.php
* Version 1.0
* Date 10:16 07.01.2020
* Copyright (c) 2019-2020 S. A. Ditlefsen
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


/*- Translation ------------------------------------------------------------------------------- */
include("$root/_admin/_translations/site/$l/knowledge/ts_knowledge.php");


/*- Tables ---------------------------------------------------------------------------------- */
$t_knowledge_home_page_user_remember 		= $mysqlPrefixSav . "knowledge_home_page_user_remember";

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_spaces";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");


/*- Content ---------------------------------------------------------------------------------- */
echo"
<h1>$l_spaces</h1>

<!-- Feedback -->
			";
			if($ft != ""){
				if($fm == "changes_saved"){
					$fm = "$l_changes_saved";
				}
				else{
					$fm = ucfirst($fm);
					$fm = str_replace("_", " ", $fm);
				}
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
<!-- //Feedback -->


<!-- Actions -->
			<p>
			<a href=\"new_space.php?l=$l\" class=\"btn_default\">$l_new_space</a>
			</p>
<!-- //Actions -->

<!-- My favorite spaces -->
			";
	
			if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
			
				// Access?
				$my_user_id = $_SESSION['user_id'];
				$my_user_id = output_html($my_user_id);
				$my_user_id_mysql = quote_smart($link, $my_user_id);
				echo"
				<p style=\"padding-bottom: 0;margin-bottom:0;\"><b>$l_my_favorites</b></p>
				<div class=\"vertical\">
					<ul>
					";
					$query_space = "SELECT favorite_space_id, favorite_space_title FROM $t_knowledge_spaces_favorites WHERE favorite_user_id=$my_user_id_mysql ORDER BY favorite_space_title ASC";
					$result_space = mysqli_query($link, $query_space);
					while($row_space = mysqli_fetch_row($result_space)) {
						list($get_favorite_space_id, $get_favorite_space_title) = $row_space;
						echo"			";
						echo"<li><a href=\"open_space.php?space_id=$get_favorite_space_id&amp;l=$l\">$get_favorite_space_title</a></li>\n";
					}
					echo"
					</ul>
				</div>
				";
			}
			echo"
<!-- My favorite spaces -->


<!-- Spaces -->

			";
			$query = "SELECT category_id, category_title FROM $t_knowledge_spaces_categories ORDER BY category_title ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_category_id, $get_category_title) = $row;
	
				echo"
				<p style=\"padding-bottom: 0;margin-bottom:0;\"><b>$get_category_title</b></p>
				<div class=\"vertical\">
					<ul>
					";
					$query_space = "SELECT space_id, space_title FROM $t_knowledge_spaces_index WHERE space_is_archived='0' ORDER BY space_title ASC";
					$result_space = mysqli_query($link, $query_space);
					while($row_space = mysqli_fetch_row($result_space)) {
						list($get_space_id, $get_space_title) = $row_space;
						echo"			";
						echo"<li><a href=\"open_space.php?space_id=$get_space_id&amp;l=$l\">$get_space_title</a></li>\n";
					}
					echo"
					</ul>
				</div>
				";
			}
			echo"
<!-- //Spaces -->
";


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/$webdesignSav/footer.php");
?>