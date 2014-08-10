<?php
require_once "../database/dbUser.php";
require_once "../database/dbStranger.php";
require_once "../database/dbMessage.php";
require_once "../domain/Encryption.php";

session_start();
$converter = new Encryption;
$user = retrieve_dbUser($converter->decode($_SESSION["BowdoinDwSuserID"]));
if(!$user) $user = retrieve_dbStranger($converter->decode($_SESSION["BowdoinDwSuserID"]));

if(($user instanceof User || $user instanceof Stranger) && $message = retrieve_dbMessage($_GET['messageID'])) {
 if($user->get_id() == $message->get_recipients()) $message->set_read("t");
 update_dbMessage($message);
 return;
}
?>