<?php 
/**
*
* File: search/index.php 
* Version 1.0
* Date 14:01 24.01.2020
* Copyright (c) 2020 S. A. Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Configuration ---------------------------------------------------------------------------- */
$pageIdSav            = "2";
$pageNoColumnSav      = "2";
$pageAllowCommentsSav = "1";
$pageAuthorUserIdSav  = "1";

/*- Root dir --------------------------------------------------------------------------------- */
// This determine where we are
if(file_exists("favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
elseif(file_exists("../../../../favicon.ico")){ $root = "../../../.."; }
else{ $root = "../../.."; }

/*- Website config --------------------------------------------------------------------------- */
include("$root/_admin/website_config.php");

/*- Language --------------------------------------------------------------------------- */
include("$root/_admin/_translations/site/$l/search/ts_index.php");



/*- Variables -------------------------------------------------------------------------- */


/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_search";
include("$root/_webdesign/header.php");


echo"
<h1>$l_search</h1>
	
<!-- Search -->
	<div class=\"search_search_div\">
		<form method=\"get\" action=\"search.php\" enctype=\"multipart/form-data\">
		<input type=\"text\" name=\"inp_search_query\" id=\"inp_search_query\" class='auto' value=\"\" size=\"25\" />
		<input type=\"submit\" value=\"$l_search\" class=\"btn_default\" />
		</form>
		<div id=\"inp_search_results\"></div>
	</div>
	<!-- Focus -->
		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_search_query\"]').focus();
		});
		</script>
	<!-- //Focus -->

	<!-- Search engines Autocomplete -->
		<script id=\"source\" language=\"javascript\" type=\"text/javascript\">
			\$(document).ready(function () {
				\$('#inp_search_query').keyup(function () {
					// getting the value that user typed
					var searchString    = \$(\"#inp_search_query\").val();
					// forming the queryString
      					var data            = 'l=$l&inp_search_query='+ searchString;
         
        				// if searchString is not empty
        				if(searchString) {
						\$(\"#inp_search_results\").css('visibility','visible');
						// ajax call
        					\$.ajax({
        						type: \"GET\",
        						url: \"search_autocomplete.php\",
                					data: data,
							beforeSend: function(html) { // this happens before actual call
								\$(\"#inp_search_results\").html(''); 
							},
               						success: function(html){
                    						\$(\"#inp_search_results\").append(html);
              						}
            					});
       					}
        				return false;
            			});
         		});
		</script>
	<!-- //Search engines Autocomplete -->
<!-- //Search -->

";

		

/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/$webdesignSav/footer.php");
?>