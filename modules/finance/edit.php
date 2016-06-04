<?
# Inisialisasi
$objek->init();
$appName = "Management Lahan";
$objek->setTitle('Edit '.$appName);

$q = buatKoneksiDB();

# Isi halaman
$t = buatTemplate();

$t->set_file("handle_edit", "edit.html");
$t->set_block("handle_edit", "ada", "hdl_ada");
$t->set_block("handle_edit", "tidakada", "hdl_tidakada");
$t->set_var("hdl_tidakada", "");
$t->set_var("hdl_ada", "");
$t->set_var("appid", $_GET['appid']);
$t->set_var("appName", $appName);
$objek->debugLog("Opening Edit Form");

$user = $objek->user->profil;
$storeId = $user['store_id'];

$id = $objek->dec($_GET['id']);
if (isset($id) && ($id != "")) {
	$sql = "select * from lahan where id = '".$id."' limit 1";
	$objek->debugLog("Query [".$sql."]");
	$rs = $q->Execute($sql);
	if ($rs and !$rs->EOF) {
		$t->set_var("MESSAGE", "Edit Data");
		while(!$rs->EOF) {
			$t->set_var("id", $objek->enc($rs->fields['id']));
			$t->set_var("name", $rs->fields['name']);
			$t->set_var("latitude_longtitude", $rs->fields['latitude_longtitude']);
			$t->set_var("status", $rs->fields['status']);
			
			if($rs->fields['status'] == 1){
				$t->set_var("ActiveSelected", "selected");				
				$t->set_var("NotActiveSelected", "");				
			}else{
				$t->set_var("ActiveSelected", "");				
				$t->set_var("NotActiveSelected", "selected");								
			}
			
			$t->parse("hdl_ada", "ada", true);
			$rs->MoveNext();
		}
	}else{
		 $objek->debugLog("Edit ".$appName." Dengan ID ".$id." Tidak Ada, Error Code 1001");
		 $t->parse("hdl_tidakada", "tidakada");
	 	 $objek->nextmessage("Data Dengan ID : ".$id." Tidak Di Temukan, Error Code 1001");
	}
}else{
	$objek->debugLog("Edit ".$appName." Dengan ID ".$id." Tidak Ada, Error Code 1002");
	$t->parse("hdl_tidakada", "tidakada");
	$objek->nextmessage("Data Dengan ID : ".$id." Tidak Di Temukan, Error Code 1002");
}

$t->pparse("output", "handle_edit");

$objek->footer();
?>
