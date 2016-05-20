<?
# Inisialisasi
$objek->init();

$q = buatKoneksiDB();
$q2 = buatKoneksiDB();

# Isi halaman
$t = buatTemplate();
$t->set_file("handle_katalog", "katalog.html");
$t->set_var("HASIL PENCARIAN", "");
$t->set_var("CARI", "");

$t->set_file("handle_search_list", "cari.html");
$t->set_block("handle_search_list", "elemen", "hdl_elemen");
$t->set_block("handle_search_list", "tidakada", "hdl_empty");
$t->set_var("hdl_empty", "");
$t->set_var("hdl_elemen", "");

$cari = $_GET['cari'];
$t->set_var("CARI", $cari);

$searchVariable = "";
$t->set_var("searchVariable","");

// PAGING
$numRec = 35;
$pageCount = 10;
$page = ($_GET['page'] != '') ? $_GET['page'] : 1;
$show = $_GET['show'];

// Message
$message = $_GET['m'];
if (isset($message) && ($message != "")) {
	$objek->nextmessage($message);
}

// Order ny / Pengurutan
$urut = $_GET['order'];
if (isset($urut) && ($urut != "")) {
	$order = " order by ".$urut;
}else{
	$order = " order by datetime desc";	
}

$kolom_cari = $_GET['kolom_cari'];
if($kolom_cari == ""){
	$whereCari = "";
}else{
	$whereCari = " and ".$kolom_cari." like '%$cari%' ";		
}


$dari = $_GET['dari'];
$sampai = $_GET['sampai'];

if(($dari == "") || ($sampai == "")){
	$whereTanggal = "";
	$t->set_var("dari", "");
	$t->set_var("sampai", "");
}else{
	$whereTanggal = " and l.datetime >= '".$dari."' and l.datetime <= '".$sampai." 23:59:59' ";		
	$t->set_var("dari", $dari);
	$t->set_var("sampai", $sampai);
}


// Menu Combobox User
$id_cari_user = $_GET['id_cari_user'];
if ($rs = $q2->Execute('select * from user order by fullname')) {
	$option = '';
	while (!$rs->EOF) {
		if ($rs->fields['user_id'] == $id_cari_user) $selected = 'selected';
		else $selected = '';
		$option .= '<option value="'.$rs->fields['user_id'].'"'.$selected.'>'.$rs->fields['fullname']."</option>\n";
		$rs->MoveNext();
	}
}
$t->set_var('menuUser', $option);

if($id_cari_user == ""){
	$whereUser = "";
}else{
	$whereUser = " and l.user_id = ".$id_cari_user." ";		
}


// Pencarian
$statusCari = $_GET['statusCari'];
if ($statusCari == "cari") {
	$t->set_var("MESSAGE", "Hasil pencarian ");
//	$t->set_var("MESSAGE", "Hasil pencarian  \"$cari\"");
	$sql = "select l.*,u.fullname from log l join user u on l.user_id = u.user_id where 1=1 ".$whereCari.$whereTanggal.$whereUser.$order;
	$count_query = "select count(*) from log l join user u on l.user_id = u.user_id where 1=1 ".$whereCari.$whereTanggal.$whereUser;
	$searchVariable = "&statusCari=cari&dari=".$dari."&sampai=".$sampai."&cari=".$cari."&id_cari_user=".$id_cari_user;
	$t->set_var("searchVariable", $searchVariable);
}else{
	$t->set_var("MESSAGE", "Seluruh Data user");
	$sql = "select l.*,u.fullname from log l join user u on l.user_id = u.user_id ".$order;
	$count_query = "select count(*) from log l join user u on l.user_id = u.user_id ";
}

//paging
	$recCount = $q2->GetOne($count_query);
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
		$strLinkPage = (($groupOffset*$pageCount) > 0) ? "<li><a href=\"index.php?appid=admin_log$searchVariable&cari=$cari&order=$urut&page=". ($pageprev). "\">&lt;&lt;</a>&nbsp;</li>" : '';

		for($i=$pageStart;$i<=$pageEnd;$i++) {
			if ($i != $page) 
				$strLinkPage .= "<li><a href=\"index.php?appid=admin_log$searchVariable&cari=$cari&order=$urut&page=$i\">$i</a>&nbsp;</li>";
			else 
				$strLinkPage .= "<li class='active'><a href='#'>$i</a></li>";
		}
		$strLinkPage .= ((($groupOffset+1)*$pageCount) < $numpages) ? "<li><a href=\"index.php?appid=admin_log$searchVariable&cari=$cari&order=$urut&page=". ($pagenext). "\">&gt;&gt;</a>&nbsp;</li>" : '';
		$t->set_var('paging', $strLinkPage);
	}
	else 
		$t->set_var('paging', '');

	$offset = (($page-1) * $numRec);
	$nomor = $offset;
	$no = 1;

if($show == "all"){
	$rs = $q2->Execute($sql);
	$t->set_var('paging', '');
}else{
	$rs = $q2->Execute($sql . " limit $offset, $numRec");
}
//echo $sql;

	if ($rs and !$rs->EOF) {
		$count = 0;
		while(!$rs->EOF) {
			++$nomor;
			$no++ % 2 ? $t->set_var('row', 'row0'):$t->set_var('row', 'row1');
			$t->set_var("user", $rs->fields['fullname']);
			$t->set_var("activity", $rs->fields['keterangan']);
			$t->set_var("ip", $rs->fields['ip_address']);
			$t->set_var("datetime", $rs->fields['datetime']);
			$t->set_var("browser_name", $rs->fields['browser_name']);
			$t->set_var("browser_version", $rs->fields['browser_version']);
			$t->set_var("browser_platform", $rs->fields['browser_platform']);

			$t->parse("hdl_elemen", "elemen", true);
			$rs->MoveNext();
		}
	}
	else $t->parse("hdl_empty", "tidakada");
	$t->parse("HASIL PENCARIAN", "handle_search_list");


$t->pparse("output", "handle_katalog");

$objek->footer();
?>
