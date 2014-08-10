<?php
/* Submit Partner
 * Version: 01 08 2014
 * Author: Ruben Martinez Jr.
 */
session_start();
require_once('../database/dbUser.php');
require_once('../database/dbStranger.php');
require_once('../database/dbDate.php');
require_once('../database/dbPartner.php');
require_once('../domain/Encryption.php');
require_once('../root.php');
$converter = new Encryption;
$user = retrieve_dbUser($converter->decode($_SESSION["BowdoinDwSuserID"]));
if(!$user) $user = retrieve_dbStranger($converter->decode($_SESSION["BowdoinDwSuserID"]));
if($user->get_access() > 1) {
if(!empty($_POST['name']) && !empty($_POST['location']) && strlen($_POST['phone']) == 10 && is_numeric($_POST['phone']) && !empty($_POST['day-begin']) && !empty($_POST['day-end']) && !empty($_POST['time-begin']) && !empty($_POST['time-end'])) {
 $validDays = array('Mon','Tue','Wed','Thu','Fri','Sat','Sun');
 if(in_array($_POST['day-begin'], $validDays) && in_array($_POST['day-end'], $validDays)) {
  $hours = "Phone: (".substr($_POST['phone'],0,3).") ".substr($_POST['phone'],3,3)."-".substr($_POST['phone'], -4)."<br>";
  $hours .=  $_POST['day-begin']."-".$_POST['day-end'].": ".date('g:iA', strtotime($_POST['time-begin']))."-".date('g:iA', strtotime($_POST['time-end']));

  if(!empty($_POST['day-begin2']) &&  !empty($_POST['day-end2']) && !empty($_POST['time-begin2']) && !empty($_POST['time-end2'])) {
    if(in_array($_POST['day-begin2'], $validDays) && in_array($_POST['day-end2'], $validDays)) {
      if($_POST['day-begin2'] != $_POST['day-end2']) $hours .= "<br>".$_POST['day-begin2']."-".$_POST['day-end2'].": ".date('g:iA', strtotime($_POST['time-begin2']))."-".date('g:iA', strtotime($_POST['time-end2']));
      else $hours .= "<br>".$_POST['day-begin2'].": ".date('g:iA', strtotime($_POST['time-begin2']))."-".date('g:iA', strtotime($_POST['time-end2']));
    }
  }
  if(!empty($_POST['day-begin3']) &&  !empty($_POST['day-end3']) && !empty($_POST['time-begin3']) && !empty($_POST['time-end3'])) {
    if(in_array($_POST['day-begin3'], $validDays) && in_array($_POST['day-end3'], $validDays)) {
      if($_POST['day-begin3'] != $_POST['day-end3']) $hours .= "<br>".$_POST['day-begin3']."-".$_POST['day-end3'].": ".date('g:iA', strtotime($_POST['time-begin3']))."-".date('g:iA', strtotime($_POST['time-end3']));
      else $hours .= "<br>".$_POST['day-begin3'].": ".date('g:iA', strtotime($_POST['time-begin3']))."-".date('g:iA', strtotime($_POST['time-end3']));
    }
  }
  if($_GET['verify'] == "yes" &&029 !empty($_GET['promos'])) $promos = urlencode($_GET['promos']);
  else $promos = "";
  if(insert_dbPartner(new Partner("", urlencode(safe($_POST['name'])), urlencode(safe($_POST['location'])), urlencode($hours), "", $promos, ""))) {
    echo "<div id=g-notification>This partner has been successfully added!</div>";
    return;
  } else $e = "Sorry, there was an error adding the partner at this time. Please try again.";
 } else $e = "Error in data entry. Please try again.";
} else $e = "Uh-oh, it seems like you left some fields blank. Please try again.";
} else $e = "Sorry, looks like you don't have the proper permissions to add a Partner.";
if(!empty($e)) { echo "<div id=b-notification>".$e."</div>"; return; }
?>