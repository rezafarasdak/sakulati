<?
# Inisialisasi
error_reporting(1);
$objek->init();

$q = buatKoneksiDB();
$t = buatTemplate();

$store_id = $_SESSION['user']->profil['store_id'];
if(empty($store_id)){
	$store_id = 0;	
}


$action = htmlspecialchars(trim($_GET['action']));
$actionPost = htmlspecialchars(trim($_POST['actionPost']));

$rs=$q1 = $rs=$q;
$t->set_file("hdl_grup", "grup.html");
$t->set_block("hdl_grup", "elemen", "hdl_elemen");
$t->set_block("hdl_grup", "elemen_member", "hdl_elemen_member");
$t->set_block("hdl_grup", "elemen_module", "hdl_elemen_module");
$t->set_block("hdl_grup", "no_elemen_module", "hdl_no_elemen_module");
$t->set_block("hdl_grup", "nonform", "hdl_nonform");
$t->set_block("hdl_grup", "form", "hdl_form");
$t->set_block("hdl_grup", "detail", "hdl_detail");
$t->set_var("hdl_elemen", "");
$t->set_var("hdl_elemen_member", "");
$t->set_var("hdl_elemen_user", "");
$t->set_var("hdl_elemen_module", "");
$t->set_var("hdl_no_elemen_module", "");
$t->set_var("hdl_nonform", "");
$t->set_var("hdl_form", "");
$t->set_var("hdl_detail", "");
$t->set_var("appid", $_GET['appid']);
$t->set_var("appName", $appName);

$nama =  htmlspecialchars(trim($_POST['nama']));
$desc = htmlspecialchars(trim($_POST['desc']));

switch($actionPost) {
    case "add" :
         if (!isset($_POST['batal'])) {
             $rs=$q->query("insert into   groups(group_name, group_desc, store_id) values ( '$nama', '$desc', '$store_id')");
			 $objek->debugLog("Add Group [".$nama."] - [".$desc."]");
         }
		 $objek->nextMessage("Add Group Success");		

         break;
    case "update" :
		 $id = htmlspecialchars(trim($_POST['id']));
		 $id = $objek->dec($id);
		 if(!is_numeric($id)){
			 $objek->debugLog("Update Group - Indikasi Hacking.. [".$_POST['id']."] to [".$id."]");
			 $objek->errmessage("Update Grup Gagal");
		 }elseif (!isset($_POST['batal'])) {
			 $objek->debugLog("Updating Group [".$nama."] - ID [".$id."]");
             $rs=$q->query("update groups set group_name = '$nama', group_desc = '$desc' where  group_id = '$id'");
			 $objek->nextMessage("Update Group Success");		
         }
         break;
		 
    case "useradd" :
	  	 $userid = $objek->dec(htmlspecialchars(trim($_POST['userid'])));
		 $id =  $objek->dec(htmlspecialchars(trim($_POST['id'])));
		 $objek->debugLog("Add User Group : ".$userid);
         $rs=$q->query("insert into user_group values ('$userid','$id' )");
		 $objek->nextMessage("Adding User Group Success");		
         break;
}

switch($action) {
    case "new" :
         $t->set_var("nama", "");
         $t->set_var("id", "");
         $t->set_var("desc", "");
         $t->set_var("action", "add");
         $t->set_var("action title", "Tambah");
         $t->parse("hdl_form", "form");
		 $objek->debugLog("Opening Form Add Group");
         break;
    case "edit" :
         $id = $objek->dec(htmlspecialchars(trim($_GET['id'])));
		 if(!is_numeric($id)){
			 $objek->debugLog("Edit Group - Indikasi Hacking.. [".$_GET['id']."] to [".$id."]");
			 $objek->errmessage("Edit Grup Gagal.");
		 }
         $rs=$q->query("select * from groups where group_id = '$id'");
         if (!$rs->EOF) {
             $t->set_var("nama", $rs->fields("group_name"));
             $t->set_var("desc", $rs->fields("group_desc"));
             $t->set_var("id", $objek->enc($rs->fields("group_id")));
             $t->set_var("action", "update");
             $t->set_var("action title", "Edit");
             $t->parse("hdl_form", "form");
			 $rs->MoveNext();
         }
		 $objek->debugLog("Opening Edit Group, ID ".$id);
         break;
    case "del" :
         $id = $objek->dec(htmlspecialchars(trim($_GET['id'])));
		 if(!is_numeric($id)){
			 $objek->debugLog("Delete Group - Indikasi Hacking.. [".$_GET['id']."] to [".$id."]");
			 $objek->errmessage("Delete Grup Gagal.");
		 }
         $rs=$q->query("select group_name from groups where  group_id = '$id'");
         if (!in_array($rs->fields(0), array("Administrator", "Admin"))) {
             $rs=$q->query("delete from groups where  group_id = '$id'");
			 $objek->debugLog("Deleting Group ID : ".$id);
         }else {
             $objek->errmessage("Grup ini tidak boleh dihapus");
         }
		 $objek->nextMessage("Deleting Group Success");		
         break;
    case "userdel" :
         $id = $objek->dec(htmlspecialchars(trim($_GET['id'])));
         $userid = $objek->dec(htmlspecialchars(trim($_GET['userid'])));
		 $objek->debugLog("Deleting user group : ".$id);
         $rs=$q->query("delete from user_group where  group_id = '$id' and user_id = '$userid'");
		 $objek->nextMessage("Deleting User Group Success");		
         break;
}

if ($action != "new" && $action != "edit" &&
    $action != "detail" && $action != "useradd" && $action != "userdel") {
    $rs=$q->query("select * from   groups where store_id not in (1) and store_id in (0,".$store_id.") order  by group_id");
	$objek->debugLog("Opening List Group");
	// NOTE
//		$objek->nextmessage("Silahkan gunakan group di bawah ini atau buat group baru apabila perlu, Group Default tidak bisa di edit / hapus", "green");
		
    while(!$rs->EOF) {
        $row++ % 2 ? $t->set_var('row', 'row0'):$t->set_var('row', 'row1');
		$idEnc = $objek->enc($rs->fields("group_id"));
		$t->set_var("nama", $rs->fields("group_name"));
        $t->set_var("id", $idEnc);
        $t->set_var("detail", $rs->fields("group_desc"));
		$t->set_var("linkEdit","<a href='index.php?appid=admin&sub=grup&action=edit&id=".$idEnc."'><i class='glyphicon glyphicon-pencil'></i></a>");
		$t->set_var("linkDelete","<a href='index.php?appid=admin&sub=grup&action=del&id=".$idEnc."' onclick=\"return confirm('Are you sure?')\"><i class='i-cancel-2'></i></a>");	


        $t->parse("hdl_elemen", "elemen", true);
		$rs->MoveNext();
    }
    $t->parse("hdl_nonform", "nonform");
}
elseif ($action == "detail" || $action == "useradd" || $action == "userdel") {
    if (!isset($id)) $id = $objek->dec(htmlspecialchars(trim($_GET['id'])));
	$objek->debugLog("Opening Detail Group : ".$id." Store ID : ".$store_id);
	
    $rs=$q->query("select group_id, group_name, group_desc from groups where group_id = '$id'");
    if (!$rs->EOF) {
        $t->set_var("id", $objek->enc($rs->fields(0)));
        $t->set_var("nama", $rs->fields(1));
        $t->set_var("desc", $rs->fields(2));
	$rs->MoveNext();
    }
    else $objek->errmessage("Tidak ada group dengan ID tersebut");
    
    $rs1=$q->query("select user_id, username from user where store_id in (".$store_id.")");
    $elemen_user = "";
	while(!$rs1->EOF) {
		$row++ % 2 ? $t->set_var('row', 'row0'):$t->set_var('row', 'row1');
		$q="select * from user_group a join user b using(user_id) where  b.user_id = '". $rs1->fields(0) ."' and group_id = '$id'";
		$rs2=$q1->query($q);
			if (!$rs2->EOF) {
				$t->set_var("userid", $objek->enc($rs2->fields(0)));
				$t->set_var("username", $rs2->fields(username));
				$t->parse("hdl_elemen_member", "elemen_member", true);
				$rs1->MoveNext();
			}else{
				$elemen_user .= "<option value=\"". $objek->enc($rs1->fields(0)). "\">".$rs1->fields(1). "</option>\n";
				$rs1->MoveNext();     
			}
	}
    $t->set_var("elemen_user", $elemen_user);
	$objek->debugLog("Query : ".$q);
	
	$sqlGroupApps = "select distinct m1.title, m2.title, m1.name, m2.module_id from module m1 inner join module m2 on m1.module_id = m2.module_root_id where m1.is_public = 0 and m2.module_id in (select module_id from group_module_priv where group_id in (".$id.")) order by m1.title";
//	echo $sqlGroupApps;
	$rsGroupApps = $q1->query($sqlGroupApps);
	if (!$rsGroupApps->EOF) {
		$row = 0;
		while(!$rsGroupApps->EOF) {
			$row++ % 2 ? $t->set_var('row', 'row0'):$t->set_var('row', 'row1');
			if($rsGroupApps->fields(1) == ""){
				$t->set_var("m_title", $rsGroupApps->fields(0));			
			}else{
				$t->set_var("m_title", $rsGroupApps->fields(0)." - ".$rsGroupApps->fields(1));	
			}
			$t->set_var("m_name", $rsGroupApps->fields(2));
			$t->set_var("no", $row)	;
			$t->parse("hdl_elemen_module", "elemen_module", true);
			$rsGroupApps->MoveNext();
		}
	}else{
		$t->parse("hdl_no_elemen_module", "no_elemen_module", true);		
	}


    $t->parse("hdl_detail", "detail");
		
}
$t->pparse("out", "hdl_grup");

$objek->footer();
?>