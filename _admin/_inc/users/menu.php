<?php
/**
*
* File: _admin/_inc/settings/menu.php
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


/*- Language --------------------------------------------------------------------------- */
include("_translations/admin/$l/users/t_common.php");


if($page == "menu"){
	echo"
	<h1>Users</h1>
	<div class=\"vertical\">
		<ul>
			<li><a href=\"index.php?open=$open&amp;editor_language=$editor_language\">Users</a></li>

	";
}



echo"
			<li";if($page == "users_list"){echo" class=\"down\"";}echo"><a href=\"./?open=$open&amp;page=users_list&amp;editor_language=$editor_language&amp;l=$l\"";if($page == "users_list"){echo" class=\"selected\"";}echo">Users list</a></li>
			<li";if($page == "pending_users"){echo" class=\"down\"";}echo"><a href=\"./?open=$open&amp;page=pending_users&amp;editor_language=$editor_language&amp;l=$l\"";if($page == "pending_users"){echo" class=\"selected\"";}echo">$l_pending_users</a></li>
			<li";if($page == "user_system"){echo" class=\"down\"";}echo"><a href=\"./?open=$open&amp;page=user_system&amp;editor_language=$editor_language&amp;l=$l\"";if($page == "user_system"){echo" class=\"selected\"";}echo">$l_user_system</a></li>
			<li";if($page == "user_system"){echo" class=\"down\"";}echo"><a href=\"./?open=$open&amp;page=anti_spam&amp;editor_language=$editor_language&amp;l=$l\"";if($page == "anti_spam"){echo" class=\"selected\"";}echo">$l_anti_spam</a></li>
			<li";if($page == "professional_settings"){echo" class=\"down\"";}echo"><a href=\"./?open=$open&amp;page=professional_settings&amp;editor_language=$editor_language&amp;l=$l\"";if($page == "professional_settings"){echo" class=\"selected\"";}echo">Professional settings</a></li>
			<li";if($page == "professional_allowed_companies"){echo" class=\"down\"";}echo"><a href=\"./?open=$open&amp;page=professional_allowed_companies&amp;editor_language=$editor_language&amp;l=$l\"";if($page == "professional_allowed_companies"){echo" class=\"selected\"";}echo">Allowed companies</a></li>
			<li";if($page == "professional_allowed_company_locations"){echo" class=\"down\"";}echo"><a href=\"./?open=$open&amp;page=professional_allowed_company_locations&amp;editor_language=$editor_language&amp;l=$l\"";if($page == "professional_company_locations"){echo" class=\"selected\"";}echo">Allowed c. locations</a></li>
			<li";if($page == "professional_allowed_departments"){echo" class=\"down\"";}echo"><a href=\"./?open=$open&amp;page=professional_allowed_departments&amp;editor_language=$editor_language&amp;l=$l\"";if($page == "professional_allowed_departments"){echo" class=\"selected\"";}echo">Allowed departments</a></li>
			<li";if($page == "professional_allowed_positions"){echo" class=\"down\"";}echo"><a href=\"./?open=$open&amp;page=professional_allowed_positions&amp;editor_language=$editor_language&amp;l=$l\"";if($page == "professional_allowed_positions"){echo" class=\"selected\"";}echo">Allowed positions</a></li>
			<li";if($page == "professional_allowed_districts"){echo" class=\"down\"";}echo"><a href=\"./?open=$open&amp;page=professional_allowed_districts&amp;editor_language=$editor_language&amp;l=$l\"";if($page == "professional_allowed_districts"){echo" class=\"selected\"";}echo">Allowed districts</a></li>
			<li";if($page == "feed"){echo" class=\"down\"";}echo"><a href=\"index.php?open=$open&amp;page=feed&amp;editor_language=$editor_language&amp;l=$l\"";if($page == "feed"){echo" class=\"selected\"";}echo">Feed</a></li>
			<li";if($page == "headlines"){echo" class=\"down\"";}echo"><a href=\"index.php?open=$open&amp;page=headlines&amp;editor_language=$editor_language&amp;l=$l\"";if($page == "headlines"){echo" class=\"selected\"";}echo">Headlines</a></li>
			<li";if($page == "groups"){echo" class=\"down\"";}echo"><a href=\"index.php?open=$open&amp;page=groups&amp;editor_language=$editor_language&amp;l=$l\"";if($page == "groups"){echo" class=\"selected\"";}echo">Groups</a></li>
		

";

if($page == "menu"){
	echo"
		</ul>
	</div>
	";
}
?>