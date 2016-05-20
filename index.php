<?
//if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); 

ini_set('display_errors', '0');     # don't show any errors...
error_reporting(E_ERROR);  # ...but do log them

if (ereg("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]", $_GET['appid'])) die ("Invalid request");
if (ereg("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]", $_GET['sub'])) die ("Invalid request");

require_once ('global.php');
require_once ('frw/header.inc.php');

//$objek->debugLog("Index Debug 4");

if ($_GET['appid'] != '') {
	$appid = addslashes($_GET['appid']);
}
else {
	$appid = $conf['default_app'];
}

$sub = (isset($_GET['sub'])) ? $_GET['sub'] : "index";

initapp($appid);

# Penyertaan pustaka fungsi milik modul (jika ada)
# nama pustaka fungsi milik modul harus : lib.inc.php
# dan disimpan di subdirektori lib didalam direktori modul.

if (is_file(get_app_lib($appid).'/lib.inc.php')) require_once(get_app_lib($appid).'/lib.inc.php');

if(get_app_dir($appid)){
	include_once(get_app_dir($appid). "/$sub.php");
}else{
	include_once(get_app_dir_under_construction(). "/index.php");
}


//include_once(get_app_dir($appid). "/$sub.php");

?>
