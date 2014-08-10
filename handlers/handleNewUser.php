<?
/* Create New User via Web
 * Version: 01 04 2014
 * Author: Ruben Martinez Jr.
 */
session_start();
require_once('../database/dbUser.php');
require_once('../domain/Encryption.php');
$userID = time().rand(1, 9999);
$exists = true;
while($exists) {
 if(retrieve_dbUser($userID)) $userID = time().rand(1, 9999);
 else $exists = false;
}
$email   = substr(safe($_POST["UID"]),0,30)."@bowdoin.edu";
$fname   = substr(safe($_POST["firstname"]),0,30);
$lname   = substr(safe($_POST["lastname"]),0,30);
$class   = substr(safe($_POST["class"]),0,30);
if($_POST["majorTwo"]) $major = substr(safe($_POST["major"]." and ".$_POST["majorTwo"]),0,30);
else $major = substr(safe($_POST["major"]),0,30);
$likes   = implode(',',array_splice(explode(',', safe($_POST["likes"])), 0, 5));
$discuss = "";
$access  = substr(safe($_POST["access"]),0,1);
$city    = "Brunswick, ME";
if(empty($rating)) $rating = 5;
if(empty($access)) $access = 1;
if(empty($email) || empty($fname) || empty($lname) || empty($class) || empty($likes)) $e = "Uh-oh, it looks like you didn't fill in all the fields. All fields are required.";
$user = retrieve_byEmail_dbUser($email); //search the database for existing user
if($user instanceof User) $e = "You already have an account with us. Try logging in!";
if(!empty($e)) { echo "<div id=b-notification>".$e."</div>"; return; }
elseif(insert_dbUser(new User($userID, $email, "", $fname, $lname, "", $class, $major, $city, $likes, $discuss, "0", "0", ":rmartin13764268493064", "", "", "", $rating, $access, "active"))) echo "<div id=g-notification>You're all done with registration! Now log in using your Bowdoin Username & Password.</div>";
else echo "<div id=b-notification>Looks like something went wrong with registration. Try again!</div>";
?>