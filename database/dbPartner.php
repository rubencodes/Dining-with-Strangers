<?php
/* Partner Database
 * Version: 30 November 2013
 * Author: Ruben Martinez Jr.
 */

require_once $_SERVER['DOCUMENT_ROOT'].'/domain/Partner.php';
require_once 'connect.php';

/*
 * add a Partner to dbPartner table: if already there, return false
 */
function insert_dbPartner($partner)
{
    if (!$partner instanceof Partner) {
        echo "Failed Insert";
        return false;
    }
    $mysqli = connection();
    if (($stmt = $mysqli->prepare("INSERT INTO dbPartner (name,location,hours,codes,promos,restrictions) VALUES (?,?,?,?,?,?)"))) {
        /* bind parameters for markers */
        if(!$stmt->bind_param("ssssss", $partner->get_name(),$partner->get_location(),$partner->get_hours(),$partner->get_codes(),$partner->get_promos(),$partner->get_restrictions()))
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        /* execute query */
        if(!$stmt->execute()) 
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
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
 * @return a single row from dbPartner table matching a particular id.
 * if not in table, return false
 */
function retrieve_dbPartner($id)
{
    if($id != null) {
    $mysqli = connection();
    $stmt = $mysqli->prepare("SELECT * FROM dbPartner WHERE id = ? LIMIT 1");
    $stmt->bind_param("s",$id);
    $stmt->execute();
    $bool = $stmt->bind_result($id,$name,$location,$hours,$codes,$promos,$restrictions);
    if (!$bool) {
        $stmt->close();
        $mysqli->close();
        return false;
    }
    while ($stmt->fetch()) {
        $partner = new Partner($id,$name,$location,$hours,$codes,$promos,$restrictions);
        $stmt->close();
        $mysqli->close();
        return $partner;
    }
    }
    else return false;
}

/*
 * @return all rows from dbPartner table
 */
function retrieveAll_dbPartners($city)
{
    $mysqli = connection();
    $stmt = $mysqli->prepare("SELECT * FROM dbPartner WHERE location LIKE '%".urlencode($city)."' ORDER BY id");
    $stmt->execute();
    $bool = $stmt->bind_result($id,$name,$location,$hours,$codes,$promos,$restrictions);
    if (!$bool) {
        /* close statement */
        $stmt->close();
        /* close connection */
        $mysqli->close();
        return false;
    }
    $partners = array();
    while ($stmt->fetch()) {
        $partner = new Partner($id,$name,$location,$hours,$codes,$promos,$restrictions);
        $partners[] = $partner;
    }
    /* close statement */
    $stmt->close();
    /* close connection */
    $mysqli->close();
    return $partners;
}

function update_dbPartner($partner)
{
    if (!$partner instanceof Partner) return false;
    if (delete_dbPartner($partner->get_id())) return insert_dbPartner($partner);
    else {
        echo ("Unable to update user database for: " . $partner->get_id());
        return false;
    }
}

/*
 * remove an entry from dbPartner table.  If not there, return false
 */
function delete_dbPartner($id)
{
    $mysqli = connection();
    $stmt = $mysqli->prepare("DELETE FROM dbPartner WHERE id = ? LIMIT 1");
    $stmt->bind_param("s",$id);
    $stmt->execute();
    
    /* close statement */
    $stmt->close();
    /* close connection */
    $mysqli->close();
    return true;
}
?>