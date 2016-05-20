<?
# Inisialisasi
$objek->init();
$appName = "Cart Of Account - 4";
$objek->setTitle('Delete '.$appName);

$q = buatKoneksiDB();

# Isi halaman
$t = buatTemplate();

$t->set_file("handle_edit", "delete.html");
$t->set_block("handle_edit", "ada", "hdl_ada");
$t->set_block("handle_edit", "tidakada", "hdl_tidakada");
$t->set_var("hdl_tidakada", "");
$t->set_var("hdl_ada", "");
$t->set_var("appid", $_GET['appid']);
$t->set_var("appName", "Cart Of Account - 4");
$objek->debugLog("Opening Delete Form");

$user = $objek->user->profil;
$storeId = $user['store_id'];

$id = $objek->dec($_GET['id']);
if (isset($id) && ($id != "")) {
	$sql = "select * from cart_of_account_middle where id = ".$id." limit 1";
	$rs = $q->Execute($sql);
	if ($rs and !$rs->EOF) {
		$t->set_var("MESSAGE", "Edit Data");
		while(!$rs->EOF) {
			$t->set_var("id", $objek->enc($rs->fields['id']));
			$t->set_var("idDec", $rs->fields['id']);
			$t->set_var("name", $rs->fields['name']);
			
			if($rs->fields['type_saldo'] == "D"){
				$type_saldo = "Debit";
			}else if($rs->fields['type_saldo'] == "K"){
				$type_saldo = "Kredit";
			}
			
			$t->set_var("type_saldo", $type_saldo);
			$t->set_var("remark", $rs->fields['remark']);
			
			$t->parse("hdl_ada", "ada", true);
			$rs->MoveNext();
		}
	}else{
		 $objek->debugLog("Page Delete Barang Dengan ID ".$id." Tidak Ada, Error Code 1001");
		 $t->parse("hdl_tidakada", "tidakada");
	 	 $t->set_var("MESSAGE", "Data Dengan ID : ".$id." Tidak Di Temukan, Error Code 1001");
	}
}else{
	if(empty($_POST['act'])){
		$objek->debugLog("Page Delete Barang Dengan ID ".$id." Tidak Ada, Error Code 1002");
		$t->parse("hdl_tidakada", "tidakada");
		$t->set_var("MESSAGE", "Data Dengan ID : ".$id." Tidak Di Temukan, Error Code 1002");
	}
}


$id_delete = $objek->dec($_POST['id_delete']);
$act = $_POST['act'];
if($act == "delete"){
	$objek->debugLog("Opening Delete Form - Starting Delete...");
	$sqlDelete = "delete from cart_of_account_middle where id = ".$id_delete;
	$objek->debugLog("Query [".$sqlDelete."]");
	$rs2 = $q->Execute($sqlDelete);
	if($rs2){
		$objek->debugLog("Opening Delete Form - Delete Success");
		$objek->userLog('Delete Barang '.$id_delete);
		header("location:index.php?appid=".$_GET['appid']."&m=Delete Success&mt=success");	
	}else{
		$objek->debugLog("Opening Delete Form - Starting Failure...");
	 	 $objek->nextmessage("Data Dengan ID : ".$id." Tidak Di Temukan, Error Code 1001");

	}
}

$t->pparse("output", "handle_edit");

$objek->footer();
?>
