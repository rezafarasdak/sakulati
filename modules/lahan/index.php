<?
# Inisialisasi
$objek->init();
$appName = "Management Lahan";
$objek->setTitle($appName);

$q = buatKoneksiDB();
# Isi halaman
$t = buatTemplate();

$t->set_file("handle_search_list", "cari.html");
$t->set_block("handle_search_list", "elemen", "hdl_elemen");
$t->set_block("handle_search_list", "tidakada", "hdl_empty");
$t->set_var("hdl_empty", "");
$t->set_var("hdl_elemen", "");
$t->set_var("HASIL PENCARIAN", "");
$t->set_var("CARI", "");
$t->set_var("hdl_new", "");

$t->set_var("appid", $_GET['appid']);
$t->set_var("appName", $appName);
$objek->debugLog("Opening Index");

if(isset($_GET['new'])){
	$t->parse("hdl_new", "new", true);
}

$user = $objek->user->profil;

// PAGING
$numRec = 3500;
$pageCount = 10;
$page = ($_GET['page'] != '') ? $_GET['page'] : 1;
$show = $_GET['show'];

// Message
$message = $_GET['m'];
$message_type = $_GET['mt'];
if (isset($message) && ($message != "")) {
	$objek->nextmessage($message, $message_type);
}

// Order ny / Pengurutan
$urut = $_GET['order'];
if (isset($urut) && ($urut != "")) {
	$order = " order by ".$urut;
}else{
	$order = " order by name";	
}


// Pencarian
$cari = $_GET['cari'];
if (isset($cari) && ($cari != "")) {
	$t->set_var("CARI", $cari);
	$t->set_var("MESSAGE", "Hasil pencarian : \"$cari\"");
	$sql = "select * from lahan where type = 'M' and name like '%$cari%' ".$order;
	$count_query = "select count(*) from lahan where type = 'M' and name like '%$cari%'";
}else{
	$t->set_var("MESSAGE", "Seluruh Data");
	$sql = "select * from lahan where type = 'M' ".$order;
	$count_query = "select count(*) from lahan where type = 'M'";
}

$objek->debugLog("Query [".$sql."]");

$recCount = $q->GetOne($count_query);
$t->set_var('countTrx', $recCount);

$rs = $q->Execute($sql);

if ($rs and !$rs->EOF) {
	$count = 0;
	while(!$rs->EOF) {
		++$nomor;
		$t->set_var("id", $objek->enc($rs->fields['id']));
		$t->set_var("no", $nomor);
		$t->set_var("idDec", $rs->fields['id']);
		$t->set_var("name", $rs->fields['name']);
		$t->set_var("kordinat", $rs->fields['latitude_longtitude']);
		$t->set_var("luas", $rs->fields['luas']);
		$t->set_var("status", $arrayStatus[$rs->fields['status']]);
		$t->set_var("last_panen", $rs->fields['terakhir_panen']);
		$t->set_var("jumlah_cluster", $q->GetOne("select count(*) from lahan where type = 'C' and id_lahanutama = ".$rs->fields['id']));
		$t->parse("hdl_elemen", "elemen", true);
		$rs->MoveNext();
	}
}
else $t->parse("hdl_empty", "tidakada");

$t->pparse("output", "handle_search_list");

$objek->footer();
?>
