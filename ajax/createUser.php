<h2>Fill in the blanks:</h2>
 <form id=submitStrangerForm>
 Hi, my name is
 <input id=firstname autocomplete=off type=text name=firstname placeholder='Ruben' maxlength=20 required>
 <input id=lastname autocomplete=off name=lastname placeholder='Martinez' type=text maxlength=20  required>.
 <br>I'm a Class of
 <select id=class name=class required>
  <?php for($y=date('y');$y<=date('y')+4;$y++) echo '<option value=20'.$y.'>'.$y.'</option>'; ?>
 </select>
 <select id=type name=major required style=width:125px;>
  <option value='Undeclared'>Undeclared</option>
  <option value='Africana Studies'>Africana Studies</option>
  <option value='Anthropology'>Anthropology</option>
  <option value='Archaeology'>Archaeology</option>
  <option value='Art History'>Art History</option>
  <option value='Asian Studies'>Asian Studies</option>
  <option value='Biochemistry'>Biochemistry</option>
  <option value='Biology'>Biology</option>
  <option value='Chemistry'>Chemistry</option>
  <option value='Classics'>Classics</option>
  <option value='Computer Science'>Computer Science</option>
  <option value='Earth & Oceanographic Science'>Earth & Oceanographic Science</option>
  <option value='Economics'>Economics</option>
  <option value='Education'>Education</option>
  <option value='English'>English</option>
  <option value='Environmental Studies'>Environmental Studies</option>
  <option value='French'>French</option>
  <option value='Gender & Women Studies'>Gender & Women Studies</option>
  <option value='German'>German</option>
  <option value='Government & Legal Studies'>Government & Legal Studies</option>
  <option value='History'>History</option>
  <option value='Latin American Studies'>Latin American Studies</option>
  <option value='Mathematics'>Mathematics</option>
  <option value='Music'>Music</option>
  <option value='Neuroscience'>Neuroscience</option>
  <option value='Philosophy'>Philosophy</option>
  <option value='Physics & Astronomy'>Physics & Astronomy</option>
  <option value='Psychology'>Psychology</option>
  <option value='Religion'>Religion</option>
  <option value='Romance Languages'>Romance Languages</option>
  <option value='Russian'>Russian</option>
  <option value='Sociology'>Sociology</option>
  <option value='Spanish'>Spanish</option>
  <option value='Visual Arts'>Visual Arts</option>
 </select>
 <select id=type name='majorTwo' style=width:125px;>
  <option selected value=>None</option>
  <option value='Anthropology'>Anthropology</option>
  <option value='Archaeology'>Archaeology</option>
  <option value='Art History'>Art History</option>
  <option value='Asian Studies'>Asian Studies</option>
  <option value='Biochemistry'>Biochemistry</option>
  <option value='Biology'>Biology</option>
  <option value='Chemistry'>Chemistry</option>
  <option value='Classics'>Classics</option>
  <option value='Computer Science'>Computer Science</option>
  <option value='Earth & Oceanographic Science'>Earth & Oceanographic Science</option>
  <option value='Economics'>Economics</option>
  <option value='Education'>Education</option>
  <option value='English'>English</option>
  <option value='French'>French</option>
  <option value='Gender & Women Studies'>Gender & Women Studies</option>
  <option value='German'>German</option>
  <option value='Government & Legal Studies'>Government & Legal Studies</option>
  <option value='History'>History</option>
  <option value='Latin American Studies'>Latin American Studies</option>
  <option value='Mathematics'>Mathematics</option>
  <option value='Music'>Music</option>
  <option value='Neuroscience'>Neuroscience</option>
  <option value='Philosophy'>Philosophy</option>
  <option value='Physics & Astronomy'>Physics & Astronomy</option>
  <option value='Psychology'>Psychology</option>
  <option value='Religion'>Religion</option>
  <option value='Romance Languages'>Romance Languages</option>
  <option value='Russian'>Russian</option>
  <option value='Sociology'>Sociology</option>
  <option value='Spanish'>Spanish</option>
  <option value='Visual Arts'>Visual Arts</option>
 </select><br>major interested in:
 <input id=likes name=likes type=text placeholder='technology' autocomplete=off required><br>
 <input id=email name=email type=email placeholder='Bowdoin Username' autocomplete=off required>
 <input id=pass name=password type=password placeholder='Bowdoin Password' autocomplete=off required><br>
 <p id=tc>I agree to the <a href=ToC.php target=_blank>Terms & Conditions</a>.</p>
 <button id=getstarted class='btn btn-primary btn-small' type=button>Get Started</button>
 </form>
<script>
$('#likes').tagsInput({'delimiter':','});
$('#getstarted').click(function(){
 var posting = $.post('handlers/handleNewUser.php', $('#submitStrangerForm').serialize());
 posting.done(function(data) {
  $('body').append(data);
  setTimeout(function(){ $('[id$=notification]').fadeOut(500, function(){$(this).remove();}); }, 3000);
  if($(data).filter('#g-notification').length > 0) window.location.replace("http://DiningWithStrangers.co");
 });
});
</script>