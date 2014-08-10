<?php
require_once "../database/dbMessage.php";
require_once "../database/dbStranger.php";
require_once "../database/dbUser.php";
require_once "../domain/Encryption.php";
session_start();
$converter = new Encryption;
$user = retrieve_dbUser($converter->decode($_SESSION["BowdoinDwSuserID"]));
if(!$user) $user = retrieve_dbStranger($converter->decode($_SESSION["BowdoinDwSuserID"]));
$messageArray = retrieveMy_dbMessages($user->get_id());
if(empty($messageArray)) echo "<p style=text-align:center;background-color:#555;padding:10px;>No Messages</p>";
else {
  $count = 0;
  foreach($messageArray as $message) {
    $count++;
    $sender = $message->get_sender();
    $thesender = retrieve_dbUser($sender);
    if(!$thesender) $thesender = retrieve_dbStranger($sender);
    if($thesender) {
      $sender_name = $thesender->get_firstname();
      $payload = $message->get_payload();
      $timestamp = $message->get_timestamp();
      $date = date("h:iA - M jS", $timestamp);
      $unread = "";
      if($message->get_read() == "f") $unread = "class=unread data-messageid=".$message->get_id()." ";
      echo "<div><input id=ac-".$count." name=accordion-1 type=radio />
      <label ".$unread." for=ac-".$count.">From: ".stripslashes(stripslashes($sender_name))."<p style=float:right;>".$date."</p></label><article class=ac-small>
      <p>".stripslashes(stripslashes($converter->decode($payload)))."</p><button data-uid=".$sender." class='btn btn-primary reply' style=margin-left:20px>Reply</button></article></div>";
    }
  }
}
echo "<script>
$('.unread').click(function() {
    $(this).removeClass('unread');
    $.get('ajax/setRead.php?messageID='+$(this).data('messageid'));
});
$('.reply').click(function() {
    $('#frame').load('ajax/messages.php?send='+$(this).data('uid')).hide().fadeIn('slow');
});
</script>";
?>