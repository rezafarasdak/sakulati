<?php
	session_start();
	header("Content-type: images/png");

	$alpha = '23478ABCDEFHPTU';
	$code  = "";
	for ($i=0;$i<5;$i++) {
		$code .= substr($alpha, rand(0,strlen($alpha)-1),1);
	}
	
	$_SESSION['_CAPTCHA'] = $code;
	$string = $code;
	$im     = imagecreatefrompng("images/captcha.png");
	$white  = imagecolorallocate($im, 255, 255, 255);
	imagefill($im,0,0,$white);
	$black  = imagecolorallocate($im, 0, 0, 0);
	$px     = (imagesx($im) - 7.5 * strlen($string)) / 2;
	imagestring($im, 5, $px,2, $string, $black);
	imagepng($im);
	imagedestroy($im);

?> 
