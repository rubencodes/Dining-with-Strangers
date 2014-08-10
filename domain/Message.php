<?php
/* Message Class
 * Version: 5 July 2013
 * Author: Ruben Martinez Jr.
 */
include_once($_SERVER['DOCUMENT_ROOT'].'/database/dbStranger.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/database/dbUser.php');
class Message {
    private $id; // Unique ID
    private $payload; // Message Text
    private $sender; // Sender
    private $recipients; // Receiver(s)
    private $timestamp; // Timestamp
    private $read; // Read/Unread

    //Constructor Function
    function __construct($id, $payload, $sender, $recipients, $timestamp, $read) {
        $this->id         = $id;
        $this->payload    = $payload;
        $this->sender     = $sender;
        $this->recipients = $recipients;
        $this->timestamp  = $timestamp;
        $this->read       = $read;
    }

    //Getter Functions
    function get_id() {
        return $this->id;
    }
    function get_payload() {
        return $this->payload;
    }
    function get_sender() {
        return $this->sender;
    }
    function get_recipients() {
        return $this->recipients;
    }
    function get_timestamp() {
        return $this->timestamp;
    }
    function get_read() {
        return $this->read;
    } function set_read($new) { $this->read = $new; }
}
?>