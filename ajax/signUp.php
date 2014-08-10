<div id=choice class='box account-create'>
 <h2>Are you a...?</h2>
 <button id=student class='btn btn-primary' type=button>Bowdoin College Student</button>
 <button id=other class='btn btn-primary' type=button>UCF Student</button>
</div>
<script>
$('#other').click(function(){ $('#choice').load("ajax/createStranger.php"); });
$('#student').click(function(){ $('#choice').load("ajax/createUser.php"); });
var firstnames=new Array("Barry","Randy","Derek","Ruben","Polar","Henry","Nathaniel","Harriet","Joshua","Robert","Reed","Franklin");
var lastnames=new Array("Mills","Nichols","Shephard","Martinez","Barry","Longfellow","Hawthorne","Stowe","Chamberlain","Peary","Hastings","Pierce");
var loc=new Array("Las Vegas, NV","New York, NY","Austin, TX","Boston, MA","San Francisco, CA","Detroit, MI","San Antonio, TX","Los Angeles, CA","Albany, NY","Washington D.C.","Raleigh, NC","Kansas City, KS");
var counter = 0;
setInterval(function(){
 $('#firstname').attr('placeholder',firstnames[counter%firstnames.length]);
 $('#lastname').attr('placeholder',lastnames[counter%lastnames.length]);
 $('#location').attr('placeholder',loc[counter%loc.length]);
 counter++;
}, 1000);
</script>