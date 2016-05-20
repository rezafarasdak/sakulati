<?
# Inisialisasi
$objek->init();
//error_reporting(0);
$error = 0;
$user = $objek->user->profil;
$lokasi_id = $user['lokasi_id'];
$user_id = $user['userid'];

$q = buatKoneksiDB();

# Isi halaman
$t = buatTemplate();

$t->set_file("handle", "add.html");
$t->set_block("handle", "ada", "hdl_ada");
$t->set_block("handle", "tidakada", "hdl_tidakada");
$t->set_block("handle", "order_detail_ada", "hdl_order_detail_ada");
$t->set_block("handle", "order_detail_tidakada", "hdl_order_detail_tidakada");
$t->set_var("hdl_tidakada", "");
$t->set_var("hdl_ada", "");
$t->set_var("hdl_order_detail_tidakada", "");
$t->set_var("hdl_order_detail_ada", "");

// ==== AFTER SUBMIT BUTTON ==== //
$act = $_POST['act'];
if($act == "save"){

// Get All Post Data

	// Get All Post Data
	$date = $_POST['date'];
	$due_date = $_POST['due_date'];
	$discount = str_replace(",","",$_POST['discount']);
	$remark = $_POST['remark'];
	$dipungut = $_POST['dipungut'];
	$so_id = $objek->dec($_POST['so_id']);
	$jumlah_do = $_POST['jumlah_do'];
	$sub_total = str_replace(",","",$_POST['sub_total']);
	
	// Check Di Pungut Status, bila Tidak Di Pungut, Tidak Menghitung Pajak
	if($dipungut == "no"){
		$tax = 0;
	}else{
		$tax = $sub_total * 10 / 100;		
	}
	
	$otherAmount = str_replace(",","",$_POST['otherAmount']);
	$amount = $sub_total + $tax + $otherAmount - $discount;

	$currency = $_POST['currency'];
	$rateId = $q->GetOne("select id from rate where currency = '".$currency."' and start_date <= '".$date."' and valid_date >= '".$date."' order by valid_date asc");
	
	$objek->debugLog("select id from rate where currency = '".$currency."' and start_date <= '".$date."' and valid_date >= '".$date."' order by valid_date asc ==> [".$rateId."]");
		
	$original_amount = $amount;	
	if ($currency <> "IDR"){
		$idrAmount = $q->GetOne("select amount from rate where id = '".$rateId."' order by valid_date desc");
		$amount = $amount * $idrAmount;
	}

	$tahun = substr($date,2,2);
	$bulan = substr($date,5,2);
	
	// ==========--- Generate Invoice Number Start
//	$counting_trx_today = $q->GetOne("select count(*) from `invoice` where in_no REGEXP '".$tahun."$'");
	$counting_trx_today = $q->GetOne("select counter_min from counter where name = 'invoice'");
	
	$sequence = "";
	if($counting_trx_today >= 9999){
		$sequence = $counting_trx_today + 1;
	}else if($counting_trx_today >= 999){
		$sequence = "0".($counting_trx_today + 1);
	}else if($counting_trx_today >= 99){
		$sequence = "00".($counting_trx_today + 1);
	}else if($counting_trx_today >= 9){
		$sequence = "000".($counting_trx_today + 1);	
	}else{
		$sequence = "0000".($counting_trx_today + 1);
	}
	$in_no = $sequence."BP".$bulan.$tahun; //00123BP0815	
	$objek->debugLog("Generated Invoice Number : ".$in_no);
	
	//  ==========--- Generate Invoice Number END
	
	$faktur = $objek->generateFaktur($dipungut, $tahun);

	// Insert Into Table Order
	$sqlInsert = "insert into invoice (in_no, sales_order_id, datetime, status, date, due_date, user_id, remark, discount, amount, sub_total, tax, other_charge, original_amount, faktur, dipungut, rate_id) 
				values ('".$in_no."', '".$so_id."', now(), 'N', '".$date."', '".$due_date."', '".$user_id."', '".$remark."', '".$discount."', '".$amount."', '".$sub_total."', '".$tax."', '".$otherAmount."', '".$original_amount."', '".$faktur."', '".$dipungut."', ".$rateId.")";
	$objek->debugLog("Inserting Invoice ".$sqlInsert);			
 
	$rs = $q->Execute($sqlInsert);
	if($rs){
		$objek->userLog('Add Invoice '.$in_no);
		$invoice_id = $q->GetOne("select id from invoice where in_no = '".$in_no."' order by id desc");
		
		$q->Execute("update counter set counter_min = counter_min +1 where name = 'invoice'");
				
		$no = 0;
		$tuntas = 0;
		$selectedAmount = 0;
		// Looping Banyak Barang
		for($i=1; $i<=$jumlah_do; $i++){
			$id_do = $objek->dec($_POST['id_do_'.$i]);
			
			if(!empty($id_do) || $id_do != ""){
				
				// Save Into Table Order Detail
				$sqlInsertOrderDetail = "insert into invoice_detail (invoice_id, do_id) values ('".$invoice_id."' ,'".$id_do."')";
				$objek->debugLog("Inserting Invoice Detail ".$i.". ".$sqlInsertOrderDetail);	
				$rs2 = $q->Execute($sqlInsertOrderDetail);
				
				// Posting..
				$objek->postToJournalFromDeliveryOrder($id_do, $in_no, $invoice_id, $dipungut);

				$sqlUpdateDeliveryOrder = "update `delivery_order` set status = 'I' where id = '".$id_do."'";
				$objek->debugLog("Updating Delivery Order ".$i.". ".$sqlUpdateDeliveryOrder);	
				$rs5 = $q->Execute($sqlUpdateDeliveryOrder);

			}else{
				// Not All Delivery Order Selected		
				$tuntas = 1;
				
			}
		}
		
		
		// Check SO Status, Masih ada barang yang belum dikirim atau belum.
		$salesOrderStatus = $q->GetOne("select status from sales_order where id = '".$so_id."'");
		if($salesOrderStatus == "DP"){
			$tuntas = 2;	
		}
		$objek->debugLog("SO Status = [".$salesOrderStatus."]");
		
		
		// Cek, Apabila sudah tuntas, Update surat_jalan_status
		if($tuntas == 0){
			$sqlUpdate = "update `sales_order` set status = 'I' where id = '".$so_id."'";
			$rs3=$q->Execute($sqlUpdate);

			$sqlUpdate2 = "update `invoice` set status = 'I', amount = ".$selectedAmount-$discount." where id = '".$invoice_id."'";
			$rs4=$q->Execute($sqlUpdate2);
			
		}elseif($tuntas == 1){
			$sqlUpdate = "update `sales_order` set status = 'IP' where id = '".$so_id."'";
			$rs3=$q->Execute($sqlUpdate);

			$sqlUpdate2 = "update `invoice` set status = 'IP', amount = ".$selectedAmount-$discount." where id = '".$invoice_id."'";
			$rs4=$q->Execute($sqlUpdate2);
			
		}else{
			
			$sqlUpdate = "SO Still Not Complete, SO Status Still [". $salesOrderStatus."]";

			$sqlUpdate2 = "update `invoice` set status = 'IP', amount = ".$selectedAmount-$discount." where id = '".$invoice_id."'";
			$rs4=$q->Execute($sqlUpdate2);
			
		}
		
		$objek->debugLog("Tuntas Status = [".$tuntas."]");
		$objek->debugLog("Updating Sales Order Status [".$sqlUpdate."]");
		$objek->debugLog("Updating Invoice Amount & Status [".$sqlUpdate2."]");

		if($rs2){
			// Redirect to Report List
			header("location:index.php?appid=invoice_report&sub=detail&id=".$objek->enc($invoice_id));
		}else{
			header("location:index.php?appid=sales_order_for_invoice&m=Create Invoice Fail, Please Check The Parameter");					
		}
		
	}else{

		$objek->debugLog("ERR. ORDER-ADD ORDER : ".$no_order);
		$checkDuplicate = $q->GetOne("select in_no from invoice where in_no = '".$in_no."' order by id desc");
		if(!empty($checkDuplicate)){
			header("location:index.php?appid=sales_order_for_invoice&m=Create Invoice Fail, Invoice ".$in_no." Already Exist");			
		}elseif(empty($rateId)){
			header("location:index.php?appid=sales_order_for_invoice&m=Create Invoice Fail, Tidak ada Rate Yang Valid di tanggal tersebut");			
		}else{
			header("location:index.php?appid=sales_order_for_invoice&m=Create Invoice Fail, Contact Administrator");			
		}
		
		$t->parse("hdl_tidakada", "tidakada");
		$t->set_var("MESSAGE", "ERR. ORDER-ADD ORDER : ".$no_order." [1]");		

	}

}

$t->pparse("output", "handle");

$objek->footer();
?>