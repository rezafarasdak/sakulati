<?
$objek->init();
$appName = "Manage Dashboard";
$objek->setTitle($appName);

$q = buatKoneksiDB();

$user = $objek->user->profil;
$storeId = $user['store_id'];

$id = $objek->dec($_GET['id']);

$type = $_GET['type'];	// Table
$show = $_GET['show'];	// 1 = Show, 0 = Not Show
$last = $_GET['last'];	// Last Module

if (isset($id) && ($id != "")) {
	$sql = "update ".$type." set show_on_dashboard = '".$show."' where id = ".$id;
	$objek->debugLog("Updating Dashboard [".$sql."]");

	$rs=$q->Execute($sql);
	if ($rs) 
	{	
		$objek->userLog('Updating Dashboard '.$type." ID : ".$id);
		header("location:index.php?appid=".$last."&sub=detail&id=".$_GET['id']."&m=Edit Success&mt=success");
	}else{
		$objek->debugLog("Update ".$appName." Fail, Error Code 1001 ");
		header("location:index.php?appid=".$last."&sub=detail&id=".$_GET['id']."&m=Edit Failure, Error Code 1001&mt=danger");		
	}

}else{
	$objek->debugLog("Update ".$appName." Fail, Error Code 1002 ");
	header("location:index.php?appid=".$last."&sub=detail&id=".$_GET['id']."&m=Edit Failure, Error Code 1002&mt=danger");
}
$objek->footer();
?>