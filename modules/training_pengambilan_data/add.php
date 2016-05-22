<?
# Inisialisasi
$objek->init();
$objek->setTitle('Form Tambah Data');
$appName = "Training";

$q = buatKoneksiDB();

# Isi halaman
$t = buatTemplate();

$t->set_var("appid", $_GET['appid']);
$t->set_var("appName", $appName);

$user = $objek->user->profil;

$t->set_file("handle", "add.html");

$id_kondisi_daun = 1;
if ($rs = $q->Execute('select * from kondisi_daun order by id')) {
	$option = '';
	while (!$rs->EOF) {
		if ($rs->fields['id'] == $id_kondisi_daun) $selected = 'selected';
		else $selected = '';
		$option .= '<option value="'.$rs->fields['id'].'"'.$selected.'>['.$rs->fields['code'].'] '.$rs->fields['name']."</option>\n";
		$rs->MoveNext();
	}
}
$t->set_var('kondisi_daun', $option);

$id_pohon = $objek->dec($_GET['id']);
$t->set_var("unique_code", $q->GetOne("select unique_code from pohon where id = ".$id_pohon));
$t->set_var("kordinat", $q->GetOne("select latitude_longtitude from pohon where id = ".$id_pohon));
$t->set_var("jenis_klon", $q->GetOne("select jk.name from jenis_klon jk join pohon p on jk.id = p.id_jenis_klon where p.id = ".$id_pohon));
$t->set_var("umur_pohon", $q->GetOne("select umur_pohon from pohon where id = ".$id_pohon));



$act = $_POST['act'];
if($act == "add"){
	$id = $_POST['id'];
	$unique_code = $_POST['unique_code'];
	$umur_pohon = $_POST['umur_pohon'];
	$id_jenisklon = $_POST['id_jenisklon'];
	$id_lahan = $_POST['id_lahan'];
	$status = $_POST['status'];
	$latitude_longtitude = $_POST['latitude_longtitude'];
	
	$sqlInsert = "insert into pohon (unique_code,latitude_longtitude,umur_pohon,id_jenis_klon,id_lahan) values ('".$unique_code."','".$latitude_longtitude."','".$umur_pohon."','".$id_jenisklon."','".$id_lahan."')";
	$objek->userLog('Add '.$appName.' ['.$unique_code.'] Success');

	$rs = $q->Execute($sqlInsert);
	if($rs){
		$objek->debugLog("Add ".$appName." Success, ".$unique_code);
		header("location:index.php?appid=".$_GET['appid']."&m=Add Success&mt=success");	
	}else{
		$objek->debugLog("Add ".$appName." Fail, Query : ".$sqlInsert);	
		$objek->nextmessage("Add ".$appName." Fail, Query : ".$sqlInsert, "danger");
	}
}

$t->pparse("output", "handle");

$objek->footer();
?>
