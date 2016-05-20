<?
# Inisialisasi
$objek->init();
$q = buatKoneksiDB('objek');
$t = buatTemplate();


$t->set_file("hdl_utama", "utama.html");
$t->pparse("out", "hdl_utama");

$objek->footer();

?>
