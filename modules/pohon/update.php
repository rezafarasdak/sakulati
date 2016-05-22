<?
$objek->init();
$appName = "Pohon";
$objek->setTitle($appName);

$q = buatKoneksiDB();

$user = $objek->user->profil;
$storeId = $user['store_id'];

$id = $objek->dec($_POST['id']);
$unique_code = $_POST['unique_code'];
$umur_pohon = $_POST['umur_pohon'];
$id_jenis_klon = $_POST['id_jenisklon'];
$id_lahan = $_POST['id_lahan'];
$status = $_POST['status'];
$latitude_longtitude = $_POST['latitude_longtitude'];

$objek->debugLog("Updating..");

if (isset($id) && ($id != "")) {
	$sql = "update pohon set unique_code = '".$unique_code."', umur_pohon = '".$umur_pohon."', id_jenis_klon = '".$id_jenis_klon."', id_lahan = '".$id_lahan."', latitude_longtitude = '".$latitude_longtitude."' where id = ".$id;
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