<?php
/**
 * Logiciel : exemple d'utilisation de HTML2PDF
 * 
 * Convertisseur HTML => PDF
 * Distribu� sous la licence LGPL. 
 *
 * @author		Laurent MINGUET <webmaster@html2pdf.fr>
 * 
 * isset($_GET['vuehtml']) n'est pas obligatoire
 * il permet juste d'afficher le r�sultat au format HTML
 * si le param�tre 'vuehtml' est pass� en param�tre _GET
 */

 	// r�cup�ration du contenu HTML
// 	ob_start();
//	http://localhost/belajar/faktur.html
// 	http://localhost/penyewaan/index.php?appid=admin_order&sub=detailForPdf&id=111231009
 	$content = file_get_contents('http://localhost/penyewaan/index.php?appid=admin_order&sub=detailForPdf&id=111231009');
// 	include(dirname(__FILE__).'/res/exemple00.php');
//	$content = ob_get_clean();
	
	// conversion HTML => PDF
	require_once(dirname(__FILE__).'/../html2pdf.class.php');
	try
	{
		$html2pdf = new HTML2PDF('P','A4','fr', false, 'ISO-8859-15');
//		$html2pdf->setModeDebug();
		$html2pdf->setDefaultFont('Arial');
		$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
		$html2pdf->Output('exemple00.pdf');
	}
	catch(HTML2PDF_exception $e) { echo $e; }
	