<?
# Inisialisasi
$objek->init();

$q = buatKoneksiDB();
$t = buatTemplate();

$q1=$q;

$action = $_GET['action'];
$actionPost = $_POST['actionPost'];

$store_id = $_SESSION['user']->profil['store_id'];
if(empty($store_id)){
	$store_id = 0;	
}

$t->set_file("hdl_aplikasi", "aplikasi.html");
$t->set_block("hdl_aplikasi", "elemen", "hdl_elemen");
$t->set_block("hdl_aplikasi", "elemen_member", "hdl_elemen_member");
$t->set_block("hdl_aplikasi", "nonform", "hdl_nonform");
$t->set_block("hdl_aplikasi", "form", "hdl_form");
$t->set_block("hdl_aplikasi", "detail", "hdl_detail");
$t->set_var("hdl_elemen", "");
$t->set_var("hdl_elemen_member", "");
$t->set_var("hdl_elemen_group", "");
$t->set_var("hdl_nonform", "");
$t->set_var("hdl_form", "");
$t->set_var("hdl_detail", "");

switch($actionPost) {
    case "add" :
		$objek->debugLog("Inserting New Apps");
		$id = $_POST['id'];	
		$nama =  $_POST['nama'];
		$judul =  $_POST['judul'];
		$public =  isset($_POST['public']) ? 1 : 0;
		$visible =  isset($_POST['visible']) ? 1 : 0;
		$mandatory =  isset($_POST['mandatory']) ? 1 : 0; 
		$module_root_id =  $_POST['module_root_id'];
		if (!isset($_POST['batal'])) {
		 $rs=$q->Execute("insert into module (siteid,name,title,is_visible,is_public,is_mandatory, module_root_id)
					values ('".$objek->siteID."','$nama', '$judul', '$visible', '$public','$mandatory', '$module_root_id')");
		}
		break;

	case "update" :
	 	 $id =  $objek->dec($_POST['id']);	
         $nama =  $_POST['nama'];
         $judul =  $_POST['judul'];
         $public =  isset($_POST['public']) ? 1 : 0;
         $visible =  isset($_POST['visible']) ? 1 : 0;
		 $mandatory =  isset($_POST['mandatory']) ? 1 : 0;
		 $objek->debugLog("Updating Apps : ".$id);
       	 if (!isset($_GET['batal'])) {
             $rs=$q->Execute("update module
                        set    name = '$nama',
                               title = '$judul',
                               is_visible = '$visible',
                               is_public = '$public',
							   is_mandatory = '$mandatory'	
                        where  module_id = '$id'");
         }
		 		 
         break;

    case "groupadd" :
         $id = $objek->dec($_POST['id']);
         $groupid = $objek->dec($_POST['groupid']);
         $levelpriv = trim($_POST['levelpriv']);
 		 $objek->debugLog("Insert Into Group Module Priv");
         if (!is_numeric($levelpriv))
            $objek->errmessage("Level harus berupa bilangan bulat positif");
         $rs=$q->Execute("insert into group_module_priv values ('$groupid', '$id', '$levelpriv')");
		 $objek->nextMessage("Adding Group Success");
         break;

}


switch($action) {
    case "new" :
		 $objek->debugLog("Open Form Add Apps");
         $t->set_var("nama", "");
         $t->set_var("judul", "");
         $t->set_var("public", "");
         $t->set_var("visible", "");
         $t->set_var("id", "");
         $t->set_var("action", "add");
         $t->set_var("action title", "Tambah");
		$rs1=$q->Execute("select module_id, name, title from module where module_root_id = 0 and is_public = 0 and is_visible = 1 order by title");
		$elemen_module = "";
		while(!$rs1->EOF) {
		 $elemen_module .= "<option value=\"". $rs1->fields(0). "\">".$rs1->fields(2). "</option>\n";
		 $rs1->MoveNext();	
		}
		$t->set_var("elemen_module", $elemen_module);
		
         $t->parse("hdl_form", "form");
         break;
    case "edit" :
         $id = $objek->dec($_GET['id']);
		 $objek->debugLog("Open Form Edit Apps ID : ".$id);
         $rs=$q->Execute("select *
                    from	module
                    where  module_id= '$id'");
         if (!$rs->EOF) {
             $t->set_var("nama", $rs->fields("name"));
             $t->set_var("judul", $rs->fields("title"));
             $t->set_var("public", ($rs->fields("is_public") > 0) ? "checked" : "");
             $t->set_var("visible", ($rs->fields("is_visible") > 0) ? "checked" : "");
			 $t->set_var("mandatory", ($rs->fields("is_mandatory") > 0) ? "checked" : "");	
             $t->set_var("id", $objek->enc($rs->fields("module_id")));
             $t->set_var("action", "update");
             $t->set_var("action title", "Edit");
             $t->parse("hdl_form", "form");
			 $rs->MoveNext();
         }
         break;
    case "del" :
         $id = $objek->dec($_GET['id']);
		 $objek->debugLog("Deleting Apps ID : ".$id);
         if (!in_array($id, array("admin","daftar"))) {
             $rs=$q->Execute("delete from module where  module_id = '$id'");
         }
         else {
             $objek->errmessage("Aplikasi ini tidak boleh dihapus");
         }
         break;
    case "groupdel" :
         $id = $objek->dec($_GET['id']);
         $groupid = $objek->dec($_GET['groupid']);
		 $objek->debugLog("Delete Group Module Priviledge ID : ".$id." - Group ID : ".$groupid);
         $rs=$q->Execute("delete from group_module_priv 
                    where  group_id = '$groupid'
                    and    module_id = '$id'");
		 $objek->nextMessage("Delete Group Success");
         break;

	case "uporder" :
         $order =$_GET['order'];
		 $id	=$_GET['id'];
		 $rs= 	$q->Execute("select module_id,flag from module where flag < $order order by flag desc limit 1");
		 $mid=$rs->fields["module_id"];
		 $fl=$rs->fields["flag"];
		 $rs1 = $q->Execute("update module set flag='$fl' where module_id='$id'");
		 $rs2 = $q->Execute("update module set flag='$order' where module_id='$mid'");
		
		header("location:index.php?appid=admin&sub=aplikasi");
		break;

	case "downorder" :
         $order =$_GET['order'];
		 $id	=$_GET['id'];
		 $rs= 	$q->Execute("select module_id,flag from module where flag > $order order by flag asc limit 1");
		 $mid=$rs->fields["module_id"];
		 $fl=$rs->fields["flag"];
		 $rs1 = $q->Execute("update module set flag='$fl' where module_id='$id'");
		 $rs2 = $q->Execute("update module set flag='$order' where module_id='$mid'");
		
		header("location:index.php?appid=admin&sub=aplikasi");
		break;

	case "saveorder" :
         $jum =$_POST['jum'];
         for($i=1;$i<=$jum;$i++)
		 {
			 $rs=$q->Execute("update module set flag=".$_POST['order'.$i]." where module_id=".$_POST['mid'.$i]."");
		 }
		header("location:index.php?appid=admin&sub=aplikasi");
		break;
	

}


if ($action != "new" && $action != "edit" && $action != "detail" &&
    $action != "groupadd" && $action != "groupdel" && $action != "uporder" && $action != "downorder"  && $action != "saveorder") {
	
//	$sqlShowApps = "select distinct m1.title, m2.title, m1.name, m2.module_id from module m1 inner join module m2 on m1.module_id = m2.module_root_id where m1.is_public = 0 and m2.module_id in (select module_id from group_module_priv where group_id in (select group_id from user_group where user_id = '".$objek->user->uid()."')) order by m1.title";
	$sqlShowApps = "select distinct m1.title, m2.title, m1.name, m2.module_id from module m1 inner join module m2 on m1.module_id = m2.module_root_id where m1.is_public = 0 order by m1.title";
	$objek->debugLog("Opening List Apps ");
    $rs=$q->Execute($sqlShowApps);	
	if ($rs and !$rs->EOF) {
		$no=1;
		while(!$rs->EOF) {
			$t->set_var("no", $no);
			$no++ % 2 ? $t->set_var('row', 'row0'):$t->set_var('row', 'row1');
			$jum=$jum+1;
			$t->set_var("jum", $jum);
			$t->set_var("order", $rs->fields["flag"]);	
			$t->set_var("nama", $rs->fields("name"));
			$t->set_var("id", $objek->enc($rs->fields("module_id")));
			if($rs->fields(1) == ""){
				$t->set_var("judul", $rs->fields(0));			
			}else{
				$t->set_var("judul", $rs->fields(0)." - ".$rs->fields(1));	
			}
//			$t->set_var("judul", $rs->fields("title"));
			$t->set_var("public", $rs->fields("is_public") ? "Ya" : "Tidak");
			$t->set_var("visible", $rs->fields("is_visible") ? "Ya" : "Tidak");
			$t->set_var("mandatory", $rs->fields("is_mandatory") ? "Ya" : "Tidak");
			$t->parse("hdl_elemen", "elemen", true);
			$rs->MoveNext();
		}
	}
    $t->parse("hdl_nonform", "nonform");
}
elseif ($action == "detail" || $action == "groupadd" || $action == "groupdel") {
    if (!isset($id)) $id = $objek->dec($_GET['id']);
	$objek->debugLog("Opening Detail Apps ID : [".$_GET['id']."] = ID : ".$id);
    $rs=$q->Execute("select  module_id,name,title, is_visible, is_public
               from   module
               where  module_id = '$id'");
    if (!$rs->EOF) {
        $t->set_var("id", $objek->enc($rs->fields(0)));
        $t->set_var("name", $rs->fields(2));		
        $t->set_var("visibility", ($rs->fields(2)) ? "Ya" : "Tidak");
        $t->set_var("publicity", ($rs->fields(3)) ? "Ya" : "Tidak");
	 $rs->MoveNext();
    }else{ 
		$objek->errmessage("Tidak ada aplikasi dengan ID tersebut");
		$objek->debugLog("Cannto Open Detail Apss ID ".$id);
	}
    $rs1=$q->Execute("select group_id, group_name from groups order by group_name");
    $elemen_group = "";
    while(!$rs1->EOF) {

//select b.group_id,group_name,priv from group_module_priv a join groups b on(a.group_id=b.group_id) where module_id =
        $rs2=$q->Execute("select b.group_id,group_name,priv
                    from   group_module_priv a join groups b on(a.group_id=b.group_id)
                    where  module_id = '$id'
                    and    b.group_id = '". $rs1->fields(0). "'");

        if (!$rs2->EOF) {
            $t->set_var("groupid", $objek->enc($rs2->fields(group_id)));
            $t->set_var("groupname", $rs2->fields(group_name));
            $t->set_var("id_delete", $objek->enc($rs2->fields(id_delete)));
            $t->set_var("priv", $rs2->fields(priv));
			$t->set_var("deleteLink","<a href='index.php?appid=admin&sub=aplikasi&action=groupdel&id={id}&groupid=".$objek->enc($rs2->fields(group_id))."'><i class='i-cancel-2'></i></a>");
            $t->parse("hdl_elemen_member", "elemen_member", true);
	   		$rs2->MoveNext();
        }else{
//			if($rs1->fields(store_id) != '0'){
	            $elemen_group .= "<option value=\"". $objek->enc($rs1->fields(0)). "\">".$rs1->fields(1). "</option>\n";
//			}
		}
         $rs1->MoveNext();
    }
    $t->set_var("elemen_group", $elemen_group);
    $t->parse("hdl_detail", "detail");
}

$t->pparse("out", "hdl_aplikasi");

$objek->footer();
?>

