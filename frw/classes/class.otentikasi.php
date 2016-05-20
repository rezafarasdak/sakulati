<?
class otentikasi {

	var $varKodeError = 0;
	var $arrStatusError = array (	1 => 'Username dan password tidak boleh kosong', 
									2 => 'User dinonaktifkan.',
									3 => 'Username dan password salah',
									4 => 'User tidak ditemukan');

	function otentikasi() {
		$this->db = buatKoneksiDB();
	}

	function kode_error() {
		return $this->varKodeError > 0 ? $this->varKodeError : false;
	}
	function teks_error() {
		return $this->varKodeError > 0 ? $this->arrStatusError[$this->varKodeError] : '';
	}


# Memproses validasi username dan password yang dimasukkan user
	function login($username, $password, $verifikasi = '') {
		$login_status = false;
		if ($username == ''  or $password == '') {
			$this->varKodeError = 1;
			return false;
		}

		$rs = $this->db->Execute("select username, md5(concat(userpassword,'".$verifikasi."')) as passwd, is_active, additional_info, store_id from  user where username = '". $username. "'");

		if (!$rs->EOF) {
			if (!$rs->fields['is_active']) {
				$this->varKodeError = 2;			
			}
			elseif ($rs->fields['passwd'] != $password) {
				$this->varKodeError = 3;
			}
			else {
				$pengguna = new pengguna($rs->fields['username'], 0);
				$_SESSION['user'] = $pengguna;
				$_SESSION['additional_info'] = $rs->fields['additional_info'];
				// Update users, set session_id saat login
				$sqlUpdateSessionId = "update user set login_session_id = '".session_id()."' where username = '".$username."'";
				$rs2 = $this->db->Execute($sqlUpdateSessionId);
				
				$login_status = true;
			}
		}
		else {
			$this->varKodeError = 4;
		}

		if (!$login_status) {
			$this->cleanup();
			return false;
		}
		else {
			return true;
		}
	}

	function loginform() {
		$t = buatTemplate('_main');
		$t->set_file('hdl_login', 'login.html');
		$t->set_var('verifikasi', "img.php?size=6");
		$t->set_var('login action', self_url('login', 'continue'));
		$t->parse('out', 'hdl_login');
		return $t->get('out');
	}

	function is_login(){
		if (!is_object($_SESSION['user'])) {
			$_SESSION['user'] = '';
			unset($_SESSION['user']);
			return false;
		}
		else return true;
	}

	function cleanup() {
		$_SESSION['user'] = '';
		unset ($_SESSION['user']);
		session_regenerate_id();
	}

	function logout() {
		$pengguna = $_SESSION['user'];
		$this->db->Execute("delete from active_user where user_id = '". $pengguna->uid()."'");
		$this->cleanup();
		header('Location: index.php');
		exit;	
	}

# Mengembalikan username yang sedang login
	function user_logon() {
		return is_object($_SESSION['user']) ? $_SESSION['user'] : false;
	}
}
?>