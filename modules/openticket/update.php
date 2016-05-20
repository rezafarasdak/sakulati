<?
$objek->init();

$q = buatKoneksiDB();
$t = buatTemplate();

$parent_ticket_number = $_POST['ticket_number'];
$act = $_POST['act'];
$id_status = $_POST['id_status'];
$id_penerima = $_POST['id_penerima'];
$content = $_POST['content'];
$ticket_number = $objek->getTicketNumber();
$content = $_POST['content'];
$userId = $_SESSION['user']->profil['userid'];

//echo $act;

if (isset($act) && ($act == "update")) {
	if(!empty($content) or $content != ""){
		$sql = "insert into open_ticket (ticket_number,sender_user_id,receiver_user_id,content,status,date,parent_ticket_number) values (".$ticket_number.",'".$userId."','".$id_penerima."','".$content."','".$id_status."',now(),'".$parent_ticket_number."')";
		$objek->debugLog("Inserting Ticket ".$sql);
		$rs=$q->Execute($sql);
		if ($rs) 
		{	
			$objek->userLog('Update Open Ticket '.$ticket_number);
			header("location:index.php?appid=openticket&m=Reply Ticket Success");
		}
	}
	
	$sqlUpdate = "update open_ticket set status = '".$id_status."' where ticket_number = '".$parent_ticket_number."'";
	$rsUpdate=$q->Execute($sqlUpdate);
	if ($rsUpdate) 
	{
		header("location:index.php?appid=openticket&m=Reply Ticket Success");
	}

}else{
	// Error ID Kosong
}
$objek->footer();
?>