<form id=submitStrangerForm>
 <input name=type type=hidden value='student'>
 Hi, my name is
 <input id=firstname autocomplete=off type=text name=firstname placeholder='Ruben' maxlength=20 required>
 <input id=lastname autocomplete=off name=lastname placeholder='Martinez' type=text maxlength=20  required>. <br>I'm from
 <input id=location name=location type=text placeholder='Miami, FL' autocomplete=off required> & interested in<br>
 <input name=city type=hidden value='Orlando, FL'>
 <input id=likes name=likes type=text placeholder='technology' autocomplete=off required><br>
 <input id=email name=email type=email placeholder='Your Official UCF Email' autocomplete=off required>
 <input id=pass name=password type=password placeholder=Password autocomplete=off required><br>
 <p id=tc>I agree to the <a href=ToC.php target=_blank>Terms & Conditions</a>.</p>
 <button id=getstarted class='btn btn-primary btn-small' type=button>Get Started</button>
 </form>
<script>
$('#likes').tagsInput({});
$('#getstarted').click(function(){
 var posting = $.post('handlers/handleNewStranger.php', $('#submitStrangerForm').serialize());
 posting.done(function(data) {
  $('body').append(data);
  setTimeout(function(){ $('[id$=notification]').fadeOut(500, function(){$(this).remove();}); }, 3000);
  if($(data).filter('#g-notification').length > 0) window.location.replace("http://DiningWithStrangers.co");
 });
});
</script>