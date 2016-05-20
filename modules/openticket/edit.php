<?
# Inisialisasi
$objek->init();

$q = buatKoneksiDB('tindakan');

# Isi halaman
$t = buatTemplate();

$t->set_file("handle", "edit.html");

$t->set_block("handle", "uraian_ada", "hdl_uraian_ada");
$t->set_block("handle", "uraian_tidakada", "hdl_uraian_tidakada");
$t->set_var("hdl_uraian_tidakada", "");
$t->set_var("hdl_uraian_ada", "");

// Message
$message = $_GET['m'];
if (isset($message) && ($message != "")) {
	$objek->nextmessage($message);
}

// SHOW DATA
$id = $_GET['id'];
if (isset($id) && ($id != "")) {
	$sql = "SELECT * FROM master as m left join tb_kemasan as kms on m.kd_jenis_kemasan = kms.kd_kemasan left join tb_kantor as knt on m.kd_kantor = knt.kode_kantor left join tb_kasus as kss on m.kd_kasus = kss.kd_kasus left join tb_komoditi as kmd on m.kd_kom = kmd.kd_kom left join tb_status as sts on m.kd_status = sts.kd_status left join tb_tindaklanjut as tdl on m.kode_tindaklanjut = tdl.kd_tindaklanjut where m.no_kasus = '".$id."' limit 1";	
	$rs = $q->Execute($sql);
	if ($rs and !$rs->EOF) {
		$t->set_var("MESSAGE", "Detail Data Dengan No Kasus : ".$id);
		while(!$rs->EOF) {
			$no_kasus = $rs->fields['no_kasus'];
			$t->set_var("no_kasus", $rs->fields[no_kasus]);
			$t->set_var("tgl_input", $rs->fields[tgl_input]);
			$t->set_var("time_input", $rs->fields[time_input]);
			$t->set_var("kd_kantor", $rs->fields[kd_kantor]);
			$t->set_var("nosbp", $rs->fields[nosbp]);
			$t->set_var("tgl_sbp", $rs->fields[tgl_sbp]);
			$t->set_var("tgl_penindakan", $rs->fields[tgl_penindakan]);			
			$t->set_var("lokasi", $rs->fields[lokasi]);
			$t->set_var("penangkap", $rs->fields[penangkap]);
			$t->set_var("pelaku", $rs->fields[pelaku]);

			$t->set_var("kd_kom", $rs->fields[kd_kom]);
			$t->set_var("kd_kasus", $rs->fields[kd_kasus]);
			$t->set_var("kd_jenis_kemasan", $rs->fields[kd_jenis_kelamin]);

			$old_kd_kantor = $rs->fields[kd_kantor];
			$old_kd_kom = $rs->fields[kd_kom];
			$old_kd_kasus = $rs->fields[kd_kasus];
			$old_kd_jenis_kemasan = $rs->fields[kd_jenis_kemasan];
			$old_kd_pasal = $rs->fields[kd_pasal];
			$old_kd_status = $rs->fields[kd_status];
			$old_kd_pelanggaran = $rs->fields[kd_pelanggaran];
			$old_kd_tindak_lanjut = $rs->fields[kode_tindaklanjut];
			$old_kd_sumberinfo = $rs->fields[kd_sumberinfo];
			$old_kd_tindakan = $rs->fields[kd_tindakan];
			$old_kd_terhadap = $rs->fields[kd_terhadap];

			$t->set_var("defaultSubKasus", $rs->fields[kd_kasus]);
			$t->set_var("defaultMasterKasus", $rs->fields[root_id]);

			$t->set_var("kd_pasal", $rs->fields[kd_pasal]);
			$t->set_var("uraian_modus", $rs->fields[uraian_modus]);
			$t->set_var("potensi_kur_bar", $rs->fields[potensi_kur_bar]);
			$t->set_var("perkiraan_nil_bar", $rs->fields[Perkiraan_nil_bar]);
			$t->set_var("kd_status", $rs->fields[kd_status]);
			$t->set_var("kode_tindaklanjut", $rs->fields[kode_tindaklanjut]);
			$t->set_var("nomor_tindaklanjut", $rs->fields[nomor_tindaklanjut]);
			$t->set_var("tgl_tindaklanjut", $rs->fields[tgl_tindaklanjut]);
			$t->set_var("ket", $rs->fields[ket]);
			$t->set_var("kd_kemasan", $rs->fields[kd_kemasan]);
			$t->set_var("uraian", $rs->fields[uraian]);
			$t->set_var("kode_kantor", $rs->fields[kode_kantor]);
			$t->set_var("nama_kantor", $rs->fields[nama_kantor]);
			$t->set_var("kode_kanwil", $rs->fields[kode_kanwil]);
			$t->set_var("kd_kasus", $rs->fields[kd_kasus]);
			$t->set_var("jenis_kasus", $rs->fields[jenis_kasus]);
			$t->set_var("kd_kom", $rs->fields[kd_kom]);
			$t->set_var("jenis_kom", $rs->fields[jenis_kom]);
			$t->set_var("kd_pelanggaran", $rs->fields[kd_pelanggaran]);
			$t->set_var("jenis_pelanggaran", $rs->fields[jenis_pelanggaran]);
			$t->set_var("pasal", $rs->fields[pasal]);
			$t->set_var("undang_undang", $rs->fields[undang_undang]);
			$t->set_var("kd_status", $rs->fields[kd_status]);
			$t->set_var("status", $rs->fields[status]);
			$t->set_var("kd_tindaklanjut", $rs->fields[kd_tindaklanjut]);
			$t->set_var("tindaklanjut", $rs->fields[tindaklanjut]);
			$t->set_var("no_ltp", $rs->fields[no_ltp]);
			$t->set_var("tgl_ltp", $rs->fields[tgl_ltp]);
			$t->set_var("tgl_lphp", $rs->fields[tgl_lphp]);
			$t->set_var("no_lphp", $rs->fields[no_lphp]);
			$t->set_var("no_lptp", $rs->fields[no_lptp]);
			$t->set_var("tgl_lptp", $rs->fields[tgl_lptp]);
			$t->set_var("ket_tindakan", $rs->fields[ket_tindakan]);
		
			$rs->MoveNext();
		}
	}else{
	 	 $t->set_var("MESSAGE", "Data Dengan ID : ".$id." Tidak Di Temukan [1]");
	}
}else{
	$t->set_var("MESSAGE", "Data Dengan ID : ".$id." Tidak Di Temukan [2]");
}


$sqlUraian = "select * from tb_uraian where no_kasus = ".$no_kasus;
$sqlJumUraian = "select count(*) from tb_uraian where no_kasus = ".$no_kasus;
$jumUraian = $q->GetOne($sqlJumUraian);
$t->set_var("jumUraian",$jumUraian);

//echo "uraian :".$sqlUraian;

$rs2 = $q->Execute($sqlUraian);
if ($rs2 and !$rs2->EOF) {
	while(!$rs2->EOF) {
		$no++ % 2 ? $t->set_var('row', 'row0'):$t->set_var('row', 'row1');
		$t->set_var("no", $no);					
		$t->set_var("id_uraian", $rs2->fields[kd_uraian]);					
		$t->set_var("kd_kom", $rs2->fields[kd_kom]);					
		$t->set_var("jumlah", $rs2->fields[jumlah]);					
		$t->set_var("kemasan", $rs2->fields[kemasan]);					
		$t->set_var("uraian_detail", $rs2->fields[uraian_detail]);
		$old_kd_kemasan = $rs2->fields[kemasan];

		// Menu Combobox Kemasan
		$id_cari_kemasan = $old_kd_kemasan;
		if ($rs = $q->Execute('select * from tb_kemasan order by kd_kemasan')) {
			$option = '';
			while (!$rs->EOF) {
				if ($rs->fields['kd_kemasan'] == $id_cari_kemasan) $selected = 'selected';
				else $selected = '';
				$option .= '<option value="'.$rs->fields['kd_kemasan'].'"'.$selected.'>'.$rs->fields['uraian']."</option>";
				$rs->MoveNext();
			}
		}
		$t->set_var('menuKemasan', $option);
		
		$t->parse("hdl_uraian_ada", "uraian_ada", true);					
		$rs2->MoveNext();
	}
}else{
    $t->parse("hdl_uraian_tidakada", "uraian_tidakada");				
	$t->set_var("MESSAGE", "Data Uraian Dengan ID : ".$no_kasus." Tidak Di Temukan [3]");
}	



// Menu Combobox Komoditi
//$id_cari_komoditi = $_GET['id_cari_komoditi'];
$id_cari_komoditi = $old_kd_kom;
if ($rs = $q->Execute('select * from tb_komoditi order by jenis_kom')) {
	$option = '';
	while (!$rs->EOF) {
		if ($rs->fields['kd_kom'] == $id_cari_komoditi) $selected = 'selected';
		else $selected = '';
		$option .= '<option value="'.$rs->fields['kd_kom'].'"'.$selected.'>'.$rs->fields['jenis_kom']."</option>\n";
		$rs->MoveNext();
	}
}
$t->set_var('menuKomoditi', $option);


// Menu Combobox Kantor / Kanwil
//$id_cari_kantor = $_GET['id_cari_kantor'];
$id_cari_kantor = $old_kd_kantor;
if ($rs = $q->Execute('select * from tb_kantor order by nama_kantor')) {
	$option = '';
	while (!$rs->EOF) {
		if ($rs->fields['kode_kantor'] == $id_cari_kantor) $selected = 'selected';
		else $selected = '';
		$option .= '<option value="'.$rs->fields['kode_kantor'].'"'.$selected.'>'.$rs->fields['nama_kantor']."</option>\n";
		$rs->MoveNext();
	}
}
$t->set_var('menuKanwil', $option);


// Menu Combobox Jenis Kasus
//$id_cari_jenis_kasus = $_GET['id_cari_jenis_kasus'];
$id_cari_jenis_kasus = $old_kd_kasus;
if ($rs = $q->Execute('select * from tb_kasus order by jenis_kasus')) {
	$option = '';
	while (!$rs->EOF) {
		if ($rs->fields['kd_kasus'] == $id_cari_jenis_kasus) $selected = 'selected';
		else $selected = '';
		$option .= '<option value="'.$rs->fields['kd_kasus'].'"'.$selected.'>'.$rs->fields['jenis_kasus']."</option>\n";
		$rs->MoveNext();
	}
}
$t->set_var('menuJenisKasus', $option);

$maxWord = 10;
if ($rs = $q->Execute('select * from tb_pelanggaran order by kd_pelanggaran')) {
	$checkBox = '';
	$i = 1;
	$checkExistArray = explode(";", $old_kd_pasal);
	while (!$rs->EOF) {

		if(in_array($rs->fields['kd_pelanggaran'], $checkExistArray)){
			$check = "checked";
		}else{
			$check = "";
		}

		$ringkasan = explode(" ",$rs->fields['jenis_pelanggaran']);
		$i++;
		if (sizeof($ringkasan)>$maxWord){
			$kata=0;
			$data_konten="";
			while ($kata<$maxWord) {
				$data_konten .= $ringkasan[$kata]." ";
				$kata++;
			}
			$checkBox .= '<input type="checkbox" name="'.$i.'" value="'.$rs->fields['kd_pelanggaran'].'" '.$check.'/> '.$data_konten.' ... <br>';
		} else {
			$checkBox .= '<input type="checkbox" name="'.$i.'" value="'.$rs->fields['kd_pelanggaran'].'" '.$check.'/> '.$rs->fields['jenis_pelanggaran'].' <br>';
		}
		
		$rs->MoveNext();
	}
}
$t->set_var('checkBoxPasal', $checkBox);
$t->set_var('countCheckBoxPasal', $i);

if ($rs = $q->Execute('select * from tb_kasus where root_id = 0 order by kd_kasus')) {
	$masterKasus = '';
	while (!$rs->EOF) {
		$masterKasus .= $rs->fields['kd_kasus'].":".$rs->fields['jenis_kasus']."|";
		$rs->MoveNext(); 
	}
}
$t->set_var('masterKasus', $masterKasus);

if ($rs = $q->Execute('select * from tb_kasus where root_id <> 0 order by kd_kasus')) {
	$subKasus = '';
	while (!$rs->EOF) {
		$subKasus .= $rs->fields['root_id'].":".$rs->fields['kd_kasus'].":".$rs->fields['jenis_kasus']."|"; 
		$rs->MoveNext();
	}
}
$t->set_var('subKasus', $subKasus);


// Menu Combobox Tindak Lanjut
$id_cari_tindak_lanjut = $old_kd_tindak_lanjut;
if ($rs = $q->Execute('select * from tb_tindaklanjut order by tindaklanjut')) {
	$option = '';
	while (!$rs->EOF) {
		if ($rs->fields['kd_tindaklanjut'] == $id_cari_tindak_lanjut) $selected = 'selected';
		else $selected = '';
		$option .= '<option value="'.$rs->fields['kd_tindaklanjut'].'"'.$selected.'>'.$rs->fields['tindaklanjut']."</option>\n";
		$rs->MoveNext();
	}
}
$t->set_var('menuTindakLanjut', $option);


// Menu Combobox Status Pelanggaran
$id_status_pelanggaran = $old_kd_status;
if ($rs = $q->Execute('select * from tb_status order by status')) {
	$option = '';
	while (!$rs->EOF) {
		if ($rs->fields['kd_status'] == $id_status_pelanggaran) $selected = 'selected';
		else $selected = '';
		$option .= '<option value="'.$rs->fields['kd_status'].'"'.$selected.'>'.$rs->fields['status']."</option>\n";
		$rs->MoveNext();
	}
}
$t->set_var('menuStatusPelanggaran', $option);


// Menu Combobox Sumber Informasi
$id_cari_sumber_informasi = $old_kd_sumberinfo;
if ($rs = $q->Execute('select * from tb_sumberinfo order by sumberinfo')) {
	$option = '';
	while (!$rs->EOF) {
		if ($rs->fields['kd_sumberinfo'] == $id_cari_sumber_informasi) $selected = 'selected';
		else $selected = '';
		$option .= '<option value="'.$rs->fields['kd_sumberinfo'].'"'.$selected.'>'.$rs->fields['sumberinfo']."</option>\n";
		$rs->MoveNext();
	}
}
$t->set_var('menuSumberInformasi', $option);


// Menu Combobox Tindakan
$id_cari_tindakan = $old_kd_tindakan;
if ($rs = $q->Execute('select * from tb_tindakan order by tindakan')) {
	$option = '';
	while (!$rs->EOF) {
		if ($rs->fields['kd_tindakan'] == $id_cari_tindakan) $selected = 'selected';
		else $selected = '';
		$option .= '<option value="'.$rs->fields['kd_tindakan'].'"'.$selected.'>'.$rs->fields['tindakan']."</option>\n";
		$rs->MoveNext();
	}
}
$t->set_var('menuTindakan', $option);

// Menu Combobox terhadap
$id_cari_terhadap = $old_kd_tindakan;
if ($rs = $q->Execute('select * from tb_terhadap order by terhadap')) {
	$option = '';
	while (!$rs->EOF) {
		if ($rs->fields['kd_terhadap'] == $id_cari_terhadap) $selected = 'selected';
		else $selected = '';
		$option .= '<option value="'.$rs->fields['kd_terhadap'].'"'.$selected.'>'.$rs->fields['terhadap']."</option>\n";
		$rs->MoveNext();
	}
}
$t->set_var('menuTerhadap', $option);

$act = $_POST['act'];
if($act == "update"){

	$no_kasus = $_POST['id']; 
	$id_kantor = $_POST['id_kantor']; 
	$no_sbp = $_POST['no_sbp']; 
	$tgl_sbp = $_POST['tgl_sbp']; 
	$tanggal_penindakan = $_POST['tanggal_penindakan']; 
	$lokasi_penindakan = str_replace("'", "",$_POST["lokasi_penindakan"]); 
	$penangkap = str_replace("'", "",$_POST["penangkap"]); 
	$pelaku = str_replace("'", "",$_POST["pelaku"]);
	$id_komoditi = $_POST['id_komoditi']; 
	$jumUraian = $_POST['jumUraian'];
	$uraian_jenis_kasus = $_POST['uraian_jenis_kasus']; 
	$kd_jenis_kemasan = 0; // need confirmation
	$uraian_pasal = str_replace("'", "",$_POST["uraian_pasal"]); 
	$uraian_modus = str_replace("'", "",$_POST["uraian_modus"]);
	$perkiraan_nil_bar = $_POST['perkiraan_nil_bar'];
	$potensi_kur_bar = $_POST['potensi_kur_bar'];
	$status_pelanggaran = $_POST['status_pelanggaran']; 
	$id_tindaklanjut = $_POST['id_tindaklanjut'];
	$nomor_tindaklanjut = $_POST['nomor_tindaklanjut']; 
	$tgl_tindaklanjut = $_POST['tgl_tindaklanjut']; 
	$ket = str_replace("'", "",$_POST["ket"]);
	$ket = str_replace("\"", "",$_POST["ket"]);
	$batal = $_POST['batal'];
	$id_kemasan = $_POST['id_kemasan'];
	$kd_sumberinfo = $_POST['id_sumberinformasi']; 

// Add 8 Aug 2012
	$no_ltp = $_POST['no_ltp'];
	$tgl_ltp = $_POST['tgl_ltp'];
	$no_lphp = $_POST['no_lphp'];
	$tgl_lphp = $_POST['tgl_lphp'];
	$id_tindakan = $_POST['id_tindakan'];
	$id_terhadap = $_POST['id_terhadap'];
	$ket_tindakan = str_replace("'", "",$_POST["ket_tindakan"]);
	
// Add 11 Aug 2012
	$countCheckBox = $_POST['countCheckBox'];
	$kdPasalValue = "0;";
	for($j=0; $j<=$countCheckBox; $j++){
		if(!empty($_POST[$j])){
			$kdPasalValue .= $_POST[$j].";";		
		}
	}
	$kdPasalValue .= "0";
//	echo $kdPasalValue;
	
// Add 12 Jan 2013
	$no_lptp = $_POST['no_lptp'];
	$tgl_lptp = $_POST['tgl_lptp'];
	
	$sqlUpdate = "update master set kd_kantor = ".$id_kantor.", nosbp = '".$no_sbp."', tgl_sbp = '".$tgl_sbp."', tgl_penindakan = '".$tanggal_penindakan."', lokasi = '".$lokasi_penindakan."', penangkap = '".$penangkap."', pelaku = '".$pelaku."', kd_kom = '".$id_komoditi."', kd_kasus = '".$uraian_jenis_kasus."', kd_jenis_kemasan = ".$kd_jenis_kemasan." ,kd_pasal = '".$kdPasalValue."', pasal = '".$uraian_pasal."', uraian_modus = '".$uraian_modus."', potensi_kur_bar = ".$potensi_kur_bar.", Perkiraan_nil_bar = ".$perkiraan_nil_bar.", kd_status = ".$status_pelanggaran." , kode_tindaklanjut = ".$id_tindaklanjut.", nomor_tindaklanjut = '".$nomor_tindaklanjut."', tgl_tindaklanjut = '".$tgl_tindaklanjut."', ket = '".$ket."', kd_sumberinfo = '".$kd_sumberinfo."',no_ltp = '".$no_ltp."',tgl_ltp = '".$tgl_ltp."',no_lphp = '".$no_lphp."',tgl_lphp = '".$tgl_lphp."', no_lptp = '".$no_lptp."', tgl_lptp = '".$tgl_lptp."',kd_tindakan = '".$id_tindakan."',kd_terhadap = '".$id_terhadap."',ket_tindakan = '".$ket_tindakan."' where no_kasus = ".$no_kasus;
	
//	echo "Update : ".$sqlUpdate;
	
	$rs = $q->Execute($sqlUpdate);
	if($rs){
		echo "<br><br> <br>";
		// Get No Kasus
		echo "No SBP ".$no_sbp;
	    $no_kasus = $q->GetOne("select no_kasus from master where nosbp = '".$no_sbp."'");
		echo " No Kasus ".$no_kasus;
		echo "<br>";
		// Loop sampai Banyak Uraian;
		for($i=1; $i<=$jumUraian; $i++){ 


			$uraian_jumlah = $_POST['uraian_jumlah_'.$i]; 
			$uraian_kemasan = $_POST['uraian_kemasan_'.$i]; 
			$uraian_detail = $_POST['uraian_detail_'.$i]; 
			$id_uraian = $_POST['id_uraian_'.$i]; 
			
			$sqlUpdateUraian = "update tb_uraian set kd_kom = ".$id_komoditi.", jumlah = ".$uraian_jumlah.", kemasan = '".$uraian_kemasan."',uraian_detail = '".$uraian_detail."' where kd_uraian = ".$id_uraian;
			echo "<br> Update : ".$sqlUpdateUraian;

			$rs = $q->Execute($sqlUpdateUraian);
		}

		header("location:index.php?appid=penangkapan&m=Update Success");
	}
}

$t->pparse("output", "handle");

$objek->footer();
?>
