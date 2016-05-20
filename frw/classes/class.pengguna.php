<?
# Fungsi dari kelas ini untuk mengambil profil dari pengguna, tidak termasuk password
class pengguna {

	var $varKodeError = 0;
	var $arrStatusError = array (	1 => 'User tidak ditemukan');

	# Konstruktor kelas, sebagai argumen adalah pengenal user, dapat berupa userid atau username
	# apabila berupa username, maka argumen kedua $is_uid diset ke false.
	function pengguna($identifier = '', $is_uid = 1) {
		$this->db = buatKoneksiDB();
		if ($identifier != '') $this->ambil_profil($identifier, $is_uid);
	}

	# mengambil profil dasar user dari database
	function ambil_profil($identifier = '', $is_uid = 1) {
		if (is_array($this->profil)) unset($this->profil);
		if ($identifier != '') {
			$where_clause = $is_uid ? "where user_id = '".$identifier."'" : "where username = '".$identifier."'";
			$rs = $this->db->Execute("select user_id, username, fullname, is_superuser, store_id, lokasi_id, email from ".$GLOBALS['conf']['database']['name'].".user ".$where_clause);
			if (!$rs->EOF) {
				$this->profil['userid'] = $rs->fields['user_id'];
				$this->profil['username'] = $rs->fields['username'];
				$this->profil['email'] = $rs->fields['email'];
				$this->profil['fullname'] = $rs->fields['fullname'];
				$this->profil['is_superuser'] = $rs->fields['is_superuser'];
				$this->profil['login_session_id'] = $rs->fields['login_session_id'];
				$this->profil['additional_info'] = $rs->fields['additional_info'];
				$this->profil['store_id'] = $rs->fields['store_id'];
				$this->profil['lokasi_id'] = $rs->fields['lokasi_id'];
				
				$sqlGroups = "select group_id from ".$GLOBALS['conf']['database']['name'].".user_group where user_id = '".$this->uid()."'";
				//echo $sqlGroups;
				$rs2 = $this->db->Execute($sqlGroups);
				while(!$rs2->EOF) {
					$this->profil['groups'][] = $rs2->fields[0];
					$rs2->MoveNext();
				}
				$rs->MoveNext();
				return true;
			}
			else {
				$this->varKodeError = 1;
				return false;
			}
		}
		return false;
	}

# mengembalikan userid dari user
	function uid() {
		return ($this->profil['userid'] != '') ? $this->profil['userid'] : false;
	}

	function uidOld() {
		if($this->profil['user_id'] != ''){
			return $uid = 0;		
		}else{
			return $this->profil['user_id'];					
		}
	}

# mengembalikan username dari user
	function username() {
		return ($this->profil['username'] != '') ? $this->profil['username'] : false;
	}

# mengembalikan status user apakah superuser atau tidak
	function is_superuser() {
		return ($this->profil['is_superuser'] != '') ? $this->profil['is_superuser'] : false;
	}

# mengembalikan nama lengkap user
	function fullname() {
		return ($this->profil['fullname'] != '') ? $this->profil['fullname'] : false;
	}

# mengembalikan array id kelompok dimana user berada
	function groups() {
		return (is_array($this->profil['groups'])) ? $this->profil['groups'] : false;
	}
	
# mengembalikan nilai Additional Infor
	function additional_info() {
//		echo "a";
		return ($this->profil['additional_info'] != '') ? $this->profil['additional_info'] : false;
	}

# mengembalikan nilai session_id saat pertama kali login
	function login_session_id() {
		return ($this->profil['login_session_id'] != '') ? $this->profil['login_session_id'] : false;
	}	
	
# mengembalikan nilai Additional Infor
	function store_id() {
		return ($this->profil['store_id'] != '') ? $this->profil['store_id'] : false;
	}

# mengembalikan nilai Additional Infor
	function location() {
		return ($this->profil['lokasi_id'] != '') ? $this->profil['lokasi_id'] : false;
	}
	
	
}
?>