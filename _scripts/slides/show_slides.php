<?php
include("_uploads/slides/$l/slides.php");
$size = sizeof($slide_id);

echo"
			<div id=\"owl-example\" class=\"owl-carousel\">
";

$year = date("Y");
$month = date("m");
$day = date("d");
$hour = date("H");

$time = time();
	
for($x=0;$x<$size;$x++){

	$show_slide = "1";

	// From
	if($slide_active_from_time[$x] < $time){
		$show_slide = "1";
	}
	else{
		$show_slide = "0";
	}

	if($show_slide == "1"){
		echo"


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
		
		";
	}
}
echo"
			</div>
";

?>