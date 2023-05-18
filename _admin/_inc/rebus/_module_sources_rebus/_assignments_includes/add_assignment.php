<?php
/**
*
* File: rebus/_assignments_includes/add_assignment.php
* Version 1.0.0.
* Date 09:50 01.07.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){

	$script_filename = basename($_SERVER["SCRIPT_FILENAME"]);
	

	// Variables
	$assignment_type = "answer_a_question";
	if(isset($_GET['assignment_type'])) {
		$assignment_type = $_GET['assignment_type'];
		$assignment_type = output_html($assignment_type);
	}

	if($process == "1"){
		// HTML Purifier
		require_once "$root/_admin/_functions/htmlpurifier/HTMLPurifier.auto.php";
		$config = HTMLPurifier_Config::createDefault();
		$purifier = new HTMLPurifier($config);

		$assignment_type_mysql = quote_smart($link, $assignment_type);


		if(isset($_POST['inp_address'])){
			$inp_address = $_POST['inp_address'];
		}
		else{
			$inp_address = "";
		}
		$inp_address = output_html($inp_address);
		$inp_address_mysql = quote_smart($link, $inp_address);

		$inp_answer_a = $_POST['inp_answer_a'];
		$inp_answer_a = output_html($inp_answer_a);
		$inp_answer_a_mysql = quote_smart($link, $inp_answer_a);

		$inp_answer_a_clean = clean($inp_answer_a);
		$inp_answer_a_clean_mysql = quote_smart($link, $inp_answer_a_clean);
	
		if(isset($_POST['inp_answer_b'])){
			$inp_answer_b = $_POST['inp_answer_b'];
		}
		else{
			$inp_answer_b = "";
		}
		$inp_answer_b = output_html($inp_answer_b);
		$inp_answer_b_mysql = quote_smart($link, $inp_answer_b);

		$inp_answer_b_clean = clean($inp_answer_b);
		$inp_answer_b_clean_mysql = quote_smart($link, $inp_answer_b_clean);


		// Radius
		$inp_radius = "10";
		$inp_radius_metric = 10;
		$inp_radius_imperial = 33;
		if(isset($_POST['inp_radius'])){
			$inp_radius = $_POST['inp_radius'];
		}
		$inp_radius = output_html($inp_radius);

		if($get_my_user_measurement == "metric"){
			$inp_radius_metric = "$inp_radius";
			$inp_radius_imperial = round($inp_radius_metric/3.2, 0);
		}
		else{
			$inp_radius_imperial = "$inp_radius";
			$inp_radius_metric = round($inp_radius_imperial*3.2, 0);
		}
		$inp_radius_metric_mysql = quote_smart($link, $inp_radius_metric);
		$inp_radius_imperial_mysql = quote_smart($link, $inp_radius_imperial);

		$inp_radius_user_measurment_mysql = quote_smart($link, $get_my_user_measurement);

		$inp_hint_a_value = $_POST['inp_hint_a_value'];
		//$inp_hint_a_value = output_html($inp_hint_a_value);
		//$inp_hint_a_value_mysql = quote_smart($link, $inp_hint_a_value);
		$inp_hint_a_value = encode_national_letters($inp_hint_a_value);
		$inp_hint_a_value = $purifier->purify($inp_hint_a_value);

		$inp_hint_a_price = $_POST['inp_hint_a_price'];
		if($inp_hint_a_price == ""){ $inp_hint_a_price = 0.5; }
		$inp_hint_a_price = output_html($inp_hint_a_price);
		$inp_hint_a_price_mysql = quote_smart($link, $inp_hint_a_price);

		$inp_hint_b_value = $_POST['inp_hint_b_value'];
		//$inp_hint_b_value = output_html($inp_hint_b_value);
		//$inp_hint_b_value_mysql = quote_smart($link, $inp_hint_b_value);
		$inp_hint_b_value = encode_national_letters($inp_hint_b_value);
		$inp_hint_b_value = $purifier->purify($inp_hint_b_value);

		$inp_hint_b_price = $_POST['inp_hint_b_price'];
		if($inp_hint_b_price == ""){ $inp_hint_b_price = 0.5; }
		$inp_hint_b_price = output_html($inp_hint_b_price);
		$inp_hint_b_price_mysql = quote_smart($link, $inp_hint_b_price);

		$inp_hint_c_value = $_POST['inp_hint_c_value'];
		//$inp_hint_c_value = output_html($inp_hint_c_value);
		//$inp_hint_c_value_mysql = quote_smart($link, $inp_hint_c_value);
		$inp_hint_c_value = encode_national_letters($inp_hint_c_value);
		$inp_hint_c_value = $purifier->purify($inp_hint_c_value);

		$inp_hint_c_price = $_POST['inp_hint_c_price'];
		if($inp_hint_c_price == ""){ $inp_hint_c_price = 0.5; }
		$inp_hint_c_price = output_html($inp_hint_c_price);
		$inp_hint_c_price_mysql = quote_smart($link, $inp_hint_c_price);

		$inp_points = $_POST['inp_points'];
		$inp_points = output_html($inp_points);
		if(!(is_numeric($inp_points))){
			$inp_points = 1;
		}
		$inp_points = round($inp_points, 0);
		$inp_points_mysql = quote_smart($link, $inp_points);

		$inp_text_when_correct_answer = $_POST['inp_text_when_correct_answer'];
		$inp_text_when_correct_answer = output_html($inp_text_when_correct_answer);
		$inp_text_when_correct_answer_mysql = quote_smart($link, $inp_text_when_correct_answer);

		// Time to solve
		$inp_time_to_solve_seconds_mysql = quote_smart($link, 0);
		$inp_time_to_solve_saying_mysql = quote_smart($link, "");

		// Me
		$query = "SELECT user_id, user_email, user_name, user_language, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_language, $get_my_user_rank) = $row;
			
		// Ip 
		$my_ip = $_SERVER['REMOTE_ADDR'];
		$my_ip = output_html($my_ip);
		$my_ip_mysql = quote_smart($link, $my_ip);

		// Get weight
		$query = "SELECT count(assignment_id) FROM $t_rebus_games_assignments WHERE assignment_game_id=$get_current_game_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_count_assignment_id) = $row;
		$inp_number = $get_count_assignment_id+1;

		mysqli_query($link, "INSERT INTO $t_rebus_games_assignments
		(assignment_id, assignment_game_id, assignment_number, assignment_type, 
		assignment_address, assignment_answer_a, assignment_answer_a_clean, assignment_answer_b, 
		assignment_answer_b_clean, assignment_radius_metric, assignment_radius_imperial, assignment_radius_user_measurment, assignment_hint_a_price, 
		assignment_hint_b_price, assignment_hint_c_price, assignment_points, assignment_text_when_correct_answer, assignment_time_to_solve_seconds, 
		assignment_time_to_solve_saying, assignment_created_by_user_id, assignment_created_by_ip, assignment_created_datetime) 
		VALUES 
		(NULL, $get_current_game_id, $inp_number, $assignment_type_mysql, 
		$inp_address_mysql, $inp_answer_a_mysql, $inp_answer_a_clean_mysql, $inp_answer_b_mysql, 
		$inp_answer_b_clean_mysql, $inp_radius_metric_mysql, $inp_radius_imperial_mysql, $inp_radius_user_measurment_mysql, $inp_hint_a_price_mysql, 
		$inp_hint_b_price_mysql,  $inp_hint_c_price_mysql,  $inp_points_mysql, $inp_text_when_correct_answer_mysql, $inp_time_to_solve_seconds_mysql, 
		$inp_time_to_solve_saying_mysql, $get_my_user_id, $my_ip_mysql, '$datetime')") or die(mysqli_error($link));

		// Update game_number_of_assignments
		mysqli_query($link, "UPDATE $t_rebus_games_index SET
						game_number_of_assignments=$inp_number 
						WHERE game_id=$get_current_game_id") or die(mysqli_error($link));

			

		// Get ID
		$query = "SELECT assignment_id FROM $t_rebus_games_assignments WHERE assignment_game_id=$get_current_game_id ORDER BY assignment_id DESC LIMIT 0,1";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_assignment_id) = $row;

		// Assignment value
		$inp_assignment_value = $_POST['inp_assignment_value'];
		//$inp_assignment_value = output_html($inp_assignment_value);
		//$inp_assignment_value_mysql = quote_smart($link, $inp_assignment_value);
		$inp_assignment_value = encode_national_letters($inp_assignment_value);
		$inp_assignment_value = $purifier->purify($inp_assignment_value);

		$inp_assignment_value_short = $_POST['inp_assignment_value'];
		$inp_assignment_value_short = strip_tags($inp_assignment_value_short);
		$inp_assignment_value_short = output_html($inp_assignment_value_short);
		$inp_assignment_value_short_len = strlen($inp_assignment_value_short);
		if($inp_assignment_value_short_len > 100){
			$inp_assignment_value_short = substr($inp_assignment_value_short, 0, 96);
			$inp_assignment_value_short = $inp_assignment_value_short  . "...";
		}

		$sql = "UPDATE $t_rebus_games_assignments SET assignment_value=?, assignment_value_short=?, assignment_hint_a_value=?, assignment_hint_b_value=?, assignment_hint_c_value=? WHERE assignment_id=$get_current_assignment_id";
		$stmt = $link->prepare($sql);
		$stmt->bind_param("sssss", $inp_assignment_value, $inp_assignment_value_short, $inp_hint_a_value, $inp_hint_b_value, $inp_hint_c_value);
		$stmt->execute();
		if ($stmt->errno) {
			echo "FAILURE!!! " . $stmt->error; die;
		}

		// Game latitude and longitude is the first assignment with take a picture
		$query = "SELECT assignment_id, assignment_answer_a, assignment_answer_b FROM $t_rebus_games_assignments WHERE assignment_game_id=$get_current_game_id AND assignment_type='take_a_picture_with_coordinates' ORDER BY assignment_id ASC LIMIT 0,1";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_assignment_id, $get_assignment_answer_a, $get_assignment_answer_b) = $row;
		if($get_assignment_id != ""){
			if($get_assignment_answer_a != "" && $get_assignment_answer_b != ""){
				if($get_current_game_latitude != "$get_assignment_answer_a" OR $get_current_game_longitude != "$get_assignment_answer_b"){
					
					$inp_game_latitude_mysql = quote_smart($link, $get_assignment_answer_a);
					$inp_game_longitude_mysql = quote_smart($link, $get_assignment_answer_b);

					mysqli_query($link, "UPDATE $t_rebus_games_index SET
						game_latitude=$inp_game_latitude_mysql, 
						game_longitude=$inp_game_longitude_mysql 
						WHERE game_id=$get_current_game_id") or die(mysqli_error($link));

				}
			}
		}
		


		// Header
		$url = "";
		if($script_filename == "create_game_step_8_add_assignment.php"){
			$url = "create_game_step_8_add_assignment.php?game_id=$get_current_game_id&assignment_type=$assignment_type&l=$l&ft=success&fm=assignment_added";
		}
		else{
			$url = "edit_game_add_assignment.php?game_id=$get_current_game_id&assignment_type=$assignment_type&l=$l&ft=success&fm=assignment_added";
		}
		header("Location: $url");
		exit;
	} // process == 1

	echo"
	<!-- Headline -->
		<h1>$get_current_game_title</h1>
	<!-- //Headline -->

	<!-- Where am I ? -->
		<p><b>$l_you_are_here:</b><br />
		<a href=\"index.php?l=$l\">$l_rebus</a>
		&gt;
		<a href=\"my_games.php?l=$l\">$l_my_games</a>
		&gt;
		<a href=\"edit_game.php?game_id=$get_current_game_id&amp;l=$l\">$get_current_game_title</a>
		&gt;";
		if($script_filename == "create_game_step_8_add_assignment.php"){
			echo"
			<a href=\"create_game_step_9_assignments_overview.php?game_id=$get_current_game_id&amp;l=$l\">$l_assignments</a>
			&gt;
			<a href=\"create_game_step_8_add_assignment.php?game_id=$get_current_game_id&amp;l=$l\">$l_add_assignment</a>
			";
		}
		else{
			echo"
			<a href=\"edit_game_assignments.php?game_id=$get_current_game_id&amp;l=$l\">$l_assignments</a>
			&gt;
			<a href=\"edit_game_add_assignment.php?game_id=$get_current_game_id&amp;l=$l\">$l_add_assignment</a>
			";
		}
		echo"
		</p>
	<!-- //Where am I ? -->

	<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				if($fm == "assignment_added"){
					$fm = "$l_assignment_added";
					if($script_filename == "create_game_step_8_add_assignment.php"){
						
						$fm = $fm . "</p>
						<p><a href=\"create_game_step_9_assignments_overview.php?game_id=$get_current_game_id&amp;l=$l\" class=\"btn_default\">$l_next &gt;</a>
						";
					}
				}
				else{
					$fm = ucfirst($fm);
					$fm = str_replace("_", " ", $fm);
				}
				echo"<div class=\"$ft\"><p>$fm</p>";

				echo"</div>";
			}
			echo"
	<!-- //Feedback -->

	<!-- Focus -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_assignment_value\"]').focus();
			});
			</script>
	<!-- //Focus -->

	<!-- Add question form -->
		<h2>$l_add_assignment</h2>";

		if($script_filename == "create_game_step_8_add_assignment.php"){
			echo"
			<form method=\"post\" action=\"create_game_step_8_add_assignment.php?game_id=$get_current_game_id&amp;assignment_type=$assignment_type&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

			<p><b>$l_assignment_type:</b><br />
			<select name=\"assignment_type\" class=\"on_select_go_to_url\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
				<option value=\"create_game_step_8_add_assignment.php?game_id=$get_current_game_id&amp;l=$l&amp;assignment_type=answer_a_question\""; if($assignment_type == "answer_a_question"){ echo" selected=\"selected\""; } echo">$l_answer_a_question</option>
				<option value=\"create_game_step_8_add_assignment.php?game_id=$get_current_game_id&amp;l=$l&amp;assignment_type=take_a_picture_with_coordinates\""; if($assignment_type == "take_a_picture_with_coordinates"){ echo" selected=\"selected\""; } echo">$l_take_a_picture_with_coordinates</option>
			</select>
			</p>
			";
		}
		else{
			echo"
			<form method=\"post\" action=\"edit_game_add_assignment.php?game_id=$get_current_game_id&amp;assignment_type=$assignment_type&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
			<p><b>$l_assignment_type:</b><br />
			<select name=\"assignment_type\" class=\"on_select_go_to_url\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
				<option value=\"edit_game_add_assignment.php?game_id=$get_current_game_id&amp;l=$l&amp;assignment_type=answer_a_question\""; if($assignment_type == "answer_a_question"){ echo" selected=\"selected\""; } echo">$l_answer_a_question</option>
				<option value=\"edit_game_add_assignment.php?game_id=$get_current_game_id&amp;l=$l&amp;assignment_type=take_a_picture_with_coordinates\""; if($assignment_type == "take_a_picture_with_coordinates"){ echo" selected=\"selected\""; } echo">$l_take_a_picture_with_coordinates</option>
			</select>
			</p>
			";
		}
		echo"

		<!-- On select go to URL -->
			<script>
				\$(function(){
					// bind change event to select
					\$('.on_select_go_to_url').on('change', function () {
						var url = $(this).val(); // get selected value
						if (url) { // require a URL
							window.location = url; // redirect
						}
						return false;
					});
				});
				</script>
		<!-- //On select go to URL -->


		<!-- TinyMCE -->
				<script type=\"text/javascript\" src=\"$root/_admin/_javascripts/tinymce/tinymce.min.js\"></script>
				<script>
					tinymce.init({
						selector: 'textarea.editor',
						plugins: 'print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern help',
						toolbar: 'formatselect | bold italic strikethrough forecolor backcolor permanentpen formatpainter | link image media pageembed | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent | removeformat | addcomment',
						image_advtab: true,
						content_css: [
							'//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
							'//www.tiny.cloud/css/codepen.min.css'
						],
						link_list: [
							{ title: 'My page 1', value: 'http://www.tinymce.com' },
							{ title: 'My page 2', value: 'http://www.moxiecode.com' }
							],
						image_list: [";
						$x = 0;
						$query = "SELECT image_id, image_path, image_file, image_name FROM $t_rebus_games_assignments_images WHERE image_game_id=$get_current_game_id ORDER BY image_name ASC";
						$result = mysqli_query($link, $query);
						while($row = mysqli_fetch_row($result)) {
							list($get_image_id, $get_image_path, $get_image_file, $get_image_name) = $row;
							
							if($x > 0){
								echo",\n";
							}

							echo"							";
							echo"{ title: '$get_image_name', value: '$root/$get_image_path/$get_image_file' }";
							$x++;
						}
						echo"
						],
						image_class_list: [
							{ title: 'None', value: '' },
							{ title: 'Some class', value: 'class-name' }
						],
						importcss_append: true,
						height: 600,
						/* without images_upload_url set, Upload tab won't show up*/
						images_upload_url: 'create_game_step_8_add_assignment_upload_image.php?game_id=$get_current_game_id&process=1',
					});
				</script>
		<!-- //TinyMCE -->

		";
		if($assignment_type == "answer_a_question"){
			echo"
			<p><b>$l_question*:</b><br />
			<textarea name=\"inp_assignment_value\" value=\"\" class=\"editor\" rows=\"5\" cols=\"20\" style=\"width: 99%;\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /></textarea>
			</p>

			<p><b>$l_answer_alt_1*:</b><br />
			<input type=\"text\" name=\"inp_answer_a\" value=\"\" size=\"25\" style=\"width: 99%;\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
			</p>

			<p><b>$l_answer_alt_2:</b><br />
			<input type=\"text\" name=\"inp_answer_b\" value=\"\" size=\"25\" style=\"width: 99%;\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
			</p>
			";
		} // assignment_type
		elseif($assignment_type == "take_a_picture_with_coordinates"){
			echo"
			<p><b>$l_take_a_picture_of*:</b><br />
			<textarea name=\"inp_assignment_value\" value=\"\" class=\"editor\" rows=\"5\" cols=\"20\" style=\"width: 99%;\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /></textarea>
			</p>
	
			<p>
			<a id=\"upload_image_with_coordinates_link\"></a>
			$l_to_add_coordinates_you_can_either_upload_a_picture_with_correct_coordinates_enter_address_click_on_map_or_enter_coordinates_manually.
			</p>

			<p><b>$l_alt 1 &middot; $l_upload_image_with_coordinates:</b><br />
			<input type=\"file\" name=\"inp_image\" id=\"sortpicture\" accept=\"image/*\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /> 
			<a href=\"#upload_image_with_coordinates_link\" id=\"coordinates_from_image_btn\" class=\"btn_default\">$l_get_coordinates_from_image</a>
			<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" id=\"loading_image\" />
			</p>
			<div id=\"coordinates_from_image_result\"></div>

			<!-- Javascript get coordinates from image -->
				<script>
				\$(document).ready(function(){
					\$(\"#sortpicture\").change(function() {
						\$(\"#coordinates_from_image_btn\").trigger(\"click\");
					});
					\$(\"#coordinates_from_image_btn\").click(function () {
						// Loading
						\$(\"#loading_image\").fadeToggle();

						\$(\"#coordinates_from_image_result\").html('<span>Loading...</span>'); 



						var file_data = \$('#sortpicture').prop('files')[0];   
						var form_data = new FormData();                  
						form_data.append('file', file_data);

						\$.ajax({
							url: 'create_game_step_8_add_assignment_find_coordinates_from_image.php?game_id=$get_current_game_id&l=$l', // <-- point to server-side PHP script 
							dataType: 'text',  // <-- what to expect back from the PHP script, if anything
							cache: false,
							contentType: false,
							processData: false,
							data: form_data,                         
							type: 'post',
							success: function(php_script_response){
								\$(\"#coordinates_from_image_result\").html(php_script_response); // <-- display response from the PHP script, if any
								\$(\"#loading_image\").fadeToggle();
							}
						});

					});
				});
				</script>
			<!-- //Javascript get coordinates from image -->

			<p><b>$l_alt 2 &middot; $l_enter_address:</b><br />
			<input type=\"text\" name=\"inp_address\" value=\"\" size=\"25\" style=\"width: 99%;\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
			</p>

			<!-- leaflet -->
			";
			// Get my last used coordinate, if it doesnt exist, then get the last used in my country
			$query = "SELECT assignment_id, assignment_answer_a, assignment_answer_b FROM $t_rebus_games_assignments WHERE assignment_type='take_a_picture_with_coordinates' AND assignment_created_by_user_id=$my_user_id_mysql ORDER BY assignment_id DESC LIMIT 0,1";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_last_assignment_id, $get_last_assignment_answer_a, $get_last_assignment_answer_b) = $row;
			if($get_last_assignment_id == "" OR $get_last_assignment_answer_a == ""){
				$get_last_assignment_answer_a = "51.505";
				$get_last_assignment_answer_b = "-0.09";
			}

			echo"
			<script src=\"$root/_admin/_javascripts/leaflet/leaflet.js\" crossorigin=\"\"></script>

			<p><b>$l_alt 3 &middot; $l_click_on_map_to_get_coordinates:</b></p>
			 <div id=\"map\" style=\"width: 100%; height: 450px;\"></div>

			<!-- Add game assignment - Map script -->
				<script>";
				if($get_last_assignment_id == ""){
					echo"
					var map = L.map('map').fitWorld();

					L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
							maxZoom: 18,
							attribution: 'Map data &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors, ' +
							'Imagery © <a href=\"https://www.mapbox.com/\">Mapbox</a>',
							id: 'mapbox/streets-v11',
							tileSize: 512,
							zoomOffset: -1
						}).addTo(map);

						function onLocationFound(e) {
							var radius = e.accuracy / 2;
							L.marker(e.latlng).addTo(map)
							.bindPopup(\"Your location\").openPopup();

							L.circle(e.latlng, radius).addTo(map);
						}

						function onLocationError(e) {
							alert(e.message);
						}

						map.on('locationfound', onLocationFound);
						map.on('locationerror', onLocationError);
						map.locate({setView: true, maxZoom: 16});
						";
					}
					else{
						echo"
						var map = L.map('map').setView([$get_last_assignment_answer_a, $get_last_assignment_answer_b], 13);
				
						L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
							maxZoom: 18,
							attribution: 'Map data &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors, ' +
							'Imagery © <a href=\"https://www.mapbox.com/\">Mapbox</a>',
							id: 'mapbox/streets-v11',
							tileSize: 512,
							zoomOffset: -1
						}).addTo(map);
						";
					}
					echo"


						var popup = L.popup();
						function onMapClick(e) {
							popup
							.setLatLng(e.latlng)
							.setContent(\"You clicked the map at \" + e.latlng.toString())
							.openOn(map);

							// Fetch coordinates
							var coordinates = e.latlng.toString();
							coordinates = coordinates.replace(\"LatLng(\", \"\"); 
							coordinates = coordinates.replace(\")\", \"\"); 
							coordinates = coordinates.replace(\" \", \"\"); 

							// Split coordinates to lat and lng
							var coordinates_split = coordinates.split(\",\");

							document.getElementById(\"inp_answer_a\").value=coordinates_split[0];
							document.getElementById(\"inp_answer_b\").value=coordinates_split[1];
						}
						map.on('click', onMapClick);


					</script>
			<!-- //Add game assignment - Map script -->
			<!-- //leaflet -->

			<p style=\"padding-bottom:0;margin-bottom:0;\"><b>$l_alt 4 &middot; $l_enter_coordinates_manually*:</b></p>
			<table>
			 <tr>
			  <td>
				<span>$l_latitude<br />
				<input type=\"text\" name=\"inp_answer_a\" id=\"inp_answer_a\" value=\"\" size=\"19\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /><br />
				</span>
			  </td>
			  <td>
				<span>
				$l_longitude<br />
				<input type=\"text\" name=\"inp_answer_b\" id=\"inp_answer_b\" value=\"\" size=\"19\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
				</span>
			  </td>
			 </tr>
			</table>
			
			<!-- Javascript on , in coordinates then split -->
				<script>
				\$(document).ready(function(){
					\$('#inp_answer_a').on('input',function(e){
						var checkForComma = $(\"#inp_answer_a\").val();

						if (checkForComma.indexOf(\",\") >= 0){
							const coordinatesArr = checkForComma.split(\",\");
							coordinatesArr[1] = coordinatesArr[1].replace(\" \", \"\"); 

							\$(\"#inp_answer_a\").val(coordinatesArr[0]);
							\$(\"#inp_answer_b\").val(coordinatesArr[1]);
						}

					});
				});
				</script>
			<!-- //Javascript on , in coordinates then split -->


				<!-- Radius -->
				<div class=\"slidecontainer\">
					<p>$l_radius: <span id=\"radius_output\"></span> ";
					if($get_my_user_measurement == "metric"){
						echo"
						<span id=\"measurment_saying\">$l_meters_lowercase</span>
						[<a href=\"#\" id=\"switch_measurment\">$l_switch_to_feet</a>]
						<input type=\"range\" name=\"inp_radius\"  min=\"1\" max=\"200\" value=\"100\" class=\"slider\" id=\"radius_input\">
						</p>";
					}
					else{
						echo"
						<span id=\"measurment_saying\">$l_feet_lowercase</span>
						[<a href=\"#\" id=\"switch_measurment\">$l_switch_to_meter</a>]
						<input type=\"range\" name=\"inp_radius\"  min=\"1\" max=\"700\" value=\"328\" class=\"slider\" id=\"radius_input\">
						</p>";
					}
					echo"
				</div>

				<!-- Script on change measurment -->
						
					<script id=\"source\" language=\"javascript\" type=\"text/javascript\">
					\$(document).ready(function () {
						\$('#switch_measurment').click(function () {
							// Get input
							var radius = \$('#radius_input').val();

							// Change text
							if(\$('#switch_measurment').text() == '$l_switch_to_meter'){
								// We are on meter
								\$('#measurment_saying').text('$l_meters_lowercase');
								\$('#switch_measurment').text('$l_switch_to_feet');

								var radius_meter = radius*3.2;
								radius_meter = radius_meter.toFixed(0);
								\$('#radius_input').val(radius_meter);
								\$('#radius_output').text(radius_meter);
							}
							else{
								// We are on feet
								\$('#measurment_saying').text('$l_feet_lowercase');
								\$('#switch_measurment').text('$l_switch_to_meter');
								var radius_feet = radius/3.2;
								radius_feet = radius_feet.toFixed(0);
								\$('#radius_input').val(radius_feet);
								\$('#radius_output').text(radius_feet);
							}

        						// ajax call
       							var data = 'game_id=$get_current_game_id';
            						\$.ajax({
                						type: \"GET\",
               							url: \"edit_game_add_assignment_switch_measurment.php\",
                						data: data,
								beforeSend: function(html) { // this happens before actual call
									\$(\"#measurment_saying\").html(''); 
								},
               							success: function(html){
									\$(\"#measurment_saying\").html(''); 
                    							\$(\"#measurment_saying\").html(html);
              							}
            						});
        						return false;
            					});
         				});
					</script>
				<!-- //Script on change measurment -->

				<!-- Script on change slider -->
					<script>
					var slider = document.getElementById(\"radius_input\");
					var output = document.getElementById(\"radius_output\");
					output.innerHTML = slider.value;

					slider.oninput = function() {
						output.innerHTML = this.value;
					}
					</script>
				<!-- //Script on change slider -->
				<!-- //Radius -->
				";
		} // take_a_picture_with_coordinates
		echo"


			<p style=\"padding-bottom:0;margin-bottom:0;\"><b>$l_hint 1:</b><br />
			<textarea name=\"inp_hint_a_value\" class=\"editor\" rows=\"2\" style=\"width: 99%;\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\"></textarea><br />
			$l_hint_price: <input type=\"text\" name=\"inp_hint_a_price\" value=\"1\" size=\"3\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
			</p>
			
			<p style=\"padding-bottom:0;margin-bottom:0;\"><b>$l_hint 2:</b><br />
			<textarea name=\"inp_hint_b_value\" class=\"editor\" rows=\"2\" style=\"width: 99%;\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\"></textarea><br />
			$l_hint_price: <input type=\"text\" name=\"inp_hint_b_price\" value=\"1\" size=\"3\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
			</p>

			<p style=\"padding-bottom:0;margin-bottom:0;\"><b>$l_hint 3:</b><br />
			<textarea name=\"inp_hint_c_value\" class=\"editor\" rows=\"2\" style=\"width: 99%;\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\"></textarea><br />
			$l_hint_price: <input type=\"text\" name=\"inp_hint_c_price\" value=\"1\" size=\"3\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
			</p>



			<p><b>$l_points*:</b><br />
			<input type=\"text\" name=\"inp_points\" value=\"10\" size=\"2\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
			</p>

			<p><b>$l_text_when_correct_answer:</b><br />
			<textarea name=\"inp_text_when_correct_answer\" rows=\"5\" cols=\"20\" style=\"width: 99%;\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\"></textarea>
			</p>

			<div style=\"float: left;\">
				<p><input type=\"submit\" value=\"$l_save\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /></p>
			</div>
			<div style=\"float: right;\">";

				if($script_filename == "create_game_step_8_add_assignment.php"){
					echo"
					<p><a href=\"create_game_step_9_assignments_overview.php?game_id=$get_current_game_id&amp;l=$l\" class=\"btn_default\">$l_assignments &gt;</a></p>
					";
				}
				else{
					echo"
					<p><a href=\"edit_game_assignments.php?game_id=$get_current_game_id&amp;l=$l\" class=\"btn_default\">$l_assignments &gt;</a></p>
					";
				}
				echo"
			</div>
			<div class=\"clear\"></div>
	
		</form>
			
	<!-- //Add question form -->
	";

} // logged in
?>