<?
/* Submit Bug Report
 * Version: 01 04 2014
 * Author: Ruben Martinez Jr.
 */
session_start();
session_cache_expire(30);
require_once('../database/connect.php');
require_once('../domain/Encryption.php');
$converter  = new Encryption;
$userID = $converter->decode($_SESSION["BowdoinDwSuserID"]);
$BR = safe($_POST['BR']);
$BR = "From User: ".$userID."<br>".$BR;
$mysqli = connection();
if(($stmt = $mysqli->prepare("INSERT INTO dbBR (BR) VALUES (?)"))) {
 $stmt->bind_param("s", $BR);
 if(!$stmt->execute()) {
  echo "<div id=b-notification>Your Comment Card could not be submitted at this time. Please try again later.</div>";
  return;
 }
 $stmt->close();
 $mysqli->close();

$to = "ruben@diningwithstrangers.co";
$subject = "BUG REPORT";
$message = $BR;
$headers = "From: Dining with Strangers <error@DiningWithStrangers.co>\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
mail($to, $subject, $message, $headers);

 echo "<div id=g-notification>Your Comment Card has been sent to our team! Thanks for your help.</div><br>";
} else echo "<div id=b-notification>Your Comment Card could not be submitted at this time. Please try again later.</div>"
?>