<?
# Inisialisasi
$objek->init();
$appName = "Cart Of Account - 4";
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
$t->set_var("appName", "Cart Of Account - 4");
$objek->debugLog("Opening Edit Form");

$user = $objek->user->profil;
$storeId = $user['store_id'];

$id = $objek->dec($_GET['id']);
if (isset($id) && ($id != "")) {
	$sql = "select * from cart_of_account_middle where id = '".$id."' limit 1";
	$objek->debugLog("Query [".$sql."]");
	$rs = $q->Execute($sql);
	if ($rs and !$rs->EOF) {
		$t->set_var("MESSAGE", "Edit Data");
		while(!$rs->EOF) {
			$t->set_var("id", $objek->enc($rs->fields['id']));
			$t->set_var("idDec2", substr($rs->fields['id'],0,2));
			$t->set_var("idDec4", substr($rs->fields['id'],2,2));
			$t->set_var("name", $rs->fields['name']);
			$t->set_var("remark", $rs->fields['remark']);
		
			$id_cart_of_account_header = $rs->fields['cart_of_account_header_id'];
			$objek->debugLog("Header ".$rs->fields['cart_of_account_header_id']);
			if ($rs2 = $q->Execute('select * from cart_of_account_header order by name')) {
				$option = '';
				while (!$rs2->EOF) {
					if ($rs2->fields['id'] == $id_cart_of_account_header) $selected = 'selected';
					else $selected = '';
					$option .= '<option value="'.$rs2->fields['id'].'"'.$selected.'>['.$rs2->fields['id']."] ".$rs2->fields['name']."</option>\n";
					$rs2->MoveNext();
				}
			}
			$t->set_var('cart_of_account_header', $option);

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
