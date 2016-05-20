<?
$objek->init();

$q = buatKoneksiDB();
$t = buatTemplate();
$wyswyg1 = makeWyswyg("isi",'');

$t->set_file("hdl_news", "news.html");

$t->set_block("hdl_news", "elemen", "hdl_elemen");
$t->set_block("hdl_news", "daftar", "hdl_daftar");
$t->set_block("hdl_news", "form", "hdl_form");

$t->set_var("hdl_elemen", "");
$t->set_var("hdl_daftar", "");
$t->set_var("hdl_form", "");



switch($_GET["action"]) {

    case "new" :
         $t->set_var("judul", "");
         $t->set_var("ringkasan", "");
         $t->set_var("id", "");
         $t->set_var("action", "add");
         $t->set_var("action title", "Tambah");
         $t->set_var("isi", $wyswyg1->getHtml());
         $t->parse("hdl_form", "form");
         break;

    case "edit" :
         $rs=$q->Execute("select id, judul, ringkasan, isi
                    from   artikel
                    where  id = '" .$_GET["id"]. "'");
         if ($rs and !$rs->EOF) {

			 $wyswyg2 = makeWyswyg("isi",$rs->fields("isi")); //get content

             $t->set_var("judul", $rs->fields("judul"));
             $t->set_var("ringkasan", $rs->fields("ringkasan"));
             $t->set_var("isi", $wyswyg2->getHtml());
			 $t->set_var("id", $rs->fields("id"));
             $t->set_var("action", "update");
             $t->set_var("action title", "Edit");
             $t->parse("hdl_form", "form");
         }
         else {
             $t->set_var("hdl_form", "Record dengan ID tersebut tidak ada");
         }
         break;

    case "del" :
         $rs=$q->Execute("delete
                    from   artikel
                    where  id = '".$_GET["id"]. "'");
         break;
}

if (isset($_POST["action"])) {
    if (!isset($_POST["batal"])) {
        $judul = trim($_POST["judul"]);
        $ringkasan = trim ($_POST["ringkasan"]);
        $isi = trim($_POST["isi"]);
        switch($_POST["action"]) {

            case "add" :
				$sqlAdd = "insert into   artikel (judul,ringkasan,isi,date) values ('$judul', '$ringkasan','$isi', now())";
				//echo "add : ".$sqlAdd;
			 $rs=$q->Execute($sqlAdd);
            break;

            case "update" :
             $rs=$q->Execute("update artikel
                        set    judul = '$judul',
                               ringkasan = '$ringkasan',
                               isi = '$isi',
                               date = now()
                        where  id = '". $_POST["id"]. "'");
            break;
        }
    }
}


if ($_GET["action"] != "new" && $_GET["action"] != "edit") {

    $page_num = 10;
    $rs=$q->Execute("select count(*) from artikel");
	if ($rs and !$rs->EOF){
		$page_count = (int) $rs->fields(0) / $page_num;
		if (($rs->fields(0) % $page_num) > 0) $page_count++;
		$strpage = "";
		for ($i = 1; $i <= $page_count; $i++) {
					$strpage .= "[<a href=\"".$PHP_SELF."?appid=admin&sub=news&start=".(($i-1)*$page_num+1) .
                   "\">".$i."</a>]&nbsp;\n";
		}
		
    }
	$t->set_var("paging", $strpage);

    if (!isset($_GET["start"])) $start = 0;

    else $start;

    $rs1=$q->Execute("select id, judul, ringkasan,date_format(date, '%d-%m-%Y') as tanggal, date_format(date, '%h:%i:%s') as waktu from   artikel order  by date limit  $start, $page_num ");
	
    if($rs and !$rs->EOF){
		while(!$rs1->EOF) {
			 $row++ % 2 ? $t->set_var('row', 'row0'):$t->set_var('row', 'row1');
			$t->set_var("waktu", $rs1->fields("tanggal") . "<br>". $rs1->fields("waktu"));
			$t->set_var("judul", $rs1->fields("judul"));
			$t->set_var("ringkasan", $rs1->fields("ringkasan"));
			$t->set_var("ID", $rs1->fields("id"));
			$t->parse("hdl_elemen",  "elemen", true);
			$rs1->MoveNext();
		}
    }
		
    $t->parse("hdl_daftar", "daftar");
	

}

$t->pparse("out", "hdl_news");

$objek->footer();
?>
