<?
$objek = new objek;

$t = buatTemplate(BLOCK_TEMPLATE_DIR);
$q = buatKoneksiDB('');

$is_superuser = ($objek->otentikasi->is_login() ? $objek->user->is_superuser() : false);
$uid = ($objek->otentikasi->is_login() ? $objek->user->uid() : '');
$rs1 = $q->Execute("select a.* from module a join sites b where is_visible='1' and siteName= '".$GLOBALS['siteName']."' and a.siteID=b.siteID  order by flag,title asc" );
$groupId = $objek->userInfo();

$app = $_GET['appid'];

if ($rs1 and !$rs1->EOF) {
	$t->set_file("hdl_menu", "menu.html");
	$t->set_block("hdl_menu", "elemen", "hdl_elemen");
	$t->set_block("hdl_menu", "sub_elemen", "hdl_sub_elemen");
	$t->set_var("hdl_sub_elemen", "");
	$t->set_var("hdl_elemen", "");
	
	$t->set_var("sub_appid", "");
	$t->set_var("sub_app_desc", "");
	
	while(!$rs1->EOF) {
		$sql = "select *	from   group_module_priv a, user_group b
							where  a.group_id = b.group_id
							and    a.module_id = '". $rs1->fields["module_id"]. "'
							and    b.user_id = '". $uid. "'";
//		$objek->debugLog("App ".$rs1->fields["title"]." : ".$sql);
		$rs2 = $q->Execute($sql);
		if(($rs2 and !$rs2->EOF) or ($rs1->fields["is_public"] == 1) or ($rs1->fields["is_mandatory"] == 1) or $is_superuser) {
			
			$jumlahSubMenu = $q->GetOne("select count(*) from module where module_root_id = '". $rs1->fields["module_id"]. "'");
//			$objek->debugLog("Jumlah Sub Menu ".$rs1->fields["title"]." - ".$app."  ".$jumlahSubMenu);
			if($jumlahSubMenu > 0){
				
				// Dynamic Menu Start
				if(empty($app)){
//						$objek->debugLog("No APP");
						$t->set_var("cssClass", "openable");									
				}else{
					if($rs1->fields["name"] == $app){
						// Getting Root Menu - Describe
						$t->set_var("cssClass", "openable open");
					}else{
						// Getting Sub Menu
						$sqlSubMenu = "select name from module where module_id = (select module_root_id from module where name = '".$app."') and module_id in (select module_id from group_module_priv where group_id = ".$groupId.") order by flag";
//						$objek->debugLog("Cari Sub Menu".$rs1->fields["title"]."  ".$sqlSubMenu);
					
						$rootApp = $q->GetOne($sqlSubMenu);
						if($rootApp == $rs1->fields["name"]){
	
							$t->set_var("cssClass", "openable open");						
	
						}else{
	
							$t->set_var("cssClass", "openable");
	
						}
					}
				}
				// Dynamic Menu End
			}else{
				$t->set_var("cssClass", "");
			}
			
			$t->set_var("appid", $rs1->fields["name"]);
			$t->set_var("app_desc", $rs1->fields["title"]);
				$sqlSub = "select * from module where module_root_id = '". $rs1->fields["module_id"]. "' and module_id in (select module_id from group_module_priv where group_id = ".$groupId.") order by flag";
//				$objek->debugLog("Sub Menu : ".$sqlSub);
				$rsSub = $q->Execute($sqlSub);
				$menu = "";
				if ($rsSub and !$rsSub->EOF) {
					$menu .= '<ul class="sub">';


					while(!$rsSub->EOF) {

						$cssSubMenu = "";
						if(empty($app)){
							if($rsSub->fields["name"] == "order"){
								$cssSubMenu = "class='active'";			
							}else{
								$cssSubMenu = "";									
							}
						}else{
							if($rsSub->fields["name"] == $app){
								$cssSubMenu = "class='active'";			
							}else{
								$cssSubMenu = "";
							}
						}
			
						$menu .= "<li ".$cssSubMenu."><a href='index.php?appid=".$rsSub->fields["name"]."'>".$rsSub->fields["title"]."</a></li>";
						$t->set_var("sub_appid", $rsSub->fields["name"]);
						$t->set_var("sub_app_desc", $rsSub->fields["title"]);
						

						$rsSub->MoveNext();
					}
					$menu .= "</ul>";
				}
				$t->set_var("sub_menu", $menu);
			
			$t->parse("hdl_elemen", "elemen", true);
		}
		$rs1->MoveNext();
	}
	if (trim($t->get('hdl_elemen')) != '')  $t->pparse("out", "hdl_menu");
}
unset($objek);
?>