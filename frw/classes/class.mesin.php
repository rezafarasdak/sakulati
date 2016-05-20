<?
# Template kelas utama, fungsi2 dibawah adalah fungsi2 public yang harus didefinisikan oleh kelas turunannya

class mesin {

	# Konstruktur
	function mesin() {
		# biasanya digunakan untuk menginisiasi header, session, dan menginstantiasi kelas otentikasi, user, dan hak akses
		# harus didefinisikan oleh kelas turunan
		$this->message = '';
	}

	# dipanggil oleh modul di awal kode
	function init() {
		# biasanya berisi mekanisme pemeriksaan hak akses dan/atau menyimpan output dari modul kedalam memori (output buffering)
		# harus didefinisikan oleh kelas turunan
	}

	# dipanggil oleh modul di baris terakhir kode
	function footer() {
		# biasanya bertujuan menangkap output dari buffer, memuat blok dan menggabungkan ke kerangka tampilan serta menampilkannya
		# harus didefinisikan oleh kelas turunan
	}

	# Menambahkan pesan pada 
	function nextmessage($message) {
		# atribut message diisi oleh fungsi ini
		$this->message = $message;
	}

	# Menampilkan pesan kesalahan secara elegan dan menghentikan proses
	/*function errmessage() {
		# harus didefinisikan oleh kelas turunan
	}*/
}
?>