<?php
/* Date Database
 * Version: 01 17 2014
 * Author: Ruben Martinez Jr.
 */

require_once $_SERVER['DOCUMENT_ROOT'].'/domain/Date.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/domain/Encryption.php';
require_once 'connect.php';
/*
 * add a User to dbDate table: if already there, return false
 */
function insert_dbDate($date) {
    if (!$date instanceof Date) {
        echo "Failed Insert";
        return false;
    }
    $mysqli = connection();
    if (($stmt = $mysqli->prepare("INSERT INTO dbDate (id,name,participants,size,maxsize,dateTime,location,city,romance,active) VALUES (?,?,?,?,?,?,?,?,?,?)"))) {
        /* bind parameters for markers */
        if(!$stmt->bind_param("sssiisssss", $date->get_id(),$date->get_name(),$date->get_participants(),$date->get_size(),$date->get_maxsize(),$date->get_dateTime(),$date->get_location(),$date->get_city(),$date->get_romance(),$date->get_active())) {
         echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
         return false;
        }
        /* execute query */
        if(!$stmt->execute()) {
         echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
         return false;
        }
        $stmt->close();
        $mysqli->close();
        return true;
    }
    else echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    return false;
}

/*
 * @return a single row from dbDate table matching a particular id.
 * if not in table, return false
 */
function retrieve_dbDate($id) {
  if(!empty($id)) {
    $mysqli = connection();
    $stmt = $mysqli->prepare("SELECT * FROM dbDate WHERE id = ? LIMIT 1");
    $stmt->bind_param("s",$id);
    $stmt->execute();
    $bool = $stmt->bind_result($id,$name,$participants,$size,$maxsize,$dateTime,$location,$city,$romance,$active);
    if (!$bool) {
        $stmt->close();
        $mysqli->close();
        return false;
    }
    while ($stmt->fetch()) {
        $date = new Date($id, $name, $participants, $size, $maxsize, $dateTime, $location, $city, $romance, $active);
        $stmt->close();
        $mysqli->close();
        return $date;
    }
  }
  return false;
}

function retrieveAllFutureRomantic_dbDates($user, $romance, $page) {
    $g1 = substr($romance,0,1);
    $g2 = substr($romance,-1);
    $search_query = "SELECT * FROM dbDate WHERE size < maxsize AND romance = ? AND participants != ? ORDER BY dateTime";
    $mysqli = connection();
    $converter = new Encryption;
    $user = $converter->decode($user);
    $stmt = $mysqli->prepare($search_query);
    $dates = array();
    if($g2 != "3") {
        //run search for $g2's interested in $g1's & $g2's interested in both
        $r = $g2.$g1;
        $stmt->bind_param("ss",$r,$user);
        $stmt->execute();
        $stmt->bind_result($id,$name,$participants,$size,$maxsize,$dateTime,$location,$city,$romance,$active);
        while ($stmt->fetch()) {
            $date = new Date($id, $name, $participants, $size, $maxsize, $dateTime, $location, $city, $romance, $active);
            $time = $date->format_dateTime();
            if(strtotime($time." - 1 hours") > strtotime("now")) $dates[] = $date;
        }
        $stmt->close();

        $r = $g2."3";
        $stmt = $mysqli->prepare($search_query);
        $stmt->bind_param("ss",$r,$user);
        $stmt->execute();
        $stmt->bind_result($id,$name,$participants,$size,$maxsize,$dateTime,$location,$city,$romance,$active);
        while ($stmt->fetch()) {
            $date = new Date($id, $name, $participants, $size, $maxsize, $dateTime, $location, $city, $romance, $active);
            $time = $date->format_dateTime();
            if(strtotime($time." - 1 hours") > strtotime("now")) $dates[] = $date;
        }
        $stmt->close();
        $mysqli->close();
        return array_slice($dates,$page*15,($page+1)*15);
    }
    else {
        //run search for $g2-1's interested in $g1's & $g2-2's interested in $g1's & $g2-1's interested in both & $g2-2's interested in both
        $r = "1".$g1;
        $stmt->bind_param("ss",$r,$user);
        $stmt->execute();
        $stmt->bind_result($id,$name,$participants,$size,$maxsize,$dateTime,$location,$city,$romance,$active);

        while ($stmt->fetch()) {
            $date = new Date($id, $name, $participants, $size, $maxsize, $dateTime, $location, $city, $romance, $active);
            $time = $date->format_dateTime();
            if(strtotime($time." - 1 hours") > strtotime("now")) $dates[] = $date;
        }
        $stmt->close();

        $r = "2".$g1;
        $stmt = $mysqli->prepare($search_query);
        $stmt->bind_param("ss",$r,$user);
        $stmt->execute();
        $stmt->bind_result($id,$name,$participants,$size,$maxsize,$dateTime,$location,$city,$romance,$active);
        while ($stmt->fetch()) {
            $date = new Date($id, $name, $participants, $size, $maxsize, $dateTime, $location, $city, $romance, $active);
            $time = $date->format_dateTime();
            if(strtotime($time." - 1 hours") > strtotime("now")) $dates[] = $date;
        }
        $stmt->close();

        $r = "1"."3";
        $stmt = $mysqli->prepare($search_query);
        $stmt->bind_param("ss",$r,$user);
        $stmt->execute();
        $stmt->bind_result($id,$name,$participants,$size,$maxsize,$dateTime,$location,$city,$romance,$active);
        while ($stmt->fetch()) {
            $date = new Date($id, $name, $participants, $size, $maxsize, $dateTime, $location, $city, $romance, $active);
            $time = $date->format_dateTime();
            if(strtotime($time." - 1 hours") > strtotime("now")) $dates[] = $date;
        }
        $stmt->close();

        $r = "2"."3";
        $stmt = $mysqli->prepare($search_query);
        $stmt->bind_param("ss",$r,$user);
        $stmt->execute();
        $stmt->bind_result($id,$name,$participants,$size,$maxsize,$dateTime,$location,$city,$romance,$active);
        while ($stmt->fetch()) {
            $date = new Date($id, $name, $participants, $size, $maxsize, $dateTime, $location, $city, $romance, $active);
            $time = $date->format_dateTime();
            if(strtotime($time." - 1 hours") > strtotime("now")) $dates[] = $date;
        }
        $stmt->close();
        $mysqli->close();
        return array_slice($dates,$page*15,($page+1)*15);
    }
}

function search_dbDate($UID, $DID, $ms, $dT, $l, $r) {
    if(!empty($DID)) return retrieve_dbDate($DID);
    $search_query = "SELECT * FROM dbDate WHERE size < maxsize AND maxsize = ? AND dateTime = ? AND location = ? AND romance = ? LIMIT 1";
    $mysqli = connection();
    $stmt = $mysqli->prepare($search_query);
    if($r != "false") {
        $g1 = substr($r, 0,1);
        $g2 = substr($r, 1,1);
        if($g2 != "3") {
            //run search for $g2's interested in $g1's & $g2's interested in both
            $r = $g2.$g1;
            $stmt->bind_param("isss",$ms,$dT,$l,$r);
            $stmt->execute();
            $stmt->bind_result($id,$name,$participants,$size,$maxsize,$dateTime,$location,$city,$romance,$active);
            $found = false;
            while ($stmt->fetch()) {
                $found = true;
                $date = new Date($id, $name, $participants, $size, $maxsize, $dateTime, $location, $city, $romance, $active);
                $stmt->close();
                return $date;
            }
            if(!$found) {
                $r = $g2."3";
                $stmt = $mysqli->prepare($search_query);
                $stmt->bind_param("isss",$ms,$dT,$l,$r);
                $stmt->execute();
                $stmt->bind_result($id,$name,$participants,$size,$maxsize,$dateTime,$location,$city,$romance,$active);
                while ($stmt->fetch()) {
                    $date = new Date($id, $name, $participants, $size, $maxsize, $dateTime, $location, $city, $romance, $active);
                    $stmt->close();
                    return $date;
                }
            }
            $mysqli->close();
            return false;
        }
        else {
           //run search for $g2-1's interested in $g1's & $g2-2's interested in $g1's & $g2-1's interested in both & $g2-2's interested in both
           $r = "1".$g1;
           $stmt->bind_param("isss",$ms,$dT,$l,$r);
           $stmt->execute();
           $stmt->bind_result($id,$name,$participants,$size,$maxsize,$dateTime,$location,$city,$romance,$active);

           $found = false;
           while ($stmt->fetch()) {
               $found = true;
               $date = new Date($id, $name, $participants, $size, $maxsize, $dateTime, $location, $city, $romance, $active);
               $stmt->close();
               return $date;
           }
           if(!$found) {
               $r = "2".$g1;
               $stmt = $mysqli->prepare($search_query);
               $stmt->bind_param("isss",$ms,$dT,$l,$r);
               $stmt->execute();
               $stmt->bind_result($id,$name,$participants,$size,$maxsize,$dateTime,$location,$city,$romance,$active);
               while ($stmt->fetch()) {
                   $found = true;
                   $date = new Date($id, $name, $participants, $size, $maxsize, $dateTime, $location, $city, $romance, $active);
                   $stmt->close();
                   return $date;
               }
               if(!$found) {
                   $r = "1"."3";
                   $stmt = $mysqli->prepare($search_query);
                   $stmt->bind_param("isss",$ms,$dT,$l,$r);
                   $stmt->execute();
                   $stmt->bind_result($id,$name,$participants,$size,$maxsize,$dateTime,$location,$city,$romance,$active);
                   while ($stmt->fetch()) {
                       $found = true;
                       $date = new Date($id, $name, $participants, $size, $maxsize, $dateTime, $location, $city, $romance, $active);
                       $stmt->close();
                       return $date;
                   }
                   if(!$found) {
                       $r = "2"."3";
                       $stmt = $mysqli->prepare($search_query);
                       $stmt->bind_param("isss",$ms,$dT,$l,$r);
                       $stmt->execute();
                       $stmt->bind_result($id,$name,$participants,$size,$maxsize,$dateTime,$location,$city,$romance,$active);
                       while ($stmt->fetch()) {
                          $date = new Date($id, $name, $participants, $size, $maxsize, $dateTime, $location, $city, $romance, $active);
                          $stmt->close();
                          return $date;
                       }
                   }
               }
           }
           /* close connection */
           $mysqli->close();
           return false;
        }
    }
    else {
        $stmt->bind_param("isss",$ms,$dT,$l,$r);
        $stmt->execute();
        $stmt->bind_result($id,$name,$participants,$size,$maxsize,$dateTime,$location,$city,$romance,$active);

        while ($stmt->fetch()) {
            $date = new Date($id, $name, $participants, $size, $maxsize, $dateTime, $location, $city, $romance, $active);
            $stmt->close();
            $mysqli->close();
            return $date;
        }
        return false;
    }
}

function update_dbDate($date) {
    if (!$date instanceof Date) return false;
    if (delete_dbDate($date->get_id())) return insert_dbDate($date);
    else echo ("Unable to update user database for: " . $date->get_id());
    return false;
}

/*
 * remove a user entry from dbDate table.  If not there, return false
 */
function delete_dbDate($id) {
    $mysqli = connection();
    $stmt = $mysqli->prepare("DELETE FROM dbDate WHERE id = ? LIMIT 1");
    $stmt->bind_param("s",$id);
    $stmt->execute();
    $stmt->close();
    $mysqli->close();
    return true;
}
?>