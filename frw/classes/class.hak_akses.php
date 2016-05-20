<?
class hak_akses {

	function hak_akses() {
		$this->db = buatKoneksiDB();
	}

# Memeriksa hak akses

	function user_perms($siteID, $appid, $user) {
		if ($appid == '') {
			return true;
		}
		else {
			$rs = $this->db->Execute("select * from module where name = '".$appid."' and siteID = '". $siteID. "' and is_public = 1");
			
			if (!$rs->EOF) {
				return true;
			}
			else {
				if (!is_object($user)) {
					return false;
				}
				else {
					if ($user->is_superuser()) {
						return true;
					}
					else {
						
						$rs = $this->db->Execute("select * from module where name = '". $appid. "' and (name in (select a.name from module a, group_module_priv b, user_group c  where  a.module_id = b.module_id and a.siteID = '". $siteID. "' and b.group_id = c.group_id and c.user_id = '". $user->uid(). "' or is_public = 1))");
						if (!$rs->EOF) return true;
						else return false;
					}
				}
			}
		}
	}




# Sama seperti user_perms tetapi akan menampilkan pesan error jika user tidak
# memiliki hak akses
	function check_perms($siteID, $appid, $user) {
		if (!$this->user_perms($siteID, $appid, $user)) {
			$t = buatTemplate('_main');
			$t->set_file('hdl_error', 'error_page.html');
			$t->set_var('link','index.php?login=yes');
			$t->pparse('out', 'hdl_error');
			exit;
		}
		else {
			return true;
		}
	}
}
?>