<?php
require_once "../database/dbPartner.php";
require_once "../database/dbUser.php";
require_once "../database/dbStranger.php";
require_once "../domain/Encryption.php";
session_start();
$converter = new Encryption;
$user = retrieve_dbUser($converter->decode($_SESSION["BowdoinDwSuserID"]));
if(!$user) $user = retrieve_dbStranger($converter->decode($_SESSION["BowdoinDwSuserID"]));
$partners = retrieveAll_dbPartners($user->get_city());
echo "
<div id=createDate class='box submit-date'>
 <h2 style=width:54%;display:inline-block;><b>Submit Date</b></h2>
 <h2 id=address style=display:inline-block;></h2>
 <div id=mapHolder><iframe id=map width=435 height=270 frameborder=0 scrolling=no marginheight=0 marginwidth=0></iframe></div>
 <form id=submitDateForm>
 <h2><p class='icon ion-ios7-location'></p>&nbsp;Where?
  <select id=loc name=location required>";
  foreach($partners as $partner)echo "<option value='".$partner->get_id()."'>".stripslashes(urldecode($partner->get_name()))." ".urldecode($partner->get_promos())."</option>";
  echo "</select></h2>
 <h2><p class='icon ion-person-add'></p>&nbsp;How Many?
  <select id=size name=size required>
   <option selected value=2>Party of Two</option>
   <option value=3>Party of Three</option>
   <option value=4>Party of Four</option>
  </select></h2>
 <h2><p class='icon ion-ios7-clock'></p>&nbsp;When?
  <select id=time name=time required>";
  $date = date('Y-m-d H:i:s');
  $newtime = strtotime($date.' + 6 days');
  $start = "04:00";
  $end = "23:30";
  $tNow = strtotime($start);
  while($tNow <= strtotime($end)){
   echo "<option ";
   if($tNow == strtotime("12:00")) echo "selected";
   echo " value=".date("h:iA",$tNow).">".date("h:iA",$tNow)."</option>";
   $tNow = strtotime('+30 minutes',$tNow);
  }
  echo "</select>
  <input id=date required name=date value=".date('Y-m-d')." type=date min=".date('Y-m-d')." max=".date('Y-m-d', $newtime).">
  <p id=hours></p></h2>
 <h2><p id=heart class='icon ion-ios7-heart'></p>&nbsp;Romantic Date?<input id=romance type=checkbox>
  <div class=hover-me><b>[?]</b><div class=mytip>Looking to go on a romantic date? You're not alone! Romantic Dates work exactly the same as regular Dining Dates, but provide a way for you to specify and search by your romantic interests.</div></div></h2>
 <div id=showops style=display:none;>
  I am a
  <select id=gender name=gender>
   <option selected></option>
   <option value=1>Male</option>
   <option value=2>Female</option>
  </select>
  interested in dating
  <select id=interestedin name=interestedin>
   <option selected></option>
   <option value=1>Men</option>
   <option value=2>Women</option>
   <option value=3>Either</option>
  </select>
 </div>
 </form>
 <button id=submit-button class='btn-primary pullDown' type=button value=></button>
</div>";
?>
<script>
$('#loc').change(function(){
 <?php $counter = 0;
 foreach(retrieveAll_dbPartners($user->get_city()) as $partner) { echo "
    if($('#loc').prop('selectedIndex') == ".$counter.") {
     $('#hours').html('".urldecode($partner->get_hours())."');
     $('#map').attr('src','http://maps.google.com/maps?f=q&source=s_q&hl=en&geocode=&q=".$partner->get_location()."&ie=UTF8&z=16&t=m&iwloc=near&output=embed');
     $('#address').html('".urldecode($partner->get_location())."');
    }";
  $counter++;
 }
 ?>
});
$('#loc').trigger('change');
$('#romance').click(function(){
 $('#showops').slideToggle('100');
 if($(this).prop('checked')) {
  $('#size').prop('selectedIndex',0).attr('disabled','true').css('background-color', '#e74c3c');
  $('#heart').css('color','#e74c3c');
  $('#gender').attr('required','');
  $('#interestedin').attr('required','');
 }
 else {
  $('#gender').val('0').removeAttr('required');
  $('#interestedin').val('0').removeAttr('required');
  $('#size').removeAttr('disabled').css('background-color', '#043567');
  $('#heart').css('color','#fff');
 }
});
$('#submit-button').click(function(){
 var posting = $.post('handlers/handleDate.php', $('#submitDateForm').serialize());
 posting.done(function(data) {
  $('body').append(data);
  setTimeout(function(){ $('[id$=notification]').fadeOut(500, function(){$(this).remove();}); }, 3000);
  if($(data).filter('#g-notification').length > 0) clearOverlay();
 });
});
</script>