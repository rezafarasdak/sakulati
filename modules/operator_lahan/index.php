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

    case "groupadd" :
         $id = $objek->dec($_POST['id']);
         $id_lahan = $objek->dec($_POST['id_lahan']);
		 $sqlUpdateUserGroup = "insert into lahan_role (id_user, id_lahan) values ( '$id','$id_lahan')";
         $rs=$q->Execute($sqlUpdateUserGroup);
		 if($rs){
			$objek->nextMessage("Updating User Lahan Success");					 
			$objek->debugLog("Updating user Lahan success");	 
		 }else{
			$objek->nextMessage("Updating User Lahan Fail, Please Try Again or Call Support");				 
			$objek->debugLog("Updating user Lahan fail [".$sqlUpdateUserGroup."]");
		 }
         break;
}

// AFTER CLICK LINK
switch($action) {
    case "groupdel" :
         $id = $objek->dec($_GET['id']);
         $id_lahan = $objek->dec($_GET['id_lahan']);
		 $sqlDelUserGroup = "delete from lahan_role where id_lahan = '$id_lahan' and id_user = '$id' ";
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
//	$sqlShowUser = "select u.username,u.user_id,u.fullname,u.email,l.remark as lokasi from user u join lokasi l on u.lokasi_id = l.id order by u.username";
	$sqlShowUser = "select u.username,u.user_id,u.fullname,u.email,g.group_name from user u join user_group ug on u.user_id = ug.user_id join groups g on g.group_id = ug.group_id where g.group_id in (25,29,31) order by u.fullname, group_name";
    $rs=$q->Execute($sqlShowUser);
    $no=1;
    while(!$rs->EOF) {
		$row++ % 2 ? $t->set_var('row', 'row0'):$t->set_var('row', 'row1');
    	$t->set_var("no", $no++);
        $t->set_var("username", $rs->fields("username"));
        $t->set_var("id", $objek->enc($rs->fields("user_id")));
        $t->set_var("fullname", $rs->fields("fullname"));
		$t->set_var("email",$rs->fields("email"));
		$t->set_var("group_name",$rs->fields("group_name"));
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
    $rs1=$q->Execute("select id, name from lahan where type = 'C' order by name");
               
    $elemen_group = "";
    while(!$rs1->EOF) {

		$sqlShowLahan = "select distinct u.user_id, l.id, l.name from lahan_role r join lahan l on r.id_lahan = l.id join user u on u.user_id = r.id_user where u.user_id = '$id' and l.id = ". $rs1->fields(0);
		$objek->debugLog($sqlShowLahan);
        $rs2=$q->Execute($sqlShowLahan);
        if (!$rs2->EOF) {	
            $t->set_var("id_lahan", $objek->enc($rs2->fields(1)));
            $t->set_var("lahanname", $rs2->fields(name));
			if($additionalInfoUser == "default" && $rs2->fields(1) == "14"){
				$t->set_var("linkDelete", "<i>Cannot Delete</i>");
			}else{
				$t->set_var("linkDelete","<a href='index.php?appid=operator_lahan&action=groupdel&id=".$objek->enc($rs2->fields(0))."&id_lahan=".$objek->enc($rs2->fields(1))."' onclick=\"return confirm('Are you sure?')\"><i class='i-cancel-2'></i></a>");	
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
