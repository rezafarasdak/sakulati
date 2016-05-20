<?
# Inisialisasi
$objek->init();

$q = buatKoneksiDB();
$appName = "Delete Barang Delivery Order";
$objek->setTitle($appName);

# Isi halaman
$t = buatTemplate();

$t->set_file("handle", "delete.html");

$t->set_block("handle", "ada", "hdl_ada");
$t->set_block("handle", "tidakada", "hdl_tidakada");
$t->set_block("handle", "order_detail_ada", "hdl_order_detail_ada");
$t->set_block("handle", "order_detail_tidakada", "hdl_order_detail_tidakada");
$t->set_block("handle", "barang_detail_ada", "hdl_barang_detail_ada");
$t->set_block("handle", "barang_detail_tidakada", "hdl_barang_detail_tidakada");
$t->set_block("handle", "delete", "hdl_delete");

$t->set_var("hdl_barang_detail_tidakada", "");
$t->set_var("hdl_barang_detail_ada", "");
$t->set_var("hdl_order_detail_tidakada", "");
$t->set_var("hdl_order_detail_ada", "");
$t->set_var("hdl_tidakada", "");
$t->set_var("hdl_ada", "");
$t->set_var("hdl_delete", "");

$t->set_var("appid", $_GET['appid']);
$t->set_var("appName", $appName);
$objek->debugLog("Opening Detail");

$user = $objek->user->profil;
$storeId = $user['store_id'];
$id = $objek->dec($_GET['id']);

if (isset($id) && ($id != "")) {
	$sql = "select inv.*, so.so_no, so.currency, so.lokasi_id, so.customer_po, so.customer_date, so.customer_term_of_payment, c.nama as customerName, c.nama_persh as customerPershName 
			from invoice inv 
			join sales_order so on inv.sales_order_id = so.id 
			join customer c on c.id = so.customer_id
			where inv.id = ".$id." limit 1";
			
	$rs = $q->Execute($sql);
	if ($rs and !$rs->EOF) {
		$t->set_var("MESSAGE", "Detail Data");
		while(!$rs->EOF) {
			$t->set_var("id", $objek->enc($rs->fields['id']));
			$t->set_var("idEnc", $objek->enc($rs->fields['id']));
			$so_no = $rs->fields['so_no'];
			$t->set_var("so_no", $so_no);
			$t->set_var("so_no_format", $objek->salesOrderFormat($so_no));
			$in_no = $rs->fields['in_no'];
			$t->set_var("in_no", $in_no);
			$t->set_var("in_no_format", $objek->invoiceFormat($in_no));			
			$t->set_var("date", $objek->ubahFormatTanggalForReport($rs->fields['date']));
			$t->set_var("customerPershName", $rs->fields['customerPershName']);
			$t->set_var("customerName", $rs->fields['customerName']);	// PIC
			$t->set_var("karyawanName", $rs->fields['karyawanName']);
			$t->set_var("customer_po", $rs->fields['customer_po']);
			$t->set_var("customer_po_date", $objek->ubahFormatTanggalForReport($rs->fields['customer_date']));
			$t->set_var("remark", $rs->fields['remark']);
			$t->set_var("customer_term_of_payment", $rs->fields['customer_term_of_payment']);
			$t->set_var("currency", $rs->fields['currency']);
			$t->set_var("lokasi", $arrayLokasiName[$rs->fields['lokasi_id']]);
			$t->set_var("amount", $objek->number_format_usd($rs->fields['amount']));
			$t->set_var("currency", $rs->fields['currency']);
			$t->set_var("status", $arraySalesOrderStatus[$rs->fields['status']]);
			$t->set_var("required_date", $objek->ubahFormatTanggalForReport($rs->fields['required_date']));
			$t->set_var("tax", $objek->number_format_usd($rs->fields['tax']));
			$t->set_var("other_charge", $objek->number_format_usd($rs->fields['other_charge']));
			$t->set_var("sub_total", $objek->number_format_usd($rs->fields['sub_total']));
			$t->set_var("discount", $objek->number_format_usd($rs->fields['discount']));
			$t->set_var("faktur", $rs->fields['faktur']);

			$subTotal = 0;
			
			$sql2 = "select do.* from invoice_detail id join delivery_order do on id.do_id = do.id
					where id.invoice_id = '".$rs->fields['id']."' 
					order by do.id";
//			$objek->debugLog($sql2);
			$rs2 = $q->Execute($sql2);
			if ($rs2 and !$rs2->EOF) {
				$i = 1;
				while(!$rs2->EOF) {
					$t->set_var("no", $i++);
					$t->set_var("do_id", $objek->enc($rs2->fields['id']));
					$do_no = $rs2->fields['do_no'];
					$t->set_var("do_no", $do_no);
					$t->set_var("do_no_format", $objek->deliveryOrderFormat($do_no));
					$t->set_var("do_date", $objek->ubahFormatTanggalForReport($rs2->fields['date']));
					$t->parse("hdl_order_detail_ada", "order_detail_ada", true);
					$rs2->MoveNext();
				}
			}else{
				$t->parse("hdl_order_detail_tidakada", "order_detail_tidakada");
			}
			
			$sql3 = "select bs.name, sum(dod.dikirim) as quantity, sod.price, sum(dod.dikirim*sod.price) as extended_price 
					from invoice_detail id 
					join delivery_order do on id.do_id = do.id
					join delivery_order_detail dod on dod.delivery_order_id = do.id
					join sales_order so on do.sales_order_id = so.id 
					join sales_order_detail sod on sod.sales_order_id = so.id and sod.barang_sales_id = dod.barang_sales_id
					join barang_sales bs on sod.barang_sales_id = bs.id and dod.barang_sales_id = bs.id
					where dod.dikirim > 0 and id.invoice_id = '".$rs->fields['id']."'
					group by bs.name";
			$rs3 = $q->Execute($sql3);
			if($rs3 and !$rs3->EOF){
				$i=1;
				while(!$rs3->EOF) {
					$t->set_var("no", $i++);
					$t->set_var("nama_barang", $rs3->fields['name']);
					$t->set_var("quantity", $rs3->fields['quantity']);
					$t->set_var("unit_price", $objek->number_format_usd($rs3->fields['price']));
					$t->set_var("extended_price", $objek->number_format_usd($rs3->fields['extended_price']));
					$t->parse("hdl_barang_detail_ada", "barang_detail_ada", true);					
					$rs3->MoveNext();
				}
			}else{
				$t->parse("hdl_barang_detail_tidakada", "barang_detail_tidakada", true);									
			}

			$t->parse("hdl_ada", "ada", true);
			$rs->MoveNext();
		}
	}else{
		 $t->parse("hdl_tidakada", "tidakada");
	 	 $t->set_var("MESSAGE", "Data Dengan ID : ".$id." Tidak Di Temukan [1]");
	}
}else{
	$t->parse("hdl_tidakada", "tidakada");
	$t->set_var("MESSAGE", "Data Dengan ID : ".$id." Tidak Di Temukan [2]");
}

$id_delete = $objek->dec($_POST['id_delete']);
$act = $_POST['act'];
if($act == "delete"){
	$sqlDelete = "update invoice set status = 'R' where id = '".$id_delete."'";
	$objek->debugLog("Update Invoice Status To Remove... ".$sqlDelete);
	$objek->userLog('Delete Invoice '.$id_delete);

	// Update Jurnal Status jadi Removed.
	$sqlUpdateJurnal = "update jurnal set status = 'R' where referal_id = '".$id_delete."' and type = 'ar'";
	$objek->debugLog("Updating Jurnal Status To Delete... ".$sqlUpdateJurnal);
	$q->Execute($sqlUpdateJurnal);

	// Update SO jadi New
	$sqlUpdateSO = "update sales_order set status = 'D' where id in (select sales_order_id from invoice where id = '".$id_delete."')";
	$objek->debugLog("Updating SO Status To New... ".$sqlUpdateSO);
	$q->Execute($sqlUpdateSO);

	// Update DO jadi New
	$sqlUpdateDO = "update delivery_order set status = 'N' where id in (select do_id from invoice_detail where invoice_id = '".$id_delete."')";
	$objek->debugLog("Updating DO Status To New... ".$sqlUpdateDO);
	$q->Execute($sqlUpdateDO);	
		 
	$rs2 = $q->Execute($sqlDelete);
	if($rs2){
		header("location:index.php?appid=invoice_report&m=Delete Success");	
	}
}

$t->pparse("output", "handle");

$objek->footer();
?>
