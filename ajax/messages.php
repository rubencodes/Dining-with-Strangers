<?php
session_start();
require_once "../domain/Encryption.php";
require_once "../database/dbMessage.php";
require_once "../database/dbUser.php";
require_once "../database/dbDate.php";
require_once "../database/dbStranger.php";
$converter = new Encryption;
$user = retrieve_dbUser($converter->decode($_SESSION["BowdoinDwSuserID"]));
if(!$user) $user = retrieve_dbStranger($converter->decode($_SESSION["BowdoinDwSuserID"]));
if(isset($_SESSION["BowdoinDwSuserID"]) && ($user instanceof User || $user instanceof Stranger)) {
  $dateArray = array();
  foreach (explode(':', $user->get_futureDates()) as $dateID) { //loop through array of dates
   $date = retrieve_dbDate($dateID); //retrieve date for date ID
   if ($date instanceof Date && $date->get_size() > 1) $dateArray[] = $date;
  }
  $FilteredDates = array();
  foreach($dateArray as $date) {
   $bool = true;
   foreach($FilteredDates as $existingDate) if(count(array_diff(explode(":",$existingDate->get_participants()), explode(":",$date->get_participants()))) == 0) $bool = false;
   if($bool) $FilteredDates[] = $date;
  }
   echo "<section id=messaging class=ac-container>
   <div id=messages-header>
    <h2 id=messages-title>Messages</h2>
    <input id=reload type=button onclick=getMessages();>
   </div>
    <div>
     <input id=ac-0 name=accordion-1 type=radio checked/>
     <label for=ac-0>+ Send New Message</label>
     <article class=ac-small>
      <form id=handleMessage>
       <p style=padding:0;>To:
       <select id=date-contacts name=contacts onChange=enableButton()>";
       if(!empty($_GET['send'])) {
           $sendToUser = retrieve_dbUser($_GET['send']);
           if(!$sendToUser) $sendToUser = retrieve_dbStranger($_GET['send']);
           if($sendToUser) echo "<option selected value='".$sendToUser->get_id()."'>".stripslashes(stripslashes($sendToUser->get_firstname()))."</option>";
           else echo "<option>Not Found :(</option>";
       }
       if(sizeof($FilteredDates) == 0 && empty($_GET['send'])) echo "<option>No Dates Available</option>";
       else {
        foreach($FilteredDates as $date) {
         $DT = $date->format_dateTimeLocation();
         if($_GET['DID'] == $date->get_id()) echo "<option selected value='".$date->get_participants()."'>".stripslashes(stripslashes($date->format_participants($user->get_id())))."</option>";
         else if($date->get_participants() != false) echo "<option value='".$date->get_participants()."'>".stripslashes(stripslashes($date->format_participants($user->get_id())))."</option>";
         else echo "<option>No Dates Available</option>";
        }
       }
       echo "</select></p>
       <textarea type=text id=payload name=payload></textarea>
       <button type=button id=send-confirm name=send title='Remember to select a recipient!' disabled>Send</button>
      </form>
     </article>
    </div>
    <div id=all-messages></div>
   </div></section>";
}
?>
</div>
<script type='text/javascript'>
function enableButton() {
 if($("#date-contacts").val() == "") $("#send-confirm").attr('disabled',true);
 else $("#send-confirm").removeAttr('disabled');
}
function getMessages() {
 $('#all-messages').html('<div class=spinner></div>');
 $('#all-messages').load("ajax/retrieveMessages.php");
}
enableButton();
getMessages();

$('#send-confirm').click(function(){
    var posting = $.post('handlers/handleMessage.php', $('#handleMessage').serialize());
    posting.done(function(data){
        $('body').append(data);
        setTimeout(function(){ $('[id$=notification]').fadeOut(500, function(){$(this).remove();}); }, 3000);
        if($(data).filter('#g-notification').length > 0) $('#payload').val("");
    });
});
</script>