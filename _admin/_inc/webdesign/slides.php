<?php
/**
*
* File: _admin/_inc/slides/default.php
* Version 1.0.0
* Date 20:17 30.10.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}



/*- Tables --------------------------------------------------------------------------- */
$t_slides = $mysqlPrefixSav . "slides";

// Is setup run?
$query = "SELECT * FROM $t_slides LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
}
else{
	echo"<div class=\"info\"><p>First time setup.</p></div>
	<meta http-equiv=refresh content=\"1; url=index.php?open=$open&amp;page=slides_tables&amp;editor_language=$editor_language\">
	<div style=\"height:1000px\"></div>";
}

if($action == ""){
	echo"
	<h1>Slides</h1>
		
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
	if(isset($_GET['img_ft']) && isset($_GET['img_fm'])){

		$img_ft = $_GET['img_ft'];
		$img_ft = strip_tags(stripslashes($img_ft));

		$img_fm = $_GET['img_fm'];
		$img_fm = strip_tags(stripslashes($img_fm));

		if($img_fm == "changes_saved"){
			$img_fm = "$l_changes_saved";
		}
		else{
			$img_fm = ucfirst($img_fm);
		}
		echo"<div class=\"$img_ft\"><span>$img_fm</span></div>";
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

		<div style=\"float: left\">
			<p>
			<a href=\"index.php?open=$open&amp;page=slides_new&amp;editor_language=$editor_language\" class=\"btn\">New slide</a>
			<a href=\"index.php?open=$open&amp;page=slides_tables&amp;editor_language=$editor_language\" class=\"btn\">Tables</a>
			</p>
		</div>
		<div style=\"float: right;\">
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
				
				echo"	<option value=\"index.php?open=$open&amp;page=$page&amp;editor_language=$get_language_active_iso_two&amp;l=$l\""; if($editor_language == "$get_language_active_iso_two"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";
			}
			echo"
			</select>
			</p>
		</div>
		<div class=\"clear\"></div>
	<!-- //Menu -->
		
	<!-- List all pages -->
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span>$l_slide</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_active</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_active_from</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_active_to</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_author</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_date</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_actions</span>
		   </th>
		  </tr>
		</thead>
		<tbody>
		";
	


		$editor_language_mysql = quote_smart($link, $editor_language);
		$y = 1;
		$query = "SELECT $t_slides.slide_id, $t_slides.slide_language, $t_slides.slide_active, $t_slides.slide_active_from_datetime, $t_slides.slide_active_to_datetime, $t_slides.slide_active_on_page, $t_slides.slide_weight, $t_slides.slide_headline, $t_slides.slide_image, $t_slides.slide_text, $t_slides.slide_url, $t_slides.slide_link_name, $t_slides.slide_edited_by_user_id, $t_slides.slide_edited_datetime, $t_users.user_name FROM $t_slides
			JOIN $t_users ON $t_slides.slide_edited_by_user_id=$t_users.user_id
			WHERE slide_language=$editor_language_mysql ORDER BY slide_weight ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_slide_id, $get_slide_language, $get_slide_active, $get_slide_active_from_datetime, $get_slide_active_to_datetime, $get_slide_active_on_page, $get_slide_weight, $get_slide_headline, $get_slide_image, $get_slide_text, $get_slide_url, $get_slide_link_name, $get_slide_edited_by_user_id, $get_slide_edited_datetime, $get_user_name) = $row;

			if(isset($odd) && $odd == false){
				$odd = true;
			}
			else{
				$odd = false;
			}			

			echo"
			<tr>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
				<div style=\"float:left;margin-right: 10px;\">
					";
					if($get_slide_image != ""){
						echo"<img src=\"../image.php/$get_slide_image.png?width=55&height=55&cropratio=1:1&image=/_uploads/slides/$editor_language/imgs/$get_slide_image\" />";
					}
					echo"
				</div>
				<div style=\"float:left;\">
					<p style=\"padding: 0;margin:0;\"><a href=\"index.php?open=$open&amp;page=slides_edit&amp;slide_id=$get_slide_id&amp;editor_language=$editor_language\">$get_slide_headline</a><br />
					$get_slide_text<br />
					<a href=\"../$get_slide_url\">$get_slide_link_name</a>
					</p>
				</div>
			  </td>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
				<span>";
				if($get_slide_active == 1){
					echo $l_yes;
				}
				else{
					echo $l_no;
				}
				echo"</span>
			  </td>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
				<span>$get_slide_active_from_datetime</span>
			  </td>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
				<span>";
				if($get_slide_active_to_datetime == "3000-01-01 00:00:00"){
					echo"-";
				}
				else{
					echo"$get_slide_active_to_datetime";
				}
				echo"</span>
			  </td>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
				<span><a href=\"index.php?open=users&amp;page=users_edit_user&amp;user_id=$get_slide_edited_by_user_id&amp;editor_language=$editor_language\">$get_user_name</a></span>
			  </td>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
				<span>";
				echo substr($get_slide_edited_datetime, 0, 10);
				echo"</span>
			  </td>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
				<span>
				<a href=\"index.php?open=$open&amp;page=slides_edit&amp;slide_id=$get_slide_id&amp;editor_language=$editor_language\">$l_edit</a>
				&middot;
				<a href=\"index.php?open=$open&amp;page=slides_delete&amp;slide_id=$get_slide_id&amp;editor_language=$editor_language\">$l_delete</a>
				</span>
			 </td>
			</tr>
			";

			// Order
			if($y != "$get_slide_weight"){
				$result_update = mysqli_query($link, "UPDATE $t_slides SET slide_weight=$y WHERE slide_id=$get_slide_id");

			}

			$y++;
		}
		echo"
		 </tbody>
		</table>
	<!-- //List all pages -->
	";
}
?>