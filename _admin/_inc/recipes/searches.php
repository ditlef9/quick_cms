<?php
/**
*
* File: _admin/_inc/recipes/searches.php
* Version 1.0
* Date 13:41 04.11.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ---------------------------------------------------------------------------- */
$t_recipes_searches			= $mysqlPrefixSav . "recipes_searches";

/*- Variables ------------------------------------------------------------------------ */

/*- Script start --------------------------------------------------------------------- */
if($action == ""){
	echo"
	<h1>Searches</h1>


	<!-- Feedback -->
	";
	if($ft != ""){
		if($fm == "changes_saved"){
			$fm = "$l_changes_saved";
		}
		else{
			$fm = ucfirst($ft);
		}
		echo"<div class=\"$ft\"><span>$fm</span></div>";
	}
	echo"	
	<!-- //Feedback -->

	<!-- Where am I ? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=recipes&amp;page=menu&amp;editor_language=$editor_language\">Recipes</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=translations&amp;editor_language=$editor_language\">Searches</a>
		</p>
	<!-- //Where am I ?  -->


	<!-- Select language -->

		<script>
		\$(function(){
			// bind change event to select
			\$('#inp_l').on('change', function () {
				var url = \$(this).val(); // get selected value
				if (url) { // require a URL
 					window.location = url; // redirect
				}
				return false;
			});
		});
		</script>

		<form method=\"get\" enctype=\"multipart/form-data\">
		<p>
		$l_language:
		<select id=\"inp_l\">
			<option value=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">$l_editor_language</option>
			<option value=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">-</option>\n";


			$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_flag, language_active_default FROM $t_languages_active";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_flag, $get_language_active_default) = $row;

				$flag_path 	= "_design/gfx/flags/16x16/$get_language_active_flag" . "_16x16.png";

				// No language selected?
				if($editor_language == ""){
						$editor_language = "$get_language_active_iso_two";
				}
				
				
				echo"	<option value=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;editor_language=$get_language_active_iso_two&amp;l=$l\" style=\"background: url('$flag_path') no-repeat;padding-left: 20px;\"";if($editor_language == "$get_language_active_iso_two"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";
			}
		echo"
		</select>
		</p>
		</form>
	<!-- //Select language -->

	


	<!-- List all searches -->
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span>Query</span>
		   </th>
		   <th scope=\"col\">
			<span>Unique hits</span>
		   </th>
		   <th scope=\"col\">
			<span>Last searched</span>
		   </th>
		   <th scope=\"col\">
			<span>Recipes</span>
		   </th>
		  </tr>
		</thead>
		<tbody>
		";

		$editor_language_mysql = quote_smart($link, $editor_language);
		$query = "SELECT search_id, search_query, search_language, search_unique_count, search_unique_ip_block, search_first_datetime, search_first_saying, search_last_datetime, search_last_saying, search_found_recipes FROM $t_recipes_searches WHERE search_language=$editor_language_mysql ORDER BY search_id DESC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_search_id, $get_search_query, $get_search_language, $get_search_unique_count, $get_search_unique_ip_block, $get_search_first_datetime, $get_search_first_saying, $get_search_last_datetime, $get_search_last_saying, $get_search_found_recipes) = $row;

			if(isset($style) && $style == ""){
				$style = "odd";
			}
			else{
				$style = "";
			}			

			echo"
			<tr>
			  <td class=\"$style\">
				<span>$get_search_query</span>
			  </td>
			  <td class=\"$style\">
				<span>$get_search_unique_count</span>
			  </td>
			  <td class=\"$style\">
				<span>$get_search_last_saying</span>
			  </td>
			  <td class=\"$style\">
				<span>$get_search_found_recipes</span>
			 </td>
			</tr>
			";
		}
		echo"
		 </tbody>
		</table>
	<!-- //List all categories -->
 	";
} // action == "";
?>