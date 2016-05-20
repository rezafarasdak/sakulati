<?
# Inisialisasi
$objek->init();
$objek->setTitle('Form Tambah');
$appName = "Management Lahan";

$q = buatKoneksiDB();

# Isi halaman
$t = buatTemplate();

$t->set_var("appid", $_GET['appid']);
$t->set_var("appName", $appName);

$user = $objek->user->profil;
$storeId = $user['store_id'];

$t->set_file("handle", "add.html");

$act = $_POST['act'];
if($act == "add"){
	$id = $_POST['id'];
	$name = $_POST['name'];
	$remark = $_POST['remark'];

	$sqlInsert = "insert into lahan (name,remark) values ('".$name."','".$remark."')";
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
