<?
# Inisialisasi
$objek->init();
$appName = "Create Invoice";
$objek->setTitle($appName);

$q = buatKoneksiDB();

# Isi halaman
$t = buatTemplate();

$t->set_file("handle", "detail.html");
$t->set_block("handle", "ada", "hdl_ada");
$t->set_block("handle", "tidakada", "hdl_tidakada");
$t->set_block("handle", "sales_order_detail_ada", "hdl_sales_order_detail_ada");
$t->set_block("handle", "sales_order_detail_tidakada", "hdl_sales_order_detail_tidakada");

$t->set_var("hdl_tidakada", "");
$t->set_var("hdl_ada", "");
$t->set_var("hdl_sales_order_detail_tidakada", "");
$t->set_var("hdl_sales_order_detail_ada", "");
$t->set_var("appid", $_GET['appid']);
$t->set_var("appName", $appName);
$objek->debugLog("Opening Form");

$sisaCounterFaktur = $q->GetOne("select counter_max - counter_min from counter where name = 'faktur'");
if($sisaCounterFaktur <= 0){
	header("location:index.php?appid=sales_order_for_invoice");
}

// GET ORDER NUMBER
$id = $objek->dec($_GET['id']);
if (isset($id) && ($id != "")) {
	$sql = "select so.*, c.nama as customerName, c.nama_persh as customerPershName, k.name as karyawanName 
			from sales_order so 
			join customer c on c.id = so.customer_id 
			join karyawan k on k.id = so.marketing_id
			where so.id = ".$id." limit 1";

	$rs = $q->Execute($sql);
	if ($rs and !$rs->EOF) {
		$t->set_var("MESSAGE", "Edit Data");
		while(!$rs->EOF) {
			$t->set_var("id", $objek->enc($rs->fields['id']));
			$so_no = $rs->fields['so_no'];
			$t->set_var("so_no", $so_no);
			$t->set_var("so_no_format", $objek->salesOrderFormat($so_no));
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
			$t->set_var("do_id", "");
			$t->set_var("sub_total", $objek->number_format_usd($rs->fields['sub_total']));
			$t->set_var("tax", $objek->number_format_usd($rs->fields['tax_amount']));
			$t->set_var("other_amount", $objek->number_format_usd($rs->fields['other_amount']));
			$curr = $rs->fields['currency'];
			// If Currency = IDR, No Need to convert
			if($curr == "IDR"){
				$t->set_var("rate", "");
				$t->set_var("fxRate", "1");
				$t->set_var("amount_idr", "1");
			}else{
				$idrAmount = $q->GetOne("select amount from rate where currency = '".$rs->fields['currency']."' order by valid_date desc");
				$t->set_var("rate", ", 1 ".$rs->fields['currency']." = ".$objek->number_format_usd($idrAmount)." IDR");
				$t->set_var("fxRate", $idrAmount);
				$t->set_var("amount_idr","(". $objek->number_format_usd($rs->fields['other_amount'] * $idrAmount)." IDR)");
			}
			
			$sql2 = "select * 
					from delivery_order do 
					where do.sales_order_id = '".$rs->fields['id']."' and do.status in ('N')
					order by do.id";
			$objek->debugLog("Detail : ".$sql2);
					
			$rs2 = $q->Execute($sql2);
			if ($rs2 and !$rs2->EOF) {
				$i = 1;
				while(!$rs2->EOF) {
					$t->set_var("no", $i++);
					$t->set_var("do_id", $objek->enc($rs2->fields['id']));
					$do_no = $rs2->fields['do_no'];
					$t->set_var("do_no", $do_no);
					$t->set_var("do_no_format", $objek->deliveryOrderFormat($do_no));
					$t->set_var("do_date", $objek->ubahFormatTanggalForReport($rss->fields['date']));
					$do_amount = $objek->getAmountFromDeliveryOrder($rs2->fields['id']);
					$t->set_var("do_amount", $objek->number_format_usd($do_amount));
					$t->set_var("do_amount_no_format", $do_amount);

					// If Currency = IDR, No Need to convert
					if($curr == "IDR"){
						$t->set_var("do_amount_idr", "");
					}else{
						$t->set_var("do_amount_idr", "( ".$objek->number_format_usd($do_amount * $idrAmount)." IDR)");
					}
					
					$t->parse("hdl_sales_order_detail_ada", "sales_order_detail_ada", true);
					$rs2->MoveNext();
				}
			}else{
				$t->parse("hdl_sales_order_detail_tidakada", "sales_order_detail_tidakada");
			}
			
			$t->set_var("jumlah_do", $i-1);		
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

$t->pparse("output", "handle");

$objek->footer();
?>
