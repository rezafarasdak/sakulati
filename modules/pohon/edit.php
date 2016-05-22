<?
# Inisialisasi
$objek->init();
$appName = "Cluster";
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
$t->set_var("appName", "Cluster");
$objek->debugLog("Opening Edit Form");

$user = $objek->user->profil;
$storeId = $user['store_id'];

$id = $objek->dec($_GET['id']);
if (isset($id) && ($id != "")) {
	$sql = "select * from pohon where id = ".$id."";
	$objek->debugLog("Query [".$sql."]");
	$rs = $q->Execute($sql);
	if ($rs and !$rs->EOF) {
		$t->set_var("MESSAGE", "Edit Data");
		while(!$rs->EOF) {
			$t->set_var("id", $objek->enc($rs->fields['id']));
			$t->set_var("unique_code", $rs->fields['unique_code']);
			$t->set_var("latitude_longtitude", $rs->fields['latitude_longtitude']);
			$t->set_var("umur_pohon", $rs->fields['umur_pohon']);
		
			$id_lahan = $rs->fields['id_lahan'];
			if ($rs2 = $q->Execute('select * from lahan where type = "C" and status = 1 order by name')) {
				$option = '';
				while (!$rs2->EOF) {
					if ($rs2->fields['id'] == $id_lahan) $selected = 'selected';
					else $selected = '';
					$option .= '<option value="'.$rs2->fields['id'].'"'.$selected.'>'.$rs2->fields['name']."</option>\n";
					$rs2->MoveNext();
				}
			}
			$t->set_var('lahan', $option);
						
			$id_jenis_klon = $rs->fields['id_jenis_klon'];
			if ($rs3 = $q->Execute('select * from jenis_klon order by name')) {
				$option = '';
				while (!$rs3->EOF) {
					if ($rs3->fields['id'] == $id_jenis_klon) $selected = 'selected';
					else $selected = '';
					$option .= '<option value="'.$rs3->fields['id'].'"'.$selected.'>'.$rs3->fields['name']."</option>\n";
					$rs3->MoveNext();
				}
			}
			$t->set_var('jenis_klon', $option);

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
