<?

# Kelas aplikasi, otomatis dibuat saat suatu halaman dipanggil
class objek extends mesin {
	var $mt;                    # objek Template
	var $dir;                   # Direktori lokal tempat site ini diinstall
	var $url;                   # URL site ini
	var $db;                    # objek Database
	var $blocks;                # Variabel/Array nama blok yang akan di-load
	var $override = 0;          # Flag untuk menentukan urutan prioritas blok
	var $sudah_muat = false;
	var $layout = true;
	
# Constructor
	function objek() {

		global $guest_user;
		
		header('P3P: CP="IDC DSP COR CURa ADMa OUR IND PHY ONL COM STA"'); 
		
		session_start();

		$this->mt = buatTemplate('_main');
		$this->db = buatKoneksiDB();

		$this->otentikasi = new otentikasi;
		$this->hak_akses = new hak_akses;
		$this->pengguna = new pengguna;

		$this->siteID = $GLOBALS['conf']['siteID'];

		$this->dir = $GLOBALS['mainConf']['site']['path'];
		$this->url = $GLOBALS['mainConf']['site']['url'];
		$this->log_folder_name = $GLOBALS['mainConf']['site']['log_folder_name'];
		$this->log_file_name = $GLOBALS['mainConf']['site']['log_file_name'];
		$this->debug_status = $GLOBALS['mainConf']['site']['debug'];
		$this->encryptKey = $GLOBALS['mainConf']['site']['key'];
		$this->print_folder_name = $GLOBALS['mainConf']['site']['print_folder_name'];

//		echo $this->debug;

		$this->guest_user = $guest_user;

		$this->mt->set_file('hdl_main',  'skeleton.html');
        $this->mt->set_block('hdl_main', 'kalender', 'hdl_kalender');
		$this->mt->set_block('hdl_main', 'left_block_header', 'hdl_left_block');
		$this->mt->set_block('hdl_main', 'right_block_header', 'hdl_right_block');
		$this->mt->set_block('hdl_main', 'free_block_header', 'hdl_free_block');		
		$this->mt->set_block('hdl_main', 'blok_kiri', 'hdl_blok_kiri');
		$this->mt->set_block('hdl_main', 'blok_kanan', 'hdl_blok_kanan');
		$this->mt->set_block('hdl_main', 'LoggedOn', 'hdl_logon');
		$this->mt->set_block('hdl_main', 'koleksi', 'hdl_koleksi');

		$this->mt->set_var('muatan', '');
		$this->mt->set_var('hdl_kalender', '');
		$this->mt->set_var('hdl_left_block', '');
		$this->mt->set_var('hdl_right_block', '');
		$this->mt->set_var('hdl_free_block', '');
		$this->mt->set_var('hdl_blok_kiri', '');
		$this->mt->set_var('hdl_blok_kanan', '');
		$this->mt->set_var('hdl_logon', '');
		$this->mt->set_var('hdl_koleksi','');

		$this->mt->set_var('tanggal sistem', date('l, F d, Y'));

		$this->mt->set_var('url',$this->url);
		
		$this->setTitle($title);

		$this->arrayBulan['01']="Januari";
		$this->arrayBulan['02']="Februari";
		$this->arrayBulan['03']="Maret";
		$this->arrayBulan['04']="April";
		$this->arrayBulan['05']="Mei";
		$this->arrayBulan['06']="Juni";
		$this->arrayBulan['07']="Juli";
		$this->arrayBulan['08']="Agustus";
		$this->arrayBulan['09']="September";
		$this->arrayBulan['10']="Oktober";
		$this->arrayBulan['11']="November";
		$this->arrayBulan['12']="Desember";

		$this->arrayBulanSimple['01']="JAN";
		$this->arrayBulanSimple['02']="FEB";
		$this->arrayBulanSimple['03']="MAR";
		$this->arrayBulanSimple['04']="APR";
		$this->arrayBulanSimple['05']="MEI";
		$this->arrayBulanSimple['06']="JUN";
		$this->arrayBulanSimple['07']="JUL";
		$this->arrayBulanSimple['08']="AUG";
		$this->arrayBulanSimple['09']="SEP";
		$this->arrayBulanSimple['10']="OCT";
		$this->arrayBulanSimple['11']="NOV";
		$this->arrayBulanSimple['12']="DES";

		// Message
		$message = $_GET['m'];
		if (isset($message) && ($message != "")) {
			$this->nextmessage($message);
		}

		if ($_GET['login'] == 'yes') {
//			$this->debugLog("Test 2");
			$this->muat_utama(false, $this->otentikasi->loginform(), 'Formulir login');
		}
		elseif($_GET['login'] == 'continue' && !$this->otentikasi->is_login()) {
//			$this->debugLog("Login from [".$_POST['username']."] & [".$_POST['password']."] Processing...");
			if (!$this->otentikasi->login($_POST['username'], md5(md5($_POST['password'])))) {
				$this->debugLog("Login from [".$_POST['username']."] & [".$_POST['password']."] Failed..");
				$this->nextmessage($this->otentikasi->teks_error());
				$this->muat_utama(false, $this->otentikasi->loginform(), 'Formulir login');
			}
			else {
//				$this->debugLog("Login from [".$_POST['username']."] & [".$_POST['password']."] Success..");
				$this->debugLog("Login from [".$_POST['username']." Success..");
				$this->userLog('Login Success');				
				header("location:index.php");
//				header("Location: ".self_url('login', ''));
			}
		}
		elseif($_GET['logout'] == 'yes') {
			$this->debugLog("Logout..");
			$this->userLog('Logout Success');
			$this->otentikasi->logout();
		}

		if($this->otentikasi->is_login()) {
			$this->user = $this->otentikasi->user_logon();
		}
		else {
			unset($this->user);
		}

		
	}



# sembunyikan layout tampilan
	function hide_layout() {
		$this->layout = false;
	}

# tampilkan layout tampilan
	function show_layout() {
		$this->layout = true;
	}

#	Melakukan inisialisasi di tingkat halaman
	function init() {
		global $appid;
		$this->hak_akses->check_perms($this->siteID, $appid, $this->user);
		ob_start();
	}


	function muat_utama($ob = true, $isi = '', $header = '') {
		global $appid;
		if ($ob == true) {
			$this->mt->set_var('muatan', $this->showmessage(). $this->ambil_muatan_modul());
		}
		else {
			$this->mt->set_var('muatan', $this->showmessage(). $isi);
		}
		$this->sudah_muat = true;
		if ($header != '') {
			$this->mt->set_var('header muatan', $header);
		}
		elseif ($appid != '') {
//			$sql = "select title from module where name = '".$appid."' and is_visible = 1";
			$sql = "select title from module where name = '".$appid."'";
//			echo $sql;
			$rs = $this->db->Execute($sql);
			if (!$rs->EOF) $this->mt->set_var('header muatan', $rs->fields[0]);
			else $this->mt->set_var('header muatan', '&nbsp;');
		}
		else $this->mt->set_var('header muatan', '&nbsp;');
	}

	function ambil_muatan_modul() {
		$isi = ob_get_contents();
		ob_end_clean();
		return $isi;
	}

	function blok($namafile) {
		ob_start();
		include($namafile);
		$block_content = ob_get_contents();
		ob_end_clean();
		return $block_content;
	}

	function showmessage() {
		if($_SESSION['smessage'] != '') {
			$this->message .= (($this->message != '') ? "\n<br>\n" : '') . $_SESSION['smessage'];
			unset($_SESSION['smessage']);
		}
		if ($this->message != '') {
			$t = buatTemplate('_main');
			$t->set_file('hdl_message', 'message.html');
			$t->set_var('message', $this->message);
			$t->set_var('message_type', $this->message_type);
			$t->parse('hdl_message', 'hdl_message');
			return $t->get('hdl_message');
		}
		else return '';
	}

	function muat_blok($nama_blok, $posisi = 'kiri', $block_header = '') {
		$isi_blok = $this->blok(BLOCK_DIR. '/'. $nama_blok);
		if (trim($isi_blok) == '') return false;
		if ($posisi == 'kiri') {
			$this->mt->set_var('block content', $isi_blok);
			if ($block_header != '') {
				$this->mt->set_var('block name', $block_header);
				$this->mt->parse('hdl_left_block', 'left_block_header');
			}
			$this->mt->parse('hdl_blok_kiri', 'blok_kiri', true);
		}
		elseif ($posisi == 'free') {
			$this->mt->set_var('block content', $isi_blok);
			if ($block_header != '') {
				$this->mt->set_var('block name', $block_header);
				$this->mt->parse('hdl_free_block', 'free_block_header');
			}
			$this->mt->parse('hdl_blok_kanan', 'blok_kanan', true);
		}
		elseif ($posisi == 'kanan') {
			$this->mt->set_var('block content', $isi_blok);
			if ($block_header != '') {
				$this->mt->set_var('block name', $block_header);
				$this->mt->parse('hdl_right_block', 'right_block_header');
			}
			$this->mt->parse('hdl_blok_kanan', 'blok_kanan', true);
		}
	}

# Mengolah tampilan akhir yang dikirim ke client, harus dipanggil di akhir halaman.
	function userLogonId(){
		$uid = $this->user->uid();
		if (empty($uid))$uid=0;
		return $uid;
	}
	
	function userAdditionalInfo(){
		$additional_info = $this->pengguna->additional_info();
//		echo "aa".$additional_info;
		if (empty($additional_info))$additional_info='99';
		return $additional_info;
	}	

	function userStoreId(){
		$storeId = $this->pengguna->storeId();
		if (empty($storeId))$storeId='0';
		return $storeId;
	}
		
	function userInfo(){
		if($this->otentikasi->is_login()){
			$uid = $this->user->groups();
			if (empty($uid)){
				$uid=0;
			}else{
				//print_r($uid);
				//echo "Group ID ".$uid[0];	
			}
			return $uid[0];
		}else{
			return 0;	
		}
	}	


	function getUserLocation(){
		return $this->user->location();
	}
		
	function footer() {
		if ($this->layout) {
			if (!$this->sudah_muat) $this->muat_utama();
			ob_end_clean();
			$this->muat_blok('menu.php', 'kiri', 'Menu');
			
			if ($this->otentikasi->is_login()) {
				$this->mt->set_var('user login', ' <b>'. $this->user->username(). '</b>');
				$this->mt->set_var('login action', self_url('logout', 'yes'));
				$this->mt->set_var('login action title', 'Logout');
				$this->mt->set_var('login setting css','i-cog');
				$this->mt->set_var('login setting title','Setting');
				$this->mt->parse('hdl_logon', 'LoggedOn');
			}
			else {
				$this->mt->set_var('user login', '<b>Anda belum login</b>');
				$this->mt->set_var('login action', self_url('login', 'yes'));
				$this->mt->set_var('login action title', 'Login');
				$this->mt->set_var('login setting css','');
				$this->mt->set_var('login setting title','');
				$this->mt->parse('hdl_logon', 'LoggedOn');
			}


			$this->mt->set_var('URL', $this->url);
			$this->mt->pparse('out', 'hdl_main');
		}
		else {
			echo $this->ambil_muatan_modul();
		}
	}



# Mengirim pesan 
	function nextmessage($message, $type = 'warning') {
		// $type = warning, danger, success, info
		if(empty($type)) $type = 'warning';
		$this->message = $message;
		$this->message_type = $type;
	}

#
	function smessage($message) {
		$_SESSION['smessage'] = $message;
	}

# Menampilkan pesan error yang dikirim lewat $error
    function errmessage($error) {
		global $HTTP_SERVER_VARS, $appid;
		$this->debugLog("[ERROR] - Direct / No Access [".$HTTP_SERVER_VARS."] [".$appid."]");
		$t = new Template(MAIN_TEMPLATE_DIR);
		$t->set_file('hdl_error', 'error.html');
		$t->set_var('error', $error);
		$t->set_var('link', (preg_match("/\?/", $HTTP_SERVER_VARS['HTTP_REFERER'])) ?
							$HTTP_SERVER_VARS['HTTP_REFERER'] :
							$HTTP_SERVER_VARS['HTTP_REFERER'] . "?appid=$appid");
		$t->pparse('out', 'hdl_error');
		$this->footer();
		exit;
	}

# For Statistic User Access and User Online

	function userOnline(){
		$now = date("Y-m-j H:i:s");
		$IPnum = $_SERVER['REMOTE_ADDR'];
		
// Lemot klo pake lokasi
//		$lokasi=$this->countryCityFromIP($IPnum); 

		$rs = $this->db2->Execute("SELECT * FROM user_online WHERE ipaddress = '".$_SERVER['REMOTE_ADDR']."' LIMIT 1");

		if(!$rs->EOF)
		{
			$sql = "UPDATE user_online SET lastactive = ".time().",datetime = '$now' WHERE ipaddress = '".$_SERVER['REMOTE_ADDR']."' LIMIT 1";
			$sql2 = "UPDATE user_access SET lastactive = ".time().",datetime = '$now' WHERE ipaddress = '".$_SERVER['REMOTE_ADDR']."' LIMIT 1";

	 		$rs = $this->db2->Execute($sql);
			$rs2 = $this->db2->Execute($sql2);
		}
		else
		{
			 $sql = "INSERT INTO `user_online` VALUES ('".$_SERVER['REMOTE_ADDR']."', ".time().",'$now')";
 			 $sql2 = "INSERT INTO `user_access` VALUES ('".$_SERVER['REMOTE_ADDR']."', ".time().",'$now','$lokasi')";

			 $rs = $this->db2->Execute($sql);
 			 $rs2 = $this->db2->Execute($sql2);

		}
		
		$rs = $this->db2->Execute("DELETE FROM user_online WHERE lastactive < ".(time()-300));
		
		//$allViewQuery = $this->db2->Execute("SELECT * FROM user_online");


		$online = $this->db2->GetOne("select count(*) from user_online");
		$user_access = $this->db2->GetOne("select count(*) from user_access");

		$this->mt->set_var('tot_visitor',$user_access);
	}
	
	function getFullName(){
		$fullname = $this->user->uidOld();
		return $fullname;			
	}

	function countryCityFromIP($ipAddr)
	{
	   //function to find country and city name from IP address
	   //Developed by Roshan Bhattarai 
	   //Visit http://roshanbh.com.np for this script and more.
	  
	  //verify the IP address for the  
	  ip2long($ipAddr)== -1 || ip2long($ipAddr) === false ? trigger_error("Invalid IP", E_USER_ERROR) : "";
	  // This notice MUST stay intact for legal use
	  $ipDetail=array(); //initialize a blank array
	  //get the XML result from hostip.info
	  $xml = file_get_contents("http://api.hostip.info/?ip=".$ipAddr);
	  //get the city name inside the node <gml:name> and </gml:name>
	  preg_match("@<Hostip>(\s)*<gml:name>(.*?)</gml:name>@si",$xml,$match);
	  //assing the city name to the array
	  $ipDetail['city']=$match[2]; 
	  $lokasi = $ipDetail['city'];
	  //get the country name inside the node <countryName> and </countryName>
	  preg_match("@<countryName>(.*?)</countryName>@si",$xml,$matches);
	  //assign the country name to the $ipDetail array 
	  $ipDetail['country']=$matches[1];
	  //get the country name inside the node <countryName> and </countryName>
	  preg_match("@<countryAbbrev>(.*?)</countryAbbrev>@si",$xml,$cc_match);
	  $ipDetail['country_code']=$cc_match[1]; //assing the country code to array
	  //return the array containing city, country and country code
	  return $lokasi;
	}
	
	function setTitle($title) {
		if(isset($title)){
			$this->mt->set_var("title", $title." | ");
		}else{
			$this->mt->set_var("title", "");			
		}
	}

	function userLog($keterangan){
		$userId = $_SESSION['user']->profil['userid'];
		
		$moduleId = $this->db->GetOne("select module_id from module where name = '".$_GET['appid']."'");
		if(empty($moduleId)){
			$moduleId = 1;	
		}
		
		$sql = "INSERT INTO `log` (user_id,datetime,module_id,ip_address,keterangan) VALUES (".$userId.",now(),".$moduleId.",'".$_SERVER['REMOTE_ADDR']."', '".$keterangan."')";
		
		if(!empty($userId)){
			$this->db->Execute($sql);
		}	
		return true;
	}
	
	// Write log into file .log
	function debugLog($log){
		global $appid;
		$today = date("Y-m-d H:i:s")." ".round(microtime() * 1000); 
		$todayForLogName = date("Ymd");
		$username = $_SESSION['user']->profil['username'];
		
		if(empty($username)){
			$logedName = "No Loggin";
		}else{
			if ($this->otentikasi->is_login()) {
				$logedName = $username;						
			}else{
				$logedName = "Logged";										
			}
		}
		
		if(empty($log)){
			$log = "-";	
		}
		
		if(empty($appid)){
			$module = "Home Page";
		}else{
			$module = $appid;	
		}
		
		if ($this->debug_status){
			$fileName = $this->log_file_name.".".$todayForLogName;
			$fh = fopen($this->log_folder_name .'/'.$fileName ,'a+') or die('Cant open file '.$this->log_folder_name .'/'.$fileName);
//			$fh = fopen(LOG_FOLDER_NAME .'/'. LOG_FILE_NAME,'a+') or die('Cant open file ');
			$content = "[".$today."] [".$logedName."] [".$module."] ".$log;
			fwrite($fh,$content."\n"); 
			fclose($fh);
		}
	}

	function enc($str){
		$key = $this->encryptKey;
		for($i=0; $i<strlen($str); $i++) {
			$char = substr($str, $i, 1);
			$keychar = substr($key, ($i % strlen($key))-1, 1);
//			$this->debugLog($i.". ".$char." - ".$keychar);
			$char = chr(ord($char)+ord($keychar));
			$result.=$char;
//			$this->debugLog($i.". ".$char." - ".$result);			
		}
//		$this->debugLog("[".$result."] -> [".$this->String2Hex($result)."] -> [".urlencode($this->String2Hex($result))."]");
//		return base64_encode($result);
//		return urlencode(urlencode(base64_encode($result)));
		return urlencode($this->String2Hex($result));
	}
	
	
	function dec($str){
//		$str = base64_decode($str);
//		$str = base64_decode(urldecode($str));
		$str = $this->Hex2String(urldecode($str));
	  	$result = '';
		$key = $this->encryptKey;
		for($i=0; $i<strlen($str); $i++) {
			$char = substr($str, $i, 1);
			$keychar = substr($key, ($i % strlen($key))-1, 1);
			$char = chr(ord($char)-ord($keychar));
			$result.=$char;
		}
	return $result;
	}

	function String2Hex($string){
		$hex='';
		for ($i=0; $i < strlen($string); $i++){
			$hex .= dechex(ord($string[$i]));
		}
		return $hex;
	}
	 
	 
	function Hex2String($hex){
		$string='';
		for ($i=0; $i < strlen($hex)-1; $i+=2){
			$string .= chr(hexdec($hex[$i].$hex[$i+1]));
		}
		return $string;
	}
	
	function checkDeletePermission(){
	
		$perms = $this->db->GetOne("select count(*) from groups where allow_delete is true and group_id in (select group_id from user_group where user_id = ".$this->user->uid().")");
		if($perms > 0 ){
			return true;
		}else{
			return false;	
		}
	}

	function checkEditPermission(){
	
		$perms = $this->db->GetOne("select count(*) from groups where allow_edit is true and group_id in (select group_id from user_group where user_id = ".$this->user->uid().")");
		if($perms > 0 ){
			return true;
		}else{
			return false;	
		}
	}
	
	function checkAllowSeeOtherLocationPermission(){
	
		$perms = $this->db->GetOne("select count(*) from groups where allow_see_other_location is true and group_id in (select group_id from user_group where user_id = ".$this->user->uid().")");
		if($perms > 0 ){
			return true;
		}else{
			return false;	
		}
	}
	
	function ubahFormatTanggalForReport($date){
	
		// YYYY-MM-DD TO DD-MM-YYYY 
		$date = substr($date,8,2)."-".substr($date,5,2)."-".substr($date,0,4);
		return $date;
	}

	function ubahFormatTanggalForPrint($date){
	
		// YYYY-MM-DD TO DD-MM-YYYY 
		$date = substr($date,8,2)." ".$this->arrayBulan[substr($date,5,2)]." ".substr($date,0,4);
		return $date;
	}


	function ubahFormatTanggalForDB($date){
	
		//DD-MM-YYYY TO : YYYY-MM-DD
		$date = substr($date,6,4)."-".substr($date,3,2)."-".substr($date,0,2);
		return $date;
		
	}	

	function ubahFormatTanggalForTrialBalance($date){
	
		// YYYY-MM-DD TO Month Name YYYY
//		$date = $this->arrayBulan[substr($date,3,2)]." ".substr($date,0,4);
		$date = $this->arrayBulan[substr($date,5,2)]." ".substr($date,0,4);
		return $date;
	}

	function ubahFormatTanggalForLedger($date){
	
		// YYYY-MM-DD TO Month Name [3 word] YY
		$date = $this->arrayBulanSimple[substr($date,5,2)]." ".substr($date,2,2);
		return $date;
	}

	function ubahFormatTanggalForSummary($date){
	
		// YYYY-MM-DD TO DD-MMM-YY 
		$date = substr($date,8,2)." ".$this->arrayBulanSimple[substr($date,5,2)]." ".substr($date,2,2);
		return $date;
	}
			
	function updateAmountSalesOrder($so_id){
		$this->debugLog("Updating Amount Sales Order");
		// Update Amount
		$sqlUpdateAmount = "update `sales_order` set 
				sub_total = (SELECT sum(price*quantity) as total FROM `sales_order_detail` WHERE sales_order_id = '".$so_id."'), 
				tax_amount = (SELECT sum(price*quantity)*0.1 as tax FROM `sales_order_detail` WHERE sales_order_id = '".$so_id."'),
				amount = sub_total + tax_amount + other_amount
				where id = '".$so_id."'";
		$this->debugLog($sqlUpdateAmount);
		$this->db->Execute($sqlUpdateAmount);
		
		return true;
	}

	function updateAmountPurchaseOrder($po_id){
		$this->debugLog("Updating Amount Purchase Order");
		// Update Amount
		$sqlUpdateAmount = "update `purchase_order` set 
				sub_total = (SELECT sum(price*quantity) as total FROM `purchase_order_detail` WHERE purchase_order_id = '".$po_id."'), 
				tax_amount = (SELECT sum(price*quantity)*0.1 as tax FROM `purchase_order_detail` WHERE purchase_order_id = '".$po_id."'),
				amount = sub_total + tax_amount + other_amount
				where id = '".$po_id."'";
		$this->debugLog($sqlUpdateAmount);
		$this->db->Execute($sqlUpdateAmount);
		
		return true;	
	}
	// Terbilang
	
	function konversi($x){
	   
	  $x = abs($x);
	  $angka = array ("","SATU", "DUA", "TIGA", "EMPAT", "LIMA", "ENAM", "TUJUH", "DELAPAN", "SEMBILAN", "SEPULUH", "SEBELAS");
	  $temp = "";
	   
	  if($x < 12){
	   $temp = " ".$angka[$x];
	  }else if($x<20){
	   $temp = $this->konversi($x - 10)." BELAS";
	  }else if ($x<100){
	   $temp = $this->konversi($x/10)." PULUH". $this->konversi($x%10);
	  }else if($x<200){
	   $temp = " SERATUS".$this->konversi($x-100);
	  }else if($x<1000){
	   $temp = $this->konversi($x/100)." RATUS".$this->konversi($x%100);  
	  }else if($x<2000){
	   $temp = " SERIBU".$this->konversi($x-1000);
	  }else if($x<1000000){
	   $temp = $this->konversi($x/1000)." RIBU".$this->konversi($x%1000);  
	  }else if($x<1000000000){
	   $temp = $this->konversi($x/1000000)." JUTA".$this->konversi($x%1000000);
	  }else if($x<1000000000000){
	   $temp = $this->konversi($x/1000000000)." MILYAR".$this->konversi($x%1000000000);
	  }
	   
	  return $temp;
	 }
	   
	 function tkoma($x){
	  $str = stristr($x,",");
	  $ex = explode(',',$x);
	   
	  if(($ex[1]/10) >= 1){
	   $a = abs($ex[1]);
	  }
	  $string = array("NOL", "SATU", "DUA", "TIGA", "EMPAT", "LIMA", "ENAM", "TUJUH", "DELAPAN", "SEMBILAN", "SEPULUH", "SEBELAS");
	  $temp = "";
	  
	  $a2 = $ex[1]/10;
	  $pjg = strlen($str);
	  $i =1;
		 
	   
	  if($a>=1 && $a< 12){  
	   $temp .= " ".$string[$a];
	  }else if($a>12 && $a < 20){  
	   $temp .= $this->konversi($a - 10)." BELAS";
	  }else if ($a>20 && $a<100){  
	   $temp .= $this->konversi($a / 10)." PULUH". $this->konversi($a % 10);
	  }else{
	   if($a2<1){
		 
		while ($i<$pjg){    
		 $char = substr($str,$i,1);    
		 $i++;
		 $temp .= " ".$string[$char];
		}
	   }
	  } 
	  return $temp;
	 }
	  
	 function terbilang($x){
	  if($x<0){
	   $hasil = "MINUS ".trim($this->konversi(x));
	  }else{
	   $poin = trim($this->tkoma($x));
	   $hasil = trim($this->konversi($x));
	  }
	   
	if($poin){
	   $hasil = $hasil." KOMA ".$poin;
	  }else{
	   $hasil = $hasil;
	  }
	  return $hasil; 
	 }
	 
// TERBILANG END	


// Terbilang USD Start
	function terbilangUsd($number) {

		// Replace comma ","
		$number = str_replace(",", "", $number);
		
	    $hyphen      = ' ';
	    $conjunction = ' AND ';
	    $separator   = ', ';
	    $negative    = 'NEGATIVE ';
	    $decimal     = ' POINT ';
	    $dictionary  = array(
	        0                   => 'ZERO',
	        1                   => 'ONE',
	        2                   => 'TWO',
	        3                   => 'THREE',
	        4                   => 'FOUR',
	        5                   => 'FIVE',
	        6                   => 'SIX',
	        7                   => 'SEVEN',
	        8                   => 'EIGHT',
	        9                   => 'NINE',
	        10                  => 'TEN',
	        11                  => 'ELEVEN',
	        12                  => 'TWELVE',
	        13                  => 'THIRTEEN',
	        14                  => 'FOURTEEN',
	        15                  => 'FIFTEEN',
	        16                  => 'SIXTEEN',
	        17                  => 'SEVENTEEN',
	        18                  => 'EIGHTEEN',
	        19                  => 'NINETEEN',
	        20                  => 'TWENTY',
	        30                  => 'THIRTY',
	        40                  => 'FOURTY',
	        50                  => 'FIFTY',
	        60                  => 'SIXTY',
	        70                  => 'SEVENTY',
	        80                  => 'EIGHTY',
	        90                  => 'NINETY',
	        100                 => 'HUNDRED',
	        1000                => 'THOUSAND',
	        1000000             => 'MILLION',
	        1000000000          => 'BILLION',
	        1000000000000       => 'TRILLION',
	        1000000000000000    => 'QUADRILLION',
	        1000000000000000000 => 'QUINTILLION'
	    );
	
	    if (!is_numeric($number)) {
//	        $this->debugLog("Not Number");
	        return false;
	    }
	
	    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
	        // overflow
//	        $this->debugLog("Number Not Allowed");
	        return false;
	    }
	
	    if ($number < 0) {
//	        $this->debugLog("Number Negative");
	        return $negative . $this->terbilangUsd(abs($number));
	    }
	
	    $string = $fraction = null;
	
	    if (strpos($number, '.') !== false) {
	        list($number, $fraction) = explode('.', $number);
	    }
	
	    switch (true) {
	        case $number < 21:
//		        $this->debugLog("Number Less Than 21");
	            $string = $dictionary[$number];
	            break;
	        case $number < 100:
//		        $this->debugLog("Number Less Than 100");
	            $tens   = ((int) ($number / 10)) * 10;
	            $units  = $number % 10;
	            $string = $dictionary[$tens];
	            if ($units) {
	                $string .= $hyphen . $dictionary[$units];
	            }
	            break;
	        case $number < 1000:
//		        $this->debugLog("Number Less Than 1000");
	            $hundreds  = $number / 100;
	            $remainder = $number % 100;
	            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
	            if ($remainder) {
	                $string .= $conjunction . $this->terbilangUsd($remainder);
	            }
	            break;
	        default:
//		        $this->debugLog("Default..");
	            $baseUnit = pow(1000, floor(log($number, 1000)));
	            $numBaseUnits = (int) ($number / $baseUnit);
	            $remainder = $number % $baseUnit;
	            $string = $this->terbilangUsd($numBaseUnits) . ' ' . $dictionary[$baseUnit];
	            if ($remainder) {
	                $string .= $remainder < 100 ? $conjunction : $separator;
	                $string .= $this->terbilangUsd($remainder);
	            }
	            break;
	    }
	
	    if (null !== $fraction && is_numeric($fraction)) {
//	        $this->debugLog("Fraction");
	        $string .= $decimal;
	        $words = array();
	        foreach (str_split((string) $fraction) as $number) {
	            $words[] = $dictionary[$number];
	        }
	        $string .= implode(' ', $words);
	    }
	
	    return $string;
	}

// Terbilang USD End

// Paging Start

	function paginate_function($item_per_page, $current_page, $total_records, $total_pages){
		$pagination = '';
		if($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages){ //verify total pages and current page number
			$pagination .= '<ul class="pagination">';
			$right_links    = $current_page + 5; 
			$previous       = $current_page - 5; //previous link 
			$next           = $current_page + 1; //next link
			$first_link     = true; //boolean var to decide our first link
	
			if($current_page > 1){
				$previous_link = ($previous==0)?1:$previous;
				$pagination .= '<li class="first"><a href="#" data-page="1" title="First">&laquo;</a></li>'; //first link
				$pagination .= '<li><a href="#" data-page="'.$previous_link.'" title="Previous">&lt;</a></li>'; //previous link
					for($i = ($current_page-2); $i < $current_page; $i++){ //Create left-hand side links
						if($i > 0){
							$pagination .= '<li><a href="#" data-page="'.$i.'" title="Page'.$i.'">'.$i.'</a></li>';
						}
					}   
				$first_link = false; //set first link to false
			}
	
			if($first_link){ //if current active page is first link
				$pagination .= '<li class="first active">'.$current_page.'</li>';
			}elseif($current_page == $total_pages){ //if it's the last active link
				$pagination .= '<li class="last active">'.$current_page.'</li>';
			}else{ //regular current link
				$pagination .= '<li class="active">'.$current_page.'</li>';
			}
	
			for($i = $current_page+1; $i < $right_links ; $i++){ //create right-hand side links
				if($i<=$total_pages){
					$pagination .= '<li><a href="#" data-page="'.$i.'" title="Page '.$i.'">'.$i.'</a></li>';
				}
			}
	
			if($current_page < $total_pages){ 
					$next_link = ($i > $total_pages)? $total_pages : $i;
					$pagination .= '<li><a href="#" data-page="'.$next_link.'" title="Next">&gt;</a></li>'; //next link
					$pagination .= '<li class="last"><a href="#" data-page="'.$total_pages.'" title="Last">&raquo;</a></li>'; //last link
			}
	
			$pagination .= '</ul>'; 
		}
	
		return $pagination; //return pagination links
	}

// Paging End

	function desimal($number) {
		$formatted = number_format($number,0,',','.');
		return $formatted;
	}

	function number_format_ind($number) {
		$formatted = number_format($number,2,',','.');
		return $formatted;
	}
	
	function number_format_usd($number) {
		$formatted = number_format($number,2,'.',',');
		return $formatted;
	}
	
	function invoiceFormat($invoice){
		$invoice = substr($invoice,0,5)."/".substr($invoice,5,2)."/".substr($invoice,7,2)."/".substr($invoice,9,2);
		return $invoice;
	}

	function pelunasanFormat($invoice){
		$invoice = substr($invoice,5,5)."/".substr($invoice,10,2)."/".substr($invoice,12,2)."/".substr($invoice,0,5);
		return $invoice;
	}	

	function pembayaranFormat($invoice){
		$invoice = substr($invoice,5,5)."/".substr($invoice,10,2)."/".substr($invoice,12,2)."/".substr($invoice,0,5);
		return $invoice;
	}	
		
	function salesOrderFormat($so_no){
		$so_no = substr($so_no,0,3)."/".substr($so_no,3,5)."/".substr($so_no,8,2)."/".substr($so_no,10,2);
		return $so_no;
	}

	function purchaseOrderFormat($so_no){
		$so_no = substr($so_no,0,5)."/".substr($so_no,5,5)."/".substr($so_no,10,2)."/".substr($so_no,12,2);
		return $so_no;
	}	
	function deliveryOrderFormat($do_no){
		if(strlen($do_no) == 15){
			$do_no = substr($do_no,0,5)."/".substr($do_no,5,6)."/".substr($do_no,11,2)."/".substr($do_no,13,2);
		}else if(strlen($do_no) == 14){
			$do_no = substr($do_no,0,5)."/".substr($do_no,5,5)."/".substr($do_no,10,2)."/".substr($do_no,12,2);			
		}
		
		return $do_no;
	}	
	function goodsReceiptFormat($gr_no){
		$gr_no = substr($gr_no,0,5)."/".substr($gr_no,5,5)."/".substr($gr_no,10,2)."/".substr($gr_no,12,2);
		return $gr_no;
	}	
		
	function getAmountFromDeliveryOrder($do_id){
		$sql = "select sum(dod.dikirim*sod.price) as subTotal
				from delivery_order_detail dod 
				join delivery_order do on dod.delivery_order_id = do.id 
				join sales_order so on do.sales_order_id = so.id 
				join sales_order_detail sod on sod.sales_order_id = so.id and sod.barang_sales_id = dod.barang_sales_id
				where dod.dikirim > 0 and do.id = ".$do_id;
		
		$amount = $this->db->GetOne($sql);
		return $amount;
	}
	
	function getAmountFromGoodsReceipt($gr_id){
		$sql = "select sum(distinct dod.dikirim*sod.price) as subTotal
				from goods_receipt_detail dod 
				join goods_receipt do on dod.goods_receipt_id = do.id 
				join purchase_order so on do.purchase_order_id = so.id 
				join purchase_order_detail sod on sod.purchase_order_id = so.id and sod.barang_purchase_id = dod.barang_purchase_id
				where dod.dikirim > 0 and do.id = ".$gr_id;
		
		$amount = $this->db->GetOne($sql);
		return $amount;
	}
	
	function generateFaktur($diPungut, $tahun){
		
		if($diPungut == "yes"){
			$kodePajak = "010";	
		}else{
			$kodePajak = "070";	
		}
		
		$fakturPeriod = $this->db->GetOne("select faktur_period from counter where name = 'faktur'");
		$counter = $this->db->GetOne("select counter_min from counter where name = 'faktur'");

		$this->db->Execute("update counter set counter_min = counter_min + 1 where name = 'faktur'");
		
//		$faktur = $kodePajak.".003.".date('y').".".$counter;
		$faktur = $kodePajak.".".$fakturPeriod."-".$tahun.".".$counter;
		return $faktur;
		
/*		- Format : 010.003.14.16336433
				 : KodePajak.KodeDaerah2.Tahun.Counter.
				 : Kode Pajak ==> 010 : Di Pungut, 070 : Tidak di pungut.	
				 : Kode Daerah statis = 003.
				 : Tahun : YY
				 : Counter : 8 Digit, di buatkan management Counter.				 
*/		
	}
	
	function postToJournalFromInvoice($invoice_id){
		$this->debugLog("Func : postToJournalFromInvoice, Start Posting From Invoice...");		
		$sql = "select pd.*, p.voucher_no, i.in_no, so.currency, p.voucher_date from pelunasan p join pelunasan_detail pd on p.id = pd.pelunasan_id join invoice i on pd.invoice_id = i.id join sales_order so on i.sales_order_id = so.id where i.id = ".$invoice_id." limit 1";
		$this->debugLog($sql);

		$sumSubTotal = 0;
		$rs = $this->db->Execute($sql);
		if ($rs and !$rs->EOF) {
			while(!$rs->EOF) {
				$pelunasan_id = $rs->fields['pelunasan_id'];
				$in_no = $this->invoiceFormat($rs->fields['in_no']);
				$voucher_no = $this->pelunasanFormat($rs->fields['voucher_no']);
				$subTotal = $rs->fields['amount_dibayar'];
				$accountDebit = $rs->fields['ar_account'];
				$accountCredit = $rs->fields['sl_account'];
				$voucher_date = $rs->fields['voucher_date'];
				
				$description = "Invoice ".$in_no." On Voucher ".$voucher_no;
				$sumSubTotal += $subTotal;
				$total = $subTotal + ($subTotal / 10);
				
				$currency = $rs->fields['currency'];
				if($currency == "IDR"){
					$subTotalIdr = $subTotal;
					$totalIdr = $total;
					$convertRate = 1;
				}else{
					$convertRate = $this->db->GetOne("select amount from rate where currency = '".$currency."' order by valid_date desc");
					if(empty($convertRate)){
						$this->debugLog("Error Get Rate from Delivery Order : ".$do_id);
						$convertRate = 1;
					}
					$subTotalIdr = $subTotal * $convertRate;
					$totalIdr = $total * $convertRate;
				}

				// Posting Sub Total
				$sqlPostingSubTotal = "insert into jurnal (move, account, amount, amount_real, referal_id, type, latest_datetime, status, description, datetime) values ('C', '".$accountCredit."', '".$subTotalIdr."', '".$subTotal."', '".$pelunasan_id."', 'pl', now(), 'A', 'Sub Total ".$description."', '".$voucher_date."')";
				$this->db->Execute($sqlPostingSubTotal);
				$this->debugLog("Posting Sub Total ".$sqlPostingSubTotal);

				// Posting Total
				$sqlPostingTotal = "insert into jurnal (move, account, amount, amount_real, referal_id, type, latest_datetime, status, description, datetime) values ('D', '".$accountDebit."', '".$totalIdr."', '".$total."', '".$pelunasan_id."', 'pl', now(), 'A', 'Total ".$description."', '".$voucher_date."')";
				$this->db->Execute($sqlPostingTotal);
				$this->debugLog("Posting Total ".$sqlPostingTotal);

				$rs->MoveNext();		
			}
		}
		
		$tax = $sumSubTotal / 10;
		$taxIdr = $tax * $convertRate;
		
		// Posting Tax
		$sqlPostingTax = "insert into jurnal (move, account, amount, amount_real, referal_id, type, latest_datetime, status, description, datetime) values ('C', '".$accountCredit."', '".$taxIdr."', '".$tax."', '".$pelunasan_id."', 'pl', now(), 'A', 'Tax ".$voucher_no."', '".$voucher_date."')";
		$this->db->Execute($sqlPostingTax);
		$this->debugLog("Posting Tax ".$sqlPostingTax);
		
		return true;		
	
	
	}
	
	function rePostToJournalFromInvoice($invoiceId){
		
		$this->debugLog("Re Post Journal From Invoice Start");

		// Update Jurnal Status jadi Removed.
		$sqlUpdateJurnal = "update jurnal set status = 'R' where referal_id = '".$invoiceId."' and type = 'ar'";
		$this->debugLog("Updating Jurnal Status To Delete... ".$sqlUpdateJurnal);
		$this->db->Execute($sqlUpdateJurnal);
		
		$in_no = $this->db->GetOne("select in_no from invoice where id = ".$invoiceId);
		$dipungut = $this->db->GetOne("select dipungut from invoice where id = ".$invoiceId);

		// Select Available DO			
		$sql2 = "select do.* from invoice_detail id join delivery_order do on id.do_id = do.id
				where id.invoice_id = '".$invoiceId."' 
				order by do.id";
		$this->debugLog($sql2);
		$rs2 = $this->db->Execute($sql2);
		if ($rs2 and !$rs2->EOF) {
			$i = 1;
			while(!$rs2->EOF) {
				
				// Re Post
				$this->postToJournalFromDeliveryOrder($rs2->fields['id'],$in_no,$invoiceId,$dipungut);
				
				$rs2->MoveNext();
			}
		}
			
					
	}
	
	function postToJournalFromDeliveryOrder($do_id, $in_no, $in_id, $dipungut){
		$this->debugLog("Start Posting From Delivery Order...");		
		$sql = "select bs.id as barang_id, bs.name, bs.account_debit, bs.account_credit, sum(dod.dikirim*sod.price) as subTotal, so.currency
from delivery_order_detail dod 
join delivery_order do on dod.delivery_order_id = do.id 
join sales_order so on do.sales_order_id = so.id 
join sales_order_detail sod on sod.sales_order_id = so.id and sod.barang_sales_id = dod.barang_sales_id
join barang_sales bs on bs.id = dod.barang_sales_id and bs.id = sod.barang_sales_id
where dod.dikirim > 0 and do.id = ".$do_id;
		$this->debugLog($sql);
		$sumSubTotal = 0;
		
		$in_date = $this->db->GetOne("select date from invoice where id = ".$in_id);
		
		$rs = $this->db->Execute($sql);
		if ($rs and !$rs->EOF) {
			while(!$rs->EOF) {
				$barangSalesId = $rs->fields['barang_id'];
				$subTotal = $rs->fields['subTotal'];
				$barangName = $rs->fields['name'];
				$accountDebit = $rs->fields['account_debit'];
				$accountCredit = $rs->fields['account_credit'];

				$description = $barangName." On Invoice ".$in_no;
				$sumSubTotal += $subTotal;
				
				if($dipungut == "no"){
					$total = $subTotal;
				}else{
					$total = $subTotal + ($subTotal / 10);
				}
				

				$convertRate = 1;
				$currency = $rs->fields['currency'];
				if($currency == "IDR"){
					$subTotalIdr = $subTotal;
					$totalIdr = $total;
				}else{
					$convertRate = $this->db->GetOne("select amount from rate where currency = '".$currency."' order by valid_date desc");
					if(empty($convertRate)){
						$this->debugLog("Error Get Rate from Delivery Order : ".$do_id);
						$convertRate = 1;
					}
					$subTotalIdr = $subTotal * $convertRate;
					$totalIdr = $total * $convertRate;
				}

				// Posting Sub Total
				$sqlPostingSubTotal = "insert into jurnal (move, account, amount, amount_real, referal_id, type, latest_datetime, status, description, datetime) values ('C', '".$accountCredit."', '".$subTotalIdr."', '".$subTotal."', '".$in_id."', 'ar', now(), 'A', 'Sub Total Barang ".$description."', '".$in_date."')";
				$this->db->Execute($sqlPostingSubTotal);
				$this->debugLog("Posting Sub Total ".$sqlPostingSubTotal);

				// Posting Total
				$sqlPostingTotal = "insert into jurnal (move, account, amount, amount_real, referal_id, type, latest_datetime, status, description, datetime) values ('D', '".$accountDebit."', '".$totalIdr."', '".$total."', '".$in_id."', 'ar', now(), 'A', 'Total Barang ".$description."', '".$in_date."')";
				$this->db->Execute($sqlPostingTotal);
				$this->debugLog("Posting Total ".$sqlPostingTotal);

				$rs->MoveNext();		
			}
		}
		
		$tax = $sumSubTotal / 10;
		$taxIdr = $tax * $convertRate;
		
		$faktur = $this->db->GetOne("select faktur from invoice where id = ".$in_id);

		$coaPpnAR = "231110";

		// Check Di Pungut Status, bila Tidak Di Pungut, Tidak Menghitung Pajak
		if($dipungut == "no"){
			

			$sqlPostingTax = "insert into jurnal (move, account, amount, amount_real, referal_id, type, latest_datetime, status, description, datetime) values ('C', '".$coaPpnAR."', '0', '0', '".$in_id."', 'ar', now(), 'A', 'Faktur ".$faktur."', '".$in_date."')";
			$this->db->Execute($sqlPostingTax);
			$this->debugLog("Transaksi Tidak Di Pungut, Posting Tax Ke Jurnal = 0 ..");
			$this->debugLog("Posting Tax ".$sqlPostingTax);
			
		}else{
			// Posting Tax
			$sqlPostingTax = "insert into jurnal (move, account, amount, amount_real, referal_id, type, latest_datetime, status, description, datetime) values ('C', '".$coaPpnAR."', '".$taxIdr."', '".$tax."', '".$in_id."', 'ar', now(), 'A', 'Faktur ".$faktur."', '".$in_date."')";
			$this->db->Execute($sqlPostingTax);
			$this->debugLog("Posting Tax ".$sqlPostingTax);
		}
		
		return true;
	}



	function postToJournalFromGoodsReceipt($gr_id, $in_no, $in_id){
		$this->debugLog("Start Posting From Goods Receipt...");		
		$sql = "select bp.id as barang_id, bp.name, bp.account_debit, bp.account_credit, sum(dod.dikirim*sod.price) as subTotal, so.currency
from goods_receipt_detail dod 
join goods_receipt do on dod.goods_receipt_id = do.id 
join purchase_order so on do.purchase_order_id = so.id 
join purchase_order_detail sod on sod.purchase_order_id = so.id and sod.barang_purchase_id = dod.barang_purchase_id
join barang_purchase bp on bp.id = dod.barang_purchase_id and bp.id = sod.barang_purchase_id
where dod.dikirim > 0 and do.id = ".$gr_id;
		$this->debugLog($sql);
		$sumSubTotal = 0;
		$convertRate = 1;
		$in_date = $this->db->GetOne("select date from invoice where id = ".$in_id);
		$rs = $this->db->Execute($sql);
		if ($rs and !$rs->EOF) {
			while(!$rs->EOF) {
				$barangSalesId = $rs->fields['barang_id'];
				$subTotal = $rs->fields['subTotal'];
				$barangName = $rs->fields['name'];
				$accountDebit = $rs->fields['account_debit'];
				$accountCredit = $rs->fields['account_credit'];

				$description = $barangName." On Invoice ".$in_no;
				$sumSubTotal += $subTotal;
				$total = $subTotal + ($subTotal / 10);
				
				$currency = $rs->fields['currency'];
				if($currency == "IDR"){
					$subTotalIdr = $subTotal;
					$totalIdr = $total;
				}else{
					$convertRate = $this->db->GetOne("select amount from rate where currency = '".$currency."' order by valid_date desc");
					if(empty($convertRate)){
						$this->debugLog("Error Get Rate from Goods Receipt : ".$gr_id);
						$convertRate = 1;
					}
					$subTotalIdr = $subTotal * $convertRate;
					$totalIdr = $total * $convertRate;
				}

				// Posting Sub Total
				$sqlPostingSubTotal = "insert into jurnal (move, account, amount, amount_real, referal_id, type, latest_datetime, status, description, datetime) values ('D', '".$accountDebit."', '".$subTotalIdr."', '".$subTotal."', '".$in_id."', 'ap', now(), 'A', 'Sub Total Barang ".$description."', '".$in_date."')";
				$this->db->Execute($sqlPostingSubTotal);
				$this->debugLog("Posting Sub Total ".$sqlPostingSubTotal);

				// Posting Total
				$sqlPostingTotal = "insert into jurnal (move, account, amount, amount_real, referal_id, type, latest_datetime, status, description, datetime) values ('C', '".$accountCredit."', '".$totalIdr."', '".$total."', '".$in_id."', 'ap', now(), 'A', 'Total Barang ".$description."', '".$in_date."')";
				$this->db->Execute($sqlPostingTotal);
				$this->debugLog("Posting Total ".$sqlPostingTotal);

				$rs->MoveNext();		
			}
		}
		
		$this->debugLog("Tax : ".$sumSubTotal." / 10 --- Tax IDR : [".$tax."] * [".$convertRate."]");
		
		$tax = $sumSubTotal / 10;
		$taxIdr = $tax * $convertRate;
		
		// Posting Tax
		$coaPpnAP = "151110";
		$sqlPostingTax = "insert into jurnal (move, account, amount, amount_real, referal_id, type, latest_datetime, status, description, datetime) values ('D', '".$coaPpnAP."', '".$taxIdr."', '".$tax."', '".$in_id."', 'ap', now(), 'A', 'Tax ".$in_no."', '".$in_date."')";
		$this->db->Execute($sqlPostingTax);
		$this->debugLog("Posting Tax ".$sqlPostingTax);
		
		return true;
	}

	function postToJournalFromPelunasan($pelunasan_id){
		$this->debugLog("Func : postToJournalFromPelunasan, Start Posting From Invoice... ".$pelunasan_id);		
		$sql = "select pd.*, p.voucher_no, i.in_no, so.currency, i.date as invoice_date, p.bank_receipt from pelunasan p join pelunasan_detail pd on p.id = pd.pelunasan_id join invoice i on pd.invoice_id = i.id join sales_order so on i.sales_order_id = so.id where pd.status = 'A' and p.id = ".$pelunasan_id;
		$this->debugLog($sql);

		$sumSubTotal = 0;
		$rs = $this->db->Execute($sql);
		if ($rs and !$rs->EOF) {
			while(!$rs->EOF) {
				$pelunasan_id = $rs->fields['pelunasan_id'];
				$in_no = $this->invoiceFormat($rs->fields['in_no']);
				$voucher_no = $this->pelunasanFormat($rs->fields['voucher_no']);
				$subTotal = $rs->fields['amount_dibayar'];
				
// 				Change To Bank Receipt on Debit, 13 Dec 2015
//				$accountDebit = $rs->fields['ar_account'];
				$accountDebit = $rs->fields['bank_receipt'];
				$accountCredit = $rs->fields['sl_account'];
				$in_date = $rs->fields['invoice_date'];
				
				$description = "Invoice ".$in_no." On Voucher ".$voucher_no;
				$sumSubTotal += $subTotal;

//				Pelunasan Tidak Ada PPN
//				$total = $subTotal + ($subTotal / 10);
				$total = $subTotal;
			
				$currency = $rs->fields['currency'];
				if($currency == "IDR"){
					$subTotalIdr = $subTotal;
					$totalIdr = $total;
					$convertRate = 1;
					$sumTotalIdr += $subTotal;
				}else{
					$convertRate = $this->db->GetOne("select amount from rate where currency = '".$currency."' order by valid_date desc");
					if(empty($convertRate)){
						$this->debugLog("Error Get Rate from Delivery Order : ".$do_id);
						$convertRate = 1;
					}
					$subTotalIdr = $subTotal * $convertRate;
					$totalIdr = $total * $convertRate;
					$sumTotalIdr += $totalIdr;
				}

				// Posting Sub Total
				$sqlPostingSubTotal = "insert into jurnal (move, account, amount, amount_real, referal_id, type, latest_datetime, status, description, datetime) values ('C', '".$accountCredit."', '".$subTotalIdr."', '".$subTotal."', '".$pelunasan_id."', 'pl', now(), 'A', 'Sub Total ".$description."', '".$in_date."')";
				$this->db->Execute($sqlPostingSubTotal);
				$this->debugLog("Posting Sub Total ".$sqlPostingSubTotal);

				$rs->MoveNext();		
			}
		}

		// Posting Total
		$sqlPostingTotal = "insert into jurnal (move, account, amount, amount_real, referal_id, type, latest_datetime, status, description, datetime) values ('D', '".$accountDebit."', '".$sumTotalIdr."', '".$sumSubTotal."', '".$pelunasan_id."', 'pl', now(), 'A', 'Total ".$description."', '".$in_date."')";
		$this->db->Execute($sqlPostingTotal);
		$this->debugLog("Posting Total ".$sqlPostingTotal);

/*		
		$tax = $sumSubTotal / 10;
		$taxIdr = $tax * $convertRate;
		
		// Posting Tax
		$sqlPostingTax = "insert into jurnal (move, account, amount, amount_real, referal_id, type, latest_datetime, status, description, datetime) values ('C', '".$accountCredit."', '".$taxIdr."', '".$tax."', '".$pelunasan_id."', 'pl', now(), 'A', 'Tax ".$voucher_no."', '".$in_date."')";
		$this->db->Execute($sqlPostingTax);
		$this->debugLog("Posting Tax ".$sqlPostingTax);
*/		
		return true;		
	
	
	}



	function postToJournalFromPelunasanCash($pelunasan_id){
		$this->debugLog("Start Posting From Cash...");		
		$sql = "select pd.*, p.voucher_no, p.bank_receipt, p.rate_id, i.name, p.voucher_date from pelunasan p join pelunasan_detail_cash pd on p.id = pd.pelunasan_id join cart_of_account i on pd.account = i.id where pd.status = 'A' and p.id = ".$pelunasan_id;
		$this->debugLog($sql);

		$sumSubTotal = 0;
		$rs = $this->db->Execute($sql);
		if ($rs and !$rs->EOF) {
			while(!$rs->EOF) {
				$pelunasan_id = $rs->fields['pelunasan_id'];
				$voucher_no = $this->pelunasanFormat($rs->fields['voucher_no']);
				$subTotal = $rs->fields['amount'];
				$account = $rs->fields['account'];
				$rateId = $rs->fields['rate_id'];
				$bank_receipt = $rs->fields['bank_receipt'];
				$voucher_date = $rs->fields['voucher_date'];				
			
				$description = "Cash ".$account." On Voucher ".$voucher_no;
				$sumSubTotal += $subTotal;
				
				$currency = $rs->fields['currency'];
				if($currency == "IDR"){
					$subTotalIdr = $subTotal;
					$convertRate = 1;
				}else{
					$convertRate = $this->db->GetOne("select amount from rate where id = '".$rateId."'");
					if(empty($convertRate)){
						$this->debugLog("Error Get Rate From Pelunasan Cash : ".$pelunasan_id);
						$convertRate = 1;
					}
					$subTotalIdr = $subTotal * $convertRate;
				}

				// Posting Sub Total
				$sqlPostingSubTotal = "insert into jurnal (move, account, amount, amount_real, referal_id, type, latest_datetime, status, description, datetime) values ('C', '".$account."', '".$subTotalIdr."', '".$subTotal."', '".$pelunasan_id."', 'pl', now(), 'A', '".$description."', '".$voucher_date."')";
				$this->db->Execute($sqlPostingSubTotal);
				$this->debugLog("Posting Credit ".$sqlPostingSubTotal);

				$rs->MoveNext();		
			}
		}
		
		$sumSubTotalIdr = $sumSubTotal * $convertRate;
		
		// Posting Total
		$sqlPostingDebit = "insert into jurnal (move, account, amount, amount_real, referal_id, type, latest_datetime, status, description, datetime) values ('D', '".$bank_receipt."', '".$sumSubTotalIdr."', '".$sumSubTotal."', '".$pelunasan_id."', 'pl', now(), 'A', 'Bank Receipt Cash ".$voucher_no."', '".$voucher_date."')";
		$this->db->Execute($sqlPostingDebit);
		$this->debugLog("Posting Debit ".$sqlPostingDebit);
				
		return true;		
	
	}	
	
	function unPostToJournalFromPelunasanCash($pelunasan_id){

		$this->debugLog("Deleting Jurnal With Pelunasan ID ".$pelunasan_id);
		$this->db->Execute("update jurnal set status = 'R' where type = 'P' and referal_id = '".$pelunasan_id."'");

		return true;	
	}
	
	function unPostBankReceiptToJournalFromPelunasanCash($pelunasan_id, $bank_receipt){
		
		$this->debugLog("Deleting Jurnal With Pelunasan ID ".$pelunasan_id." And Bank Receipt ".$bank_receipt);
		$sqlDeleteJurnal = "update jurnal set status = 'R', latest_datetime = now(), latest_userid = '".$this->user->uid()."' where type = 'pl' and referal_id = '".$pelunasan_id."' and account = '".$bank_receipt."'";
		$this->db->Execute($sqlDeleteJurnal);
		$this->debugLog($sqlDeleteJurnal);
		
		$this->reCountPelunasanCash($pelunasan_id);
		
		return true;	
	}
	
	function reCountPelunasanCash($pelunasan_id){
		// Hitung Ulang
		$newAmount = $this->db->GetOne("select sum(amount) from pelunasan_detail_cash where pelunasan_id = '".$pelunasan_id."' and status = 'A'");
		$sqlReCountJurnal = "update jurnal set amount = '".$newAmount."', latest_datetime = now(), latest_userid = '".$this->user->uid()."' where type = 'pl' and referal_id = '".$pelunasan_id."' and move = 'D'";
		$this->debugLog($sqlReCountJurnal);
		$this->db->Execute($sqlReCountJurnal);

		$this->debugLog("Re Count Amount Pelunasan Cash");		
		$this->db->Execute("update pelunasan set amount = '".$newAmount."' where id = '".$pelunasan_id."'");
		
		return true;		
		
	}

	function postAccountToJournalFromPelunasanCash($pelunasan_id, $account, $amount){
		
		$this->debugLog("Inserting Jurnal With Pelunasan ID ".$pelunasan_id." And Account ".$account);
		
		$voucherNo = $this->db->GetOne("select voucher_no from pelunasan where id = '".$pelunasan_id."'");
		$currency = $this->pelunasanFormat($this->db->GetOne("select currency from pelunasan where id = '".$pelunasan_id."'"));
		
		if($currency == "IDR"){
			$amountIdr = $amount;
		}else{
			$convertRate = $this->db->GetOne("select amount from rate where id in (select rate_id from pelunasan where id = '".$pelunasan_id."')");
			$amountIdr = $convertRate * $amount;		
		}
		
		$this->db->Execute("insert into jurnal (move, account, amount, amount_real, referal_id, type, latest_datetime, status, description) values ('C', '".$account."', '".$amountIdr."', '".$amount."', '".$pelunasan_id."', 'pl', now(), 'A', 'Cash ".$account." On Voucher ".$voucherNo."')");		
		
		return true;	
	}


// === START PEMBAYARAN FUNCTION

	function postToJournalFromPembayaran($pembayaran_id){
		$this->debugLog("Func : postToJournalFromPembayaran, Start Posting From Invoice... ".$pembayaran_id);		
		$sql = "select pd.*, p.voucher_no, i.in_no, so.currency, p.voucher_date, p.bank_payment from pembayaran p join pembayaran_detail pd on p.id = pd.pembayaran_id join invoice_po i on pd.invoice_po_id = i.id join purchase_order so on i.purchase_order_id = so.id where pd.status = 'A' and p.id = ".$pembayaran_id;
		$this->debugLog($sql);

		$voucher_date = $rs->fields['voucher_date'];				

		$sumSubTotal = 0;
		$rs = $this->db->Execute($sql);
		if ($rs and !$rs->EOF) {
			while(!$rs->EOF) {
				$pembayaran_id = $rs->fields['pembayaran_id'];
				$in_no = $this->invoiceFormat($rs->fields['in_no']);
				$voucher_no = $this->pembayaranFormat($rs->fields['voucher_no']);
				$subTotal = $rs->fields['amount_dibayar'];

				$accountDebit = $rs->fields['ar_account'];
//				$accountCredit = $rs->fields['sl_account'];
				$accountCredit = $rs->fields['bank_payment'];
				$voucher_date = $rs->fields['voucher_date'];				
				
				$description = "Invoice ".$in_no." On Voucher ".$voucher_no;
				$sumSubTotal += $subTotal;
//				$total = $subTotal + ($subTotal / 10);
				$total = $subTotal;
				
				$currency = $rs->fields['currency'];
				if($currency == "IDR"){
					$subTotalIdr = $subTotal;
					$totalIdr = $total;
					$convertRate = 1;
					$sumTotalIdr += $subTotalIdr;
				}else{
					$convertRate = $this->db->GetOne("select amount from rate where currency = '".$currency."' order by valid_date desc");
					if(empty($convertRate)){
						$this->debugLog("Error Get Rate from Delivery Order : ".$do_id);
						$convertRate = 1;
					}
					$subTotalIdr = $subTotal * $convertRate;
					$totalIdr = $total * $convertRate;
					$sumTotalIdr += $totalIdr;
				}

				// Posting Total
				$sqlPostingTotal = "insert into jurnal (move, account, amount, amount_real, referal_id, type, latest_datetime, status, description, datetime) values ('D', '".$accountDebit."', '".$totalIdr."', '".$total."', '".$pembayaran_id."', 'pm', now(), 'A', 'Total ".$description."', '".$voucher_date."')";
				$this->db->Execute($sqlPostingTotal);
				$this->debugLog("Posting Debit ".$sqlPostingTotal);

				$rs->MoveNext();		
			}
		}

		// Posting Sub Total
		$sqlPostingSubTotal = "insert into jurnal (move, account, amount, amount_real, referal_id, type, latest_datetime, status, description, datetime) values ('C', '".$accountCredit."', '".$sumTotalIdr."', '".$sumSubTotal."', '".$pembayaran_id."', 'pm', now(), 'A', 'Sub Total ".$description."', '".$voucher_date."')";
		$this->db->Execute($sqlPostingSubTotal);
		$this->debugLog("Posting Credit ".$sqlPostingSubTotal);

		
/*		$tax = $sumSubTotal / 10;
		$taxIdr = $tax * $convertRate;
		
		// Posting Tax
		$sqlPostingTax = "insert into jurnal (move, account, amount, amount_real, referal_id, type, latest_datetime, status, description, datetime) values ('C', '".$accountCredit."', '".$taxIdr."', '".$tax."', '".$pembayaran_id."', 'pm', now(), 'A', 'Tax ".$voucher_no."', '".$voucher_date."')";
		$this->db->Execute($sqlPostingTax);
		$this->debugLog("Posting Tax ".$sqlPostingTax);
*/
		return true;		

	}
	
	function postToJournalFromPembayaranCash($pembayaran_id){
		$this->debugLog("Start Posting From Cash...");		
		$sql = "select pd.*, p.voucher_no, p.bank_payment, p.rate_id, i.name, p.voucher_date, pd.remark from pembayaran p join pembayaran_detail_cash pd on p.id = pd.pembayaran_id join cart_of_account i on pd.account = i.id where pd.status = 'A' and p.id = ".$pembayaran_id;
		$this->debugLog($sql);

		$sumSubTotal = 0;
		$rs = $this->db->Execute($sql);
		if ($rs and !$rs->EOF) {
			while(!$rs->EOF) {
				$pembayaran_id = $rs->fields['pembayaran_id'];
				$voucher_no = $this->pembayaranFormat($rs->fields['voucher_no']);
				$subTotal = $rs->fields['amount'];
				$account = $rs->fields['account'];
				$rateId = $rs->fields['rate_id'];
				$bank_payment = $rs->fields['bank_payment'];
				$voucher_date = $rs->fields['voucher_date'];
				$description = $rs->fields['remark'];
				$sumSubTotal += $subTotal;
				
				$currency = $rs->fields['currency'];
				if($currency == "IDR"){
					$subTotalIdr = $subTotal;
					$convertRate = 1;
					$sumSubTotalIdr += $subTotalIdr;
				}else{
					$convertRate = $this->db->GetOne("select amount from rate where id = '".$rateId."'");
					if(empty($convertRate)){
						$this->debugLog("Error Get Rate From Pembayaran Cash : ".$pembayaran_id);
						$convertRate = 1;
					}
					$subTotalIdr = $subTotal * $convertRate;
					$sumSubTotalIdr += $subTotalIdr;
				}

				// Posting Sub Total
				$sqlPostingSubTotal = "insert into jurnal (move, account, amount, amount_real, referal_id, type, latest_datetime, status, description, datetime) values ('D', '".$account."', '".$subTotalIdr."', '".$subTotal."', '".$pembayaran_id."', 'pm', now(), 'A', '".$description."', '".$voucher_date."')";
				$this->db->Execute($sqlPostingSubTotal);
				$this->debugLog("Posting Debit ".$sqlPostingSubTotal);

				$rs->MoveNext();		
			}
		}
			
		// Posting Total
		$sqlPostingDebit = "insert into jurnal (move, account, amount, amount_real, referal_id, type, latest_datetime, status, description, datetime) values ('C', '".$bank_payment."', '".$sumSubTotalIdr."', '".$sumSubTotal."', '".$pembayaran_id."', 'pm', now(), 'A', 'Bank Payment Cash ".$voucher_no."', '".$voucher_date."')";
		$this->db->Execute($sqlPostingDebit);
		$this->debugLog("Posting Credit ".$sqlPostingDebit);
				
		return true;		
	
	}	
	
	function unPostToJournalFromPembayaranCash($pembayaran_id){

		$this->debugLog("Deleting Jurnal With Pembayaran ID ".$pembayaran_id);
		$this->db->Execute("update jurnal set status = 'R' where type = 'pm' and referal_id = '".$pembayaran_id."'");

		return true;	
	}
	
	function unPostBankPaymentToJournalFromPembayaranCash($pembayaran_id, $bank_payment){
		
		$this->debugLog("Deleting Jurnal With Pembayaran ID ".$pembayaran_id." And Bank Payment ".$bank_payment);
		$sqlDeleteJurnal = "update jurnal set status = 'R', latest_datetime = now(), latest_userid = '".$this->user->uid()."' where type = 'pm' and referal_id = '".$pembayaran_id."' and account = '".$bank_payment."'";
		$this->db->Execute($sqlDeleteJurnal);
		$this->debugLog($sqlDeleteJurnal);
		
		$this->reCountPembayaranCash($pembayaran_id);
		
		return true;	
	}
	
	function reCountPembayaranCash($pembayaran_id){
		// Hitung Ulang
		$newAmount = $this->db->GetOne("select sum(amount) from pembayaran_detail_cash where pembayaran_id = '".$pembayaran_id."' and status = 'A'");
		$sqlReCountJurnal = "update jurnal set amount = '".$newAmount."', latest_datetime = now(), latest_userid = '".$this->user->uid()."' where type = 'pm' and referal_id = '".$pembayaran_id."' and move = 'C'";
		$this->debugLog($sqlReCountJurnal);
		$this->db->Execute($sqlReCountJurnal);

		$this->debugLog("Re Count Amount Pembayaran Cash");		
		$this->db->Execute("update pembayaran set amount = '".$newAmount."' where id = '".$pembayaran_id."'");
		
		return true;		
		
	}

	function postAccountToJournalFromPembayaranCash($pembayaran_id, $account, $amount){
		
		$this->debugLog("Inserting Jurnal With Pembayaran ID ".$pembayaran_id." And Account ".$account);
		
		$voucherNo = $this->pembayaranFormat($this->db->GetOne("select voucher_no from pembayaran where id = '".$pembayaran_id."'"));
		$currency = $this->db->GetOne("select currency from pembayaran where id = '".$pembayaran_id."'");
		$voucher_date = $this->db->GetOne("select voucher_date from pembayaran where id = '".$pembayaran_id."'");				
		$remark = $this->db->GetOne("select remark from pembayaran_detail_cash where pembayaran_id = '".$pembayaran_id."' and account = '".$account."' and amount = '".$amount."'");				
		
	//	$description = "Cash ".$account." On Voucher ".$voucherNo;
		$description = $remark;
		
		if($currency == "IDR"){
			$amountIdr = $amount;
		}else{
			$convertRate = $this->db->GetOne("select amount from rate where id in (select rate_id from pembayaran where id = '".$pembayaran_id."')");
			$amountIdr = $convertRate * $amount;		
		}
		
		$this->db->Execute("insert into jurnal (move, account, amount, amount_real, referal_id, type, latest_datetime, status, description, datetime) values ('D', '".$account."', '".$amountIdr."', '".$amount."', '".$pembayaran_id."', 'pm', now(), 'A', '".$description."', '".$voucher_date."')");		
		
		return true;	
	}
	
// === END PEMBAYARAN CASH FUNCTION			


// === START VOUCHER ===

	function postToJournalFromVoucher($voucher_id){
		$this->debugLog("Start Posting From Voucher...");		
		$sql = "select pd.*, p.voucher_no, p.rate_id, i.name, p.voucher_date from voucher p join voucher_detail pd on p.id = pd.voucher_id join cart_of_account i on pd.account = i.id where pd.status = 'A' and p.id = ".$voucher_id;
		$this->debugLog($sql);

		$sumSubTotal = 0;
		$rs = $this->db->Execute($sql);
		if ($rs and !$rs->EOF) {
			while(!$rs->EOF) {
				$voucher_id = $rs->fields['voucher_id'];
				$voucher_no = $this->pelunasanFormat($rs->fields['voucher_no']);
				$subTotal = $rs->fields['amount'];
				$account = $rs->fields['account'];
				$rateId = $rs->fields['rate_id'];
				$bank_receipt = $rs->fields['bank_receipt'];
				$voucher_date = $rs->fields['voucher_date'];
				
				if($subTotal >= 0){
					$move = "D";
					$moveLable = "Debit";
				}else{
					$move = "C";
					$moveLable = "Credit";
				}
			
				$description = "Voucher ".$account." On ".$voucher_no;
				$sumSubTotal += $subTotal;
				
				$currency = $rs->fields['currency'];
				if($currency == "IDR"){
					$subTotalIdr = $subTotal;
					$convertRate = 1;
				}else{
					$convertRate = $this->db->GetOne("select amount from rate where id = '".$rateId."'");
					if(empty($convertRate)){
						$this->debugLog("Error Get Rate From Voucher : ".$voucher_id);
						$convertRate = 1;
					}
					$subTotalIdr = $subTotal * $convertRate;
				}
				
				$subTotal = abs($subTotal);
				$subTotalIdr = abs($subTotalIdr);

				// Posting Sub Total
				$sqlPostingSubTotal = "insert into jurnal (move, account, amount, amount_real, referal_id, type, latest_datetime, status, description, datetime) values ('".$move."', '".$account."', '".$subTotalIdr."', '".$subTotal."', '".$voucher_id."', 'jv', now(), 'A', '".$description."', '".$voucher_date."')";
				$this->db->Execute($sqlPostingSubTotal);
				$this->debugLog("Posting ".$moveLable." ".$sqlPostingSubTotal);

				$rs->MoveNext();		
			}
		}
						
		return true;		
	
	}	
	
	function unPostToJournalFromVoucher($voucher_id){

		$this->debugLog("Deleting Jurnal With Voucher ID ".$voucher_id);
		$this->db->Execute("update jurnal set status = 'R' where type = 'jv' and referal_id = '".$voucher_id."'");

		return true;	
	}
	
	function unPostBankReceiptToJournalFromVoucher($voucher_id, $account){
		
		$this->debugLog("Deleting Jurnal With Voucher ID ".$voucher_id." And Account ".$account);
		$sqlDeleteJurnal = "update jurnal set status = 'R', latest_datetime = now(), latest_userid = '".$this->user->uid()."' where type = 'jv' and referal_id = '".$voucher_id."' and account = '".$account."'";
		$this->db->Execute($sqlDeleteJurnal);
		$this->debugLog($sqlDeleteJurnal);
			
		return true;	
	}
	
// === END VOUCHER ===	

	function GetArAccount($invoice_id){
		
		$account = $this->db->GetOne("select bs.account_credit from invoice i join  sales_order s on i.sales_order_id = s.id join sales_order_detail sod on sod.sales_order_id = s.id join barang_sales bs on bs.id = sod.barang_sales_id where i.id = '".$invoice_id."' order by bs.id");
		
		return $account;
	}
	
	function GetSlAccount($invoice_id){
		
		$account = $this->db->GetOne("select bs.account_debit from invoice i join  sales_order s on i.sales_order_id = s.id join sales_order_detail sod on sod.sales_order_id = s.id join barang_sales bs on bs.id = sod.barang_sales_id where i.id = '".$invoice_id."' order by bs.id");
		
		return $account;
	}
	
	function GetArAccountPO($invoice_po_id){
		
		$account = $this->db->GetOne("select bs.account_credit from invoice_po i join purchase_order s on i.purchase_order_id = s.id join purchase_order_detail sod on sod.purchase_order_id = s.id join barang_purchase bs on bs.id = sod.barang_purchase_id where i.status not in ('R') and i.id = '".$invoice_po_id."' order by bs.id");
		
		return $account;
	}
	
	function GetSlAccountPO($invoice_po_id){
		
		$account = $this->db->GetOne("select bs.account_debit from invoice_po i join  purchase_order s on i.purchase_order_id = s.id join purchase_order_detail sod on sod.purchase_order_id = s.id join barang_purchase bs on bs.id = sod.barang_purchase_id where i.status not in ('R') and i.id = '".$invoice_po_id."' order by bs.id");
		
		return $account;
	}
	
	function UnPostJurnalByInvPD(){
		$sqlUnPostingJurnal = "update jurnal";
		return true;
	}

	function getTicketNumber(){
		$rand = rand(1, 9999999);
		$checkRand = $this->db->GetOne("select ticket_number from open_ticket where ticket_number = '".$rand."'");
		if (!empty($checkRand)){
			$this->getBookingCode();
		}else{
			$checkRand = $this->db->GetOne("select ticket_number from open_ticket where ticket_number = '".$rand."'");
			if (!empty($checkRand)){
				$this->getBookingCode();
			}else{
				return $rand;
			}
		}
	}
	
	function getRates($from){

		$to = 'IDR';
		$url = 'http://finance.yahoo.com/d/quotes.csv?f=l1d1t1&s='.$from.$to.'=X';
		$this->debugLog($url);
		$handle = fopen($url, 'r');
		$this->debugLog($handle);
		 
		if ($handle) {
			$result = fgetcsv($handle);
			fclose($handle);
		}	 
		$this->debugLog('1 '.$from.' is worth '.$result[0].' '.$to.' Based on data on '.$result[1].' '.$result[2]);
		
		if(!empty($result[0])){
			$sql = "insert into rate (currency, amount, valid_date, insert_date) values ('".$from."', '".$result[0]."', date(now()), now())";
			$this->debugLog("Inserting Rate.. ".$sql);
			$this->db->Execute($sql);
		}else{
			$this->debugLog("Cannot Connect to URL");
		}
		
	}
	
	
	function manageStockBarangPurchase($id_barang, $quantity, $goodsPrice){

		$lastStock = $this->db->GetOne("select stock from barang_purchase where id = '".$id_barang."'");
		$lastAvgPrice = $this->db->GetOne("select price_average from barang_purchase where id = '".$id_barang."'");

		// If Average Price is empty, get from real price, should be only for the new goods.
		if(empty($avgPrice)){
			$lastAvgPrice = $this->db->GetOne("select price from barang_purchase where id = '".$id_barang."'");
		}

		// New Average Price
		$newAvgPrice = (($lastAvgPrice * $lastStock) + ($quantity * $goodsPrice)) / ($lastStock + $quantity);
		$newStock = $lastStock + $quantity;
		
		$sql = "update barang_purchase set price_average = '".$newAvgPrice."' where id = '".$id_barang."'";
		$this->debugLog("Updating Price Average : ".$sql);

/*		Change, Only Update Price Average
		$sql = "update barang_purchase set stock = '".$newStock."', price_average = '".$newAvgPrice."' where id = '".$id_barang."'";
		$this->debugLog("Updating Stock.. ".$sql);
*/

		$this->db->Execute($sql);
		
		return true;	
	}
	
	function decreaseStock($barangSalesId, $sentQuantity){
		
		$this->debugLog("Start Decrease Stock");
		// Get Formula
		$sql = "select bp.id as barang_purchase_id, bp.name, bps.formula, bp.stock from barang_purchase_sales bps join barang_purchase bp on bps.barang_purchase_id = bp.id where bps.barang_sales_id = '".$barangSalesId."'";
		$this->debugLog("Get Formula ".$sql);
		
		// Loop All Formula
		$rs = $this->db->Execute($sql);
		if ($rs and !$rs->EOF) {
			while(!$rs->EOF) {

				$formula = $rs->fields['formula'];
				$stock = $rs->fields['stock'];
				$barangPurchaseId = $rs->fields['barang_purchase_id'];
		
				$stockDiKirim = $sentQuantity * $formula / 100;
				
				// Decrease Stock
				$sqlUpdate = "update barang_purchase set stock = stock - ".$stockDiKirim." where id = '".$barangPurchaseId."'";
				if($this->db->Execute($sqlUpdate)){
					$this->debugLog("Updating Stock Success.. ".$sqlUpdate);		
				}else{
					$this->debugLog("Updating Stock Failure.. ".$sqlUpdate);					
				}
					
		
				$rs->MoveNext();		
			}
		}
		
		
		return true;	
	}

	function saveHashParamForJurnal($sql){
		$sql = str_replace("'","\'",$sql);	
		$hash = md5($sql);
		$online = $this->db->GetOne("select count(*) from save_sql where hash = '".$hash."'");   
		if($online == 0){
			$this->db->Execute("insert into save_sql values	('".$hash."','".$sql."', 1)");
		}else{
			$this->db->Execute("update save_sql set count = count + 1 where hash = '".$hash."'");			
		}
		return $hash;	
	}	
	
	function getPrintFolder(){
		
		return $this->print_folder_name;

	}
	

	function isTahunKabisat($angkaTahun) {
		return (bool) date('L', strtotime($angkaTahun.'-01-01'));
	}


	function reCountInvoiceAmount($invoice_id){
		// Hitung Ulang Amount Invoice, Biasa terjadi perubahan bila SO sebelumnya USD, kemudian di ubah menjadi IDR setelah invoice dibuat
		$newSubTotal = $this->db->GetOne("select sum(dod.dikirim*sod.price) as extended_price  from invoice_detail id  join delivery_order do on id.do_id = do.id join delivery_order_detail dod on dod.delivery_order_id = do.id join sales_order so on do.sales_order_id = so.id  join sales_order_detail sod on sod.sales_order_id = so.id and sod.barang_sales_id = dod.barang_sales_id join barang_sales bs on sod.barang_sales_id = bs.id and dod.barang_sales_id = bs.id where dod.dikirim > 0 and id.invoice_id = '".$invoice_id."'");
		
		$dipungut = $this->db->GetOne("select dipungut from invoice where id = ".$invoice_id);
		$otherAmount = $this->db->GetOne("select other_charge from invoice where id = ".$invoice_id);
		$discount = $this->db->GetOne("select discount from invoice where id = ".$invoice_id);
		
		if($dipungut == "yes"){
			$tax = $newSubTotal * 10 / 100;
		}else{
			$tax = 0;
		}
		
		$amount = $newSubTotal + $tax + $otherAmount - $discount;
		
		
		$sqlReCount = "update invoice set amount = '".$amount."', sub_total = '".$newSubTotal."', tax = '".$tax."' where id = '".$invoice_id."'";
		$this->debugLog($sqlReCount);
		$this->db->Execute($sqlReCount);	
		return true;		
		
	}
	
	function manageStock($id_barang, $ref_id, $type, $add_stock, $date){

		if(empty($date)){
			$date = date("Y-m-d"); 
		}
		
		if($type == "DO"){
			
			// Type = Delivery Order, Perlu di Check Formula barang purchasenya nya dulu untuk dikurangi stock nya.
//			$banyakBarang = $this->db->GetOne("select dikirim from delivery_order_detail where id = '".$ref_id."' order by id desc");
//			$this->debugLog("select dikirim from delivery_order_detail where id = '".$ref_id."' order by id desc ==> ".$banyakBarang);
			$banyakBarang = $add_stock;
		
			$sqlFormula = "select * from barang_purchase_sales where barang_sales_id = '".$id_barang."'";
			$this->debugLog($sqlFormula);
			$rs = $this->db->Execute($sqlFormula);
			if ($rs and !$rs->EOF) {
				while(!$rs->EOF) {
					$id_barang = $rs->fields['barang_purchase_id'];
					$add_stock = $rs->fields['formula'] * $banyakBarang / 100;
					$this->debugLog("Adding Stock [".$rs->fields['formula']."] * [".$banyakBarang."] / [100] ==> [".$add_stock."]");

					$lastFinalStock = $this->db->GetOne("select final_stock from stock where barang_purchase_id = '".$id_barang."' order by id desc");
					$this->debugLog("Get Last Final Stock [".$lastFinalStock."]");
					
					$finalStock = $lastFinalStock - $add_stock;
		
					// Insert Stock
					$sql = "insert into stock (barang_purchase_id, referal_id, type, datetime, add_stock, final_stock) values ('".$id_barang."','".$ref_id."','".$type."','".$date."', '".$add_stock."', '".$finalStock."')";
					$this->db->Execute($sql);
					$this->debugLog("DO Inserting Stock ".$sql);
					
					// Update Barang Purchase
					$sqlUpdate = "update barang_purchase set stock = ".$finalStock." where id = '".$id_barang."'";
					$this->db->Execute($sqlUpdate);
					$this->debugLog("Updating Master ".$sqlUpdate);
						
					$rs->MoveNext();
				}
			}
			
			if($rs->RecordCount() == 0){
				$this->debugLog("[ERROR] Formula [".$id_barang."] Belum Di Set");
			}
			
		}else{
			// Langsung Hitung Barang Purchase

			$lastFinalStock = $this->db->GetOne("select final_stock from stock where barang_purchase_id = '".$id_barang."' order by id desc");
			$this->debugLog("Get Last Final Stock [".$lastFinalStock."]");
			
			$finalStock = $lastFinalStock + $add_stock;
			$this->debugLog("Get Final Stock ".$finalStock);
		
			$sql = "insert into stock (barang_purchase_id, referal_id, type, datetime, add_stock, final_stock) values ('".$id_barang."','".$ref_id."','".$type."','".$date."', '".$add_stock."', '".$finalStock."')";
			$this->db->Execute($sql);
			$this->debugLog("Inserting Stock ".$sql);	
		
			$sqlUpdate = "update barang_purchase set stock = ".$finalStock." where id = '".$id_barang."'";
			$this->db->Execute($sqlUpdate);	
			$this->debugLog("Updating Master ".$sqlUpdate);
	
		}
		
		return true;		
	}
		
}
?>