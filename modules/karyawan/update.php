<?
$objek->init();
$appName = "Karyawan";
$objek->setTitle($appName);

$q = buatKoneksiDB();

$user = $objek->user->profil;
$storeId = $user['store_id'];

$id = $objek->dec($_POST['id']);
$name = $_POST['name'];
$email = $_POST['email'];
$id_karyawan_divisi = $_POST['id_karyawan_divisi'];
$id_lokasi = $_POST['id_lokasi'];
$objek->debugLog("Updating..");

if (isset($id) && ($id != "")) {
	$sql = "update karyawan set name = '".$name."', email = '".$email."', karyawan_divisi_id = '".$id_karyawan_divisi."', lokasi_id = '".$id_lokasi."' where id = ".$id;
	$objek->debugLog("Query [".$sql."]");

	$rs=$q->Execute($sql);
	if ($rs) 
	{	
		$objek->userLog('Update '.$appName."-".$id);
		header("location:index.php?appid=".$_GET['appid']."&m=Edit Success&mt=success");
	} else {
		$objek->debugLog("Update ".$appName." Fail, Error Code 1001 ");
		header("location:index.php?appid=".$_GET['appid']."&m=Edit Failure, Error Code 1001&mt=danger");
	}

}else{
	// Error ID Kosong
}
//echo "$sql";
$objek->footer();
?>