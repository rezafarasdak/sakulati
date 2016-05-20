<?
# Inisialisasi
$objek->init();

$q = buatKoneksiDB();
$appName = "Edit Invoice - Delete Delivery Order";
$objek->setTitle($appName);

# Isi halaman
$t = buatTemplate();

$t->set_file("handle", "delete_barang.html");
$t->set_block("handle", "ada", "hdl_ada");
$t->set_block("handle", "tidakada", "hdl_tidakada");

$t->set_var("hdl_tidakada", "");
$t->set_var("hdl_ada", "");
$t->set_var("appid", $_GET['appid']);
$t->set_var("appName", $appName);
$objek->debugLog("Opening ".$appName);

$id = $objek->dec($_GET['id']);
if (isset($id) && ($id != "")) {
	$sql = "select * from `delivery_order` where id = '".$id."' limit 1";
	$objek->debugLog("Edit Invoice - Delete Delivery Order [".$sql."]");
	$rs = $q->Execute($sql);
	if ($rs and !$rs->EOF) {
		$t->set_var("MESSAGE", "Edit Data");
		while(!$rs->EOF) {
			$t->set_var("id", $objek->enc($rs->fields['id']));
			$t->set_var("delivery_order_id", $rs->fields['id']);

			$do_no = $rs->fields['do_no'];		
			$t->set_var("do_no", $do_no);
			$t->set_var("do_no_format", $objek->deliveryOrderFormat($do_no));
			$t->set_var("invoice_no", $objek->invoiceFormat($q->GetOne("select in_no from invoice where id in (select invoice_id from invoice_detail where do_id = ".$rs->fields['id'].")")));
			$t->set_var("invoice_id", $q->GetOne("select id from invoice where id in (select invoice_id from invoice_detail where do_id = ".$rs->fields['id'].")"));
			
			$t->set_var("deleteButton", '<button class="btn btn-danger" type="submit">DELETE</button>');
			
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
$invoice_id = $_POST['invoice_id'];
$act = $_POST['act'];
if($act == "delete"){
	$sqlDelete = "delete from `invoice_detail` where do_id = '".$id_delete."'";
	$objek->debugLog("Deleting... ".$sqlDelete);
	$rs2 = $q->Execute($sqlDelete);
	if($rs2){
		$objek->userLog('Delete Invoice Detail '.$id_delete);
		$objek->UnPostJurnalByInvDO($invoice_id, $id_delete);
		header("location:index.php?appid=delivery_order&sub=edit&id=".$objek->enc($delivery_order_id)."&m=Delete Success");	
	}
}

$t->pparse("output", "handle");

$objek->footer();
?>
