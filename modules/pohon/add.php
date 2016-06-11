<?
# Inisialisasi
$objek->init();
$objek->setTitle('Form Tambah Cluster');
$appName = "Cluster";

$q = buatKoneksiDB();

# Isi halaman
$t = buatTemplate();

$t->set_var("appid", $_GET['appid']);
$t->set_var("appName", $appName);

$user = $objek->user->profil;
$storeId = $user['store_id'];

$t->set_file("handle", "add.html");

if($objek->isAdmin()){
	$whereLahan = "";
}else{
	$whereLahan = " and id in (select id_lahan from lahan_role where id_user = ".$user[userid].")";
}

$id_lahan = $_GET['id_lahan'];
if ($rs = $q->Execute('select * from lahan where type = "C" and status = 1 '.$whereLahan.' order by name')) {
	$option = '';
	while (!$rs->EOF) {
		if ($rs->fields['id'] == $id_lahan) $selected = 'selected';
		else $selected = '';
		$option .= '<option value="'.$rs->fields['id'].'"'.$selected.'>'.$rs->fields['name']."</option>\n";
		$rs->MoveNext();
	}
}
$t->set_var('lahan', $option);


$id_jenis_klon = 1;
if ($rs = $q->Execute('select * from jenis_klon order by name')) {
	$option = '';
	while (!$rs->EOF) {
		if ($rs->fields['id'] == $id_jenis_klon) $selected = 'selected';
		else $selected = '';
		$option .= '<option value="'.$rs->fields['id'].'"'.$selected.'>'.$rs->fields['name']."</option>\n";
		$rs->MoveNext();
	}
}
$t->set_var('jenis_klon', $option);

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
