<?
# Inisialisasi
$objek->init();
$appName = "List Konsultasi";
$objek->setTitle($appName);

//require_site_lib('lib.additional.inc.php');
//$add = new additional;

$q2 = buatKoneksiDB();
$q = buatKoneksiDB();

# Isi halaman
$t = buatTemplate();
$t->set_file("handle_search_list", "katalog.html");
$t->set_var("HASIL PENCARIAN", "");
$t->set_var("CARI", "");
$t->set_block("handle_search_list", "elemen", "hdl_elemen");
$t->set_block("handle_search_list", "tidakada", "hdl_empty");
$t->set_block("handle_search_list", "menuMentri", "hdl_menuMentri");
$t->set_var("hdl_empty", "");
$t->set_var("hdl_elemen", "");
$t->set_var("CARI", $cari);
$t->set_var("appid", $_GET['appid']);
$t->set_var("appName", $appName);
$objek->debugLog("Opening List");


// GET GROUP ID
$userId = $_SESSION['user']->profil['userid'];

// IF Group ID Admin (14), Show All Ticket
$isAdmin = $q->GetOne("select count(*) from user_group where group_id = 14 and user_id = ".$userId);
if($isAdmin > 0){
	$whereAdmin = " or 1=1 ";
}else{
	$whereAdmin = "";
}

// PAGING
$numRec = 50;
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
	$order = " order by date desc";	
}

// Menu Combobox Pengirim
$id_cari_pengirim = $_GET['id_cari_pengirim'];
if ($rs = $q2->Execute('select user_id,fullname from user order by fullname')) {
	$option = '';
	while (!$rs->EOF) {
		if ($rs->fields['user_id'] == $id_cari_pengirim) $selected = 'selected';
		else $selected = '';
		$option .= '<option value="'.$rs->fields['user_id'].'"'.$selected.'>'.$rs->fields['fullname']."</option>\n";
		$rs->MoveNext();
	}
}
$t->set_var('menu_pengirim', $option);


// Menu Combobox Penerima
$id_cari_penerima = $_GET['id_cari_penerima'];
if ($rs = $q2->Execute('select user_id,fullname from user order by fullname')) {
	$option = '';
	while (!$rs->EOF) {
		if ($rs->fields['user_id'] == $id_cari_penerima) $selected = 'selected';
		else $selected = '';
		$option .= '<option value="'.$rs->fields['user_id'].'"'.$selected.'>'.$rs->fields['fullname']."</option>\n";
		$rs->MoveNext();
	}
}
$t->set_var('menu_penerima', $option);

	
// Pencarian
$statusCari = $_GET['statusCari'];
$id_cari_status = $_GET['id_cari_status'];
$id_cari_type = $_GET['id_cari_type'];

$cari = $_GET['cari'];
$t->set_var("CARI", $cari);

$kolom_cari = $_GET['kolom_cari'];
if($kolom_cari == ""){
	$whereCari = "";
}else{
	$whereCari = " and ".$kolom_cari." like '%$cari%' ";		
}

if($id_cari_pengirim == ""){
	$wherePengirim = "";
}else{
	$wherePengirim = " and o.sender_user_id = ".$id_cari_pengirim." ";		
}

if($id_cari_penerima == ""){
	$wherePenerima = "";
}else{
	$wherePenerima = " and o.receiver_user_id = ".$id_cari_penerima." ";		
}

if($id_cari_status == ""){
	$whereStatus = "";
}else{
	$whereStatus = " and o.status = '".$id_cari_status."' ";		
}

if($id_cari_type == ""){
	$whereType = "";
}else{
	$whereType = " and o.type = '".$id_cari_type."' ";		
}

$dari = $_GET['dari'];
$sampai = $_GET['sampai'];

if(($dari == "") || ($sampai == "")){
	$whereTanggal = "";
	$t->set_var("dari", "");
	$t->set_var("sampai", "");
}else{
	$whereTanggal = " and o.date >= '".$dari."' and o.date <= '".$sampai."' ";		
	$t->set_var("dari", $dari);
	$t->set_var("sampai", $sampai);
}

$searchVariable = "";
$t->set_var("searchVariable","");

if ($statusCari == "cari") {
	$t->set_var("MESSAGE", "Hasil pencarian ");
	$sql = "select us.fullname as sender, u.fullname as receiver,o.* from open_ticket o join user u on o.receiver_user_id = u.user_id join user us on o.sender_user_id = us.user_id where (sender_user_id = ".$userId." or receiver_user_id in (".$userId.",0)".$whereAdmin.") ".$wherePengirim.$wherePenerima.$whereStatus.$whereType.$whereCari.$whereTanggal.$order;
	$count_query = "select count(*) from open_ticket o join user u on o.receiver_user_id = u.user_id join user us on o.sender_user_id = us.user_id where (sender_user_id = ".$userId." or receiver_user_id in (".$userId.",0)".$whereAdmin.") and (o.parent_ticket_number is null or o.parent_ticket_number = '') ".$wherePengirim.$wherePenerima.$whereStatus.$whereType.$whereCari.$whereTanggal;

	$searchVariable = "&statusCari=cari&dari=".$dari."&sampai=".$sampai."&kolom_cari=".$kolom_cari."&id_cari_penerima=".$id_cari_penerima."&id_cari_pengirim=".$id_cari_pengirim."&id_cari_status=".$id_cari_status."&id_cari_type=".$id_cari_type."&cari=".$cari;
	$t->set_var("searchVariable", $searchVariable);

}else{
	$t->set_var("MESSAGE", "Seluruh Data ");
	$sql = "select us.fullname as sender, u.fullname as receiver,o.* from open_ticket o join user u on o.receiver_user_id = u.user_id join user us on o.sender_user_id = us.user_id where (sender_user_id = ".$userId." or receiver_user_id in (".$userId.",0)".$whereAdmin.")".$order;
	$count_query = "select count(*) from open_ticket o join user u on o.receiver_user_id = u.user_id join user us on o.sender_user_id = us.user_id where (sender_user_id = ".$userId." or receiver_user_id in (".$userId.",0)".$whereAdmin.") and (o.parent_ticket_number is null or o.parent_ticket_number = '')";
}

	$objek->debugLog($sql);

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
		$strLinkPage = (($groupOffset*$pageCount) > 0) ? "<a href=\"index.php?appid=openticket$searchVariable&cari=$cari&order=$urut&page=". ($pageprev). "\">&lt;&lt;</a>&nbsp;" : '';

		for($i=$pageStart;$i<=$pageEnd;$i++) {
			if ($i != $page) 
				$strLinkPage .= "<a href=\"index.php?appid=openticket$searchVariable&cari=$cari&order=$urut&page=$i\">$i</a>&nbsp;";
			else 
				$strLinkPage .= "<b>$i</b>&nbsp;";
		}
		$strLinkPage .= ((($groupOffset+1)*$pageCount) < $numpages) ? "<a href=\"index.php?appid=openticket$searchVariable&cari=$cari&order=$urut&page=". ($pagenext). "\">&gt;&gt;</a>&nbsp;" : '';
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
	if ($rs and !$rs->EOF) {
		$count = 0;
		while(!$rs->EOF) {
			
			// Dont Show if Parent Ticket is null or empty
			if(empty($rs->fields['parent_ticket_number'])){		
				$t->set_var("no", $no);			
				++$nomor;
				$no++ % 2 ? $t->set_var('row', 'row0'):$t->set_var('row', 'row1');
				$t->set_var("ticket_number", $rs->fields[ticket_number]);
				$t->set_var("pengirim", $rs->fields['sender']);			
				$t->set_var("penerima", $rs->fields['receiver']);			
				$t->set_var("subject", $rs->fields['subject']);			
				$t->set_var("content", $rs->fields['content']);			
				$t->set_var("type", $OpenTicketType[$rs->fields['type']]);			
				$t->set_var("status", $OpenTicketStatus[$rs->fields['status']]);			
				$t->set_var("date", $rs->fields['date']);	
				
				$t->parse("hdl_elemen", "elemen", true);
			}

			$rs->MoveNext();
		}
	}
	else $t->parse("hdl_empty", "tidakada");
	$t->parse("HASIL PENCARIAN", "handle_search_list");


$t->pparse("output", "handle_search_list");

$objek->footer();
?>
