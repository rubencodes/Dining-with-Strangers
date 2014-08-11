<?
/* Add To Contacts
 * Version: 16 August 2013
 * Author: Ruben Martinez Jr.
 */
include_once('mobileapps/DwS/Encryption.php');
include_once('root.php');
$converter = new Encryption;
$contact = $_GET['contact'];
$curl = curl_init();
curl_setopt_array($curl, array(
 CURLOPT_RETURNTRANSFER => 1,
 CURLOPT_URL => root().'database/submitContact.php?UID='.$_COOKIE['BowdoinDwSuserID'].'&contact='.$contact,
 CURLOPT_USERAGENT => 'Dining with Strangers'
));
$resp = curl_exec($curl);
curl_close($curl);
if(strcmp($resp, "false") == 0) 
	header('Location: '.root().'viewDates.php?contact=false');
else 
	header('Location: '.root().'viewDates.php?contact='.$converter->encode($contact));
?>