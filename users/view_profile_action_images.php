<?php


// Variables
if(isset($_GET['user_id'])) {
	$user_id = $_GET['user_id'];
	$user_id = strip_tags(stripslashes($user_id));
}
else{
	$user_id = "";
}
$user_id_mysql = quote_smart($link, $user_id);


/*- Content --------------------------------------------------------------------------- */


if(isset($can_view_profile)){
	echo"



	<!-- Images -->
		
		";

		// Rest of photos
		$query = "SELECT photo_id, photo_user_id, photo_destination, photo_thumb_200 FROM $t_users_profile_photo WHERE photo_user_id=$user_id_mysql ORDER BY photo_profile_image DESC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_photo_id, $get_photo_user_id, $get_photo_destination, $get_photo_thumb_200) = $row;


			if(file_exists("$root/_uploads/users/images/$get_photo_user_id/$get_photo_destination")){

					// Small = 40
					// Medium = 60
					// Large = 200
					if($get_photo_thumb_200 == ""){
						$extension = get_extension($get_photo_destination);
						$extension = strtolower($extension);
						$name = str_replace(".$extension", "", $get_photo_destination);
	
						// Small
						$thumb_a = $name . "_40." . $extension;
						$thumb_a_mysql = quote_smart($link, $thumb_a);

						// Medium
						$thumb_b = $name . "_50." . $extension;
						$thumb_b_mysql = quote_smart($link, $thumb_b);

						// Large
						$thumb_c = $name . "_60." . $extension;
						$thumb_c_mysql = quote_smart($link, $thumb_c);

						// Extra Large
						$thumb_d = $name . "_200." . $extension;
						$thumb_d_mysql = quote_smart($link, $thumb_d);
		
						// Update
						$result_update = mysqli_query($link, "UPDATE $t_users_profile_photo SET photo_thumb_40=$thumb_a_mysql, photo_thumb_50=$thumb_b_mysql, photo_thumb_60=$thumb_c_mysql, photo_thumb_200=$thumb_d_mysql WHERE photo_id=$get_photo_id");
				
						// Pass new variables
						$get_photo_thumb_40 = "$thumb_a";
						$get_photo_thumb_50 = "$thumb_b";
						$get_photo_thumb_60 = "$thumb_c";
						$get_photo_thumb_200 = "$thumb_d";
					}
					if(!(file_exists("$root/_uploads/users/images/$get_photo_user_id/$get_photo_thumb_200"))){
						// Thumb
						$inp_new_x = 200;
						$inp_new_y = 200;
						resize_crop_image($inp_new_x, $inp_new_y, "$root/_uploads/users/images/$get_photo_user_id/$get_photo_destination", "$root/_uploads/users/images/$get_photo_user_id/$get_photo_thumb_200");
					} // thumb

				echo"

				<a href=\"view_profile.php?action=view_photo&amp;user_id=$get_photo_user_id&amp;photo_id=$get_photo_id&amp;l=$l\"><img src=\"$root/_uploads/users/images/$get_photo_user_id/$get_photo_thumb_200\" alt=\"$get_photo_destination\" class=\"photo_img\" /></a>
						
				";
			} // image exists

		} // while images
		echo"
	<!-- //Images -->
	
	";
}
?>