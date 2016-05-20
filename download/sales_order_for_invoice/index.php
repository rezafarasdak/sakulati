<?
# Inisialisasi
$objek->init();
$appName = "Create Invoice On Sales Order";
$objek->setTitle($appName);

$q = buatKoneksiDB();

$user = $objek->user->profil;
$storeId = $user['store_id'];

# Isi halaman
$t = buatTemplate();

$t->set_file("handle_search_list", "cari.html");
$t->set_block("handle_search_list", "elemen", "hdl_elemen");
$t->set_block("handle_search_list", "tidakada", "hdl_empty");
$t->set_var("hdl_empty", "");
$t->set_var("hdl_elemen", "");
$t->set_var("HASIL PENCARIAN", "");
$t->set_var("CARI", "");
$t->set_var("appid", $_GET['appid']);
$t->set_var("appName", $appName);
$objek->debugLog("Opening Index");

// PAGING
$numRec = 3500;
$pageCount = 10;
$page = ($_GET['page'] != '') ? $_GET['page'] : 1;
$show = $_GET['show'];

// Message
$message = $_GET['m'];
if (isset($message) && ($message != "")) {
	$objek->nextmessage($message);
}

// Check Ketersediaan Counter Faktur Pajak, Bila Tidak Tersedia, Redirect Ke Home Invoice.
$sisaCounterFaktur = $q->GetOne("select counter_max - counter_min from counter where name = 'faktur'");
if($sisaCounterFaktur <= 0){
	$objek->nextmessage("Tidak Ada Faktur Tersisa, Tidak Bisa Membuat Invoice", "danger");
}elseif($sisaCounterFaktur <= 10) {
	$objek->nextmessage("Sisa Nomor Faktur Adalah ".$sisaCounterFaktur);	
}

// Order ny / Pengurutan
$urut = $_GET['order'];
if (isset($urut) && ($urut != "")) {
	$order = " order by ".$urut;
}else{
	$order = " order by nama_persh";	
}

// Pencarian
$cari = $_GET['cari'];
$field = $_GET['field'];
$status = $_GET['status'];
$start = $_GET['start'];
$end = $_GET['end'];

if (!empty($cari) || (!empty($status)) || (!empty($end)) ) {
	$t->set_var("CARI", $cari);
	
	if(!empty($cari)){
		$whereCari = " and ".$field." like '%".$cari."%'";
		$message .= $arrayFieldPencarian[$field]." : ".$cari;
		$message .= "<br>";
	}

	if(!empty($status)){	
		$whereStatus = " and so.status = '".$status."'";
		$message .= " Status : ".$arraySalesOrderStatus[$status];
		$message .= "<br>";
	}
	
	if(!empty($end)){
		if(!empty($start)){
			$whereDate = " and so.date between '".$start."' and '".$end."'";
			$message .= "Tanggal Mulai Dari ".$start." Sampai ".$end;
		}else{
			$whereDate = " and so.date < '".$end."'";	
			$message .= "Tanggal Sampai ".$end;		
		}
		$message .= "<br>";
	}
	
	$sql = "select so.*, c.nama as customerName, c.nama_persh as customerPershName, k.name as karyawanName 
			from sales_order so 
			join customer c on c.id = so.customer_id 
			join karyawan k on k.id = so.marketing_id
			where status in ('IP','D','DP') ".$whereCari.$whereStatus.$whereDate."
			order by so.id desc";

	$count_query = "select count(*)
			from sales_order so 
			join customer c on c.id = so.customer_id 
			join karyawan k on k.id = so.marketing_id
			where status in ('IP','D','DP') ".$whereCari.$whereStatus.$whereDate;

	$t->set_var("MESSAGE", "Hasil pencarian <br> ".$message);

}else{
	$t->set_var("MESSAGE", "");
	$sql = "select so.*, c.nama as customerName, c.nama_persh as customerPershName, k.name as karyawanName 
			from sales_order so 
			join customer c on c.id = so.customer_id 
			join karyawan k on k.id = so.marketing_id
			where status in ('IP','D','DP')
			order by so.id desc";
			
	$count_query = "select count(*)
			from sales_order so 
			join customer c on c.id = so.customer_id 
			join karyawan k on k.id = so.marketing_id
			where status in ('IP','D','DP')
			order by so.id desc";
}

	$objek->debugLog("Query : ".$sql);
//paging
	$recCount = $q->GetOne($count_query);
	$t->set_var('countTrx', $recCount);
			

	$rs = $q->Execute($sql);

	if ($rs and !$rs->EOF) {
		$count = 0;
		while(!$rs->EOF) {
			++$nomor;
			$no++ % 2 ? $t->set_var('row', 'row0'):$t->set_var('row', 'row1');
			$t->set_var("id", $objek->enc($rs->fields['id']));

			$do_no = $rs->fields['do_no'];
			$t->set_var("do_no", $do_no);
			$t->set_var("do_no_format", $objek->deliveryOrderFormat($do_no));

			$so_no = $rs->fields['so_no'];
			$t->set_var("so_no", $so_no);
			$t->set_var("so_no_format", $objek->salesOrderFormat($so_no));

			$t->set_var("date", $objek->ubahFormatTanggalForReport($rs->fields['date']));
			$t->set_var("customerPershName", $rs->fields['customerPershName']);
			$t->set_var("customerName", $rs->fields['customerName']);	// PIC
			$t->set_var("karyawanName", $rs->fields['karyawanName']);
			$t->set_var("customer_po", $rs->fields['customer_po']);
			$t->set_var("customer_term_of_payment", $rs->fields['customer_term_of_payment']);
			$t->set_var("currency", $rs->fields['currency']);
			$t->set_var("lokasi", $arrayLokasiName[$rs->fields['lokasi_id']]);
			$t->set_var("amount", $objek->number_format_usd($rs->fields['amount']));
			$t->set_var("currency", $rs->fields['currency']);
			$t->set_var("status", $arraySalesOrderStatus[$rs->fields['status']]);

			$t->parse("hdl_elemen", "elemen", true);
			$rs->MoveNext();
		}
	}
	else $t->parse("hdl_empty", "tidakada");

$t->pparse("output", "handle_search_list");

$objek->footer();
?>
