<?
# Inisialisasi
$objek->init();

$q = buatKoneksiDB();

# Isi halaman
$t = buatTemplate();

$t->set_file("handle_edit", "detail.html");
$t->set_block("handle_edit", "reply", "hdl_reply");
$t->set_block("handle_edit", "ada", "hdl_ada");
$t->set_block("handle_edit", "tidakada", "hdl_tidakada");
$t->set_block("handle_edit", "uraian_ada", "hdl_uraian_ada");
$t->set_block("handle_edit", "uraian_tidakada", "hdl_uraian_tidakada");
$t->set_var("hdl_tidakada", "");
$t->set_var("hdl_reply", "");
$t->set_var("hdl_ada", "");
$t->set_var("hdl_uraian_tidakada", "");
$t->set_var("hdl_uraian_ada", "");

$no = 0;
$userId = $_SESSION['user']->profil['userid'];

// IF Group ID Admin (14), Show All Ticket
$isAdmin = $q->GetOne("select count(*) from user_group where group_id = 14 and user_id = ".$userId);
if($isAdmin > 0){
	$whereAdmin = " or 1=1 ";
}else{
	$whereAdmin = "";
}


if(!empty($_GET['refreshTicket'])){
	$q->Execute("update user set ket = '' where user_id = '".$userId."'");
}
					
$id = $_GET['id'];
if (isset($id) && ($id != "")) {
	$sql = "select us.fullname as sender, u.fullname as receiver,o.* from open_ticket o join user u on o.receiver_user_id = u.user_id join user us on o.sender_user_id = us.user_id where (sender_user_id = ".$userId." or receiver_user_id in (".$userId.",0) ".$whereAdmin.") and o.ticket_number = '".$id."' limit 1";
	$rs = $q->Execute($sql);
//	echo $sql;
	if ($rs and !$rs->EOF) {
		while(!$rs->EOF) {
			$t->set_var("MESSAGE", "Detail Data Dengan No Ticket : ".$id);
			$ticket_number = $id;
			$t->set_var("ticket_number", $id);
			$t->set_var("pengirim", $rs->fields['sender']);			
			$t->set_var("penerima", $rs->fields['receiver']);
			
			if($rs->fields['sender_user_id'] == 0){
				$t->set_var("id_penerima", 0);			
			}elseif($rs->fields['sender_user_id'] == $userId){
				$t->set_var("id_penerima", $rs->fields['receiver_user_id']);
//				echo "Sama";
			}else{
				$t->set_var("id_penerima", $rs->fields['sender_user_id']);
//				echo "Beda";
			}
			$t->set_var("subject", $rs->fields['subject']);
			$t->set_var("content", nl2br($rs->fields['content']));			
			$t->set_var("type", $OpenTicketType[$rs->fields['type']]);			
			$t->set_var("status", $OpenTicketStatus[$rs->fields['status']]);
			if($rs->fields['status'] == 'c'){
				$t->set_var("hdl_reply", "");
			}else{
				$t->parse("hdl_reply", "reply", true);
			}
						
			$t->set_var("date", $objek->ubahFormatTanggalForReport($rs->fields['date']));					

			$t->parse("hdl_ada", "ada", true);
			
			$rs->MoveNext();
		}
	}else{
		 $t->parse("hdl_tidakada", "tidakada");
	 	 $t->set_var("MESSAGE", "Data Dengan ID : ".$id." Tidak Di Temukan [1]");
	}
	

				
	// Show Child Ticket for Each Ticket
	$sqlDetail = "select us.fullname as sender, u.fullname as receiver,o.* from open_ticket o join user u on o.receiver_user_id = u.user_id join user us on o.sender_user_id = us.user_id where o.parent_ticket_number = '".$id."' order by o.date desc";
//	echo $sqlDetail;
	$rsDetail = $q->Execute($sqlDetail);
	if($rsDetail and !$rsDetail->EOF){
		while(!$rsDetail->EOF) {
//			echo $rsDetail->fields['ticket_number'];
			$no++ % 2 ? $t->set_var('row', 'row0'):$t->set_var('row', 'row1');
			$t->set_var("reply_ticket_number", $rsDetail->fields['ticket_number']);
			$t->set_var("reply_pengirim", $rsDetail->fields['sender']);			
			$t->set_var("reply_penerima", $rsDetail->fields['receiver']);
			$t->set_var("reply_content", nl2br($rsDetail->fields['content']));			
			$t->set_var("reply_date", $objek->ubahFormatTanggalForReport($rsDetail->fields['date']));					

			$t->parse("hdl_uraian_ada", "uraian_ada", true);
			
			$rsDetail->MoveNext();	
			
		}
		
	}else{
		 $t->set_var("MESSAGE", "Detail Data Dengan ID : ".$id." Tidak Di Temukan [3]");
		 $t->parse("hdl_uraian_tidakada", "uraian_tidakada");				

	}
	
}else{
	$t->parse("hdl_tidakada", "tidakada");
	$t->set_var("MESSAGE", "Data Dengan ID : ".$id." Tidak Di Temukan [2]");
}

$t->pparse("output", "handle_edit");

$objek->footer();
?>
