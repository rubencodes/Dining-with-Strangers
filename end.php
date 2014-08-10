<footer class=box>
<a href="ToC.php">Terms of Service &amp; Privacy Policy</a><br>
<a href="FAQ.php">FAQ</a><br>
<a href="CommentCard.php">Leave Us a Comment Card</a><br>
Dining with Strangers &copy; <?php echo date('Y'); ?> <br>
<a href="http://blog.diningwithstrangers.co">Version 3.0 Beta</a>
</footer>
<script>
$(document).ready(function(){
 $('#mydates').on('click', '.address', function() {
  prepareOverlay();
  $('#frame').html('<div id=placeMap class=box><h2>'+$(this).html()+' '+$(this).data('promos')+'</h2><h2>'+decodeURIComponent($(this).data('location').replace(/\+/g, '%20'))+'</h2><iframe id=map width=435 height=270 frameborder=0 scrolling=no marginheight=0 marginwidth=0></iframe><h2>'+$(this).data('phone')+'</h2></div>');
  $('#map').attr('src','http://maps.google.com/maps?f=q&source=s_q&hl=en&geocode=&q='+$(this).data('location')+'&ie=UTF8&z=16&t=m&iwloc=near&output=embed');
 });
 $('#mydates').on('click', "button[id^='date-']", function() {
  var id = '#'+$(this).attr('id');
  var posting = $.post($('form'+id).attr('action'), $('form'+id).serialize());
  posting.done(function(data) {
   $('body').append(data);
   setTimeout(function(){ $('[id$=notification]').fadeOut(500, function(){$(this).remove();}); }, 3000);
   if($('#g-notification').length > 0) $('form'+id).slideUp(function(){ $(this).remove() });
  });
 });
 setTimeout(function(){ $('[id$=notification]').fadeOut(500, function(){$('[id$=notification]').remove();}); }, 1000);
 $('#navbar-login').click(function(){
  var posting = $.post('handlers/handleLogin.php', $('#handleLogin').serialize());
  posting.done(function(data){
   $('body').append(data);
   setTimeout(function(){ $('[id$=notification]').fadeOut(500, function(){$(this).remove();}); }, 3000);
   if($(data).filter('#b-notification').length === 0) window.location.replace(<?php echo "'".root()."'"; ?>);
  });
 });
 $('#password,#username').keypress(function(e) { if(e.which == 13) { $(this).blur(); $('#navbar-login').focus().click(); } });
 if($('#join-bar').length > 0) runFilter();
 if($('#options').length > 0) {
  $('#options').change(function(){
   var option = $('#options').val();
   if(option === "location") {
    $('#filters').html("<select id=filter1 onchange=runFilter();>");
    <? if($user) {
     $partners = retrieveAll_dbPartners($user->get_city());
     foreach($partners as $partner) echo "$('#filter1').append(\"<option value='".$partner->get_id()."'>".stripslashes(urldecode($partner->get_name()))." ".urldecode($partner->get_promos())."</option>\");";
    } ?>
   }
   else if(option === "time") {
    var now = <? echo "'".date('Y-m-d')."'"; ?>;
    var max = <? echo "'".date('Y-m-d',strtotime(date('Y-m-d').' + 6 days'))."'"; ?>;
    $('#filters').html("<input id=filter1 type=date value="+now+" min="+now+" max="+max+" onchange=runFilter();>");
   }
   else if(option === "interest") $('#filters').html("<input id=filter1 type=text onkeyup='if($(this).val().length > 3)runFilter();'>");
   else if(option === "romance" || option === "all" || option === "me") $('#filters').html("");
   runFilter();
  });
 }
 $('#submitPartner,#submitDate,#submitEvent,#messages,#signUp,#updateUser').click(function(){
  prepareOverlay();
  $('#frame').load('ajax/'+$(this).attr('id')+'.php').hide().fadeIn('slow');
 });
 $(window).on("resize", function(){ $('#map').prop('width',$('#map').parent().css('width')); });
 var pageCount = 0;
 $(window).scroll(function() {
  if($(window).scrollTop() + $(window).height() > $(document).height() - $('footer').height() && released && $('#stop').length == 0) {
      released = false;
      runFilter(++pageCount);
  }
 });
});
function prepareOverlay() {
 $('body').append('<div id=shade></div><div id=frame></div><button id=close onclick=clearOverlay() type=button>x</button>').css('overflow-y','hidden');
 $('#shade').on('click', function(){ clearOverlay(); });
 $(document).keyup(function(e) { if(e.keyCode == 27) { clearOverlay(); } }); // esc
}
function clearOverlay() {
 $('body').css('overflow-y','scroll');
 $('#shade,#frame,#close').fadeOut(500, function(){ $('#shade,#frame,#close').remove(); });
}
var released = true;
function runFilter(page) {
 if(arguments.length == 0) var page = 0;
 if(page > 0)  $('#mydates').append('<div class=spinner></div>');
 else          $('#mydates').html('<div class=spinner></div>');
 if(($('#options').val() == "interest" && $('#filter1').val().length > 4) || $('#options').val() != "interest")
  $.get("ajax/retrieveDates.php?page="+page+"&filter="+$('#options').val()+"&"+$('#options').val()+"="+$('#filter1').val()).done(function(data){
   $('.spinner').remove();
   if(page > 0)  $('#mydates').append(data);
   else          $('#mydates').html(data);
   if($('#stop').length > 0) $('#stop').slideDown();
   released = true;
  });
}
var counter = 0;
setInterval(function(){
    $('.bg-'+counter%3).fadeOut(1000);
    $('.bg-'+(counter+1)%3).fadeIn(1000);
    counter++;
}, 5000);
</script>
<? if (isset($_SESSION['firstsignin'])) echo "<script src=js/hopscotch.js></script><script src=js/tour.js></script>"; ?>
</body></html>