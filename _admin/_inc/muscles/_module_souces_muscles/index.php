<?php
/**
*
* File: muscles/index.php
* Version 1.0.0.
* Date 19:42 08.02.2018
* Copyright (c) 2008-2018 Sindre Andre Ditlefsen
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
include("_tables_muslces.php");

/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);


if(isset($_GET['order_by'])) {
	$order_by = $_GET['order_by'];
	$order_by = strip_tags(stripslashes($order_by));
}
else{
	$order_by = "";
}
if(isset($_GET['order_method'])) {
	$order_method = $_GET['order_method'];
	$order_method = strip_tags(stripslashes($order_method));
}
else{
	$order_method = "";
}


/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_muscles";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");


echo"
<!-- Headline and language -->
	<h1>$l_muscles</h1>
<!-- //Headline and language -->


<!-- Where am I? -->
	<p><b>$l_you_are_here:</b><br />
	<a href=\"index.php?l=$l\">$l_muscles</a>
	</p>
<!-- //Where am I ? -->


<!-- Show all categories of muscles -->
	
	<table class=\"hor-zebra\">
	";	
	// Get all main
	$query = "SELECT muscle_group_id, muscle_group_name, muscle_group_name_clean, muscle_group_parent_id, muscle_group_image_path, muscle_group_image_file FROM $t_muscle_groups WHERE muscle_group_parent_id='0'";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_main_muscle_group_id, $get_main_muscle_group_name, $get_main_muscle_group_name_clean, $get_main_muscle_group_parent_id, $get_main_muscle_group_image_path, $get_main_muscle_group_image_file) = $row;
			
		// Translation
		$query_translation = "SELECT muscle_group_translation_id,muscle_group_translation_name FROM $t_muscle_groups_translations WHERE muscle_group_translation_muscle_group_id=$get_main_muscle_group_id AND muscle_group_translation_language=$l_mysql";
		$result_translation = mysqli_query($link, $query_translation);
		$row_translation = mysqli_fetch_row($result_translation);
		list($get_main_muscle_group_translation_id, $get_main_muscle_group_translation_name) = $row_translation;




		echo"
		 <thead>
		  <tr>
		   <th scope=\"col\" colspan=\"2\">
			<a href=\"$root/muscles/open_main_group.php?main_group_id=$get_main_muscle_group_id&amp;l=$l\" style=\"font-weight: bold;\">$get_main_muscle_group_translation_name</a>
		   </td>
		  </tr>
		 </thead>
		";
		// Get sub
		$query_sub = "SELECT muscle_group_id, muscle_group_name, muscle_group_name_clean, muscle_group_parent_id, muscle_group_image_path, muscle_group_image_file FROM $t_muscle_groups WHERE muscle_group_parent_id='$get_main_muscle_group_id'";
		$result_sub = mysqli_query($link, $query_sub);
		while($row_sub = mysqli_fetch_row($result_sub)) {
			list($get_sub_muscle_group_id, $get_sub_muscle_group_name, $get_sub_muscle_group_name_clean, $get_sub_muscle_group_parent_id, $get_sub_muscle_group_image_path, $get_sub_muscle_group_image_file) = $row_sub;
			
			// Translation
			$query_translation = "SELECT muscle_group_translation_id,muscle_group_translation_name FROM $t_muscle_groups_translations WHERE muscle_group_translation_muscle_group_id=$get_sub_muscle_group_id AND muscle_group_translation_language=$l_mysql";
			$result_translation = mysqli_query($link, $query_translation);
			$row_translation = mysqli_fetch_row($result_translation);
			list($get_sub_muscle_group_translation_id, $get_sub_muscle_group_translation_name) = $row_translation;

			if($get_sub_muscle_group_translation_id == ""){
				echo"mja";
				mysqli_query($link, "INSERT INTO $t_muscle_groups_translations 
				(muscle_group_translation_id, muscle_group_translation_muscle_group_id, muscle_group_translation_language, muscle_group_translation_name, muscle_group_translation_text) 
				VALUES 
				(NULL, 	'$get_sub_muscle_group_id', $l_mysql, '$get_sub_muscle_group_name', '')")
				or die(mysqli_error($link));
			}
				
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
				<a href=\"$root/muscles/open_sub_group.php?main_group_id=$get_main_muscle_group_id&amp;sub_group_id=$get_sub_muscle_group_id&amp;l=$l\">$get_sub_muscle_group_translation_name</a>
			  </td>
			  <td class=\"$style\">
				<span>";

				// Get muscles
				$muscles_count = 0;
				$query_m = "SELECT muscle_id, muscle_latin_name, muscle_latin_name_clean, muscle_simple_name, muscle_short_name, muscle_image_path, muscle_image_file FROM $t_muscles WHERE muscle_group_id_sub='$get_sub_muscle_group_id' AND muscle_part_of_id='0' ORDER BY muscle_latin_name ASC";
				$result_m = mysqli_query($link, $query_m);
				while($row_m = mysqli_fetch_row($result_m)) {
					list($get_muscle_id, $get_muscle_latin_name, $get_muscle_latin_name_clean, $get_muscle_simple_name, $get_muscle_short_name, $get_muscle_image_path, $get_muscle_image_file) = $row_m;

					// Translation
					$query_translation = "SELECT muscle_translation_id, muscle_translation_simple_name, muscle_translation_short_name FROM $t_muscles_translations WHERE muscle_translation_muscle_id='$get_muscle_id' AND muscle_translation_language=$l_mysql";
					$result_translation = mysqli_query($link, $query_translation);
					$row_translation = mysqli_fetch_row($result_translation);
					list($get_muscle_translation_id, $get_muscle_translation_simple_name, $get_muscle_translation_short_name) = $row_translation;
					
					if($get_muscle_translation_id == ""){
						mysqli_query($link, "INSERT INTO $t_muscles_translations
						(muscle_translation_id, muscle_translation_muscle_id, muscle_translation_language, muscle_translation_simple_name, muscle_translation_short_name, muscle_translation_text) 
						VALUES 
						(NULL, 	'$get_muscle_id', $l_mysql, '$get_muscle_simple_name', '$get_muscle_short_name', '')")
						or die(mysqli_error($link));
					}

					if($muscles_count != 0){
						echo" &middot;";
					}
					
					echo"
					<a href=\"$root/muscles/muscle.php?main_group_id=$get_main_muscle_group_id&amp;sub_group_id=$get_sub_muscle_group_id&amp;muscle_id=$get_muscle_id&amp;l=$l\">$get_muscle_translation_simple_name</a>
					
					";


					$muscles_count++;
				}
	
				// Get part ofs
				$query_p = "SELECT muscle_part_of_id, muscle_part_of_name FROM $t_muscle_part_of WHERE muscle_part_of_muscle_group_id_sub='$get_sub_muscle_group_id'";
				$result_p = mysqli_query($link, $query_p);
				while($row_p = mysqli_fetch_row($result_p)) {
					list($get_muscle_part_of_id, $get_muscle_part_of_name) = $row_p;
						
					// Get translation
					$query_translation = "SELECT muscle_part_of_translation_id, muscle_part_of_translation_name FROM $t_muscle_part_of_translations WHERE muscle_part_of_translation_muscle_part_of_id='$get_muscle_part_of_id' AND muscle_part_of_translation_language=$l_mysql";
					$result_translation = mysqli_query($link, $query_translation);
					$row_translation = mysqli_fetch_row($result_translation);
					list($get_muscle_part_of_translation_id, $get_muscle_part_of_translation_name) = $row_translation;
					if($get_muscle_part_of_translation_id == ""){
						mysqli_query($link, "INSERT INTO $t_muscle_part_of_translations
						(muscle_part_of_translation_id, muscle_part_of_translation_muscle_part_of_id, muscle_part_of_translation_language, muscle_part_of_translation_name, muscle_part_of_translation_text) 
						VALUES 
						(NULL, 	'$get_muscle_part_of_id', $l_mysql, '$get_muscle_part_of_name', '')")
						or die(mysqli_error($link));
					}

					if($muscles_count != 0){
						echo" &middot;";
					}

					echo"
					<a href=\"$root/muscles/open_muscle_part_of.php?main_group_id=$get_main_muscle_group_id&amp;sub_group_id=$get_sub_muscle_group_id&amp;part_of_id=$get_muscle_part_of_id&amp;l=$l\">$get_muscle_part_of_translation_name</a>
					(";

					
				
					
					$muscles_count_inside = 0;
					$query_m = "SELECT muscle_id, muscle_latin_name, muscle_latin_name_clean, muscle_simple_name, muscle_short_name, muscle_image_path, muscle_image_file FROM $t_muscles WHERE muscle_group_id_sub='$get_sub_muscle_group_id' AND muscle_part_of_id != '0' ORDER BY muscle_latin_name ASC";
					$result_m = mysqli_query($link, $query_m);
					while($row_m = mysqli_fetch_row($result_m)) {
						list($get_muscle_id, $get_muscle_latin_name, $get_muscle_latin_name_clean, $get_muscle_simple_name, $get_muscle_short_name, $get_muscle_image_path, $get_muscle_image_file) = $row_m;

						// Translation
						$query_translation = "SELECT muscle_translation_id, muscle_translation_simple_name, muscle_translation_short_name FROM $t_muscles_translations WHERE muscle_translation_muscle_id='$get_muscle_id' AND muscle_translation_language=$l_mysql";
						$result_translation = mysqli_query($link, $query_translation);
						$row_translation = mysqli_fetch_row($result_translation);
						list($get_muscle_translation_id, $get_muscle_translation_simple_name, $get_muscle_translation_short_name) = $row_translation;
					
						if($get_muscle_translation_id == ""){
							mysqli_query($link, "INSERT INTO $t_muscles_translations
							(muscle_translation_id, muscle_translation_muscle_id, muscle_translation_language, muscle_translation_simple_name, muscle_translation_short_name, muscle_translation_text) 
							VALUES 
							(NULL, 	'$get_muscle_id', $l_mysql, '$get_muscle_simple_name', '$get_muscle_short_name', '')")
							or die(mysqli_error($link));
						}

						if($muscles_count_inside != 0){
							echo" &middot;";
						}
					
						echo"
						<a href=\"$root/muscles/muscle.php?main_group_id=$get_main_muscle_group_id&amp;sub_group_id=$get_sub_muscle_group_id&amp;part_of_id=$get_muscle_part_of_id&amp;muscle_id=$get_muscle_id&amp;l=$l\">";
						if($get_muscle_translation_short_name == ""){
							if($get_muscle_short_name == ""){
								echo"$get_muscle_simple_name";
							}
							else{
								echo"$get_muscle_short_name";
							}
						}
						else{
							echo"$get_muscle_translation_short_name";
						}
						echo"</a>
						";

						$muscles_count_inside++;
					}
					echo")
					";

					$muscles_count++;
				} // part of
				echo"</span>
			  </td>
			 </tr>
			";
		}
		echo"
		 </tr>
		";
	}
	echo"
	</table>
<!-- //Show all categories of muscles -->

";


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>