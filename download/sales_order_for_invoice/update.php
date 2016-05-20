<?
# Inisialisasi
$objek->init();
error_reporting(0);
$error = 0;
$user = $objek->user->profil;
$lokasi_id = $user['lokasi_id'];
$user_id = $user['userid'];

$q = buatKoneksiDB();

# Isi halaman
$t = buatTemplate();

$t->set_file("handle", "edit.html");

// ==== AFTER SUBMIT BUTTON ==== //
$act = $_POST['act'];
if($act == "save"){

	// Get All Post Data
	$in_id = $objek->dec($_POST['in_id']);
	$date = $_POST['date'];
	$due_date = $_POST['due_date'];
	$discount = str_replace(",","",$_POST['discount']);
	$remark = $_POST['remark'];

	
	
	$oldDipungut = $q->GetOne("select dipungut from invoice where id = ".$in_id);
	$dipungut = $_POST['dipungut'];
	
	$sub_total = str_replace(",","",$_POST['sub_total']);
	$otherAmount = str_replace(",","",$_POST['otherAmount']);

	if($dipungut == "yes"){
		$tax = $sub_total * 10 / 100;
		$kodePajak = "010";
	}else{
		$tax = 0;
		$kodePajak = "070";	
	}

	$amount = $sub_total + $tax + $otherAmount - $discount;
	$faktur = $kodePajak.substr($_POST['faktur'],3);

	$currency = $_POST['currency'];
	$rateId = $q->GetOne("select id from rate where currency = '".$currency."' and start_date <= '".$date."' and valid_date >= '".$date."' order by valid_date asc");
		
	// Update Table Order
	$sqlUpdate = "update invoice set date = '".$date."', due_date = '".$due_date."', remark = '".$remark."', faktur = '".$faktur."', amount = '".$amount."', other_charge = '".$otherAmount."', discount = '".$discount."', dipungut = '".$dipungut."', tax = '".$tax."', rate_id = '".$rateId."' where id = ".$in_id;
	$objek->debugLog("Updating Invoice ".$sqlUpdate);

	// Remove Old Jurnal & Posting Ulang, Bila Di pungut status nya berbeda dengan yang sebelumnya.
	if($oldDipungut == $dipungut){
		$objek->debugLog("Old & New Data Di Pungut Sama, Tidak Perlu Posting Ulang");
	}else{

		// Update Jurnal Status jadi Removed.
		$sqlUpdateJurnal = "update jurnal set status = 'R' where referal_id = '".$in_id."' and type = 'ar'";
		$objek->debugLog("Di Pungut Status Berubah, Updating Jurnal Status To Delete... ".$sqlUpdateJurnal);
		$q->Execute($sqlUpdateJurnal);
		
		// Posting Ulang Setiap Delivery Order..
		$objek->debugLog("Di Pungut Status Berubah, Posting Ulang... ");
		$sql2 = "select do.* from invoice_detail id join delivery_order do on id.do_id = do.id
				where id.invoice_id = '".$in_id."' 
				order by do.id";

		$rs2 = $q->Execute($sql2);
		if ($rs2 and !$rs2->EOF) {
			while(!$rs2->EOF) {

				$id_do = $rs2->fields['id'];
				$in_no = $q->GetOne("select in_no from invoice where id = ".$in_id);
				$objek->debugLog("Di Pungut Status Berubah, Posting ".$id_do);
				
				$objek->postToJournalFromDeliveryOrder($id_do, $in_no, $in_id, $dipungut);

				$rs2->MoveNext();
			}
		}		
		$objek->debugLog("Di Pungut Status Berubah, Posting Done ");
		
	}
	


	$rs = $q->Execute($sqlUpdate);
	if($rs){
			$objek->userLog('Update Invoice '.$in_id);
			header("location:index.php?appid=invoice_report&m=Update Success&sub=detail&id=".$objek->enc($in_id));
	}else{
			header("location:index.php?appid=invoice_report&m=Update Invoice Fail, Please Check The Parameter");					
	}
}

$t->pparse("output", "handle");
$objek->footer();
?>