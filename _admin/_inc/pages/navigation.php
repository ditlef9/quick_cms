<?php
/**
*
* File: _admin/_inc/dashboard/navigation.php
* Version 2
* Date 15.18 03.03.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Access check --------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Variables ------------------------------------------------------------------------ */
if(isset($_GET['id'])) {
	$id = $_GET['id'];
	$id = strip_tags(stripslashes($id));
}
else{
	$id = "";
}
$tabindex = 0;

if($action == ""){
	echo"
	<h2>$l_navigation</h2>

	<!-- Feedback -->
	";
	if($ft != ""){
		if($fm == "changes_saved"){
			$fm = "$l_changes_saved";
		}
		elseif($fm == "navgation_item_deleted"){
			$fm = "$l_navgation_item_deleted";
		}
		
		echo"<div class=\"$ft\"><span>$fm</span></div>";
	}
	echo"	
	<!-- //Feedback -->

		
	<!-- Menu: Editor language, Actions -->
		<script>
		\$(function(){
			\$('#inp_l').on('change', function () {
				var url = \$(this).val(); // get selected value
				if (url) { // require a URL
 					window.location = url; // redirect
				}
				return false;
			});
		});
		</script>
		<table>
		 <tr>
		  <td style=\"padding-right: 10px;\">
			<p>
			<select id=\"inp_l\">\n";
			$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;

	
				// No language selected?
				if($editor_language == ""){
						$editor_language = "$get_language_active_iso_two";
				}
				
				echo"	<option value=\"index.php?open=$open&amp;page=navigation&amp;editor_language=$get_language_active_iso_two&amp;l=$l\""; if($editor_language == "$get_language_active_iso_two"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";
			}
			echo"
			</select>
			</p>
		  </td>
		  <td style=\"padding-right: 4px;\">
			<p>
			<a href=\"index.php?open=$open&amp;page=navigation&amp;action=new&amp;editor_language=$editor_language\"><img src=\"_inc/pages/_gfx/icons/list-add.png\" alt=\"\" /></a>
			</p>
		  </td>
		  <td>
			<p>
			<a href=\"index.php?open=$open&amp;page=navigation&amp;action=new&amp;editor_language=$editor_language\">$l_new_menu_item</a>
			</p>
		  </td>
		 </tr>
		</table>
	<!-- //Menu -->
		
	<!-- Navigation list -->

		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span>$l_title</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_url</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_actions</span>
		   </th>
		  </tr>
		</thead>
		<tbody>";



		// Select
		$editor_language_mysql = quote_smart($link, $editor_language);
		$query = "SELECT navigation_id, navigation_parent_id, navigation_title, navigation_url, navigation_weight FROM $t_pages_navigation WHERE navigation_parent_id='0' AND navigation_language=$editor_language_mysql ORDER BY navigation_weight ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_navigation_id, $get_navigation_parent_id, $get_navigation_title, $get_navigation_url, $get_navigation_weight) = $row;

			// Style
			if(isset($odd) && $odd == false){
				$odd = true;
			}
			else{
				$odd = false;
			}	

			echo"
			 <tr>
       			  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
          			<span>$get_navigation_title</span>
			  </td>
       			  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
				<span><a href=\"../$get_navigation_url\">$get_navigation_url</a></span>
			  </td>
       			  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
				<script type=\"text/javascript\">
				function confirmDelete$get_navigation_id() {
					if (confirm(\"$l_are_your_sure_you_want_to_delete_the_item\")) {
						return true;
					}
					else {
						return false;
					}
				}
				</script>

				<span>
				";
				if($get_navigation_weight == "0"){
					echo"<img src=\"_inc/pages/_gfx/icons/go-up-transparent.png\" alt=\"Flytt opp\" />\n";
				}
				else{
					echo"<a href=\"index.php?open=$open&amp;page=navigation&amp;action=move_up&amp;id=$get_navigation_id&amp;editor_language=$editor_language&amp;process=1\"><img src=\"_inc/pages/_gfx/icons/go-up.png\" alt=\"$l_up\" /></a>\n";
				}
				echo"<a href=\"index.php?open=$open&amp;page=navigation&amp;action=move_down&amp;id=$get_navigation_id&amp;editor_language=$editor_language&amp;process=1\"><img src=\"_inc/pages/_gfx/icons/go-down.png\" alt=\"$l_down\" /></a>\n";
				echo"	
				<a href=\"index.php?open=$open&amp;page=navigation&amp;action=edit&amp;id=$get_navigation_id&amp;editor_language=$editor_language\"><img src=\"_inc/pages/_gfx/icons/format-justify-left.png\" alt=\"$l_edit\" /></a>
				<a href=\"index.php?open=$open&amp;page=navigation&amp;action=delete&amp;id=$get_navigation_id&amp;editor_language=$editor_language&amp;process=1\" onClick=\"return confirmDelete$get_navigation_id();\"><img src=\"_inc/pages/_gfx/icons/delete.png\" alt=\"$l_delete\" /></a>
			</span>
		  </td>
     		 </tr>";

		// Children lvel 1
		$query_b = "SELECT navigation_id, navigation_parent_id, navigation_title, navigation_url, navigation_weight FROM $t_pages_navigation WHERE navigation_parent_id=$get_navigation_id AND navigation_language=$editor_language_mysql ORDER BY navigation_weight ASC";
		$result_b = mysqli_query($link, $query_b);
		while($row_b = mysqli_fetch_row($result_b)) {
			list($get_b_navigation_id, $get_b_navigation_parent_id, $get_b_navigation_title, $get_b_navigation_url, $get_b_navigation_weight) = $row_b;

			// Style
			if(isset($odd) && $odd == false){
				$odd = true;
			}
			else{
				$odd = false;
			}

			echo"
			 <tr>
       			  <td "; if($odd == true){ echo" class=\"odd\""; } echo" style=\"padding-left: 20px;\">
          			<span>$get_b_navigation_title</span>
			  </td>
       			  <td "; if($odd == true){ echo" class=\"odd\""; } echo" style=\"padding-left: 20px;\">
				<span><a href=\"../$get_b_navigation_url\">$get_b_navigation_url</a></span>
			  </td>
       			  <td "; if($odd == true){ echo" class=\"odd\""; } echo" style=\"padding-left: 20px;\">
				<script type=\"text/javascript\">
				function confirmDelete$get_b_navigation_id() {
					if (confirm(\"$l_are_your_sure_you_want_to_delete_the_item\")) {
						return true;
					}
					else {
						return false;
					}
				}
				</script>
				<span>
				";
				if($get_b_navigation_weight == "0"){
					echo"<img src=\"_inc/pages/_gfx/icons/go-up-transparent.png\" alt=\"Flytt opp\" />\n";
				}
				else{
					echo"<a href=\"index.php?open=$open&amp;page=navigation&amp;action=move_up&amp;id=$get_b_navigation_id&amp;editor_language=$editor_language&amp;process=1\"><img src=\"_inc/pages/_gfx/icons/go-up.png\" alt=\"$l_up\" /></a>\n";
				}
				echo"<a href=\"index.php?open=$open&amp;page=navigation&amp;action=move_down&amp;id=$get_b_navigation_id&amp;editor_language=$editor_language&amp;process=1\"><img src=\"_inc/pages/_gfx/icons/go-down.png\" alt=\"$l_down\" /></a>\n";
				echo"	
				<a href=\"index.php?open=$open&amp;page=navigation&amp;action=edit&amp;id=$get_b_navigation_id&amp;editor_language=$editor_language\"><img src=\"_inc/pages/_gfx/icons/format-justify-left.png\" alt=\"$l_edit\" /></a>
				<a href=\"index.php?open=$open&amp;page=navigation&amp;action=delete&amp;id=$get_b_navigation_id&amp;editor_language=$editor_language&amp;process=1\" onClick=\"return confirmDelete$get_navigation_id();\"><img src=\"_inc/pages/_gfx/icons/delete.png\" alt=\"$l_delete\" /></a>
				</span>
			  </td>
     			 </tr>";

			// Children level 2
			$query_c = "SELECT navigation_id, navigation_parent_id, navigation_title, navigation_url, navigation_weight FROM $t_pages_navigation WHERE navigation_parent_id=$get_b_navigation_id AND navigation_language=$editor_language_mysql ORDER BY navigation_weight ASC";
			$result_c = mysqli_query($link, $query_c);
			while($row_c = mysqli_fetch_row($result_c)) {
				list($get_c_navigation_id, $get_c_navigation_parent_id, $get_c_navigation_title, $get_c_navigation_url, $get_c_navigation_weight) = $row_c;

				// Style
				if(isset($odd) && $odd == false){
					$odd = true;
				}
				else{
					$odd = false;
				}

				echo"
				 <tr>
       				  <td "; if($odd == true){ echo" class=\"odd\""; } echo" style=\"padding-left: 40px;\">
          				<span>$get_c_navigation_title</span>
				  </td>
       				  <td "; if($odd == true){ echo" class=\"odd\""; } echo" style=\"padding-left: 40px;\">
					<span><a href=\"../$get_c_navigation_url\">$get_c_navigation_url</a></span>
			   	 </td>
       				  <td "; if($odd == true){ echo" class=\"odd\""; } echo" style=\"padding-left: 40px;\">
					<script type=\"text/javascript\">
					function confirmDelete$get_c_navigation_id() {
						if (confirm(\"$l_are_your_sure_you_want_to_delete_the_item\")) {
							return true;
						}
						else {
							return false;
						}
					}
					</script>

					<span>
					";
					if($get_c_navigation_weight == "0"){
						echo"<img src=\"_inc/pages/_gfx/icons/go-up-transparent.png\" alt=\"Flytt opp\" />\n";
					}
					else{
						echo"<a href=\"index.php?open=$open&amp;page=navigation&amp;action=move_up&amp;id=$get_c_navigation_id&amp;editor_language=$editor_language&amp;process=1\"><img src=\"_inc/pages/_gfx/icons/go-up.png\" alt=\"$l_up\" /></a>\n";
					}
					echo"<a href=\"index.php?open=$open&amp;page=navigation&amp;action=move_down&amp;id=$get_c_navigation_id&amp;editor_language=$editor_language&amp;process=1\"><img src=\"_inc/pages/_gfx/icons/go-down.png\" alt=\"$l_down\" /></a>\n";
					echo"	
					<a href=\"index.php?open=$open&amp;page=navigation&amp;action=edit&amp;id=$get_c_navigation_id&amp;editor_language=$editor_language\"><img src=\"_inc/pages/_gfx/icons/format-justify-left.png\" alt=\"$l_edit\" /></a>
					<a href=\"index.php?open=$open&amp;page=navigation&amp;action=delete&amp;id=$get_c_navigation_id&amp;editor_language=$editor_language&amp;process=1\" onClick=\"return confirmDelete$get_navigation_id();\"><img src=\"_inc/pages/_gfx/icons/delete.png\" alt=\"$l_delete\" /></a>
					</span>
				  </td>
     				 </tr>";
				} // select children level 2
			} // select children level 1

		} // Select
			echo"
		 </tbody>
		</table>
	<!-- //Navigation list -->
	";
}
elseif($action == "new"){
	if($process == "1"){

		$inp_language = $_POST['inp_language'];
		$inp_language = output_html($inp_language);
		$inp_language_mysql = quote_smart($link, $inp_language);
		$editor_language = $inp_language;
		
		// Transfer language 
		$language = "$inp_language";

		$inp_title = $_POST['inp_title'];
		$inp_title = output_html($inp_title);
		$inp_title_mysql = quote_smart($link, $inp_title);

		if($inp_title == ""){
			header("Location: index.php?open=$open&page=navigation&action=new&focus=inp_name&ft=warning&fm=please_enter_a_title&editor_language=$editor_language");
			exit;
		}

		$inp_slug = clean($inp_title);
		$inp_slug = output_html($inp_slug);
		$inp_slug_mysql = quote_smart($link, $inp_slug);


		$inp_url = $_POST['inp_url'];
		$inp_url = output_html($inp_url);
		$inp_url_mysql = quote_smart($link, $inp_url);

		$inp_url_parsed = parse_url($inp_url);
		$inp_url_scheme = "";
		$inp_url_host = "";
		if(isset($inp_url_parsed['scheme']) && isset($inp_url_parsed['host'])){
			$inp_url_scheme = $inp_url_parsed['scheme'];
			$inp_url_host = $inp_url_parsed['host'];
		}
		$inp_url_path = $inp_url_parsed['path'];
		if(isset($inp_url_parsed['query'])){
			$inp_url_query = $inp_url_parsed['query'];
		}
		else{
			$inp_url_query = "";
		}
		
		if($inp_url_query != ""){
			$inp_url_query = "?" . $inp_url_query;
		}
		
		if($inp_url_scheme == "http" OR $inp_url_scheme == "https"){
			$inp_url_path = "$inp_url_scheme://$inp_url_host$inp_url_path";
			$inp_url_query = "$inp_url_query";
			$inp_internal_or_external = "external";
		}
		else{
			$inp_internal_or_external = "internal";
		}
		$inp_url_path = output_html($inp_url_path);
		$inp_url_path_mysql = quote_smart($link, $inp_url_path);

		$inp_url_path_md5 = md5($inp_url_path);
		$inp_url_path_md5_mysql = quote_smart($link, $inp_url_path_md5);

		$inp_url_query = output_html($inp_url_query);
		$inp_url_query_mysql = quote_smart($link, $inp_url_query);

		$inp_parent = $_POST['inp_parent'];
		$inp_parent = output_html($inp_parent);
		$inp_parent_mysql = quote_smart($link, $inp_parent);

		$datetime = date("Y-m-d H:i:s");


		$inp_created_by_user_id = $_SESSION['admin_user_id'];
		$inp_created_by_user_id = output_html($inp_created_by_user_id);
		$inp_created_by_user_id_mysql = quote_smart($link, $inp_created_by_user_id);

		// Get weight
		$query = "SELECT count(*) FROM $t_pages_navigation WHERE navigation_parent_id=$inp_parent_mysql AND navigation_language=$inp_language_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_count_rows) = $row;

		// Insert
		mysqli_query($link, "INSERT INTO $t_pages_navigation 
		(navigation_id, navigation_parent_id, navigation_title, navigation_title_clean, navigation_url, 
		navigation_url_path, navigation_url_path_md5, navigation_url_query, navigation_language, navigation_internal_or_external, 
		navigation_weight, navigation_created_datetime, navigation_created_by_user_id) 
		VALUES 
		(NULL, $inp_parent_mysql, $inp_title_mysql, $inp_slug_mysql, $inp_url_mysql, 
		$inp_url_path_mysql, $inp_url_path_md5_mysql, $inp_url_query_mysql, $inp_language_mysql, '$inp_internal_or_external', 
		'$get_count_rows', '$datetime', $inp_created_by_user_id_mysql)")
		or die(mysqli_error($link));

		// Get ID
		$query = "SELECT navigation_id FROM $t_pages_navigation WHERE navigation_created_datetime='$datetime'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_navigation_id) = $row;

		// Dir
		$upload_path = "../_uploads/pages/navigation/$inp_language";
		if(!(is_dir("../_uploads"))){
			mkdir("../_uploads");
		}
		if(!(is_dir("../_uploads/pages"))){
			mkdir("../_uploads/pages");
		}
		if(!(is_dir("../_uploads/pages/navigation"))){
			mkdir("../_uploads/pages/navigation");
		}
		if(!(is_dir("../_uploads/pages/navigation/$inp_language"))){
			mkdir("../_uploads/pages/navigation/$inp_language");
		}

		// Upload icon
		$icons_size_array = array("18x18", "24x24");
		$icons_types_array = array("inactive", "hover", "active");
		for($x=0;$x<sizeof($icons_size_array);$x++){
			for($y=0;$y<sizeof($icons_types_array);$y++){

				// Name (inp_icon_18x18_inactive)
				$file_name = basename($_FILES["inp_icon_" . $icons_size_array[$x] . "_" . $icons_types_array[$y]]['name']);
				$file_exp = explode('.', $file_name); 
				$file_type = $file_exp[count($file_exp) -1]; 
				$file_type = strtolower("$file_type");

				// New name
				$new_name = $inp_slug . "_" . $icons_size_array[$x] . "_" . $icons_types_array[$y] . "." . $file_type;

				// Sjekk om det er en OK filendelse
				if($file_type == "jpg" OR $file_type == "jpeg" OR $file_type == "png" OR $file_type == "gif"){
					if(move_uploaded_file($_FILES["inp_icon_" . $icons_size_array[$x] . "_" . $icons_types_array[$y]]['tmp_name'], "$upload_path/$new_name")) {
						
						// Sjekk om det faktisk er et bilde som er lastet opp
						list($width,$height) = getimagesize("$upload_path/$new_name");
						if(is_numeric($width) && is_numeric($height)){
							// Update MySQL

							// path
							$inp_path = "_uploads/pages/navigation/$inp_language";
							$inp_path_mysql = quote_smart($link, $inp_path);

							// icon
							$inp_icon = $new_name;
							$inp_icon_mysql = quote_smart($link, $inp_icon);
							

							// Update MySQL
							$result = mysqli_query($link, "UPDATE $t_pages_navigation SET 
											navigation_icon_path=$inp_path_mysql, 
											navigation_icon_$icons_size_array[$x]" . "_" . "$icons_types_array[$y]=$inp_icon_mysql
											WHERE navigation_id=$get_current_navigation_id") or die(mysqli_error($link));
						}
						else{
							// Not a image
							unlink("$upload_path/$new_name");
						}
					}
					else{
						// Could not upload
					}
				}
				else{
					// Wrong file type
				}


			}  // for icons type
		} // for icons size
	

		header("Location: index.php?open=$open&page=navigation&action=new&focus=inp_name&ft=success&fm=menu_item_created&editor_language=$editor_language");
		exit;
	}
	echo"
	<h1>$l_new_menu_item</h1>

	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l&amp;editor_language=$editor_language\">Navigation</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;l=$l&amp;editor_language=$editor_language\">New</a>
		</p>
	<!-- //Where am I? -->

	<form method=\"post\" action=\"?open=$open&amp;page=navigation&amp;action=new&amp;process=1&amp;editor_language=$editor_language\" enctype=\"multipart/form-data\">
				
	
	<!-- Feedback -->
	";
	if($ft != ""){
		if($fm == "please_enter_a_title"){
			$fm = "$l_please_enter_a_title";
		}
		elseif($fm == "please_enter_url"){
			$fm = "$l_please_enter_url";
		}
		elseif($fm == "menu_item_created"){
			$fm = "$l_menu_item_created";
		}
		else{

		}
		echo"<div class=\"$ft\"><span>$fm</span></div>";
	}
	echo"	
	<!-- //Feedback -->



	<!-- Focus -->
	<script>
	\$(document).ready(function(){
		\$('[name=\"inp_title\"]').focus();
	});
	</script>
	<!-- //Focus -->


	<p><b>$l_language</b>*<br />
	<select name=\"inp_language\" tabindex=\"";$tabindex=0; $tabindex=$tabindex+1;echo"$tabindex\" />";
		
	$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;

		$flag_path 	= "_design/gfx/flags/16x16/$get_language_active_flag_16x16" . "_16x16.png";


		echo"	<option value=\"$get_language_active_iso_two\"";if($editor_language == "$get_language_active_iso_two"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";
		
	}
	echo"
	</select>
	</p>

	<p><b>$l_title</b>*<br />
	<input type=\"text\" name=\"inp_title\" value=\"\" size=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
	</p>
	
	<p><b>$l_url</b>*:<br />
	<input type=\"text\" name=\"inp_url\" value=\"\" size=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
	</p>

	<p><b>$l_parent</b>*<br />
	<select name=\"inp_parent\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">
		<option value=\"0\" selected=\"selected\">$l_this_is_parent</option>
		<option value=\"0\">-</option>";
		
		$editor_language_mysql = quote_smart($link, $editor_language);
		$query = "SELECT navigation_id, navigation_title FROM $t_pages_navigation WHERE navigation_parent_id='0' AND navigation_language=$editor_language_mysql";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_navigation_id, $get_navigation_title) = $row;

			echo"		<option value=\"$get_navigation_id\">$get_navigation_title</option>\n";

			// Sub
			$query_b = "SELECT navigation_id, navigation_title FROM $t_pages_navigation WHERE navigation_parent_id='$get_navigation_id' AND navigation_editor_language=$editor_language_mysql";
			$result_b = mysqli_query($link, $query_b);
			while($row_b = mysqli_fetch_row($result_b)) {
				list($get_b_navigation_id, $get_b_navigation_title) = $row_b;
				echo"		<option value=\"$get_b_navigation_id\">&nbsp; $get_b_navigation_title</option>\n";

			}
		}
		echo"
	</select>
	</p>

	<!- icons -->
	";
	$icons_size_array = array("18x18", "24x24");
	$icons_types_array = array("inactive", "hover", "active");
	for($x=0;$x<sizeof($icons_size_array);$x++){
		echo"
		<hr />
		";
		for($y=0;$y<sizeof($icons_types_array);$y++){

			// Name (inp_icon_18x18_inactive)
			$inp_name = "inp_icon_" . $icons_size_array[$x] . "_" . $icons_types_array[$y];

			echo"
			<p><b>Icon $icons_size_array[$x] $icons_types_array[$y]:</b><br />
			<input type=\"file\" name=\"$inp_name\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>";
		}
	}
	echo"



	<p><input type=\"submit\" value=\"$l_create\" class=\"btn btn-success btn-sm\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
	 
	</form>


	<!-- Back -->
		<table>
		 <tr>
		  <td style=\"padding-right: 4px;\">
			<p>
			<a href=\"index.php?open=$open&amp;page=navigation\"><img src=\"_inc/pages/_gfx/icons/go-previous.png\" alt=\"\" /></a>
			</p>
		  </td>
		  <td>
			<p>
			<a href=\"index.php?open=$open&amp;page=navigation&amp;editor_language=$editor_language\">$l_go_back</a>
			</p>
		  </td>
		 </tr>
		</table>
	<!-- //Back -->
	";

}
elseif($action == "new_auto_insert"){
	
	$module = $_GET['module'];
	$module = output_html($module);
	if($module == "blog"){
		// Fetch all languages
		$query_l = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
		$result_l = mysqli_query($link, $query_l);
		while($row_l = mysqli_fetch_row($result_l)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row_l;

			// Blog titles
			$t_blog_titles 		= $mysqlPrefixSav . "blog_titles";
			$language_mysql = quote_smart($link, $get_language_active_iso_two);
			$query_t = "SELECT title_id, title_language, title_value FROM $t_blog_titles WHERE title_language=$language_mysql";
			$result_t = mysqli_query($link, $query_t);
			$row_t = mysqli_fetch_row($result_t);
			list($get_title_id, $get_title_language, $get_title_value) = $row_t;
			if($get_title_id == ""){
				mysqli_query($link, "INSERT INTO $t_blog_titles
				(title_id, title_language, title_value) 
				VALUES 
				(NULL, $language_mysql, 'Blog')
				") or die(mysqli_error($link));
				$get_title_value = "Blog";
			}
			
			// Values for navigation
			$inp_title = "$get_title_value";
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			$inp_slug = clean($inp_title);
			$inp_slug = output_html($inp_slug);
			$inp_slug_mysql = quote_smart($link, $inp_slug);
			
			$inp_url = "blog/index.php?l=$get_language_active_iso_two";
			$inp_url = output_html($inp_url);
			$inp_url_mysql = quote_smart($link, $inp_url);


			$inp_url_parsed = parse_url($inp_url);
			$inp_url_scheme = "";
			$inp_url_host = "";
			if(isset($inp_url_parsed['scheme']) && isset($inp_url_parsed['host'])){
				$inp_url_scheme = $inp_url_parsed['scheme'];
				$inp_url_host = $inp_url_parsed['host'];
			}
			$inp_url_path = $inp_url_parsed['path'];
			if(isset($inp_url_parsed['query'])){
				$inp_url_query = $inp_url_parsed['query'];
			}
			else{
				$inp_url_query = "";
			}
				
			if($inp_url_query != ""){
				$inp_url_query = "?" . $inp_url_query;
			}
		
			if($inp_url_scheme == "http" OR $inp_url_scheme == "https"){
				$inp_url_path = "$inp_url_scheme://$inp_url_host$inp_url_path";
				$inp_url_query = "$inp_url_query";
				$inp_internal_or_external = "external";
			}
			else{
				$inp_internal_or_external = "internal";
			}
			$inp_url_path = output_html($inp_url_path);
			$inp_url_path_mysql = quote_smart($link, $inp_url_path);

			$inp_url_path_md5 = md5($inp_url_path);
			$inp_url_path_md5_mysql = quote_smart($link, $inp_url_path_md5);

			$inp_url_query = output_html($inp_url_query);
			$inp_url_query_mysql = quote_smart($link, $inp_url_query);

			$inp_parent = 0;
			$inp_parent = output_html($inp_parent);
			$inp_parent_mysql = quote_smart($link, $inp_parent);

			$datetime = date("Y-m-d H:i:s");


			$inp_created_by_user_id = $_SESSION['admin_user_id'];
			$inp_created_by_user_id = output_html($inp_created_by_user_id);
			$inp_created_by_user_id_mysql = quote_smart($link, $inp_created_by_user_id);

			// Get weight
			$query = "SELECT count(*) FROM $t_pages_navigation WHERE navigation_parent_id=$inp_parent_mysql AND navigation_language=$language_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_count_rows) = $row;

			// Insert
			mysqli_query($link, "INSERT INTO $t_pages_navigation 
			(navigation_id, navigation_parent_id, navigation_title, navigation_title_clean, navigation_url, 
			navigation_url_path, navigation_url_path_md5, navigation_url_query, navigation_language, navigation_internal_or_external, 
			navigation_weight, navigation_created_datetime, navigation_created_by_user_id) 
			VALUES 
			(NULL, $inp_parent_mysql, $inp_title_mysql, $inp_slug_mysql, $inp_url_mysql, 
			$inp_url_path_mysql, $inp_url_path_md5_mysql, $inp_url_query_mysql, $language_mysql, '$inp_internal_or_external', 
			'$get_count_rows', '$datetime', $inp_created_by_user_id_mysql)")
			or die(mysqli_error($link));
			

		} // while languages

		$url = "index.php?open=blog&editor_language=$editor_language&l=$l&ft=success&fm=inserted_to_navigation";
		header("Location: $url");
		exit;
	
	} // module = blog
	elseif($module == "food_diary"){
		// Fetch all languages
		$query_l = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
		$result_l = mysqli_query($link, $query_l);
		while($row_l = mysqli_fetch_row($result_l)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row_l;

			// Food diary title
			include("_translations/site/$get_language_active_iso_two/food_diary/ts_index.php");
			$inp_title = "$l_food_diary";
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			$inp_slug = clean($inp_title);
			$inp_slug = output_html($inp_slug);
			$inp_slug_mysql = quote_smart($link, $inp_slug);
			
			$inp_url = "food_diary/index.php?l=$get_language_active_iso_two";
			$inp_url = output_html($inp_url);
			$inp_url_mysql = quote_smart($link, $inp_url);


			$inp_url_parsed = parse_url($inp_url);
			$inp_url_scheme = "";
			$inp_url_host = "";
			if(isset($inp_url_parsed['scheme']) && isset($inp_url_parsed['host'])){
				$inp_url_scheme = $inp_url_parsed['scheme'];
				$inp_url_host = $inp_url_parsed['host'];
			}
			$inp_url_path = $inp_url_parsed['path'];
			if(isset($inp_url_parsed['query'])){
				$inp_url_query = $inp_url_parsed['query'];
			}
			else{
				$inp_url_query = "";
			}
				
			if($inp_url_query != ""){
				$inp_url_query = "?" . $inp_url_query;
			}
		
			if($inp_url_scheme == "http" OR $inp_url_scheme == "https"){
				$inp_url_path = "$inp_url_scheme://$inp_url_host$inp_url_path";
				$inp_url_query = "$inp_url_query";
				$inp_internal_or_external = "external";
			}
			else{
				$inp_internal_or_external = "internal";
			}
			$inp_url_path = output_html($inp_url_path);
			$inp_url_path_mysql = quote_smart($link, $inp_url_path);

			$inp_url_path_md5 = md5($inp_url_path);
			$inp_url_path_md5_mysql = quote_smart($link, $inp_url_path_md5);

			$inp_url_query = output_html($inp_url_query);
			$inp_url_query_mysql = quote_smart($link, $inp_url_query);

			$inp_parent = 0;
			$inp_parent = output_html($inp_parent);
			$inp_parent_mysql = quote_smart($link, $inp_parent);

			$datetime = date("Y-m-d H:i:s");

			$inp_created_by_user_id = $_SESSION['admin_user_id'];
			$inp_created_by_user_id = output_html($inp_created_by_user_id);
			$inp_created_by_user_id_mysql = quote_smart($link, $inp_created_by_user_id);

			// Get weight
			$language_mysql = quote_smart($link, $get_language_active_iso_two);
			$query = "SELECT count(*) FROM $t_pages_navigation WHERE navigation_parent_id=$inp_parent_mysql AND navigation_language=$language_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_count_rows) = $row;

			// Insert
			mysqli_query($link, "INSERT INTO $t_pages_navigation 
			(navigation_id, navigation_parent_id, navigation_title, navigation_title_clean, navigation_url, 
			navigation_url_path, navigation_url_path_md5, navigation_url_query, navigation_language, navigation_internal_or_external, 
			navigation_weight, navigation_created_datetime, navigation_created_by_user_id) 
			VALUES 
			(NULL, $inp_parent_mysql, $inp_title_mysql, $inp_slug_mysql, $inp_url_mysql, 
			$inp_url_path_mysql, $inp_url_path_md5_mysql, $inp_url_query_mysql, $language_mysql, '$inp_internal_or_external', 
			'$get_count_rows', '$datetime', $inp_created_by_user_id_mysql)")
			or die(mysqli_error($link));
			

		} // while languages

		$url = "index.php?open=$module&editor_language=$editor_language&l=$l&ft=success&fm=inserted_to_navigation";
		header("Location: $url");
		exit;
	
	} // module = food_diary
	elseif($module == "chat"){
		// Title
		include("_data/chat.php");

		// Fetch all languages
		$query_l = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
		$result_l = mysqli_query($link, $query_l);
		while($row_l = mysqli_fetch_row($result_l)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row_l;

			$inp_title = "$chatTitleSav";
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			$inp_slug = clean($inp_title);
			$inp_slug = output_html($inp_slug);
			$inp_slug_mysql = quote_smart($link, $inp_slug);
			
			$inp_url = "chat/index.php?l=$get_language_active_iso_two";
			$inp_url = output_html($inp_url);
			$inp_url_mysql = quote_smart($link, $inp_url);


			$inp_url_parsed = parse_url($inp_url);
			$inp_url_scheme = "";
			$inp_url_host = "";
			if(isset($inp_url_parsed['scheme']) && isset($inp_url_parsed['host'])){
				$inp_url_scheme = $inp_url_parsed['scheme'];
				$inp_url_host = $inp_url_parsed['host'];
			}
			$inp_url_path = $inp_url_parsed['path'];
			if(isset($inp_url_parsed['query'])){
				$inp_url_query = $inp_url_parsed['query'];
			}
			else{
				$inp_url_query = "";
			}
				
			if($inp_url_query != ""){
				$inp_url_query = "?" . $inp_url_query;
			}
		
			if($inp_url_scheme == "http" OR $inp_url_scheme == "https"){
				$inp_url_path = "$inp_url_scheme://$inp_url_host$inp_url_path";
				$inp_url_query = "$inp_url_query";
				$inp_internal_or_external = "external";
			}
			else{
				$inp_internal_or_external = "internal";
			}
			$inp_url_path = output_html($inp_url_path);
			$inp_url_path_mysql = quote_smart($link, $inp_url_path);

			$inp_url_path_md5 = md5($inp_url_path);
			$inp_url_path_md5_mysql = quote_smart($link, $inp_url_path_md5);

			$inp_url_query = output_html($inp_url_query);
			$inp_url_query_mysql = quote_smart($link, $inp_url_query);

			$inp_parent = 0;
			$inp_parent = output_html($inp_parent);
			$inp_parent_mysql = quote_smart($link, $inp_parent);

			$datetime = date("Y-m-d H:i:s");

			$inp_created_by_user_id = $_SESSION['admin_user_id'];
			$inp_created_by_user_id = output_html($inp_created_by_user_id);
			$inp_created_by_user_id_mysql = quote_smart($link, $inp_created_by_user_id);

			// Get weight
			$language_mysql = quote_smart($link, $get_language_active_iso_two);
			$query = "SELECT count(*) FROM $t_pages_navigation WHERE navigation_parent_id=$inp_parent_mysql AND navigation_language=$language_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_count_rows) = $row;

			// Insert
			mysqli_query($link, "INSERT INTO $t_pages_navigation 
			(navigation_id, navigation_parent_id, navigation_title, navigation_title_clean, navigation_url, 
			navigation_url_path, navigation_url_path_md5, navigation_url_query, navigation_language, navigation_internal_or_external, 
			navigation_weight, navigation_created_datetime, navigation_created_by_user_id) 
			VALUES 
			(NULL, $inp_parent_mysql, $inp_title_mysql, $inp_slug_mysql, $inp_url_mysql, 
			$inp_url_path_mysql, $inp_url_path_md5_mysql, $inp_url_query_mysql, $language_mysql, '$inp_internal_or_external', 
			'$get_count_rows', '$datetime', $inp_created_by_user_id_mysql)")
			or die(mysqli_error($link));
			

		} // while languages

		$url = "index.php?open=$module&editor_language=$editor_language&l=$l&ft=success&fm=inserted_to_navigation";
		header("Location: $url");
		exit;
	
	} // module = chat
	elseif($module == "contact_forms"){
		$t_contact_forms_index			= $mysqlPrefixSav . "contact_forms_index";


		// Fetch all languages
		$query_l = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
		$result_l = mysqli_query($link, $query_l);
		while($row_l = mysqli_fetch_row($result_l)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row_l;

			// Fetch all forms
			$l_mysql = quote_smart($link, $get_language_active_iso_two);
			$query_f = "SELECT form_id, form_title, form_language, form_mail_to, form_text_before_form, form_text_left_of_form, form_text_right_of_form, form_text_after_form, form_created_datetime, form_created_by_user_id, form_updated_datetime, form_updated_by_user_id, form_api_avaible, form_ipblock, form_used_times FROM $t_contact_forms_index WHERE form_language=$l_mysql";
			$result_f = mysqli_query($link, $query_f);
			while($row_f = mysqli_fetch_row($result_f)) {
				list($get_form_id, $get_form_title, $get_form_language, $get_form_mail_to, $get_form_text_before_form, $get_form_text_left_of_form, $get_form_text_right_of_form, $get_form_text_after_form, $get_form_created_datetime, $get_form_created_by_user_id, $get_form_updated_datetime, $get_form_updated_by_user_id, $get_form_api_avaible, $get_form_ipblock, $get_form_used_times) = $row_f;


				$inp_title = "$get_form_title";
				$inp_title = output_html($inp_title);
				$inp_title_mysql = quote_smart($link, $inp_title);

				$inp_slug = clean($inp_title);
				$inp_slug = output_html($inp_slug);
				$inp_slug_mysql = quote_smart($link, $inp_slug);
			
				$inp_url = "contact_forms/view_form.php?form_id=$get_form_id&amp;l=$get_language_active_iso_two";
				$inp_url = output_html($inp_url);
				$inp_url_mysql = quote_smart($link, $inp_url);


				$inp_url_parsed = parse_url($inp_url);
				$inp_url_scheme = "";
				$inp_url_host = "";
				if(isset($inp_url_parsed['scheme']) && isset($inp_url_parsed['host'])){
					$inp_url_scheme = $inp_url_parsed['scheme'];
					$inp_url_host = $inp_url_parsed['host'];
				}
				$inp_url_path = $inp_url_parsed['path'];
				if(isset($inp_url_parsed['query'])){
					$inp_url_query = $inp_url_parsed['query'];
				}
				else{
					$inp_url_query = "";
				}
				
				if($inp_url_query != ""){
					$inp_url_query = "?" . $inp_url_query;
				}
		
				if($inp_url_scheme == "http" OR $inp_url_scheme == "https"){
					$inp_url_path = "$inp_url_scheme://$inp_url_host$inp_url_path";
					$inp_url_query = "$inp_url_query";
					$inp_internal_or_external = "external";
				}
				else{
					$inp_internal_or_external = "internal";
				}
				$inp_url_path = output_html($inp_url_path);
				$inp_url_path_mysql = quote_smart($link, $inp_url_path);

				$inp_url_path_md5 = md5($inp_url_path);
				$inp_url_path_md5_mysql = quote_smart($link, $inp_url_path_md5);

				$inp_url_query = output_html($inp_url_query);
				$inp_url_query_mysql = quote_smart($link, $inp_url_query);

				$inp_parent = 0;
				$inp_parent = output_html($inp_parent);
				$inp_parent_mysql = quote_smart($link, $inp_parent);

				$datetime = date("Y-m-d H:i:s");

				$inp_created_by_user_id = $_SESSION['admin_user_id'];
				$inp_created_by_user_id = output_html($inp_created_by_user_id);
				$inp_created_by_user_id_mysql = quote_smart($link, $inp_created_by_user_id);

				// Get weight
				$language_mysql = quote_smart($link, $get_language_active_iso_two);
				$query = "SELECT count(*) FROM $t_pages_navigation WHERE navigation_parent_id=$inp_parent_mysql AND navigation_language=$language_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_count_rows) = $row;

				// Insert
				mysqli_query($link, "INSERT INTO $t_pages_navigation 
				(navigation_id, navigation_parent_id, navigation_title, navigation_title_clean, navigation_url, 
				navigation_url_path, navigation_url_path_md5, navigation_url_query, navigation_language, navigation_internal_or_external, 
				navigation_weight, navigation_created_datetime, navigation_created_by_user_id) 
				VALUES 
				(NULL, $inp_parent_mysql, $inp_title_mysql, $inp_slug_mysql, $inp_url_mysql, 
				$inp_url_path_mysql, $inp_url_path_md5_mysql, $inp_url_query_mysql, $language_mysql, '$inp_internal_or_external', 
				'$get_count_rows', '$datetime', $inp_created_by_user_id_mysql)")
				or die(mysqli_error($link));

			} // list forms

		} // while languages

		$url = "index.php?open=$module&editor_language=$editor_language&l=$l&ft=success&fm=inserted_to_navigation";
		header("Location: $url");
		exit;
	} // module = contact_forms
	elseif($module == "courses"){
		// Fetch all languages
		$query_l = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
		$result_l = mysqli_query($link, $query_l);
		while($row_l = mysqli_fetch_row($result_l)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row_l;

			// Food diary title
			include("_translations/site/$get_language_active_iso_two/courses/ts_index.php");
			$inp_title = "$l_courses";
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			$inp_slug = clean($inp_title);
			$inp_slug = output_html($inp_slug);
			$inp_slug_mysql = quote_smart($link, $inp_slug);
			
			$inp_url = "courses/index.php?l=$get_language_active_iso_two";
			$inp_url = output_html($inp_url);
			$inp_url_mysql = quote_smart($link, $inp_url);


			$inp_url_parsed = parse_url($inp_url);
			$inp_url_scheme = "";
			$inp_url_host = "";
			if(isset($inp_url_parsed['scheme']) && isset($inp_url_parsed['host'])){
				$inp_url_scheme = $inp_url_parsed['scheme'];
				$inp_url_host = $inp_url_parsed['host'];
			}
			$inp_url_path = $inp_url_parsed['path'];
			if(isset($inp_url_parsed['query'])){
				$inp_url_query = $inp_url_parsed['query'];
			}
			else{
				$inp_url_query = "";
			}
				
			if($inp_url_query != ""){
				$inp_url_query = "?" . $inp_url_query;
			}
		
			if($inp_url_scheme == "http" OR $inp_url_scheme == "https"){
				$inp_url_path = "$inp_url_scheme://$inp_url_host$inp_url_path";
				$inp_url_query = "$inp_url_query";
				$inp_internal_or_external = "external";
			}
			else{
				$inp_internal_or_external = "internal";
			}
			$inp_url_path = output_html($inp_url_path);
			$inp_url_path_mysql = quote_smart($link, $inp_url_path);

			$inp_url_path_md5 = md5($inp_url_path);
			$inp_url_path_md5_mysql = quote_smart($link, $inp_url_path_md5);

			$inp_url_query = output_html($inp_url_query);
			$inp_url_query_mysql = quote_smart($link, $inp_url_query);

			$inp_parent = 0;
			$inp_parent = output_html($inp_parent);
			$inp_parent_mysql = quote_smart($link, $inp_parent);

			$datetime = date("Y-m-d H:i:s");

			$inp_created_by_user_id = $_SESSION['admin_user_id'];
			$inp_created_by_user_id = output_html($inp_created_by_user_id);
			$inp_created_by_user_id_mysql = quote_smart($link, $inp_created_by_user_id);

			// Get weight
			$language_mysql = quote_smart($link, $get_language_active_iso_two);
			$query = "SELECT count(*) FROM $t_pages_navigation WHERE navigation_parent_id=$inp_parent_mysql AND navigation_language=$language_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_count_rows) = $row;

			// Insert
			mysqli_query($link, "INSERT INTO $t_pages_navigation 
			(navigation_id, navigation_parent_id, navigation_title, navigation_title_clean, navigation_url, 
			navigation_url_path, navigation_url_path_md5, navigation_url_query, navigation_language, navigation_internal_or_external, 
			navigation_weight, navigation_created_datetime, navigation_created_by_user_id) 
			VALUES 
			(NULL, $inp_parent_mysql, $inp_title_mysql, $inp_slug_mysql, $inp_url_mysql, 
			$inp_url_path_mysql, $inp_url_path_md5_mysql, $inp_url_query_mysql, $language_mysql, '$inp_internal_or_external', 
			'$get_count_rows', '$datetime', $inp_created_by_user_id_mysql)")
			or die(mysqli_error($link));
			

		} // while languages

		$url = "index.php?open=$module&editor_language=$editor_language&l=$l&ft=success&fm=inserted_to_navigation";
		header("Location: $url");
		exit;
	
	} // module = courses
	elseif($module == "downloads"){
		// Fetch all languages
		$query_l = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
		$result_l = mysqli_query($link, $query_l);
		while($row_l = mysqli_fetch_row($result_l)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row_l;

			// Food diary title
			include("_translations/site/$get_language_active_iso_two/downloads/ts_index.php");
			$inp_title = "$l_downloads";
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			$inp_slug = clean($inp_title);
			$inp_slug = output_html($inp_slug);
			$inp_slug_mysql = quote_smart($link, $inp_slug);
			
			$inp_url = "downloads/index.php?l=$get_language_active_iso_two";
			$inp_url = output_html($inp_url);
			$inp_url_mysql = quote_smart($link, $inp_url);


			$inp_url_parsed = parse_url($inp_url);
			$inp_url_scheme = "";
			$inp_url_host = "";
			if(isset($inp_url_parsed['scheme']) && isset($inp_url_parsed['host'])){
				$inp_url_scheme = $inp_url_parsed['scheme'];
				$inp_url_host = $inp_url_parsed['host'];
			}
			$inp_url_path = $inp_url_parsed['path'];
			if(isset($inp_url_parsed['query'])){
				$inp_url_query = $inp_url_parsed['query'];
			}
			else{
				$inp_url_query = "";
			}
				
			if($inp_url_query != ""){
				$inp_url_query = "?" . $inp_url_query;
			}
		
			if($inp_url_scheme == "http" OR $inp_url_scheme == "https"){
				$inp_url_path = "$inp_url_scheme://$inp_url_host$inp_url_path";
				$inp_url_query = "$inp_url_query";
				$inp_internal_or_external = "external";
			}
			else{
				$inp_internal_or_external = "internal";
			}
			$inp_url_path = output_html($inp_url_path);
			$inp_url_path_mysql = quote_smart($link, $inp_url_path);

			$inp_url_path_md5 = md5($inp_url_path);
			$inp_url_path_md5_mysql = quote_smart($link, $inp_url_path_md5);

			$inp_url_query = output_html($inp_url_query);
			$inp_url_query_mysql = quote_smart($link, $inp_url_query);

			$inp_parent = 0;
			$inp_parent = output_html($inp_parent);
			$inp_parent_mysql = quote_smart($link, $inp_parent);

			$datetime = date("Y-m-d H:i:s");

			$inp_created_by_user_id = $_SESSION['admin_user_id'];
			$inp_created_by_user_id = output_html($inp_created_by_user_id);
			$inp_created_by_user_id_mysql = quote_smart($link, $inp_created_by_user_id);

			// Get weight
			$language_mysql = quote_smart($link, $get_language_active_iso_two);
			$query = "SELECT count(*) FROM $t_pages_navigation WHERE navigation_parent_id=$inp_parent_mysql AND navigation_language=$language_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_count_rows) = $row;

			// Insert
			mysqli_query($link, "INSERT INTO $t_pages_navigation 
			(navigation_id, navigation_parent_id, navigation_title, navigation_title_clean, navigation_url, 
			navigation_url_path, navigation_url_path_md5, navigation_url_query, navigation_language, navigation_internal_or_external, 
			navigation_weight, navigation_created_datetime, navigation_created_by_user_id) 
			VALUES 
			(NULL, $inp_parent_mysql, $inp_title_mysql, $inp_slug_mysql, $inp_url_mysql, 
			$inp_url_path_mysql, $inp_url_path_md5_mysql, $inp_url_query_mysql, $language_mysql, '$inp_internal_or_external', 
			'$get_count_rows', '$datetime', $inp_created_by_user_id_mysql)")
			or die(mysqli_error($link));
			

		} // while languages

		$url = "index.php?open=$module&editor_language=$editor_language&l=$l&ft=success&fm=inserted_downloads_to_navigation";
		header("Location: $url");
		exit;
	
	} // module = downloads
	elseif($module == "exercises"){
		$t_exercise_types	       = $mysqlPrefixSav . "exercise_types";
		$t_exercise_types_translations = $mysqlPrefixSav . "exercise_types_translations";
		$t_muscle_groups 			= $mysqlPrefixSav . "muscle_groups";
		$t_muscle_groups_translations	 	= $mysqlPrefixSav . "muscle_groups_translations";

		// Fetch all languages
		$query_l = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
		$result_l = mysqli_query($link, $query_l);
		while($row_l = mysqli_fetch_row($result_l)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row_l;

			// Food diary title
			include("_translations/site/$get_language_active_iso_two/exercises/ts_index.php");
			$inp_title = "$l_exercises";
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			$inp_slug = clean($inp_title);
			$inp_slug = output_html($inp_slug);
			$inp_slug_mysql = quote_smart($link, $inp_slug);
			
			$inp_url = "exercises/index.php?l=$get_language_active_iso_two";
			$inp_url = output_html($inp_url);
			$inp_url_mysql = quote_smart($link, $inp_url);


			$inp_url_parsed = parse_url($inp_url);
			$inp_url_scheme = "";
			$inp_url_host = "";
			if(isset($inp_url_parsed['scheme']) && isset($inp_url_parsed['host'])){
				$inp_url_scheme = $inp_url_parsed['scheme'];
				$inp_url_host = $inp_url_parsed['host'];
			}
			$inp_url_path = $inp_url_parsed['path'];
			if(isset($inp_url_parsed['query'])){
				$inp_url_query = $inp_url_parsed['query'];
			}
			else{
				$inp_url_query = "";
			}
				
			if($inp_url_query != ""){
				$inp_url_query = "?" . $inp_url_query;
			}
		
			if($inp_url_scheme == "http" OR $inp_url_scheme == "https"){
				$inp_url_path = "$inp_url_scheme://$inp_url_host$inp_url_path";
				$inp_url_query = "$inp_url_query";
				$inp_internal_or_external = "external";
			}
			else{
				$inp_internal_or_external = "internal";
			}
			$inp_url_path = output_html($inp_url_path);
			$inp_url_path_mysql = quote_smart($link, $inp_url_path);

			$inp_url_path_md5 = md5($inp_url_path);
			$inp_url_path_md5_mysql = quote_smart($link, $inp_url_path_md5);

			$inp_url_query = output_html($inp_url_query);
			$inp_url_query_mysql = quote_smart($link, $inp_url_query);

			$inp_parent = 0;
			$inp_parent = output_html($inp_parent);
			$inp_parent_mysql = quote_smart($link, $inp_parent);

			$datetime = date("Y-m-d H:i:s");

			$inp_created_by_user_id = $_SESSION['admin_user_id'];
			$inp_created_by_user_id = output_html($inp_created_by_user_id);
			$inp_created_by_user_id_mysql = quote_smart($link, $inp_created_by_user_id);

			// Get weight
			$language_mysql = quote_smart($link, $get_language_active_iso_two);
			$query = "SELECT count(*) FROM $t_pages_navigation WHERE navigation_parent_id=$inp_parent_mysql AND navigation_language=$language_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_count_rows) = $row;

			// Insert
			mysqli_query($link, "INSERT INTO $t_pages_navigation 
			(navigation_id, navigation_parent_id, navigation_title, navigation_title_clean, navigation_url, 
			navigation_url_path, navigation_url_path_md5, navigation_url_query, navigation_language, navigation_internal_or_external, 
			navigation_weight, navigation_created_datetime, navigation_created_by_user_id) 
			VALUES 
			(NULL, $inp_parent_mysql, $inp_title_mysql, $inp_slug_mysql, $inp_url_mysql, 
			$inp_url_path_mysql, $inp_url_path_md5_mysql, $inp_url_query_mysql, $language_mysql, '$inp_internal_or_external', 
			'$get_count_rows', '$datetime', $inp_created_by_user_id_mysql)")
			or die(mysqli_error($link));
			
			// Get ID
			$query = "SELECT navigation_id, navigation_parent_id, navigation_title, navigation_url_path, navigation_url_query, navigation_language FROM $t_pages_navigation WHERE navigation_url=$inp_url_mysql AND navigation_language=$language_mysql AND navigation_created_datetime='$datetime'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_navigation_id, $get_navigation_parent_id, $get_navigation_title, $get_navigation_url_path, $get_navigation_url_query, $get_navigation_language) = $row;


			// Insert sub menus :. Types
			$query_sub = "SELECT type_id, type_title FROM $t_exercise_types ORDER BY type_title ASC";
			$result_sub = mysqli_query($link, $query_sub);
			while($row_sub = mysqli_fetch_row($result_sub)) {
				list($get_type_id, $get_type_title) = $row_sub;

				// Translation
				$query_translation = "SELECT type_translation_id, type_translation_value FROM $t_exercise_types_translations WHERE type_id='$get_type_id' AND type_translation_language=$language_mysql";
				$result_translation = mysqli_query($link, $query_translation);
				$row_translation = mysqli_fetch_row($result_translation);
				list($get_type_translation_id, $get_type_translation_value) = $row_translation;


				$inp_title = "$get_type_translation_value";
				$inp_title_mysql = quote_smart($link, $inp_title);

				$inp_url = "exercises/view_type.php?type_id=$get_type_id&amp;l=$get_language_active_iso_two";
				$inp_url = output_html($inp_url);
				$inp_url_mysql = quote_smart($link, $inp_url);

				$inp_url_parsed = parse_url($inp_url);
				$inp_url_scheme = "";
				$inp_url_host = "";
				if(isset($inp_url_parsed['scheme']) && isset($inp_url_parsed['host'])){
					$inp_url_scheme = $inp_url_parsed['scheme'];
					$inp_url_host = $inp_url_parsed['host'];
				}
				$inp_url_path = $inp_url_parsed['path'];
				if(isset($inp_url_parsed['query'])){
					$inp_url_query = $inp_url_parsed['query'];
				}
				else	{
				$inp_url_query = "";
				}
				
				if($inp_url_query != ""){
					$inp_url_query = "?" . $inp_url_query;
				}
		
				if($inp_url_scheme == "http" OR $inp_url_scheme == "https"){
					$inp_url_path = "$inp_url_scheme://$inp_url_host$inp_url_path";
					$inp_url_query = "$inp_url_query";
					$inp_internal_or_external = "external";
				}
				else{
					$inp_internal_or_external = "internal";
				}
				$inp_url_path = output_html($inp_url_path);
				$inp_url_path_mysql = quote_smart($link, $inp_url_path);

				$inp_url_path_md5 = md5($inp_url_path);
				$inp_url_path_md5_mysql = quote_smart($link, $inp_url_path_md5);

				$inp_url_query = output_html($inp_url_query);
				$inp_url_query_mysql = quote_smart($link, $inp_url_query);

				mysqli_query($link, "INSERT INTO $t_pages_navigation 
				(navigation_id, navigation_parent_id, navigation_title, navigation_title_clean, navigation_url, 
				navigation_url_path, navigation_url_path_md5, navigation_url_query, navigation_language, navigation_internal_or_external, 
				navigation_weight, navigation_created_datetime, navigation_created_by_user_id) 
				VALUES 
				(NULL, $get_navigation_id, $inp_title_mysql, $inp_slug_mysql, $inp_url_mysql, 
				$inp_url_path_mysql, $inp_url_path_md5_mysql, $inp_url_query_mysql, $language_mysql, '$inp_internal_or_external', 
				'$get_count_rows', '$datetime', $inp_created_by_user_id_mysql)")
				or die(mysqli_error($link));


				// Get ID of type
				$query_nt = "SELECT navigation_id, navigation_parent_id, navigation_title, navigation_url_path, navigation_url_query, navigation_language FROM $t_pages_navigation WHERE navigation_url=$inp_url_mysql AND navigation_language=$language_mysql AND navigation_created_datetime='$datetime'";
				$result_nt = mysqli_query($link, $query_nt);
				$row_nt = mysqli_fetch_row($result_nt);
				list($get_type_navigation_id, $get_type_navigation_parent_id, $get_type_navigation_title, $get_type_navigation_url_path, $get_type_navigation_url_query, $get_type_navigation_language) = $row_nt;


				// Get sub categories
				$query_mg = "SELECT muscle_group_id, muscle_group_name, muscle_group_name_clean, muscle_group_parent_id, muscle_group_image_path, muscle_group_image_file FROM $t_muscle_groups WHERE muscle_group_parent_id='0'";
				$result_mg = mysqli_query($link, $query_mg);
				while($row_mg = mysqli_fetch_row($result_mg)) {
					list($get_main_muscle_group_id, $get_main_muscle_group_name, $get_main_muscle_group_name_clean, $get_main_muscle_group_parent_id, $get_main_muscle_group_image_path, $get_main_muscle_group_image_file) = $row_mg;

					// Translation
					$query_translation = "SELECT muscle_group_translation_id,muscle_group_translation_name FROM $t_muscle_groups_translations WHERE muscle_group_translation_muscle_group_id=$get_main_muscle_group_id AND muscle_group_translation_language=$language_mysql";
					$result_translation = mysqli_query($link, $query_translation);
					$row_translation = mysqli_fetch_row($result_translation);
					list($get_main_muscle_group_translation_id, $get_main_muscle_group_translation_name) = $row_translation;


					$inp_title = "$get_main_muscle_group_translation_name";
					$inp_title_mysql = quote_smart($link, $inp_title);

					$inp_url = "exercises/view_muscle_group.php?main_muscle_group_id=$get_main_muscle_group_id&amp;type_id=$get_type_id&amp;l=$get_language_active_iso_two";
					$inp_url = output_html($inp_url);
					$inp_url_mysql = quote_smart($link, $inp_url);

					$inp_url_parsed = parse_url($inp_url);
					$inp_url_scheme = "";
					$inp_url_host = "";
					if(isset($inp_url_parsed['scheme']) && isset($inp_url_parsed['host'])){
						$inp_url_scheme = $inp_url_parsed['scheme'];
						$inp_url_host = $inp_url_parsed['host'];
					}
					$inp_url_path = $inp_url_parsed['path'];
					if(isset($inp_url_parsed['query'])){
						$inp_url_query = $inp_url_parsed['query'];
					}
					else	{
						$inp_url_query = "";
					}
				
					if($inp_url_query != ""){
						$inp_url_query = "?" . $inp_url_query;
					}
		
					if($inp_url_scheme == "http" OR $inp_url_scheme == "https"){
						$inp_url_path = "$inp_url_scheme://$inp_url_host$inp_url_path";
						$inp_url_query = "$inp_url_query";
						$inp_internal_or_external = "external";
					}
					else{
						$inp_internal_or_external = "internal";
					}
					$inp_url_path = output_html($inp_url_path);
					$inp_url_path_mysql = quote_smart($link, $inp_url_path);

					$inp_url_path_md5 = md5($inp_url_path);
					$inp_url_path_md5_mysql = quote_smart($link, $inp_url_path_md5);

					$inp_url_query = output_html($inp_url_query);
					$inp_url_query_mysql = quote_smart($link, $inp_url_query);

					mysqli_query($link, "INSERT INTO $t_pages_navigation 
					(navigation_id, navigation_parent_id, navigation_title, navigation_title_clean, navigation_url, 
					navigation_url_path, navigation_url_path_md5, navigation_url_query, navigation_language, navigation_internal_or_external, 
					navigation_weight, navigation_created_datetime, navigation_created_by_user_id) 
					VALUES 
					(NULL, $get_type_navigation_id, $inp_title_mysql, $inp_slug_mysql, $inp_url_mysql, 
					$inp_url_path_mysql, $inp_url_path_md5_mysql, $inp_url_query_mysql, $language_mysql, '$inp_internal_or_external', 
					'$get_count_rows', '$datetime', $inp_created_by_user_id_mysql)")
					or die(mysqli_error($link));



				} // muscle groups


			} // types (cardio, strenght etc)

			// Insert sub menus :: User pages

			$inp_title = "$l_user_pages";
			$inp_title_mysql = quote_smart($link, $inp_title);

			$inp_url = "exercises/user_pages.php?l=$get_language_active_iso_two";
			$inp_url = output_html($inp_url);
			$inp_url_mysql = quote_smart($link, $inp_url);

			$inp_url_parsed = parse_url($inp_url);
			$inp_url_scheme = "";
			$inp_url_host = "";
			if(isset($inp_url_parsed['scheme']) && isset($inp_url_parsed['host'])){
				$inp_url_scheme = $inp_url_parsed['scheme'];
				$inp_url_host = $inp_url_parsed['host'];
			}
			$inp_url_path = $inp_url_parsed['path'];
			if(isset($inp_url_parsed['query'])){
				$inp_url_query = $inp_url_parsed['query'];
			}
			else	{
				$inp_url_query = "";
			}
				
			if($inp_url_query != ""){
				$inp_url_query = "?" . $inp_url_query;
			}
		
			if($inp_url_scheme == "http" OR $inp_url_scheme == "https"){
				$inp_url_path = "$inp_url_scheme://$inp_url_host$inp_url_path";
				$inp_url_query = "$inp_url_query";
				$inp_internal_or_external = "external";
			}
			else{
				$inp_internal_or_external = "internal";
			}
			$inp_url_path = output_html($inp_url_path);
			$inp_url_path_mysql = quote_smart($link, $inp_url_path);

			$inp_url_path_md5 = md5($inp_url_path);
			$inp_url_path_md5_mysql = quote_smart($link, $inp_url_path_md5);

			$inp_url_query = output_html($inp_url_query);
			$inp_url_query_mysql = quote_smart($link, $inp_url_query);

			mysqli_query($link, "INSERT INTO $t_pages_navigation 
			(navigation_id, navigation_parent_id, navigation_title, navigation_title_clean, navigation_url, 
			navigation_url_path, navigation_url_path_md5, navigation_url_query, navigation_language, navigation_internal_or_external, 
			navigation_weight, navigation_created_datetime, navigation_created_by_user_id) 
			VALUES 
			(NULL, $get_navigation_id, $inp_title_mysql, $inp_slug_mysql, $inp_url_mysql, 
			$inp_url_path_mysql, $inp_url_path_md5_mysql, $inp_url_query_mysql, $language_mysql, '$inp_internal_or_external', 
			'$get_count_rows', '$datetime', $inp_created_by_user_id_mysql)")
			or die(mysqli_error($link));


			/*
			<li class=\"header_up\"><a href=\"$root/exercises/user_pages.php?l=$l\""; if($minus_one == "index.php" && $minus_two == "android"){ echo" class=\"navigation_active\"";}echo">$l_user_pages</a></li>
						<li><a href=\"$root/exercises/new_exercise.php?l=$l\""; if($minus_one == "new_exercise.php"){ echo" class=\"navigation_active\"";}echo">$l_new_exercise</a></li>
						<li><a href=\"$root/exercises/my_exercises.php?l=$l\""; if($minus_one == "my_exercises.php"){ echo" class=\"navigation_active\"";}echo">$l_my_exercises</a></li>
						<li><a href=\"$root/exercises/new_equipment.php?l=$l\""; if($minus_one == "new_equipment.php"){ echo" class=\"navigation_active\"";}echo">$l_new_equipment</a></li>
						<li><a href=\"$root/exercises/my_equipment.php?l=$l\""; if($minus_one == "my_equipment.php"){ echo" class=\"navigation_active\"";}echo">$l_my_equipment</a></li>

			*/


		} // while languages
		$url = "index.php?open=$module&editor_language=$editor_language&l=$l&ft=success&fm=inserted_to_navigation";
		header("Location: $url");
		exit;
	
	} // module = exercises
	elseif($module == "food"){
		// Fetch all languages
		$query_l = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
		$result_l = mysqli_query($link, $query_l);
		while($row_l = mysqli_fetch_row($result_l)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row_l;

			// Food diary title
			include("_translations/site/$get_language_active_iso_two/food/ts_index.php");
			$inp_title = "$l_food";
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			$inp_slug = clean($inp_title);
			$inp_slug = output_html($inp_slug);
			$inp_slug_mysql = quote_smart($link, $inp_slug);
			
			$inp_url = "food/index.php?l=$get_language_active_iso_two";
			$inp_url = output_html($inp_url);
			$inp_url_mysql = quote_smart($link, $inp_url);

			$inp_url_parsed = parse_url($inp_url);
			$inp_url_scheme = "";
			$inp_url_host = "";
			if(isset($inp_url_parsed['scheme']) && isset($inp_url_parsed['host'])){
				$inp_url_scheme = $inp_url_parsed['scheme'];
				$inp_url_host = $inp_url_parsed['host'];
			}
			$inp_url_path = $inp_url_parsed['path'];
			if(isset($inp_url_parsed['query'])){
				$inp_url_query = $inp_url_parsed['query'];
			}
			else{
				$inp_url_query = "";
			}
				
			if($inp_url_query != ""){
				$inp_url_query = "?" . $inp_url_query;
			}
		
			if($inp_url_scheme == "http" OR $inp_url_scheme == "https"){
				$inp_url_path = "$inp_url_scheme://$inp_url_host$inp_url_path";
				$inp_url_query = "$inp_url_query";
				$inp_internal_or_external = "external";
			}
			else{
				$inp_internal_or_external = "internal";
			}
			$inp_url_path = output_html($inp_url_path);
			$inp_url_path_mysql = quote_smart($link, $inp_url_path);

			$inp_url_path_md5 = md5($inp_url_path);
			$inp_url_path_md5_mysql = quote_smart($link, $inp_url_path_md5);

			$inp_url_query = output_html($inp_url_query);
			$inp_url_query_mysql = quote_smart($link, $inp_url_query);

			$inp_parent = 0;
			$inp_parent = output_html($inp_parent);
			$inp_parent_mysql = quote_smart($link, $inp_parent);

			$datetime = date("Y-m-d H:i:s");

			$inp_created_by_user_id = $_SESSION['admin_user_id'];
			$inp_created_by_user_id = output_html($inp_created_by_user_id);
			$inp_created_by_user_id_mysql = quote_smart($link, $inp_created_by_user_id);

			// Get weight
			$language_mysql = quote_smart($link, $get_language_active_iso_two);
			$query = "SELECT count(*) FROM $t_pages_navigation WHERE navigation_parent_id=$inp_parent_mysql AND navigation_language=$language_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_count_rows) = $row;

			// Insert
			mysqli_query($link, "INSERT INTO $t_pages_navigation 
			(navigation_id, navigation_parent_id, navigation_title, navigation_title_clean, navigation_url, 
			navigation_url_path, navigation_url_path_md5, navigation_url_query, navigation_language, navigation_internal_or_external, 
			navigation_weight, navigation_created_datetime, navigation_created_by_user_id) 
			VALUES 
			(NULL, $inp_parent_mysql, $inp_title_mysql, $inp_slug_mysql, $inp_url_mysql, 
			$inp_url_path_mysql, $inp_url_path_md5_mysql, $inp_url_query_mysql, $language_mysql, '$inp_internal_or_external', 
			'$get_count_rows', '$datetime', $inp_created_by_user_id_mysql)")
			or die(mysqli_error($link));
			
			// Get ID
			$query = "SELECT navigation_id, navigation_parent_id, navigation_title, navigation_url_path, navigation_url_query, navigation_language FROM $t_pages_navigation WHERE navigation_url=$inp_url_mysql AND navigation_language=$language_mysql AND navigation_created_datetime='$datetime'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_navigation_id, $get_navigation_parent_id, $get_navigation_title, $get_navigation_url_path, $get_navigation_url_query, $get_navigation_language) = $row;
		} // languages

		$url = "index.php?open=$module&editor_language=$editor_language&l=$l&ft=success&fm=inserted_to_navigation";
		header("Location: $url");
		exit;
	} // module=food
	elseif($module == "forum"){
		$t_forum_titles		= $mysqlPrefixSav . "forum_titles";

		// Fetch all languages
		$query_l = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
		$result_l = mysqli_query($link, $query_l);
		while($row_l = mysqli_fetch_row($result_l)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row_l;

			// Forum title
			$query_t = "SELECT title_id, title_language, title_value FROM $t_forum_titles WHERE title_language='$get_language_active_iso_two'";
			$result_t = mysqli_query($link, $query_t);
			$row_t = mysqli_fetch_row($result_t);
			list($get_title_id, $get_title_language, $get_title_value) = $row_t;

			$inp_title = "$get_title_value";
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			$inp_slug = clean($inp_title);
			$inp_slug = output_html($inp_slug);
			$inp_slug_mysql = quote_smart($link, $inp_slug);
			
			$inp_url = "forum/index.php?l=$get_language_active_iso_two";
			$inp_url = output_html($inp_url);
			$inp_url_mysql = quote_smart($link, $inp_url);

			$inp_url_parsed = parse_url($inp_url);
			$inp_url_scheme = "";
			$inp_url_host = "";
			if(isset($inp_url_parsed['scheme']) && isset($inp_url_parsed['host'])){
				$inp_url_scheme = $inp_url_parsed['scheme'];
				$inp_url_host = $inp_url_parsed['host'];
			}
			$inp_url_path = $inp_url_parsed['path'];
			if(isset($inp_url_parsed['query'])){
				$inp_url_query = $inp_url_parsed['query'];
			}
			else{
				$inp_url_query = "";
			}
				
			if($inp_url_query != ""){
				$inp_url_query = "?" . $inp_url_query;
			}
		
			if($inp_url_scheme == "http" OR $inp_url_scheme == "https"){
				$inp_url_path = "$inp_url_scheme://$inp_url_host$inp_url_path";
				$inp_url_query = "$inp_url_query";
				$inp_internal_or_external = "external";
			}
			else{
				$inp_internal_or_external = "internal";
			}
			$inp_url_path = output_html($inp_url_path);
			$inp_url_path_mysql = quote_smart($link, $inp_url_path);

			$inp_url_path_md5 = md5($inp_url_path);
			$inp_url_path_md5_mysql = quote_smart($link, $inp_url_path_md5);

			$inp_url_query = output_html($inp_url_query);
			$inp_url_query_mysql = quote_smart($link, $inp_url_query);

			$inp_parent = 0;
			$inp_parent = output_html($inp_parent);
			$inp_parent_mysql = quote_smart($link, $inp_parent);

			$datetime = date("Y-m-d H:i:s");

			$inp_created_by_user_id = $_SESSION['admin_user_id'];
			$inp_created_by_user_id = output_html($inp_created_by_user_id);
			$inp_created_by_user_id_mysql = quote_smart($link, $inp_created_by_user_id);

			// Get weight
			$language_mysql = quote_smart($link, $get_language_active_iso_two);
			$query = "SELECT count(*) FROM $t_pages_navigation WHERE navigation_parent_id=$inp_parent_mysql AND navigation_language=$language_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_count_rows) = $row;

			// Insert
			mysqli_query($link, "INSERT INTO $t_pages_navigation 
			(navigation_id, navigation_parent_id, navigation_title, navigation_title_clean, navigation_url, 
			navigation_url_path, navigation_url_path_md5, navigation_url_query, navigation_language, navigation_internal_or_external, 
			navigation_weight, navigation_created_datetime, navigation_created_by_user_id) 
			VALUES 
			(NULL, $inp_parent_mysql, $inp_title_mysql, $inp_slug_mysql, $inp_url_mysql, 
			$inp_url_path_mysql, $inp_url_path_md5_mysql, $inp_url_query_mysql, $language_mysql, '$inp_internal_or_external', 
			'$get_count_rows', '$datetime', $inp_created_by_user_id_mysql)")
			or die(mysqli_error($link));
			
			// Get ID
			$query = "SELECT navigation_id, navigation_parent_id, navigation_title, navigation_url_path, navigation_url_query, navigation_language FROM $t_pages_navigation WHERE navigation_url=$inp_url_mysql AND navigation_language=$language_mysql AND navigation_created_datetime='$datetime'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_navigation_id, $get_navigation_parent_id, $get_navigation_title, $get_navigation_url_path, $get_navigation_url_query, $get_navigation_language) = $row;
		} // languages

		$url = "index.php?open=$module&editor_language=$editor_language&l=$l&ft=success&fm=inserted_to_navigation";
		header("Location: $url");
		exit;
	} // module=forum
	elseif($module == "meal_plans"){
		
		// Fetch all languages
		$query_l = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
		$result_l = mysqli_query($link, $query_l);
		while($row_l = mysqli_fetch_row($result_l)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row_l;

			// Forum title
			include("_translations/site/$get_language_active_iso_two/meal_plans/ts_index.php");
			$inp_title = "$l_meal_plans";
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			$inp_slug = clean($inp_title);
			$inp_slug = output_html($inp_slug);
			$inp_slug_mysql = quote_smart($link, $inp_slug);
			
			$inp_url = "meal_plans/index.php?l=$get_language_active_iso_two";
			$inp_url = output_html($inp_url);
			$inp_url_mysql = quote_smart($link, $inp_url);

			$inp_url_parsed = parse_url($inp_url);
			$inp_url_scheme = "";
			$inp_url_host = "";
			if(isset($inp_url_parsed['scheme']) && isset($inp_url_parsed['host'])){
				$inp_url_scheme = $inp_url_parsed['scheme'];
				$inp_url_host = $inp_url_parsed['host'];
			}
			$inp_url_path = $inp_url_parsed['path'];
			if(isset($inp_url_parsed['query'])){
				$inp_url_query = $inp_url_parsed['query'];
			}
			else{
				$inp_url_query = "";
			}
				
			if($inp_url_query != ""){
				$inp_url_query = "?" . $inp_url_query;
			}
		
			if($inp_url_scheme == "http" OR $inp_url_scheme == "https"){
				$inp_url_path = "$inp_url_scheme://$inp_url_host$inp_url_path";
				$inp_url_query = "$inp_url_query";
				$inp_internal_or_external = "external";
			}
			else{
				$inp_internal_or_external = "internal";
			}
			$inp_url_path = output_html($inp_url_path);
			$inp_url_path_mysql = quote_smart($link, $inp_url_path);

			$inp_url_path_md5 = md5($inp_url_path);
			$inp_url_path_md5_mysql = quote_smart($link, $inp_url_path_md5);

			$inp_url_query = output_html($inp_url_query);
			$inp_url_query_mysql = quote_smart($link, $inp_url_query);

			$inp_parent = 0;
			$inp_parent = output_html($inp_parent);
			$inp_parent_mysql = quote_smart($link, $inp_parent);

			$datetime = date("Y-m-d H:i:s");

			$inp_created_by_user_id = $_SESSION['admin_user_id'];
			$inp_created_by_user_id = output_html($inp_created_by_user_id);
			$inp_created_by_user_id_mysql = quote_smart($link, $inp_created_by_user_id);

			// Get weight
			$language_mysql = quote_smart($link, $get_language_active_iso_two);
			$query = "SELECT count(*) FROM $t_pages_navigation WHERE navigation_parent_id=$inp_parent_mysql AND navigation_language=$language_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_count_rows) = $row;

			// Insert
			mysqli_query($link, "INSERT INTO $t_pages_navigation 
			(navigation_id, navigation_parent_id, navigation_title, navigation_title_clean, navigation_url, 
			navigation_url_path, navigation_url_path_md5, navigation_url_query, navigation_language, navigation_internal_or_external, 
			navigation_weight, navigation_created_datetime, navigation_created_by_user_id) 
			VALUES 
			(NULL, $inp_parent_mysql, $inp_title_mysql, $inp_slug_mysql, $inp_url_mysql, 
			$inp_url_path_mysql, $inp_url_path_md5_mysql, $inp_url_query_mysql, $language_mysql, '$inp_internal_or_external', 
			'$get_count_rows', '$datetime', $inp_created_by_user_id_mysql)")
			or die(mysqli_error($link));
			
			// Get ID
			$query = "SELECT navigation_id, navigation_parent_id, navigation_title, navigation_url_path, navigation_url_query, navigation_language FROM $t_pages_navigation WHERE navigation_url=$inp_url_mysql AND navigation_language=$language_mysql AND navigation_created_datetime='$datetime'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_navigation_id, $get_navigation_parent_id, $get_navigation_title, $get_navigation_url_path, $get_navigation_url_query, $get_navigation_language) = $row;
		} // languages

		$url = "index.php?open=$module&editor_language=$editor_language&l=$l&ft=success&fm=inserted_to_navigation";
		header("Location: $url");
		exit;
	} // module=meal_plans
	elseif($module == "muscles"){
		// Fetch all languages
		$query_l = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
		$result_l = mysqli_query($link, $query_l);
		while($row_l = mysqli_fetch_row($result_l)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row_l;

			// Food diary title
			include("_translations/site/$get_language_active_iso_two/muscles/ts_index.php");
			$inp_title = "$l_muscles";
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			$inp_slug = clean($inp_title);
			$inp_slug = output_html($inp_slug);
			$inp_slug_mysql = quote_smart($link, $inp_slug);
			
			$inp_url = "muscles/index.php?l=$get_language_active_iso_two";
			$inp_url = output_html($inp_url);
			$inp_url_mysql = quote_smart($link, $inp_url);


			$inp_url_parsed = parse_url($inp_url);
			$inp_url_scheme = "";
			$inp_url_host = "";
			if(isset($inp_url_parsed['scheme']) && isset($inp_url_parsed['host'])){
				$inp_url_scheme = $inp_url_parsed['scheme'];
				$inp_url_host = $inp_url_parsed['host'];
			}
			$inp_url_path = $inp_url_parsed['path'];
			if(isset($inp_url_parsed['query'])){
				$inp_url_query = $inp_url_parsed['query'];
			}
			else{
				$inp_url_query = "";
			}
				
			if($inp_url_query != ""){
				$inp_url_query = "?" . $inp_url_query;
			}
		
			if($inp_url_scheme == "http" OR $inp_url_scheme == "https"){
				$inp_url_path = "$inp_url_scheme://$inp_url_host$inp_url_path";
				$inp_url_query = "$inp_url_query";
				$inp_internal_or_external = "external";
			}
			else{
				$inp_internal_or_external = "internal";
			}
			$inp_url_path = output_html($inp_url_path);
			$inp_url_path_mysql = quote_smart($link, $inp_url_path);

			$inp_url_path_md5 = md5($inp_url_path);
			$inp_url_path_md5_mysql = quote_smart($link, $inp_url_path_md5);

			$inp_url_query = output_html($inp_url_query);
			$inp_url_query_mysql = quote_smart($link, $inp_url_query);

			$inp_parent = 0;
			$inp_parent = output_html($inp_parent);
			$inp_parent_mysql = quote_smart($link, $inp_parent);

			$datetime = date("Y-m-d H:i:s");

			$inp_created_by_user_id = $_SESSION['admin_user_id'];
			$inp_created_by_user_id = output_html($inp_created_by_user_id);
			$inp_created_by_user_id_mysql = quote_smart($link, $inp_created_by_user_id);

			// Get weight
			$language_mysql = quote_smart($link, $get_language_active_iso_two);
			$query = "SELECT count(*) FROM $t_pages_navigation WHERE navigation_parent_id=$inp_parent_mysql AND navigation_language=$language_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_count_rows) = $row;

			// Insert
			mysqli_query($link, "INSERT INTO $t_pages_navigation 
			(navigation_id, navigation_parent_id, navigation_title, navigation_title_clean, navigation_url, 
			navigation_url_path, navigation_url_path_md5, navigation_url_query, navigation_language, navigation_internal_or_external, 
			navigation_weight, navigation_created_datetime, navigation_created_by_user_id) 
			VALUES 
			(NULL, $inp_parent_mysql, $inp_title_mysql, $inp_slug_mysql, $inp_url_mysql, 
			$inp_url_path_mysql, $inp_url_path_md5_mysql, $inp_url_query_mysql, $language_mysql, '$inp_internal_or_external', 
			'$get_count_rows', '$datetime', $inp_created_by_user_id_mysql)")
			or die(mysqli_error($link));
			

		} // while languages

		$url = "index.php?open=$module&editor_language=$editor_language&l=$l&ft=success&fm=inserted_to_navigation";
		header("Location: $url");
		exit;
	
	} // module = muscles
	elseif($module == "office_calendar"){
		// Fetch all languages
		$query_l = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
		$result_l = mysqli_query($link, $query_l);
		while($row_l = mysqli_fetch_row($result_l)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row_l;

			// Food diary title
			include("_translations/site/$get_language_active_iso_two/office_calendar/ts_index.php");
			$inp_title = "$l_office_calendar";
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			$inp_slug = clean($inp_title);
			$inp_slug = output_html($inp_slug);
			$inp_slug_mysql = quote_smart($link, $inp_slug);
			
			$inp_url = "office_calendar/index.php?l=$get_language_active_iso_two";
			$inp_url = output_html($inp_url);
			$inp_url_mysql = quote_smart($link, $inp_url);


			$inp_url_parsed = parse_url($inp_url);
			$inp_url_scheme = "";
			$inp_url_host = "";
			if(isset($inp_url_parsed['scheme']) && isset($inp_url_parsed['host'])){
				$inp_url_scheme = $inp_url_parsed['scheme'];
				$inp_url_host = $inp_url_parsed['host'];
			}
			$inp_url_path = $inp_url_parsed['path'];
			if(isset($inp_url_parsed['query'])){
				$inp_url_query = $inp_url_parsed['query'];
			}
			else{
				$inp_url_query = "";
			}
				
			if($inp_url_query != ""){
				$inp_url_query = "?" . $inp_url_query;
			}
		
			if($inp_url_scheme == "http" OR $inp_url_scheme == "https"){
				$inp_url_path = "$inp_url_scheme://$inp_url_host$inp_url_path";
				$inp_url_query = "$inp_url_query";
				$inp_internal_or_external = "external";
			}
			else{
				$inp_internal_or_external = "internal";
			}
			$inp_url_path = output_html($inp_url_path);
			$inp_url_path_mysql = quote_smart($link, $inp_url_path);

			$inp_url_path_md5 = md5($inp_url_path);
			$inp_url_path_md5_mysql = quote_smart($link, $inp_url_path_md5);

			$inp_url_query = output_html($inp_url_query);
			$inp_url_query_mysql = quote_smart($link, $inp_url_query);

			$inp_parent = 0;
			$inp_parent = output_html($inp_parent);
			$inp_parent_mysql = quote_smart($link, $inp_parent);

			$datetime = date("Y-m-d H:i:s");

			$inp_created_by_user_id = $_SESSION['admin_user_id'];
			$inp_created_by_user_id = output_html($inp_created_by_user_id);
			$inp_created_by_user_id_mysql = quote_smart($link, $inp_created_by_user_id);

			// Get weight
			$language_mysql = quote_smart($link, $get_language_active_iso_two);
			$query = "SELECT count(*) FROM $t_pages_navigation WHERE navigation_parent_id=$inp_parent_mysql AND navigation_language=$language_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_count_rows) = $row;

			// Insert
			mysqli_query($link, "INSERT INTO $t_pages_navigation 
			(navigation_id, navigation_parent_id, navigation_title, navigation_title_clean, navigation_url, 
			navigation_url_path, navigation_url_path_md5, navigation_url_query, navigation_language, navigation_internal_or_external, 
			navigation_weight, navigation_created_datetime, navigation_created_by_user_id) 
			VALUES 
			(NULL, $inp_parent_mysql, $inp_title_mysql, $inp_slug_mysql, $inp_url_mysql, 
			$inp_url_path_mysql, $inp_url_path_md5_mysql, $inp_url_query_mysql, $language_mysql, '$inp_internal_or_external', 
			'$get_count_rows', '$datetime', $inp_created_by_user_id_mysql)")
			or die(mysqli_error($link));
			

		} // while languages

		$url = "index.php?open=$module&editor_language=$editor_language&l=$l&ft=success&fm=inserted_to_navigation";
		header("Location: $url");
		exit;
	
	} // module = office_calendar
	elseif($module == "recipes"){
		// Fetch all languages
		$query_l = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
		$result_l = mysqli_query($link, $query_l);
		while($row_l = mysqli_fetch_row($result_l)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row_l;

			// Food diary title
			include("_translations/site/$get_language_active_iso_two/recipes/ts_index.php");
			$inp_title = "$l_recipes";
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			$inp_slug = clean($inp_title);
			$inp_slug = output_html($inp_slug);
			$inp_slug_mysql = quote_smart($link, $inp_slug);
			
			$inp_url = "recipes/index.php?l=$get_language_active_iso_two";
			$inp_url = output_html($inp_url);
			$inp_url_mysql = quote_smart($link, $inp_url);


			$inp_url_parsed = parse_url($inp_url);
			$inp_url_scheme = "";
			$inp_url_host = "";
			if(isset($inp_url_parsed['scheme']) && isset($inp_url_parsed['host'])){
				$inp_url_scheme = $inp_url_parsed['scheme'];
				$inp_url_host = $inp_url_parsed['host'];
			}
			$inp_url_path = $inp_url_parsed['path'];
			if(isset($inp_url_parsed['query'])){
				$inp_url_query = $inp_url_parsed['query'];
			}
			else{
				$inp_url_query = "";
			}
				
			if($inp_url_query != ""){
				$inp_url_query = "?" . $inp_url_query;
			}
		
			if($inp_url_scheme == "http" OR $inp_url_scheme == "https"){
				$inp_url_path = "$inp_url_scheme://$inp_url_host$inp_url_path";
				$inp_url_query = "$inp_url_query";
				$inp_internal_or_external = "external";
			}
			else{
				$inp_internal_or_external = "internal";
			}
			$inp_url_path = output_html($inp_url_path);
			$inp_url_path_mysql = quote_smart($link, $inp_url_path);

			$inp_url_path_md5 = md5($inp_url_path);
			$inp_url_path_md5_mysql = quote_smart($link, $inp_url_path_md5);

			$inp_url_query = output_html($inp_url_query);
			$inp_url_query_mysql = quote_smart($link, $inp_url_query);

			$inp_parent = 0;
			$inp_parent = output_html($inp_parent);
			$inp_parent_mysql = quote_smart($link, $inp_parent);

			$datetime = date("Y-m-d H:i:s");

			$inp_created_by_user_id = $_SESSION['admin_user_id'];
			$inp_created_by_user_id = output_html($inp_created_by_user_id);
			$inp_created_by_user_id_mysql = quote_smart($link, $inp_created_by_user_id);

			// Get weight
			$language_mysql = quote_smart($link, $get_language_active_iso_two);
			$query = "SELECT count(*) FROM $t_pages_navigation WHERE navigation_parent_id=$inp_parent_mysql AND navigation_language=$language_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_count_rows) = $row;

			// Insert
			mysqli_query($link, "INSERT INTO $t_pages_navigation 
			(navigation_id, navigation_parent_id, navigation_title, navigation_title_clean, navigation_url, 
			navigation_url_path, navigation_url_path_md5, navigation_url_query, navigation_language, navigation_internal_or_external, 
			navigation_weight, navigation_created_datetime, navigation_created_by_user_id) 
			VALUES 
			(NULL, $inp_parent_mysql, $inp_title_mysql, $inp_slug_mysql, $inp_url_mysql, 
			$inp_url_path_mysql, $inp_url_path_md5_mysql, $inp_url_query_mysql, $language_mysql, '$inp_internal_or_external', 
			'$get_count_rows', '$datetime', $inp_created_by_user_id_mysql)")
			or die(mysqli_error($link));
			

		} // while languages

		$url = "index.php?open=$module&editor_language=$editor_language&l=$l&ft=success&fm=inserted_to_navigation";
		header("Location: $url");
		exit;
	
	} // module = recipes
	elseif($module == "references"){
		// Fetch all languages
		$query_l = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
		$result_l = mysqli_query($link, $query_l);
		while($row_l = mysqli_fetch_row($result_l)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row_l;

			// Food diary title
			include("_translations/site/$get_language_active_iso_two/references/ts_index.php");
			$inp_title = "$l_references";
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			$inp_slug = clean($inp_title);
			$inp_slug = output_html($inp_slug);
			$inp_slug_mysql = quote_smart($link, $inp_slug);
			
			$inp_url = "references/index.php?l=$get_language_active_iso_two";
			$inp_url = output_html($inp_url);
			$inp_url_mysql = quote_smart($link, $inp_url);


			$inp_url_parsed = parse_url($inp_url);
			$inp_url_scheme = "";
			$inp_url_host = "";
			if(isset($inp_url_parsed['scheme']) && isset($inp_url_parsed['host'])){
				$inp_url_scheme = $inp_url_parsed['scheme'];
				$inp_url_host = $inp_url_parsed['host'];
			}
			$inp_url_path = $inp_url_parsed['path'];
			if(isset($inp_url_parsed['query'])){
				$inp_url_query = $inp_url_parsed['query'];
			}
			else{
				$inp_url_query = "";
			}
				
			if($inp_url_query != ""){
				$inp_url_query = "?" . $inp_url_query;
			}
		
			if($inp_url_scheme == "http" OR $inp_url_scheme == "https"){
				$inp_url_path = "$inp_url_scheme://$inp_url_host$inp_url_path";
				$inp_url_query = "$inp_url_query";
				$inp_internal_or_external = "external";
			}
			else{
				$inp_internal_or_external = "internal";
			}
			$inp_url_path = output_html($inp_url_path);
			$inp_url_path_mysql = quote_smart($link, $inp_url_path);

			$inp_url_path_md5 = md5($inp_url_path);
			$inp_url_path_md5_mysql = quote_smart($link, $inp_url_path_md5);

			$inp_url_query = output_html($inp_url_query);
			$inp_url_query_mysql = quote_smart($link, $inp_url_query);

			$inp_parent = 0;
			$inp_parent = output_html($inp_parent);
			$inp_parent_mysql = quote_smart($link, $inp_parent);

			$datetime = date("Y-m-d H:i:s");

			$inp_created_by_user_id = $_SESSION['admin_user_id'];
			$inp_created_by_user_id = output_html($inp_created_by_user_id);
			$inp_created_by_user_id_mysql = quote_smart($link, $inp_created_by_user_id);

			// Get weight
			$language_mysql = quote_smart($link, $get_language_active_iso_two);
			$query = "SELECT count(*) FROM $t_pages_navigation WHERE navigation_parent_id=$inp_parent_mysql AND navigation_language=$language_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_count_rows) = $row;

			// Insert
			mysqli_query($link, "INSERT INTO $t_pages_navigation 
			(navigation_id, navigation_parent_id, navigation_title, navigation_title_clean, navigation_url, 
			navigation_url_path, navigation_url_path_md5, navigation_url_query, navigation_language, navigation_internal_or_external, 
			navigation_weight, navigation_created_datetime, navigation_created_by_user_id) 
			VALUES 
			(NULL, $inp_parent_mysql, $inp_title_mysql, $inp_slug_mysql, $inp_url_mysql, 
			$inp_url_path_mysql, $inp_url_path_md5_mysql, $inp_url_query_mysql, $language_mysql, '$inp_internal_or_external', 
			'$get_count_rows', '$datetime', $inp_created_by_user_id_mysql)")
			or die(mysqli_error($link));
			

		} // while languages

		$url = "index.php?open=$module&editor_language=$editor_language&l=$l&ft=success&fm=inserted_to_navigation";
		header("Location: $url");
		exit;
	
	} // module = references
	elseif($module == "workout_diary"){
		// Fetch all languages
		$query_l = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
		$result_l = mysqli_query($link, $query_l);
		while($row_l = mysqli_fetch_row($result_l)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row_l;

			// Food diary title
			include("_translations/site/$get_language_active_iso_two/workout_diary/ts_index.php");
			$inp_title = "$l_workout_diary";
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			$inp_slug = clean($inp_title);
			$inp_slug = output_html($inp_slug);
			$inp_slug_mysql = quote_smart($link, $inp_slug);
			
			$inp_url = "workout_diary/index.php?l=$get_language_active_iso_two";
			$inp_url = output_html($inp_url);
			$inp_url_mysql = quote_smart($link, $inp_url);


			$inp_url_parsed = parse_url($inp_url);
			$inp_url_scheme = "";
			$inp_url_host = "";
			if(isset($inp_url_parsed['scheme']) && isset($inp_url_parsed['host'])){
				$inp_url_scheme = $inp_url_parsed['scheme'];
				$inp_url_host = $inp_url_parsed['host'];
			}
			$inp_url_path = $inp_url_parsed['path'];
			if(isset($inp_url_parsed['query'])){
				$inp_url_query = $inp_url_parsed['query'];
			}
			else{
				$inp_url_query = "";
			}
				
			if($inp_url_query != ""){
				$inp_url_query = "?" . $inp_url_query;
			}
		
			if($inp_url_scheme == "http" OR $inp_url_scheme == "https"){
				$inp_url_path = "$inp_url_scheme://$inp_url_host$inp_url_path";
				$inp_url_query = "$inp_url_query";
				$inp_internal_or_external = "external";
			}
			else{
				$inp_internal_or_external = "internal";
			}
			$inp_url_path = output_html($inp_url_path);
			$inp_url_path_mysql = quote_smart($link, $inp_url_path);

			$inp_url_path_md5 = md5($inp_url_path);
			$inp_url_path_md5_mysql = quote_smart($link, $inp_url_path_md5);

			$inp_url_query = output_html($inp_url_query);
			$inp_url_query_mysql = quote_smart($link, $inp_url_query);

			$inp_parent = 0;
			$inp_parent = output_html($inp_parent);
			$inp_parent_mysql = quote_smart($link, $inp_parent);

			$datetime = date("Y-m-d H:i:s");

			$inp_created_by_user_id = $_SESSION['admin_user_id'];
			$inp_created_by_user_id = output_html($inp_created_by_user_id);
			$inp_created_by_user_id_mysql = quote_smart($link, $inp_created_by_user_id);

			// Get weight
			$language_mysql = quote_smart($link, $get_language_active_iso_two);
			$query = "SELECT count(*) FROM $t_pages_navigation WHERE navigation_parent_id=$inp_parent_mysql AND navigation_language=$language_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_count_rows) = $row;

			// Insert
			mysqli_query($link, "INSERT INTO $t_pages_navigation 
			(navigation_id, navigation_parent_id, navigation_title, navigation_title_clean, navigation_url, 
			navigation_url_path, navigation_url_path_md5, navigation_url_query, navigation_language, navigation_internal_or_external, 
			navigation_weight, navigation_created_datetime, navigation_created_by_user_id) 
			VALUES 
			(NULL, $inp_parent_mysql, $inp_title_mysql, $inp_slug_mysql, $inp_url_mysql, 
			$inp_url_path_mysql, $inp_url_path_md5_mysql, $inp_url_query_mysql, $language_mysql, '$inp_internal_or_external', 
			'$get_count_rows', '$datetime', $inp_created_by_user_id_mysql)")
			or die(mysqli_error($link));
			

		} // while languages

		$url = "index.php?open=$module&editor_language=$editor_language&l=$l&ft=success&fm=inserted_to_navigation";
		header("Location: $url");
		exit;
	
	} // module = workout_diary
	elseif($module == "workout_plans"){
		// Fetch all languages
		$query_l = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
		$result_l = mysqli_query($link, $query_l);
		while($row_l = mysqli_fetch_row($result_l)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row_l;

			// Food diary title
			include("_translations/site/$get_language_active_iso_two/workout_plans/ts_index.php");
			$inp_title = "$l_workout_plans";
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			$inp_slug = clean($inp_title);
			$inp_slug = output_html($inp_slug);
			$inp_slug_mysql = quote_smart($link, $inp_slug);
			
			$inp_url = "workout_plans/index.php?l=$get_language_active_iso_two";
			$inp_url = output_html($inp_url);
			$inp_url_mysql = quote_smart($link, $inp_url);


			$inp_url_parsed = parse_url($inp_url);
			$inp_url_scheme = "";
			$inp_url_host = "";
			if(isset($inp_url_parsed['scheme']) && isset($inp_url_parsed['host'])){
				$inp_url_scheme = $inp_url_parsed['scheme'];
				$inp_url_host = $inp_url_parsed['host'];
			}
			$inp_url_path = $inp_url_parsed['path'];
			if(isset($inp_url_parsed['query'])){
				$inp_url_query = $inp_url_parsed['query'];
			}
			else{
				$inp_url_query = "";
			}
				
			if($inp_url_query != ""){
				$inp_url_query = "?" . $inp_url_query;
			}
		
			if($inp_url_scheme == "http" OR $inp_url_scheme == "https"){
				$inp_url_path = "$inp_url_scheme://$inp_url_host$inp_url_path";
				$inp_url_query = "$inp_url_query";
				$inp_internal_or_external = "external";
			}
			else{
				$inp_internal_or_external = "internal";
			}
			$inp_url_path = output_html($inp_url_path);
			$inp_url_path_mysql = quote_smart($link, $inp_url_path);

			$inp_url_path_md5 = md5($inp_url_path);
			$inp_url_path_md5_mysql = quote_smart($link, $inp_url_path_md5);

			$inp_url_query = output_html($inp_url_query);
			$inp_url_query_mysql = quote_smart($link, $inp_url_query);

			$inp_parent = 0;
			$inp_parent = output_html($inp_parent);
			$inp_parent_mysql = quote_smart($link, $inp_parent);

			$datetime = date("Y-m-d H:i:s");

			$inp_created_by_user_id = $_SESSION['admin_user_id'];
			$inp_created_by_user_id = output_html($inp_created_by_user_id);
			$inp_created_by_user_id_mysql = quote_smart($link, $inp_created_by_user_id);

			// Get weight
			$language_mysql = quote_smart($link, $get_language_active_iso_two);
			$query = "SELECT count(*) FROM $t_pages_navigation WHERE navigation_parent_id=$inp_parent_mysql AND navigation_language=$language_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_count_rows) = $row;

			// Insert
			mysqli_query($link, "INSERT INTO $t_pages_navigation 
			(navigation_id, navigation_parent_id, navigation_title, navigation_title_clean, navigation_url, 
			navigation_url_path, navigation_url_path_md5, navigation_url_query, navigation_language, navigation_internal_or_external, 
			navigation_weight, navigation_created_datetime, navigation_created_by_user_id) 
			VALUES 
			(NULL, $inp_parent_mysql, $inp_title_mysql, $inp_slug_mysql, $inp_url_mysql, 
			$inp_url_path_mysql, $inp_url_path_md5_mysql, $inp_url_query_mysql, $language_mysql, '$inp_internal_or_external', 
			'$get_count_rows', '$datetime', $inp_created_by_user_id_mysql)")
			or die(mysqli_error($link));
			

		} // while languages

		$url = "index.php?open=$module&editor_language=$editor_language&l=$l&ft=success&fm=inserted_to_navigation";
		header("Location: $url");
		exit;
	
	} // module = workout_diary
	else{
		echo"Unknown module";
	}
} // new_auto_insert
elseif($action == "edit"){
	$id_mysql = quote_smart($link, $id);

	$query = "SELECT navigation_id, navigation_parent_id, navigation_title, navigation_title_clean, navigation_url, navigation_url_path, navigation_url_query, navigation_language, navigation_internal_or_external, navigation_icon_path, navigation_icon_16x16_inactive, navigation_icon_16x16_hover, navigation_icon_16x16_active, navigation_icon_18x18_inactive, navigation_icon_18x18_hover, navigation_icon_18x18_active, navigation_icon_24x24_inactive, navigation_icon_24x24_hover, navigation_icon_24x24_active, navigation_weight, navigation_created_datetime, navigation_created_by_user_id, navigation_updated_datetime, navigation_updated_by_user_id FROM $t_pages_navigation WHERE navigation_id=$id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_navigation_id, $get_navigation_parent_id, $get_navigation_title, $get_navigation_title_clean, $get_navigation_url, $get_navigation_url_path, $get_navigation_url_query, $get_navigation_language, $get_navigation_internal_or_external, $get_navigation_icon_path, $get_navigation_icon_16x16_inactive, $get_navigation_icon_16x16_hover, $get_navigation_icon_16x16_active, $get_navigation_icon_18x18_inactive, $get_navigation_icon_18x18_hover, $get_navigation_icon_18x18_active, $get_navigation_icon_24x24_inactive, $get_navigation_icon_24x24_hover, $get_navigation_icon_24x24_active, $get_navigation_weight, $get_navigation_created_datetime, $get_navigation_created_by_user_id, $get_navigation_updated_datetime, $get_navigation_updated_by_user_id) = $row;

	if($get_navigation_id == ""){
		echo"
		<h1>Server error 404</h1>

		<p>
		Navigation item not found.
		</p>

		<p>
		<a href=\"index.php?open=$open&amp;page=navigation\">Home</a>
		</p>
		";
	}
	else{

		// Process
		if($process == "1"){

			$inp_language = $_POST['inp_language'];
			$inp_language = output_html($inp_language);
			$inp_language_mysql = quote_smart($link, $inp_language);
		
			// Transfer language 
			$editor_language = "$inp_language";

			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			if($inp_title == ""){
				header("Location: index.php?open=$open&page=navigation&action=edit&id=$id&ft=warning&fm=please_enter_a_title&editor_language=$editor_language");
				exit;
			}

			$inp_title_clean = clean($inp_title);
			$inp_title_clean = output_html($inp_title_clean);
			$inp_title_clean_mysql = quote_smart($link, $inp_title_clean);


			$inp_url = $_POST['inp_url'];
			$inp_url = output_html($inp_url);
			$inp_url_mysql = quote_smart($link, $inp_url);

			$inp_url_parsed = parse_url($inp_url);
			$inp_url_scheme = "";
			$inp_url_host = "";
			if(isset($inp_url_parsed['scheme']) && isset($inp_url_parsed['host'])){
				$inp_url_scheme = $inp_url_parsed['scheme'];
				$inp_url_host = $inp_url_parsed['host'];
			}
			$inp_url_path = $inp_url_parsed['path'];
			if(isset($inp_url_parsed['query'])){
				$inp_url_query = $inp_url_parsed['query'];
			}
			else{
				$inp_url_query = "";
			}
		
			if($inp_url_query != ""){
				$inp_url_query = "?" . $inp_url_query;
			}
		
			if($inp_url_scheme == "http" OR $inp_url_scheme == "https"){
				$inp_url_path = "$inp_url_scheme://$inp_url_host$inp_url_path";
				$inp_url_query = "$inp_url_query";
				$inp_internal_or_external = "external";
			}
			else{
				$inp_internal_or_external = "internal";
			}
			$inp_url_path = output_html($inp_url_path);
			$inp_url_path_mysql = quote_smart($link, $inp_url_path);

			$inp_url_path_md5 = md5($inp_url_path);
			$inp_url_path_md5_mysql = quote_smart($link, $inp_url_path_md5);

			$inp_url_query = output_html($inp_url_query);
			$inp_url_query_mysql = quote_smart($link, $inp_url_query);

			$inp_parent = $_POST['inp_parent'];
			$inp_parent = output_html($inp_parent);
			$inp_parent_mysql = quote_smart($link, $inp_parent);

			$datetime = date("Y-m-d");

			$inp_updated_by_user_id = $_SESSION['admin_user_id'];
			$inp_updated_by_user_id = output_html($inp_updated_by_user_id);
			$inp_updated_by_user_id_mysql = quote_smart($link, $inp_updated_by_user_id);
			
			// Update
			$result = mysqli_query($link, "UPDATE $t_pages_navigation SET 
							navigation_parent_id=$inp_parent_mysql, 
							navigation_title=$inp_title_mysql, 
							navigation_title_clean=$inp_title_clean_mysql, 
							navigation_url=$inp_url_mysql, 
							navigation_url_path=$inp_url_path_mysql, 
							navigation_url_path_md5=$inp_url_path_md5_mysql, 
							navigation_url_query=$inp_url_query_mysql, 
							navigation_language=$inp_language_mysql,
							navigation_internal_or_external='$inp_internal_or_external', 
							navigation_updated_datetime='$datetime', 	
							navigation_updated_by_user_id=$inp_updated_by_user_id_mysql 
							WHERE navigation_id=$id_mysql") or die(mysqli_error($link));
			



			// Dir
			$upload_path = "../_uploads/pages/navigation/$inp_language";
			if(!(is_dir("../_uploads"))){
				mkdir("../_uploads");
			}
			if(!(is_dir("../_uploads/pages"))){
				mkdir("../_uploads/pages");
			}
			if(!(is_dir("../_uploads/pages/navigation"))){
				mkdir("../_uploads/pages/navigation");
			}
			if(!(is_dir("../_uploads/pages/navigation/$inp_language"))){
				mkdir("../_uploads/pages/navigation/$inp_language");
			}

			// Upload icon
			$icons_size_array = array("18x18", "24x24");
			$icons_types_array = array("inactive", "hover", "active");
			for($x=0;$x<sizeof($icons_size_array);$x++){
			for($y=0;$y<sizeof($icons_types_array);$y++){

				// Name (inp_icon_18x18_inactive)
				$file_name = basename($_FILES["inp_icon_" . $icons_size_array[$x] . "_" . $icons_types_array[$y]]['name']);
				$file_exp = explode('.', $file_name); 
				$file_type = $file_exp[count($file_exp) -1]; 
				$file_type = strtolower("$file_type");

				// New name
				$new_name = $inp_title_clean . "_" . $icons_size_array[$x] . "_" . $icons_types_array[$y] . "." . $file_type;

				// Sjekk om det er en OK filendelse
				if($file_type == "jpg" OR $file_type == "jpeg" OR $file_type == "png" OR $file_type == "gif"){
					if(move_uploaded_file($_FILES["inp_icon_" . $icons_size_array[$x] . "_" . $icons_types_array[$y]]['tmp_name'], "$upload_path/$new_name")) {
						
						// Sjekk om det faktisk er et bilde som er lastet opp
						list($width,$height) = getimagesize("$upload_path/$new_name");
						if(is_numeric($width) && is_numeric($height)){
							// Update MySQL

							// path
							$inp_path = "_uploads/pages/navigation/$inp_language";
							$inp_path_mysql = quote_smart($link, $inp_path);

							// icon
							$inp_icon = $new_name;
							$inp_icon_mysql = quote_smart($link, $inp_icon);
							

							// Update MySQL
							$result = mysqli_query($link, "UPDATE $t_pages_navigation SET 
											navigation_icon_path=$inp_path_mysql, 
											navigation_icon_$icons_size_array[$x]" . "_" . "$icons_types_array[$y]=$inp_icon_mysql
											WHERE navigation_id=$get_navigation_id") or die(mysqli_error($link));
						}
						else{
							// Not a image
							unlink("$upload_path/$new_name");
						}
					}
					else{
						// Could not upload
					}
				}
				else{
					// Wrong file type
				}


			}  // for icons type
			} // for icons size

			
			// Move to edit
			header("Location: index.php?open=$open&page=navigation&action=edit&id=$id&editor_language=$editor_language&ft=success&fm=changes_saved");
			exit;
		} // end process
			

		
		echo"
		<h1>$l_edit_menu_item</h1>

		<!-- Where am I? -->
			<p><b>$l_you_are_here</b><br />
			<a href=\"index.php?open=$open&amp;page=navigation&amp;editor_language=$editor_language&amp;l=$l\">Navigation</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=navigation&amp;action=edit&amp;id=$id&amp;editor_language=$editor_language&amp;l=$l\">Edit $get_navigation_title</a>
			</p>
		<!-- //Where am I? -->


		<form method=\"post\" action=\"?open=$open&amp;page=navigation&amp;action=edit&amp;id=$id&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">
				
	
		<!-- Feedback -->
			";
			if($ft != ""){
				if($fm == "please_enter_a_name"){
					$fm = "$l_please_enter_a_name";
				}
				elseif($fm == "changes_saved"){
					$fm = "$l_changes_saved";
				}
				else{
				}
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
		<!-- //Feedback -->


		<!-- Focus -->
		<script>
			\$(document).ready(function(){
				\$('[name=\"inp_title\"]').focus();
			});
			</script>
		<!-- //Focus -->



		<p><b>$l_language</b>*<br />
		<select name=\"inp_language\" tabindex=\"";$tabindex=0; $tabindex=$tabindex+1;echo"$tabindex\" />";
		$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;

			echo"	<option value=\"$get_language_active_iso_two\"";if($get_navigation_language == "$get_language_active_iso_two"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";
		}
		echo"
		</select>
		</p>

		<p><b>$l_title</b>*<br />
		<input type=\"text\" name=\"inp_title\" value=\"$get_navigation_title\" size=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>
	
		<p><b>$l_url</b>*:<br />
		<input type=\"text\" name=\"inp_url\" value=\"$get_navigation_url\" size=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		<p><b>$l_parent</b>*<br />
		<select name=\"inp_parent\">
			<option value=\"0\""; if($get_navigation_parent_id == 0){ echo" selected=\"selected\""; } echo">$l_this_is_parent</option>
			<option value=\"0\">-</option>";
		
			$language_mysql = quote_smart($link, $get_navigation_language);
			$query = "SELECT navigation_id, navigation_title FROM $t_pages_navigation WHERE navigation_parent_id='0' AND navigation_language=$language_mysql";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_navigation_id, $get_navigation_title) = $row;

				echo"			<option value=\"$get_navigation_id\""; if($get_navigation_parent_id == $get_navigation_id){ echo" selected=\"selected\""; } echo">$get_navigation_title</option>\n";

				// Sub
				$query_b = "SELECT navigation_id, navigation_title FROM $t_pages_navigation WHERE navigation_parent_id='$get_navigation_id' AND navigation_language=$language_mysql";
				$result_b = mysqli_query($link, $query_b);
				while($row_b = mysqli_fetch_row($result_b)) {
					list($get_b_navigation_id, $get_b_navigation_title) = $row_b;


					echo"			<option value=\"$get_b_navigation_id\""; if($get_navigation_parent_id == $get_b_navigation_id){ echo" selected=\"selected\""; } echo">&nbsp; $get_b_navigation_title</option>\n";

				}
			}
		echo"
		</select>
		</p>
	

		";
		$icons_size_array = array("18x18", "24x24");
		$icons_types_array = array("inactive", "hover", "active");

		for($x=0;$x<sizeof($icons_size_array);$x++){
			echo"
			<hr />
			";
			for($y=0;$y<sizeof($icons_types_array);$y++){

				// Name (inp_icon_18x18_inactive)
				$inp_name = "inp_icon_" . $icons_size_array[$x] . "_" . $icons_types_array[$y];

				echo"
				<p><b>Icon $icons_size_array[$x] $icons_types_array[$y]:</b><br />";

				if($icons_size_array[$x] == "18x18" && $icons_types_array[$y] == "inactive"){
					$icon = "$get_navigation_icon_18x18_inactive";
				}
				elseif($icons_size_array[$x] == "18x18" && $icons_types_array[$y] == "hover"){
					$icon = "$get_navigation_icon_18x18_hover";
				}
				elseif($icons_size_array[$x] == "18x18" && $icons_types_array[$y] == "active"){
					$icon = "$get_navigation_icon_18x18_active";
				}
				elseif($icons_size_array[$x] == "24x24" && $icons_types_array[$y] == "inactive"){
					$icon = "$get_navigation_icon_24x24_inactive";
				}
				elseif($icons_size_array[$x] == "24x24" && $icons_types_array[$y] == "hover"){
					$icon = "$get_navigation_icon_24x24_hover";
				}
				elseif($icons_size_array[$x] == "24x24" && $icons_types_array[$y] == "active"){
					$icon = "$get_navigation_icon_24x24_active";
				}
				else{
					$icon = "?";
				}
				if(file_exists("../$get_navigation_icon_path/$icon") && $icon != ""){
					echo"
					<a href=\"../$get_navigation_icon_path/$icon\">$icon</a>
					<img src=\"../$get_navigation_icon_path/$icon\" alt=\"$icon\" /><br />
					";
				}
				else{
					echo"
					<a href=\"../$get_navigation_icon_path/$icon\">$icon</a>
					";
				}
				echo"
				<input type=\"file\" name=\"$inp_name\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				</p>";
			}
		}
		echo"


	
		<p><input type=\"submit\" value=\"Save changes\" class=\"btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
			 
		</form>

		<!-- Back -->
			<table>
			 <tr>
			  <td style=\"padding-right: 4px;\">
				<p>
				<a href=\"index.php?open=$open&amp;page=navigation&amp;editor_language=$editor_language\"><img src=\"_inc/pages/_gfx/icons/go-previous.png\" alt=\"\" /></a>
				</p>
			  </td>
			  <td>
				<p>
				<a href=\"index.php?open=$open&amp;page=navigation&amp;editor_language=$editor_language\">$l_go_back</a>
				</p>
			  </td>
			 </tr>
			</table>
		<!-- //Back -->
		";
	} // found
} // edit
elseif($action == "delete"){

	$id_mysql = quote_smart($link, $id);

	$query = "SELECT navigation_id, navigation_parent_id, navigation_title, navigation_url_path, navigation_url_query, navigation_language FROM $t_pages_navigation WHERE navigation_id=$id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_navigation_id, $get_navigation_parent_id, $get_navigation_title, $get_navigation_url_path, $get_navigation_url_query, $get_navigation_language) = $row;

	if($get_navigation_id == ""){
		echo"
		<h1>Server error 404</h1>
		";
	}
	else{
		$result = mysqli_query($link, "DELETE FROM $t_pages_navigation WHERE navigation_id=$id_mysql");


		// Move to index
		header("Location: index.php?open=$open&page=navigation&editor_language=$editor_language&ft=success&fm=navgation_item_deleted");
		exit;
	} // file exists
}
elseif($action == "move_up"){

	$id_mysql = quote_smart($link, $id);

	$query = "SELECT navigation_id, navigation_parent_id, navigation_title, navigation_url_path, navigation_url_query, navigation_language, navigation_weight FROM $t_pages_navigation WHERE navigation_id=$id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_navigation_id, $get_navigation_parent_id, $get_navigation_title, $get_navigation_url_path, $get_navigation_url_query, $get_navigation_language, $get_navigation_weight) = $row;

	if($get_navigation_id == ""){
		echo"
		<h1>Server error 404</h1>
		";
	}
	else{
		
		$inp_navigation_weight = $get_navigation_weight-2;
		$result = mysqli_query($link, "UPDATE $t_pages_navigation SET navigation_weight=$inp_navigation_weight WHERE navigation_id=$id_mysql");
			

		// Go trough entire menu, and order everything
		$count_a = 0;
		$count_b = 0;
		$count_c = 0;
		$editor_language_mysql = quote_smart($link, $editor_language);
		$query = "SELECT navigation_id, navigation_parent_id, navigation_title, navigation_url_path, navigation_url_query, navigation_weight FROM $t_pages_navigation WHERE navigation_parent_id='0' AND navigation_language=$editor_language_mysql ORDER BY navigation_weight ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_navigation_id, $get_navigation_parent_id, $get_navigation_title, $get_navigation_url_path, $get_navigation_url_query, $get_navigation_weight) = $row;


			if($get_navigation_weight != $count_a){
				$res = mysqli_query($link, "UPDATE $t_pages_navigation SET navigation_weight=$count_a WHERE navigation_id=$get_navigation_id");
			}

			$query_b = "SELECT navigation_id, navigation_parent_id, navigation_title, navigation_url_path, navigation_url_query, navigation_weight FROM $t_pages_navigation WHERE navigation_parent_id=$get_navigation_id AND navigation_language=$editor_language_mysql ORDER BY navigation_weight ASC";
			$result_b = mysqli_query($link, $query_b);
			while($row_b = mysqli_fetch_row($result_b)) {
				list($get_b_navigation_id, $get_b_navigation_parent_id, $get_b_navigation_title, $get_b_navigation_url_path, $get_b_navigation_url_query, $get_b_navigation_weight) = $row_b;


				if($get_b_navigation_weight != $count_b){
					$res = mysqli_query($link, "UPDATE $t_pages_navigation SET navigation_weight=$count_b WHERE navigation_id=$get_b_navigation_id");
				}

				// Children level 2
				$query_c = "SELECT navigation_id, navigation_parent_id, navigation_title, navigation_url_path, navigation_url_query, navigation_weight FROM $t_pages_navigation WHERE navigation_parent_id=$get_b_navigation_id AND navigation_language=$editor_language_mysql ORDER BY navigation_weight ASC";
				$result_c = mysqli_query($link, $query_c);
				while($row_c = mysqli_fetch_row($result_c)) {
					list($get_c_navigation_id, $get_c_navigation_parent_id, $get_c_navigation_title, $get_c_navigation_url_path, $get_c_navigation_url_query, $get_c_navigation_weight) = $row_c;


					if($get_c_navigation_weight != $count_c){
						$res = mysqli_query($link, "UPDATE $t_pages_navigation SET navigation_weight=$count_c WHERE navigation_id=$get_c_navigation_id");
					}

					$count_c++;
				}

				$count_b++;
			}


			$count_a++;
		}


		// Move to index
		header("Location: index.php?open=$open&page=navigation&editor_language=$editor_language");
		exit;
	} // file exists
}
elseif($action == "move_down"){


	$id_mysql = quote_smart($link, $id);

	$query = "SELECT navigation_id, navigation_parent_id, navigation_title, navigation_url_path, navigation_url_query, navigation_language, navigation_weight FROM $t_pages_navigation WHERE navigation_id=$id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_navigation_id, $get_navigation_parent_id, $get_navigation_title, $get_navigation_url_path, $get_navigation_url_query, $get_navigation_language, $get_navigation_weight) = $row;

	if($get_navigation_id == ""){
		echo"
		<h1>Server error 404</h1>
		";
	}
	else{
		
		$inp_navigation_weight = $get_navigation_weight+2;
		$result = mysqli_query($link, "UPDATE $t_pages_navigation SET navigation_weight=$inp_navigation_weight WHERE navigation_id=$id_mysql");
			

		// Go trough entire menu, and order everything
		$count_a = 0;
		$count_b = 0;
		$count_c = 0;
		$editor_language_mysql = quote_smart($link, $editor_language);
		$query = "SELECT navigation_id, navigation_parent_id, navigation_title, navigation_url_path, navigation_url_query, navigation_weight FROM $t_pages_navigation WHERE navigation_parent_id='0' AND navigation_language=$editor_language_mysql ORDER BY navigation_weight ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_navigation_id, $get_navigation_parent_id, $get_navigation_title, $get_navigation_url_path, $get_navigation_url_query, $get_navigation_weight) = $row;


			if($get_navigation_weight != $count_a){
				$res = mysqli_query($link, "UPDATE $t_pages_navigation SET navigation_weight=$count_a WHERE navigation_id=$get_navigation_id");
			}

			$query_b = "SELECT navigation_id, navigation_parent_id, navigation_title, navigation_url_path, navigation_url_query, navigation_weight FROM $t_pages_navigation WHERE navigation_parent_id=$get_navigation_id AND navigation_language=$editor_language_mysql ORDER BY navigation_weight ASC";
			$result_b = mysqli_query($link, $query_b);
			while($row_b = mysqli_fetch_row($result_b)) {
				list($get_b_navigation_id, $get_b_navigation_parent_id, $get_b_navigation_title, $get_b_navigation_url_path, $get_b_navigation_url_query,, $get_b_navigation_weight) = $row_b;


				if($get_b_navigation_weight != $count_b){
					$res = mysqli_query($link, "UPDATE $t_pages_navigation SET navigation_weight=$count_b WHERE navigation_id=$get_b_navigation_id");
				}

				// Children level 2
				$query_c = "SELECT navigation_id, navigation_parent_id, navigation_title, navigation_url_path, navigation_url_query, navigation_weight FROM $t_pages_navigation WHERE navigation_parent_id=$get_b_navigation_id AND navigation_language=$editor_language_mysql ORDER BY navigation_weight ASC";
				$result_c = mysqli_query($link, $query_c);
				while($row_c = mysqli_fetch_row($result_c)) {
					list($get_c_navigation_id, $get_c_navigation_parent_id, $get_c_navigation_title, $get_c_navigation_url_path, $get_c_navigation_url_query, $get_c_navigation_weight) = $row_c;


					if($get_c_navigation_weight != $count_c){
						$res = mysqli_query($link, "UPDATE $t_pages_navigation SET navigation_weight=$count_c WHERE navigation_id=$get_c_navigation_id");
					}

					$count_c++;
				}

				$count_b++;
			}


			$count_a++;
		}


		// Move to index
		header("Location: index.php?open=$open&page=navigation&editor_language=$editor_language");
		exit;
	} // file exists
}
?>