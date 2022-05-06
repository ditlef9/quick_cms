<?php
if(isset($_GET['print'])) {
	include("$root/_webdesign/print_footer.php");
}
else{
	if($process != "1"){
		echo"
			</div> <!-- main_center_inner -->
		</div> <!-- main_center_single_colum -->
		";

		if(isset($pageNoColumnSav) && $pageNoColumnSav != "1"){
			// Find right
			$right = "";
			if($root != "."){
				if(file_exists("right.php")){
					$right = "right.php";
				}
				else{
					if(file_exists("../right.php")){
						$right= "../right.php";
					}
					else{
						if(file_exists("../../right.php")){
							$right = "../../right.php";
						}
					}
				}
			}
			if($right != ""){
				echo"
				<div id=\"main_right\">";
					include("$right");
					echo"
				</div> <!-- //main_right -->
				";
			}
		}

		echo"
	</div> <!-- //main_wrapper -->
<!-- //Main -->


<!-- Footer -->
	<div id=\"footer\">
			<div id=\"footer_left\">
				<p>
				$configWebsiteCopyrightSav
				</p>
			</div>
			<div id=\"footer_right\">
				<p>
				<a href=\"#top\">Top</a>
				</p>
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
	}
}

?>