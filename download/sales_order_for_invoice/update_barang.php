<?
$objek->init();

$q = buatKoneksiDB();

$id = $objek->dec($_POST['id']);
$quantity = $_POST['quantity'];
$terkirim = $_POST['terkirim'];
$barang_dikirim = $_POST['barang_dikirim'];
$sisa = $quantity - $terkirim - $barang_dikirim;

if (isset($id) && ($id != "")) {
	$sql = "update `delivery_order_detail` set dikirim = ".$barang_dikirim.", sisa_kirim = ".$sisa." where id = '".$id."'";
	$objek->debugLog("Updating delivery Order Detail [".$sql."]");

	$rs=$q->Execute($sql);
	if ($rs) 
	{	
		$objek->userLog('Update Delivery Order Detail on SO '.$id);
	
		$do_id = $q->GetOne("select delivery_order_id from delivery_order_detail where id = ".$id);		
		$so_id = $q->GetOne("select sales_order_id from delivery_order where id = ".$do_id);
		$otherPending = $q->GetOne("select count(*) from delivery_order_detail where sisa_kirim > 0 and delivery_order_id = ".$do_id);
		// Cek, Apabila sudah tuntas, Update surat_jalan_status
		if($otherPending == 0){
			$sqlUpdate = "update `sales_order` set status = 'D' where id = '".$so_id."'";
			$rs3=$q->Execute($sqlUpdate);
		}else{
			$sqlUpdate = "update `sales_order` set status = 'DP' where id = '".$so_id."'";
			$rs3=$q->Execute($sqlUpdate);
		}		

		header("location:index.php?appid=delivery_order&sub=edit&m=Edit Success&id=".$objek->enc($do_id));
	}else{
		$objek->debugLog("ERROR Edit Barang");
		header("location:index.php?appid=delivery_order&sub=edit_barang&m=Edit Failure, Please Check Parameter&id=".$id);		
	}

}else{
	$objek->debugLog("ERROR Edit Barang, ID Kosong");
}

$objek->footer();
?>