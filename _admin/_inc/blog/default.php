<?php
/**
*
* File: _admin/_inc/blog/default.php
* Version 15.00 03.03.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Config ----------------------------------------------------------------------- */
if(!(file_exists("_data/blog.php"))){
	$update_file="<?php
\$blogActiveSav    	   = \"1\";
\$blogWhoCanHaveBlogSav    = \"everyone\";
\$blogEditModeSav 	   = \"wuciwug\";
\$blogPrintLogoOnImagesSav = \"0\";

\$blogPostsImageSizeXSav = \"1280\";
\$blogPostsImageSizeYSav = \"720\";

\$blogPostsThumbSmallSizeXSav = \"100\";
\$blogPostsThumbSmallSizeYSav = \"56\";

\$blogPostsThumbMediumSizeXSav = \"400\";
\$blogPostsThumbMediumSizeYSav = \"225\";

\$blogPostsThumbLargeSizeXSav = \"818\";
\$blogPostsThumbLargeSizeYSav = \"460\";
?>";

	$fh = fopen("_data/blog.php", "w+") or die("can not open file");
	fwrite($fh, $update_file);
	fclose($fh);
}
/*- Check if setup is run ------------------------------------------------------------- */
$t_blog_liquidbase			= $mysqlPrefixSav . "blog_liquidbase";
$query = "SELECT * FROM $t_blog_liquidbase LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
}
else{
	echo"
	<div class=\"info\"><p><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Running setup</p></div>
	<meta http-equiv=\"refresh\" content=\"1;url=index.php?open=blog&amp;page=tables&amp;refererer=default&amp;editor_language=$editor_language&amp;l=$l\" />
	";
}


echo"
<h1>Blog</h1>


<!-- Blog menu -->
	<div class=\"vertical\">
		<ul>
			";
			include("_inc/blog/menu.php");
			echo"
		</ul>
	</div>
<!-- //Blog menu -->

";
?>