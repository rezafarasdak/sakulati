<?
# Inisialisasi
$objek->init();
$appName = "Edit Invoice";
$objek->setTitle($appName);

$q = buatKoneksiDB();

# Isi halaman
$t = buatTemplate();

$t->set_file("handle", "edit.html");
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
$objek->debugLog("Opening Edit Form");

// GET ORDER NUMBER
$id = $objek->dec($_GET['id']);


if (isset($id) && ($id != "")) {

	// ReCount Invoice Amount
	$objek->debugLog("Hitung ulang amount invoice");
	$objek->reCountInvoiceAmount($id);

	$sql = "select inv.*, so.so_no, so.currency, so.lokasi_id, so.customer_po, so.customer_date, so.customer_term_of_payment, c.nama as customerName, c.nama_persh as customerPershName 
			from invoice inv 
			join sales_order so on inv.sales_order_id = so.id 
			join customer c on c.id = so.customer_id
			where inv.id = ".$id." limit 1";
			
	$objek->debugLog($sql);
	$rs = $q->Execute($sql);
	if ($rs and !$rs->EOF) {
		$t->set_var("MESSAGE", "Edit Data");
		while(!$rs->EOF) {
			$t->set_var("id", $objek->enc($rs->fields['id']));

			$do_no = $rs->fields['do_no'];
			$t->set_var("do_no", $do_no);
			$t->set_var("do_no_format", $objek->deliveryOrderFormat($do_no));

			$so_no = $rs->fields['so_no'];
			$t->set_var("so_no", $so_no);
			$t->set_var("so_no_format", $objek->salesOrderFormat($so_no));

			$t->set_var("in_no_format", $objek->invoiceFormat($rs->fields['in_no']));
			$t->set_var("date", $rs->fields['date']);
			$t->set_var("due_date", $rs->fields['due_date']);
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
			$t->set_var("discount", $objek->number_format_usd($rs->fields['discount']));
			$t->set_var("sub_total", $objek->number_format_usd($rs->fields['sub_total']));
			$t->set_var("tax", $objek->number_format_usd($rs->fields['tax']));
			$t->set_var("other_amount", $objek->number_format_usd($rs->fields['other_charge']));
			$t->set_var("currency", $rs->fields['currency']);
			$t->set_var("status", $arraySalesOrderStatus[$rs->fields['status']]);
			$t->set_var("faktur", $rs->fields['faktur']);

			if($rs->fields['dipungut'] == "yes"){
				$t->set_var("yesSeleted", 'selected="selected"');
				$t->set_var("noSeleted", '');
			}else{
				$t->set_var("yesSeleted", '');
				$t->set_var("noSeleted", 'selected="selected"');				
			}

			// If Currency = IDR, No Need to convert
			if($rs->fields['currency'] == "IDR"){
				$t->set_var("rate", "");
			}else{
				$idrAmount = $q->GetOne("select amount from rate where currency = '".$rs->fields['currency']."' and start_date <= '".$rs->fields['date']."' and valid_date >= '".$rs->fields['date']."' order by valid_date desc");
				$t->set_var("rate", "1 ".$rs->fields['currency']." = ".$objek->number_format_usd($idrAmount)." IDR");
			}

			$do_no = $rs->fields['do_no'];

			// Check is this the last delivery order or not, cannot edit for not last delivery order.
//			$nextDO = $q->GetOne("select in_on from invoice where delivery_order_id = ".$rs->fields['id']);
			$nextDO = null;		
			// CHECK If Already Delivered Before
			if(empty($nextDO)){
				$t->set_var("editable", "true");

			
/*				$sql2 = "select * 
						from delivery_order do 
						where do.sales_order_id = '".$rs->fields['sales_order_id']."'
						order by do.id";
*/
				$sql2 = "select * 
						from delivery_order do 
						where do.id in (select do_id from invoice_detail where invoice_id = ".$id.")
						order by do.id";
				
				$jumlahDO = $q->GetOne("select count(*) from delivery_order do where do.id in (select do_id from invoice_detail where invoice_id = ".$id.")");
				$objek->debugLog("Detail : ".$sql2);

				$rs2 = $q->Execute($sql2);
				if ($rs2 and !$rs2->EOF) {
					$i = 1;
					while(!$rs2->EOF) {
						$do_id = $objek->enc($rs2->fields['id']);
						$do_no = $rs2->fields['do_no'];
						$t->set_var("no", $i++);
						
						if($jumlahDO > 1){
							$t->set_var("removeDO", '<a href="index.php?appid=sales_order_for_invoice&sub=delete_item&id='.$do_id.'"><i class="glyphicon glyphicon-remove"></i></a>');
						}else{
							$t->set_var("removeDO", "Cannot Deleted");
						}
						$t->set_var("do_id", $do_id);
						$t->set_var("do_no", $do_no);
						$t->set_var("do_no_format", $objek->deliveryOrderFormat($do_no));
						$t->set_var("do_date", $objek->ubahFormatTanggalForReport($rs2->fields['date']));
						$do_amount = $objek->getAmountFromDeliveryOrder($rs2->fields['id']);
						$t->set_var("do_amount", $objek->number_format_usd($do_amount));
						$t->set_var("do_amount_no_format", $do_amount);
						$t->parse("hdl_sales_order_detail_ada", "sales_order_detail_ada", true);
						$rs2->MoveNext();
					}
				}
			}else{
				$t->set_var("editable", "false");
				$t->parse("hdl_sales_order_detail_tidakada", "sales_order_detail_tidakada");			
			}
			
			$t->set_var("jumlah_barang", $i-1);		
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
