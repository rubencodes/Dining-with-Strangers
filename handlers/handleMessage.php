<?php
/* Send Message
 * Version: 01 13 2013
 * Author: Ruben Martinez Jr.
*/
session_start();
session_cache_expire(30);
require_once('../database/dbStranger.php');
require_once('../database/dbUser.php');
require_once('../database/dbMessage.php');
require_once('../domain/Encryption.php');

$converter  = new Encryption;
$userID = $converter->decode($_SESSION["BowdoinDwSuserID"]);
$user = retrieve_dbUser($userID);
if(!$user) $user = retrieve_dbStranger($userID);

$recipients = implode(":", array_diff(explode(":", safe($_POST['contacts'])), array($userID)));
$payload    = safe($_POST['payload']);
$encoded    = $converter->encode($payload); //encoded message, for security
$timestamp  = time();
$read       = "f";

if(empty($payload)) {
 echo "<div id=b-notification>Cannot send empty message! Please enter a message to send.</div>";
 return;
}
elseif(empty($recipients)) {
 echo "<div id=b-notification>Please enter a recipient before sending message.</div>";
 return;
}
$sent = true;
if($user instanceof User || $user instanceof Stranger) { //if we find a matching User
foreach(explode(':',$recipients) as $ID) { //loop through array of users
 $thisuser = retrieve_dbUser($ID);
 if(!$thisuser) $thisuser = retrieve_dbStranger($ID);
 if($thisuser instanceof User || $thisuser instanceof Stranger) {
  $to      = $thisuser->get_email();
  $subject = "You Have a New Message!";
  $message = "<h2 style='font-weight:normal;'>Hello, ".$thisuser->get_firstname()."! </h2><br><br>You just received a new message from someone on Dining with Strangers. To read it, send a message back, or see all of your other mail, visit <a href=http://DiningWithStrangers.co/>Dining with Strangers</a> and click on the Message icon at the top.<br><br><h2 style='font-weight:normal;'>Happy Dining, <br><a href=http://DiningWithStrangers.co/>Dining with Strangers!</a></h2>";
  $headers = "From: Dining with Strangers <date@DiningWithStrangers.co>\r\n";
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
  mail($to, $subject, $message, $headers);
  $MID = $userID.$timestamp.mt_rand(1000, 9999);  //Generate Message ID
  if(!insert_dbMessage(new Message($MID, $encoded, $userID, $ID, $timestamp, $read))) $sent = false;
 }
}
}
if($sent) echo "<div id=g-notification>Message sent!</div>";
else echo "<div id=b-notification>Uh-oh, something went wrong while sending your message. Please try again later!</div>";
return;
?>