<?
// variabel waktu
date_default_timezone_set("Asia/Jakarta");
$waktu=getdate();
$now = date("Y-m-j H:i:s");

/*$mainConf['site']['version'] = "2.00-alpha";
$mainConf['site']['siteName'] = "kismin";
$mainConf['site']['path'] = "/home/anakluar/public_html/bhakti/frw";
$mainConf['site']['module_path'] = "modules";
$mainConf['site']['block_path'] = "blocks";
$mainConf['site']['log_folder_name'] = "/home/anakluar/public_html/bhakti/frw/log";
$mainConf['site']['log_file_name'] = "financeSystem.log";
$mainConf['site']['key'] = "financeSystemV3RezaKey";
$mainConf['site']['siteID'] = "3";
$mainConf['site']['url'] = "http://bhakti.indowebapps.com";
$mainConf['site']['default_app'] = "utama";
$mainConf['site']['default_user_app'] = "utama";
$mainConf['site']['debug'] = "yes";

$mainConf['database']['host'] = "localhost";
$mainConf['database']['username'] = "anakluar_bhakti";	 
$mainConf['database']['password'] = "";
$mainConf['database']['name'] = "anakluar_bhakti";*/

$arraybulan[0]="Januari";
$arraybulan[1]="Februari";
$arraybulan[2]="Maret";
$arraybulan[3]="April";
$arraybulan[4]="Mei";
$arraybulan[5]="Juni";
$arraybulan[6]="Juli";
$arraybulan[7]="Agustus";
$arraybulan[8]="September";
$arraybulan[9]="Oktober";
$arraybulan[10]="November";
$arraybulan[11]="Desember";

$arrayBulan['01']="Januari";
$arrayBulan['02']="Februari";
$arrayBulan['03']="Maret";
$arrayBulan['04']="April";
$arrayBulan['05']="Mei";
$arrayBulan['06']="Juni";
$arrayBulan['07']="Juli";
$arrayBulan['08']="Agustus";
$arrayBulan['09']="September";
$arrayBulan['10']="Oktober";
$arrayBulan['11']="November";
$arrayBulan['12']="Desember";

$arrayBulan3format['01']="Jan";
$arrayBulan3format['02']="Feb";
$arrayBulan3format['03']="Mar";
$arrayBulan3format['04']="Apr";
$arrayBulan3format['05']="Mei";
$arrayBulan3format['06']="Jun";
$arrayBulan3format['07']="Jul";
$arrayBulan3format['08']="Aug";
$arrayBulan3format['09']="Sep";
$arrayBulan3format['10']="Okt";
$arrayBulan3format['11']="Nov";
$arrayBulan3format['12']="Des";

$arrayBulanComment[01]="JAN";
$arrayBulanComment[02]="FEB";
$arrayBulanComment[03]="MAR";
$arrayBulanComment[04]="APR";
$arrayBulanComment[05]="MEI";
$arrayBulanComment[06]="JUN";
$arrayBulanComment[07]="JUL";
$arrayBulanComment[08]="AUG";
$arrayBulanComment[09]="SEP";
$arrayBulanComment[10]="OKT";
$arrayBulanComment[11]="NOV";
$arrayBulanComment[12]="DES";

// Array Type & Status For Open Ticket
$OpenTicketType['k'] = "Koreksi Data";
$OpenTicketType['l'] = "Login Problem";
$OpenTicketType['q'] = "Pertanyaan";
$OpenTicketType['o'] = "Other";

$OpenTicketStatus['p'] = "Pending";
$OpenTicketStatus['o'] = "Open";
$OpenTicketStatus['c'] = "Close";

// Karakter yang dilarang
$vowels = array("'", "<", ">");

$cara_kirim_array = array("take" => "Ambil Sendiri",
				 		  "delivery"  => "Kirim Pakai Mobil Sendiri",
				 		  "delivery_sewa" => "Kirim Pakai Mobil Sewa");

$arrayStatus[0]="Not Active"; // Belum buat surat jalan
$arrayStatus[1]="Active"; // Surat Jalan Sebagian


$perusahaan['name'] = "SAKULATI";
$perusahaan['address'] = "JL.RAYA TENGAH RT.003/12 NO.15 GEDONG, PASAR REBO";
$perusahaan['address2'] = "JAKARTA TIMUR 13760";
$perusahaan['tax'] = "01.729.925.6-007.000";
$perusahaan['telp'] = "(021) 8408171";
$perusahaan['telp2'] = "(021) 87795264";
$perusahaan['fax'] = "(021) 8411831";
$perusahaan['npwp'] = "01.79.925.6-007.000";


$setting['lineTableBreak'] = 11;
$setting['lineTableBreakInv'] = 11;
$setting['maxBulkPrint'] = 40;

// Paging
$item_per_page = 15;

?>