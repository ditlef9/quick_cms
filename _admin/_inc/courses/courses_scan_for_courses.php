<?php
/**
*
* File: _admin/_inc/comments/courses_scan_for_courses.php
* Version 2
* Copyright (c) 2008-2023 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}
/*- Variables ------------------------------------------------------------------------ */
$tabindex = 0;
if(isset($_GET['category_id'])){
	$category_id = $_GET['category_id'];
	$category_id = strip_tags(stripslashes($category_id));
}
else{
	$category_id = "";
}

/*- Functions ------------------------------------------------------------------------ */
function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}


if($action == ""){
	echo"
	<h1>Scan for courses</h1>
			

	<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
	<!-- //Feedback -->


	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=courses&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Courses</a>
		&gt;
		<a href=\"index.php?open=courses&amp;page=default&amp;editor_language=$editor_language&amp;l=$l\">Categories</a>
		&gt;
		<a href=\"index.php?open=courses&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Scan for courses</a>
		</p>
	<!-- //Where am I? -->

	<!-- About -->
		<div style=\"border: #ccc 1px solid;margin-bottom: 20px;\">
			<p>This scans all directories in <a href=\"../\">../</a>. It looks for ../{dir}/_course.txt for information about the course. The course info should be
			like this:<br /><br /><br />
			course_title: Algoritmer og datastrukturer<br /><br />

course_short_introduction: Algoritmer er en programmeringskode som gj�r en definert jobb. For eksempel � se om et tall er et primtall eller ikke.<br /><br />

course_long_introduction: &lt;p&gt;This long course will..&lt;/p&gt;<br /><br />

course_contents: &lt;pThe candidate has finished the course xyz&lt;/p&gt;
&lt;p&gt;Contents of the course:&lt;/p&gt;
&lt;ul&gt;
	&lt;li&gt;&lt;span&gt;Data&lt;/span&gt;&lt;/li&gt;
&lt;/ul&gt;
<br /><br />

course_language: no<br /><br />

course_dir_name: algoritmer_og_datastrukturer<br /><br />

course_category_dir_name: programmering<br /><br />

course_intro_video_embedded: 
			</p>
		</div>
		
	<!-- //About -->

	<!-- Scan courses -->
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span>Title</span>
		   </th>
		   <th scope=\"col\">
			<span>Category</span>
		   </th>
		   <th scope=\"col\">
			<span>Actions</span>
		   </th>
		  </tr>
		 </thead>
		 <tbody>
		";



		$filenames = "";
		$dir = "../";
		if ($handle = opendir($dir)) {
			while (false !== ($file = readdir($handle))) {
				if(file_exists("../$file/_course.txt")){
					if(isset($odd) && $odd == false){
						$odd = true;
					}
					else{
						$odd = false;
					}


					$fh = fopen("../$file/_course.txt", "r");
					$data = fread($fh, filesize("../$file/_course.txt"));
					fclose($fh);


					$course_title = trim(get_string_between($data, "course_title:", "course_short_introduction:"));
					$course_title = str_replace("\n", "", $course_title);
					$course_title = str_replace("\r", "", $course_title);
					$course_title_mysql = quote_smart($link, $course_title);

					$course_short_introduction = trim(get_string_between($data, "course_short_introduction:", "course_long_introduction:"));
					$course_short_introduction_mysql = quote_smart($link, $course_short_introduction);

					$course_long_introduction = trim(get_string_between($data, "course_long_introduction:", "course_contents:"));
					$course_long_introduction_mysql = quote_smart($link, $course_long_introduction);

					$course_contents = trim(get_string_between($data, "course_contents:", "course_language:"));
					$course_contents_mysql = quote_smart($link, $course_contents);

					$course_language = trim(get_string_between($data, "course_language:", "course_dir_name:"));
					$course_language = str_replace("\n", "", $course_language);
					$course_language = str_replace("\r", "", $course_language);
					$course_language_mysql = quote_smart($link, $course_language);

					$course_dir_name = trim(get_string_between($data, "course_dir_name:", "course_category_dir_name:"));
					$course_dir_name = str_replace("\n", "", $course_dir_name);
					$course_dir_name = str_replace("\r", "", $course_dir_name);
					$course_dir_name_mysql = quote_smart($link, $course_dir_name);

					$course_category_dir_name = trim(get_string_between($data, "course_category_dir_name:", "course_intro_video_embedded:"));
					$course_category_dir_name = str_replace("\n", "", $course_category_dir_name);
					$course_category_dir_name = str_replace("\r", "", $course_category_dir_name);
					$course_category_dir_name_mysql = quote_smart($link, $course_category_dir_name);

					$course_intro_video_embedded = trim(substr($data, strpos($data, "course_intro_video_embedded:") + strlen("course_intro_video_embedded:")));    
					$course_intro_video_embedded = str_replace("\n", "", $course_intro_video_embedded);
					$course_intro_video_embedded = str_replace("\r", "", $course_intro_video_embedded);
					$course_intro_video_embedded_mysql = quote_smart($link, $course_intro_video_embedded);

				
					// Check for course
					$query = "SELECT course_id FROM $t_courses_index WHERE course_title=$course_title_mysql AND course_language=$course_language_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_course_id) = $row;

					// Check for category
					$query = "SELECT category_id FROM $t_courses_categories WHERE category_dir_name=$course_category_dir_name_mysql AND category_language=$course_language_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_current_category_id) = $row;


					echo"
					<tr>
					  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
						<span>$course_title</span>
					  </td>
					  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
						<span>$course_category_dir_name </span>
					  </td>
					  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
						<span>
						";
						if($get_course_id == ""){
							if($get_current_category_id == ""){
								echo"Category $course_category_dir_name not found<br />$query";
							}
							else{
								echo"<a href=\"index.php?open=$open&amp;page=$page&amp;action=add&amp;dir=$file&amp;editor_language=$editor_language&amp;process=1\">Add</a>";
							}
						}
						else{
							echo"$get_course_id";
						}
						echo"</span>
					 </td>
					</tr>
					";
				} // course exists
			} // while
		} // dir
		echo"
		 </tbody>
		</table>
	<!-- //Scan courses  -->
	";
} // action
elseif($action == "add"){
	if(isset($_GET['dir'])){
		$dir = $_GET['dir'];
		$dir = strip_tags(stripslashes($dir));
	}
	else{
		echo"error";
		die;
	}
	if(file_exists("../$dir/_course.txt")){
		$fh = fopen("../$dir/_course.txt", "r");
		$data = fread($fh, filesize("../$dir/_course.txt"));
		fclose($fh);

		$course_title = trim(get_string_between($data, "course_title:", "course_short_introduction:"));
		$course_title = str_replace("\n", "", $course_title);
		$course_title = str_replace("\r", "", $course_title);
		$course_title_mysql = quote_smart($link, $course_title);

		$course_short_introduction = trim(get_string_between($data, "course_short_introduction:", "course_long_introduction:"));
		$course_short_introduction_mysql = quote_smart($link, $course_short_introduction);

		$course_long_introduction = trim(get_string_between($data, "course_long_introduction:", "course_contents:"));
		$course_long_introduction_mysql = quote_smart($link, $course_long_introduction);

		$course_contents = trim(get_string_between($data, "course_contents:", "course_language:"));
		$course_contents_mysql = quote_smart($link, $course_contents);

		$course_language = trim(get_string_between($data, "course_language:", "course_dir_name:"));
		$course_language = str_replace("\n", "", $course_language);
		$course_language = str_replace("\r", "", $course_language);
		$course_language_mysql = quote_smart($link, $course_language);

		$course_dir_name = trim(get_string_between($data, "course_dir_name:", "course_category_dir_name:"));
		$course_dir_name = str_replace("\n", "", $course_dir_name);
		$course_dir_name = str_replace("\r", "", $course_dir_name);
		$course_dir_name_mysql = quote_smart($link, $course_dir_name);

		$course_category_dir_name = trim(get_string_between($data, "course_category_dir_name:", "course_intro_video_embedded:"));
		$course_category_dir_name = str_replace("\n", "", $course_category_dir_name);
		$course_category_dir_name = str_replace("\r", "", $course_category_dir_name);
		$course_category_dir_name_mysql = quote_smart($link, $course_category_dir_name);

		$course_intro_video_embedded = trim(substr($data, strpos($data, "course_intro_video_embedded:") + strlen("course_intro_video_embedded:")));    
		$course_intro_video_embedded = str_replace("\n", "", $course_intro_video_embedded);
		$course_intro_video_embedded = str_replace("\r", "", $course_intro_video_embedded);
		$course_intro_video_embedded_mysql = quote_smart($link, $course_intro_video_embedded);

		$inp_icon_a = $course_dir_name . "_48x48.png";
		$inp_icon_a_mysql = quote_smart($link, $inp_icon_a);

		$inp_icon_b = $course_dir_name . "_64x64.png";
		$inp_icon_b_mysql = quote_smart($link, $inp_icon_b);

		$inp_icon_c = $course_dir_name . "_96x96.png";
		$inp_icon_c_mysql = quote_smart($link, $inp_icon_c);

		$datetime = date("Y-m-d H:i:s");


		$query = "SELECT category_id FROM $t_courses_categories WHERE category_dir_name=$course_category_dir_name_mysql AND category_language=$course_language_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_category_id) = $row;



		mysqli_query($link, "INSERT INTO $t_courses_index
		(course_id, course_title, course_short_introduction, course_long_introduction, course_contents, course_language, course_dir_name, course_category_id, course_category_dir_name, course_intro_video_embedded, course_icon_48, course_icon_64, course_icon_96, course_modules_count, course_lessons_count, course_quizzes_count, course_users_enrolled_count, course_read_times, course_created, course_updated) 
		VALUES 
		(NULL, $course_title_mysql, $course_short_introduction_mysql, $course_long_introduction_mysql, $course_contents_mysql, $course_language_mysql, $course_dir_name_mysql, $get_current_category_id, $course_category_dir_name_mysql, $course_intro_video_embedded_mysql, $inp_icon_a_mysql, $inp_icon_b_mysql, $inp_icon_c_mysql, 0, 0, 0, 0, 0, '$datetime', '$datetime')")
		or die(mysqli_error($link));

		// Get ID
		$query = "SELECT course_id FROM $t_courses_index WHERE course_title=$inp_title_mysql AND course_created='$datetime'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_course_id) = $row;


		// Create file
		$datetime_print = date("j M Y H:i");
		$year = date("Y");
		$page_id = date("ymdhis");
		$input="<?php
/**
*
* File: $inp_dir_name/index.php
* Version 2.0.0
* Date $datetime_print
* Copyright (c) 2009-$year Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Configuration ---------------------------------------------------------------------------- */
\$pageIdSav            = \"$page_id\";
\$pageNoColumnSav      = \"2\";
\$pageAllowCommentsSav = \"0\";

/*- Root dir --------------------------------------------------------------------------------- */
// This determine where we are
if(file_exists(\"favicon.ico\")){ \$root = \".\"; }
elseif(file_exists(\"../favicon.ico\")){ \$root = \"..\"; }
elseif(file_exists(\"../../favicon.ico\")){ \$root = \"../..\"; }
elseif(file_exists(\"../../../favicon.ico\")){ \$root = \"../../..\"; }
elseif(file_exists(\"../../../../favicon.ico\")){ \$root = \"../../../..\"; }
else{ \$root = \"../../..\"; }

/*- Website config --------------------------------------------------------------------------- */
include(\"\$root/_admin/website_config.php\");

/*- Translation ------------------------------------------------------------------------------ */
include(\"\$root/_admin/_translations/site/\$l/courses/ts_courses.php\");

/*- Headers ---------------------------------------------------------------------------------- */
\$website_title = \"$inp_title\";
if(file_exists(\"./favicon.ico\")){ \$root = \".\"; }
elseif(file_exists(\"../favicon.ico\")){ \$root = \"..\"; }
elseif(file_exists(\"../../favicon.ico\")){ \$root = \"../..\"; }
elseif(file_exists(\"../../../favicon.ico\")){ \$root = \"../../..\"; }
include(\"\$root/_webdesign/header.php\");

/* Course header ---------------------------------------------------------------------------- */
\$courseDirNameSav = \"$inp_dir_name\";

include(\"\$root/courses/_includes/course.php\");

/*- Footer ---------------------------------------------------------------------------------- */
include(\"\$root/_webdesign/footer.php\");
?>";

		$fh = fopen("../$inp_dir_name/index.php", "w+") or die("can not open file");
		fwrite($fh, $input);
		fclose($fh);


		// Header
		$url = "index.php?open=$open&page=$page&editor_language=$editor_language&ft=success&fm=course_created";
		header("Location: $url");
		exit;
	}
}
?>