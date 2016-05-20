<?

$mainPath = str_replace('header.inc.php', '', __FILE__);

#----------------------------------------------#
# penyertaan file konfigurasi				   #
#----------------------------------------------#

$mainConf = parse_ini_file($mainPath. "conf/configSaKuLati.ini", true);
$siteName = $mainConf['site']['siteName'];

#----------------------------------------------#
# Bagian ini tidak diubah pada saaat instalasi #
#----------------------------------------------#

if (!$mainConf['site']['debug']) {
	error_reporting(0);
}

# Definisi konstanta yang diperlukan untuk direktori

# Dir. pustaka fungsi
define('LIB_DIR', $mainConf['site']['path'].'/lib');
# Dir. pustaka fungsi
define('CLASS_DIR', $mainConf['site']['path'].'/classes');

define('FPDF_FONTPATH', $mainConf['site']['path'].'/lib/font/');


# Penyertaan pustaka fungsi

require_once (LIB_DIR . '/adodb5/adodb.inc.php');
require_once (LIB_DIR . '/template.inc');
require_once (LIB_DIR . '/misc.inc.php');			// function2 penting 
//require_once (LIB_DIR . '/spaw2/spaw.inc.php');		// editor WYSWYG added 25-01-2009 ... Reza
require_once (LIB_DIR . '/html2pdf/html2pdf.class.php');	// HTML 2 PDF Versi 4.01
//require_once (LIB_DIR . '/FusionCharts/FusionCharts.php'); // Graph with Fusion Chart V3, added 23-10-2011 ... Reza 
require_once (LIB_DIR . '/dateclass.php');			// function date operation, added 22-01-2012 ... Reza
require_once (LIB_DIR . '/excelwriter.inc.php'); // Save to Excel with Excelwriter, added 01-12-2011 ... Reza 


$conn = buatKoneksiDB('');
$conn->setFetchMode(ADODB_FETCH_ASSOC);

$sql = "select * from sites where siteName = '".$siteName."'";

$conf = $conn->GetRow($sql);
if (!$conf) die ('false site');

# Pemanggilan kelas2 penting
require_class('pengguna');
require_class('otentikasi');
require_class('hak_akses');
require_class('mesin');

include_once($conf['path'].'/header.inc.php');

// LOG FILE 14-08-2013 ... Reza



?>
