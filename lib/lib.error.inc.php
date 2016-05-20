<?
#--------------------------------------------------------------
# Nama File  : lib.error.inc.php
# Pembuat    : Faisal Hidayat <faisal_h@staff.gunadarma.ac.id>
# Tanggal    : 21-05-2003
# Deskripsi  :
#--------------------------------------------------------------

function error_handle($type, $msg, $filename, $ln, $vars) {
	global $pustaka;
	switch($type) {
		case E_USER_WARNING:
		case E_USER_NOTICE:
			$pustaka->msgbox($msg);
			break;
		case E_WARNING:
		case E_COMPILE_WARNING:
		case E_COMPILE_ERROR:
		case E_CORE_WARNING:
		case E_CORE_ERROR:
		case E_ERROR:
			$pustaka->errmessage("<b>Kesalahan pada file $filename baris $ln </b>: $msg");
			break;
		case E_USER_ERROR:
			$pustaka->errmessage($msg);
			break;
	}
}
