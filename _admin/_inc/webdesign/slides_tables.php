<?php
/**
*
* File: _admin/_inc/slides/mysql_tables.php
* Version 1.0.0
* Date 15.18 03.03.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Access check --------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Tables --------------------------------------------------------------------------- */
$t_slides = $mysqlPrefixSav . "slides";


if($action == ""){
	echo"
	<h1>$l_mysql_tables</h1>
	";

	// Create table slides
	echo"
	<p>$t_slides:";
	$query = "SELECT * FROM $t_slides LIMIT 1";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		echo"
		OK
		";
	}
	else{
		echo" Created table";
		mysqli_query($link, "CREATE TABLE $t_slides(
		   slide_id INT NOT NULL AUTO_INCREMENT,
	  	   PRIMARY KEY(slide_id), 
		   slide_language VARCHAR(70),
		   slide_active INT,
		   slide_active_from_datetime VARCHAR(200),
		   slide_active_from_time VARCHAR(200),
		   slide_active_to_datetime VARCHAR(200),
		   slide_active_to_time VARCHAR(200),
		   slide_active_repeat_yearly INT,
		   slide_active_on_page VARCHAR(200),
		   slide_weight INT,
		   slide_headline VARCHAR(200),
		   slide_image VARCHAR(200),
		   slide_text VARCHAR(200),
		   slide_url VARCHAR(200),
		   slide_link_name VARCHAR(200),
		   slide_edited_by_user_id INT,
		   slide_edited_datetime DATETIME)")
	 	  or die(mysqli_error($link));
	}
	echo"</p>";

	// Dir
	if(!(is_dir("../_slides"))){
		mkdir("../_slides");
	}


	// Create flat file slide
	echo"
	<p>slides/show_slides.php:";
		
		$inp_header ="<?php
include(\"_slides/\$l/slides.php\");
\$size = sizeof(\$slide_id);
";

		$fh = fopen("../_slides/show_slides.php", "w+") or die("can not open file");
		fwrite($fh, $inp_header);
		fclose($fh);

		$inp_header_b ='
echo"
			<div id=\"owl-example\" class=\"owl-carousel\">
";
		';
		$fh = fopen("../_slides/show_slides.php", "a+") or die("can not open file");
		fwrite($fh, $inp_header_b);
		fclose($fh);


		$inp_header_c ="
for(\$x=0;\$x<\$size;\$x++){
	echo\"";
		$fh = fopen("../_slides/show_slides.php", "a+") or die("can not open file");
		fwrite($fh, $inp_header_c);
		fclose($fh);

		$inp_body ='


				<div class=\"owl-slide-$slide_id[$x]\">
					<div class=\"slide_box_wrapper\">
						<div class=\"slide_box_inner\">
							<div class=\"slide_box_content\">
								<div class=\"slide_headline\">
									<a href=\"$slide_url[$x]\" class=\"slide_headline\">$slide_headline[$x]</a>
								</div>
								<div class=\"slide_headline_text_seperator\">
									
								</div>
								<div class=\"slide_text\">
									<a href=\"$slide_url[$x]\" class=\"slide_text\">$slide_text[$x]</a>
								</div>
								<div class=\"slide_button\">
									<a href=\"$slide_url[$x]\" class=\"slide_button\">$slide_link_name[$x]</a>
								</div>
							</div>
						</div>
					</div>
				</div>
		';
		$fh = fopen("../_slides/show_slides.php", "a+") or die("can not open file");
		fwrite($fh, $inp_body);
		fclose($fh);

		$inp_footer ="
	\";
}
echo\"
			</div>
\";

?>";
		$fh = fopen("../_slides/show_slides.php", "a+") or die("can not open file");
		fwrite($fh, $inp_footer);
		fclose($fh);
	echo"</p>";


}
?>