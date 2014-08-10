<?php
/* Submit Date/Event
 * Version: 01 03 2014
 * Author: Ruben Martinez Jr.
 */
session_start();
require_once('../database/dbUser.php');
require_once('../database/dbStranger.php');
require_once('../database/dbDate.php');
require_once('../database/dbPartner.php');
require_once('../domain/Encryption.php');
require_once('../root.php');
$converter = new Encryption;
$userID = $converter->decode($_SESSION["BowdoinDwSuserID"]);	//User creating date's ID
$user = retrieve_dbUser($userID);
if(!$user) $user = retrieve_dbStranger($userID);
if(!$user) $e = "You're not signed in correctly. Please log in before creating or joining a date.";
else {
 $DID = safe($_POST['DID']);	//Date ID if joined a date
 if($date = search_dbDate($userID, $DID,"","","","")) $dateTime = $date->get_dateTime();
 else {
  $name       = safe($_POST["name"]);	 //Name of Date, optional
  $size       = safe($_POST["size"]);	 //Desired size of date
  $dayOfDate  = safe($_POST["date"]);     //Day of Date
  $timeOfDate = safe($_POST["time"]);     //Time of Date
  $location   = safe($_POST["location"]); //Partner ID of Date, or Location if Event
  $city       = $user->get_city();        //Origin City
  if(empty($_POST["gender"]) || empty($_POST["interestedin"])) {
   $romance = "false";
   if(empty($name)) {
    $name = "Dining Date";
    if(!is_numeric($size) && $size > 5 || $size < 2) $e = "Dining Dates can have a maximum of 5 people, a minimum of two. Please try again.";
    if(!retrieve_dbPartner($location)) $e = "That is not a valid location! Please try again.";
   }
   else {
    $name = "Custom Event, ".$name.",";
    if(strlen($name) < 5)     $e = "Please enter a more descriptive name before submitting (minimum 5 letters).";
    if(strlen($location) < 5) $e = "Please enter a more descriptive location before submitting (minimum 5 letters).";
    if(!is_numeric($size) || $size > 10 || $size < 3)      $e = "Events can hold a maximum of 10 guests, a minimum of 3. Please try again.";
    if(strtotime($dayOfDate) > strtotime("now + 7 days"))  $e = "Please enter a date within the next week.";
   }
  }
  else {
   $name = "Romantic Date";
   $romance  = safe($_POST["gender"].$_POST["interestedin"]);
   $size = 2;
   if(!retrieve_dbPartner($location)) $e = "That is not a valid location! Please try again.";
  }
  $dateTime = strtotime($dayOfDate." ".$timeOfDate); //formatting date and time
  $date = search_dbDate($userID, $DID, $size, $dateTime, $location, $romance);
 }
 foreach(explode(":", $user->get_futureDates()) as $myDateID) {
  $myDate = retrieve_dbDate($myDateID);
  if($myDate instanceof Date && ($myDate->get_dateTime() < $dateTime+3600 && $myDate->get_dateTime() > $dateTime-3600)) $e = "You already have a date at or near this time. Please select another time.";
 }
 if($dateTime < strtotime("- 1 hours")) $e = "Sorry, that date has already passed or is too soon. Please try again.";
}
if(!empty($e)) { echo "<div id=b-notification>".$e."</div>"; return; }

$taken = false;
$joined = false;
$new = false;
//if we find a matching date
if ($date instanceof Date) {
 $oldParticipants = $date->get_participants();
 $newParticipants = $date->get_participants().":".$userID; //append this user's ID to list of date participants
 $newSize         = $date->get_size()+1; //increment number of people currently enrolled
 //update date information with new information
 $date->set_participants($newParticipants);
 $date->set_size($newSize);
 if (update_dbDate($date)) {
  $partner = retrieve_dbPartner($location);
  if($partner) if($partner->get_promos() != "") $discount = ", and remember ".urldecode($partner->get_promos());
  foreach (explode(":", $oldParticipants) as $UID) {
   $thisuser = retrieve_dbUser($UID);
   if(!$thisuser) $thisuser = retrieve_dbStranger($UID);
   $to = $thisuser->get_email();
   $subject = "Someone Joined Your Date!";
   $message = "<h2 style='font-weight:normal;'>Hello ".$thisuser->get_firstname().", </h2>Someone new has joined the ".$date->get_name()." you created at ".$date->format_dateTimeLocation()."! You can view your date details from the Dining with Strangers website, under the 'My Dates' filter, or you can visit the Messaging Center to let your date(s) know where to meet you. Don't be late".$discount."! <br><br><h2 style='font-weight:normal;'>Happy Dining,<br><a href=http://DiningWithStrangers.co/>Dining with Strangers</a></h2>";
   $headers = "From: Dining with Strangers <date@DiningWithStrangers.co>\r\n";
   $headers .= "MIME-Version: 1.0\r\n";
   $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
   mail($to, $subject, $message, $headers);
  }
  $to = $user->get_email();
  $subject = "You Joined a Date!";
  $message = "<h2 style='font-weight:normal;'>Hello ".$user->get_firstname().", </h2>You just became a part of a ".$date->get_name()." at ".$date->format_dateTimeLocation()."! You can view your date details from the Dining with Strangers website, under the 'My Dates' filter, or you can visit the Messaging Center to let your date(s) know where to meet you. Don't be late".$discount."! <br><br><h2 style='font-weight:normal;'>Happy Dining,<br><a href=http://DiningWithStrangers.co/>Dining with Strangers</a></h2>";
  $headers = "From: Dining with Strangers <date@DiningWithStrangers.co>\r\n";
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
  mail($to, $subject, $message, $headers);

  //create list of dates for user
  $dateList = $date->get_id();
  $futureDates = $user->get_futureDates();
  if (!empty($futureDates)) $dateList = $user->get_futureDates().":".$dateList; //add date to date list
  //update user with new information
  $user->set_futureDates($dateList);
  if($user instanceof User) update_dbUser($user);
  elseif($user instanceof Stranger) update_dbStranger($user);
  $joined = true; //user joined an existing date
 }
}
elseif(!empty($DID)) $taken = true; //if we didn't find a date by this DID
else { //if no existing date
 //generate a unique random ID
 $newDateID = time().rand(1, 9999);
 $dateID    = $newDateID . $dateTime;
 //create a new date object
 if (insert_dbDate(new Date($dateID, $name, $userID, 1, $size, $dateTime, $location, $user->get_city(), $romance, "true"))) {
  //create list of dates for user
  $dateList = $dateID;
  $futureDates = $user->get_futureDates();
  if (!empty($$futureDates)) $dateList .= ":".$user->get_futureDates(); //add date to date list
  //update user with new information
  $user->set_futureDates($dateList);
  if($user instanceof User) update_dbUser($user);
  elseif($user instanceof Stranger) update_dbStranger($user);
  $new = true; //user created a new date
 }
}

if($joined) echo "<div id=g-notification>Congratulations! The date you just created is all ready to go. Hint: Send your fellow diners a message to let them know where to meet you.</div>";
elseif($new) echo "<div id=g-notification>Your date has been submitted! You will be notified by email when a match has been made.</div>";
elseif($taken) echo "<div id=b-notification>Sorry, this date was taken! Please refresh your browser.</div>";
else echo "<div id=b-notification>Sorry, we seem to be having technical difficulties. Please try again later.</div>";
?>