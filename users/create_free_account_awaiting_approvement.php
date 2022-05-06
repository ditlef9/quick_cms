<?php
/**
*
* File: users/index.php
* Version 17.46 18.02.2017
* Copyright (c) 2009-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Configuration ---------------------------------------------------------------------------- */
$pageIdSav            = "0";
$pageNoColumnSav      = "2";
$pageAllowCommentsSav = "0";

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

/*- Translation ------------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/users/ts_index.php");

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_awaiting_approvement - $l_users";
include("$root/_webdesign/header.php");



/*- Content --------------------------------------------------------------------------- */


/*- Translations -------------------------------------------------------------------- */
include("$root/_admin/_translations/site/$l/users/ts_create_free_account.php");

echo"
<h1>$l_awaiting_approvement</h1>
<p>
$l_your_account_will_be_examined_by_a_moderator_shortly
</p>

<p>
$l_it_will_after_examination_be_approved
</p>

<p>
<a href=\"$root/index.php\" class=\"btn\">$l_home</a></p>
";
/*- Footer ---------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");

?>