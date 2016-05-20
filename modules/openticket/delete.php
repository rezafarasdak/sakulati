<?
# Inisialisasi
$objek->init();

$q = buatKoneksiDB('tindakan');

# Isi halaman
$t = buatTemplate();

$t->set_file("handle_edit", "delete.html");
$t->set_block("handle_edit", "ada", "hdl_ada");
$t->set_block("handle_edit", "tidakada", "hdl_tidakada");
$t->set_block("handle_edit", "uraian_ada", "hdl_uraian_ada");
$t->set_block("handle_edit", "uraian_tidakada", "hdl_uraian_tidakada");
$t->set_var("hdl_uraian_tidakada", "");
$t->set_var("hdl_uraian_ada", "");
$t->set_var("hdl_tidakada", "");
$t->set_var("hdl_ada", "");

$id = $_GET['id'];
if (isset($id) && ($id != "")) {
$sql = "SELECT * FROM master as m left join tb_kemasan as kms on m.kd_jenis_kemasan = kms.kd_kemasan left join tb_kantor as knt on m.kd_kantor = knt.kode_kantor left join tb_kasus as kss on m.kd_kasus = kss.kd_kasus left join tb_komoditi as kmd on m.kd_kom = kmd.kd_kom left join tb_status as sts on m.kd_status = sts.kd_status left join tb_tindaklanjut as tdl on m.kode_tindaklanjut = tdl.kd_tindaklanjut where m.no_kasus = '".$id."' limit 1";
	$rs = $q->Execute($sql);
	if ($rs and !$rs->EOF) {
		while(!$rs->EOF) {
			$t->set_var("MESSAGE", "Detail Data Dengan No SBP : ".$rs->fields[nosbp]);
			$no_kasus = $rs->fields['no_kasus'];
			$t->set_var("no_kasus", $rs->fields[no_kasus]);
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

			$t->set_var("kd_pasal", $rs->fields[kd_pasal]);
			$pasalArray = explode(";", $rs->fields[kd_pasal]);
			for($i=0;$i<count($pasalArray);$i++){
				$detailPasal .= $q->GetOne("select jenis_pelanggaran from tb_pelanggaran where kd_pelanggaran = '".$pasalArray[$i]."'");
				if($pasalArray[$i] != '0'){
					$detailPasal .= ". <br>";
				}
			}			
			$t->set_var("detailPasal", $detailPasal);

			$t->set_var("pasal", $rs->fields[pasal]);
			$t->set_var("uraian_modus", $rs->fields[uraian_modus]);
			$t->set_var("potensi_kur_bar", number_format($rs->fields[potensi_kur_bar],0,",","."));
			$t->set_var("perkiraan_nil_bar", number_format($rs->fields[Perkiraan_nil_bar],0,",","."));
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

// ADD 8 Aug 2012
			$t->set_var("no_ltp", $rs->fields[no_ltp]);
			$t->set_var("tgl_ltp", $rs->fields[tgl_ltp]);
			$t->set_var("no_lphp", $rs->fields[no_lphp]);
			$t->set_var("tgl_lphp", $rs->fields[tgl_lphp]);
			$t->set_var("kd_tindakan", $rs->fields[kd_tindakan]);
			$t->set_var("kd_terhadap", $rs->fields[kd_terhadap]);
			$t->set_var("ket_tindakan", $rs->fields[ket_tindakan]);

// ADD 12 Jan 2013
			$t->set_var("no_lptp", $rs->fields[no_lptp]);
			$t->set_var("tgl_lptp", $rs->fields[tgl_lptp]);
					
			$sumberinfo = $q->GetOne("select sumberinfo from tb_sumberinfo where kd_sumberinfo = '".$rs->fields[kd_sumberinfo]."'");					
			$t->set_var("sumberinfo", $sumberinfo);					

			$tindakan = $q->GetOne("select tindakan from tb_tindakan where kd_tindakan = '".$rs->fields[kd_tindakan]."'");					
			$t->set_var("tindakan", $tindakan);					

			$terhadap = $q->GetOne("select terhadap from tb_terhadap where kd_terhadap = '".$rs->fields[kd_terhadap]."'");					
			$t->set_var("terhadap", $terhadap);					

			$masterKasus = $q->GetOne("select jenis_kasus from tb_kasus where kd_kasus = '".$rs->fields[root_id]."'");					
			$t->set_var("masterKasus", $masterKasus);				
			$t->parse("hdl_ada", "ada", true);
		
			$rs->MoveNext();
		}
	}else{
		 $t->parse("hdl_tidakada", "tidakada");
	 	 $t->set_var("MESSAGE", "Data Dengan ID : ".$id." Tidak Di Temukan [1]");
	}
}else{
	$t->parse("hdl_tidakada", "tidakada");
	$t->set_var("MESSAGE", "Data Dengan ID : ".$id." Tidak Di Temukan [2]");
}

$sqlUraian = "select * from tb_uraian where no_kasus = ".$no_kasus;
//echo "uraian :".$sqlUraian;
$rs2 = $q->Execute($sqlUraian);
if ($rs2 and !$rs2->EOF) {
	while(!$rs2->EOF) {
		$no++ % 2 ? $t->set_var('row', 'row0'):$t->set_var('row', 'row1');
		$t->set_var("no", $no);					
		$t->set_var("kd_kom", $rs2->fields[kd_kom]);					
		$t->set_var("jumlah", $rs2->fields[jumlah]);					
		$t->set_var("kemasan", $rs2->fields[kemasan]);					
		$t->set_var("uraian_detail", $rs2->fields[uraian_detail]);
		$t->parse("hdl_uraian_ada", "uraian_ada", true);					
		$rs2->MoveNext();
	}
}else{
	 $t->parse("hdl_uraian_tidakada", "uraian_tidakada");				
}	

$id_delete = $_POST['id_delete'];
$act = $_POST['act'];
if($act == "delete"){
	$sqlDelete = "delete from master where no_kasus = ".$id_delete;
	$rs2 = $q->Execute($sqlDelete);
	if($rs2){
		$objek->userLog('Delete Ticket '.$id_delete);
		header("location:index.php?appid=penangkapan&m=Delete Success");	
	}
}

$t->pparse("output", "handle_edit");

$objek->footer();
?>
