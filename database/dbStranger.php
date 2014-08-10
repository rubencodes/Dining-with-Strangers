<?php
/* Stranger Database
 * Version: 13 October 2013
 * Author: Ruben Martinez Jr.
 */

require_once $_SERVER['DOCUMENT_ROOT']."/domain/Stranger.php";
require_once 'connect.php';

/*
 * add a Stranger to dbStranger table: if already there, return false
 */
function insert_dbStranger($stranger) {
    if (!$stranger instanceof Stranger) {
        echo "Failed Insert";
        return false;
    }
    $mysqli = connection();
    if (($stmt = $mysqli->prepare("INSERT INTO dbStranger (id,email,password,firstname,lastname,type,location,city,likes,discussion,gender,interestedin,messageIDs,contacts,futureDates,pastDates,rating,access,active) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"))) {
        /* bind parameters for markers */
        if(!$stmt->bind_param("sssssssssssssssssss", $stranger->get_id(),$stranger->get_email(),$stranger->get_password(),$stranger->get_firstname(),$stranger->get_lastname(),$stranger->get_type(),$stranger->get_location(),$stranger->get_city(),$stranger->get_likes(),$stranger->get_discussion(),$stranger->get_gender(),$stranger->get_interestedin(),$stranger->get_messageIDs(),$stranger->get_contacts(),$stranger->get_futureDates(),$stranger->get_pastDates(),$stranger->get_rating(),$stranger->get_access(),$stranger->get_active()))
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        /* execute query */
        if(!$stmt->execute()) echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        /* close statement */
        $stmt->close();
        /* close connection */
        $mysqli->close();
        return true;
    }
    else echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    return false;
}

/*
 * @return a single row from dbStranger table matching a particular id.
 * if not in table, return false
 */
function retrieve_dbStranger($id) {
    if(!empty($id)) {
        $mysqli = connection();
        $stmt = $mysqli->prepare("SELECT * FROM dbStranger WHERE id = ? LIMIT 1");
        $stmt->bind_param("s",$id);
        $stmt->execute();
        $bool = $stmt->bind_result($id,$email,$password,$firstname,$lastname,$type,$location,$city,$likes,$discussion,$gender,$interestedin,$messageIDs,$contacts,$futureDates,$pastDates,$rating,$access,$active);
        if (!$bool) {
            $stmt->close();
            $mysqli->close();
            return false;
        }
        while ($stmt->fetch()) {
            $stranger = new Stranger($id,$email,$password,$firstname,$lastname,$type,$location,$city,$likes,$discussion,$gender,$interestedin,$messageIDs,$contacts,$futureDates,$pastDates,$rating,$access,$active);
            $stmt->close();
            $mysqli->close();
            return $stranger;
        }
    } return false;
}
function retrieveAll_dbStranger() {
    $mysqli = connection();
    $stmt = $mysqli->prepare("SELECT * FROM dbStranger");
    $stmt->bind_param("s",$id);
    $stmt->execute();
    $bool = $stmt->bind_result($id,$email,$password,$firstname,$lastname,$type,$location,$city,$likes,$discussion,$gender,$interestedin,$messageIDs,$contacts,$futureDates,$pastDates,$rating,$access,$active);
    if (!$bool) {
        $stmt->close();
        $mysqli->close();
        return false;
    }
    $strangers = array();
    while ($stmt->fetch()) {
        $stranger = new Stranger($id,$email,$password,$firstname,$lastname,$type,$location,$city,$likes,$discussion,$gender,$interestedin,$messageIDs,$contacts,$futureDates,$pastDates,$rating,$access,$active);
        $strangers[] = $stranger;
    }
    $stmt->close();
    $mysqli->close();
    return $strangers;
}
function retrieve_byEmail_dbStranger($email) {
    if(!empty($email)) {
        $mysqli = connection();
        $stmt = $mysqli->prepare("SELECT * FROM dbStranger WHERE email = ? LIMIT 1");
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $bool = $stmt->bind_result($id,$email,$password,$firstname,$lastname,$type,$location,$city,$likes,$discussion,$gender,$interestedin,$messageIDs,$contacts,$futureDates,$pastDates,$rating,$access,$active);
        if (!$bool) {
            $stmt->close();
            $mysqli->close();
            return false;
        }
        while ($stmt->fetch()) {
            $stranger = new Stranger($id,$email,$password,$firstname,$lastname,$type,$location,$city,$likes,$discussion,$gender,$interestedin,$messageIDs,$contacts,$futureDates,$pastDates,$rating,$access,$active);
            $stmt->close();
            $mysqli->close();
            return $stranger;
        }
    } return false;
}

function update_dbStranger($stranger) {
    if (!$stranger instanceof Stranger) return false;
    if (delete_dbStranger($stranger->get_id())) return insert_dbStranger($stranger);
    else {
        echo ("Unable to update user database for: " . $stranger->get_id());
        return false;
    }
}

/*
 * remove an entry from dbStranger table.  If not there, return false
 */
function delete_dbStranger($id) {
    if(empty($id)) return false;
    $mysqli = connection();
    $stmt = $mysqli->prepare("DELETE FROM dbStranger WHERE id = ? LIMIT 1");
    $stmt->bind_param("s",$id);
    $stmt->execute();

    /* close statement */
    $stmt->close();
    /* close connection */
    $mysqli->close();
    return true;
}
?>