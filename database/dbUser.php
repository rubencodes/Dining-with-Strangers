<?php
/* User Database
 * Version: 23 April 2013
 * Author: Ruben Martinez Jr.
 */

require_once $_SERVER['DOCUMENT_ROOT'].'/domain/User.php';
require_once 'dbinfo.php';

function create_dbUser()
{
    connect();
    mysql_query("DROP TABLE IF EXISTS dbUser");
    $result = mysql_query("CREATE TABLE dbUser(id TEXT NOT NULL, email TEXT, password TEXT, firstname TEXT NOT NULL, lastname TEXT NOT NULL, photoURL TEXT, classYear TEXT, major TEXT, city TEXT, likes TEXT, discussion TEXT, gender TEXT, interestedin TEXT, messageIDs TEXT, contacts TEXT, futureDates TEXT, pastDates TEXT, rating TEXT, access TEXT, active TEXT NOT NULL)");
    mysql_close();
    if (!$result) {
        echo mysql_error() . "Error creating dbUser table. <br>";
        return false;
    } else
        return true;
}

/*
 * add a User to dbUser table: if already there, return false
 */
function insert_dbUser($user)
{
    if (!$user instanceof User) {
        return false;
    }
    connect();
    $id     = mysql_real_escape_string($user->get_id());
    $query  = "SELECT * FROM dbUser WHERE id = '" . $id . "' LIMIT 1";
    $result = mysql_query($query);
    //if there's no entry for this id, add user
    if (mysql_num_rows($result) == 0) {
        $query  = "INSERT INTO dbUser VALUES(
        '" . $user->get_id()               . "',
        '" . $user->get_email()            . "',
        '" . $user->get_password()         . "',
        '" . $user->get_firstname()        . "',
        '" . $user->get_lastname()         . "',
        '" . $user->get_photoURL()         . "',
        '" . $user->get_classYear()        . "',
        '" . $user->get_major()            . "',
        '" . $user->get_city()             . "',
        '" . $user->get_likes()            . "',
        '" . $user->get_discussion()       . "',
        '" . $user->get_gender()           . "',
        '" . $user->get_interestedin()     . "',
        '" . $user->get_messageIDs()       . "',
        '" . $user->get_contacts()         . "',
        '" . $user->get_futureDates()      . "',
        '" . $user->get_pastDates()        . "',
        '" . $user->get_rating()           . "',
        '" . $user->get_access()           . "',
        '" . $user->get_active()           . "')";
        $result = mysql_query($query);
        if (!$result) {
            echo (mysql_error() . " Error inserting into database: " . $user->get_id() . "\n");
            mysql_close();
            return false;
        }
    } else
        return false;
    mysql_close();
    return true;
}

/*
 * @return a single row from dbUser table matching a particular id.
 * if not in table, return false
 */
function retrieve_dbUser($id)
{
    connect();
    $query  = "SELECT * FROM dbUser WHERE id = '" . mysql_real_escape_string($id) . "' LIMIT 1";
    $result = mysql_query($query);
    if (mysql_num_rows($result) == 0) {
        mysql_close();
        return false;
    }
    $result_row = mysql_fetch_assoc($result);
    $user = new User($result_row['id'], $result_row['email'], $result_row['password'], $result_row['firstname'], $result_row['lastname'], $result_row['photoURL'], $result_row['classYear'], $result_row['major'], $result_row['city'], $result_row['likes'], $result_row['discussion'],  $result_row['gender'], $result_row['interestedin'], $result_row['messageIDs'], $result_row['contacts'], $result_row['futureDates'], $result_row['pastDates'], $result_row['rating'], $result_row['access'], $result_row['active']);
    mysql_close();
    return $user;
}

function retrieve_byEmail_dbUser($email)
{
    connect();
    $query  = "SELECT * FROM dbUser WHERE email = '" . mysql_real_escape_string($email) . "' LIMIT 1";
    $result = mysql_query($query);
    if (mysql_num_rows($result) == 0) {
        mysql_close();
        return false;
    }
    $result_row = mysql_fetch_assoc($result);
    $user       = new User($result_row['id'], $result_row['email'], $result_row['password'], $result_row['firstname'], $result_row['lastname'], $result_row['photoURL'], $result_row['classYear'], $result_row['major'], $result_row['city'], $result_row['likes'], $result_row['discussion'],  $result_row['gender'], $result_row['interestedin'], $result_row['messageIDs'], $result_row['contacts'], $result_row['futureDates'], $result_row['pastDates'], $result_row['rating'], $result_row['access'], $result_row['active']);
    mysql_close();
    return $user;
}
/*
 * @return all from dbUser table matching a particular id.
 * if not in table, return false
 */
function retrieveAll_dbUser()
{
    connect();
    $query  = "SELECT * FROM dbUser";
    $result = mysql_query($query);
    if (mysql_num_rows($result) == 0) {
        mysql_close();
        return false;
    }
    $users = array();
    while($result_row = mysql_fetch_array($result)) {
        $user    = new User($result_row['id'], $result_row['email'], $result_row['password'], $result_row['firstname'], $result_row['lastname'], $result_row['photoURL'], $result_row['classYear'], $result_row['major'], $result_row['city'], $result_row['likes'], $result_row['discussion'], $result_row['gender'], $result_row['interestedin'], $result_row['messageIDs'], $result_row['contacts'], $result_row['futureDates'], $result_row['pastDates'], $result_row['rating'], $result_row['access'], $result_row['active']);
        $users[] = $user;
    }
    mysql_close();
    return $users;
}

function update_dbUser($user)
{
    if (!$user instanceof User) return false;
    if (delete_dbUser($user->get_id())) return insert_dbUser($user);
    else {
        echo (mysql_error() . "Unable to update user database for: " . $user->get_id());
        return false;
    }
}

/*
 * remove a user entry from dbUser table.  If not there, return false
 */
function delete_dbUser($id)
{
    connect();
    $id     = mysql_real_escape_string($id);
    $query  = 'DELETE FROM dbUser WHERE id = "' . $id . '"  LIMIT 1';
    $result = mysql_query($query);
    mysql_close();
    if (!$result) {
        echo (mysql_error() . " Cannot delete from dbUser: " . $id);
        return false;
    }
    return true;
}
?>