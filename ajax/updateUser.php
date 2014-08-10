<?php
session_start();
require_once "../domain/Encryption.php";
require_once "../database/dbUser.php";
require_once "../database/dbStranger.php";
$converter = new Encryption;
$user = retrieve_dbUser($converter->decode($_SESSION["BowdoinDwSuserID"]));
if(!$user) $user = retrieve_dbStranger($converter->decode($_SESSION["BowdoinDwSuserID"]));
if ($user instanceof User) {
 echo "<div class='box account-user-update'>
  <h2>Fill in the Changed Info:</h2>
  <p style=text-align:justify>
  <form id=handleUserUpdate>
  Hi, my name is
  <input id=firstname type=text name=firstname placeholder='".$user->get_firstname()."' maxlength=20>
  <input id=lastname type=text name=lastname placeholder='".$user->get_lastname()."' maxlength=20>. <br>I'm a Class of
  <select name=class style=width:auto;margin-left:3px;>";
   for($y=date('y');$y<=date('y')+4;$y++) {
    echo "<option value='20".$y."'";
    if ("20".$y == $user->get_classYear()) echo " selected";
    echo ">".$y."</option>";
   }
  echo "</select>
  <select id=type name=major style=width:125px;>
   <option selected value=''>Unchanged</option>
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
   <option selected value=''>Unchanged</option>
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
  </select><br>
  major interested in:
  <input id=likes type=text name=likes><br>
  <button id=getstarted class='btn btn-primary btn-small' type=button>Update</button>
  </form>
  </p>
 </div>";
}
elseif($user instanceof Stranger) {
 echo "<div class='box account-create'>
 <h2>Fill in the Changed Info:</h2>
 <form id=handleStrangerUpdate>
 Hi, my name is
 <input id=firstname autocomplete=off type=text name=firstname placeholder='".$user->get_firstname()."' maxlength=20>
 <input id=lastname autocomplete=off name=lastname placeholder='".$user->get_lastname()."' type=text maxlength=20 >. <br>I'm from
 <input id=location name=location type=text placeholder='".urldecode($user->get_location())."' autocomplete=off>& interested in<br>
 <input id=likes type=text name=likes><br>
 <input id=email type=email name=email placeholder=".$user->get_email()." autocomplete=off>
 <input id=pass type=password name=password placeholder=Password autocomplete=off><br>
 <button id=getstarted class='btn btn-primary btn-small' type=button>Update</button>
 </form>
</div>";
}
?>
<script>
$('#likes').tagsInput({});
$('#likes').importTags('<? echo $user->get_likes(); ?>');
$('#getstarted').click(function(){
 var posting = $.post('handlers/'+$(this).parent().attr('id')+'.php', $('#'+$(this).parent().attr('id')+'').serialize());
 posting.done(function(data) {
  $('body').append(data);
  setTimeout(function(){ $('[id$=notification]').fadeOut(500, function(){$(this).remove();}); }, 3000);
  if($(data).filter('#g-notification').length > 0) clearOverlay();
 });
});
</script>