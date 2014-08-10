<?php include 'start.php'; ?>
 <div id=mainbody class=container>
  <div id=hero style="padding: 60px 0;"><h1 id=page-title style=margin-top:0;>
   <p id=instruct style=width:550px;>Did we break something? Is someone bothering you? Report it here, and help provide everyone a better experience!</p>
   </h1></div>
  <?php
   echo "<div class='box pullDown' style=width:96%;>
    <form id=handleBugReport style=text-align:center;>
     <textarea id=payload name=BR rows=4 maxlength=1400 placeholder='What happened? Please provide us with as much information as possible, so we can prevent this from happening in the future.' required></textarea>
     <button id=send-confirm type=button class='btn btn-primary btn-small'>Submit</button>
    <form>
   </div>";
  ?>
  </div>
<script>
$('#send-confirm').click(function(){
 var posting = $.post('handlers/handleBugReport.php', $('#handleBugReport').serialize());
 posting.done(function(data){
  $('body').append(data);
  setTimeout(function(){ $('[id$=notification]').fadeOut(500, function(){$(this).remove();}); }, 3000);
  if($(data).filter('#g-notification').length > 0) $('#payload').val("");
 });
});
</script>
<?php include 'end.php'; ?>