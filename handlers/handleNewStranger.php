<?
/* Create New Stranger
 * Version: 01 04 2014
 * Author: Ruben Martinez Jr.
 */
require_once('../database/dbStranger.php');
require_once('../domain/Encryption.php');
$sfx = "UCF.edu";
if(strcasecmp(substr($_POST["email"], -strlen($sfx)), $sfx) != 0) $e = "Uh-oh, that's not an @ucf.edu email address. Please try again!";
$convert = new Encryption;
$userID  = time().rand(1, 9999);
$exists  = true;
while($exists) {
 if(retrieve_dbStranger($userID)) $userID = time().rand(1, 9999);
 else $exists = false;
}
$email    = trim($_POST["email"]);
$password = $convert->encode(substr(trim($_POST["password"]),0,30));
$fname    = substr(safe($_POST["firstname"]),0,30);
$lname    = substr(safe($_POST["lastname"]),0,30);
$type     = substr(safe($_POST["type"]),0,30);
$location = substr(safe($_POST["location"]),0,30);
$city     = substr(safe($_POST["city"]),0,30);
$likes    = implode(',',array_splice(explode(',', safe($_POST["likes"])), 0, 5));
$discuss  = "";
$access   = substr(safe($_POST["access"]),0,1);
if(empty($rating)) $rating = 5;
if(empty($access)) $access = 1;
if(empty($password) || filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) == false || empty($fname) || empty($lname) || empty($type) || empty($location) || empty($city) || empty($likes))
 $e = "Uh-oh, it looks like you didn't fill in all the fields. All fields are required.";
$user = retrieve_byEmail_dbStranger($email);
if ($user instanceof Stranger) $e = "Uh-oh, it looks like that email is already in use. Please try another.";
else { //if no existing user, create a new user object
 if(insert_dbStranger(new Stranger($userID, $email, $password, $fname, $lname, $type, $location, $city, $likes, $discuss, "0", "0", ":rmartin13764268493064", "", "", "", $rating, $access, "f"))) {
  $to      = $email;
  $subject = "You Have a New Message!";
  $message = "<h2 style='font-weight:normal;'>Hello, ".$fname."! </h2><br><br>Dining with Strangers is glad to welcome you into our family! To get started with your dining adventure, visit <a href=http://DiningWithStrangers.co/?id=".$userID."&email=".urlencode($email).">this link to verify your account</a>. Then sign in to join your first dining date, or create a date for others to join!<br><br><h2 style='font-weight:normal;'>Happy Dining, <br><a href=http://DiningWithStrangers.co/>Dining with Strangers!</a></h2>";
  $headers = "From: Dining with Strangers <date@DiningWithStrangers.co>\r\n";
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
  mail($to, $subject, $message, $headers);
  //send confirmation email here
  echo "<div id=g-notification>Your registration is almost complete! Check your email to activate your account.</div>";
 }
 else $e = "Uh-oh, it looks like something went wrong during registration. Please try again later.";
}
if(!empty($e)) { echo "<div id=b-notification>".$e."</div>"; }
return;
?>