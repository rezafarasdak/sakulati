<?
# Inisialisasi
$objek->init();
$appName = "Edit Barang Delivery Order";
$objek->setTitle($appName);

$q = buatKoneksiDB();

# Isi halaman
$t = buatTemplate();

$t->set_file("handle_edit", "edit_barang.html");
$t->set_block("handle_edit", "ada", "hdl_ada");
$t->set_block("handle_edit", "tidakada", "hdl_tidakada");
$t->set_var("hdl_tidakada", "");
$t->set_var("hdl_ada", "");
$t->set_var("appid", $_GET['appid']);
$t->set_var("appName", $appName);
$objek->debugLog("Opening ".$appName);

$id = $objek->dec($_GET['id']);
if (isset($id) && ($id != "")) {
	$sql = "select do.*, b.name 
					from delivery_order_detail do 
					join barang_sales b on do.barang_sales_id = b.id 
					where do.id = '".$id."' limit 1";
	$objek->debugLog($sql);
	$rs = $q->Execute($sql);
	if ($rs and !$rs->EOF) {
		$t->set_var("MESSAGE", "Edit Data");
		while(!$rs->EOF) {
			$t->set_var("id", $objek->enc($rs->fields['id']));
			$do_no = $q->GetOne("select do_no from delivery_order where id = ".$rs->fields['delivery_order_id']);
			$do_no_format = substr($do_no,0,5)."/".substr($do_no,5,6)."/".substr($do_no,11,2)."/".substr($do_no,13,2);
			$t->set_var("do_no", $do_no);
			$t->set_var("do_no_format", $do_no_format);
			
			$t->set_var("nama_barang", $rs->fields['name']);
			$t->set_var("sisa_kirim", number_format($rs->fields['sisa_kirim'],0,',','.'));
			$t->set_var("quantity", number_format($rs->fields['quantity'],0,',','.'));
			$sisaKirim = $rs->fields['sisa_kirim'];
			$terkirim = $rs->fields['quantity'] - $rs->fields['dikirim'] - $rs->fields['sisa_kirim'];
			$t->set_var("terkirim", number_format($terkirim,0,',','.'));
			$t->set_var("dikirim", $rs->fields['dikirim']);
			$t->set_var("maxDikirim", $rs->fields['dikirim']+$rs->fields['sisa_kirim']);
			$t->set_var("sisa_kirim", number_format($sisaKirim,0,',','.'));					
		
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


$t->pparse("output", "handle_edit");

$objek->footer();
?>
