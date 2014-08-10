<?php
/* Message Database
 * Version: 5 July 2013
 * Author: Ruben Martinez Jr.
 */
require_once '../domain/Message.php';
require_once 'dbinfo.php';

function create_dbMessage()
{
    connect();
    mysql_query("DROP TABLE IF EXISTS dbMessage");
    $result = mysql_query("CREATE TABLE dbMessage(id TEXT, payload TEXT, sender TEXT, recipients TEXT, timestamp TEXT, read TEXT)");
    mysql_close();
    if (!$result) {
        echo mysql_error() . "Error creating dbMessage table. <br>";
        return false;
    }
    
    return true;
}

/*
 * add a Message to dbMessage table: if already there, return false
 */
function insert_dbMessage($message)
{
    if (!$message instanceof Message) {
        echo "Failed Insert";
        return false;
    }
    connect();
    $id     = mysql_real_escape_string($message->get_id());
    $query  = "SELECT * FROM dbMessage WHERE id = '" . $id . "' LIMIT 1";
    $result = mysql_query($query);
    //if there's no entry for this id, add message
    if (mysql_num_rows($result) == 0) {
        $query  = "INSERT INTO dbMessage VALUES('" . $message->get_id() . "','" . $message->get_payload() . "','" . $message->get_sender() . "','" . $message->get_recipients() . "','" . $message->get_timestamp() . "','" . $message->get_read() . "');";
        $result = mysql_query($query);
        if (!$result) {
            echo (mysql_error() . " Error inserting into database: " . $message->get_id() . "\n");
            mysql_close();
            return false;
        }
    } else {
        delete_dbMessage($message->get_id());
        insert_dbMessage($message);
        return false;
    }
    mysql_close();
    return true;
}

/*
 * @return a single row from dbMessage table matching a particular id.
 * if not in table, return false
 */
function retrieve_dbMessage($id)
{
    connect();
    $query  = "SELECT * FROM dbMessage WHERE id = '" . mysql_real_escape_string($id) . "' LIMIT 1";
    $result = mysql_query($query);
    if (!mysql_num_rows($result)) {
        mysql_close();
        return false;
    }
    $result_row = mysql_fetch_assoc($result);
    $message    = new Message($result_row['id'], $result_row['payload'], $result_row['sender'], $result_row['recipients'], $result_row['timestamp'], $result_row['read']);
    mysql_close();
    return $message;
}
function retrieveMy_dbMessages($userID) {
    connect();
    $query  = "SELECT * FROM dbMessage WHERE recipients = '" . mysql_real_escape_string($userID) . "' ORDER BY timestamp DESC";
    $result = mysql_query($query);
    if (!mysql_num_rows($result)) {
        mysql_close();
        return false;
    }
    $messages = array();
    while($result_row = mysql_fetch_array($result)) {
        $message    = new Message($result_row['id'], $result_row['payload'], $result_row['sender'], $result_row['recipients'], $result_row['timestamp'], $result_row['read']);
        $messages[] = $message;
    }
    mysql_close();
    return $messages;
}
function retrieveSent_dbMessages($userID) {
    connect();
    $query  = "SELECT * FROM dbMessage WHERE sender = '" . mysql_real_escape_string($userid) . "' ORDER BY timestamp DESC";
    $result = mysql_query($query);
    if (!mysql_num_rows($result)) {
        mysql_close();
        return false;
    }
    $messages = array();
    while($result_row = mysql_fetch_array($result)) {
        $message    = new Message($result_row['id'], $result_row['payload'], $result_row['sender'], $result_row['recipients'], $result_row['timestamp'], $result_row['read']);
        $messages[] = $message;
    }
    mysql_close();
    return $messages;
}
function update_dbMessage($message)
{
    if (!$message instanceof Message) {
        return false;
    }
    if (delete_dbMessage($message->get_id()))
        return insert_dbMessage($message);
    else {
        echo (mysql_error() . "Unable to update user database for: " . $message->get_id());
        return false;
    }
}

/*
 * remove an entry from dbMessage table.  If not there, return false
 */
function delete_dbMessage($id)
{
    connect();
    $id     = mysql_real_escape_string($id);
    $query  = 'DELETE FROM dbMessage WHERE id = "' . $id . '"  LIMIT 1';
    $result = mysql_query($query);
    mysql_close();
    if (!$result) {
        echo (mysql_error() . " Cannot delete from dbMessage: " . $id);
        return false;
    }
    return true;
}
?>