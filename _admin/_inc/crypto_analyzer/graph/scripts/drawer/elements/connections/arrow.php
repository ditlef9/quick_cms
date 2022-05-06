<?php
echo"
<div class=\"element_arrow\" id=\"element$get_element_id\" style=\"";
	echo"position: absolute; ";
	echo"top: $get_element_position_top"; echo"px; ";
	echo"left: $get_element_position_left"; echo"px; ";
	echo"width: $get_element_width"; echo"px; ";
	echo"height: $get_element_height"; echo"px; ";
	echo"\">



	<svg width=\"300\" height=\"100\">
		<defs>
			<marker id=\"arrow\" markerWidth=\"10\" markerHeight=\"7\" refX=\"0\" refY=\"3.5\" orient=\"auto\">
				<polygon points=\"0 0, 10 3.5, 0 7\" />
			</marker>
		</defs>

		<path d=\"M0,10 L90,10\" style=\"stroke:red; stroke-width: 1.25px; fill: none;marker-end: url(#arrow);\" />
	</svg>



</div>
";
?>