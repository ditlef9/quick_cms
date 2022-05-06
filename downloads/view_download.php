<?php
/**
*
* File: downloads/view_download.php
* Version 11:07 15.11.2020
* Copyright (c) 2009-2020 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Configuration ---------------------------------------------------------------------------- */
$pageIdSav            = "0";
$pageNoColumnSav      = "2";
$pageAllowCommentsSav = "0";

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

/*- Tables ---------------------------------------------------------------------------- */
include("_tables_downloads.php");


/*- Translation ------------------------------------------------------------------------------ */

/*- Varialbes  ---------------------------------------------------- */
if(isset($_GET['download_id'])) {
	$download_id = $_GET['download_id'];
	$download_id = strip_tags(stripslashes($download_id));
}
else{
	$download_id = "";
}
if(isset($_GET['main_category_id'])) {
	$main_category_id = $_GET['main_category_id'];
	$main_category_id = strip_tags(stripslashes($main_category_id));
}
else{
	$main_category_id = "";
}
if(isset($_GET['sub_category_id'])) {
	$sub_category_id = $_GET['sub_category_id'];
	$sub_category_id = strip_tags(stripslashes($sub_category_id));
}
else{
	$sub_category_id = "";
}
if(isset($_GET['image'])) {
	$image = $_GET['image'];
	$image = strip_tags(stripslashes($image));
}
else{
	$image = "";
}
$l_mysql = quote_smart($link, $l);


/*- Find download ------------------------------------------------------------------------------ */
$download_id_mysql = quote_smart($link, $download_id);
$query = "SELECT download_id, download_title, download_title_short, download_title_length, download_language, download_introduction, download_description, download_video, download_image_path, download_image_store, download_image_store_thumb, download_image_thumb_a, download_image_thumb_b, download_image_thumb_c, download_image_thumb_d, download_image_file_a, download_image_file_b, download_image_file_c, download_image_file_d, download_read_more_url, download_main_category_id, download_sub_category_id, download_internal_external, download_file_external_url, download_dir, download_file, download_type, download_version, download_file_size, download_file_date, download_file_date_print, download_last_download, download_hits, download_unique_hits, download_ip_block, download_tag_a, download_tag_b, download_tag_c, download_created_datetime, download_updated_datetime, download_updated_print, download_have_to_be_logged_in_to_download FROM $t_downloads_index WHERE download_id=$download_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_download_id, $get_current_download_title, $get_current_download_title_short, $get_current_download_title_length, $get_current_download_language, $get_current_download_introduction, $get_current_download_description, $get_current_download_video, $get_current_download_image_path, $get_current_download_image_store, $get_current_download_image_store_thumb, $get_current_download_image_thumb_a, $get_current_download_image_thumb_b, $get_current_download_image_thumb_c, $get_current_download_image_thumb_d, $get_current_download_image_file_a, $get_current_download_image_file_b, $get_current_download_image_file_c, $get_current_download_image_file_d, $get_current_download_read_more_url, $get_current_download_main_category_id, $get_current_download_sub_category_id, $get_current_download_internal_external, $get_current_download_file_external_url, $get_current_download_dir, $get_current_download_file, $get_current_download_type, $get_current_download_version, $get_current_download_file_size, $get_current_download_file_date, $get_current_download_file_date_print, $get_current_download_last_download, $get_current_download_hits, $get_current_download_unique_hits, $get_current_download_ip_block, $get_current_download_tag_a, $get_current_download_tag_b, $get_current_download_tag_c, $get_current_download_created_datetime, $get_current_download_updated_datetime, $get_current_download_updated_print, $get_current_download_have_to_be_logged_in_to_download) = $row;

if($get_current_download_id == ""){
	echo"
	<h1>Server error 404</h1>
	<p>Download not found.</p>
	";
}
else{
	$query = "SELECT main_category_id, main_category_title, main_category_icon_path, main_category_icon_file FROM $t_downloads_main_categories WHERE main_category_id='$get_current_download_main_category_id'";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_main_category_id, $get_current_main_category_title, $get_current_main_category_icon_path, $get_current_main_category_icon_file) = $row;

	if($get_current_main_category_id == ""){
		echo"
		<h1>Server error 404</h1>
		<p>Main category not found.</p>
		";
		
		// We have a download without category?
		if($get_current_download_main_category_id == "0"){
			// Pick a random category
			$query = "SELECT main_category_id, main_category_title, main_category_icon_path, main_category_icon_file FROM $t_downloads_main_categories LIMIT 0,1";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_main_category_id, $get_current_main_category_title, $get_current_main_category_icon_path, $get_current_main_category_icon_file) = $row;

			$result = mysqli_query($link, "UPDATE $t_downloads_index SET  download_main_category_id='$get_current_main_category_id' WHERE download_id='$get_current_download_id'") or die(mysqli_error($link));
			
			// Refresh
			$time = date("H:i:s");
			echo"<meta http-equiv=\"refresh\" content=\"1;url=view_download.php?download_id=$download_id&amp;l=$l&amp;time=$time\" />";
		}
		
	}
	else{
		// Find translation
		$query_t = "SELECT main_category_translation_id, main_category_translation_value FROM $t_downloads_main_categories_translations WHERE main_category_id='$get_current_main_category_id' AND main_category_translation_language=$l_mysql";
		$result_t = mysqli_query($link, $query_t);
		$row_t = mysqli_fetch_row($result_t);
		list($get_current_main_category_translation_id, $get_current_main_category_translation_value) = $row_t;

		if($get_current_download_sub_category_id == "0"){
			$website_title = "$get_current_download_title - $get_current_main_category_translation_value - $l_downloads";
		}
		else{
			// Sub category
			$query = "SELECT sub_category_id, sub_category_title FROM $t_downloads_sub_categories WHERE sub_category_id='$get_current_download_sub_category_id'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_sub_category_id, $get_current_sub_category_title) = $row;

			if($get_current_sub_category_id == ""){
				$website_title = "Server error 404 - $l_downloads";
			}
			else{
				// Find translation
				$query_t = "SELECT sub_category_id, sub_category_translation_value FROM $t_downloads_sub_categories_translations WHERE sub_category_id='$get_current_sub_category_id' AND sub_category_translation_language=$l_mysql";
				$result_t = mysqli_query($link, $query_t);
				$row_t = mysqli_fetch_row($result_t);
				list($get_current_translation_sub_category_id, $get_current_sub_category_translation_value) = $row_t;
		
				if($get_current_sub_category_id == ""){
					$inp_value_mysql = quote_smart($link, $get_current_sub_category_title);
					echo"<p>Sub category translation not found. Fixed automatically. Sub category id=$get_current_sub_category_id, title=$get_current_sub_category_title</p>";
					mysqli_query($link, "INSERT INTO $t_downloads_sub_categories_translations 
							     (sub_category_translation_id, sub_category_id, sub_category_translation_language, sub_category_translation_value)
							     VALUES
							     (NULL, $get_current_sub_category_id, $l_mysql, $inp_value_mysql)") or die(mysqli_error($link));

				}
				$website_title = "$get_current_download_title - $get_current_sub_category_translation_value - $get_current_main_category_translation_value - $l_downloads";
			} // sub category found
		} // has sub category


		/*- Headers ---------------------------------------------------------------------------------- */
		include("$root/_webdesign/header.php");


		/*- Content --------------------------------------------------------------------------- */
		if($action == ""){

			// Headline
			if(file_exists("$root/$get_current_main_category_icon_path/$get_current_main_category_icon_file")){
				echo"
				<table>
				 <tr>
				  <td style=\"padding: 10px 10px 0px 0px;vertical-algin:top;\">
					<img src=\"$root/$get_current_main_category_icon_path/$get_current_main_category_icon_file\" alt=\"$get_current_main_category_icon_file\" />
				  </td>
				  <td style=\"vertical-algin:top;\">
					<h1>$get_current_download_title</h1>
				  </td>
				 </tr>
				</table>
				";
			}
			else{
				echo"
				<h1>$get_current_download_title</h1>
				";
			}
			echo"


			<!-- Where am I ? -->
				<p>
				<b>$l_you_are_here:</b><br />
				<a href=\"index.php?l=$l\">$l_downloads</a>
				&gt;
				<a href=\"open_main_category.php?main_category_id=$get_current_download_main_category_id&amp;l=$l\">$get_current_main_category_translation_value</a>\n";
			
				if($get_current_download_sub_category_id != "0"){
					echo"
					&gt;
					<a href=\"open_sub_category.php?main_category_id=$get_current_download_main_category_id&amp;sub_category_id=$get_current_download_sub_category_id&amp;l=$l\">$get_current_sub_category_translation_value</a>
					";
				}
				echo"
				&gt;
				<a href=\"view_download.php?download_id=$download_id&amp;main_category_id=$get_current_download_main_category_id&amp;sub_category_id=$get_current_download_sub_category_id&amp;l=$l\">$get_current_download_title</a>
				</p>
			<!-- //Where am I ? -->

			<!-- Ad -->
				";
				include("$root/ad/_includes/ad_main_below_headline.php");
				echo"
			<!-- //Ad -->


			<!-- Download info -->
				<h2 style=\"margin-bottom:0;padding-bottom:0;\">$l_info</h2>
				<div id=\"download_info_right\">
					";
					if(file_exists("$root/$get_current_download_image_path/$get_current_download_image_store_thumb") && $get_current_download_image_store_thumb != ""){
						echo"<img src=\"$root/$get_current_download_image_path/$get_current_download_image_store_thumb\" alt=\"$get_current_download_image_store_thumb\" />\n";
					}
					echo"
				</div>
				<div id=\"download_info_left\">
					<p>
					$get_current_download_introduction
					</p>

					<table>
					 <tr>
					  <td style=\"padding: 0px 6px 6px 0px;\">
						<span>
						<span class=\"grey\">$l_name:</span>
						</span>
					  </td>
					  <td style=\"padding: 0px 0px 6px 0px;\">
						<span>
						$get_current_download_file.$get_current_download_type
						</span>
					  </td>
					 </tr>
					 <tr>
					  <td style=\"padding: 0px 6px 6px 0px;\">
						<span>
						<span class=\"grey\">$l_updated:</span>
						</span>
					  </td>
					  <td style=\"padding: 0px 0px 6px 0px;\">
						<span>
						$get_current_download_updated_print
						</span>
					  </td>
					 </tr>";
					if($get_current_download_version != ""){
						echo"
						 <tr>
						  <td style=\"padding: 0px 6px 6px 0px;\">
							<span>
							<span class=\"grey\">$l_version:</span>
							</span>
						  </td>
						  <td style=\"padding: 0px 0px 6px 0px;\">
							<span>
							$get_current_download_version
							</span>
						  </td>
						 </tr>
						";
					}

					if($get_current_download_file_size != ""){
						echo"
						 <tr>
						  <td style=\"padding: 0px 6px 6px 0px;\">
							<span>
							<span class=\"grey\">$l_size:</span>
							</span>
						  </td>
						  <td style=\"padding: 0px 0px 6px 0px;\">
							<span>
							$get_current_download_file_size
							</span>
						  </td>
						 </tr>
						";
					}
					echo"
					 <tr>
					  <td style=\"padding: 0px 6px 6px 0px;\">
						<span>
						<span class=\"grey\">$l_downloads:</span>
						</span>
					  </td>
					  <td style=\"padding: 0px 0px 6px 0px;\">
						<span>
						$get_current_download_hits
						</span>
					  </td>
					 </tr>
					 <tr>
					  <td style=\"padding: 0px 6px 6px 0px;\">
						<span>
						<span class=\"grey\" title=\"$l_unique_downloads\">$l_unique:</span>
						</span>
					  </td>
					  <td style=\"padding: 0px 0px 6px 0px;\">
						<span>
						$get_current_download_unique_hits
						</span>
					  </td>
					 </tr>
					</table>
				</div>
			<!-- //Download info -->

			<!-- Download actions -->
				<div id=\"download_actions\">
					<p><a href=\"view_download.php?action=download&amp;download_id=$download_id&amp;main_category_id=$get_current_download_main_category_id&amp;sub_category_id=$get_current_download_sub_category_id&amp;l=$l&amp;process=1\" class=\"btn_download_now\" title=\"$l_download\">$l_click_to_download</a>
				</div>
			<!-- //Download actions -->


			<!-- Download images -->
			<div id=\"download_images\">
				";
				if(file_exists("$root/$get_current_download_image_path/$get_current_download_image_file_a") && $get_current_download_image_file_a != ""){
					echo"
					<h2>$l_images</h2>
					<a id=\"image\"></a>
					";
					if($image == "" OR $image == "a"){
						echo"
						<img src=\"$root/$get_current_download_image_path/$get_current_download_image_file_a\" alt=\"$get_current_download_image_file_a\" />
						";
					}
					elseif($image == "b"){
						if(file_exists("$root/$get_current_download_image_path/$get_current_download_image_file_b") && $get_current_download_image_file_b != ""){
							echo"
							<img src=\"$root/$get_current_download_image_path/$get_current_download_image_file_b\" alt=\"$get_current_download_image_file_b\" />
							";
						}
					}
					elseif($image == "c"){
						if(file_exists("$root/$get_current_download_image_path/$get_current_download_image_file_c") && $get_current_download_image_file_c != ""){
							echo"
							<img src=\"$root/$get_current_download_image_path/$get_current_download_image_file_c\" alt=\"$get_current_download_image_file_c\" />
							";
						}
					}
					elseif($image == "d"){
						if(file_exists("$root/$get_current_download_image_path/$get_current_download_image_file_d") && $get_current_download_image_file_d != ""){
							echo"
							<img src=\"$root/$get_current_download_image_path/$get_current_download_image_file_d\" alt=\"$get_current_download_image_file_d\" />
							";
						}
					}
				}
				echo"
				<div id=\"thumbs\">
				";
					if(file_exists("$root/$get_current_download_image_path/$get_current_download_image_thumb_a") && $get_current_download_image_thumb_a != ""){
						echo"<a href=\"view_download.php?download_id=$get_current_download_id&amp;image=a&amp;l=$l#image\"><img src=\"$root/$get_current_download_image_path/$get_current_download_image_thumb_a\" alt=\"$get_current_download_image_file_a\" /></a>\n";
					}
					if(file_exists("$root/$get_current_download_image_path/$get_current_download_image_thumb_b") && $get_current_download_image_thumb_b != ""){
						echo"<a href=\"view_download.php?download_id=$get_current_download_id&amp;image=b&amp;l=$l#image\"><img src=\"$root/$get_current_download_image_path/$get_current_download_image_thumb_b\" alt=\"$get_current_download_image_file_b\" /></a>\n";
					}
					if(file_exists("$root/$get_current_download_image_path/$get_current_download_image_thumb_c") && $get_current_download_image_thumb_c != ""){
						echo"<a href=\"view_download.php?download_id=$get_current_download_id&amp;image=c&amp;l=$l#image\"><img src=\"$root/$get_current_download_image_path/$get_current_download_image_thumb_c\" alt=\"$get_current_download_image_file_c\" /></a>\n";
					}
					if(file_exists("$root/$get_current_download_image_path/$get_current_download_image_thumb_d") && $get_current_download_image_thumb_d != ""){
						echo"<a href=\"view_download.php?download_id=$get_current_download_id&amp;image=d&amp;l=$l#image\"><img src=\"$root/$get_current_download_image_path/$get_current_download_image_thumb_d\" alt=\"$get_current_download_image_file_d\" /></a>\n";
					}
					echo"
				</div>
			</div>
			<!-- //Download images -->

			<!-- Description -->
				<div id=\"download_description\">
					$get_current_download_description
				</div>
			<!-- //Description -->

			";
			// New comment and read comments
			if($process != "1"){
				echo"
				<!-- Comments -->
					<a id=\"comments\"></a>

					<!-- Feedback -->
						";
						if(isset($_GET['ft_comment']) && isset($_GET['fm_comment'])){
							$ft_comment = $_GET['ft_comment'];
							$ft_comment = output_html($ft_comment);
							$fm_comment = $_GET['fm_comment'];
							$fm_comment = output_html($fm_comment);
							$fm_comment = str_replace("_", " ", $fm_comment);
							$fm_comment = ucfirst($fm_comment);
							echo"<div class=\"$ft_comment\"><span>$fm_comment</span></div>";
						}
						echo"	
					<!-- //Feedback -->
				";
			}
				include("view_download_include_new_comment.php");
				include("view_download_include_fetch_comments.php");
				
				echo"
			<!-- //Comments -->
			";
		} // action == ""
		elseif($action == "download"){

			if(!(isset($_SESSION['user_id'])) && $get_current_download_have_to_be_logged_in_to_download == "1"){
				$url = "$root/users/login.php?referer=downloads/view_download.php?action=downloadamp;download_id=$download_id&l=$l&ft=info&fm=please_login_or_registrer_to_download_files";
				header("Location: $url");
				exit;
			} // Not logged in
			
			// My IP
			$my_ip = $_SERVER['REMOTE_ADDR'];
			$my_ip = output_html($my_ip);

			$have_downloaded_from_before = "false";
			$inp_ip_block = "";

			$ip_array = explode("\n", $get_current_download_ip_block);
			$ip_array_size = sizeof($ip_array);
			for($x=0;$x<$ip_array_size;$x++){
				$stored_ip = $ip_array[$x];
				if($stored_ip == "$my_ip"){
					$have_downloaded_from_before = "true";
				}
				
				if($x > 5){
					if($inp_ip_block == ""){
						$inp_ip_block = "$stored_ip";
					}
					else{
						$inp_ip_block = $inp_ip_block  . "\n$stored_ip";
					}
				}
			}
				
			if($have_downloaded_from_before == "false"){
				if($inp_ip_block == ""){
					$inp_ip_block = "$my_ip";
				}
				else{
					$inp_ip_block = $my_ip . "\n" . $inp_ip_block;
				}
				$inp_unique_hits = $get_current_download_unique_hits+1;
			}
			else{
				$inp_ip_block = "$get_current_download_ip_block";
				$inp_unique_hits = "$get_current_download_unique_hits";
			}
			$inp_ip_block_mysql = quote_smart($link, $inp_ip_block);
			$inp_unique_hits_mysql = quote_smart($link, $inp_unique_hits);

			$inp_hits = $get_current_download_hits+1;	
					

				$datetime = date("Y-m-d H:i:s");

				$result = mysqli_query($link, "UPDATE $t_downloads_index SET download_last_download='$datetime', download_hits='$inp_hits', 
				download_unique_hits=$inp_unique_hits_mysql, download_ip_block=$inp_ip_block_mysql 
				 WHERE download_id='$get_current_download_id'") or die(mysqli_error($link));

			// Move
			if($process == "1"){
				if($get_current_download_internal_external == "external"){
					$url = "$get_current_download_file_external_url";
					header("Location: $url");
					exit;
				}
				else{	
					$url = "$root/$get_current_download_dir/$get_current_download_file.$get_current_download_type";
					header("Location: $url");
					exit;
				}
			}
			else{
				if($get_current_download_internal_external == "external"){
					echo"
					<h1>$l_now_downloading $get_current_download_title</h1>
					<meta http-equiv=\"refresh\" content=\"1;url=$get_current_download_file_external_url\" />
					";
				}
				else{	
					echo"
					<h1>$l_now_downloading $get_current_download_title</h1>
					<meta http-equiv=\"refresh\" content=\"1;url=$root/$get_current_download_dir/$get_current_download_file.$get_current_download_type\" />
					";
				}
			}
		} // action == "download"

		/*- Footer ---------------------------------------------------------------------------- */
		include("$root/_webdesign/footer.php");

	} // found main category
} // download found


?>