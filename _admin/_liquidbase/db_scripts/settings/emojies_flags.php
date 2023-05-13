<?php
/**
*
* File: _admin/_liquidbase/db_scripts/settings/emojies_flags.php
* Version 1.0.0
* Date 20:24 22.04.2022
* Copyright (c) 2022 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// Access check
if(isset($_SESSION['admin_user_id'])){

	/*- Tables ---------------------------------------------------------------------------- */


	$t_emojies_categories_main	= $mysqlPrefixSav . "emojies_categories_main";
	$t_emojies_categories_sub	= $mysqlPrefixSav . "emojies_categories_sub";
	$t_emojies_index 		= $mysqlPrefixSav . "emojies_index";
	$t_emojies_users_recent_used	= $mysqlPrefixSav . "emojies_users_recent_used";


	// Flags (Flag Symbol	Country	Unicode Hex	HTML Dec Code)
	$flags ="🇦🇨|Ascension Island|U+1F1E6 U+1F1E8|&#127462 &#127464
🇦🇩|Andorra|U+1F1E6 U+1F1E9|&#127462 &#127465
🇦🇪|United Arab Emirates|U+1F1E6 U+1F1EA|&#127462 &#127466
🇦🇫|Afghanistan|U+1F1E6 U+1F1EB|&#127462 &#127467
🇦🇬|Antigua & Barbuda|U+1F1E6 U+1F1EC|&#127462 &#127468
🇦🇮|Anguilla|U+1F1E6 U+1F1EE|&#127462 &#127470
🇦🇱|Albania|U+1F1E6 U+1F1F1|&#127462 &#127473
🇦🇲|Armenia|U+1F1E6 U+1F1F2|&#127462 &#127474
🇦🇴|Angola|U+1F1E6 U+1F1F4|&#127462 &#127476
🇦🇶|Antarctica|U+1F1E6 U+1F1F6|&#127462 &#127478
🇦🇷|Argentina|U+1F1E6 U+1F1F7|&#127462 &#127479
🇦🇸|American Samoa|U+1F1E6 U+1F1F8|&#127462 &#127480
🇦🇹|Austria|U+1F1E6 U+1F1F9|&#127462 &#127481
🇦🇺|Australia|U+1F1E6 U+1F1FA|&#127462 &#127482
🇦🇼|Aruba|U+1F1E6 U+1F1FC|&#127462 &#127484
🇦🇽|Åland Islands|U+1F1E6 U+1F1FD|&#127462 &#127485
🇦🇿|Azerbaijan|U+1F1E6 U+1F1FF|&#127462 &#127487
🇧🇦|Bosnia & Herzegovina|U+1F1E7 U+1F1E6|&#127463 &#127462
🇧🇧|Barbados|U+1F1E7 U+1F1E7|&#127463 &#127463
🇧🇩|Bangladesh|U+1F1E7 U+1F1E9|&#127463 &#127465
🇧🇪|Belgium|U+1F1E7 U+1F1EA|&#127463 &#127466
🇧🇫|Burkina Faso|U+1F1E7 U+1F1EB|&#127463 &#127467
🇧🇬|Bulgaria|U+1F1E7 U+1F1EC|&#127463 &#127468
🇧🇭|Bahrain|U+1F1E7 U+1F1ED|&#127463 &#127469
🇧🇮|Burundi|U+1F1E7 U+1F1EE|&#127463 &#127470
🇧🇯|Benin|U+1F1E7 U+1F1EF|&#127463 &#127471
🇧🇱|St. Barthélemy|U+1F1E7 U+1F1F1|&#127463 &#127473
🇧🇲|Bermuda|U+1F1E7 U+1F1F2|&#127463 &#127474
🇧🇳|Brunei|U+1F1E7 U+1F1F3|&#127463 &#127475
🇧🇴|Bolivia|U+1F1E7 U+1F1F4|&#127463 &#127476
🇧🇶|Caribbean Netherlands|U+1F1E7 U+1F1F6|&#127463 &#127478
🇧🇷|Brazil|U+1F1E7 U+1F1F7|&#127463 &#127479
🇧🇸|Bahamas|U+1F1E7 U+1F1F8|&#127463 &#127480
🇧🇹|Bhutan|U+1F1E7 U+1F1F9|&#127463 &#127481
🇧🇻|Bouvet Island|U+1F1E7 U+1F1FB|&#127463 &#127483
🇧🇼|Botswana|U+1F1E7 U+1F1FC|&#127463 &#127484
🇧🇾|Belarus|U+1F1E7 U+1F1FE|&#127463 &#127486
🇧🇿|Belize|U+1F1E7 U+1F1FF|&#127463 &#127487
🇨🇦|Canada|U+1F1E8 U+1F1E6|&#127464 &#127462
🇨🇨|Cocos (Keeling) Islands|U+1F1E8 U+1F1E8|&#127464 &#127464
🇨🇩|Congo - Kinshasa|U+1F1E8 U+1F1E9|&#127464 &#127465
🇨🇫|Central African Republic|U+1F1E8 U+1F1EB|&#127464 &#127467
🇨🇬|Congo - Brazzaville|U+1F1E8 U+1F1EC|&#127464 &#127468
🇨🇭|Switzerland|U+1F1E8 U+1F1ED|&#127464 &#127469
🇨🇮|Côte d’Ivoire|U+1F1E8 U+1F1EE|&#127464 &#127470
🇨🇰|Cook Islands|U+1F1E8 U+1F1F0|&#127464 &#127472
🇨🇱|Chile|U+1F1E8 U+1F1F1|&#127464 &#127473
🇨🇲|Cameroon|U+1F1E8 U+1F1F2|&#127464 &#127474
🇨🇳|China|U+1F1E8 U+1F1F3|&#127464 &#127475
🇨🇴|Colombia|U+1F1E8 U+1F1F4|&#127464 &#127476
🇨🇵|Clipperton Island|U+1F1E8 U+1F1F5|&#127464 &#127477
🇨🇷|Costa Rica|U+1F1E8 U+1F1F7|&#127464 &#127479
🇨🇺|Cuba|U+1F1E8 U+1F1FA|&#127464 &#127482
🇨🇻|Cape Verde|U+1F1E8 U+1F1FB|&#127464 &#127483
🇨🇼|Curaçao|U+1F1E8 U+1F1FC|&#127464 &#127484
🇨🇽|Christmas Island|U+1F1E8 U+1F1FD|&#127464 &#127485
🇨🇾|Cyprus|U+1F1E8 U+1F1FE|&#127464 &#127486
🇨🇿|Czechia|U+1F1E8 U+1F1FF|&#127464 &#127487
🇩🇪|Germany|U+1F1E9 U+1F1EA|&#127465 &#127466
🇩🇬|Diego Garcia|U+1F1E9 U+1F1EC|&#127465 &#127468
🇩🇯|Djibouti|U+1F1E9 U+1F1EF|&#127465 &#127471
🇩🇰|Denmark|U+1F1E9 U+1F1F0|&#127465 &#127472
🇩🇲|Dominica|U+1F1E9 U+1F1F2|&#127465 &#127474
🇩🇴|Dominican Republic|U+1F1E9 U+1F1F4|&#127465 &#127476
🇩🇿|Algeria|U+1F1E9 U+1F1FF|&#127465 &#127487
🇪🇦|Ceuta & Melilla|U+1F1EA U+1F1E6|&#127466 &#127462
🇪🇨|Ecuador|U+1F1EA U+1F1E8|&#127466 &#127464
🇪🇪|Estonia|U+1F1EA U+1F1EA|&#127466 &#127466
🇪🇬|Egypt|U+1F1EA U+1F1EC|&#127466 &#127468
🇪🇭|Western Sahara|U+1F1EA U+1F1ED|&#127466 &#127469
🇪🇷|Eritrea|U+1F1EA U+1F1F7|&#127466 &#127479
🇪🇸|Spain|U+1F1EA U+1F1F8|&#127466 &#127480
🇪🇹|Ethiopia|U+1F1EA U+1F1F9|&#127466 &#127481
🇪🇺|European Union|U+1F1EA U+1F1FA|&#127466 &#127482
🇫🇮|Finland|U+1F1EB U+1F1EE|&#127467 &#127470
🇫🇯|Fiji|U+1F1EB U+1F1EF|&#127467 &#127471
🇫🇰|Falkland Islands|U+1F1EB U+1F1F0|&#127467 &#127472
🇫🇲|Micronesia|U+1F1EB U+1F1F2|&#127467 &#127474
🇫🇴|Faroe Islands|U+1F1EB U+1F1F4|&#127467 &#127476
🇫🇷|France|U+1F1EB U+1F1F7|&#127467 &#127479
🇬🇦|Gabon|U+1F1EC U+1F1E6|&#127468 &#127462
🇬🇧|United Kingdom|U+1F1EC U+1F1E7|&#127468 &#127463
🇬🇩|Grenada|U+1F1EC U+1F1E9|&#127468 &#127465
🇬🇪|Georgia|U+1F1EC U+1F1EA|&#127468 &#127466
🇬🇫|French Guiana|U+1F1EC U+1F1EB|&#127468 &#127467
🇬🇬|Guernsey|U+1F1EC U+1F1EC|&#127468 &#127468
🇬🇭|Ghana|U+1F1EC U+1F1ED|&#127468 &#127469
🇬🇮|Gibraltar|U+1F1EC U+1F1EE|&#127468 &#127470
🇬🇱|Greenland|U+1F1EC U+1F1F1|&#127468 &#127473
🇬🇲|Gambia|U+1F1EC U+1F1F2|&#127468 &#127474
🇬🇳|Guinea|U+1F1EC U+1F1F3|&#127468 &#127475
🇬🇵|Guadeloupe|U+1F1EC U+1F1F5|&#127468 &#127477
🇬🇶|Equatorial Guinea|U+1F1EC U+1F1F6|&#127468 &#127478
🇬🇷|Greece|U+1F1EC U+1F1F7|&#127468 &#127479
🇬🇸|South Georgia & South Sandwich Islands|U+1F1EC U+1F1F8|&#127468 &#127480
🇬🇹|Guatemala|U+1F1EC U+1F1F9|&#127468 &#127481
🇬🇺|Guam|U+1F1EC U+1F1FA|&#127468 &#127482
🇬🇼|Guinea-Bissau|U+1F1EC U+1F1FC|&#127468 &#127484
🇬🇾|Guyana|U+1F1EC U+1F1FE|&#127468 &#127486
🇭🇰|Hong Kong SAR China|U+1F1ED U+1F1F0|&#127469 &#127472
🇭🇲|Heard & McDonald Islands|U+1F1ED U+1F1F2|&#127469 &#127474
🇭🇳|Honduras|U+1F1ED U+1F1F3|&#127469 &#127475
🇭🇷|Croatia|U+1F1ED U+1F1F7|&#127469 &#127479
🇭🇹|Haiti|U+1F1ED U+1F1F9|&#127469 &#127481
🇭🇺|Hungary|U+1F1ED U+1F1FA|&#127469 &#127482
🇮🇨|Canary Islands|U+1F1EE U+1F1E8|&#127470 &#127464
🇮🇩|Indonesia|U+1F1EE U+1F1E9|&#127470 &#127465
🇮🇪|Ireland|U+1F1EE U+1F1EA|&#127470 &#127466
🇮🇱|Israel|U+1F1EE U+1F1F1|&#127470 &#127473
🇮🇲|Isle of Man|U+1F1EE U+1F1F2|&#127470 &#127474
🇮🇳|India|U+1F1EE U+1F1F3|&#127470 &#127475
🇮🇴|British Indian Ocean Territory|U+1F1EE U+1F1F4|&#127470 &#127476
🇮🇶|Iraq|U+1F1EE U+1F1F6|&#127470 &#127478
🇮🇷|Iran|U+1F1EE U+1F1F7|&#127470 &#127479
🇮🇸|Iceland|U+1F1EE U+1F1F8|&#127470 &#127480
🇮🇹|Italy|U+1F1EE U+1F1F9|&#127470 &#127481
🇯🇪|Jersey|U+1F1EF U+1F1EA|&#127471 &#127466
🇯🇲|Jamaica|U+1F1EF U+1F1F2|&#127471 &#127474
🇯🇴|Jordan|U+1F1EF U+1F1F4|&#127471 &#127476
🇯🇵|Japan|U+1F1EF U+1F1F5|&#127471 &#127477
🇰🇪|Kenya|U+1F1F0 U+1F1EA|&#127472 &#127466
🇰🇬|Kyrgyzstan|U+1F1F0 U+1F1EC|&#127472 &#127468
🇰🇭|Cambodia|U+1F1F0 U+1F1ED|&#127472 &#127469
🇰🇮|Kiribati|U+1F1F0 U+1F1EE|&#127472 &#127470
🇰🇲|Comoros|U+1F1F0 U+1F1F2|&#127472 &#127474
🇰🇳|St. Kitts & Nevis|U+1F1F0 U+1F1F3|&#127472 &#127475
🇰🇵|North Korea|U+1F1F0 U+1F1F5|&#127472 &#127477
🇰🇷|South Korea|U+1F1F0 U+1F1F7|&#127472 &#127479
🇰🇼|Kuwait|U+1F1F0 U+1F1FC|&#127472 &#127484
🇰🇾|Cayman Islands|U+1F1F0 U+1F1FE|&#127472 &#127486
🇰🇿|Kazakhstan|U+1F1F0 U+1F1FF|&#127472 &#127487
🇱🇦|Laos|U+1F1F1 U+1F1E6|&#127473 &#127462
🇱🇧|Lebanon|U+1F1F1 U+1F1E7|&#127473 &#127463
🇱🇨|St. Lucia|U+1F1F1 U+1F1E8|&#127473 &#127464
🇱🇮|Liechtenstein|U+1F1F1 U+1F1EE|&#127473 &#127470
🇱🇰|Sri Lanka|U+1F1F1 U+1F1F0|&#127473 &#127472
🇱🇷|Liberia|U+1F1F1 U+1F1F7|&#127473 &#127479
🇱🇸|Lesotho|U+1F1F1 U+1F1F8|&#127473 &#127480
🇱🇹|Lithuania|U+1F1F1 U+1F1F9|&#127473 &#127481
🇱🇺|Luxembourg|U+1F1F1 U+1F1FA|&#127473 &#127482
🇱🇻|Latvia|U+1F1F1 U+1F1FB|&#127473 &#127483
🇱🇾|Libya|U+1F1F1 U+1F1FE|&#127473 &#127486
🇲🇦|Morocco|U+1F1F2 U+1F1E6|&#127474 &#127462
🇲🇨|Monaco|U+1F1F2 U+1F1E8|&#127474 &#127464
🇲🇩|Moldova|U+1F1F2 U+1F1E9|&#127474 &#127465
🇲🇪|Montenegro|U+1F1F2 U+1F1EA|&#127474 &#127466
🇲🇫|St. Martin|U+1F1F2 U+1F1EB|&#127474 &#127467
🇲🇬|Madagascar|U+1F1F2 U+1F1EC|&#127474 &#127468
🇲🇭|Marshall Islands|U+1F1F2 U+1F1ED|&#127474 &#127469
🇲🇰|Macedonia|U+1F1F2 U+1F1F0|&#127474 &#127472
🇲🇱|Mali|U+1F1F2 U+1F1F1|&#127474 &#127473
🇲🇲|Myanmar (Burma)|U+1F1F2 U+1F1F2|&#127474 &#127474
🇲🇳|Mongolia|U+1F1F2 U+1F1F3|&#127474 &#127475
🇲🇴|Macao SAR China|U+1F1F2 U+1F1F4|&#127474 &#127476
🇲🇵|Northern Mariana Islands|U+1F1F2 U+1F1F5|&#127474 &#127477
🇲🇶|Martinique|U+1F1F2 U+1F1F6|&#127474 &#127478
🇲🇷|Mauritania|U+1F1F2 U+1F1F7|&#127474 &#127479
🇲🇸|Montserrat|U+1F1F2 U+1F1F8|&#127474 &#127480
🇲🇹|Malta|U+1F1F2 U+1F1F9|&#127474 &#127481
🇲🇺|Mauritius|U+1F1F2 U+1F1FA|&#127474 &#127482
🇲🇻|Maldives|U+1F1F2 U+1F1FB|&#127474 &#127483
🇲🇼|Malawi|U+1F1F2 U+1F1FC|&#127474 &#127484
🇲🇽|Mexico|U+1F1F2 U+1F1FD|&#127474 &#127485
🇲🇾|Malaysia|U+1F1F2 U+1F1FE|&#127474 &#127486
🇲🇿|Mozambique|U+1F1F2 U+1F1FF|&#127474 &#127487
🇳🇦|Namibia|U+1F1F3 U+1F1E6|&#127475 &#127462
🇳🇨|New Caledonia|U+1F1F3 U+1F1E8|&#127475 &#127464
🇳🇪|Niger|U+1F1F3 U+1F1EA|&#127475 &#127466
🇳🇫|Norfolk Island|U+1F1F3 U+1F1EB|&#127475 &#127467
🇳🇬|Nigeria|U+1F1F3 U+1F1EC|&#127475 &#127468
🇳🇮|Nicaragua|U+1F1F3 U+1F1EE|&#127475 &#127470
🇳🇱|Netherlands|U+1F1F3 U+1F1F1|&#127475 &#127473
🇳🇴|Norway|U+1F1F3 U+1F1F4|&#127475 &#127476
🇳🇵|Nepal|U+1F1F3 U+1F1F5|&#127475 &#127477
🇳🇷|Nauru|U+1F1F3 U+1F1F7|&#127475 &#127479
🇳🇺|Niue|U+1F1F3 U+1F1FA|&#127475 &#127482
🇳🇿|New Zealand|U+1F1F3 U+1F1FF|&#127475 &#127487
🇴🇲|Oman|U+1F1F4 U+1F1F2|&#127476 &#127474
🇵🇦|Panama|U+1F1F5 U+1F1E6|&#127477 &#127462
🇵🇪|Peru|U+1F1F5 U+1F1EA|&#127477 &#127466
🇵🇫|French Polynesia|U+1F1F5 U+1F1EB|&#127477 &#127467
🇵🇬|Papua New Guinea|U+1F1F5 U+1F1EC|&#127477 &#127468
🇵🇭|Philippines|U+1F1F5 U+1F1ED|&#127477 &#127469
🇵🇰|Pakistan|U+1F1F5 U+1F1F0|&#127477 &#127472
🇵🇱|Poland|U+1F1F5 U+1F1F1|&#127477 &#127473
🇵🇲|St. Pierre & Miquelon|U+1F1F5 U+1F1F2|&#127477 &#127474
🇵🇳|Pitcairn Islands|U+1F1F5 U+1F1F3|&#127477 &#127475
🇵🇷|Puerto Rico|U+1F1F5 U+1F1F7|&#127477 &#127479
🇵🇸|Palestinian Territories|U+1F1F5 U+1F1F8|&#127477 &#127480
🇵🇹|Portugal|U+1F1F5 U+1F1F9|&#127477 &#127481
🇵🇼|Palau|U+1F1F5 U+1F1FC|&#127477 &#127484
🇵🇾|Paraguay|U+1F1F5 U+1F1FE|&#127477 &#127486
🇶🇦|Qatar|U+1F1F6 U+1F1E6|&#127478 &#127462
🇷🇪|Réunion|U+1F1F7 U+1F1EA|&#127479 &#127466
🇷🇴|Romania|U+1F1F7 U+1F1F4|&#127479 &#127476
🇷🇸|Serbia|U+1F1F7 U+1F1F8|&#127479 &#127480
🇷🇺|Russia|U+1F1F7 U+1F1FA|&#127479 &#127482
🇷🇼|Rwanda|U+1F1F7 U+1F1FC|&#127479 &#127484
🇸🇦|Saudi Arabia|U+1F1F8 U+1F1E6|&#127480 &#127462
🇸🇧|Solomon Islands|U+1F1F8 U+1F1E7|&#127480 &#127463
🇸🇨|Seychelles|U+1F1F8 U+1F1E8|&#127480 &#127464
🇸🇩|Sudan|U+1F1F8 U+1F1E9|&#127480 &#127465
🇸🇪|Sweden|U+1F1F8 U+1F1EA|&#127480 &#127466
🇸🇬|Singapore|U+1F1F8 U+1F1EC|&#127480 &#127468
🇸🇭|St. Helena|U+1F1F8 U+1F1ED|&#127480 &#127469
🇸🇮|Slovenia|U+1F1F8 U+1F1EE|&#127480 &#127470
🇸🇯|Svalbard & Jan Mayen|U+1F1F8 U+1F1EF|&#127480 &#127471
🇸🇰|Slovakia|U+1F1F8 U+1F1F0|&#127480 &#127472
🇸🇱|Sierra Leone|U+1F1F8 U+1F1F1|&#127480 &#127473
🇸🇲|San Marino|U+1F1F8 U+1F1F2|&#127480 &#127474
🇸🇳|Senegal|U+1F1F8 U+1F1F3|&#127480 &#127475
🇸🇴|Somalia|U+1F1F8 U+1F1F4|&#127480 &#127476
🇸🇷|Suriname|U+1F1F8 U+1F1F7|&#127480 &#127479
🇸🇸|South Sudan|U+1F1F8 U+1F1F8|&#127480 &#127480
🇸🇹|São Tomé & Príncipe|U+1F1F8 U+1F1F9|&#127480 &#127481
🇸🇻|El Salvador|U+1F1F8 U+1F1FB|&#127480 &#127483
🇸🇽|Sint Maarten|U+1F1F8 U+1F1FD|&#127480 &#127485
🇸🇾|Syria|U+1F1F8 U+1F1FE|&#127480 &#127486
🇸🇿|Eswatini|U+1F1F8 U+1F1FF|&#127480 &#127487
🇹🇦|Tristan da Cunha|U+1F1F9 U+1F1E6|&#127481 &#127462
🇹🇨|Turks & Caicos Islands|U+1F1F9 U+1F1E8|&#127481 &#127464
🇹🇩|Chad|U+1F1F9 U+1F1E9|&#127481 &#127465
🇹🇫|French Southern Territories|U+1F1F9 U+1F1EB|&#127481 &#127467
🇹🇬|Togo|U+1F1F9 U+1F1EC|&#127481 &#127468
🇹🇭|Thailand|U+1F1F9 U+1F1ED|&#127481 &#127469
🇹🇯|Tajikistan|U+1F1F9 U+1F1EF|&#127481 &#127471
🇹🇰|Tokelau|U+1F1F9 U+1F1F0|&#127481 &#127472
🇹🇱|Timor-Leste|U+1F1F9 U+1F1F1|&#127481 &#127473
🇹🇲|Turkmenistan|U+1F1F9 U+1F1F2|&#127481 &#127474
🇹🇳|Tunisia|U+1F1F9 U+1F1F3|&#127481 &#127475
🇹🇴|Tonga|U+1F1F9 U+1F1F4|&#127481 &#127476
🇹🇷|Turkey|U+1F1F9 U+1F1F7|&#127481 &#127479
🇹🇹|Trinidad & Tobago|U+1F1F9 U+1F1F9|&#127481 &#127481
🇹🇻|Tuvalu|U+1F1F9 U+1F1FB|&#127481 &#127483
🇹🇼|Taiwan|U+1F1F9 U+1F1FC|&#127481 &#127484
🇹🇿|Tanzania|U+1F1F9 U+1F1FF|&#127481 &#127487
🇺🇦|Ukraine|U+1F1FA U+1F1E6|&#127482 &#127462
🇺🇬|Uganda|U+1F1FA U+1F1EC|&#127482 &#127468
🇺🇲|U.S. Outlying Islands|U+1F1FA U+1F1F2|&#127482 &#127474
🇺🇳|United Nations|U+1F1FA U+1F1F3|&#127482 &#127475
🇺🇸|United States|U+1F1FA U+1F1F8|&#127482 &#127480
🇺🇾|Uruguay|U+1F1FA U+1F1FE|&#127482 &#127486
🇺🇿|Uzbekistan|U+1F1FA U+1F1FF|&#127482 &#127487
🇻🇦|Vatican City|U+1F1FB U+1F1E6|&#127483 &#127462
🇻🇨|St. Vincent & Grenadines|U+1F1FB U+1F1E8|&#127483 &#127464
🇻🇪|Venezuela|U+1F1FB U+1F1EA|&#127483 &#127466
🇻🇬|British Virgin Islands|U+1F1FB U+1F1EC|&#127483 &#127468
🇻🇮|U.S. Virgin Islands|U+1F1FB U+1F1EE|&#127483 &#127470
🇻🇳|Vietnam|U+1F1FB U+1F1F3|&#127483 &#127475
🇻🇺|Vanuatu|U+1F1FB U+1F1FA|&#127483 &#127482
🇼🇫|Wallis & Futuna|U+1F1FC U+1F1EB|&#127484 &#127467
🇼🇸|Samoa|U+1F1FC U+1F1F8|&#127484 &#127480
🇽🇰|Kosovo|U+1F1FD U+1F1F0|&#127485 &#127472
🇾🇪|Yemen|U+1F1FE U+1F1EA|&#127486 &#127466
🇾🇹|Mayotte|U+1F1FE U+1F1F9|&#127486 &#127481
🇿🇦|South Africa|U+1F1FF U+1F1E6|&#127487 &#127462
🇿🇲|Zambia|U+1F1FF U+1F1F2|&#127487 &#127474
🇿🇼|Zimbabwe|U+1F1FF U+1F1FC|&#127487 &#127484
🏴󠁧󠁢󠁥󠁮󠁧󠁿|England|U+1F3F4 U+E0067 U+E0062 U+E0065 U+E006E U+E0067 U+E007F|&#127988 &#917607 &#917602 &#917605 &#917614 &#917607 &#917631
🏴󠁧󠁢󠁳󠁣󠁴󠁿|Scotland|U+1F3F4 U+E0067 U+E0062 U+E0073 U+E0063 U+E0074 U+E007F|&#127988 &#917607 &#917602 &#917619 &#917603 &#917620 &#917631
🏴󠁧󠁢󠁷󠁬󠁳󠁿|Wales|U+1F3F4 U+E0067 U+E0062 U+E0077 U+E006C U+E0073 U+E007F|&#127988 &#917607 &#917602 &#917623 &#917612 &#917619 &#917631
";

	// Get main category
	$query = "SELECT main_category_id, main_category_title FROM $t_emojies_categories_main WHERE main_category_title='Flags'";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_main_category_id, $get_current_main_category_title) = $row;

	// Functions

	// Strips leading zeros
	// And returns str in UPPERCASE letters with a U+ prefix
	function format($str) {
		$copy = false;
		$len = strlen($str);
		$res = '';

		for ($i = 0; $i < $len; ++$i) {
			$ch = $str[$i];

			if (!$copy) {
			if ($ch != '0') {
 				$copy = true;
			}
			// Prevent format("0") from returning ""
			else if (($i + 1) == $len) {
				$res = '0';
				}
			}

			if ($copy) {
				$res .= $ch;
			}
		}

		return 'U+'.strtoupper($res);
	}

	function emoji_to_unicode($emoji) {
		// ✊🏾 --> 0000270a0001f3fe
		$emoji = mb_convert_encoding($emoji, 'UTF-32', 'UTF-8');
		$hex = bin2hex($emoji);

		// Split the UTF-32 hex representation into chunks
		$hex_len = strlen($hex) / 8;
		$chunks = array();

		for ($i = 0; $i < $hex_len; ++$i) {
			$tmp = substr($hex, $i * 8, 8);

			// Format each chunk
			$chunks[$i] = format($tmp);
		}

		// Convert chunks array back to a string
		$chunks_size = sizeof($chunks);
		$chunks_string = "";
		for($x=0;$x<$chunks_size;$x++){
			if($chunks_string == ""){
				$chunks_string = $chunks[$x];
			}
			else{
				$chunks_string = $chunks_string . " " . $chunks[$x];
			}
		}
		return $chunks_string;
	}

	function emoji_to_hex($emoji) {
		return htmlentities($emoji);
	}

	echo"
	";
	$flags_array = explode("\n", $flags);
	$flags_array_size = sizeof($flags_array);
	echo"
	<p>Flags array size: $flags_array_size</p>
	<table class=\"hor-zebra\">
	 <thead>
	  <tr>
	   <th>
		<span>Flag</span>
	   </th>
	   <th>
		<span>Title</span>
	   </th>
	   <th>
		<span>Code (Unicode)</span>
	   </th>
	   <th>
		<span>Char (Hex)</span>
	   </th>
	  </tr>
	 </thead>
	 <tbody>
	";
	for($x=0;$x<$flags_array_size;$x++){
		// Flag-Symbol	Country	Unicode Hex	HTML Dec Code
		// 🇳🇴|Norway|U+1F1F3 U+1F1F4|&#127475 &#127476
		$temp = explode("|", $flags_array[$x]);
		$flag = "";
		$title = "";

		if(isset($temp[0])){
			$flag = trim($temp[0]);
		}
		if(isset($temp[1])){
			$title = trim($temp[1]);
		}

		if($flag != ""){
			// Title
			$inp_title = output_html($title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			// Code (Unicode)
			$inp_code = emoji_to_unicode($flag);
			$inp_code = str_replace("U+", "", $inp_code);
			$inp_code_mysql = quote_smart($link, $inp_code);
			
			// Char (Hex)
			$inp_char = emoji_to_hex($flag);
			$inp_char_mysql = quote_smart($link, $inp_char);

			// Output_html
			$inp_output_html = htmlentities($flag);
			$inp_output_html_mysql = quote_smart($link, $inp_output_html);

			// String value
			$inp_string_value = htmlentities($flag);
			$inp_string_value_mysql = quote_smart($link, $inp_string_value);

			echo"
			  <tr>
			   <td>
				<span>$flag</span>
			   </td>
			   <td>
				<span>$inp_title</span>
			   </td>
			   <td>
				<span>$inp_code</span>
			   </td>
			   <td>
				<span>$inp_char</span>
			   </td>
			  </tr>
			";


			// Check if exists, if so then delete and then insert
			$query = "SELECT emoji_id FROM $t_emojies_index WHERE emoji_title=$inp_title_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_emoji_id) = $row;
			if($get_emoji_id != ""){
				mysqli_query($link, "DELETE FROM $t_emojies_index WHERE emoji_id=$get_emoji_id") or die(mysqli_error($link));
			}

			mysqli_query($link, "INSERT INTO $t_emojies_index 
			(emoji_id, emoji_main_category_id, emoji_sub_category_id, emoji_title, emoji_replace_a, 
			emoji_replace_b, emoji_replace_c, emoji_is_active, emoji_code, emoji_char, 
			emoji_char_output_html, emoji_char_string_value) 
			VALUES 
			(NULL, $get_current_main_category_id, -1, $inp_title_mysql, '', 
			'', '', 1, $inp_code_mysql, $inp_char_mysql, 
			$inp_output_html_mysql, $inp_string_value_mysql)")
			or die(mysqli_error($link));

		}

	} // for flags
	echo"
	 </tbody>
	</table>
	";

} // isset admin
?>