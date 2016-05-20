<?
# membuat objek koneksi ADODB berdasarkan setting db individual yang dispesifikasikan di config.ini
# Parameter :
# $id_koneksi = nama header konfigurasi dari config.ini tanpa diawali 'db_', defaultnya adalah
#               'database' yang merupakan konfigurasi DB utama untuk engine
# $debug = flag debug untuk menampilkan query dan pesan-pesan lain dari ADODB/MySQL, true|false,
#          default adalah false

// new connection with adodb5 ... Reza(25-01-2009)
// Use Nconnect if want to conncet more 1 database, Pconncet only can conncet 1 database

function buatKoneksiDB($id_koneksi = '', $debug = false) {
	$id_koneksi = ($id_koneksi != '') ? 'db_'. $id_koneksi : 'database';
	$koneksi = &ADONewConnection('mysql');
	$koneksi->debug = $debug;
	$koneksi->NConnect($GLOBALS['mainConf'][$id_koneksi]['host'], $GLOBALS['mainConf'][$id_koneksi]['username'], $GLOBALS['mainConf'][$id_koneksi]['password'], $GLOBALS['mainConf'][$id_koneksi]['name']);
	return $koneksi;
}

function buatKoneksiDBOld($id_koneksi = '', $debug = false) {
	$id_koneksi = ($id_koneksi != '') ? 'db_'. $id_koneksi : 'database';
	$koneksi = ADONewConnection('mysql');
	$koneksi->debug = $debug;
	$koneksi->NConnect($GLOBALS['mainConf'][$id_koneksi]['host'], $GLOBALS['mainConf'][$id_koneksi]['username'], $GLOBALS['mainConf'][$id_koneksi]['password'], $GLOBALS['mainConf'][$id_koneksi]['name']);
	return $koneksi;
}

# Membuat objek Template PHPLIB, dengan path yang ditentukan, atau yang standar yang tersedia oleh
# engine.
# Parameter:
# $path = dapat bernilai kosong atau '_app' yang berarti lokasi template pada direktori modul,
#         atau bernilai '_main' yang berarti direktori template utama.

function buatTemplate($path = '') {
	switch($path) {
		case '' :
		case '_app' :
			$path_template = TEMPLATE_DIR;
			break;
		case '_main' :
			$path_template = MAIN_TEMPLATE_DIR;
			break;
		default :
			$path_template = $path;
	}
	$tmpl = new Template($path_template);
	return $tmpl;
}

# Membuat Excel... added 02-12-2011 ... Reza
function makeExcel($nameFile){
	if(empty($nameFile)){
		$excel=new ExcelWriter("noName.xls");
	}else{
		$excel=new ExcelWriter($nameFile);
	}
	return $excel;
}


# Membuat editor wyswyg... added 26-01-2009 ... Reza
function makeWyswyg($id,$konten){
	if(empty($konten)){
		$wyswyg = new SpawEditor($id);
	}else{
		$wyswyg = new SpawEditor($id,$konten);
	}
	return $wyswyg;
}

//$html2pdf = new HTML2PDF('P','A4','fr', false);
//$sens = 'P', $format = 'A4', $langue='fr', $unicode=true, $encoding='UTF-8', $marges = array(5, 5, 5, 8)
# Membuat PDF ... added 31-07-2011 ... Reza
function buatHtml2Pdf($sens = 'P', $format = 'LETTER', $langue='fr', $unicode=false, $encoding='ISO-8859-15'){
	$html2pdf = new HTML2PDF($sens,$format,$langue, $unicode, $encoding);
	return $html2pdf;
}

# Memanggil Date Class, untuk date operation, Reza 2012 01 22
function dateClass(){
	$d = new date();
	return $d;
}

# inisialisasi konstanta yang terkait dengan module
function initapp($appid) {
    define('APP_DIR', get_app_dir($appid));					# Dir. modul
    define('TEMPLATE_DIR', get_app_template($appid));		# Dir. template modul
	define('APP_LIB', get_app_lib($appid));					# Dir. library
}

# menyediakan path dari modul yang digunakan untuk inisialisasi konstanta, dll.
function get_app_dir($modulename) {
	return is_dir(MODULE_DIR. '/'. $modulename) ? MODULE_DIR.'/'.$modulename : false;
}

# adding under construction mode
function get_app_dir_under_construction() {
	return MODULE_DIR. '/under_construction';
}

# menyediakan path dari template modul yang digunakan untuk inisialisasi konstanta, dll.
function get_app_template($modulename) {
	$template_base_dir = get_app_dir($modulename).'/template';
	return is_dir($template_base_dir.'/'.$GLOBALS['conf']['theme']) ? $template_base_dir.'/'.$GLOBALS['conf']['theme'] : $template_base_dir.'/default';
}

function get_siteLib($libname) {
	include_once($GLOBALS['conf']['path'].'/lib/.'.$libname.'.php');
}

# menyediakan path library modul yang digunakan untuk inisialisasi konstanta, dll.
function get_app_lib($modulename) {
	return is_dir(get_app_dir($modulename).'/lib') ? get_app_dir($modulename).'/lib' : false;
}

# mengimpor library modul
function import_lib($module) {
	include_once(get_app_lib($module).'/lib.inc.php');
}

# mengimpor library non standar (yang tidak dimuat secara default)
function require_lib($libname) {
	include_once(LIB_DIR.'/'.$libname);
}

# mengimpor kelas (yang tidak dimuat secara default)
function require_class($classname) {
	$classLocation = CLASS_DIR.'/class.'.$classname.'.php';
	if (is_file($classLocation)) {
		include_once($classLocation);
		return true;
	}
	else return false;
}

# Menyertakan blok
function blok($namafile, $block_header='') {
	ob_start();
	include($namafile);
	echo "<br>";
	$block_content = ob_get_contents();
	ob_end_clean();
	return $block_content;
}

# menghasilkan seed untuk pembuatan bilangan acak
function make_seed() { 
   list($usec, $sec) = explode(' ', microtime()); 
   return (float) $sec + ((float) $usec * 100000); 
} 

# menghasilkan karakter acak sebanyak minimum $min dan maksikmum $max
function generatePassword($min = 4, $max = 4){
	rand(0,time());
	$possible="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
	while(strlen($str)< rand($min,$max)) {
		srand(make_seed());
		$str.=substr($possible,(rand()%(strlen($possible))),1);
	}
	return $str;
}

# konversi tanggal dari mysql ke tanggal format PHP
function mysql2date($str_date) {
	if (!preg_match("/[12][0-9]{3}-[01]?[0-9]-[0123]?[0-9]/", $str_date)) return false;
	$arr_date = split('-', $str_date);
	if (!checkdate($arr_date[1],$arr_date[2],$arr_date[0])) return false;
	return mktime(0,0,0,$arr_date[1],$arr_date[2],$arr_date[0]);
}

# konversi tanggal dd-mm-yyyy menjadi tanggal versi MySQL yyyy-mm-dd
function strdate2mysql($str_date) {
	if (!preg_match("/[0123]?[0-9]-[01]?[0-9]-[12][0-9]{3}/", $str_date)) return false;
	$arr_date = split('-', $str_date);
	if (!checkdate($arr_date[1],$arr_date[2],$arr_date[0])) return false;
	return $arr_date[0].'-'.$arr_date[1].'-'.$arr_date[2];
}

# INPUT DATE MYSQL WAY
function joDateCheck($date, $yearepsilon=5000) 
{ 
	// inputs format is "YYYY-MM-DD" ONLY !
	if (count($datebits=explode('-',$date))!=3) return false;
	$year = intval($datebits[0]);
	$month = intval($datebits[1]);
	$day = intval($datebits[2]);
	if ((abs($year-date('Y'))>$yearepsilon) || // year outside given range
	($month<1) || ($month>12) || ($day<1) ||
	(($month==2) && ($day>28+(!($year%4))-(!($year%100))+(!($year%400)))) ||
	($day>30+(($month>7)^($month&1)))) return false; // date out of range';
	return checkdate($month,$day,$year );
}

# konversi tanggal format PHP menjadi tanggal format MySQL
function date2mysql($timestamp = 0) {
	return ($timestamp) ? date('Y-m-d', $timestamp) : date('Y-m-d');
}

# Mengembalikan url file yang direquest(seperti PHPSELF) lengkap dgn parameternya
function self_url($variable = '', $value  = '') {
	$ok = false;
	$get_baru = '';
	if (isset($_SERVER['QUERY_STRING']) and $_SERVER['QUERY_STRING'] != '') {
		$array_get = split('&', $_SERVER['QUERY_STRING']);
		foreach($array_get as $isi) {
			list($var, $nilai) = split('=', $isi);
			if ($get_baru != '') $get_baru .= '&';
			if ($variable != '') {
				if ($var == $variable) {
					$nilai = urlencode($value);
					$ok = true;
				}
			}
			$get_baru .= "$var=$nilai";
		}
		if (!$ok) {
			if ($get_baru != '') $get_baru .= '&';
			$get_baru .= "$variable=". urlencode($value);
		}
		$self_url = $_SERVER['PHP_SELF']. '?'. $get_baru;
	}
	else {
		if ($variable == '') $self_url = $_SERVER['PHP_SELF'];
		else $self_url = $_SERVER['PHP_SELF'] . "?$variable=$value";
	}
	return $self_url;
}

function numb_fmt($number) {
	if ($number > 999999) {
		if ($number > 999999999) {
			if ($number > 100000000000) {
				$dec_point = 1;
			} 
			else {
				$dec_point = 3;
			}
			$number = $number / 1000000000;
			$formatted = number_format($number, $dec_point);
			$formatted = $formatted."B";
		}
		else {
			if ($number > 100000000) {
				$dec_point = 1;
			} 
			else {
				$dec_point = 3;
			}
			$number = $number / 1000000;
			$formatted = number_format($number, $dec_point);
			$formatted = $formatted."M";
		}
	}
	else {
		$formatted = (($number - floor($number)) > 0) ? number_format($number,2) : number_format($number, 0);
	}
	return $formatted;
}
?>
