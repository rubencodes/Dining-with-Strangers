<?
include_once('root.php');
$contact = $_GET['contact'];

$curl = curl_init();
curl_setopt_array($curl, array(
 CURLOPT_RETURNTRANSFER => 1,
 CURLOPT_URL => root().'database/deleteContact.php?UID='.$_COOKIE['BowdoinDwSuserID'].'&contact='.$contact,
 CURLOPT_USERAGENT => 'Dining with Strangers'
));
$resp = curl_exec($curl);
curl_close($curl);
if(strcmp($resp, "true") == 0) header('Location: '.root().'updateuser.php?rmcontact=true');
else header('Location: '.root().'updateuser.php?rmcontact=false');
?>