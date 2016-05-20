<?
# Inisialisasi
$objek->init();

$q = buatKoneksiDB();
$q2 = buatKoneksiDB();

# Isi halaman
$t = buatTemplate();

$t->set_file("handle", "add.html");

$uid = $objek->userLogonId();
$username = $q2->GetOne("select fullname from user where user_id = ".$uid);

// Get Group User
$groupId = $q2->GetOne("select group_id from user_group where user_id = ".$uid);

// If Group ID = 1 (Admin), tampilkan CheckBox untuk mengirim ke semua user
if($groupId == 1){
	$t->set_var('public', 'Atau <input type="checkbox" name="public" value="public" onclick="publicStatus()"/> Kirim Ke Semua User');
}else{
	$t->set_var('public', '');	
}

$t->set_var('pengirim', $username);

// Menu Combobox Tujuan
$id_cari_user = $_GET['id_cari_user'];
if ($rs = $q2->Execute('select * from user where user_id <> '.$uid.' order by fullname')) {
	$option = '';
	while (!$rs->EOF) {
		if ($rs->fields['user_id'] == $id_cari_user) $selected = 'selected';
		else $selected = '';
		$option .= '<option value="'.$rs->fields['user_id'].'"'.$selected.'>'.$rs->fields['fullname']."</option>\n";
		$rs->MoveNext();
	}
}
$t->set_var('menu_penerima', $option);

$abc = $_POST['public'];

$act = $_POST['act'];
if($act == "add"){

	$ticket_number = $objek->getTicketNumber();

	$objek->userLog('Add Open Ticket : '.$ticket_number);

	$id_penerima = $_POST['id_penerima']; 
	$id_type = $_POST['id_type']; 
	$public = $_POST['public'];
	if($public == 'public'){
		$public = 1;
	}else{
		$public = 0;	
	}
	
	$subject = str_replace("'", "",$_POST["subject"]); 
	$content = str_replace("'", "",$_POST["content"]); 
	
	$sqlInsert = "insert into open_ticket (ticket_number,sender_user_id,receiver_user_id,subject,content,status,type,date,public_status) values (".$ticket_number.",'".$uid."','".$id_penerima."','".$subject."','".$content."','o','".$id_type."',now(),".$public.")";
	
	$objek->debugLog("Insert : ".$sqlInsert);
	
	$rs = $q2->Execute($sqlInsert);
	if($rs){

		header("location:index.php?appid=openticket&m=Add Success, Ticket Number : ".$ticket_number." - ".$abc);
	}
}

$t->pparse("output", "handle");

$objek->footer();
?>
