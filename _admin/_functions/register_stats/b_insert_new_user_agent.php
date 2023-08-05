<?php
/*
* This inserts a new user agent because it does not exits. 
* It inserts it into the table based on the 'my_user_agent' variable:
* 
* $my_user_agent = $_SERVER['HTTP_USER_AGENT'];
* $my_user_agent = output_html($my_user_agent);
* if($my_user_agent == ""){ echo"406 Not Acceptable"; die; }
* 
*/

// Functions
include("$root/_admin/_functions/get_between.php");

// debug?
$debug = 0;

// We need to find the following variables:
// $my_user_agent, $inp_type, $inp_browser, $inp_browser_version, 
// $inp_browser_icon, $inp_os, $inp_os_version, $inp_os_icon, $inp_bot, 
// $inp_bot_version, $inp_bot_icon, $inp_bot_website, $inp_banned
$inp_type = "?";
$inp_browser = ""; 
$inp_browser_version = -1;
$inp_browser_icon = "";
$inp_os = "";
$inp_os_version = -1; 
$inp_os_icon = "";
$inp_bot = "";
$inp_bot_version = -1; 
$inp_bot_icon = "";
$inp_bot_website = "";
$inp_banned = 0;


// A B C D E F G H I J K L M N O P Q R S T U V W X Y Z � � �
$robots = array(
    'Apache-HttpClient', 'archive.org_bot', 'AhrefsBot', 'AlphaBot', 'aboundex', 'altavista', 'appengine-google',
    'Baiduspider', 'browsershot', 'botje.com', 'bing', 'bingbot', 
    'CheckMarkNetwork', 'Cliqzbot', 'curl', 'CensysInspect',
    'Dataprovider.com', 'discobot', 'Dispatch', 'edisterbot', 'DuckDuckGo-Favicons-Bot', 'dotbot', 
    'exabot', 'ExtLinksBot', 'evc-batch', 'Embarcadero',
    'facebook', 
    'gigabot', 'G-i-g-a-b-o-t', 'Grammarly', 'googlebot-mobile', 'Googlebot-Image', 'gigabot', 'gidbot', 'googlebot', 'mediapartners-google', 'google-site-verification', 'googlebot-image', 
    'Go-http-client', 'GuzzleHttp', 'Google-Youtube-Links', 
    'ia_archiver', 'ics', 
    'jyxobot',  'lycos', 'J2ME/MIDP',
    'HTTrack', 'Hstpnetwork.com', 
    'linkdexbot', 'iubenda-radar', 'libwww-FM',
    'MJ12bot', 'mail.ru_bot', 'meanpathbo', 'mlbot', 'msnbot', 'MSIECrawler',
    'node-fetch', 
    'openbot', 'one.com-cms-scanner',
    'Qwantify', 
    'PageThing', 'panscient.com', 'PetalBot', 'proximic', 'PocketParser', 'python-requests', 'Python-urllib', 'Python', 
    'Screaming Frog SEO Spider', 'SeznamBot', 'serpstatbot', 'semrushbot', 'SEOkicks-Robot', 'SMTBot','slurp', 'scooter', 'SiteExplorer', 'sqlmap',
    'startsite.com', 'synapse', 'Snapchat', 'Sogou web spider', 'spbot', 'Scrapy', 
    'teoma', 'openacoon', 'twitter', 'temnos', 
    'unikstart', 'Uptimebot',
    'vbseo', 
    'Yandexnot', 'yammybot', 'YandexMobileBot', 'YandexBot', 'yahoo', 
    'zgrab',
    'QuickCMS', 
    'Wappalyzer', 'w3c_validator', 'windows-live', 'WhatsApp', 'WordPress');


foreach($robots as $r){
    $r_position = stripos($my_user_agent, $r);
    if($r_position !== false ){

        // Visitor type
        $inp_type = "bot";

    
        // URL
        $inp_bot_website = "http://" . $r . ".com";
        $inp_bot_website = output_html($inp_bot_website);

        // Bot version
        $inp_bot_version = get_between($my_user_agent, $r, ';');
        if($inp_bot_version == ""){
            $inp_bot_version = get_between($my_user_agent, ucfirst($r), ';');
        }
        $inp_bot_version = str_replace("/", "", $inp_bot_version);
        $inp_bot_version = output_html($inp_bot_version);
        if($inp_bot_version > 10){
            $inp_bot_version = substr($inp_bot_version, 0, 10);
        }
        //echo"Agent: $my_user_agent<br />get_between($my_user_agent, $r, ';')<br />Bot ver: $inp_stats_user_agent_bot_version";



        // Agent Name
        $inp_bot = ucfirst($r);
        $inp_bot = output_html($inp_bot);

        // Icon
        $inp_bot_icon = $r . ".png";
        $inp_bot_icon = output_html($inp_bot_icon);

    }
}


// Not bot :: Check mobile
if($inp_type == "?"){
    // Mobile
    $mobile_os = array('Android', 'Blackberry', 'iPhone', 'iPad', 'Nokia', 'Samsung');
    foreach($mobile_os as $m_os){

        $m_os_position = stripos($my_user_agent, $m_os);
        if($m_os_position !== false ){

            // Visitor type
            $inp_type = "mobile";

            // Browser checkup
            // A B C D E F G H I J K L M N O P Q R S T U V W X Y Z
            $mobile_browsers = array('AppleWebKit', 'Dalvik', 'Mobile Safari', 'Minefield', 'Safari', 'Chrome', 'Firefox', 'Opera', 'OPR', 
                        'SamsungBrowser', 'UCBrowser');
            foreach($mobile_browsers as $m_b){
                 if(stripos($my_user_agent, $m_b) !== false ){
                    $inp_browser = "$m_b";
                }
            }

            $inp_browser_icon = clean($inp_browser);
            $inp_browser_icon = $inp_browser_icon . ".png";
            $inp_browser_icon = output_html($inp_browser_icon);

            // Browser version
            $inp_browser_version = 0;
            if($inp_browser != ""){
                $inp_browser_version = substr($my_user_agent, strrpos($my_user_agent, '/') + 1);
                $inp_browser_version_len = strlen($inp_browser_version);
                if($inp_browser_version_len > 10){
                    $inp_browser_version = substr($inp_rowser_version, 0, 10);
                }
            }
            //echo"Agent: $my_user_agent<br />Browser ver: $inp_stats_user_agent_browser_version ";
            $inp_browser_version = output_html($inp_browser_version);

            // Browser
            $inp_browser = output_html(ucfirst($inp_browser));

            // OS 
            $inp_os = ucfirst($m_os);
            $inp_os = output_html($inp_os);
            $inp_os = str_replace("IPhone", "iPhone", $inp_os);
            $inp_os = str_replace("IPad", "iPad", $inp_os);

            // OS Icon
            $inp_os_icon = clean($m_os);
            $inp_os_icon =  $inp_os_icon. ".png";
            $inp_os_icon = output_html($inp_os_icon);

            // OS Version
            // my_user_agent = Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:87.0) Gecko/20100101 Firefox/87.0
            // os = Windows NT
            // os_position = 13
            $inp_os_version = get_between($my_user_agent, $m_os, ';');
            $inp_os_version = str_replace(" ", "", $inp_os_version);
            $inp_os_version = output_html($inp_os_version);
            if($inp_stats_user_agent_os_version == ""){
                // my_user_agent = Mozilla/5.0 (iPhone; CPU iPhone OS 14_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) GSA/173.0.391310503 Mobile/15E148 Safari/604.1
                // os = CPU iPhone OS
                // os_position = 14_4
                $agent = str_replace("_", ".", $my_user_agent);
                $split_agent_on_space = explode(" ", $agent);
                $split_agent_on_space_size = sizeof($split_agent_on_space);

                $found_os_look_for_version = 0;

                for($x=0;$x<$split_agent_on_space_size;$x++){

                    // First look for OS name, then version
                    $word = $split_agent_on_space[$x];
                    $word = str_replace("(", "", $word);
                    $word = str_replace(";", "", $word);
                    $word = str_replace(")", "", $word);
                    // echo"$word<br />";
                    if($word == "$inp_stats_user_agent_os"){
                        // echo"FOUND OS $word<br />";
                        $found_os_look_for_version = 1;
                    }

                    // Look for version
                    if($found_os_look_for_version == "1" && is_numeric($word)){
                        // echo"FOUND VERSION $word<br />";
                        $inp_os_version = "$word";
                        $inp_os_version = output_html($inp_os_version);
                        break;
                    }
                }
            }
            if($inp_os_version == ""){
                $inp_os_version = "N/A";
            }
            
            // OS :: Look for ) Example 12)AppleWebKit/537.36(KHTML,likeGecko)Chrome/99.0.4844.88MobileSafari/537.36
             if (strpos($inp_os_version, ')') !== false) {
                $os_version_explode = explode(")", $inp_os_version);
                $inp_os_version = $os_version_explode[0];
            }

            $inp_os_version_len = strlen($inp_os_version);
            if($inp_os_version_len > 10){
                $inp_os_version = substr($inp_os_version, 0, 10);
            }
            if($inp_os_version == ""){
                $inp_os_version = "N/A";
            }
        } // found mobile
    } // foreach mobile


} // $inp_type == "?" (Not bot)

// Not bot :: Not mobile :: Check desktop
if($inp_type == "?"){
    // Desktop
    
    // A B C D E F G H I J K L M N O P Q R S T U V W X Y Z � � �
    $desktop_os = array('CrOS', 'Freebsd', 'Fedora', 'Linux x86.64', 'Linux x86_64', 'Linux i686', 'linux-gnu', 'Mac OS X', 'SunOS', 'Windows NT', 'Ubuntu', 'X11');
    foreach($desktop_os as $os){
        $os_position = stripos($my_user_agent, $os);
        if($os_position !== false ){
				
            // Visitor type
            $inp_type = "desktop";

            // OS
            $inp_os = ucfirst($os);
            $inp_os = output_html($inp_os);

            // OS Icon
            $inp_os_icon = clean($os);
            $inp_os_icon = $inp_os_icon . ".png";
            $inp_os_icon = output_html($inp_os_icon);

            // OS Version
            // my_user_agent = Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:87.0) Gecko/20100101 Firefox/87.0
            // os = Windows NT
            // os_position = 13
            $inp_os_version = get_between($my_user_agent, $inp_os, ';');
            $inp_os_version = str_replace(" ", "", $inp_os_version);
            $inp_os_version = output_html($inp_os_version);
            $inp_os_version = trim($inp_os_version);

            // Check for ) in OS version
            if (strpos($inp_os_version, ')') !== false) {
                $os_version_explode = explode(")", $inp_os_version);
                $inp_os_version = $os_version_explode[0];
            }
            if($inp_os_version > 10){
                $inp_os_version = substr($inp_os_version, 0, 10);
            }
            if($inp_os_version == ""){
                $inp_os_version = "N/A";
            }
            
            

            // Browser checkup (desktop)
            $desktop_browsers = array('AppleWebKit', 'Safari', 'Chrome', 'Edge', 'Edg', 'Firefox', 'Galeon', 'Minefield', 'MSIE', 'Opera', 
                            'SeaMonkey', 'SkypeUriPreview', 'Trident', 'Thunderbird', 'Qt', 'Wget');
            foreach($desktop_browsers as $d_b){
                if(stripos($my_user_agent, $d_b) !== false ){
                    $inp_browser = "$d_b";
                }
            }

            // Browser icon
            $inp_browser_icon = clean($inp_browser);
            $inp_browser_icon = $inp_browser_icon . ".png";
            $inp_browser_icon = output_html($inp_browser_icon);


            // Browser version
            if($inp_browser != ""){
                $inp_browser_version = substr($my_user_agent, strrpos($my_user_agent, '/') + 1);
                $inp_browser_version_len = strlen($inp_browser_version);
                if($inp_browser_version_len > 10){
                    $inp_browser_version = substr($inp_browser_version, 0, 10);
                }
            }
            // echo"Agent: $my_user_agent<br />Browser ver: $inp_stats_user_agent_browser_version ";

            // Browser version: check for ;  example 4.0; GTB7.2; SLCC1; .NET CLR 2.0.50727; InfoPath.1; .NET CLR 3.5.30729; .NET CLR 3.0.30618; .NET4.0C)
            if (strpos($inp_browser_version, ';') !== false) {
                $browser_version_explode = explode(";", $inp_browser_version);
                $inp_browser_version = $browser_version_explode[0];
            }

				// Browser version: check for ) example 522.12.1) AND 7184=6517-- QBxL
				if (strpos($inp_browser_version, ')') !== false) {
					$browser_version_explode = explode(")", $inp_browser_version);
					$inp_browser_version = $browser_version_explode[0];
				}

				// Browser version: check for   example 522.12.1) AND 7184=6517-- QBxL
				if (strpos($inp_browser_version, ' ') !== false) {
					$browser_version_explode = explode(" ", $inp_browser_version);
					$inp_browser_version = $browser_version_explode[0];
				}

				// Browser version: check for ( example 10_15_5(Erg&#228
				if (strpos($inp_browser_version, '(') !== false) {
					$browser_version_explode = explode("(", $inp_browser_version);
					$inp_browser_version = $browser_version_explode[0];
				}
				$inp_browser_version = output_html($inp_browser_version);
				$inp_browser_version = str_replace("&amp;#039", "", $inp_browser_version);


				// Browser
				$inp_browser = output_html(ucfirst($inp_browser));

			}
		}

} // $inp_type == "?" (Not bot and not mobile)


$stmt = $mysqli->prepare("INSERT INTO $t_stats_user_agents_index
    (stats_user_agent_id, stats_user_agent_string, stats_user_agent_type, stats_user_agent_browser, stats_user_agent_browser_version, 
    stats_user_agent_browser_icon, stats_user_agent_os, stats_user_agent_os_version, stats_user_agent_os_icon, stats_user_agent_bot, 
    stats_user_agent_bot_version, stats_user_agent_bot_icon, stats_user_agent_bot_website, stats_user_agent_banned) 
    VALUES 
    (NULL,?,?,?,?,
    ?,?,?,?,?,
    ?,?,?,?)");
$stmt->bind_param("sssssssssssss", $my_user_agent, $inp_type, $inp_browser, $inp_browser_version, 
                $inp_browser_icon, $inp_os, $inp_os_version, $inp_os_icon, $inp_bot, 
                $inp_bot_version, $inp_bot_icon, $inp_bot_website, $inp_banned); 
$stmt->execute();
if ($stmt->errno) { echo "Error MySQLi insert: " . $stmt->error; die; }

?>