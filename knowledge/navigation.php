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
include("$root/_admin/_translations/site/$l/food/ts_food.php");


/*- Special main mode ------------------------------------------------------------------ */
if(!(isset($include_as_navigation_main_mode))){
	$include_as_navigation_main_mode = 0;
}

/*- Variables -------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);

if(isset($_GET['space_id'])){
	$space_id = $_GET['space_id'];
	$space_id = strip_tags(stripslashes($space_id));
	$space_id_mysql = quote_smart($link, $space_id);

	// Get space title
	if(!(isset($get_current_space_id))){
		$query = "SELECT space_id, space_title FROM $t_knowledge_spaces_index WHERE space_id=$space_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_space_id, $get_current_space_title) = $row;
	}

	// Get current page
	if(isset($_GET['page_id'])){
		$page_id = $_GET['page_id'];
		$page_id = strip_tags(stripslashes($page_id));
	}
	else{
		$page_id = "";
	}
	$page_id_mysql = quote_smart($link, $page_id);

	if($page_id != ""){
		$query = "SELECT page_id, page_title, page_parent_id, page_no_of_children FROM $t_knowledge_pages_index WHERE page_id=$page_id_mysql AND page_space_id=$get_current_space_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_page_id, $get_current_page_title, $get_current_page_parent_id, $get_current_page_no_of_children) = $row;

		// Does current page have parent?
		if($get_current_page_parent_id != ""){
			$query = "SELECT page_id, page_title, page_parent_id, page_no_of_children FROM $t_knowledge_pages_index WHERE page_id=$get_current_page_parent_id AND page_space_id=$get_current_space_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_parent_page_id, $get_current_parent_page_title, $get_current_parent_page_parent_id, $get_current_parent_page_no_of_children) = $row;


			// Does current parent page have parent?
			if($get_current_parent_page_parent_id != ""){
				$query = "SELECT page_id, page_title, page_parent_id, page_no_of_children FROM $t_knowledge_pages_index WHERE page_id=$get_current_parent_page_parent_id AND page_space_id=$get_current_space_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_current_parent_parent_page_id, $get_current_parent_parent_page_title, $get_current_parent_parent_page_parent_id, $get_current_parent_parent_page_no_of_children) = $row;


				// Does current parent parent page have parent?
				if($get_current_parent_parent_page_parent_id != ""){
					$query = "SELECT page_id, page_title, page_parent_id, page_no_of_children FROM $t_knowledge_pages_index WHERE page_id=$get_current_parent_parent_page_parent_id AND page_space_id=$get_current_space_id";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_current_parent_parent_parent_page_id, $get_current_parent_parent_parent_page_title, $get_current_parent_parent_parent_page_parent_id, $get_current_parent_parent_parent_page_no_of_children) = $row;
				}

			}
		}
	}


	if(isset($get_current_space_id) && $get_current_space_id != ""){


		if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
			$my_user_id = $_SESSION['user_id'];
		$my_user_id = output_html($my_user_id);
			$my_user_id_mysql = quote_smart($link, $my_user_id);

			// Check if I am a member
			$query = "SELECT member_id, member_space_id, member_rank, member_user_id, member_user_alias, member_user_image, member_user_about, member_added_datetime, member_added_date_saying, member_added_by_user_id, member_added_by_user_alias, member_added_by_user_image FROM $t_knowledge_spaces_members WHERE member_space_id=$get_current_space_id AND member_user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_member_id, $get_member_space_id, $get_member_rank, $get_member_user_id, $get_member_user_alias, $get_member_user_image, $get_member_user_about, $get_member_added_datetime, $get_member_added_date_saying, $get_member_added_by_user_id, $get_member_added_by_user_alias, $get_member_added_by_user_image) = $row;
			if($get_member_id != ""){



		if($include_as_navigation_main_mode == 0){
			echo"


			<!-- Hide show nav -->
				<script>
				\$(document).ready(function(){
					\$(\".knowledge_navigation_toggle\").click(function () {
						var idname= \$(this).data('divid');
						\$(\".\"+idname).toggle();

						// Make all inactive, or active
						var clickedimage = \$(this).attr('src');
						if(clickedimage == '_gfx/navigation/arrow_right_black_18dp.png'){
							\$(this).attr('src', '_gfx/navigation/arrow_down_black_18dp.png');
						}
						else{
							\$(this).attr('src', '_gfx/navigation/arrow_right_black_18dp.png');
						}
					});
				});
				</script>
			<!-- //Hide show nav -->


			<div id=\"knowledge_navigation\">
				<ul>
					<li><img src=\"_gfx/navigation/circle_black_18dp.png\" alt=\"circle_black_18dp.png\" /><a href=\"$root/knowledge/open_space.php?space_id=$get_current_space_id&amp;l=$l\" class=\"knowledge_navigation_a\">$get_current_space_title</a></li>\n";
		}

	
		// Get all pages
		$page_weight_counter_a = 0;
		$query = "SELECT page_id, page_title, page_no_of_children, page_weight FROM $t_knowledge_pages_index WHERE page_space_id=$get_current_space_id AND page_parent_id='0' ORDER BY page_weight ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_page_id_a, $get_page_title_a, $get_page_no_of_children_a, $get_page_weight_a) = $row;

			// A: Link
			echo"					";
			echo"<li>";
			if($get_page_no_of_children_a == "0"){
				echo"<img src=\"_gfx/navigation/circle_black_18dp.png\" alt=\"circle_black_18dp.png\" />";
			}
			else{
				if($page_id == "$get_page_id_a" OR (isset($get_current_page_parent_id) && $get_current_page_parent_id == "$get_page_id_a") OR (isset($get_current_parent_page_parent_id) && $get_current_parent_page_parent_id == "$get_page_id_a") OR (isset($get_current_parent_parent_page_parent_id) && $get_current_parent_parent_page_parent_id == "$get_page_id_a")){
					echo"<img src=\"_gfx/navigation/arrow_down_black_18dp.png\" alt=\"arrow_down_black_18dp.png\" class=\"knowledge_navigation_toggle\" data-divid=\"display_knowledge_navigation_$get_page_id_a\" />";
				}
				else{
					echo"<img src=\"_gfx/navigation/arrow_right_black_18dp.png\" alt=\"arrow_right_black_18dp.png\" class=\"knowledge_navigation_toggle\" data-divid=\"display_knowledge_navigation_$get_page_id_a\" />";
				}
			}
			echo"<a href=\"$root/knowledge/view_page.php?space_id=$get_current_space_id&amp;page_id=$get_page_id_a&amp;l=$l\" class=\"knowledge_navigation_a"; if($get_page_id_a == "$page_id"){ echo"_active";}echo"\">$get_page_title_a</a>\n";

			// A: Children <ul> start
			if($get_page_no_of_children_a != "0"){
				echo"						";
				echo"<ul class=\"display_knowledge_navigation_$get_page_id_a\""; if($get_page_id_a == "$page_id" OR (isset($get_current_page_parent_id) && $get_current_page_parent_id == "$get_page_id_a") OR (isset($get_current_parent_parent_page_id) && $get_current_parent_parent_page_id == "$get_page_id_a") OR (isset($get_current_parent_parent_parent_page_id) && $get_current_parent_parent_parent_page_id == "$get_page_id_a")){ echo" style=\"display:block;\""; } echo">\n";
			}

			// A: Children <li>
			$count_number_of_children_a = 0;
			$page_weight_counter_b = 0;
			$query_b = "SELECT page_id, page_title, page_no_of_children, page_weight FROM $t_knowledge_pages_index WHERE page_space_id=$get_current_space_id AND page_parent_id=$get_page_id_a ORDER BY page_weight ASC";
			$result_b = mysqli_query($link, $query_b);
			while($row_b = mysqli_fetch_row($result_b)) {
				list($get_page_id_b, $get_page_title_b, $get_page_no_of_children_b, $get_page_weight_b) = $row_b;

				// B: Link
				echo"						";
				echo"<li>";
				if($get_page_no_of_children_b == "0"){
					echo"<img src=\"_gfx/navigation/circle_black_18dp.png\" alt=\"circle_black_18dp.png\" />";
				}
				else{
					if($page_id == "$get_page_id_b" OR (isset($get_current_page_parent_id) && $get_current_page_parent_id == "$get_page_id_b") OR (isset($get_current_parent_page_parent_id) && $get_current_parent_page_parent_id == "$get_page_id_b")){
						echo"<img src=\"_gfx/navigation/arrow_down_black_18dp.png\" alt=\"arrow_down_black_18dp.png\" class=\"knowledge_navigation_toggle\" data-divid=\"display_knowledge_navigation_$get_page_id_b\" />";
					}
					else{
						echo"<img src=\"_gfx/navigation/arrow_right_black_18dp.png\" alt=\"arrow_right_black_18dp.png\" class=\"knowledge_navigation_toggle\" data-divid=\"display_knowledge_navigation_$get_page_id_b\" />";
					}
				}
				echo"<a href=\"$root/knowledge/view_page.php?space_id=$get_current_space_id&amp;page_id=$get_page_id_b&amp;l=$l\" class=\"knowledge_navigation_b"; if($get_page_id_b == "$page_id"){ echo"_active";}echo"\">$get_page_title_b</a>\n";


				// B: Children <ul> start
				if($get_page_no_of_children_b != "0"){
					echo"							";
					echo"<ul class=\"display_knowledge_navigation_$get_page_id_b\""; if($get_page_id_b == "$page_id" OR (isset($get_current_parent_page_id) && $get_current_parent_page_id == "$get_page_id_b") OR (isset($get_current_parent_parent_page_id) && $get_current_parent_parent_page_id == "$get_page_id_b")){ echo" style=\"display:block;\""; } echo">\n";
				}

				// B: Children
				$count_number_of_children_b = 0;
				$page_weight_counter_c = 0;
				$query_c = "SELECT page_id, page_title, page_no_of_children, page_weight FROM $t_knowledge_pages_index WHERE page_space_id=$get_current_space_id AND page_parent_id=$get_page_id_b ORDER BY page_weight ASC";
				$result_c = mysqli_query($link, $query_c);
				while($row_c = mysqli_fetch_row($result_c)) {
					list($get_page_id_c, $get_page_title_c, $get_page_no_of_children_c, $get_page_weight_c) = $row_c;

					// C: Link
					echo"						";
					echo"<li>";
					if($get_page_no_of_children_c == "0"){
						echo"<img src=\"_gfx/navigation/circle_black_18dp.png\" alt=\"circle_black_18dp.png\" />";
					}
					else{
						if($page_id == "$get_page_id_c" OR (isset($get_current_page_parent_id) && $get_current_page_parent_id == "$get_page_id_c")){
							echo"<img src=\"_gfx/navigation/arrow_down_black_18dp.png\" alt=\"arrow_down_black_18dp.png\" class=\"knowledge_navigation_toggle\" data-divid=\"display_knowledge_navigation_$get_page_id_c\" />";
						}
						else{
							echo"<img src=\"_gfx/navigation/arrow_right_black_18dp.png\" alt=\"arrow_right_black_18dp.png\" class=\"knowledge_navigation_toggle\" data-divid=\"display_knowledge_navigation_$get_page_id_c\" />";
						}
					}
					echo"<a href=\"$root/knowledge/view_page.php?space_id=$get_current_space_id&amp;page_id=$get_page_id_c&amp;l=$l\" class=\"knowledge_navigation_c"; if($get_page_id_c == "$page_id"){ echo"_active";}echo"\">$get_page_title_c</a>\n";



					// C: Children <ul> start
					if($get_page_no_of_children_c != "0"){
						echo"							";
						echo"<ul class=\"display_knowledge_navigation_$get_page_id_c\""; if($get_page_id_c == "$page_id" OR (isset($get_current_parent_parent_page_id) && $get_current_parent_parent_page_id == "$get_page_id_c") OR (isset($get_current_parent_page_id) && $get_current_parent_page_id == "$get_page_id_c")){ echo" style=\"display:block;\""; } echo">\n";
					}



					// C: Children
					$count_number_of_children_c = 0;
					$page_weight_counter_d = 0;
					$query_d = "SELECT page_id, page_title, page_no_of_children, page_weight FROM $t_knowledge_pages_index WHERE page_space_id=$get_current_space_id AND page_parent_id=$get_page_id_c ORDER BY page_weight ASC";
					$result_d = mysqli_query($link, $query_d);
					while($row_d = mysqli_fetch_row($result_d)) {
						list($get_page_id_d, $get_page_title_d, $get_page_no_of_children_d, $get_page_weight_d) = $row_d;

						echo"<li>";
						echo"<img src=\"_gfx/navigation/circle_black_18dp.png\" alt=\"circle_black_18dp.png\" />";
						echo"<a href=\"$root/knowledge/view_page.php?space_id=$get_current_space_id&amp;page_id=$get_page_id_d&amp;l=$l\" class=\"knowledge_navigation_d"; if($get_page_id_d == "$page_id"){ echo"_active";}echo"\">$get_page_title_d</a>\n";
						echo"</li>\n";
						
						

						$page_weight_counter_d++;
						if($page_weight_counter_d != "$get_page_weight_d"){
							$result_update = mysqli_query($link, "UPDATE $t_knowledge_pages_index SET page_weight=$page_weight_counter_d WHERE page_id=$get_page_id_d");
						}

						$count_number_of_children_c++;
					}
					// C: Update number of children
					if($get_page_no_of_children_c != "$count_number_of_children_c"){
						$result_update = mysqli_query($link, "UPDATE $t_knowledge_pages_index SET page_no_of_children=$count_number_of_children_c WHERE page_id=$get_page_id_c");
					}


					// C: Children </ul> end
					if($get_page_no_of_children_c != "0"){
						echo"							";
						echo"</ul>\n";
					}
					echo"						";
					echo"</li>\n\n";



					$count_number_of_children_b = $count_number_of_children_b+1;
					$page_weight_counter_c = $page_weight_counter_c+1;
					if($page_weight_counter_c != "$get_page_weight_c"){
						$result_update = mysqli_query($link, "UPDATE $t_knowledge_pages_index SET page_weight=$page_weight_counter_c WHERE page_id=$get_page_id_c");
					}


				} // pages level c
				// B: Update number of children
				if($get_page_no_of_children_b != "$count_number_of_children_b"){
					$result_update = mysqli_query($link, "UPDATE $t_knowledge_pages_index SET page_no_of_children=$count_number_of_children_b WHERE page_id=$get_page_id_b");
				}

				// B: Children </ul> end
				if($get_page_no_of_children_b != "0"){
					echo"							";
					echo"</ul>\n";
				}
				echo"						";
				echo"</li>\n\n";



				$count_number_of_children_a = $count_number_of_children_a+1;
				$page_weight_counter_b = $page_weight_counter_b+1;
				if($page_weight_counter_b != "$get_page_weight_b"){
					$result_update = mysqli_query($link, "UPDATE $t_knowledge_pages_index SET page_weight=$page_weight_counter_b WHERE page_id=$get_page_id_b");
				}
			} // pages level b

			// A: Update number of children
			if($get_page_no_of_children_a != "$count_number_of_children_a"){
				$result_update = mysqli_query($link, "UPDATE $t_knowledge_pages_index SET page_no_of_children=$count_number_of_children_a WHERE page_id=$get_page_id_a");
			}
			// A: Children </ul> end
			if($get_page_no_of_children_a != "0"){
				echo"						";
				echo"</ul>\n";
			}
			echo"					";
			echo"</li>\n\n";

			// Page weight
			$page_weight_counter_a = $page_weight_counter_a+1;
			if($page_weight_counter_a != "$get_page_weight_a"){
				$result_update = mysqli_query($link, "UPDATE $t_knowledge_pages_index SET page_weight=$page_weight_counter_a WHERE page_id=$get_page_id_a");
			}
		} // pages level a
	
		if($include_as_navigation_main_mode == 0){
			echo"
				</ul>
			</div> <!-- //knowledge navigation -->
			";
		}
		echo"
		";
			} // is member of space
		} // logged in
	} // $get_current_page_id != ""

} // isset space id
?>