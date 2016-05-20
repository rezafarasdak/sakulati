<?
# Inisialisasi
$objek->init();

$q = buatKoneksiDB();

$t = buatTemplate();

//$objek->nextmessage("Untuk menghubungkan antara user dengan karyawan, Gunakan email yang sama di Data User & Karyawan",'info');

$store_id = $_SESSION['user']->profil['store_id'];
if(empty($store_id)){
	$store_id = 0;	
}

$q1 = $q;
$action = $_GET['action'];
$actionPost = $_POST['actionPost'];

$t->set_file("hdl_user", "user.html");
$t->set_block("hdl_user", "elemen", "hdl_elemen");
$t->set_block("hdl_user", "elemen_member", "hdl_elemen_member");
$t->set_block("hdl_user", "nonform", "hdl_nonform");
$t->set_block("hdl_user", "form", "hdl_form");
$t->set_block("hdl_user", "detail", "hdl_detail");
$t->set_var("hdl_elemen", "");
$t->set_var("hdl_elemen_member", "");
$t->set_var("hdl_elemen_group", "");
$t->set_var("hdl_nonform", "");
$t->set_var("hdl_form", "");
$t->set_var("hdl_detail", "");
$t->set_var("appid", $_GET['appid']);
$t->set_var("appName", $appName);
$objek->debugLog("Opening Index User");


// AFTER SUBMIT
switch($actionPost) {
    case "add" :
         $username =  trim($_POST['username']);
         $name =  trim($_POST['name']);
         $email =  trim($_POST['email']);
         $pass1 = trim($_POST['password1']);
         $pass2 = trim($_POST['password2']);
         $additional_info = trim($_POST['additional_info']);
         $lokasi_id =  trim($_POST['lokasi_id']);
         
         if (!isset($_POST['batal'])) {
             if ($username == ""){
				  $objek->errmessage("Username kosong");
				  $objek->debugLog("Add User Fail, Username Kosong");
			 }
             if ($pass1 != $pass2){
				  $objek->errmessage("Password tidak sama");
				  $objek->debugLog("Add User Fail, Password 1 & 2 Tidak Sama");
			 }
             $pass = $pass1;
             $rs=$q->Execute("insert into user ( username, userpassword, fullname, email, additional_info, lokasi_id) values ('$username', md5('$pass'), '$name', '$email', '$additional_info', '$lokasi_id')");
			 if($rs){
				$objek->nextMessage("Adding User Success");				 
				$objek->debugLog("Add User [".$username."] Success"); 
			 }
         }
         break;

    case "update" :
         $username =  trim($_POST['username']);
         $name =  trim($_POST['name']);
         $email =  trim($_POST['email']);
         $pass1 = trim($_POST['password1']);
         $pass2 = trim($_POST['password2']);
         $id =  $objek->dec($_POST['id']);
         $login_session_id = trim($_POST['login_session_id']);
         $additional_info = trim($_POST['additional_info']);
                  
         if (!isset($_GET['batal'])) {
            if ($username == "") $objek->errmessage("Username kosong");
            
			if ($pass1 != $pass2){
				  $objek->errmessage("Password tidak sama");
				  $objek->debugLog("Add User Fail, Password 1 & 2 Tidak Sama");
			 }
             $pass = $pass1;

             // check apakah session_id ini login dengan id yang sama.
			 $sqlUpdateUser ="update user set username = '$username', fullname = '$name', email = '$email' where  user_id = '$id'";
             $rs=$q->Execute($sqlUpdateUser);
			 if($rs){
				$objek->nextMessage("Updating User Success");
			 	$objek->debugLog("Updating user $name success");	 
			 }else{
				$objek->nextMessage("Updating User Fail, Please Try Again or Call Support");				 
				$objek->debugLog("Updating user fail [".$sqlUpdateUser."]");
			 }
			 
             if ($pass != ""){
				 $sqlUpdateUserPass = "update user set userpassword = md5('$pass') where  user_id = '$id'";
                 $rs=$q->Execute($sqlUpdateUserPass);
				 if($rs){
					$objek->nextMessage("Updating User Success");					 
					$objek->debugLog("Updating user password success");	 
				 }else{
					$objek->nextMessage("Updating User Fail, Please Try Again or Call Support");				 
					$objek->debugLog("Updating user password fail [".$sqlUpdateUserPass."]");
				 }
			 }
         }
        break;
    case "groupadd" :
         $id = $objek->dec($_POST['id']);
         $groupid = $objek->dec($_POST['groupid']);
		 $sqlUpdateUserGroup = "insert into user_group values ( '$id','$groupid')";
         $rs=$q->Execute($sqlUpdateUserGroup);
		 if($rs){
			$objek->nextMessage("Updating User Group Success");					 
			$objek->debugLog("Updating user group success");	 
		 }else{
			$objek->nextMessage("Updating User Group Fail, Please Try Again or Call Support");				 
			$objek->debugLog("Updating user group fail [".$sqlUpdateUserGroup."]");
		 }
         break;
}

// AFTER CLICK LINK
switch($action) {
    case "new" :
         $t->set_var("id", "");
         $t->set_var("username", "");
         $t->set_var("name", "");
         $t->set_var("email", "");
         $t->set_var("action", "add");
         $t->set_var("action title", "Tambah");         

		// Menu Combobox lokasi
		$id_cari_lokasi = "1";
		if ($rs = $q->Execute('select id,name,remark from lokasi order by id')) {
			$option = '';
			while (!$rs->EOF) {
				if ($rs->fields['id'] == $id_cari_lokasi) $selected = 'selected';
				else $selected = '';
				$option .= '<option value="'.$rs->fields['id'].'"'.$selected.'>'.$rs->fields['remark']."</option>\n";
				$rs->MoveNext();
			}
		}
		$t->set_var('menuLokasi', $option);
         
         $t->parse("hdl_form", "form");
         
         break;
    case "edit" :
         $id = $objek->dec($_GET['id']);
         $rs=$q->Execute("select * from user where  user_id = '$id'");
         if (!$rs->EOF) {
             $t->set_var("username", $rs->fields("username"));
             $t->set_var("name", $rs->fields("fullname"));
             $t->set_var("email", $rs->fields("email"));
             $t->set_var("id", $objek->enc($rs->fields("user_id")));
             $t->set_var("action", "update");
             $t->set_var("action title", "Edit");

			// Menu Combobox lokasi
			$id_cari_lokasi = $rs->fields("lokasi_id");
			if ($rs = $q->Execute('select id,name,remark from lokasi order by id')) {
				$option = '';
				while (!$rs->EOF) {
					if ($rs->fields['id'] == $id_cari_lokasi) $selected = 'selected';
					else $selected = '';
					$option .= '<option value="'.$rs->fields['id'].'"'.$selected.'>'.$rs->fields['remark']."</option>\n";
					$rs->MoveNext();
				}
			}
			$t->set_var('menuLokasi', $option);
			 
			 
             $t->parse("hdl_form", "form");
			 $rs->MoveNext();
         }
         break;
    case "del" :
         $id = $objek->dec($_GET['id']);
         $rs=$q->Execute("select username from user where is_superuser = 0 and user_id = '$id'");
         if (!in_array($rs->fields(0), array("admin"))) {
             $rs=$q->Execute("delete from user_group where  user_id = '$id'");
             $rs1=$q->Execute("delete from user where  user_id = '$id'");
         }
         else {
             $objek->errmessage("User ini tidak boleh dihapus");
         }
         break;
    case "groupdel" :
         $id = $objek->dec($_GET['id']);
         $groupid = $objek->dec($_GET['groupid']);
		 $sqlDelUserGroup = "delete from user_group where group_id = '$groupid' and user_id = '$id' ";
         $rs=$q->Execute($sqlDelUserGroup);
		 if($rs){
			$objek->nextMessage("Delete User Group Success");					 
			$objek->debugLog("Delete user group success");	 
		 }else{
			$objek->nextMessage("Delete User Group Fail, Please Try Again or Call Support");				 
			$objek->debugLog("Delete user group fail [".$sqlDelUserGroup."]");
		 }		 
         break;
}

// IF DO NOTHING, SHOW ALL USER LIST
if ($action != "new" && $action != "edit" && $action != "detail" && $action != "groupadd" && $action != "groupdel" ) {
	$objek->debugLog("Opening List User");
//	$sqlShowUser = "select u.username,u.user_id,u.username,g.group_name,g.group_desc from user u left join user_group ug on u.user_id = ug.user_id left join groups g on g.group_id = ug.group_id where u.store_id = '".$store_id."' order by u.username";
	$sqlShowUser = "select u.username,u.user_id,u.fullname,u.email,l.remark as lokasi from user u join lokasi l on u.lokasi_id = l.id order by u.username";
    $rs=$q->Execute($sqlShowUser);
    $no=1;
    while(!$rs->EOF) {
		$row++ % 2 ? $t->set_var('row', 'row0'):$t->set_var('row', 'row1');
    	$t->set_var("no", $no++);
        $t->set_var("username", $rs->fields("username"));
        $t->set_var("id", $objek->enc($rs->fields("user_id")));
        $t->set_var("fullname", $rs->fields("fullname"));
		$t->set_var("email",$rs->fields("email"));
		$t->set_var("lokasi",$rs->fields("lokasi"));
        $t->parse("hdl_elemen", "elemen", true);
		$rs->MoveNext();
    }
    $t->parse("hdl_nonform", "nonform");
}
// SHOW DETAIL USER
elseif ($action == "detail" || $action == "groupadd" || $action == "groupdel") {
    if (!isset($id)) $id = $objek->dec($_GET['id']);
	$objek->debugLog("Opening Detail User [".$_GET['id']."] -> [".$id."]");
    $rs=$q->Execute("select user_id, username, fullname, email, additional_info,lokasi_id from user where user_id = '$id'");
               
    if (!$rs->EOF) {    
        $t->set_var("id", $objek->enc($rs->fields(0)));
        $t->set_var("username", $rs->fields(1));
        $t->set_var("nama", $rs->fields(2));
        $t->set_var("fullname", $rs->fields(2));
        $t->set_var("email", $rs->fields(3));
        $t->set_var("lokasi", $q->GetOne("select remark from lokasi where id = ".$rs->fields(5)));
		$additionalInfoUser = $rs->fields("additional_info");
		$rs->MoveNext();
    }else{ 
		$objek->errmessage("Tidak ada user dengan ID tersebut");
		$objek->debugLog("Tidak Ada User ID [".$_GET['id']."] -> [".$id."]");
	}
    $rs1=$q->Execute("select group_id, group_name from groups where store_id in (0,".$store_id.") order by group_name");
               
    $elemen_group = "";
    while(!$rs1->EOF) {

        $rs2=$q->Execute("select user_id,group_id,group_name from user_group a join groups b using (group_id) where user_id = '$id' and group_id = '". $rs1->fields(0). "'");
        if (!$rs2->EOF) {	
            $t->set_var("groupid", $objek->enc($rs2->fields(1)));
            $t->set_var("groupname", $rs2->fields(group_name));
			if($additionalInfoUser == "default" && $rs2->fields(1) == "14"){
				$t->set_var("linkDelete", "<i>Cannot Delete</i>");
			}else{
				$t->set_var("linkDelete","<a href='index.php?appid=admin&sub=user&action=groupdel&id=".$objek->enc($rs2->fields(0))."&groupid=".$objek->enc($rs2->fields(1))."' onclick=\"return confirm('Are you sure?')\"><i class='i-cancel-2'></i></a>");	
			}
			
            $t->parse("hdl_elemen_member", "elemen_member", true);
	    	$rs2->MoveNext();
        }else{
            $elemen_group .= "<option value=\"". $objek->enc($rs1->fields(0)). "\">". $rs1->fields(1) . "</option>\n";
		}
        $rs1->MoveNext();
    }
    $t->set_var("elemen_group", $elemen_group);
    $t->parse("hdl_detail", "detail");
}

$t->pparse("out", "hdl_user");

$objek->footer();
?>
