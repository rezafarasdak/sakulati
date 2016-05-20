<?
# Inisialisasi
$objek->init();
$appName = "Karyawan";
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
$numRec = 500;
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
	$order = " order by k.name";	
}


// Pencarian
$cari = $_GET['cari'];
if (isset($cari) && ($cari != "")) {
	$t->set_var("CARI", $cari);
	$t->set_var("MESSAGE", "Hasil pencarian : \"$cari\"");
	$sql = "SELECT k.id, k.name, k.email, kd.name as divisi, l.name as lokasi
			FROM karyawan k
			JOIN karyawan_divisi kd ON k.karyawan_divisi_id = kd.id
			JOIN lokasi l ON k.lokasi_id = l.id 
			where k.name like '%$cari%' ".$order;
	$count_query = "select count(*)
					FROM karyawan k
					JOIN karyawan_divisi kd ON k.karyawan_divisi_id = kd.id
					JOIN lokasi l ON k.lokasi_id = l.id 
					where k.name like '%$cari%'";
}else{
	$t->set_var("MESSAGE", "Seluruh Data");
	$sql = "SELECT k.id, k.name, k.email, kd.name as divisi, l.name as lokasi
			FROM karyawan k
			JOIN karyawan_divisi kd ON k.karyawan_divisi_id = kd.id
			JOIN lokasi l ON k.lokasi_id = l.id ".$order;
	$count_query = "select count(*) from karyawan
			JOIN karyawan_divisi kd ON k.karyawan_divisi_id = kd.id
			JOIN lokasi l ON k.lokasi_id = l.id ";
}

$objek->debugLog("Query [".$sql."]");

//paging
	$recCount = $q->GetOne($count_query);
	$t->set_var('countTrx', $recCount);
			
	if ($recCount > 0) {
		$numpages = ceil($recCount / $numRec);

		$groupCount = ceil($numpages / $pageCount);
		$groupOffset = floor($page / $pageCount) - 1;
		$groupOffset += (($page % $pageCount) > 0) ? 1 : 0;
		
		$pageprev = $groupOffset * $pageCount;
		$pagenext = (($groupOffset+1)* $pageCount)+1;
		$pageStart = ($groupOffset * $pageCount) + 1;
		$pageEnd = ((($groupOffset+1) * $pageCount) > $numpages) ? $numpages : ($groupOffset+1) * $pageCount;
		$strLinkPage = (($groupOffset*$pageCount) > 0) ? "<a href=\"index.php?appid=karyawan&cari=$cari&order=$urut&page=". ($pageprev). "\">&lt;&lt;</a>&nbsp;" : '';

		for($i=$pageStart;$i<=$pageEnd;$i++) {
			if ($i != $page) 
				$strLinkPage .= "<a href=\"index.php?appid=karyawan&cari=$cari&order=$urut&page=$i\">$i</a>&nbsp;";
			else 
				$strLinkPage .= "<b>$i</b>&nbsp;";
		}
		$strLinkPage .= ((($groupOffset+1)*$pageCount) < $numpages) ? "<a href=\"index.php?appid=karyawan&cari=$cari&order=$urut&page=". ($pagenext). "\">&gt;&gt;</a>&nbsp;" : '';
		$t->set_var('paging', $strLinkPage);
	}
	else 
		$t->set_var('paging', '');

	$offset = (($page-1) * $numRec);
	$nomor = $offset;
	$no = 1;

if($show == "all"){
	$rs = $q->Execute($sql);
	$t->set_var('paging', '');
}else{
	$rs = $q->Execute($sql . " limit $offset, $numRec");
}
	if ($rs and !$rs->EOF) {
		$count = 0;
		while(!$rs->EOF) {
			++$nomor;
			$no++ % 2 ? $t->set_var('row', 'row0'):$t->set_var('row', 'row1');
			$t->set_var("id", $objek->enc($rs->fields['id']));
			$t->set_var("name", $rs->fields['name']);
			$t->set_var("email", $rs->fields['email']);
			$t->set_var("divisi", $rs->fields['divisi']);
			$t->set_var("lokasi", $rs->fields['lokasi']);
			$t->parse("hdl_elemen", "elemen", true);
			$rs->MoveNext();
		}
	}
	else $t->parse("hdl_empty", "tidakada");
	


$t->pparse("output", "handle_search_list");

$objek->footer();
?>
