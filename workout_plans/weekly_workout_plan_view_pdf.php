<?php 
/**
*
* File: workout_plans/weekly_workout_plan_view_pdf.php
* Version 1.0.0
* Date 12:05 10.02.2018
* Copyright (c) 2011-2018 S. A. Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/



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
include("_tables.php");

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/workout_plans/ts_new_workout_plan.php");
include("$root/_admin/_translations/site/$l/workout_plans/ts_yearly_workout_plan_edit.php");

/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['weekly_id'])){
	$weekly_id = $_GET['weekly_id'];
	$weekly_id = output_html($weekly_id);
}
else{
	$weekly_id = "";
}


$tabindex = 0;
$l_mysql = quote_smart($link, $l);

/*- Function ------------------------------------------------------------------------- */
function remove_html_tags($value){
	
	$value = str_replace("&aelig;", "æ", "$value"); // &#230;
	$value = str_replace("&oslash;","ø", "$value"); // &#248;
	$value = str_replace("&aring;", "å", "$value"); // &#229;
	$value = str_replace("&Aelig;", "Æ", "$value"); // &#198;
	$value = str_replace("&Oslash;","Ø", "$value"); // &#216;
	$value = str_replace("&Aring;", "Å", "$value"); // &#197;

	$value = str_replace("&middot;", "·", "$value"); // &#197;

	return $value;
}


// Get workout plan weekly
$weekly_id_mysql = quote_smart($link, $weekly_id);
$query = "SELECT workout_weekly_id, workout_weekly_user_id, workout_weekly_period_id, workout_weekly_weight, workout_weekly_language, workout_weekly_title, workout_weekly_title_clean, workout_weekly_introduction, workout_weekly_goal, workout_weekly_image_path, workout_weekly_image_file, workout_weekly_created, workout_weekly_updated, workout_weekly_unique_hits, workout_weekly_unique_hits_ip_block, workout_weekly_comments, workout_weekly_likes, workout_weekly_dislikes, workout_weekly_rating, workout_weekly_ip_block, workout_weekly_user_ip, workout_weekly_notes FROM $t_workout_plans_weekly WHERE workout_weekly_id=$weekly_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_workout_weekly_id, $get_current_workout_weekly_user_id, $get_current_workout_weekly_period_id, $get_current_workout_weekly_weight, $get_current_workout_weekly_language, $get_current_workout_weekly_title, $get_current_workout_weekly_title_clean, $get_current_workout_weekly_introduction, $get_current_workout_weekly_goal, $get_current_workout_weekly_image_path, $get_current_workout_weekly_image_file, $get_current_workout_weekly_created, $get_current_workout_weekly_updated, $get_current_workout_weekly_unique_hits, $get_current_workout_weekly_unique_hits_ip_block, $get_current_workout_weekly_comments, $get_current_workout_weekly_likes, $get_current_workout_weekly_dislikes, $get_current_workout_weekly_rating, $get_current_workout_weekly_ip_block, $get_current_workout_weekly_user_ip, $get_current_workout_weekly_notes) = $row;
if($get_current_workout_weekly_id == ""){

	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "Server error 404 - $l_workout_plans";
	include("$root/_webdesign/header.php");
	echo"<h1>Server error 404</h1><p>Plan not found</p>";
	include("$root/_webdesign/footer.php");
	
}
else{
	/*- PDF variables */
	include("$root/_admin/_data/logo.php");
	$server_name = $_SERVER["SERVER_NAME"];
	$server_name = ucfirst($server_name);
	$year = date("Y");
	

	require("$root/_scripts/fpdf/fpdf.php");



	class PDF extends FPDF {
		// Page header
		function Header() {
			// Logo
			global $root;
			global $logoPathPdfSav;
			global $logoFilePdfSav;
    			$this->Image("$root/$logoPathPdfSav/$logoFilePdfSav",170,6);

			// Arial bold 15
			$this->SetFont('Arial','B',15);
			// Move to the right
			$this->Cell(80);
			// Title
			global $get_current_workout_weekly_title;
			$this->Cell(30,10,"$get_current_workout_weekly_title",0,0,'C');
			// Line break
			$this->Ln(20);
		}

		// Page footer
		function Footer(){
			// Position at 1 cm from bottom
			$this->SetY(-10);

			// Arial italic 8
			$this->SetFont('Arial','I',8);

    			
			$this->SetFillColor(0,179,186); // bg
			$this->SetTextColor(255,255,255);

			// Page number
			$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C',true);
		}

	}

	/* PDF */
	$pdf = new PDF();
	$pdf->AliasNbPages();
	$pdf->AddPage();



	/* Sessions */
	$exercise_ids_array = array();

	$query = "SELECT workout_session_id, workout_session_weight, workout_session_title, workout_session_title_clean, workout_session_duration, workout_session_intensity FROM $t_workout_plans_sessions WHERE workout_session_weekly_id=$get_current_workout_weekly_id ORDER BY workout_session_weight ASC";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_workout_session_id, $get_workout_session_weight, $get_workout_session_title, $get_workout_session_title_clean, $get_workout_session_duration, $get_workout_session_intensity) = $row;

		// Remove HTML tags
		$get_workout_session_title = remove_html_tags($get_workout_session_title);

		// Session title
		$pdf->SetFont('Arial','B',13);
    		$pdf->SetTextColor(0,37,74);
		$pdf->Cell(40,10,"$get_workout_session_title");
  		$pdf->SetLineWidth(.3);
    		$pdf->Ln(8); // Line break



		/* List sessions_main */
		$human_counter = 1;


		$img_counter_y = 39;
		$img_counter_x = 5;

		$query_sessions = "SELECT workout_session_main_id, workout_session_main_user_id, workout_session_main_session_id, workout_session_main_weight, workout_session_main_exercise_id, workout_session_main_exercise_title, workout_session_main_sets, workout_session_main_reps, workout_session_main_velocity_a, workout_session_main_velocity_b, workout_session_main_distance, workout_session_main_duration, workout_session_main_intensity, workout_session_main_text FROM $t_workout_plans_sessions_main WHERE workout_session_main_session_id=$get_workout_session_id ORDER BY workout_session_main_weight ASC";
		$result_sessions = mysqli_query($link, $query_sessions);
		while($row_sessions = mysqli_fetch_row($result_sessions)) {
			list($get_workout_session_main_id, $get_workout_session_main_user_id, $get_workout_session_main_session_id, $get_workout_session_main_weight, $get_workout_session_main_exercise_id, $get_workout_session_main_exercise_title, $get_workout_session_main_sets, $get_workout_session_main_reps, $get_workout_session_main_velocity_a, $get_workout_session_main_velocity_b, $get_workout_session_main_distance, $get_workout_session_main_duration, $get_workout_session_main_intensity, $get_workout_session_main_text) = $row_sessions;

			// Remove HTML tags
			$get_workout_session_main_exercise_title = remove_html_tags($get_workout_session_main_exercise_title);
			$get_workout_session_main_text = remove_html_tags($get_workout_session_main_text);

			// Workout images
			// For exercise guide
			$exercise_ids_array[] = "$get_workout_session_main_exercise_title|$get_workout_session_main_exercise_id";
		
			/* Exercise */

			// Set background
			if(isset($style_background) && $style_background == "250,250,250"){
				$style_background = "244,244,244";
			} else{
				$style_background = "250,250,250";
			}
    			$pdf->SetFillColor($style_background);

			
			// Number
			$pdf->SetFont('Arial','B',12);
    			$pdf->SetTextColor(0,0,0);
			$pdf->Cell(1,24," ",'B','0','C', '0,179,186'); // width, height, text, border, ln, align, fill
			$pdf->Cell(3,24,"$human_counter",'B','0','C', '0,179,186'); // width, height, text, border, ln, align, fill
			
			
			// Headline
			$pdf->SetFont('Arial','',12);
			if($get_workout_session_main_text == ""){
				$pdf->Cell(60,24,"$get_workout_session_main_exercise_title",'B','0','L', '0,179,186'); // width, height, text, border, ln, align, fill
			}
			else{
				$pdf->Cell(60,24,"$get_workout_session_main_exercise_title ($get_workout_session_main_text)",'B','0','L', '0,179,186');
			
			}

			
			$extra_text = "   ";
			$middot = false;
			if($get_workout_session_main_sets != 0 && $get_workout_session_main_reps != 0){
				$extra_text = $extra_text . "$get_workout_session_main_sets x $get_workout_session_main_reps";
				$middot = true;
			}
			if($get_workout_session_main_velocity_a != 0 && $get_workout_session_main_velocity_b != 0){
				if($middot == "true"){
					$extra_text = $extra_text . " &middot; ";
				}
				$middot = true;
				$extra_text = $extra_text . "$get_workout_session_main_velocity_a - $get_workout_session_main_velocity_b km/h";
			}
			else{
				if($get_workout_session_main_velocity_a != 0){
					if($middot == "true"){
						$extra_text = $extra_text . " &middot; ";
					}
					$middot = true;
					$extra_text = $extra_text . "$get_workout_session_main_velocity_akm/h";
				}
				if($get_workout_session_main_velocity_b != 0){
				if($middot == "true"){
					$extra_text = $extra_text . "; &middot; ";
				}
				$middot = true;
					$extra_text = $extra_text . "get_workout_session_main_velocity_b km/h";
				}
			}
			if($get_workout_session_main_distance != 0){
				if($middot == "true"){
					$extra_text = $extra_text . "; &middot; ";
				}
				$middot = true;
				$extra_text = $extra_text . "$get_workout_session_main_distance m\n";
			}
			if($get_workout_session_main_duration != 0){
				if($middot == "true"){
					$extra_text = $extra_text . "; &middot; ";
				}
				$middot = true;
				$extra_text = $extra_text . "$get_workout_session_main_duration $l_min_lowercase\n";
			}
			if($get_workout_session_main_intensity != 0){
				if($middot == "true"){
					$extra_text = $extra_text . " &middot; ";
				}
				$middot = true;
				$extra_text = $extra_text . "$get_workout_session_main_intensity\n";
			}
			$extra_text = remove_html_tags($extra_text);
			$pdf->SetY($img_counter_y+13);
			$pdf->Cell(40,8,$extra_text,0);



			// Image
			$pdf->SetY($img_counter_y-1);
			$pdf->SetX(74);
			$pdf->Cell(80,24," ",'B','0','C', '0,179,186'); // width, height, text, border, ln, align, fill
			$img_counter_x = 95;
			$query_images = "SELECT exercise_image_id, exercise_image_type, exercise_image_path, exercise_image_file FROM $t_exercise_index_images WHERE exercise_image_exercise_id='$get_workout_session_main_exercise_id' ORDER BY exercise_image_type DESC LIMIT 0,2";
			$result_images = mysqli_query($link, $query_images);
			while($row_images = mysqli_fetch_row($result_images)) {
				list($get_exercise_image_id, $get_exercise_image_type, $get_exercise_image_path, $get_exercise_image_file) = $row_images;
				if($get_exercise_image_file != "" && file_exists("../$get_exercise_image_path/$get_exercise_image_file")){
					$inp_new_x = 80;
					$inp_new_y = 80;
					$thumb = "exercise_image_" . $get_exercise_image_id . "-" . $inp_new_x . "x" . $inp_new_y . ".png";

					if(!(file_exists("$root/_cache/$thumb"))){
						resize_crop_image($inp_new_x, $inp_new_y, "../$get_exercise_image_path/$get_exercise_image_file", "$root/_cache/$thumb");
					}

					$thumb = "$root/_cache/$thumb";
				}
				else{
					$thumb = "_gfx/no_thumb.png";
				}

				//$pdf->Cell(60,7); // width, height, text, border, ln, align, fill
    				$pdf->Image("$thumb", $img_counter_x, $img_counter_y); // float x [, float y
				$img_counter_x = $img_counter_x+22;
				
			}

			// Muscle
			$img_counter_y = $img_counter_y + 24;
			


			/* Colums for customer */
			

			$pdf->SetLeftMargin(10);
			$pdf->Ln();

			$human_counter++;
		} // end workout_plans_sessions_main

		// Print workout plans session main
		
		

	} // end sessions


	
	/* Print PDF */
	$pdf->Output();
}
?>

