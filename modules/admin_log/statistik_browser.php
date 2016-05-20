<?
# Inisialisasi
$objek->init();

$q = buatKoneksiDB();

# Isi halaman
$t = buatTemplate();
$t->set_file("handle_search_list", "statistik_browser.html");
$t->set_block("handle_search_list", "elemen", "hdl_elemen");
$t->set_block("handle_search_list", "tidakada", "hdl_empty");
$t->set_var("hdl_empty", "");
$t->set_var("hdl_elemen", "");
$t->set_var("CARI", $cari);

// Message
$message = $_GET['m'];
if (isset($message) && ($message != "")) {
	$objek->nextmessage($message);
}

// Order ny / Pengurutan
$urut = $_GET['order'];
if (isset($urut) && ($urut != "")) {
	$order = " order by ".$urut;
}else{
	$order = " order by count(*) desc";	
}

// Pencarian
$tgl_dari = $_GET['tgl_dari'];
$tgl_sampai = $_GET['tgl_sampai'];
if (isset($tgl_dari) && ($tgl_dari != "")) {
	$t->set_var("MESSAGE", "Hasil pencarian berdasarkan No SBP Mulai : \"$tgl_dari\" Sampai Dengan \"$tgl_sampai\"");

	$sql = "select tk.kd_kasus,tk.jenis_kasus,tk.root_id,count(nosbp) as jum from master m right join tb_kasus tk on m.kd_kasus = tk.kd_kasus where tk.root_id > 0 and m.tgl_sbp between '$tgl_dari' and '$tgl_sampai' ".$whereKasus." group by tk.kd_kasus, tk.root_id ".$order."";
	
}else{
	$t->set_var("MESSAGE", "Statistik Browser");

	$sql = "select browser_platform,browser_name,count(*) as jum from log where browser_name <> '' group by browser_platform,browser_name ".$order;
}

//echo $sql;
// GRAPH

$strXML  = "<chart caption='Browser' xAxisName='Browser Name' yAxisName='Jumlah' showValues='0' formatNumberScale='0' showBorder='1' labelDisplay='Rotate' slantLabels='2'>";
		
$total = 0;
// Kolom Bawah
$rs = $q->Execute($sql);
if ($rs and !$rs->EOF) {
	$count = 0;
	$nomor = 0;
	while(!$rs->EOF) {
		++$nomor;
		$no++ % 2 ? $t->set_var('row', 'row0'):$t->set_var('row', 'row1');
		$t->set_var("nomor", $nomor);
		$t->set_var("browser_name", $rs->fields['browser_name']);
		$t->set_var("browser_platform", $rs->fields['browser_platform']);
		$t->set_var("jumlah", $rs->fields['jum']);
		
		$total = $total + $rs->fields['jum'];
		$strXML .= "<set label='".$rs->fields['browser_name']." - ".$rs->fields['browser_platform']."' value='".$rs->fields['jum']."' />";

		$t->parse("hdl_elemen", "elemen", true);
		$rs->MoveNext();
	}
}
else $t->parse("hdl_empty", "tidakada");

$t->set_var("totalAll", $total);

$t->parse("HASIL PENCARIAN", "handle_search_list");


$strXML .= "</chart>";

	//Create the chart - Column 3D Chart with data from strXML variable using dataXML method
	//echo renderChartHTML("../../FusionCharts/Column3D.swf", "", $strXML, "myNext", 500, 500, false);
$t->set_var("chart", renderChartHTML("frw/lib/FusionCharts/Column3D.swf", "", $strXML, "myNext", 540, 400, false));	

$t->pparse("output", "handle_search_list");

$objek->footer();
?>
