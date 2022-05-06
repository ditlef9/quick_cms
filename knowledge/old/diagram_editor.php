<?php 
/**
*
* File: howto/diagram_editor.php
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

/*- Translation ------------------------------------------------------------------------------- */
include("$root/_admin/_translations/site/$l/knowledge/ts_diagrams.php");

/*- Variables -------------------------------------------------------------------------------- */
$tabindex = 0;

if(isset($_GET['space_id'])) {
	$space_id = $_GET['space_id'];
	$space_id = stripslashes(strip_tags($space_id));
}
else{
	$space_id = "";
}
$space_id_mysql = quote_smart($link, $space_id);
if(isset($_GET['page_id'])) {
	$page_id = $_GET['page_id'];
	$page_id = stripslashes(strip_tags($page_id));
}
else{
	$page_id = "0";
}
$page_id_mysql = quote_smart($link, $page_id);
if(isset($_GET['diagram_id'])) {
	$diagram_id = $_GET['diagram_id'];
	$diagram_id = stripslashes(strip_tags($diagram_id));
}
else{
	$diagram_id = "0";
}
$diagram_id_mysql = quote_smart($link, $diagram_id);

if(isset($_GET['toolbox'])) {
	$toolbox = $_GET['toolbox'];
	$toolbox = stripslashes(strip_tags($toolbox));
}
else{
	$toolbox = "uml";
}
if(isset($_GET['tool'])) {
	$tool = $_GET['tool'];
	$tool = stripslashes(strip_tags($tool));
}
else{
	$tool = "";
}


// Find space
$query = "SELECT space_id, space_title, space_title_clean, space_description, space_text, space_image, space_thumb_32, space_thumb_16, space_is_archived, space_unique_hits, space_unique_hits_ip_block, space_unique_hits_user_id_block, space_created_datetime, space_created_date_saying, space_created_user_id, space_created_user_alias, space_created_user_image, space_updated_datetime, space_updated_date_saying, space_updated_user_id, space_updated_user_alias, space_updated_user_image FROM $t_knowledge_spaces_index WHERE space_id=$space_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_space_id, $get_current_space_title, $get_current_space_title_clean, $get_current_space_description, $get_current_space_text, $get_current_space_image, $get_current_space_thumb_32, $get_current_space_thumb_16, $get_current_space_is_archived, $get_current_space_unique_hits, $get_current_space_unique_hits_ip_block, $get_current_space_unique_hits_user_id_block, $get_current_space_created_datetime, $get_current_space_created_date_saying, $get_current_space_created_user_id, $get_current_space_created_user_alias, $get_current_space_created_user_image, $get_current_space_updated_datetime, $get_current_space_updated_date_saying, $get_current_space_updated_user_id, $get_current_space_updated_user_alias, $get_current_space_updated_user_image) = $row;

if($get_current_space_id == ""){
	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "404 server error";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
	include("$root/_webdesign/header.php");

	echo"
	<h1>Server error 404</h1>

	<p>Space not found.</p>
	";
}
else{

	// Get diagram
	$query = "SELECT diagram_id, diagram_space_id, diagram_page_id, diagram_type, diagram_version, diagram_title, diagram_file_path, diagram_file_name, diagram_file_thumb_100, diagram_unique_hits, diagram_unique_hits_ip_block, diagram_unique_hits_user_id_block, diagram_created_datetime, diagram_created_date_saying, diagram_created_by_user_id, diagram_created_by_user_alias, diagram_created_by_user_email, diagram_created_by_user_image_file, diagram_created_by_user_ip, diagram_created_by_user_hostname, diagram_created_by_user_agent, diagram_updated_datetime, diagram_updated_date_saying, diagram_updated_by_user_id, diagram_updated_by_user_alias, diagram_updated_by_user_email, diagram_updated_by_user_image_file, diagram_updated_by_user_ip, diagram_updated_by_user_hostname, diagram_updated_by_user_agent FROM $t_knowledge_pages_diagrams WHERE diagram_id=$diagram_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_diagram_id, $get_current_diagram_space_id, $get_current_diagram_page_id, $get_current_diagram_type, $get_current_diagram_version, $get_current_diagram_title, $get_current_diagram_file_path, $get_current_diagram_file_name, $get_current_diagram_file_thumb_100, $get_current_diagram_unique_hits, $get_current_diagram_unique_hits_ip_block, $get_current_diagram_unique_hits_user_id_block, $get_current_diagram_created_datetime, $get_current_diagram_created_date_saying, $get_current_diagram_created_by_user_id, $get_current_diagram_created_by_user_alias, $get_current_diagram_created_by_user_email, $get_current_diagram_created_by_user_image_file, $get_current_diagram_created_by_user_ip, $get_current_diagram_created_by_user_hostname, $get_current_diagram_created_by_user_agent, $get_current_diagram_updated_datetime, $get_current_diagram_updated_date_saying, $get_current_diagram_updated_by_user_id, $get_current_diagram_updated_by_user_alias, $get_current_diagram_updated_by_user_email, $get_current_diagram_updated_by_user_image_file, $get_current_diagram_updated_by_user_ip, $get_current_diagram_updated_by_user_hostname, $get_current_diagram_updated_by_user_agent) = $row;

	if($get_current_diagram_id == ""){
		/*- Headers ---------------------------------------------------------------------------------- */
		$website_title = "404 server error";
		if(file_exists("./favicon.ico")){ $root = "."; }
		elseif(file_exists("../favicon.ico")){ $root = ".."; }
		elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
		elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
		include("$root/_webdesign/header.php");

		echo"
		<h1>Server error 404</h1>

		<p>Diagram not found.</p>
		";
	}
	else{



		// Get my user
		if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
			$my_user_id = $_SESSION['user_id'];
			$my_user_id = output_html($my_user_id);
			$my_user_id_mysql = quote_smart($link, $my_user_id);

			// Check if I am a member
			$query = "SELECT member_id, member_space_id, member_rank, member_user_id, member_user_alias, member_user_image, member_user_about, member_added_datetime, member_added_date_saying, member_added_by_user_id, member_added_by_user_alias, member_added_by_user_image FROM $t_knowledge_spaces_members WHERE member_space_id=$get_current_space_id AND member_user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_my_member_id, $get_my_member_space_id, $get_my_member_rank, $get_my_member_user_id, $get_my_member_user_alias, $get_my_member_user_image, $get_my_member_user_about, $get_my_member_added_datetime, $get_my_member_added_date_saying, $get_my_member_added_by_user_id, $get_my_member_added_by_user_alias, $get_my_member_added_by_user_image) = $row;
			if($get_my_member_id == ""){
				echo"Not member";
				die;
			}
			else{
				// Header
				if($process != "1"){
					echo"<!DOCTYPE html>\n";
					echo"<html lang=\"$l\">\n";
					echo"<head>\n";
					echo"	<title>$get_current_diagram_title</title>\n";
					echo"	<link rel=\"stylesheet\" type=\"text/css\" href=\"_css/diagram_editor.css\" />\n";
					echo"	<link rel=\"icon\" href=\"$root/_uploads/favicon/16x16.png\" type=\"image/png\" sizes=\"16x16\" />\n";
					echo"	<link rel=\"icon\" href=\"$root/_uploads/favicon/32x32.png\" type=\"image/png\" sizes=\"32x32\" />\n";
					echo"	<link rel=\"icon\" href=\"$root/_uploads/favicon/260x260.png\" type=\"image/png\" sizes=\"260x260\" />\n";
					echo"	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n";
					echo"	<meta name=\"viewport\" content=\"width=device-width; initial-scale=1.0;\"/>\n";
					echo"	<script type=\"text/javascript\" src=\"$root/_scripts/javascripts/jquery/jquery-3.4.0.min.js\"></script>\n";
					echo"</head>\n";
					echo"<body>\n";
				}



				echo"
				<!-- Header -->
					<header>
						<div id=\"header_top_left\">
							<a href=\"index.php\">$get_current_diagram_title</a>
						</div>
						<div id=\"header_top_center\">
							<form method=\"POST\" id=\"save_diagram_form\" action=\"diagram_editor_save.php?space_id=$space_id&amp;page_id=$page_id&amp;diagram_id=$get_current_diagram_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
							<div id=\"coords\">Coords</div>
							<p>
							<input type=\"hidden\" name=\"inp_regcoords\" id=\"regcoords\" />
							<input type=\"hidden\" name=\"inp_toolbox\" value=\"$toolbox\" />
							<input type=\"hidden\" name=\"inp_tool\" value=\"$tool\" />
							<input type=\"submit\" value=\"$l_save\" />
							</p>
							</form>
							<!-- Feedback -->
								";
								if($ft != ""){
									
									$fm = ucfirst($fm);
									$fm = str_replace("_", " ", $fm);
									echo"<div class=\"$ft\"><span>$fm</span></div>";
								}
								echo"	
							<!-- //Feedback -->
						</div>
						<div id=\"header_top_right\">
							<ul>
								<li><a href=\"diagrams.php?space_id=$space_id&amp;page_id=$page_id&amp;l=$l\">$l_diagrams</a></li>
								<li><a href=\"diagram_editor_delete.php?space_id=$space_id&amp;page_id=$page_id&amp;diagram_id=$get_current_diagram_id&amp;l=$l\">$l_delete</a></li>
							</ul>
						</div>
					</header>
						
				<!-- Sub Header -->
					<div id=\"sub_header\">
						<div id=\"sub_header_left\">
							<ul>
								<li><a href=\"diagram_editor.php?space_id=$space_id&amp;page_id=$page_id&amp;diagram_id=$get_current_diagram_id&amp;toolbox=$toolbox&amp;l=$l\">$l_pointer</a></li>
							</ul>
						</div>
						<div id=\"sub_header_right\">
							<!-- Edit -->";
								if(isset($_GET['data_id'])) {
									$data_id = $_GET['data_id'];
									$data_id = stripslashes(strip_tags($data_id));
									$data_id_mysql = quote_smart($link, $data_id);

									$query = "SELECT data_id, data_space_id, data_page_id, data_diagram_id, data_cord_start_x, data_cord_start_y, data_cord_end_x, data_cord_end_y, data_cord_toolbox, data_cord_tool, data_headline, data_text, data_connection_type, data_connection_start_at_data_id, data_connection_end_at_data_id FROM $t_knowledge_pages_diagrams_data WHERE data_id=$data_id_mysql AND data_diagram_id=$get_current_diagram_id";
									$result = mysqli_query($link, $query);
									$row = mysqli_fetch_row($result);
									list($get_current_data_id, $get_current_data_space_id, $get_current_data_page_id, $get_current_data_diagram_id, $get_current_data_cord_start_x, $get_current_data_cord_start_y, $get_current_data_cord_end_x, $get_current_data_cord_end_y, $get_current_data_cord_toolbox, $get_current_data_cord_tool, $get_current_data_headline, $get_current_data_text, $get_current_data_connection_type, $get_current_data_connection_start_at_data_id, $get_current_data_connection_end_at_data_id) = $row;

									if($get_current_data_id != ""){
										echo"
										<form method=\"POST\" id=\"save_diagram_form\" action=\"diagram_editor_edit_data.php?space_id=$space_id&amp;page_id=$page_id&amp;diagram_id=$get_current_diagram_id&amp;data_id=$data_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
										<p>
										<input type=\"text\" name=\"inp_headline\" value=\"$get_current_data_headline\" />
										<input type=\"submit\" value=\"$l_save\" />
										<a href=\"diagram_editor_delete_data.php?space_id=$space_id&amp;page_id=$page_id&amp;diagram_id=$get_current_diagram_id&amp;data_id=$data_id&amp;l=$l&amp;process=1\" class=\"btn_warning\">$l_delete</a>
										</p>
										</form>
										";
									}
								}
								echo"
							<!-- //Edit -->
						</div>
					</div>
				<!-- //Sub Header -->
				<!-- Main -->
					<div id=\"main_wrapper\">
						<div id=\"main_left\">

							<!-- Hide show nav -->
								<script>
								\$(document).ready(function(){
									\$(\".main_left_menu_header\").click(function () {
										var idname= \$(this).data('divid');
										\$(\".\"+idname).toggle();
									});
								});
								</script>
							<!-- //Hide show nav -->

							<!-- Toolbox UML -->
								<div><a href=\"#\" class=\"main_left_menu_header\" data-divid=\"display_uml\"><img src=\"_gfx/diagram/icon_"; if($toolbox == "uml"){ echo"down"; } else{ echo"right"; } echo".png\" alt=\"icon_right.png\" /> UML</a></div>
								<div class=\"display_uml\""; if($toolbox == "uml"){ echo" style=\"display: inline\""; } echo">
									<a href=\"diagram_editor.php?space_id=$space_id&amp;page_id=$page_id&amp;diagram_id=$get_current_diagram_id&amp;toolbox=uml&amp;tool=class&amp;l=$l\"><img src=\"_gfx/diagram/uml/48x48/class_48x48.png\" alt=\"class_48x48.png\" /></a>
								</div>
								
							<!-- //Toolbox UML -->

						</div> <!-- //main_left -->
						<div id=\"main_right\">
							<div id=\"main_right_content\" style=\"position: relative;";
								if($toolbox != "" && $tool != ""){
									$mouse = "_gfx/diagram/$toolbox/48x48/$tool" . "_48x48.png";
									echo"cursor: url('$mouse'), auto;";
								}
								echo"\">
								<!-- Draw -->";
									$query = "SELECT data_id, data_space_id, data_page_id, data_diagram_id, data_cord_start_x, data_cord_start_y, data_cord_start_x_px, data_cord_start_y_px, data_cord_end_x, data_cord_end_y, data_width_x, data_width_y, data_width_x_px, data_width_y_px, data_border_color, data_background_color, data_background_image, data_cord_toolbox, data_cord_tool, data_headline, data_text, data_connection_type, data_connection_start_at_data_id, data_connection_end_at_data_id FROM $t_knowledge_pages_diagrams_data WHERE data_diagram_id=$get_current_diagram_id";
									$result = mysqli_query($link, $query);
									while($row = mysqli_fetch_row($result)) {
										list($get_data_id, $get_data_space_id, $get_data_page_id, $get_data_diagram_id, $get_data_cord_start_x, $get_data_cord_start_y, $get_data_cord_start_x_px, $get_data_cord_start_y_px, $get_data_cord_end_x, $get_data_cord_end_y, $get_data_width_x, $get_data_width_y, $get_data_width_x_px, $get_data_width_y_px, $get_data_border_color, $get_data_background_color, $get_data_background_image, $get_data_cord_toolbox, $get_data_cord_tool, $get_data_headline, $get_data_text, $get_data_connection_type, $get_data_connection_start_at_data_id, $get_data_connection_end_at_data_id) = $row;
										
										if($get_data_cord_toolbox == "uml" && $get_data_cord_tool == "class"){
											echo"
											<div style=\"border: $get_data_border_color 1px solid;background: $get_data_background_color; width: $get_data_width_x_px; height: $get_data_width_y_px;position: absolute;left: $get_data_cord_start_x_px;top: $get_data_cord_start_y_px;\">
												<!-- Edit // Delete -->
													<a href=\"diagram_editor_delete_data.php?space_id=$space_id&amp;page_id=$page_id&amp;diagram_id=$get_current_diagram_id&amp;data_id=$get_data_id&amp;toolbox=$toolbox&amp;l=$l\"><img src=\"_gfx/diagram/icon_delete.png\" alt=\"icon_delete.png\" /></a>
												<!-- Headline -->
													
													<form method=\"POST\" action=\"diagram_editor_edit_data.php?space_id=$space_id&amp;page_id=$page_id&amp;diagram_id=$get_current_diagram_id&amp;data_id=$get_data_id&amp;toolbox=$toolbox&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
													<p>
													<input type=\"text\" name=\"inp_headline\" value=\"$get_data_headline\" />
													<input type=\"submit\" value=\"$l_save\" />
													</p>
													</form>
												<!-- //Headline -->
												
											</div>
											";
										}
									}
									echo"

								<!-- //Draw -->
							</div>

							
							<!-- Get mouse clicks -->
								";
								if($toolbox != "" && $tool != ""){
							
									echo"
								<script type=\"text/javascript\">
								/*
								 Here add the ID of the HTML elements for which to show the mouse coords
								 Within quotes, separated by comma.
								 E.g.:   ['imgid', 'divid'];
								*/
								var elmids = ['main_right_content'];

								var x, y = 0;       // variables that will contain the coordinates

								// Get X and Y position of the elm (from: vishalsays.wordpress.com)
								function getXYpos(elm) {
								  x = elm.offsetLeft;        // set x to elm’s offsetLeft
								  y = elm.offsetTop;         // set y to elm’s offsetTop

								  elm = elm.offsetParent;    // set elm to its offsetParent

								  //use while loop to check if elm is null
								  // if not then add current elm’s offsetLeft to x
								  //offsetTop to y and set elm to its offsetParent
								  while(elm != null) {
								    x = parseInt(x) + parseInt(elm.offsetLeft);
								    y = parseInt(y) + parseInt(elm.offsetTop);
								    elm = elm.offsetParent;
								  }

								  // returns an object with \"xp\" (Left), \"=yp\" (Top) position
								  return {'xp':x, 'yp':y};
								}

								// Get X, Y coords, and displays Mouse coordinates
								function getCoords(e) {
								 // coursesweb.net/
								  var xy_pos = getXYpos(this);

								  // if IE
								  if(navigator.appVersion.indexOf(\"MSIE\") != -1) {
								    // in IE scrolling page affects mouse coordinates into an element
								    // This gets the page element that will be used to add scrolling value to correct mouse coords
								    var standardBody = (document.compatMode == 'CSS1Compat') ? document.documentElement : document.body;

								    x = event.clientX + standardBody.scrollLeft;
								    y = event.clientY + standardBody.scrollTop;
								  }
								  else {
								    x = e.pageX;
								    y = e.pageY;
								  }

								  x = x - xy_pos['xp'];
								  y = y - xy_pos['yp'];

								  // displays x and y coords in the #coords element
								  document.getElementById('coords').innerHTML = 'X= '+ x+ ' ,Y= ' +y;
								}

								// register onmousemove, and onclick the each element with ID stored in elmids
								for(var i=0; i<elmids.length; i++) {
								  if(document.getElementById(elmids[i])) {
								    // calls the getCoords() function when mousemove
								    document.getElementById(elmids[i]).onmousemove = getCoords;

								    // execute a function when click
								    document.getElementById(elmids[i]).onclick = function() {
								  	document.getElementById('regcoords').value = x+ ' , ' +y;
									var form = document.getElementById(\"save_diagram_form\");
									form.submit();
								    };
								  }
								}
								</script>
									";
								}
								echo"
							<!-- //Get mouse clicks -->

						</div> <!-- //main_right -->
					</div>
				<!-- //Main -->
				";
	
				// Footer
				echo"</body>\n";
				echo"</html>";
			} // is member
		} // logged in
		else{
		
			echo"
			<h1><img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" /> $l_please_log_in...</h1>
			<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=$root/knowledge/open_space.php?space_id=$get_current_space_id\">
			";
		}
	} // diagram found
} // space found

?>