<?php
echo"
<div class=\"element_text_box\" id=\"element$get_element_id\" style=\"";
	echo"position: absolute; ";
	echo"top: $get_element_position_top"; echo"px; ";
	echo"left: $get_element_position_left"; echo"px; ";
	echo"width: $get_element_width"; echo"px; ";
	echo"height: $get_element_height"; echo"px; ";
	echo"border: $get_element_border_color 1px solid; ";
	echo"background: $get_element_background_color; ";
	echo"\">

	<!-- ID -->
		<div class=\"element_meta\">
			<span>ID: $get_element_id<br />
			Top&amp;left: $get_element_position_top x $get_element_position_left<br />
			Width&amp;heigt: $get_element_width x $get_element_height</span>
		</div>
	<!-- //ID -->

	<p style=\"color: $get_element_text_color;\">$get_element_text</p>
</div>
";
?>