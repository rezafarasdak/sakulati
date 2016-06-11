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

if($objek->isAdmin()){
	$whereLahan = "";
}else{
	$whereLahan = " and id in (select id_lahan from lahan_role where id_user = ".$user[userid].")";
}

$id = $objek->dec($_GET['id']);
if (isset($id) && ($id != "")) {
	$sql = "select * from lahan where id = '".$id."' ".$whereLahan." limit 1";
	$objek->debugLog("Query [".$sql."]");
	$rs = $q->Execute($sql);
	if ($rs and !$rs->EOF) {
		$t->set_var("MESSAGE", "Edit Data");
		while(!$rs->EOF) {
			$t->set_var("id", $objek->enc($rs->fields['id']));
			$t->set_var("name", $rs->fields['name']);
			$t->set_var("lahanutama", $rs->fields['lahanutama']);
			$t->set_var("jenispertanian", $rs->fields['jenispertanian']);
			$t->set_var("latitude_longtitude", $rs->fields['latitude_longtitude']);
			$t->set_var("luas", $rs->fields['luas']);
			$t->set_var("status", $arrayStatus[$rs->fields['status']]);
			$t->set_var("last_panen", $rs->fields['terakhir_panen']);
			$t->set_var("jumlah_pohon", $rs->fields['jumlah_pohon']);
		
			$id_lahan = $rs->fields['id_lahanutama'];
			if ($rs2 = $q->Execute('select * from lahan where type = "M" order by name')) {
				$option = '';
				while (!$rs2->EOF) {
					if ($rs2->fields['id'] == $id_lahan) $selected = 'selected';
					else $selected = '';
					$option .= '<option value="'.$rs2->fields['id'].'"'.$selected.'>'.$rs2->fields['name']."</option>\n";
					$rs2->MoveNext();
				}
			}
			$t->set_var('lahan_utama', $option);
			
			$id_jenis_pertanian = $rs->fields['id_jenispertanian'];
			if ($rs3 = $q->Execute('select * from jenis_pertanian order by name')) {
				$option = '';
				while (!$rs3->EOF) {
					if ($rs3->fields['id'] == $id_jenis_pertanian) $selected = 'selected';
					else $selected = '';
					$option .= '<option value="'.$rs3->fields['id'].'"'.$selected.'>'.$rs3->fields['name']."</option>\n";
					$rs3->MoveNext();
				}
			}
			$t->set_var('jenis_pertanian', $option);

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
