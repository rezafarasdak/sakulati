<?
# Inisialisasi
$objek->init();
$objek->setTitle('Form Tambah Data');
$appName = "Training Pengambilan Data";

$q = buatKoneksiDB();

# Isi halaman
$t = buatTemplate();

$t->set_var("appid", $_GET['appid']);
$t->set_var("appName", $appName);

$id_pohon = $objek->dec($_GET['id']);

$user = $objek->user->profil;
if(!$objek->isAdmin()){
	$sql = "select count(*) from pohon where id_lahan in (select id_lahan from lahan_role where id_user = ".$user[userid].") and id = ".$id_pohon;
	$objek->debugLog($sql);
	$allowedItem = $q->GetOne($sql);
	if($allowedItem < 1){
		header("location:index.php?appid=".$_GET['appid']."&m=Permision Denied");	
	}
}

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


$t->set_var("unique_code", $q->GetOne("select unique_code from pohon where id = ".$id_pohon));
$t->set_var("kordinat", $q->GetOne("select latitude_longtitude from pohon where id = ".$id_pohon));
$t->set_var("jenis_klon", $q->GetOne("select jk.name from jenis_klon jk join pohon p on jk.id = p.id_jenis_klon where p.id = ".$id_pohon));
$t->set_var("umur_pohon", $q->GetOne("select umur_pohon from pohon where id = ".$id_pohon));
$t->set_var('id_pohon', $_GET['id']);



$act = $_POST['act'];
if($act == "add"){
	$id_pohon = $objek->dec($_POST['id_pohon']);
	$id_daun_muda = $_POST['id_daun_muda'];
	$id_daun_tua = $_POST['id_daun_tua'];
	$date = $_POST['date'];
	$bunga = $_POST['bunga'];
	$buah_kecil = $_POST['buah_kecil'];
	$buah_dewasa = $_POST['buah_dewasa'];
	$buah_siap_panen = $_POST['buah_siap_panen'];

	$ph = $_POST['ph'];
	$bo = $_POST['bo'];
	$ktk = $_POST['ktk'];

	$pytoptora = $_POST['pytoptora'];
	$sehat = $_POST['sehat'];
	$vsd = $_POST['vsd'];
	$pbk = $_POST['pbk'];

	$sqlInsert = "insert into objek (id_pohon,date,daun_tua,daun_muda,bunga,buah_kecil,buah_dewasa,buah_siap_panen,PH,BO,KTK,sehat_status,pytoptora,pbk,vsd_status) values ('".$id_pohon."','".$date."','".$id_daun_tua."','".$id_daun_muda."','".$bunga."','".$buah_kecil."','".$buah_dewasa."','".$buah_siap_panen."','".$ph."','".$bo."','".$ktk."','".$sehat."','".$pytoptora."','".$pbk."','".$vsd."')";
	$objek->userLog('Add '.$appName.' ['.$id_pohon.'] Success');
	$objek->debugLog("Add ".$appName.", Query : ".$sqlInsert);	

	$rs = $q->Execute($sqlInsert);
	if($rs){
		$objek->debugLog("Add ".$appName." Success, ".$id_pohon);
		header("location:index.php?appid=".$_GET['appid']."&m=Add Success&mt=success");	
	}else{
		$objek->debugLog("Add ".$appName." Fail, Query : ".$sqlInsert);	
		$objek->nextmessage("Add ".$appName." Fail, Query : ".$sqlInsert, "danger");
	}
}

$t->pparse("output", "handle");

$objek->footer();
?>
