<?php
session_start();
include_once('root.php');
include_once('domain/Encryption.php');
include_once('database/dbUser.php');
include_once('database/dbStranger.php');
include_once('database/dbDate.php');
include_once('database/dbPartner.php');
$converter = new Encryption;
$UID = $converter->decode($_SESSION["BowdoinDwSuserID"]);
$user = retrieve_dbUser($UID);
if(!$user) $user = retrieve_dbStranger($UID);
?>
<!doctype html><html><head>
<title>Dining with Strangers | A Social Discover App for Dining Enthusiasts</title>
<meta name=application-name content='Dining w/ Strangers'/>
<meta name=mobile-web-app-capable content=yes>
<meta name=viewport content='width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=0'>
<link rel=stylesheet media='only screen and (max-width: 600px)' href=styles/losangeles.css />
<link rel=stylesheet media='only screen and (min-width: 601px)' href=styles/malibu.css />
<link rel=stylesheet href=styles/ionicons.css />
<link rel=stylesheet href=styles/jquery.tagsinput.css />
<link href=http://fonts.googleapis.com/css?family=Raleway:200 rel=stylesheet type=text/css>
<link href=http://fonts.googleapis.com/css?family=Open+Sans:300,400 rel=stylesheet type=text/css />
<meta property=og:title content='Dining with Strangers | A Social Discovery App for Dining Enthusiasts'/>
<meta property=og:description content="Hanging out with the same group of people, every day? Dining with Strangers is a Social Discovery site designed to take the difficulty out of finding fun new people near you." />
<meta property=og:type content=website/>
<meta property=og:image content=images/32X.png />
<link rel=icon type=image/png href=/images/32X.png />
<link rel='shortcut icon' sizes=196x196 href=images/200X.png>
<link rel='shortcut icon' sizes=600x600 href=images/600X.png />
<link rel=apple-touch-startup-image href=images/600X.png />
<script src=http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js></script>
<script src=/js/min/jquery.tagsinput.min.js></script>
<?
if($_GET['offmail'] == "t") $note = "<div id=g-notification>You've been removed from our email list.</div>";
if($_GET['offmail'] == "f") $note = "<div id=b-notification>Something's gone wrong while removing you from our list. Please try again later.</div>";
if(!empty($_GET['id']) && !empty($_GET['email'])) {
    if($u = retrieve_byEmail_dbStranger($_GET['email'])) {
        if($u->get_id() == $_GET['id'] && $u->get_active() == "f") {
            $u->set_active("t");
            $_SESSION['firstsignin'] = "true";
            if(update_dbStranger($u)) $note = "<div id=g-notification>Your email is confirmed! You may now sign in.</div>";
        } else $note = "<div id=b-notification>This account has already been confirmed.</div>";
    }
} elseif($user instanceof User && $user->get_active() == 't') {
    $_SESSION['firstsignin'] = "true";
    $user->set_active('true');
    update_dbUser($user);
}
if ($user) echo "<style> body { background: #EEE; } </style>";
if (isset($_SESSION['firstsignin'])) echo "<link rel=stylesheet href=styles/hopscotch.css />";
?>
</head><body>
<? echo $note; ?>
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-42313627-1', 'bowdoinwithstrangers.com');
  ga('send', 'pageview');
  ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js';
</script>
<table id=navbar class=box><tr>
 <td id=DwS><a href=/><img src=images/32X.png width=30 height=30 />Dining with Strangers</a></td>
<? if (!$user) echo "
<td style=float:right;margin-top:-2px;>
<form id=handleLogin>
 <input id=username type=text name=username placeholder=Username required>
 <input id=password type=password name=password placeholder=Password required>
 <button id=navbar-login class='btn btn-primary btn-small' type=button>Login</button>
</form>";
else { echo "
<td>
<p id=submitDate class='icon ion-coffee toolbar-link'></p>
<p id=submitEvent class='icon ion-film-marker toolbar-link'></p>
<p id=messages class='icon ion-ios7-chatboxes toolbar-link'></p>
<p id=updateUser class='icon ion-person toolbar-link'></p>";
if($user->get_access() > 1) echo "<p id=submitPartner class='icon ion-fork toolbar-link'></p>";
echo"</td>
<td style=float:right;>
<a href=handlers/handleLogout.php><button id=navbar-logout class='btn btn-secondary btn-small' type=button>Logout</button></a>";
} ?>
</td></tr></table>