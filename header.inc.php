<?
$nama_bulan = array( 1  => 'Januari',
                     2  => 'Pebruari',
                     3  => 'Maret',
                     4  => 'April',
                     5  => 'Mei',
                     6  => 'Juni',
                     7  => 'Juli',
                     8  => 'Agustus',
                     9  => 'September',
                     10 => 'Oktober',
                     11 => 'Nopember',
                     12 => 'Desember');

# Kode Bahasa
$nama_kd_bahasa = array('ind' => 'Indonesia',
                        'eng' => 'Inggris');


# Dir. template utama
define('MAIN_TEMPLATE_DIR', is_dir($conf['path']. '/template/'. $conf['theme'])? $conf['path']. '/template/'. $conf['theme'] : $conf['path']. '/template/default');	
# Dir modul
define('MODULE_DIR', $conf['path']. '/'. $conf['modulePath']);
# Dir. blok
define('BLOCK_DIR', $conf['path']. '/'. $conf['blockPath']);		
# Dir. template blok
define('BLOCK_TEMPLATE_DIR', is_dir(BLOCK_DIR. '/template/'. $conf['theme']) ? BLOCK_DIR. '/template/'. $conf['theme'] : BLOCK_DIR. '/template/default');	
define('SITE_LIB', $conf['path']. '/lib');	

require_once(SITE_LIB. '/class.mesin.php');

function require_site_lib($libname) {
	require_once(SITE_LIB. '/'. $libname);
}

class dasar {
	function errstr() {
		return ($this->errorstring != '') ? $this->errorstring : false;
	}
}

# Instantiasi objek engine
//if (!is_a($perpus, 'anakluarbiasa')) $anakluarbiasa = new anakluarbiasa;
if (!is_a($perpus, 'objek')) $objek = new objek;
//$objek->debugLog("Header Debug 3 ");

?>