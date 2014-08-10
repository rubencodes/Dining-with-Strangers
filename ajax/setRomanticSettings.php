<?php
session_start();
require_once "../domain/Encryption.php";
require_once "../database/dbUser.php";
require_once "../database/dbStranger.php";
$converter = new Encryption;
$user = retrieve_dbUser($converter->decode($_SESSION["BowdoinDwSuserID"]));
if(!$user) $user = retrieve_dbStranger($converter->decode($_SESSION["BowdoinDwSuserID"]));
echo "<div id=stop class='box account-create' style=margin-left:50%;text-align:center;>
 <h2>Help us find your perfect date! We promise to keep these settings private. </h2>
 <form id=";
 if($user instanceof Stranger) echo "handleStrangerUpdate";
 elseif($user instanceof User) echo "handleUserUpdate";
 echo ">
 I'm a
 <select name=gender>
  <option value=0></option>
  <option value=1>Male</option>
  <option value=2>Female</option>
 </select>
 interested in
 <select name=interestedin>
  <option value=0></option>
  <option value=1>Men</option>
  <option value=2>Women</option>
  <option value=3>Both</option>
 </select>.
 <button id=set-romance class=btn-primary type=button style=float:none;>Save & Search</button>
 </form>
</div>";
?>
<script>
$('#set-romance').click(function(){
 if($('[name=interestedin]').val() > 0 && $('[name=gender]').val() > 0) {
  var posting = $.post('handlers/'+$(this).parent().attr('id')+'.php', $('#'+$(this).parent().attr('id')).serialize());
  posting.done(function(data) {
   $('body').append(data);
   setTimeout(function(){ $('[id$=notification]').fadeOut(500, function(){$(this).remove();}); }, 3000);
   if($(data).filter('#g-notification').length > 0) {
       $('#mydates').html('');
       runFilter(0);
   }
  });
 } else {
     $('body').append('<div id=b-notification>To search for romantic dates, fill in both of the fields below.</div>');
     setTimeout(function(){ $('#b-notification').fadeOut(500, function(){$(this).remove();}); }, 3000);
 }

});
</script>