<?
/* Submit Date via Web
 * Version: 12 27 2013
 * Author: Ruben Martinez Jr.
 */
session_start();
require_once('../database/dbStranger.php');
require_once('../domain/Encryption.php');

$converter = new Encryption;
$userID = $converter->decode($_SESSION["BowdoinDwSuserID"]);
//search the database for a user with matching criterion
$result = retrieve_dbStranger($userID);
$email    = substr(safe($_POST["email"]),0,30);
$password = substr(safe($_POST["password"]),0,30);
$fname    = substr(safe($_POST["firstname"]),0,30);
$lname    = substr(safe($_POST["lastname"]),0,30);
$type     = substr(safe($_POST["type"]),0,30);
$location = substr(safe($_POST["location"]),0,30);
$likes    = substr(safe($_POST["likes"]),0,30);
$discuss  = substr(safe($_POST["discuss"]),0,30);
$gender       = substr(safe($_POST["gender"]),0,1);
$interestedin = substr(safe($_POST["interestedin"]),0,1);

//if we find a matching user
if($result instanceof Stranger && $result->get_access() > 0) {
 if(!empty($password))  $result->set_password($converter->encode(substr(trim($_POST["password"]),0,30)));
 if(!empty($fname))     $result->set_firstname($fname);
 if(!empty($lname))     $result->set_lastname($lname);
 if(!empty($type))      $result->set_type($type);
 if(!empty($location))  $result->set_location($location);
 if(!empty($likes))     $result->set_likes($likes);
 if(!empty($discuss))   $result->set_discussion($discuss);
 if(!empty($gender))       $result->set_gender($gender);
 if(!empty($interestedin)) $result->set_interestedin($interestedin);
 if(!empty($email)) { //if user is updating email address
  $result->set_id($email);
  if(!retrieve_dbStranger($email) && insert_dbStranger($result) && delete_dbStranger($userID))
   echo "<div id=g-notification>You've successfully updated your information!</div>";
  else echo "<div id=b-notification>That email address is already in use! Please enter an unused email.</div>";
 } elseif(update_dbStranger($result)) echo "<div id=g-notification>You've successfully updated your information!</div>";
} else echo "<div id=b-notification>Something seems to have gone wrong while updating your information. Please try again later.</div>";
?>