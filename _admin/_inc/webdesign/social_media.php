<?php
/**
*
* File: _admin/_inc/settings/default.php
* Version 02:10 28.12.2011
* Copyright (c) 2008-2012 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Tables ---------------------------------------------------------------------------- */
$t_social_media 	= $mysqlPrefixSav . "social_media";
$t_social_media_sites	= $mysqlPrefixSav . "social_media_sites";

/*- Check for setup ------------------------------------------------------------------- */

$query = "SELECT * FROM $t_social_media LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){

echo"
<h1>Social media</h1>


<p>
<a href=\"index.php?open=$open&amp;page=social_media_new&amp;editor_language=$editor_language\" class=\"btn btn_default\">New</a>
<a href=\"index.php?open=$open&amp;page=social_media_sites&amp;editor_language=$editor_language\" class=\"btn btn_default\">Sites</a>
<a href=\"index.php?open=$open&amp;page=social_media_tables&amp;editor_language=$editor_language\" class=\"btn btn_default\">Tables</a>
</p>

<!-- List all sosial media -->
	<table class=\"hor-zebra\">
	 <thead>
	  <tr>
	   <th scope=\"col\">
		<span>Social media</span>
	   </th>
	   <th scope=\"col\">
		<span>Language</span>
	   </th>
	   <th scope=\"col\">
		<span>Link</span>
	   </th>
	   <th scope=\"col\">
		<span>Active</span>
	   </th>
	   <th scope=\"col\">
		<span>Updated</span>
	   </th>
	   <th scope=\"col\">
		<span>Actions</span>
	   </th>
	  </tr>
	</thead>
	<tbody>
	";
	
	$query = "SELECT social_media_id, social_media_site_id, social_media_site_title, social_media_language, social_media_link_title, social_media_link_url, social_media_code, social_media_updated, social_media_active, social_media_hits FROM $t_social_media";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_social_media_id, $get_social_media_site_id, $get_social_media_site_title, $get_social_media_language, $get_social_media_link_title, $get_social_media_link_url, $get_social_media_code, $get_social_media_updated, $get_social_media_active, $get_social_media_hits) = $row;


		// Tables
		if(isset($odd) && $odd == false){
			$odd = true;
		}
		else{
			$odd = false;
		}

		echo"
		<tr>
		  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
			<span>$get_social_media_site_title</span>
		  </td>
		  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
			<span>$get_social_media_language</span>
		  </td>
		  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
			<span><a href=\"$get_social_media_link_url\">$get_social_media_link_title</a></span>
		  </td>
		  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
			<span>$get_social_media_active</span>
		  </td>
		  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
			<span>$get_social_media_updated</span>
		  </td>
		  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
			<span>
			<a href=\"index.php?open=$open&amp;page=social_media_edit&amp;social_media_id=$get_social_media_id&amp;editor_language=$editor_language\">Edit</a>
			&middot;
			<a href=\"index.php?open=$open&amp;page=social_media_delete&amp;social_media_id=$get_social_media_id&amp;editor_language=$editor_language\">Delete</a>
			</span>
		 </td>
		</tr>
		";
	}
	echo"
	 </tbody>
	</table>
<!-- //List all sosial media -->

";
} // setup ok
else{
	echo"
	<h1>Social media setup</h1>
	<h2><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Loading...</h2>
	<meta http-equiv=refresh content=\"1; url=index.php?open=$open&amp;page=social_media_tables&amp;refererer=social_media&amp;editor_language=$editor_language&amp;l=$l&amp;\">";
}
?>