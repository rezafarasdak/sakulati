<?
$objek->init();
$objek->setTitle('Halaman Utama');

$t = buatTemplate();
$q = buatKoneksiDB();

$t->set_file("handle_template", "utama.html");
$t->set_block("handle_template", "needToBilled", "hdl_needToBilled");
$t->set_block("handle_template", "needToPaid", "hdl_needToPaid");
$t->set_block("handle_template", "elemen", "hdl_elemen");
$t->set_block("handle_template", "consult", "hdl_consult");
$t->set_block("handle_template", "goodsreceipt", "hdl_goodsreceipt");
$t->set_block("handle_template", "invoiceso", "hdl_invoiceso");
$t->set_block("handle_template", "invoicepo", "hdl_invoicepo");
$t->set_block("handle_template", "ada", "hdl_ada");
$t->set_block("handle_template", "tidakada", "hdl_tidakada");
$t->set_var("hdl_needToBilled", "");
$t->set_var("hdl_needToPaid", "");
$t->set_var("hdl_elemen", "");
$t->set_var("hdl_consult", "");
$t->set_var("hdl_goodsreceipt", "");
$t->set_var("hdl_invoiceso", "");
$t->set_var("hdl_invoicepo", "");
$t->set_var("hdl_tidakada", "");
$t->set_var("hdl_ada", "");

$a = ($objek->otentikasi->is_login() ? $objek->user->uid() : '');

if(empty($a)){
	
	$t->parse("hdl_tidakada", "tidakada", true);	
	
}else{

	$userId = $_SESSION['user']->profil['userid'];
	$additional_info = $_SESSION['additional_info'];
	$numRec = 10;

	$user = $objek->user->profil;
	$userEmail = $user['email'];

//	$objek->debugLog("Location [".$objek->getUserLocation()."]");

	// IF Group ID Admin (14), Show All Ticket
	$isAdmin = $q->GetOne("select count(*) from user_group where group_id = 14 and user_id = ".$userId);
	if($isAdmin > 0){
		$whereAdmin = " or 1=1 ";
	}else{
		$whereAdmin = "";
	}

	if($isAdmin > 0){
		$whereLocation = "";
		$whereLocationPo = "";
	}else{
		$locationId = $objek->getUserLocation();
		if(!empty($locationId)){
			$whereLocation = " and so.lokasi_id = ".$locationId;
			$whereLocationPo = " and po.lokasi_id = ".$locationId;
		}else{
			$whereLocation = "";
			$whereLocationPo = "";
		}
	}

	if(!empty($userEmail)){
		$marketingId = $q->GetOne("select id from karyawan where karyawan_divisi_id = 1 and email = '".$userEmail."'");
		if(!empty($marketingId)){
			header("location:index.php?appid=dashboard_marketing");
		}
		$bodID = $q->GetOne("select id from karyawan where karyawan_divisi_id = 4 and email = '".$userEmail."'");
		if(!empty($bodID)){
			header("location:index.php?appid=dashboard_bod");
		}
	}


//	$objek->debugLog($now);

// Start - Customer Need To Billed / Invoice Belum Di Bayar Oleh Customer
	$sql = "select inv.id, inv.in_no, c.nama_persh as customerPershName, inv.original_amount, inv.due_date, so.currency, so.lokasi_id, c.id as custId
			from invoice inv 
			join sales_order so on inv.sales_order_id = so.id 
			join customer c on c.id = so.customer_id 
			where inv.status not in ('P','R')
			and inv.show_on_dashboard = 1 ".$whereLocation."
			order by due_date";

	$rs = $q->Execute($sql." limit ".$numRec);

	if ($rs and !$rs->EOF) {
		$count = 0;
		while(!$rs->EOF) {
			$t->set_var("id", $objek->enc($rs->fields['id']));
			$t->set_var("custId", $objek->enc($rs->fields['custId']));
			$t->set_var("invoice_no", $objek->invoiceFormat($rs->fields['in_no']));
			$t->set_var("due_date", $objek->ubahFormatTanggalForSummary($rs->fields['due_date']));
			$t->set_var("customerPershName", $rs->fields['customerPershName']);
			$t->set_var("currency", $rs->fields['currency']);
			$t->set_var("lokasi", $arrayLokasiName[$rs->fields['lokasi_id']]);
			$t->set_var("amount", $objek->number_format_usd($rs->fields['original_amount']));
			$t->parse("hdl_needToBilled", "needToBilled", true);
			$rs->MoveNext();
		}
	}
// End - Customer Need To Billed / Invoice Belum Di Bayar Oleh Customer


// Start - Supplier Need To Paid / Invoice Belum Di Bayar Ke Suplier
	$sql = "select inv.id, inv.in_no, c.nama_persh as customerPershName, inv.original_amount, inv.due_date, so.currency, so.lokasi_id, c.id as custId
			from invoice_po inv 
			join purchase_order so on inv.purchase_order_id = so.id 
			join suplier c on c.id = so.suplier_id 
			where inv.status not in ('P','R')
			and inv.show_on_dashboard = 1 ".$whereLocation."
			order by due_date";

	$rs = $q->Execute($sql." limit ".$numRec);

	if ($rs and !$rs->EOF) {
		$count = 0;
		while(!$rs->EOF) {
			$t->set_var("id", $objek->enc($rs->fields['id']));
			$t->set_var("custId", $objek->enc($rs->fields['custId']));
			$t->set_var("invoice_no", $objek->purchaseOrderFormat($rs->fields['in_no']));
			$t->set_var("due_date", $objek->ubahFormatTanggalForSummary($rs->fields['due_date']));
			$t->set_var("customerPershName", $rs->fields['customerPershName']);
			$t->set_var("currency", $rs->fields['currency']);
			$t->set_var("lokasi", $arrayLokasiName[$rs->fields['lokasi_id']]);
			$t->set_var("amount", $objek->number_format_usd($rs->fields['original_amount']));
			$t->parse("hdl_needToPaid", "needToPaid", true);
			$rs->MoveNext();
		}
	}
// End - Supplier Need To Paid / Invoice Belum Di Bayar Ke Suplier


// Start - Need To Create Delivery Order,  Delivery Order Belum Di Buat
	$sql = "select so.*, c.nama as customerName, c.nama_persh as customerPershName, k.name as karyawanName 
			from sales_order so 
			join customer c on c.id = so.customer_id 
			join karyawan k on k.id = so.marketing_id
			where so.status in ('N','DP')
			and show_on_dashboard = 1 ".$whereLocation."
			order by so.id";

	$rs = $q->Execute($sql." limit ".$numRec);

	if ($rs and !$rs->EOF) {
		$count = 0;
		while(!$rs->EOF) {
			$t->set_var("id", $objek->enc($rs->fields['id']));
			$t->set_var("so_no_format", $objek->salesOrderFormat($rs->fields['so_no']));
			$t->set_var("date", $objek->ubahFormatTanggalForSummary($rs->fields['date']));
			$t->set_var("customerPershName", $rs->fields['customerPershName']);
			$t->set_var("currency", $rs->fields['currency']);
			$t->set_var("lokasi", $arrayLokasiName[$rs->fields['lokasi_id']]);
			$t->set_var("amount", $objek->number_format_usd($rs->fields['amount']));
			$t->parse("hdl_elemen", "elemen", true);
			$rs->MoveNext();
		}
	}
// End - Need To Create Delivery Order,  Delivery Order Belum Di Buat

// Start - Latest Open Consult


	$sql = "select us.fullname as sender, u.fullname as receiver,o.* from open_ticket o join user u on o.receiver_user_id = u.user_id join user us on o.sender_user_id = us.user_id where (sender_user_id = ".$userId." or receiver_user_id in (".$userId.",0) ".$whereAdmin.") and parent_ticket_number = '' and status not in ('c') order by date desc";
	$rs = $q->Execute($sql." limit ".$numRec);
	$objek->debugLog($sql);
	if ($rs and !$rs->EOF) {
		$count = 0;
		while(!$rs->EOF) {
			
			// Dont Show if Parent Ticket is null or empty
//			if(empty($rs->fields['parent_ticket_number'])){		
				$t->set_var("no", $no);			
				++$nomor;
				$no++ % 2 ? $t->set_var('row', 'row0'):$t->set_var('row', 'row1');
				$t->set_var("ticket_number", $rs->fields[ticket_number]);
				$t->set_var("pengirim", $rs->fields['sender']);			
				$t->set_var("penerima", $rs->fields['receiver']);
				if(!empty($rs->fields['subject'])){
					$t->set_var("subject", $rs->fields['subject']);			
				}else{
					$t->set_var("subject", $rs->fields['content']);			
				}
				$t->set_var("content", $rs->fields['content']);			
				$t->set_var("type", $OpenTicketType[$rs->fields['type']]);			
				$t->set_var("status", $OpenTicketStatus[$rs->fields['status']]);			
				$t->set_var("date", $rs->fields['date']);	
				
				$t->parse("hdl_consult", "consult", true);
//			}

			$rs->MoveNext();
		}
	}
	
// End - Latest Open Consult


// Start - Need To Create Goods Receipt	

	$sql = "select po.*, c.nama as suplierName, c.nama_persh as suplierPershName
			from purchase_order po 
			join suplier c on c.id = po.suplier_id 
			and po.status in ('N','DP')
			and show_on_dashboard = 1 ".$whereLocationPo."
			order by po.id ";
	$rs = $q->Execute($sql." limit ".$numRec);

	if ($rs and !$rs->EOF) {
		$count = 0;
		while(!$rs->EOF) {

			$t->set_var("id", $objek->enc($rs->fields['id']));
			$t->set_var("po_no_format", $objek->purchaseOrderFormat($rs->fields['po_no']));
			$t->set_var("date_po", $objek->ubahFormatTanggalForSummary($rs->fields['date']));
			$t->set_var("suplierPershName", $rs->fields['suplierPershName']);
			$t->set_var("currency", $rs->fields['currency']);
			$t->set_var("lokasi", $arrayLokasiName[$rs->fields['lokasi_id']]);
			$t->set_var("amount", $objek->number_format_usd($rs->fields['amount']));
			$t->set_var("currency", $rs->fields['currency']);

			$t->parse("hdl_goodsreceipt", "goodsreceipt", true);
			$rs->MoveNext();
		}
	}

// End - Need To Create Goods Receipt	



// Start - Need To Create Invoice SO

	$sql = "select so.*, c.nama as customerName, c.nama_persh as customerPershName, k.name as karyawanName 
			from sales_order so 
			join customer c on c.id = so.customer_id 
			join karyawan k on k.id = so.marketing_id
			where status in ('IP','D','DP')
			and show_on_dashboard = 1 ".$whereLocation."
			order by so.id";

	$rs = $q->Execute($sql." limit ".$numRec);

	if ($rs and !$rs->EOF) {
		$count = 0;
		while(!$rs->EOF) {

			$t->set_var("id", $objek->enc($rs->fields['id']));
			$t->set_var("do_no_format", $objek->deliveryOrderFormat($rs->fields['do_no']));
			$t->set_var("so_no_format", $objek->salesOrderFormat($rs->fields['so_no']));
			$t->set_var("date_in_so", $objek->ubahFormatTanggalForSummary($rs->fields['date']));
			$t->set_var("customerPershName", $rs->fields['customerPershName']);
			$t->set_var("currency", $rs->fields['currency']);
			$t->set_var("lokasi", $arrayLokasiName[$rs->fields['lokasi_id']]);
			$t->set_var("amount", $objek->number_format_usd($rs->fields['amount']));
			$t->set_var("currency", $rs->fields['currency']);
		
			$t->parse("hdl_invoiceso", "invoiceso", true);
			$rs->MoveNext();
		}
	}
// END - Need To Create Invoice SO


// Start - Need To Create Invoice PO

	$sql = "select so.*, c.nama as suplierName, c.nama_persh as suplierPershName, k.fullname as karyawanName 
			from purchase_order so 
			join suplier c on c.id = so.suplier_id 
			join user k on k.user_id = so.ordered_karyawan_id
			where so.status in ('IP','D','DP')
			and show_on_dashboard = 1 ".$whereLocation."
			order by so.id";

	$rs = $q->Execute($sql." limit ".$numRec);

	if ($rs and !$rs->EOF) {
		$count = 0;
		while(!$rs->EOF) {

			$t->set_var("id", $objek->enc($rs->fields['id']));
			$t->set_var("do_no_format", $objek->deliveryOrderFormat($rs->fields['gr_no']));
			$t->set_var("po_no_format", $objek->purchaseOrderFormat($rs->fields['po_no']));
			$t->set_var("date_in_po", $objek->ubahFormatTanggalForSummary($rs->fields['date']));
			$t->set_var("suplierPershName", $rs->fields['suplierPershName']);
			$t->set_var("currency", $rs->fields['currency']);
			$t->set_var("lokasi", $arrayLokasiName[$rs->fields['lokasi_id']]);
			$t->set_var("amount", $objek->number_format_usd($rs->fields['amount']));
			$t->set_var("currency", $rs->fields['currency']);
		
			$t->parse("hdl_invoicepo", "invoicepo", true);
			$rs->MoveNext();
		}
	}
// END - Need To Create Invoice PO


	$t->parse("hdl_ada", "ada");	



	
}

$t->pparse("out", "handle_template");
$objek->footer();

?>