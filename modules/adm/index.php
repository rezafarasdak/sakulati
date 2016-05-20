<?
# Inisialisasi
$objek->init();
$q = buatKoneksiDB();
$t = buatTemplate();

$t->set_file("hdl_utama", "utama.html");
$t->set_block("hdl_utama", "elemen", "hdl_elemen");
$t->set_block("hdl_utama", "tidakada", "hdl_empty");
$t->set_var("hdl_elemen", "");
$t->set_var("hdl_empty", "");

$app = $_GET['appid'];
$groupId = $objek->userInfo();

$sqlSub = "select * from module where module_root_id = (select module_id from module where name = '".$app."') and module_id in (select module_id from group_module_priv where group_id = ".$groupId.")";

$rsSub = $q->Execute($sqlSub);
$menu = "";
if ($rsSub and !$rsSub->EOF) {
	while(!$rsSub->EOF) {
		$t->set_var("appid", $rsSub->fields["name"]);
		$t->set_var("app_desc", $rsSub->fields["title"]);
		$t->parse("hdl_elemen", "elemen", true);
		$rsSub->MoveNext();
	}
}

$t->pparse("out", "hdl_utama");

$objek->footer();

?>
