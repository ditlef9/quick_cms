<?php
/*- Check for admin ----------------------------------------------------------------- */
if(!(isset($_SESSION['admin_user_id']))){
	header("Location: ../../../login/index.php");
	die;
}


echo"
<div id=\"drawer\">

	";

	$query = "SELECT element_id, element_graph_id, element_group, element_type, element_headline, element_text, element_date, element_time, element_datetime_saying, element_position_top, element_position_left, element_path_left, element_path_right, element_connection_top_to_element_ids, element_connection_right_to_element_ids, element_connection_bottom_to_element_ids, element_connection_left_to_element_ids, element_width, element_height, element_border_color, element_background_color, element_text_color, element_arrow_left_type, element_arrow_left_path, element_arrow_left_color, element_arrow_right_type, element_arrow_right_path, element_arrow_right_color, element_added_by_user_id, element_added_datetime, element_updated_by_user_id, element_updated_datetime FROM $t_cran_graphs_elements WHERE element_graph_id=$get_current_graph_id ORDER BY element_position_top ASC";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_element_id, $get_element_graph_id, $get_element_group, $get_element_type, $get_element_headline, $get_element_text, $get_element_date, $get_element_time, $get_element_datetime_saying, $get_element_position_top, $get_element_position_left, $get_element_path_left, $get_element_path_right, $get_element_connection_top_to_element_ids, $get_element_connection_right_to_element_ids, $get_element_connection_bottom_to_element_ids, $get_element_connection_left_to_element_ids, $get_element_width, $get_element_height, $get_element_border_color, $get_element_background_color, $get_element_text_color, $get_element_arrow_left_type, $get_element_arrow_left_path, $get_element_arrow_left_color, $get_element_arrow_right_type, $get_element_arrow_right_path, $get_element_arrow_right_color, $get_element_added_by_user_id, $get_element_added_datetime, $get_element_updated_by_user_id, $get_element_updated_datetime) = $row;


		// Include element
		include("scripts/drawer/elements/$get_element_group/$get_element_type.php");

	} // elements

	echo"
</div> <!-- //drawer -->
";



?>