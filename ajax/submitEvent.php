<?php echo "
<div id=createDate class='box submit-event'>
 <h2><b>Submit Event</b></h2>
 <form id=submitEventForm>
 <h2><p class='icon ion-ios7-compose'></p>&nbsp;What?
  <input type=text id=loc name=name placeholder='Potluck Lunch!' required></h2>
 <h2><p class='icon ion-ios7-contact'></p>&nbsp;Where?
  <input type=text id=loc name=location placeholder='123 Main Street, Apt. 2D' required></h2>
 <h2><p class='icon ion-person-add'></p>&nbsp;Maximum Guests?
  <input type=number id=size name=size value=8 min=3 max=10 required></h2>
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
 </h2>
 </form>
 <button id=submit-event-button class='btn-primary pullDown' type=button value=></button>
</div>";
?>
<script>
$('#submit-event-button').click(function(){
 var posting = $.post('handlers/handleDate.php', $('#submitEventForm').serialize());
 posting.done(function(data) {
  $('body').append(data);
  setTimeout(function(){ $('[id$=notification]').fadeOut(500, function(){$(this).remove();}); }, 3000);
  if($(data).filter('#g-notification').length > 0) clearOverlay();
 });
});
</script>