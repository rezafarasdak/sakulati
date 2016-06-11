<?
# Inisialisasi
$objek->init();
$appName = "Cluster";
$objek->setTitle($appName);

$q = buatKoneksiDB();
# Isi halaman
$t = buatTemplate();

$t->set_file("handle_search_list", "cari.html");
$t->set_block("handle_search_list", "elemen", "hdl_elemen");
$t->set_block("handle_search_list", "tidakada", "hdl_empty");
$t->set_block("handle_search_list", "add", "hdl_add");
$t->set_var("hdl_empty", "");
$t->set_var("hdl_elemen", "");
$t->set_var("hdl_add", "");
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

if($objek->isAdmin()){
	$t->parse("hdl_add", "add", true);	
	$whereLahan = "";
}else{
	$whereLahan = " and l1.id in (select id_lahan from lahan_role where id_user = ".$user[userid].")";
}


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
	$sql = "select l1.*, l2.name as lahanutama, jp.name as jenispertanian from lahan l1 
join lahan l2 on l1.id_lahanutama = l2.id
join jenis_pertanian jp on jp.id = l1.id_jenispertanian
where l1.type = 'C' ".$whereLahan." and name like '%$cari%' ".$order;
	$count_query = "select count(*)  from lahan l1 
join lahan l2 on l1.id_lahanutama = l2.id
join jenis_pertanian jp on jp.id = l1.id_jenispertanian
where l1.type = 'C' ".$whereLahan." and name like '%$cari%' ";

}else{
	$t->set_var("MESSAGE", "Seluruh Data");
	$sql = "select l1.*, l2.name as lahanutama, jp.name as jenispertanian from lahan l1 
join lahan l2 on l1.id_lahanutama = l2.id
join jenis_pertanian jp on jp.id = l1.id_jenispertanian
where l1.type = 'C' ".$whereLahan." ".$order;
	$count_query = "select count(*)  from lahan l1 
join lahan l2 on l1.id_lahanutama = l2.id
join jenis_pertanian jp on jp.id = l1.id_jenispertanian
where l1.type = 'C' ".$whereLahan."";
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
		$strLinkPage = (($groupOffset*$pageCount) > 0) ? "<a href=\"index.php?appid=admin_cart_of_account_middle&cari=$cari&order=$urut&page=". ($pageprev). "\">&lt;&lt;</a>&nbsp;" : '';

		for($i=$pageStart;$i<=$pageEnd;$i++) {
			if ($i != $page) 
				$strLinkPage .= "<a href=\"index.php?appid=admin_cart_of_account_middle&cari=$cari&order=$urut&page=$i\">$i</a>&nbsp;";
			else 
				$strLinkPage .= "<b>$i</b>&nbsp;";
		}
		$strLinkPage .= ((($groupOffset+1)*$pageCount) < $numpages) ? "<a href=\"index.php?appid=admin_cart_of_account_middle&cari=$cari&order=$urut&page=". ($pagenext). "\">&gt;&gt;</a>&nbsp;" : '';
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

			$realPohon = $q->GetOne("select count(*) from pohon p where p.id_lahan = ".$rs->fields['id']);

			$t->set_var("id", $objek->enc($rs->fields['id']));
			$t->set_var("no", $nomor);
			$t->set_var("idDec", $rs->fields['id']);
			$t->set_var("jumlah_pohon", $rs->fields['jumlah_pohon'].", Sampling ".$realPohon." Pohon");
			$t->set_var("name", $rs->fields['name']);
			$t->set_var("lahanutama", $rs->fields['lahanutama']);
			$t->set_var("jenispertanian", $rs->fields['jenispertanian']);
			$t->set_var("kordinat", $rs->fields['latitude_longtitude']);
			$t->set_var("luas", $rs->fields['luas']);
			$t->set_var("status", $arrayStatus[$rs->fields['status']]);
			$t->set_var("last_panen", $rs->fields['terakhir_panen']);
//			$t->set_var("jumlah_pohon", );
			$t->parse("hdl_elemen", "elemen", true);
			$rs->MoveNext();
		}
	}
	else $t->parse("hdl_empty", "tidakada");
	


$t->pparse("output", "handle_search_list");

$objek->footer();
?>
