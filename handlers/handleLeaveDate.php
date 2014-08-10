<?php
/* Delete Date
 * Version: 01 15 2014
 * Author: Ruben Martinez Jr.
 */
session_start();
require_once('../root.php');
require_once('../database/dbStranger.php');
require_once('../database/dbUser.php');
require_once('../database/dbDate.php');
require_once('../domain/Encryption.php');

//parameters passed through URL
$converter = new Encryption;
$userID = $converter->decode($_SESSION["BowdoinDwSuserID"]);
$dateID = safe($_POST["DID"]);

//search the database for a date with matching criterion
$date = retrieve_dbDate($dateID);
$user = retrieve_dbUser($userID);
if(!$user) $user = retrieve_dbStranger($userID);

if($date instanceof Date && $user) {
 $participants = explode(":",$date->get_participants());
 if(in_array($userID, $participants) && $date->get_size() == 1) $success = delete_dbDate($date->get_id());
 elseif(in_array($userID, $participants) && $date->get_size() > 1) { //remove leaving participant
  $newParticipants = implode(":", array_diff($participants, array($userID)));
  foreach(explode(":",$newParticipants) as $participant) {
   $thisuser = retrieve_dbUser($participant);
   if(!$thisuser) $thisuser = retrieve_dbStranger($participant);
   $to      = $thisuser->get_email();
   $subject = "Change of Plans";
   $message = "<h2 style='font-weight:normal;'>Hello, ".$thisuser->get_firstname()."! </h2><br><br>Bummer: It seems that someone has left the Dinner Date you had scheduled for ".$date->format_dateTimeLocation()."! Others can still join this Date, but for the time-being, you will be short one person. You can view more Date details from the <a href=http://DiningWithStrangers.co/viewDates.php>Dining with Strangers</a> website, under 'My Dates'.<br><br><h2 style='font-weight:normal;'>Happy Dining,<br><a href=http://DiningWithStrangers.co/>Dining with Strangers</a></h2>";
   $headers = "From: Dining with Strangers <date@DiningWithStrangers.co>\r\n";
   $headers .= "MIME-Version: 1.0\r\n";
   $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
   mail($to, $subject, $message, $headers);
  }
  //shrink size
  $newSize = $date->get_size()-1;
  $date->set_participants($newParticipants);
  $date->set_size($newSize);
  $success = update_dbDate($date);
 }
}
if($success) { //remove date from participant
 $newDates = implode(":", array_diff(explode(":", $user->get_futureDates()), array($dateID)));
 $user->set_futureDates($newDates);
 if(($user instanceof User && update_dbUser($user)) || update_dbStranger($user)) {
  echo "<div id=g-notification>You've successfully left this date.</div>";
  return;
 }
} echo "<div id=b-notification>We couldn't remove you from this date. Please try again later.</div>";
?>