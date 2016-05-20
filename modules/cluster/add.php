<?
# Inisialisasi
$objek->init();
$objek->setTitle('Form Tambah Cart Of Account Middle');
$appName = "Cart Of Account - 4";

$q = buatKoneksiDB();

# Isi halaman
$t = buatTemplate();

$t->set_var("appid", $_GET['appid']);
$t->set_var("appName", $appName);

$user = $objek->user->profil;
$storeId = $user['store_id'];

$t->set_file("handle", "add.html");

$id_cart_of_account_header = $_GET['id_cart_of_account_header'];
if ($rs = $q->Execute('select * from cart_of_account_header order by id')) {
	$option = '';
	while (!$rs->EOF) {
		if ($rs->fields['id'] == $id_cart_of_account_header) $selected = 'selected';
		else $selected = '';
		$option .= '<option value="'.$rs->fields['id'].'"'.$selected.'>['.$rs->fields['id']."] ".$rs->fields['name']."</option>\n";
		$rs->MoveNext();
	}
}
$t->set_var('cart_of_account_header', $option);

$act = $_POST['act'];
if($act == "add"){
	$id = $_POST['id'];
	$name = $_POST['name'];
	$id_cart_of_account_header = $_POST['id_cart_of_account_header'];
	$remark = $_POST['remark'];
	
	$id = $id_cart_of_account_header.$id;

	$sqlInsert = "insert into cart_of_account_middle (id,name,cart_of_account_header_id,remark) values ('".$id."','".$name."','".$id_cart_of_account_header."','".$remark."')";
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
