<?php
set_time_limit(300000);
require_once 'Parsingcoupon.php';
require_once 'phpMailer/class.phpmailer.php';
require_once 'phpMailer/class.smtp.php';
require_once 'model/CouponParse.php';

$parsing = new Parsingcoupon();
$sites = $parsing->getCouponWebsites();
$parser = new Parser();

$siteData  = array();
foreach ($sites as $site)
{
	require_once 'Coupon/'.$site.'.php';
	$siteObj = new $site;
	$html = '';
	$url = $siteObj->getUrl();
	if($url){
		$html = $parser->getHtml($url);
	}
	try
	{
		$pagecount = 1;
		$a = $siteObj->updateCoupons($html);
		if(empty($siteData)){
			$siteData = $a;
		}else{
			$siteData = array_merge($siteData,$a);
		}
	}
	catch(Exception $e)
	{
		echo"<pre>";
		print_r($e);
		echo"</pre>";
	}
}
$message = '';
$message .= "
<style>
<!--
*{margin: 0;padding: 0;}
h4{display: inline;}
h5{display: inline;}
.eachsite{margin-top:30px;}
.item{ margin-left: 20px;}
-->
</style>
";

//echo $message;
$to = 'manish@excellencetechnologies.in';
$today = date("D M j Y h:i");
$subject = 'Found New Coupons ' . sizeof($siteData) . ' on '.$today;
$message = '';
$mail = new phpmailer();
//$mail->IsSMTP();
//$mail->Host = 'ssl://smtp.gmail.com:465';
//$mail->SMTPAuth   = true;
//$mail->SMTPSecure = "ssl";
//$mail->Host       = "smtp.gmail.com";
//$mail->Port       = 465;
///$mail->Username   = "excellenceseo@gmail.com";
//$mail->Password   = "seo@1234";
$mail->setFrom('excellenceseo@gmail.com', 'PriceGenie');
$mail->Subject    =$subject;
$message = 'Finding Coupon';
$mail->MsgHTML($message);

$mail->AddAddress($to, "Manish");

if(!$mail->Send()) {
	echo "Mailer Error: " . $mail->ErrorInfo;
} else {
	echo "Message sent!";
}
?>