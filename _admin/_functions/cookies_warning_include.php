<?php

/*- Cookies warning -------------------------------------------------------------------------- */
$t_pages_cookies_policy_accepted = $mysqlPrefixSav . "pages_cookies_policy_accepted";
$query = "SELECT cookies_policy_accepted_id FROM $t_pages_cookies_policy_accepted WHERE cookies_policy_accepted_ip=$my_ip_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_cookies_policy_accepted_id) = $row;
if($get_cookies_policy_accepted_id == "" && $process != "1"){
	include("$root/_admin/_translations/site/$l/legal/ts_pages_cookies_policy_accepted.php");
	echo"
	<div id=\"div_legal\">
		<p>$l_we_use_cookies_to_make_your_experience_as_good_as_possible. 
		<a href=\"$root/legal/index.php?doc=cookies_policy&amp;l=$l\" class=\"legal_read_more\">$l_read_more</a>
		<a href=\"#\" id=\"btn_legal_accept\" class=\"btn_default\">$l_accept</a>
		</p>


		<!-- On click accept legal -->
			<script>
			\$(document).ready(function () {
				\$(\"#btn_legal_accept\").click(function() {
					\$(\"#div_legal\").slideUp();


      					// ajax call
					var data	= 'l=$l&doc=cookies_policy&action=accept&process=1';
       					\$.ajax({
               					type: \"GET\",
       						url: \"$root/legal/index.php?\",
                				data: data,
       						success: function(html){
               						\$(\"#div_legal\").hide();
       						}
       					});
        				return false;
				});
         		   });
			</script>
		<!-- //On click accept legal -->
		

	</div>
	";
}

?>