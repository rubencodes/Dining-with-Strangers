<div id=createDate class='box submit-date'>
 <h2><b>Submit Partner</b></h2>
 <form id=submitPartnerForm>
 <div id=mapHolder>
     <h2><p class='icon ion-ios7-contact'></p>&nbsp;Where?</h2>
     <input type=text id=loc name=location placeholder='123 Maine Street, Brunswick, ME' style=width:50%;max-width:300px;float:left; required>
     <button id=test type=button style=background-color:#F00;padding:5px;color:#fff;width:100px;text-align:center;cursor:pointer;float:left;>Test Map</button>
     <iframe id=map width=435 height=200 frameborder=0 scrolling=no marginheight=0 marginwidth=0 style=display:none></iframe>
 </div>
 <div id=submitDateForm>
 <h2><p class='icon ion-ios7-compose'></p>&nbsp;What?</h2>
     <input type=text id=loc name=name placeholder='Super Awesome Burgers Express' required style=float:none;width:100%;max-width:300px;>
 <h2><p class='icon ion-ios7-clock'></p>&nbsp;When?</h2>
     <select name=day-begin style=width:auto;>
         <option selected value=Mon>Mon</option>
         <option value=Tue>Tue</option>
         <option value=Wed>Wed</option>
         <option value=Thu>Thu</option>
         <option value=Fri>Fri</option>
         <option value=Sat>Sat</option>
         <option value=Sun>Sun</option>
     </select>
     &nbsp;-&nbsp;
     <select name=day-end style=width:auto;>
         <option value=Mon>Mon</option>
         <option value=Tue>Tue</option>
         <option value=Wed>Wed</option>
         <option value=Thu>Thu</option>
         <option value=Fri>Fri</option>
         <option value=Sat>Sat</option>
         <option selected value=Sun>Sun</option>
     </select>:
     <input type=time name=time-begin value=09:00 style=width:100px;>&nbsp;to&nbsp;<input type=time name=time-end value=22:00 style=width:100px;>
     <br><b>Additional Hours:</b><br>
     <select name=day-begin2 style=width:auto;>
         <option selected></option>
         <option value=Mon>Mon</option>
         <option value=Tue>Tue</option>
         <option value=Wed>Wed</option>
         <option value=Thu>Thu</option>
         <option value=Fri>Fri</option>
         <option value=Sat>Sat</option>
         <option value=Sun>Sun</option>
     </select>
     &nbsp;-&nbsp;
     <select name=day-end2 style=width:auto;>
         <option selected></option>
         <option value=Mon>Mon</option>
         <option value=Tue>Tue</option>
         <option value=Wed>Wed</option>
         <option value=Thu>Thu</option>
         <option value=Fri>Fri</option>
         <option value=Sat>Sat</option>
         <option value=Sun>Sun</option>
     </select>:
     <input type=time name=time-begin2 style=width:100px;>&nbsp;to&nbsp;<input type=time name=time-end2 style=width:100px;><br>
     <select name=day-begin3 style=width:auto;>
         <option selected></option>
         <option value=Mon>Mon</option>
         <option value=Tue>Tue</option>
         <option value=Wed>Wed</option>
         <option value=Thu>Thu</option>
         <option value=Fri>Fri</option>
         <option value=Sat>Sat</option>
         <option value=Sun>Sun</option>
     </select>
     &nbsp;-&nbsp;
     <select name=day-end3 style=width:auto;>
         <option selected></option>
         <option value=Mon>Mon</option>
         <option value=Tue>Tue</option>
         <option value=Wed>Wed</option>
         <option value=Thu>Thu</option>
         <option value=Fri>Fri</option>
         <option value=Sat>Sat</option>
         <option value=Sun>Sun</option>
     </select>:
     <input type=time name=time-begin3 style=width:100px;>&nbsp;to&nbsp;<input type=time name=time-end3 style=width:100px;>
     <h2><p class='icon ion-android-call'></p>&nbsp;Phone?</h2>
     <input type=text maxlength=10 placeholder=1234567890 name=phone>
     <h2><p class='icon ion-android-promotion'></p>&nbsp;Promos?</h2>
     <input type=checkbox name=verify value=yes> <input type=text maxlength=45 name=promos value='- Check PartyTutor.com for Daily Discounts'>
     </div>
 </form>
 <button id=submit-button class='btn-primary pullDown' type=button value=></button>
</div>
<script>
    $('#map').prop('width',$('#mapHolder').css('width'));
    $(window).on("resize", function(){ $('#map').prop('width',$('#mapHolder').css('width')); });
    $('#test').click(function(){
        $('#map').attr('src','http://maps.google.com/maps?f=q&source=s_q&hl=en&geocode=&q='+encodeURIComponent($('#loc').val())+'&ie=UTF8&z=16&t=m&iwloc=near&output=embed').slideDown('slow');
    });
    $('#submit-button').click(function(){
        var posting = $.post('handlers/handlePartner.php', $('#submitPartnerForm').serialize());
        posting.done(function(data) {
            $('body').append(data);
            setTimeout(function(){ $('[id$=notification]').fadeOut(500, function(){$(this).remove();}); }, 3000);
            if($(data).filter('#g-notification').length > 0) clearOverlay();
        });
    });
</script>