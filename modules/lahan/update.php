<?
$objek->init();
$appName = "Management Lahan";
$objek->setTitle($appName);

$q = buatKoneksiDB();

$user = $objek->user->profil;
$storeId = $user['store_id'];

$id = $objek->dec($_POST['id']);
$name = $_POST['name'];
$remark = $_POST['remark'];
$objek->debugLog("Updating..");

if (isset($id) && ($id != "")) {
	$sql = "update lahan set name = '".$name."', remark = '".$remark."' where id = ".$id;
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