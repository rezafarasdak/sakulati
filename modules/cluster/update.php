<?
$objek->init();
$appName = "Cluster";
$objek->setTitle($appName);

$q = buatKoneksiDB();

$user = $objek->user->profil;
$storeId = $user['store_id'];

$id = $objek->dec($_POST['id']);
$name = $_POST['name'];
$luas = $_POST['luas'];
$id_jenispertanian = $_POST['id_jenispertanian'];
$id_lahanutama = $_POST['id_lahanutama'];
$status = $_POST['status'];
$latitude_longtitude = $_POST['latitude_longtitude'];
$jumlah_pohon = $_POST['jumlah_pohon'];

$objek->debugLog("Updating..");

if (isset($id) && ($id != "")) {
	$sql = "update lahan set name = '".$name."', luas = '".$luas."', id_jenispertanian = '".$id_jenispertanian."', status = '".$status."', id_lahanutama = '".$id_lahanutama."', latitude_longtitude = '".$latitude_longtitude."', jumlah_pohon = '".$jumlah_pohon."'  where id = ".$id;
	$objek->debugLog("Query [".$sql."]");

	$rs=$q->Execute($sql);
	if ($rs) 
	{	
		$objek->reCountLuasLahan($id_lahanutama);
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