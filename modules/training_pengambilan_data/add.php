<?
# Inisialisasi
$objek->init();
$objek->setTitle('Form Tambah Data');
$appName = "Training";

$q = buatKoneksiDB();

# Isi halaman
$t = buatTemplate();

$t->set_var("appid", $_GET['appid']);
$t->set_var("appName", $appName);

$user = $objek->user->profil;

$t->set_file("handle", "add.html");

$id_kondisi_daun = 1;
if ($rs = $q->Execute('select * from kondisi_daun order by id')) {
	$option = '';
	while (!$rs->EOF) {
		if ($rs->fields['id'] == $id_kondisi_daun) $selected = 'selected';
		else $selected = '';
		$option .= '<option value="'.$rs->fields['id'].'"'.$selected.'>['.$rs->fields['code'].'] '.$rs->fields['name']."</option>\n";
		$rs->MoveNext();
	}
}
$t->set_var('kondisi_daun', $option);

$id_pohon = $objek->dec($_GET['id']);

//Store to array
$pohonArr = [
	"unique_code" => $q->GetOne("select unique_code from pohon where id = ".$id_pohon),
	"id_pohon_int" => $q->GetOne("select id from pohon where id = ".$id_pohon),
	"kordinat" => $q->GetOne("select latitude_longtitude from pohon where id = ".$id_pohon),
	"jenis_klon" => $q->GetOne("select jk.name from jenis_klon jk join pohon p on jk.id = p.id_jenis_klon where p.id = ".$id_pohon),
	"umur_pohon" => $q->GetOne("select umur_pohon from pohon where id = ".$id_pohon)
];

$t->set_var("unique_code",$pohonArr["unique_code"]);
$t->set_var("id_pohon_int",$pohonArr["id_pohon_int"]);
$t->set_var("kordinat", $pohonArr["kordinat"]);
$t->set_var("jenis_klon", $pohonArr["jenis_klon"]);
$t->set_var("umur_pohon", $pohonArr["umur_pohon"]);

$act = $_POST['act'];
if($act == "add"){
	$id = $_POST['id'];
	$unique_code = $_POST['unique_code'];
	$umur_pohon = $_POST['umur_pohon'];
	$id_jenisklon = $_POST['id_jenisklon'];
	$id_lahan = $_POST['id_lahan'];
	$status = $_POST['status'];
	$latitude_longtitude = $_POST['latitude_longtitude'];

	if ($pohonArr["unique_code"] === false) {
		$sqlInsert = "insert into pohon (unique_code,latitude_longtitude,umur_pohon,id_jenis_klon,id_lahan) values ('" . $unique_code . "','" . $latitude_longtitude . "','" . $umur_pohon . "','" . $id_jenisklon . "','" . $id_lahan . "')";
		$objek->userLog('Add ' . $appName . ' [' . $unique_code . '] Success');

		$rs = $q->Execute($sqlInsert);
		if($rs){
			$objek->debugLog("Add ".$appName." Success, ".$unique_code);
		}else{
			$objek->debugLog("Add ".$appName." Fail, Query : ".$sqlInsert);
			$objek->nextmessage("Add ".$appName." Fail, Query : ".$sqlInsert, "danger");
		}
	}

	$id_pohon_int = $_POST["id_pohon_int"];
	$id_daun_tua = $_POST['id_daun_tua'];
	$id_daun_muda = $_POST['id_daun_muda'];
	$bunga = $_POST['bunga'];
	$buah_kecil = $_POST['buah_kecil'];
	$buah_dewasa = $_POST['buah_dewasa'];
	$buah_siap_panen = $_POST['buah_siap_panen'];
	$ph = $_POST['ph'];
	$bo = $_POST['bo'];
	$ktk = $_POST['ktk'];
	$pytoptora = $_POST['pytoptora'];
	$pbk = $_POST['pbk'];
	$vsd = $_POST['vsd'];

	$sqlInsert = "INSERT INTO objek" .
	"(" .
	"`id_pohon`," .
	"`date`," .
	"`daun_tua`," .
	"`daun_muda`," .
	"`bunga`," .
	"`buah_kecil`," .
	"`buah_dewasa`," .
	"`buah_siap_panen`," .
	"`PH`," .
	"`BO`," .
	"`KTK`," .
	"`sehat_status`," .
	"`pytoptora`," .
	"`pbk`," .
	"`vsd_status`)" .
	"VALUES" .
	"(" .
	"$id_pohon_int," .
	"NOW()," .
	"'$id_daun_tua'," .
	"'$id_daun_muda'," .
	"$bunga," .
	"$buah_kecil," .
	"$buah_dewasa," .
	"$buah_siap_panen," .
	"$ph," .
	"$bo," .
	"$ktk," .
	"1," .
	"$pytoptora," .
	"$pbk," .
	"$vsd)";

	$rs = $q->Execute($sqlInsert);
	if($rs){
		$objek->debugLog("Add ".$appName." Success, ".$unique_code);
		header("location:index.php?appid=".$_GET['appid']."&m=Add Success&mt=success");
	}else{
		$objek->debugLog("Add ".$appName." Fail, Query : ".$sqlInsert);
		$objek->nextmessage("Add ".$appName." Fail, Query : ".$sqlInsert, "danger");
	}


	$rs = $q->Execute($sqlInsert);

}

$t->pparse("output", "handle");

$objek->footer();
?>
