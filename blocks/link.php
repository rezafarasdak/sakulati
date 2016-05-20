
<?
$t = buatTemplate(BLOCK_TEMPLATE_DIR);
$q = buatKoneksiDB('');

$t->set_file('hdl_link', 'related.html');
$t->set_block('hdl_link', 'elemen', 'hdl_elemen');
$t->set_block('hdl_link', 'kategori', 'hdl_kategori');

$t->set_var('hdl_kategori', '');

$rs=$q->Execute("select id, nama from link_kategori where show_as_main = 'yes'");
while ($rs and !$rs->EOF) {
 $rs1=$q->Execute("select url, judul from link where kategori ='".$rs->fields[0]."'");
 if ($rs1 and !$rs1->EOF) {
  $t->set_var('judul kategori', $rs1->fields[1]);
  $t->set_var('hdl_elemen', '');
  while ($rs1 and !$rs1->EOF) {
    $t->set_var('link', $rs1->fields(0));
    $t->set_var('judul', $rs1->fields(1));
    $t->parse('hdl_elemen', 'elemen', true);
	$rs1->MoveNext();
  }
  $t->parse('hdl_kategori', 'kategori', true);
  $rs->MoveNext();
 }
}
$t->pparse('out', 'hdl_link');
?>