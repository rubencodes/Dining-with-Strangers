<?php
require_once "../database/dbDate.php";
require_once "../database/dbPartner.php";
require_once "../database/dbStranger.php";
require_once "../database/dbUser.php";
require_once "../database/connect.php";
require_once "../domain/Encryption.php";
session_start();
$converter = new Encryption;
$user = retrieve_dbUser($converter->decode($_SESSION["BowdoinDwSuserID"]));
if(!$user) $user = retrieve_dbStranger($converter->decode($_SESSION["BowdoinDwSuserID"]));
$interest = safe($_GET['interest']);
$filter = safe($_GET['filter']);
$page = safe($_GET['page']);
if($filter == "interest") {
    $dates = get_users_by_interest($user, $interest, $page, false);
    format_users($dates);
}
elseif($filter != "romance")
    $dates = get_dining_dates($user, $filter, $page);
else
    $dates = get_romantic_dates($user, $page);

if(count($dates) == 0) {
    switch($filter) {
        case "interest":
            echo "<p id=stop class=box style=text-align:center;width:96%;display:none;>Sorry, no strangers could be find with those interests. Try another search term!</p>";
            return;
            break;
        case "me":
            echo "<p id=stop class=box style=text-align:center;width:96%;display:none;>It looks like you're not a part of any more dates or events at the moment. Start one using the Create a Date tool!</p>";
            break;
        case "events":
            echo "<p id=stop class=box style=text-align:center;width:96%;display:none;>Sorry, there's no more events for you to join right now! You can create an Event for others to join using the Events tool!</p>";
            $dates = get_users_by_interest($user, null, $page, true);
            format_users($dates);
            break;
        default:
            echo "<p id=stop class=box style=text-align:center;width:96%;display:none;>Sorry, there's no more dates for you to join right now! You can create a Dining Date for others to join using the Create a Date tool!</p>";
            $dates = get_users_by_interest($user, null, $page, true);
            format_users($dates);
            break;
    }
}
elseif($filter != "interest") {
    format_dates($user, $dates);
    return;
}

function format_users($users) {
    $userCounter = 0;
    foreach($users as $aUser) {
        $action = 'messages.php?user='.$aUser->get_id();
        $button = "<button id=user-".$userCounter." data-uid=".$aUser->get_id()." class='btn btn-join date-button' type=button ".$buttonStyle."><p class='icon ion-ios7-chatboxes toolbar-link'></p></button>";
        echo "
              <form id=date-".$userCounter." action=handlers/".$action.".php>
               <div class='box join-box retrieved-date'>
                <div class=date-content>
                 ".$aUser->get_story()."
                </div>".$button."
               </div>
              </form>";
        $userCounter++;
    }
    echo "
        <script>
        $('[id^=user]').click(function(){
            prepareOverlay();
            $('#frame').load('ajax/messages.php?send='+$(this).data('uid')).hide().fadeIn('slow');
        });
        </script>";
}

function format_dates($user, $dates) {
    $dateCounter = 0;
    foreach($dates as $aDate) {
        $spots = $aDate->get_maxSize()-$aDate->get_size();
        $people = explode(':',$aDate->get_participants());
        if($filter != 'me') $size = $size-1;
        if($aDate->get_size() == 1) { $peep_s = "a person"; $like_s = "likes"; $is_s = "is";  }
        if($aDate->get_size() > 1)  { $peep_s = "people";   $like_s = "like";  $is_s = "are"; }
        $likes = "";
        $discussion = "";
        $count = 0;
        foreach($people as $person) {
            if($person != $user->get_id()) {
                $count++;
                $thisuser = retrieve_dbUser($person);
                if(!$thisuser) $thisuser = retrieve_dbStranger($person);
                if($thisuser) {
                    if(empty($likes)) {
                        $likes = $thisuser->get_likes();
                        $discussion = $thisuser->get_discussion();
                    }
                    else {
                        if($count == $sizeof) {
                            $likes .= " and ".$thisuser->get_likes();
                            $discussion .= " and ".$thisuser->get_discussion();
                        }
                        else {
                            $likes .= ", ".$thisuser->get_likes();
                            $discussion .= ", ".$thisuser->get_discussion();
                        }
                    }
                }
            }
        }
        if($aDate->get_name() == 'Dining Date' || $aDate->get_name() == 'Romantic Date') $partner = retrieve_dbPartner($aDate->get_location());
        if($partner instanceof Partner) {
            $address  = $partner->get_location();
            $promos   = $partner->get_promos();
            $info     = explode("%3Cbr%3E",$partner->get_hours());
            $phone    = $info[0];
            $location = "<button class='btn-primary address' type=button data-promos='".urldecode($promos)."' data-phone='".urldecode($phone)."' data-location='".$address."'>".stripslashes(urldecode($partner->get_name()))."</button>";
        } else $location = $aDate->get_location();
        if($_GET['filter'] == 'me') {
            $action = "handleLeaveDate";
            $dateInfo = ".";
            if($aDate->get_size() > 1) {
                foreach(array_diff(explode(":", $aDate->get_participants()), array($user->get_id())) as $userID) {
                    $thisuser = retrieve_dbUser($userID);
                    if(!$thisuser) $thisuser = retrieve_dbStranger($userID);
                    $dateInfo .= "<br>".$thisuser->get_story();
                }
            } else $dateInfo .= " No one has joined this date yet.";
            $button =
                "<button id=date-".$dateCounter." class='btn btn-leave date-button' type=button>x</button>
                <a title='Add to Calendar' class=addthisevent>
                <p class='icon ion-ios7-calendar-outline'></p>
                <span class=_start>".date('m-d-Y H:i:s', $aDate->get_dateTime())."</span>
                <span class=_end>".date('m-d-Y H:i:s', $aDate->get_dateTime()+(60*60))."</span>
                <span class=_zonecode>15</span>
                <span class=_summary>".$aDate->get_name()."</span>
                <span class=_description>".$aDate->get_story($user->get_id())."</span>
                <span class=_location>".urldecode($address)."</span>
                <span class=_all_day_event>false</span>
                <span class=_date_format>DD/MM/YYYY</span>
                </a>";
        }
        else {
            $action = "handleDate";
            $dateInfo = " with ".$peep_s." who ".$like_s." ".$likes." & ".$is_s." interested in ".$discussion.".";
            if($filter == 'romance') $buttonStyle = "style=background-color:#e74c3c;";
            $button = "<button id=date-".$dateCounter." class='btn btn-join date-button' type=button ".$buttonStyle.">+</button>";
        }
        echo "
          <form id=date-".$dateCounter." action=handlers/".$action.".php>
           <div class='box join-box retrieved-date'>
           <input type=hidden name=DID value=".$aDate->get_id().">
            <div class=date-content>
             <h2>".date('D, F jS \a\t g:iA', $aDate->get_dateTime())." - ".$spots." of ".$aDate->get_maxSize()." spots left</h2>
             A ".stripslashes($aDate->get_name())." for ".$aDate->get_maxSize()." at ".$location.$dateInfo."
            </div>".$button."
           </div>
          </form>";
        $dateCounter++;
    }
    if($_GET['filter'] == 'me') echo "<script src=http://js.addthisevent.com/atemay.js></script>";
}

function get_dining_dates($user, $filter, $page) {
    $dates = array();
    if($user instanceof User || $user instanceof Stranger) {
        $mysqli = connection();
        $limitBegin = $page * 15;
        $limitEnd = ($page+1) * 15;
        $start = "SELECT * FROM dbDate WHERE dateTime > '".time()."' AND participants NOT LIKE '%".$user->get_id()."%' AND size < maxsize AND romance = 'false' AND city = '".$user->get_city()."' ";
        $end   = " ORDER BY dateTime LIMIT ".$limitBegin.", ".$limitEnd;
        switch($filter) {
            case "all":
                $stmt = $mysqli->prepare($start."AND name = 'Dining Date'".$end);
                break;
            case "events":
                $stmt = $mysqli->prepare($start."AND name <> 'Dining Date'".$end);
                break;
            case "me":
                $stmt = $mysqli->prepare("SELECT * FROM dbDate WHERE dateTime > '".time()."' AND participants LIKE '%".$user->get_id()."%'".$end);
                break;
            case "location":
                $stmt = $mysqli->prepare($start."AND location = ?".$end);
                $stmt->bind_param("s",urlencode($_GET['location']));
                break;
            case "time":
                $stmt = $mysqli->prepare("SELECT * FROM dbDate WHERE dateTime <= ? AND dateTime >= ? AND size < maxsize AND participants NOT LIKE '%".$user->get_id()."%' AND romance = 'false' AND city = '".$user->get_city()."' ".$end);
                $dateEnd = strtotime($_GET["time"]." + 24 hours");
                $dateStart = strtotime($_GET["time"]);
                $stmt->bind_param("ss",$dateEnd,$dateStart);
                break;
        }
        $stmt->execute();
        $stmt->bind_result($id,$name,$participants,$size,$maxsize,$dateTime,$location,$city,$romance,$active);
        while ($stmt->fetch()) {
            $date = new Date($id, $name, $participants, $size, $maxsize, $dateTime, $location, $city, $romance, $active);
            $dates[] = $date;
        }
        $stmt->close();
        $mysqli->close();
    }
    return $dates;
}

function get_romantic_dates($user, $page) {
    if($user->get_gender() != 0 && $user->get_interestedin() != 0)
        return retrieveAllFutureRomantic_dbDates($user->get_id(),$user->get_gender().$user->get_interestedin(),$page);
    else {
        include('setRomanticSettings.php');
        return;
    }
}

function get_users_by_interest($user, $interest, $page, $random) {
    if($user instanceOf User)
        $type = "dbUser";
    elseif($user instanceOf Stranger)
        $type = "dbStranger";
    $mysqli = connection();
    if($random) {
        $stmt = $mysqli->prepare("SELECT * FROM ".$type." WHERE
                                    id NOT LIKE '%".$user->get_id()."%' AND
                                    city = '".$user->get_city()."'
                                    ORDER BY RAND() LIMIT 1");
    }
    else {
        $interest = '%'.$interest.'%';
        $limitBegin = $page * 15;
        $limitEnd = ($page+1) * 15;
        $stmt = $mysqli->prepare("SELECT * FROM ".$type." WHERE
                                    id NOT LIKE '%".$user->get_id()."%' AND
                                    likes LIKE ? AND city = '".$user->get_city()."'
                                    ORDER BY firstname LIMIT $limitBegin, $limitEnd");
        $stmt->bind_param("s",$interest);
    }
    $stmt->execute();
    $stmt->bind_result($id ,$email,$password,$firstname,$lastname, $photoURL, $classYear, $major, $city, $likes, $discussion, $gender, $interestedin, $messageIDs, $contacts, $futureDates, $pastDates, $rating, $access, $active);

    $users = array();
    while ($stmt->fetch()) {
        if($user instanceOf User)
            $thisUser = new User($id ,$email,$password,$firstname,$lastname, $photoURL, $classYear, $major, $city, $likes, $discussion, $gender, $interestedin, $messageIDs, $contacts, $futureDates, $pastDates, $rating, $access, $active);
        if($user instanceOf Stranger)
            $thisUser = new Stranger($id, $email,$password,$firstname,$lastname, $type, $location, $city, $likes, $discussion, $gender, $interestedin, $messageIDs, $contacts, $futureDates, $pastDates, $rating, $access, $active);
        $users[] = $thisUser;
    }

    $stmt->close();
    $mysqli->close();
    return $users;
}
?>