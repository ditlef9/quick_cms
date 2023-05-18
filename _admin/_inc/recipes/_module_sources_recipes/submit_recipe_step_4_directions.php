<?php 
/**
*
* File: recipes/step_2_directions.php
* Version 1.0.0
* Date 23:59 27.11.2017
* Copyright (c) 2011-2017 Localhost
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
include("_tables.php");

/*- Functions ------------------------------------------------------------------------- */
include("$root/_admin/_functions/encode_national_letters.php");
include("$root/_admin/_functions/decode_national_letters.php");

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/recipes/ts_index.php");


/*- Tables ------------------------------------------------------------------------ */
$t_recipes_images			= $mysqlPrefixSav . "recipes_images";


/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['mode'])){
	$mode = $_GET['mode'];
	$mode = output_html($mode);
}
else{
	$mode = "";
}
if(isset($_GET['recipe_id'])){
	$recipe_id = $_GET['recipe_id'];
	$recipe_id = output_html($recipe_id);
	if(!(is_numeric($recipe_id))){
		echo"Recipe not numeric";
		die;
	}
}
else{
	$recipe_id = "";
}
$tabindex = 0;
$l_mysql = quote_smart($link, $l);

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_submit_recipe - $l_recipes";
include("$root/_webdesign/header.php");

/*- Content ---------------------------------------------------------------------------------- */

// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
	// Get my user
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_user_id, $get_user_email, $get_user_name, $get_user_alias, $get_user_rank) = $row;


	// Get recipe
	$recipe_id_mysql = quote_smart($link, $recipe_id);

	$query = "SELECT recipe_id, recipe_title, recipe_directions FROM $t_recipes WHERE recipe_id=$recipe_id_mysql AND recipe_user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_recipe_id, $get_recipe_title, $get_recipe_directions) = $row;

	if($get_recipe_id == ""){
		echo"
		<h1>Server error</h1>

		<p>
		Recipe not found.
		</p>
		";
	}
	else{
		if($process == 1){


			$inp_recipe_directions = $_POST['inp_recipe_directions'];
			if(empty($inp_recipe_directions)){
				$ft = "error";
				$fm = "directions_cant_be_empty";
				$url = "submit_recipe_step_4_directions.php?recipe_id=$get_recipe_id&l=$l&ft=$ft&fm=$fm";
				header("Location: $url");
				exit;
			}

			require_once "$root/_admin/_functions/htmlpurifier/HTMLPurifier.auto.php";
			$config = HTMLPurifier_Config::createDefault();
			$purifier = new HTMLPurifier($config);

			if($get_user_rank == "admin" OR $get_user_rank == "moderator" OR $get_user_rank == "editor"){
			}
			elseif($get_user_rank == "trusted"){
			}
			else{
				// p, ul, li, b
				$config->set('HTML.Allowed', 'p,b,strong,a[href],i,ul,li');
				$inp_recipe_directions = $purifier->purify($inp_recipe_directions);
			}

			$inp_recipe_directions = encode_national_letters($inp_recipe_directions);

			$sql = "UPDATE $t_recipes SET recipe_directions=? WHERE recipe_id=$get_recipe_id";
			$stmt = $link->prepare($sql);
			$stmt->bind_param("s", $inp_recipe_directions);
			$stmt->execute();
			if ($stmt->errno) {
				echo "FAILURE!!! " . $stmt->error; die;
			}



			// Header
			if(isset($_GET['redirect'])){
				echo"Ok";	
				die;
			}
			else{
				$url = "submit_recipe_step_5_images.php?recipe_id=$get_recipe_id&l=$l";
				header("Location: $url");
				exit;
			}
		}


		// No data? Setup a template!
		include("$root/_admin/_translations/site/$l/recipes/ts_view_recipe.php");

		echo"
		<h1>$l_submit_recipe - $get_recipe_title</h1>
	

		<!-- Focus -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_recipe_directions\"]').focus();
			});
			</script>
		<!-- //Focus -->

		<!-- Feedback -->
			";
			if($ft != ""){
				if($fm == "changes_saved"){
					$fm = "$l_changes_saved";
				}
				elseif($fm == "directions_cant_be_empty"){
					$fm = "$l_directions_cant_be_empty";
				}
				else{
					$fm = str_replace("_", " ", $fm);
					$fm = ucfirst($fm);
				}
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
		<!-- //Feedback -->


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
					image_list: [\n";
					$x = 0;
					$query = "SELECT image_id, image_user_id, image_recipe_id, image_title, image_text, image_path, image_thumb_a, image_thumb_b, image_thumb_c, image_file, image_photo_by_name, image_photo_by_website, image_uploaded_datetime, image_uploaded_ip, image_unique_views, image_ip_block, image_reported, image_reported_checked, image_likes, image_dislikes, image_likes_dislikes_ipblock, image_comments FROM $t_recipes_images WHERE image_recipe_id=$get_recipe_id ORDER BY image_id ASC";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_image_id, $get_image_user_id, $get_image_recipe_id, $get_image_title, $get_image_text, $get_image_path, $get_image_thumb_a, $get_image_thumb_b, $get_image_thumb_c, $get_image_file, $get_image_photo_by_name, $get_image_photo_by_website, $get_image_uploaded_datetime, $get_image_uploaded_ip, $get_image_unique_views, $get_image_ip_block, $get_image_reported, $get_image_reported_checked, $get_image_likes, $get_image_dislikes, $get_image_likes_dislikes_ipblock, $get_image_comments) = $row;
						if($x != 0){
							echo",";
						}

						echo"\n						";
						echo"{ title: '$get_image_title', value: '$root/$get_image_path/$get_image_file' }";
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
					images_upload_url: 'submit_recipe_step_4_directions_upload_image.php?recipe_id=$get_recipe_id&process=1',
				});
				</script>
		<!-- //TinyMCE -->

		<!-- Form -->

			<form method=\"post\" action=\"submit_recipe_step_4_directions.php?l=$l&amp;recipe_id=$get_recipe_id&amp;process=1\" enctype=\"multipart/form-data\">
			
			<span id=\"ditlef_save_results\"></span>

			<p><b>$l_directions</b><br />
			<textarea name=\"inp_recipe_directions\" id=\"inp_recipe_directions\" rows=\"20\" cols=\"70\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" class=\"editor\">$get_recipe_directions</textarea>
			</p>

				<p>
				<input type=\"submit\" value=\"$l_continue\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				</p>

			</form>

			<!-- Autosave every 60 seconds -->
				<script>
				\$(document).ready(function(){
					setInterval(function(){ 
						//code goes here that will be run every 5 seconds.

 						// forming the queryString
						var inp_recipe_directions = \$(\"#inp_recipe_directions\").val();
      						var data            	  = 'l=$l&recipe_id=$get_recipe_id&inp_recipe_directions=' + inp_recipe_directions;

        					// if searchString is not empty
        					// ajax call
            					\$.ajax({
                					type: \"POST\",
               						url: \"submit_recipe_step_4_directions_autosave.php\",
                					data: data,
							beforeSend: function(html) { // this happens before actual call
								\$(\"#ditlef_save_results\").html('$l_saving'); 
							},
               						success: function(html){
                    						\$(\"#ditlef_save_results\").html('$l_saved ' + html + '');
              						}
            					});
       					}, 5000);
				});

				</script>

			<!-- //Autosave every 60 seconds -->


		<!-- //Form -->
		";
	} // recipe found
}
else{
	$action = "noshow";
	echo"
	<h1>
	<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/index.php?page=login&amp;l=$l&amp;refer=$root/recipes/submit_recipe.php\">
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>