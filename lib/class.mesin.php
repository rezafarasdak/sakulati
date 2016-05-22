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
	
	
	function reCountLuasLahan($id_lahan){
		// Hitung Ulang
		$this->debugLog("Re Count Luas Lahan");
		$newValue = $this->db->GetOne("select sum(luas) from lahan where type = 'C' and id_lahanutama = '".$id_lahan."' and status = 1");
		$sqlReCount = "update lahan set luas = '".$newValue."' where type = 'M' and id = '".$id_lahan."'";
		$this->debugLog($sqlReCount);
		$this->db->Execute($sqlReCount);
	
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