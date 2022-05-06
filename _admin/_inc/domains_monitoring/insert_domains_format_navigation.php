<?php
/**
*
* File: _admin/_inc/domains_monitoring/insert_domains.php
* Version 09:19 31.08.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}
echo"		<p>
		<b>Format:</b><br />
		<select name=\"on_select_go_to_url\" class=\"on_select_go_to_url\">
			<option value=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language\""; if($format == ""){ echo" selected=\"selected\""; } echo">Space seperated</option>
			<option value=\"index.php?open=$open&amp;page=$page&amp;format=number_domain_length_idn_date&amp;editor_language=$editor_language\""; if($format == "number_domain_length_idn_date"){ echo" selected=\"selected\"";  } echo">Number &nbsp; Domain &nbsp; length &nbsp; IDN &nbsp; Date</option>
		</select>
		</p>


<script>
    \$(function(){
      // bind change event to select
      \$('.on_select_go_to_url').on('change', function () {
          var url = \$(this).val(); // get selected value
          if (url) { // require a URL
              window.location = url; // redirect
          }
          return false;
      });
    });
</script>

";

?>