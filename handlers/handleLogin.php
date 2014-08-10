<?php
session_start();
session_cache_expire(30);
require_once('../database/dbStranger.php');
require_once('../database/dbUser.php');
require_once('../root.php');
require_once('../domain/Encryption.php');
$converter = new Encryption;

$url = 'https://www.bowdoin.edu/apps/mobile/login.php';
$userID = $_POST['username'];
$password = $_POST['password'];
$fields = array(
 'username'=>urlencode(stripslashes($userID)),
 'password'=>urlencode(stripslashes($password))
);

foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
rtrim($fields_string,'&');

$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_POST,count($fields));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
$result = curl_exec($ch);
curl_close($ch);

if($result == "0") { //if login unsuccessful
 $user = retrieve_byEmail_dbStranger(trim($userID));
 if($user instanceof Stranger) {
    if($converter->encode($password) == $user->get_password()) {
     if($user->get_active() != "f") $_SESSION['BowdoinDwSuserID'] = $converter->encode($user->get_id());
     else echo "<div id=b-notification>Your account is not yet active. Check your email for activation instructions!</div>";
    } else echo "<div id=b-notification>That username/password combination is incorrect.</div>";
 } else echo "<div id=b-notification>Your password is incorrect, or you don't have an account with us yet. Try again or sign up!</div>";
}
else {
 $user = retrieve_byEmail_dbUser(trim($userID)."@bowdoin.edu");
 $_SESSION['BowdoinDwSuserID'] = $converter->encode($user->get_id());
 //if we find an existing user
 if(!$user) echo "<div id=b-notification>You don't seem to have an account yet. Get started by signing up!</div>";
}
?>