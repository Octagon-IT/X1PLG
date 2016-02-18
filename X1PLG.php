<?php
function DisableAntiFlood() {
return false; //Default: false
}

function DisableBlacklist() {
return false; //Default: false
}

function ForceCookies() {
return true; //Default: true
}

function blacklist_path() {
// Blacklist Location
return $blacklist_url = "X1PLG/ip-blacklist.txt"; //Default: X1PLG/ip-blacklist.txt
}

function whitelist_path() {
// whitelist Location
return $whitelist_url = "X1PLG/ip-whitelist.txt"; //Default: X1PLG/ip-whitelist.txt
}

function forbidden_path() {
//Forbidden page location
return $forbidden_url = 'X1PLG/forbidden.html'; //Default: X1PLG/forbidden.html
}
function forcecookie_path() {
//Force Cookie page location
return $forbidden_url = 'X1PLG/cookie.html'; //Default: X1PLG/cookie.html
}

function ChckSession() {
//start first session
session_start();
$session1 = session_id();
//end first session
session_destroy();
//start second session
session_start();
$session2 = session_id();
//end second session
session_destroy();
//compare sessions
if ($session1 == $session2) {
return true;
}
else {
return false;
}
}

function blacklist() {
$blacklist_url = blacklist_path();
$forbidden_url = forbidden_path();
//read blacklist
$Bfile = fopen($blacklist_url,"r");
while(! feof($Bfile)) {
$raddr = $_SERVER['REMOTE_ADDR'];
  if ($raddr == fgets($Bfile)) {
//if IP is Blacklisted
$uri = forbidden_path();
exit('<meta http-equiv="refresh"content="0;url=' . $uri . '">');
}
}
fclose($file);
}

function antiflood () {
//Anti-flood Settings
$forbidden_url = forbidden_path();
$max_connections = 180 ; //Default: 180
$connection_interval = 600; //Default: 600 (10 minutes)
//Anti-Flood Function
//Set Cookie for saving visit count
if (isset($_COOKIE["SessionCookie"])) {
//adds count to cookie
setcookie("SessionCookie",$_COOKIE['SessionCookie'] + 1, time()+3600);
}
else {
//create cookie first visit
setcookie("SessionCookie", 1,time()+$connection_interval);
}
$Amount = $_COOKIE["SessionCookie"];
if ($Amount >= $max_connections - 1) {
//if max connections exceeded
$uri = forbidden_path();
exit('<meta http-equiv="refresh"content="0;url=' . $uri . '">');
}
}

if (DisableBlacklist() == false) {
//execute blacklist
blacklist();
}

if (DisableAntiFlood() == false) {
//Checks if force cookies is enabled
if (ForceCookies() == true) {
//checks if cookies are enabled
if (ChckSession() == false) {
//if cookies are not enabled an force cookies is true
$uri = forcecookie_path();
exit('<meta http-equiv="refresh"content="0;url=' . $uri . '">');
}
}
//execute antiflood
antiflood();
}
?>
