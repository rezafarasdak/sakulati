<?
# Inisialisasi
$objek->init();
$objek->setTitle('Form Tambah Karyawan');
$appName = "Karyawan";

$q = buatKoneksiDB();

# Isi halaman
$t = buatTemplate();

$t->set_var("appid", $_GET['appid']);
$t->set_var("appName", $appName);

$user = $objek->user->profil;
$storeId = $user['store_id'];

$t->set_file("handle", "add.html");

$id_karyawan_divisi = $_GET['id_karyawan_divisi'];
if ($rs = $q->Execute('select * from karyawan_divisi order by name')) {
	$option = '';
	while (!$rs->EOF) {
		if ($rs->fields['id'] == $id_karyawan_divisi) $selected = 'selected';
		else $selected = '';
		$option .= '<option value="'.$rs->fields['id'].'"'.$selected.'>'.$rs->fields['name']."</option>\n";
		$rs->MoveNext();
	}
}
$t->set_var('karyawan_divisi', $option);

//$id_lokasi = $_GET['id_lokasi'];
$id_lokasi = 1;
if ($rs = $q->Execute('select * from lokasi order by name')) {
	$option = '';
	while (!$rs->EOF) {
		if ($rs->fields['id'] == $id_lokasi) $selected = 'selected';
		else $selected = '';
		$option .= '<option value="'.$rs->fields['id'].'"'.$selected.'>'.$rs->fields['name']."</option>\n";
		$rs->MoveNext();
	}
}
$t->set_var('lokasi', $option);

$act = $_POST['act'];
if($act == "add"){
	$name = $_POST['name'];
	$email = $_POST['email'];
	$id_karyawan_divisi = $_POST['id_karyawan_divisi'];
	$id_lokasi = $_POST['id_lokasi'];

	$sqlInsert = "insert into karyawan (name,email,karyawan_divisi_id,lokasi_id) values ('".$name."','".$email."','".$id_karyawan_divisi."','".$id_lokasi."')";
	$objek->userLog('Add '.$appName.' ['.$nama.'] Success');

	$rs = $q->Execute($sqlInsert);
	if($rs){
		$objek->debugLog("Add ".$appName." Success, ".$nama);	
		header("location:index.php?appid=".$_GET['appid']."&m=Add Success&mt=success");	
	}else{
		$objek->debugLog("Add ".$appName." Fail, Query : ".$sqlInsert);	
		$objek->nextmessage("Add ".$appName." Fail, Query : ".$sqlInsert, "danger");
	}
}

$t->pparse("output", "handle");

$objek->footer();
?>
