<?php
/**
*
* File: _admin/_inc/search_engine_index.php
* Version 1.0.1
* Date 12:54 28.04.2019
* Copyright (c) 2008-2019 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Tables ---------------------------------------------------------------------------- */
$t_search_engine_index 		= $mysqlPrefixSav . "search_engine_index";
$t_search_engine_access_control = $mysqlPrefixSav . "search_engine_access_control";
$t_search_engine_searches 	= $mysqlPrefixSav . "search_engine_searches";




/*- Variables -------------------------------------------------------------------------- */

if(isset($_GET['order_by'])) {
	$order_by = $_GET['order_by'];
	$order_by = strip_tags(stripslashes($order_by));
}
else{
	$order_by = "index_id";
}
if(isset($_GET['order_method'])) {
	$order_method = $_GET['order_method'];
	$order_method = strip_tags(stripslashes($order_method));
}
else{
	$order_method = "desc";
}
if(isset($_GET['mode'])) {
	$mode = $_GET['mode'];
	$mode = strip_tags(stripslashes($mode));
}
else{
	$mode = "";
}


if(isset($_GET['index_id'])) {
	$index_id = $_GET['index_id'];
	$index_id = strip_tags(stripslashes($index_id));
}
else{
	$index_id = "";
}


if($action == ""){
	
	echo"
	<!-- Headline and about -->
		<table>
		 <tr>
		  <td style=\"padding-right: 14px;\">
			<h1>Search engine index</h1>
		  </td>
		  <td>
			<span>The search engine holds links to all pages that are in your website. 
			The title is used as search query. 
			<br />Title is build up like this: PageName | PageNameSub  | Sub module | Module
		  </td>
		 </tr>
		</table>
	<!-- //Headline and about -->

	<!-- Menu -->
		<table>
		 <tr>
		  <td style=\"padding-right: 14px;\">
			<!-- Editor language -->
			<p>
			<select name=\"editor_language\" class=\"on_select_go_to_url\">
				<option value=\"index.php?open=$open&amp;page=$page&amp;order_method=$order_method&amp;order_by=$order_by&amp;&amp;l=$l\">- Editor language -</option>\n";
				$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;
					echo"	<option value=\"index.php?open=$open&amp;page=$page&amp;order_method=$order_method&amp;order_by=$order_by&amp;&amp;l=$l&amp;editor_language=$get_language_active_iso_two\""; if($editor_language == "$get_language_active_iso_two"){ echo" selected=\"selected\""; } echo">$get_language_active_name</option>\n";
				}
				echo"
			</select>
			</p>
				<!-- On select go to url -->
					<script>
					\$(function(){
						// bind change event to select
						\$('.on_select_go_to_url').on('change', function () {
          					var url = \$(this).val(); // get selected value
        						if (url) { // require a URL
        							window.location = url; // redirect
							}
							return false;
						});
					});
					</script>
				<!-- //On select go to url -->

				
			<!-- //Editor language -->
		  </td>
		  <td>
			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=scan_all_modules_and_insert_into_index&amp;mode=create_module_list&amp;order_method=$order_method&amp;order_by=$order_by&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">Scan all modules and insert into index</a>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=truncate_table&amp;order_method=$order_method&amp;order_by=$order_by&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_default\">Truncate tables</a>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=show_searches&amp;order_method=$order_method&amp;order_by=$order_by&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">Show searches</a>
			</p>
		  </td>
		 </tr>
		</table>
	<!-- Menu -->

	<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = ucfirst($fm);
		}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
	<!-- //Feedback -->


	<!-- Index -->
		
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">";
			if($order_by == "index_id" && $order_method == "asc"){
				$order_method_link = "desc";
			}
			else{
				$order_method_link = "asc";
			}

			echo"
			<span><a href=\"index.php?open=$open&amp;page=$page&amp;order_by=index_id&amp;order_method=$order_method_link&amp;editor_language=$editor_language&amp;l=$l\" style=\"color:black;\"><b>ID</b></a>";
			if($order_by == "index_id" && $order_method == "asc"){
				echo"<img src=\"_design/gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
			}
			if($order_by == "index_id" && $order_method == "desc"){
				echo"<img src=\"_design/gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
			}
			echo"</span>
		   </th>
		   <th scope=\"col\">";
			if($order_by == "index_title" && $order_method == "asc"){
				$order_method_link = "desc";
			}
			else{
				$order_method_link = "asc";
			}

			echo"
			<span><a href=\"index.php?open=$open&amp;page=$page&amp;order_by=index_title&amp;order_method=$order_method_link&amp;editor_language=$editor_language&amp;l=$l\" style=\"color:black;\"><b>Title</b></a>";
			if($order_by == "index_title" && $order_method == "asc"){
				echo"<img src=\"_design/gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
			}
			if($order_by == "index_title" && $order_method == "desc"){
				echo"<img src=\"_design/gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
			}
			echo"</span>
		   </th>
		   <th scope=\"col\">";
			if($order_by == "index_module_name" && $order_method == "asc"){
				$order_method_link = "desc";
			}
			else{
				$order_method_link = "asc";
			}

			echo"
			<span><a href=\"index.php?open=$open&amp;page=$page&amp;order_by=index_module_name&amp;order_method=$order_method_link&amp;editor_language=$editor_language&amp;l=$l\" style=\"color:black;\"><b>Module</b></a>";
			if($order_by == "index_module_name" && $order_method == "asc"){
				echo"<img src=\"_design/gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
			}
			if($order_by == "index_module_name" && $order_method == "desc"){
				echo"<img src=\"_design/gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
			}
			echo"</span>
		   </th>
		   <th scope=\"col\">";
			if($order_by == "index_module_part_name" && $order_method == "asc"){
				$order_method_link = "desc";
			}
			else{
				$order_method_link = "asc";
			}

			echo"
			<span><a href=\"index.php?open=$open&amp;page=$page&amp;order_by=index_module_part_name&amp;order_method=$order_method_link&amp;editor_language=$editor_language&amp;l=$l\" style=\"color:black;\"><b>Module part</b></a>";
			if($order_by == "index_module_part_name" && $order_method == "asc"){
				echo"<img src=\"_design/gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
			}
			if($order_by == "index_module_part_name" && $order_method == "desc"){
				echo"<img src=\"_design/gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
			}
			echo"</span>
		   </th>
		   <th scope=\"col\">";
			if($order_by == "index_unique_hits" && $order_method == "asc"){
				$order_method_link = "desc";
			}
			else{
				$order_method_link = "asc";
			}

			echo"
			<span><a href=\"index.php?open=$open&amp;page=$page&amp;order_by=index_unique_hits&amp;order_method=$order_method_link&amp;editor_language=$editor_language&amp;l=$l\" style=\"color:black;\"><b>Unique hits</b></a>";
			if($order_by == "index_unique_hits" && $order_method == "asc"){
				echo"<img src=\"_design/gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
			}
			if($order_by == "index_unique_hits" && $order_method == "desc"){
				echo"<img src=\"_design/gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
			}
			echo"</span>
		   </th>
		   <th scope=\"col\">";
			if($order_by == "index_language" && $order_method == "asc"){
				$order_method_link = "desc";
			}
			else{
				$order_method_link = "asc";
			}

			echo"
			<span><a href=\"index.php?open=$open&amp;page=$page&amp;order_by=index_language&amp;order_method=$order_method_link&amp;editor_language=$editor_language&amp;l=$l\" style=\"color:black;\"><b>Language</b></a>";
			if($order_by == "index_language" && $order_method == "asc"){
				echo"<img src=\"_design/gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
			}
			if($order_by == "index_language" && $order_method == "desc"){
				echo"<img src=\"_design/gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
			}
			echo"</span>
		   </th>
		  </tr>
		 </thead>
		";

		$query = "SELECT index_id, index_title, index_url, index_short_description, index_module_name, index_module_part_name, index_reference_id, index_is_ad, index_created_datetime, index_created_datetime_print, index_updated_datetime, index_updated_datetime_print, index_language, index_unique_hits, index_hits_ipblock FROM $t_search_engine_index";
		if($editor_language != ""){
			$editor_language_mysql = quote_smart($link, $editor_language);
			$query = $query . " WHERE index_language=$editor_language_mysql OR index_language=''";
		}
		if($order_by == "index_id" OR $order_by == "index_title" OR $order_by == "index_module_name" OR $order_by == "index_module_part_name" OR $order_by == "index_language" OR $order_by == "index_unique_hits"){
			if($order_method == "asc" OR $order_method == "desc"){
				$query = $query . " ORDER BY $order_by $order_method";
			}
		}

		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_index_id, $get_index_title, $get_index_url, $get_index_short_description, $get_index_module_name, $get_index_module_part_name, $get_index_reference_id, $get_index_is_ad, $get_index_created_datetime, $get_index_created_datetime_print, $get_index_updated_datetime, $get_index_updated_datetime_print, $get_index_language, $get_index_unique_hits, $get_index_hits_ipblock) = $row;
			
			// Style
			if(isset($style) && $style == ""){
				$style = "odd";
			}
			else{
				$style = "";
			}


		
			echo"
			 <tr>
			  <td class=\"$style\">
				<span>
				$get_index_id
				</span>
			  </td>
			  <td class=\"$style\">
				<span>
				<a href=\"../$get_index_url\">$get_index_title</a>
				</span>
			  </td>
			  <td class=\"$style\">
				<span>
				$get_index_module_name
				</span>
			  </td>
			  <td class=\"$style\">
				<span>
				$get_index_module_part_name
				</span>
			  </td>
			  <td class=\"$style\">
				<span>
				$get_index_unique_hits
				</span>
			  </td>
			  <td class=\"$style\">
				<span>
				$get_index_language
				</span>
			  </td>
			 </tr>";
		}
		
		echo"
			</table>
		  </td>
		 </tr>
		</table>
	<!-- //Index  -->

	";
}
elseif($action == "scan_all_modules_and_insert_into_index"){
	// Variables
	if(isset($_GET['module'])) {
		$module= $_GET['module'];
		$module = strip_tags(stripslashes($module));
	}
	else{
		$module = "";
	}
	if($mode == "create_module_list"){


		// Make list of modules
		$pick_next_file_as_module = "";
		$next_module = "$module";

		$filenames = "";
		$dir = "_inc";
		$list_inp  = "";
		if ($handle = opendir($dir)) {
			$files = array();   
			while (false !== ($module_dir = readdir($handle))) {
			

				if(file_exists("$dir/$module_dir/_search_engine_index.php")){
				
					// Write this module to text file
					if($list_inp == ""){	
						$list_inp  = "$module_dir";
					}
					else{
						$list_inp  = $list_inp . "\n$module_dir";
					}

					// Pick a module
					if($module == ""){
						$module = "$module_dir";
					}

				}
			}
			closedir($handle);
		}

		// Write list to text file
		$fh = fopen("../_cache/search_engine_modules.txt", "w+") or die("can not open file");
		fwrite($fh, $list_inp);
		fclose($fh); 

		$mode = "insert_and_update_search_index";

	} // mode pick module
	if($mode == "insert_and_update_search_index"){
		echo"
		<h1><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float: left;padding-right: 6px;\" /> Inserting</h1>


		<table>
		 <tr>
		  <td style=\"vertical-align: top;padding-right: 30px;\">
			<!-- Modules list on left -->";
				echo"
				<table class=\"hor-zebra\">
				 <tbody>
				  <tr>
				   <td>
					";

					$fh = fopen("../_cache/search_engine_modules.txt", "r");
					$read_modules = fread($fh, filesize("../_cache/search_engine_modules.txt"));
					fclose($fh);
				
					$modules_array = explode("\n", $read_modules);

					$pick_next_module = "";
					$next_module 	  = "";
					for($x=0;$x<sizeof($modules_array);$x++){
						$module_name = $modules_array[$x];
						if($pick_next_module == "true"){ 
							$next_module = "$module_name";
							$pick_next_module = "false";

						}


						echo"
						<a href=\"index.php?open=$open&amp;page=$page&amp;action=scan_all_modules_and_insert_into_index&amp;mode=insert_and_update_search_index&amp;module=$module_name&amp;order_by=$order_by&amp;order_method=$order_method&amp;editor_language=$editor_language&amp;l=$l\""; if($module_name == "$module"){ echo" style=\"font-weight: bold;\""; } echo">$module_name</a><br />\n";

						if($module_name == "$module"){ 
							$pick_next_module = "true";
						}
					}
					echo"
				   </td>
				  </tr>
				 </tbody>
				</table>

			<!-- //Modules list on left -->
		  </td>
		  <td style=\"vertical-align: top;padding-right: 30px;\">
			<!-- Includes on right -->
				<h2>$module</h2>
				";
				include("_inc/$module/_search_engine_index.php");
				echo"

			<!-- //Includes on right -->
		  </td>
		 </tr>
		</table>

		";
		if($next_module != ""){
			echo"
			<meta http-equiv=refresh content=\"1; URL=index.php?open=$open&amp;page=$page&amp;action=scan_all_modules_and_insert_into_index&amp;mode=insert_and_update_search_index&amp;module=$next_module&amp;order_by=$order_by&amp;order_method=$order_method&amp;editor_language=$editor_language&amp;l=$l\">
	
			<!-- Jquery go to URL after x seconds -->
			<!-- In case meta refresh doesnt work -->
   			<script>
			\$(document).ready(function(){
				window.setTimeout(function(){
        				// Move to a new location or you can do something else
					window.location.href = \"index.php?open=$open&page=$page&action=scan_all_modules_and_insert_into_index&mode=insert_and_update_search_index&module=$next_module&order_by=$order_by&order_method=$order_method&editor_language=$editor_language&l=$l\";
				}, 10000);
			});
   			</script>
			<!-- //Jquery go to URL after x seconds -->
			";
		}
		else{
			echo"
			<meta http-equiv=refresh content=\"1; URL=index.php?open=$open&amp;page=$page&amp;order_by=$order_by&amp;order_method=$order_method&amp;editor_language=$editor_language&amp;l=$l&amp;ft=success&amp;fm=index_complete\">
	
			<!-- Jquery go to URL after x seconds -->
			<!-- In case meta refresh doesnt work -->
   			<script>
			\$(document).ready(function(){
				window.setTimeout(function(){
        				// Move to a new location or you can do something else
					window.location.href = \"index.php?open=$open&page=$page&order_by=$order_by&order_method=$order_method&editor_language=$editor_language&l=$l&ft=success&fm=index_complete\";
				}, 10000);
			});
   			</script>
			<!-- //Jquery go to URL after x seconds -->
			";
		}
	} // mode insert and update_search_index
	
} // scan_all_modules_and_insert_into_index
elseif($action == "truncate_table"){
	$result = mysqli_query($link, "TRUNCATE TABLE $t_search_engine_access_control");
	$result = mysqli_query($link, "TRUNCATE TABLE $t_search_engine_index");
	
	$url = "index.php?open=$open&page=$page&order_by=$order_by&order_method=$order_method&editor_language=$editor_language&l=$l&ft=success&fm=tables_trucated";
	header("Location: $url");
	exit;
} // truncate_table
elseif($action == "show_searches"){
	
	echo"
	<!-- Headline and about -->
		<table>
		 <tr>
		  <td style=\"padding-right: 14px;\">
			<h1>Search engine index</h1>
		  </td>
		  <td>
			<span>The search engine holds links to all pages that are in your website. 
			The title is used as search query. 
			<br />Title is build up like this: PageName | PageNameSub  | Sub module | Module
		  </td>
		 </tr>
		</table>
	<!-- //Headline and about -->

	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=settings&amp;page=search_engine_index&amp;editor_language=$editor_language&amp;l=$l\">Search engine index</a>
		&gt;
		<a href=\"index.php?open=settings&amp;page=search_engine_index&amp;action=$action&amp;editor_language=$editor_language&amp;l=$l\">Searches</a>
		</p>
	<!-- Where am I?  -->
	<!-- Menu -->
		
	<!-- Menu -->

	<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = ucfirst($fm);
		}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
	<!-- //Feedback -->


	<!-- Index -->
		
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">";
			if($order_by == "search_id" && $order_method == "asc"){
				$order_method_link = "desc";
			}
			else{
				$order_method_link = "asc";
			}

			echo"
			<span><a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;order_by=search_id&amp;order_method=$order_method_link&amp;editor_language=$editor_language&amp;l=$l\" style=\"color:black;\"><b>ID</b></a>";
			if($order_by == "search_id" && $order_method == "asc"){
				echo"<img src=\"_design/gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
			}
			if($order_by == "search_id" && $order_method == "desc"){
				echo"<img src=\"_design/gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
			}
			echo"</span>
		   </th>
		   <th scope=\"col\">";
			if($order_by == "search_query" && $order_method == "asc"){
				$order_method_link = "desc";
			}
			else{
				$order_method_link = "asc";
			}

			echo"
			<span><a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;order_by=search_keyword&amp;order_method=$order_method_link&amp;editor_language=$editor_language&amp;l=$l\" style=\"color:black;\"><b>Query</b></a>";
			if($order_by == "search_query" && $order_method == "asc"){
				echo"<img src=\"_design/gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
			}
			if($order_by == "search_query" && $order_method == "desc"){
				echo"<img src=\"_design/gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
			}
			echo"</span>
		   </th>
		   <th scope=\"col\">";
			if($order_by == "search_unique_counter" && $order_method == "asc"){
				$order_method_link = "desc";
			}
			else{
				$order_method_link = "asc";
			}

			echo"
			<span><a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;order_by=search_unique_counter&amp;order_method=$order_method_link&amp;editor_language=$editor_language&amp;l=$l\" style=\"color:black;\"><b>Search counter</b></a>";
			if($order_by == "search_unique_counter" && $order_method == "asc"){
				echo"<img src=\"_design/gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
			}
			if($order_by == "search_unique_counter" && $order_method == "desc"){
				echo"<img src=\"_design/gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
			}
			echo"</span>
		   </th>
		   <th scope=\"col\">";
			if($order_by == "search_number_of_results" && $order_method == "asc"){
				$order_method_link = "desc";
			}
			else{
				$order_method_link = "asc";
			}

			echo"
			<span><a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;order_by=search_number_of_results&amp;order_method=$order_method_link&amp;editor_language=$editor_language&amp;l=$l\" style=\"color:black;\"><b>Results</b></a>";
			if($order_by == "search_number_of_results" && $order_method == "asc"){
				echo"<img src=\"_design/gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
			}
			if($order_by == "search_number_of_results" && $order_method == "desc"){
				echo"<img src=\"_design/gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
			}
			echo"</span>
		   </th>
		   <th scope=\"col\">";
			if($order_by == "search_created_datetime" && $order_method == "asc"){
				$order_method_link = "desc";
			}
			else{
				$order_method_link = "asc";
			}

			echo"
			<span><a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;order_by=search_created_datetime&amp;order_method=$order_method_link&amp;editor_language=$editor_language&amp;l=$l\" style=\"color:black;\"><b>Created</b></a>";
			if($order_by == "search_created_datetime" && $order_method == "asc"){
				echo"<img src=\"_design/gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
			}
			if($order_by == "search_created_datetime" && $order_method == "desc"){
				echo"<img src=\"_design/gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
			}
			echo"</span>
		   </th>
		   <th scope=\"col\">";
			if($order_by == "search_updated_datetime" && $order_method == "asc"){
				$order_method_link = "desc";
			}
			else{
				$order_method_link = "asc";
			}

			echo"
			<span><a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;order_by=search_updated_datetime&amp;order_method=$order_method_link&amp;editor_language=$editor_language&amp;l=$l\" style=\"color:black;\"><b>Updated</b></a>";
			if($order_by == "search_updated_datetime" && $order_method == "asc"){
				echo"<img src=\"_design/gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
			}
			if($order_by == "search_updated_datetime" && $order_method == "desc"){
				echo"<img src=\"_design/gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
			}
			echo"</span>
		   </th>
		  </tr>
		 </thead>
		";

		$query = "SELECT search_id, search_query, search_unique_counter, search_unique_ip_block, search_number_of_results, search_created_datetime, search_created_datetime_print, search_updated_datetime, search_updated_datetime_print FROM $t_search_engine_searches";
		
		if($order_by == "search_id" OR $order_by == "search_keyword" OR $order_by == "search_unique_counter" OR $order_by == "search_number_of_results" OR $order_by == "search_created_datetime" OR $order_by == "search_updated_datetime"){
			if($order_method == "asc" OR $order_method == "desc"){
				$query = $query . " ORDER BY $order_by $order_method";
			}
		}

		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_search_id, $get_search_query, $get_search_unique_counter, $get_search_unique_ip_block, $get_search_number_of_results, $get_search_created_datetime, $get_search_created_datetime_print, $get_search_updated_datetime, $get_search_updated_datetime_print) = $row;
			
			// Style
			if(isset($style) && $style == ""){
				$style = "odd";
			}
			else{
				$style = "";
			}


		
			echo"
			 <tr>
			  <td class=\"$style\">
				<span>
				$get_search_id
				</span>
			  </td>
			  <td class=\"$style\">
				<span>
				$get_search_query
				</span>
			  </td>
			  <td class=\"$style\">
				<span>
				$get_search_unique_counter
				</span>
			  </td>
			  <td class=\"$style\">
				<span>
				$get_search_number_of_results
				</span>
			  </td>
			  <td class=\"$style\">
				<span>
				$get_search_created_datetime_print
				</span>
			  </td>
			  <td class=\"$style\">
				<span>
				$get_search_updated_datetime_print
				</span>
			  </td>
			 </tr>";
		}
		
		echo"
			</table>
		  </td>
		 </tr>
		</table>
	<!-- //Index  -->

	";
}
?>