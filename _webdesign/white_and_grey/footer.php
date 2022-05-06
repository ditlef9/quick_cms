<?php
		echo"
				</div> <!-- //main_center_double_column_content -->
			</div> <!-- //main_center_double_column_wrapper -->

	</div> <!-- // main_wrapper -->
<!-- //Main -->


<!-- Footer -->
	<div class=\"clear\"></div>
	<div id=\"footer_wrapper\">
		<div id=\"footer_inner\">
			<div id=\"footer_left\">
				<p>
				$configWebsiteCopyrightSav
				&middot;
				<a href=\"#top\">Top</a>
				</p>
			</div>
			<div id=\"footer_right\">
				<!-- Languages -->
					<p>
					";
					$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_flag, language_active_default FROM $t_languages_active";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_flag, $get_language_active_default) = $row;
	
						$flag_path 	= "$root/_webdesign/$webdesignSav/images/footer/flag_$get_language_active_flag" . "_16x16.png";
					
						echo"					";
						echo"<a href=\"index.php?l=$get_language_active_iso_two\"><img src=\"$flag_path\" alt=\"$get_language_active_flag\" /></a>\n";
					}
	
					echo"
					</p>
				<!-- //Languages -->
			</div>
		</div>
	</div>
<!-- //Footer -->

<!-- Cookies warning -->
	";
	include("$root/_admin/_functions/cookies_warning_include.php");
	echo"
<!-- //Cookies warning -->

</body>
</html>";

?>