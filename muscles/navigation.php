<?php

/*- Current page ----------------------------------------------------------------------- */
$self 		= $_SERVER['PHP_SELF'];
$request_url 	= $_SERVER["REQUEST_URI"];
$self_array     = explode("/", $self);
$array_size     = sizeof($self_array);

$minus_one	= $array_size-1;
$minus_one	= $self_array[$minus_one];

$minus_two	= $array_size-2;
$minus_two	= $self_array[$minus_two];

$complex	= $minus_two . "/" . $minus_one;


/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/muscles/ts_index.php");

/*- Special main mode ------------------------------------------------------------------ */
if(!(isset($include_as_navigation_main_mode))){
	$include_as_navigation_main_mode = 0;
}


/*- Variables --------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);

if(isset($_GET['main_group_id'])){
	$main_group_id = $_GET['main_group_id'];
	$main_group_id = strip_tags(stripslashes($main_group_id));
}
else{
	$main_group_id = "";
}
if(isset($_GET['sub_group_id'])){
	$sub_group_id= $_GET['sub_group_id'];
	$sub_group_id = strip_tags(stripslashes($sub_group_id));
}
else{
	$sub_group_id = "";
}



if($include_as_navigation_main_mode == 0){
	echo"

	<ul class=\"toc\">
	<li class=\"header_home\"><a href=\"$root/muscles/index.php?l=$l\""; if($minus_one == "index.php" && $minus_two == "muscles"){ echo" class=\"navigation_active\"";}echo">$l_muscles</a></li>
	";
}

	// Get all categories
	$query = "SELECT muscle_group_id, muscle_group_name FROM $t_muscle_groups WHERE muscle_group_parent_id='0'";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_main_muscle_group_id, $get_main_muscle_group_name) = $row;

		// Translation
		$query_translation = "SELECT muscle_group_translation_id, muscle_group_translation_name FROM $t_muscle_groups_translations WHERE muscle_group_translation_muscle_group_id=$get_main_muscle_group_id AND muscle_group_translation_language=$l_mysql";
		$result_translation = mysqli_query($link, $query_translation);
		$row_translation = mysqli_fetch_row($result_translation);
		list($get_muscle_group_translation_id, $get_main_muscle_group_translation_name) = $row_translation;



		echo"						";
		echo"<li><a href=\"$root/muscles/open_main_group.php?main_group_id=$get_main_muscle_group_id&amp;l=$l\""; if($main_group_id == "$get_main_muscle_group_id"){ echo" class=\"navigation_active\"";}echo">$get_main_muscle_group_translation_name</a></li>\n";


		if($main_group_id == "$get_main_muscle_group_id"){

			// Get sub categories
			$query_sub = "SELECT muscle_group_id, muscle_group_name, muscle_group_name_clean, muscle_group_parent_id, muscle_group_image_path, muscle_group_image_file FROM $t_muscle_groups WHERE muscle_group_parent_id='$get_main_muscle_group_id'";
			$result_sub = mysqli_query($link, $query_sub);
			while($row_sub = mysqli_fetch_row($result_sub)) {
				list($get_sub_muscle_group_id, $get_sub_muscle_group_name, $get_sub_muscle_group_name_clean, $get_sub_muscle_group_parent_id, $get_sub_muscle_group_image_path, $get_sub_muscle_group_image_file) = $row_sub;
				// Translation
				$query_translation = "SELECT muscle_group_translation_id,muscle_group_translation_name FROM $t_muscle_groups_translations WHERE muscle_group_translation_muscle_group_id=$get_sub_muscle_group_id AND muscle_group_translation_language=$l_mysql";
				$result_translation = mysqli_query($link, $query_translation);
				$row_translation = mysqli_fetch_row($result_translation);
				list($get_sub_muscle_group_translation_id, $get_sub_muscle_group_translation_name) = $row_translation;


				echo"						";
				echo"<li><a href=\"$root/muscles/open_sub_group.php?main_group_id=$get_main_muscle_group_id&amp;sub_group_id=$get_sub_muscle_group_id&amp;l=$l\""; if($sub_group_id == "$get_sub_muscle_group_id"){ echo" class=\"navigation_active\"";}echo">&nbsp; &nbsp; $get_sub_muscle_group_translation_name</a></li>\n";


			}
		}

	}


if($include_as_navigation_main_mode == 0){
	echo"
	</ul>
	\n";
}
?>