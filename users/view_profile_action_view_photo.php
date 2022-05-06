<?php
/*- Content --------------------------------------------------------------------------- */


// Variables
if(isset($_GET['user_id'])) {
	$user_id = $_GET['user_id'];
	$user_id = strip_tags(stripslashes($user_id));
}
else{
	$user_id = "";
}
$user_id_mysql = quote_smart($link, $user_id);

if(isset($_GET['photo_id'])) {
	$photo_id = $_GET['photo_id'];
	$photo_id = strip_tags(stripslashes($photo_id));
}
else{
	$photo_id = "";
	echo"
	<h1>Error</h1>
	
	<p>Photo not found</p>
	";
	die;
}
if(isset($_GET['start'])) {
	$start = $_GET['start'];
	$start = strip_tags(stripslashes($start));
}
else{
	$start = "$photo_id";
}
if(isset($can_view_profile)){

	// Count number of photos
	$q = "SELECT * FROM $t_users_profile_photo WHERE photo_user_id=$user_id_mysql";
	$r = mysqli_query($link, $q);
	$row_cnt = mysqli_num_rows($r);

	// Current photo
	$photo_id_mysql = quote_smart($link, $photo_id);
	$query = "SELECT photo_id, photo_title, photo_destination, photo_views_ip_block, photo_views, photo_text FROM $t_users_profile_photo WHERE photo_user_id=$user_id_mysql AND photo_id=$photo_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_photo_id, $get_photo_title, $get_photo_destination, $get_photo_views_ip_block, $get_photo_views, $get_photo_text) = $row;
	if($get_photo_id == ""){
		echo"<p>Photo not found.</p>";
	}
	else{

		// Next photo
		$query = "SELECT photo_id, photo_destination FROM $t_users_profile_photo WHERE photo_user_id=$user_id_mysql ORDER BY photo_profile_image DESC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($nav_photo_id, $nav_photo_destination) = $row;


			if(isset($next_photo) && $next_photo == "next"){
				$next_photo = "$nav_photo_id";
				
			}
			
			if($nav_photo_id == "$photo_id"){
				$next_photo = "next";

				if(isset($prev_photo_from_while)){
					$prev_photo = "$prev_photo_from_while";
				}
			}

			
			$prev_photo_from_while = "$nav_photo_id";
			
			$last_photo = "$nav_photo_id";

			
		}
		
		// Finished?

		if(!(isset($prev_photo))){
			$prev_photo = "$last_photo";
		}
		if($next_photo == "next"){
			$next_photo = "$start";
		}

		// Update views
		$my_ip = $_SERVER['REMOTE_ADDR'];
		$my_ip = output_html($my_ip);
		// $my_ip_rand = rand(0,9999);
		// $my_ip = $my_ip_rand . "." . $my_ip;

		$get_photo_views_ip_block_array = explode("|", $get_photo_views_ip_block);

		$inp_photo_views_ip_block = "";
	
		for($x=0;$x<10;$x++){
			if(isset($get_photo_views_ip_block_array[$x]) && $get_photo_views_ip_block_array[$x] == "$my_ip"){
				$my_ip_found = 1;
				break;
			}
		}

		if(!(isset($my_ip_found))){

			if(empty($get_photo_views_ip_block)){

				$inp_photo_views_ip_block = $my_ip;
			}
			else{
				$inp_photo_views_ip_block = $my_ip . "|" . $get_photo_views_ip_block;
			}

			$inp_photo_views_ip_block = substr($inp_photo_views_ip_block, 0, 60);

			$inp_photo_views_ip_block_mysql = quote_smart($link, $inp_photo_views_ip_block);
			$inp_photo_views = $get_photo_views+1;
			
			$result = mysqli_query($link, "UPDATE $t_users_profile_photo SET photo_views_ip_block=$inp_photo_views_ip_block_mysql, photo_views='$inp_photo_views' WHERE photo_id=$photo_id_mysql");
			// echo"$result<p>UPDATE $t_users_profile_photo SET photo_views_ip_block=$inp_photo_views_ip_block_mysql, photo_views='$inp_photo_views' WHERE photo_id=$photo_id_mysql</p>";

		}


		echo"
		<a id=\"photo\"></a>
		
		<!-- Photo header -->



			";

			// Get width and height of current photo
			$imagesize = getimagesize("$root/_uploads/users/images/$user_id/$get_photo_destination");
			$current_photo_width = $imagesize[0];
			$current_photo_height = $imagesize[1];

			if($current_photo_width > 630){
				$current_photo_height = round(($current_photo_height/$current_photo_width)*630, 0);
				$current_photo_width = 630;
			}
			$current_photo_height_half = round($current_photo_height/2, 0);


			$current_photo_width_px = $current_photo_width . "px";
			$current_photo_height_px = $current_photo_height . "px";
			$current_photo_height_half_px = $current_photo_height_half . "px";
	
			// Prev photo
			if(isset($prev_photo) && $prev_photo != "$photo_id"){
				echo"
				<div class=\"photo_header_left\">
					<a href=\"view_profile.php?action=view_photo&amp;user_id=$user_id&amp;photo_id=$prev_photo&amp;start=$start&amp;l=$l#photo\">&lt; $l_previous</a>
				</div>
				";
			}
		
			// Title
			if($get_photo_title != ""){
				echo"
				<div class=\"photo_header_center\">
					<span>$get_photo_title</span>
				</div>
				";
			}

			// Next
			if($next_photo != "$photo_id"){
				echo"
				<div class=\"photo_header_right\">
					<a href=\"view_profile.php?action=view_photo&amp;user_id=$user_id&amp;photo_id=$next_photo&amp;start=$start&amp;l=$l#photo\">$l_next &gt;</a>
				</div>
				";
			}
			echo"
		<!-- //Photo header -->

		";

		
		// Image
		echo"
		<!-- //Photo body -->
			<div class=\"clear\"></div>
		
			<div style=\"text-align:center;margin: auto;background: url('$root/_uploads/users/images/$user_id/$get_photo_destination');background-size: cover;width: $current_photo_width_px;height: $current_photo_height_px\">
				<p>$get_photo_text</p>
			</div>
		<!-- //Photo body -->
		";
	}



}
else{
	echo"
	<table>
	 <tr> 
	  <td style=\"padding-right: 6px;\">
		<p>
		<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"Loading\" />
		</p>
	  </td>
	  <td>
		<h1>Loading</h1>
	  </td>
	 </tr>
	</table>
		
	<meta http-equiv=\"refresh\" content=\"1;url=index.php?page=login&amp;l=$l&refer=page=view_profileamp;user_id=$user_id\">
	";
}
?>