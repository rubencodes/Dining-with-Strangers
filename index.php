<?php include 'start.php';
if (!isset($_SESSION["BowdoinDwSuserID"])) echo "
<div id=hero>
 <div class=bg-0></div>
 <div class=bg-1></div>
 <div class=bg-2></div>
 <h1 id=page-title>
  <p id=instruct>Meet awesome people.<br>Do awesome things.</p>
  <br><button id=signUp class=btn-primary type=button>Sign Up</button>
 </h1>
</div>
<div id=mainbody>
<div class=row>
<div class='pullDown desc'>
 <h2>Do Fun Things</h2>
 <p>Maybe you picked up a new Xbox and are looking for friends
 to help break it in. Perhaps you're looking dapper and want
 to paint the town red. Put simply, you're awesome and want
 to meet people to be awesome with. Whatever you'd like to
 do, we can help you find others for the evening's adventure!
 </p>
</div>
<div class='pullDown desc'>
 <h2>Meet Fun People</h2>
 <p>Dining with the same group of people, every single day?
 Grabbing a quick 11 o'clock lunch before your 11:30 meeting?
 Looking to meet fun, new people? Expand your social circle!
 Dining with Strangers takes the awkwardness out of finding
 fun, new people like you to grab a meal with. </p>
</div>
<div class='pullDown desc'>
 <h2>Safety First</h2>
 <p>Whether you have a hot date at a local restaurant, or
 you're signed up for a cocktail party at someone's apartment,
 others will only ever see your first name and interests. If
 ever you see something weird, just drop us a comment card
 and we'll check it out as soon as possible.
 </p>
</div>
</div>
<div class=divider></div>
<div class='pullDown desc-large'>
 <h2>Create a Date When You Want, Where You Want</h2>
 <img id=screen1 src=http://DiningWithStrangers.co/images/screen1.png width=auto height=auto>
</div>
<div class=divider></div>
<div class=row>
<div class='pullDown desc'>
 <h2>Bowdoin & UCF Beta</h2>
 <p>If you're a Bowdoin College or UCF student, you can be
 among the first to start Dining with Strangers! Find students
 near you who are looking for a fun evening out, wanna grab a
 scoop of gelato, or even organizing a potluck! <b>Not at Bowdoin
 College or UCF? <a href=http://blog.diningwithstrangers.co>Stay tuned!</a></b></p>
</div>
<div class='pullDown desc'>
 <h2>Our Partners</h2>
 <p>We're actively partnering with local businesses near you
 to get you the best deals and find you reservations! Are you
 a business owner near Bowdoin College or UCF interested in
 joining in on the fun? <a href=CommentCard.php>Send us a Comment Card!</a>
 We'll get back to you as soon as we can.</p>
</div>
<div class='pullDown desc'>
 <h2>Students Say:</h2>
 <p style=text-align:left;><i>
 \"Class of '17 should check this out! ...Awesome way to get to know people.\"
 \"This will be great for Gelato Fiasco dates!\"<br>
 \"Dining with Strangers is an awesome way to branch out.\"<br>
 \"Dining with Strangers is THE way to branch out. and meet cool people\"<br></i></p>
</div>
</div>";
elseif(isset($_SESSION["BowdoinDwSuserID"]) && ($user instanceof User || $user instanceof Stranger)) {
    echo "
<div id=inner-mainbody>
<div id=hero class=home>
 <h1 id=page-title>
  <p id=instruct>Meet awesome people.<br>Do awesome things.</p><br>";
    if (isset($_SESSION['firstsignin'])) echo "<button id=startTourBtn class='btn btn-primary btn-small' type=button>Take a Tour</button>";
    echo "
 </h1>
</div>
<div id=join-bar class='box pullDown'>
 <h2 id=join><p class='icon ion-ios7-search'></p>&nbsp;View</h2>
 <select id=options>
  <option value=all>Dining Dates</option>
  <option value=romance>Romantic Dates</option>
  <option value=events>Events</option>
  <option value=me>My Dates</option>
  <option value=time>Find by Day</option>
  <option value=location>Find by Location</option>
  <option value=interest>People who like...</option>
 </select>
 <div id=filters></div>
</div>
<div id=mydates></div>";
} ?>
    </div>
<? include 'end.php'; ?>