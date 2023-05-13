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
		echo"
	</div> <!-- //main_wrapper -->
<!-- //Main -->


	<!-- Footer -->
		<div id=\"footer\">
			<div class=\"footer_parent\">
				<div class=\"footer_child\">
					<h4>Top Courses</h4>
					<ul>\n";
					// Get all courses
					$l_mysql = quote_smart($link, $l);
					$t_courses_index = $mysqlPrefixSav . "courses_index";
					$query = "SELECT course_id, course_title, course_title_clean FROM $t_courses_index WHERE course_language=$l_mysql ORDER BY course_users_enrolled_count DESC LIMIT 0,10";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_course_id, $get_course_title, $get_course_title_clean) = $row;
						echo"						";
						echo"<li><a href=\"$root/$get_course_title_clean/index.php?l=$l\">$get_course_title</a></li>\n";
					}
					echo"
					</ul>
				</div>
				<div class=\"footer_child\">
					<h4>Top References</h4>
					<ul>\n";
					// Get all references
					$t_references_index = $mysqlPrefixSav . "references_index";
					$query = "SELECT reference_id, reference_title, reference_title_clean FROM $t_references_index WHERE reference_language=$l_mysql ORDER BY reference_read_times ASC LIMIT 0,10";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_reference_id, $get_reference_title, $get_reference_title_clean) = $row;
						echo"						";
						echo"<li><a href=\"$root/$get_reference_title_clean/index.php?l=$l\">$get_reference_title</a></li>\n";
					}
					echo"
					</ul>
				</div>
				<div class=\"footer_child\">
					<h4>Last forum threads</h4>
					<ul>\n";
					// Get threads
					$t_forum_topics = $mysqlPrefixSav . "forum_topics";
					$query = "SELECT topic_id, topic_title, topic_title_short, topic_title_length FROM $t_forum_topics WHERE topic_language=$l_mysql ORDER BY topic_id DESC LIMIT 0,10";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_topic_id, $get_topic_title, $get_topic_title_short, $get_topic_title_length) = $row;
						echo"						";
						if($get_topic_title_length  > 30){
							echo"<li><a href=\"$root/discuss/view_topic.php?topic_id=$get_topic_id&amp;l=$l\">$get_topic_title_short</a></li>\n";
						}
						else{
							echo"<li><a href=\"$root/discuss/view_topic.php?topic_id=$get_topic_id&amp;l=$l\">$get_topic_title</a></li>\n";
						}

						
					}
					echo"
					</ul>
				</div>
				<div class=\"footer_child\">
					<h4>Top Downloads</h4>
					<ul>\n";
					// Get all downloads
					$t_downloads_index = $mysqlPrefixSav . "downloads_index";
					$query = "SELECT download_id, download_title, download_title_short, download_title_length FROM $t_downloads_index WHERE download_language=$l_mysql ORDER BY download_unique_hits DESC LIMIT 0,10";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_download_id, $get_download_title, $get_download_title_short, $get_download_title_length) = $row;
						echo"						";
						if($get_download_title_length > 30){
							echo"<li><a href=\"$root/downloads/view_download.php?download_id=$get_download_id&amp;l=$l\">$get_download_title_short</a></li>\n";
						}
						else{
							echo"<li><a href=\"$root/downloads/view_download.php?download_id=$get_download_id&amp;l=$l\">$get_download_title</a></li>\n";
						}
					}
					echo"
					</ul>
				</div>
			</div>

			<div class=\"footer_after\">
				<p>

					&copy; 2007-2019 Code Courses
					&middot;
					<a href=\"#top\">Top</a>
					&middot;
					<a href=\"https://kurs.frindex.net/index.php?l=no\">Norwegian</a>
					";
					include("$root/_scripts/delete_cache/delete_cache.php");
					echo"
					</p>
			</div>
		</div>
	<!-- //Footer -->


</body>
</html>";
	}
}

?>