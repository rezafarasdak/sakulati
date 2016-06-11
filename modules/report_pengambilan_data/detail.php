<?
# Inisialisasi
$objek->init();
$appName = "Report Pengambilan Data";
$objek->setTitle($appName);

$q = buatKoneksiDB();
# Isi halaman
$t = buatTemplate();

$t->set_file("handle_search_list", "detail.html");
$t->set_block("handle_search_list", "elemen", "hdl_elemen");
$t->set_block("handle_search_list", "tidakada", "hdl_empty");
$t->set_var("hdl_empty", "");
$t->set_var("hdl_elemen", "");
$t->set_var("HASIL PENCARIAN", "");
$t->set_var("CARI", "");
$t->set_var("hdl_new", "");

$t->set_var("appid", $_GET['appid']);
$t->set_var("appName", $appName);
$objek->debugLog("Opening Index");

$user = $objek->user->profil;
if($objek->isAdmin()){
	$whereLahan = "";
}else{
	$whereLahan = " and l1.id in (select id_lahan from lahan_role where id_user = ".$user[userid].")";
}

$id = $objek->dec($_GET['id']);

// Message
$message = $_GET['m'];
$message_type = $_GET['mt'];
if (isset($message) && ($message != "")) {
	$objek->nextmessage($message, $message_type);
}

$sql = "select p.id as id_pohon, p.unique_code, p.latitude_longtitude, p.umur_pohon, l1.id as id_lahan, l1.name as cluster, l2.name as lahanutama, jp.name as jenispertanian, jk.name as jenisklon, kd1.name as kondisi_daun_muda, kd1.code as kondisi_daun_muda_code, kd2.name as kondisi_daun_tua, kd2.code as kondisi_daun_tua_code, l1.jumlah_pohon, o.* from objek o
join pohon p on p.id = o.id_pohon
join lahan l1 on l1.id = p.id_lahan
join lahan l2 on l1.id_lahanutama = l2.id
join jenis_pertanian jp on jp.id = l1.id_jenispertanian
join jenis_klon jk on jk.id = p.id_jenis_klon
join kondisi_daun kd1 on kd1.id = o.daun_muda
join kondisi_daun kd2 on kd2.id = o.daun_tua
where l1.type = 'C' ".$whereLahan." and o.id = ".$id;

$objek->debugLog("Query [".$sql."]");

$rs = $q->Execute($sql);

if ($rs and !$rs->EOF) {
	$count = 0;
	while(!$rs->EOF) {
		++$nomor;
		$t->set_var("id", $objek->enc($rs->fields['id']));
		$t->set_var("no", $nomor);
		$t->set_var("unique_code", $rs->fields['unique_code']);
		$t->set_var("umur", $rs->fields['umur_pohon']);
		$t->set_var("cluster", $rs->fields['cluster']);
		$t->set_var("lahanutama", $rs->fields['lahanutama']);
		$t->set_var("jenispertanian", $rs->fields['jenispertanian']);
		$t->set_var("klon", $rs->fields['jenisklon']);
		$t->set_var("kordinat", $rs->fields['latitude_longtitude']);
		$t->set_var("date", $rs->fields['date']);
		$t->set_var("siap_panen", $rs->fields['buah_siap_panen']);
		$t->set_var("daun_tua", '['.$rs->fields['kondisi_daun_tua_code'].'] '.$rs->fields['kondisi_daun_tua']);
		$t->set_var("daun_muda", '['.$rs->fields['kondisi_daun_muda_code'].'] '.$rs->fields['kondisi_daun_muda']);
		$t->set_var("bunga", $rs->fields['bunga']);
		$t->set_var("buah_kecil", $rs->fields['buah_kecil']);
		$t->set_var("buah_dewasa", $rs->fields['buah_dewasa']);
		$t->set_var("PH", $rs->fields['PH']);
		$t->set_var("BO", $rs->fields['BO']);
		$t->set_var("KTK", $rs->fields['KTK']);
		$t->set_var("sehat", $arraySehatStatus[$rs->fields['sehat_status']]);
		$t->set_var("pytoptora", $rs->fields['pytoptora']);
		$t->set_var("pbk", $rs->fields['pbk']);
		$t->set_var("vsd", $arrayVSDStatus[$rs->fields['vsd_status']]);
		$t->set_var("panen", $rs->fields['panen_id']);
		$t->set_var("date", $rs->fields['date']);
		$t->set_var("status", $arrayStatus[$rs->fields['status']]);
		$t->set_var("last_panen", $rs->fields['terakhir_panen']);
		
		$realPohon = $q->GetOne("select count(*) from pohon p where p.id_lahan = ".$rs->fields['id_lahan']);
		$samplingPohon = $rs->fields['jumlah_pohon'];
		
		$pohonDiWakili = 1;
		if($samplingPohon > 0){
			$pohonDiWakili = ceil($samplingPohon / $realPohon); 
		}

		$t->set_var("jumlah_pohon", $pohonDiWakili);
		
		// Alert Bunga
		$sqlGetBunga = "select bunga from objek where id_pohon = ".$rs->fields['id_pohon']." and date < '".$rs->fields['date']."' order by date desc";
		$lastBunga = $q->GetOne($sqlGetBunga);
		$objek->debugLog($sqlGetBunga." ==> ".$lastBunga);
		
		if(!empty($lastBunga)){
			if(($lastBunga * $persentageBungakeBuahKecil / 100) >  $rs->fields['buah_kecil']){
				$alert = true;
				$objek->nextmessage("Jumlah Buah Kecil : [".$rs->fields['buah_kecil']."], Kurang Dari ".$persentageBungakeBuahKecil."% Jumlah Bunga Periode Sebelumnya : [".$lastBunga."]", "danger");
			}else{
			//	$objek->nextmessage("Jumlah Buah Kecil : [".$rs->fields['buah_kecil']."], Lebih Dari ".$persentageBungakeBuahKecil."% Jumlah Bunga Periode Sebelumnya : [".$lastBunga."]", "success");			
			}
		}

		// Alert Buah
		$sqlGetBuahKecil = "select buah_kecil from objek where id_pohon = ".$rs->fields['id_pohon']." and date < '".$rs->fields['date']."' order by date desc";
		$lastBuahKecil = $q->GetOne($sqlGetBuahKecil);
		$objek->debugLog($sqlGetBuahKecil." ==> ".$lastBuahKecil);
		
		if(!empty($lastBuahKecil)){
			if(($lastBuahKecil * $persentageBuahKecilkeBuahBesar / 100) >  $rs->fields['buah_dewasa']){
				$alert = "Jumlah Buah Besar : [".$rs->fields['buah_dewasa']."], Kurang Dari ".$persentageBuahKecilkeBuahBesar."% Jumlah Buah Kecil Periode Sebelumnya : [".$lastBuahKecil."]";
				
				if($alert == true){
					$objek->smessage($alert);
				}else{
					$objek->nextmessage($alert,"danger");
				}
			}else{
				//$objek->nextmessage("Jumlah Buah Besar : [".$rs->fields['buah_dewasa']."], Lebih Dari ".$persentageBuahKecilkeBuahBesar."% Jumlah Buah Kecil Periode Sebelumnya : [".$lastBuahKecil."]", "success");
							
			}
		}
				
		$t->parse("hdl_elemen", "elemen", true);
		$rs->MoveNext();
	}
}
else $t->parse("hdl_empty", "tidakada");

if($objek->checkDeletePermission()){
	$t->set_var("deleteLink", '<a href="#"><button class="btn btn-danger">Delete</button></a>');
}else{
	$t->set_var("deleteLink", "");
}

if($objek->checkEditPermission()){
	$t->set_var("editLink", '<a href="#"><button class="btn btn-warning">Edit</button></a>');
}else{
	$t->set_var("editLink", "");
}


$t->pparse("output", "handle_search_list");

$objek->footer();
?>
