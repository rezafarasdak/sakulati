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

$arraySuratJalanStatus[0]="No"; // Belum buat surat jalan
$arraySuratJalanStatus[1]="Part"; // Surat Jalan Sebagian
$arraySuratJalanStatus[2]="Done"; // Surat Jalan Selesai

$arrayLokasiName[1] = "Jakarta";
$arrayLokasiName[2] = "Bandung";
$arrayLokasiName[3] = "Solo";

$arrayLokasiCode[1] = "JKT";
$arrayLokasiCode[2] = "BDG";
$arrayLokasiCode[3] = "SLO";

$arrayCurrencyName['IDR'] = "RUPIAH";
$arrayCurrencyName['USD'] = "DOLLAR";
$arrayCurrencyName['JPY'] = "YEN";
$arrayCurrencyName['EUR'] = "EURO";
$arrayCurrencyName['SGD'] = "SINGAPORE DOLLAR";

$arraySalesOrderStatus['N'] = "New";
$arraySalesOrderStatus['R'] = "Remove";
$arraySalesOrderStatus['P'] = "Pelunasan Created";
$arraySalesOrderStatus['PP'] = "Pelunasan Created Partial";
$arraySalesOrderStatus['D'] = "DO Created";
$arraySalesOrderStatus['DP'] = "DO Partial";
$arraySalesOrderStatus['C'] = "Canceled";
$arraySalesOrderStatus['I'] = "Invoice Created";
$arraySalesOrderStatus['IP'] = "Invoice  Partial";

$arrayInvoiceStatus['N'] = "New";
$arrayInvoiceStatus['R'] = "Removed";
$arrayInvoiceStatus['P'] = "Pelunasan Created";
$arrayInvoiceStatus['PP'] = "Partial";

$arrayTrialBalance['N'] = "New";
$arrayTrialBalance['O'] = "Open";
$arrayTrialBalance['C'] = "Close";

$arrayPelunasanStatus['N'] = "New";
$arrayPelunasanStatus['P'] = "Pelunasan Created";
$arrayPelunasanStatus['PP'] = "Partial";
$arrayPelunasanStatus['R'] = "Removed";

$arrayPembayaranStatus['N'] = "New";
$arrayPembayaranStatus['P'] = "Pembayaran Created";
$arrayPembayaranStatus['PP'] = "Partial";
$arrayPembayaranStatus['R'] = "Removed";

$arrayPurchaseOrderStatus['N'] = "New";
$arrayPurchaseOrderStatus['R'] = "Remove";
$arrayPurchaseOrderStatus['D'] = "GR Created";
$arrayPurchaseOrderStatus['DP'] = "GR Partial";
$arrayPurchaseOrderStatus['C'] = "Canceled";
$arrayPurchaseOrderStatus['I'] = "Invoice Created";
$arrayPurchaseOrderStatus['IP'] = "Invoice Partial";

$arrayDeliveryOrderStatus['N'] = "New";
$arrayDeliveryOrderStatus['C'] = "Canceled";
$arrayDeliveryOrderStatus['I'] = "Invoice Created";

$arrayFieldPencarian['po_no'] = "Purchase Order Number";
$arrayFieldPencarian['so_no'] = "Sales Order Number";
$arrayFieldPencarian['in_no'] = "Invoice Number";
$arrayFieldPencarian['nama_persh'] = "Nama Perusahaan";
$arrayFieldPencarian['nama'] = "PIC";
$arrayFieldPencarian['remark'] = "Remark";
$arrayFieldPencarian['voucher_no'] = "Voucher Number";


$perusahaan['name'] = "SAKULATI";
$perusahaan['address'] = "JL.RAYA TENGAH RT.003/12 NO.15 GEDONG, PASAR REBO";
$perusahaan['address2'] = "JAKARTA TIMUR 13760";
$perusahaan['tax'] = "01.729.925.6-007.000";
$perusahaan['telp'] = "(021) 8408171";
$perusahaan['telp2'] = "(021) 87795264";
$perusahaan['fax'] = "(021) 8411831";
$perusahaan['npwp'] = "01.79.925.6-007.000";
$perusahaan['dirut'] = "Yersi D. Sayangbati";
$perusahaan['dirutLabel'] = "Direktur Utama";
$perusahaan['managerAccounting'] = "Mustofa";
$perusahaan['managerAccountingLabel'] = "Accounting Manager";
$perusahaan['managerPurchasing'] = "Tato Hendarto";
$perusahaan['managerPurchasingId'] = "7";
$perusahaan['bankNameIDR_JKT'] = "Bank Mandiri";
$perusahaan['bankNameIDR_SLO'] = "Bank Mandiri";
$perusahaan['bankNameIDR_BDG'] = "Bank Mandiri";
$perusahaan['bankNameUSD_JKT'] = "Bank Mandiri";
$perusahaan['bankNameUSD_SLO'] = "Bank Mandiri";
$perusahaan['bankNameUSD_BDG'] = "Bank Mandiri";
$perusahaan['accountHolderIDR_JKT'] = "PT. Bhakti Pancawarna";
$perusahaan['accountHolderIDR_SLO'] = "PT. Bhakti Pancawarna";
$perusahaan['accountHolderIDR_BDG'] = "PT. Bhakti Pancawarna";
$perusahaan['accountHolderUSD_JKT'] = "PT. Bhakti Pancawarna";
$perusahaan['accountHolderUSD_SLO'] = "PT. Bhakti Pancawarna";
$perusahaan['accountHolderUSD_BDG'] = "PT. Bhakti Pancawarna";
$perusahaan['accountNoIDR_JKT'] = "129.009.8050.384";
$perusahaan['accountNoIDR_SLO'] = "138.000.4386.152";
$perusahaan['accountNoIDR_BDG'] = "130.000.4779.073";
$perusahaan['accountNoUSD_JKT'] = "129.009.8048.883";
$perusahaan['accountNoUSD_SLO'] = "138.000.4386.178";
$perusahaan['accountNoUSD_BDG'] = "130.001.3552.388";

$perusahaan['POSignName1_JKT'] = "Rusmiati";
$perusahaan['POSignTitle1_JKT'] = "Finance";
$perusahaan['POSignName2_JKT'] = "Tato Hendarto";
$perusahaan['POSignTitle2_JKT'] = "Manager";
$perusahaan['POSignName3_JKT'] = "Rahadian Reza";
$perusahaan['POSignTitle3_JKT'] = "Direktur";
$perusahaan['POSignName1_BDG'] = "Dian";
$perusahaan['POSignTitle1_BDG'] = "Finance";
$perusahaan['POSignName2_BDG'] = "Diki AD";
$perusahaan['POSignTitle2_BDG'] = "Manager";
$perusahaan['POSignName3_BDG'] = "Rahadian Reza";
$perusahaan['POSignTitle3_BDG'] = "Direktur";
$perusahaan['POSignName1_SLO'] = "Eka";
$perusahaan['POSignTitle1_SLO'] = "Finance";
$perusahaan['POSignName2_SLO'] = "Tato Hendarto";
$perusahaan['POSignTitle2_SLO'] = "Manager";
$perusahaan['POSignName3_SLO'] = "Rahadian Reza";
$perusahaan['POSignTitle3_SLO'] = "Direktur";

$perusahaan['DOSignName1_JKT'] = "Deden S.";
$perusahaan['DOSignTitle1_JKT'] = "Bagian Gudang";
$perusahaan['DOSignName2_JKT'] = "Jaja Z";
$perusahaan['DOSignTitle2_JKT'] = "Kepala Gudang";
$perusahaan['DOSignName1_BDG'] = "Dian";
$perusahaan['DOSignTitle1_BDG'] = "Bagian Gudang";
$perusahaan['DOSignName2_BDG'] = "Haris";
$perusahaan['DOSignTitle2_BDG'] = "Kepala Gudang";
$perusahaan['DOSignName1_SLO'] = "Eka";
$perusahaan['DOSignTitle1_SLO'] = "Bagian Gudang";
$perusahaan['DOSignName2_SLO'] = "M. Bernado";
$perusahaan['DOSignTitle2_SLO'] = "Kepala Gudang";

$perusahaan['PVSignName1_JKT'] = "Hevi P.";
$perusahaan['PVSignTitle1_JKT'] = "Finance";
$perusahaan['PVSignName2_JKT'] = "Mustofa";
$perusahaan['PVSignTitle2_JKT'] = "Manager";
$perusahaan['PVSignName3_JKT'] = "Agus S.";
$perusahaan['PVSignTitle3_JKT'] = "Direktur";


$setting['lineTableBreak'] = 11;
$setting['lineTableBreakInv'] = 11;
$setting['maxBulkPrint'] = 40;


$arrayJurnal['C'] = "Credit";
$arrayJurnal['D'] = "Debit";
$arrayJurnal['ar'] = "A / R";
$arrayJurnal['ap'] = "A / P";
$arrayJurnal['pl'] = "Bank Receipt";
$arrayJurnal['pm'] = "Bank Payment";
$arrayJurnal['jv'] = "Voucher";

$arrayPPNStatus[0]="Not Include"; // Belum buat surat jalan
$arrayPPNStatus[1]="Include"; // Surat Jalan Sebagian

// Paging
$item_per_page = 15;

?>