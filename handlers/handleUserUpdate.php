<?
/* Update User via Web
 * Version: 12 27 2013
 * Author: Ruben Martinez Jr.
 */
session_start();
session_cache_expire(30);
require_once('../database/dbUser.php');
require_once('../domain/Encryption.php');

//parameters passed through URL
$converter    = new Encryption;
$userID       = $converter->decode($_SESSION["BowdoinDwSuserID"]);   //User ID
$firstname    = substr(safe($_POST["firstname"]),0,30);    //First Name
$lastname     = substr(safe($_POST["lastname"]),0,30);     //Last Name
$classYear    = substr(safe($_POST["class"]),0,30);        //Class Year
if($_POST["majorTwo"]) $major = substr(safe($_POST["major"]." and ".$_POST["majorTwo"]),0,30);
else $major   = substr(safe($_POST["major"]),0,30);
$likes        = implode(',',array_splice(explode(',', safe($_POST["likes"])), 0, 5));        //Likes
$discussion   = substr(safe($_POST["discuss"]),0,30);      //Topics of Interest
$gender       = substr(safe($_POST["gender"]),0,1);        //Gender
$interestedin = substr(safe($_POST["interestedin"]),0,1);  //Romantic Interests
//search the database for a date with matching criterion
$result = retrieve_dbUser($userID);

//if we find a matching user
if ($result != null) {
 if(!empty($firstname))        $result->set_firstName($firstname);
 if(!empty($lastname))         $result->set_lastName($lastname);
 if(!empty($photoURL))         $result->set_photoURL($photoURL);
 if(!empty($classYear))        $result->set_classYear($classYear);
 if(!empty($major))            $result->set_major($major);
 if(!empty($geographicOrigin)) $result->set_geographicOrigin($geographicOrigin);
 if(!empty($likes))            $result->set_likes($likes);
 if(!empty($discussion))       $result->set_discussion($discussion);
 if(!empty($gender))           $result->set_gender($gender);
 if(!empty($interestedin))     $result->set_interestedin($interestedin);
 if($result->get_access() > 0 && update_dbUser($result)) {
   echo "<div id=g-notification>You've successfully updated your information!</div>";
   return;
 }
} echo "<div id=b-notification>Something seems to have gone wrong while updating your information. Please try again later.</div>";
?>