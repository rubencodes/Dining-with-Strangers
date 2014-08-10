<?php
/* Keeps Users' and Strangers' Past & Future Dates Correctly Sorted
 * Version: 01 01 2014
 * Author: Ruben Martinez Jr.
 */
session_start();
session_cache_expire(30);
include_once('dbinfo.php');
include_once('connect.php');
include_once('dbStranger.php');
include_once('dbUser.php');
include_once('dbDate.php');

  connect();
  $query = "SELECT * FROM dbUser";
  $result = mysql_query($query) or die(mysql_error());
  while($result_row = mysql_fetch_array($result)){
    $user = new User($result_row['id'], $result_row['email'], $result_row['password'], $result_row['firstname'], $result_row['lastname'], $result_row['photoURL'], $result_row['classYear'], $result_row['major'], $result_row['city'], $result_row['likes'], $result_row['discussion'], $result_row['gender'], $result_row['interestedin'], $result_row['messageIDs'], $result_row['contacts'], $result_row['futureDates'], $result_row['pastDates'], $result_row['rating'], $result_row['access'], $result_row['active']);
    $pastDates = $user->get_pastDates();
    $futureDates = "";
    foreach(explode(":",$user->get_futureDates()) as $dateID) {
      $date = retrieve_dbDate($dateID);
      if($date instanceOf Date) {
       if($date->get_dateTime() < time()) $pastDates = $pastDates.":".$dateID;
       else $futureDates = $futureDates.":".$dateID;
      }
    }
    $user->set_pastDates($pastDates);
    $user->set_futureDates($futureDates);
    update_dbUser($user);
  }

$mysqli = connection();
$stmt = $mysqli->prepare("SELECT * FROM dbStranger");
$stmt->execute();
$bool = $stmt->bind_result($id,$email,$password,$firstname,$lastname,$type,$location,$city,$likes,$discussion,$gender,$interestedin,$messageIDs,$contacts,$futureDates,$pastDates,$rating,$access,$active);
if (!$bool) {
    $stmt->close();
    $mysqli->close();
}
while ($stmt->fetch()) {
    $stranger = new Stranger($id,$email,$password,$firstname,$lastname,$type,$location,$city,$likes,$discussion,$gender,$interestedin,$messageIDs,$contacts,$futureDates,$pastDates,$rating,$access,$active);
    $pastDates = $stranger->get_pastDates();
    $futureDates = "";
    foreach(explode(":",$stranger->get_futureDates()) as $dateID) {
      $date = retrieve_dbDate($dateID);
      if($date instanceOf Date) {
       if($date->get_dateTime() < time()) $pastDates = $pastDates.":".$dateID;
       else $futureDates = $futureDates.":".$dateID;
      }
    }
    $stranger->set_pastDates($pastDates);
    $stranger->set_futureDates($futureDates);
    update_dbStranger($stranger);
}

/* close statement */
$stmt->close();
/* close connection */
$mysqli->close();
?>