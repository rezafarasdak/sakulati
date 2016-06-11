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

if(!$objek->isAdmin()){
	header("location:index.php?appid=".$_GET['appid']."&m=Permision Denied");
}

$t->set_file("handle", "add.html");

$id_lahan = $_GET['id_lahan'];
if ($rs = $q->Execute('select * from lahan where type = "M" order by name')) {
	$option = '';
	while (!$rs->EOF) {
		if ($rs->fields['id'] == $id_lahan) $selected = 'selected';
		else $selected = '';
		$option .= '<option value="'.$rs->fields['id'].'"'.$selected.'>'.$rs->fields['name']."</option>\n";
		$rs->MoveNext();
	}
}
$t->set_var('lahan_utama', $option);


$id_jenis_pertanian = 1;
if ($rs = $q->Execute('select * from jenis_pertanian order by name')) {
	$option = '';
	while (!$rs->EOF) {
		if ($rs->fields['id'] == $id_jenis_pertanian) $selected = 'selected';
		else $selected = '';
		$option .= '<option value="'.$rs->fields['id'].'"'.$selected.'>'.$rs->fields['name']."</option>\n";
		$rs->MoveNext();
	}
}
$t->set_var('jenis_pertanian', $option);



$act = $_POST['act'];
if($act == "add"){
	$id = $_POST['id'];
	$name = $_POST['name'];
	$luas = $_POST['luas'];
	$id_jenispertanian = $_POST['id_jenispertanian'];
	$id_lahanutama = $_POST['id_lahanutama'];
	$status = $_POST['status'];
	$latitude_longtitude = $_POST['latitude_longtitude'];
	$jumlah_pohon = $_POST['jumlah_pohon'];
	
	$sqlInsert = "insert into lahan (name,latitude_longtitude,luas,id_jenispertanian,type,status,id_lahanutama,jumlah_pohon) values ('".$name."','".$latitude_longtitude."','".$luas."','".$id_jenispertanian."','C','".$status."','".$id_lahanutama."', '".$jumlah_pohon."')";
	$objek->userLog('Add '.$appName.' ['.$name.'] Success');

	$rs = $q->Execute($sqlInsert);
	if($rs){
		$objek->reCountLuasLahan($id_lahanutama);
		$objek->debugLog("Add ".$appName." Success, ".$name);
		header("location:index.php?appid=".$_GET['appid']."&m=Add Success&mt=success");	
	}else{
		$objek->debugLog("Add ".$appName." Fail, Query : ".$sqlInsert);	
		$objek->nextmessage("Add ".$appName." Fail, Query : ".$sqlInsert, "danger");
	}
}

$t->pparse("output", "handle");

$objek->footer();
?>
