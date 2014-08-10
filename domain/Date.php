<?php
/* Date Class
 * Version: 01 01 2014
 * Author: Ruben Martinez Jr.
 */
include_once($_SERVER['DOCUMENT_ROOT'].'/database/dbStranger.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/database/dbUser.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/database/dbPartner.php');
class Date {
    private $id; // Unique User ID
    private $name; // Name for Date; Values: "Romantic Date", "Dining Date", "Custom Date, [event name],"
    private $participants; // Concatenated Participant IDs
    private $size; // Number of Participants in Date
    private $maxsize; // Max Size of Date
    private $dateTime; // Time and Day of Date
    private $location; // Partner ID of Date Location, or Event Location
    private $city;    // City of Date
    private $romance; //Boolean, is Date of the Romantic Kind?
    private $active;  // Boolean, has Date Completed?

    //Constructor Function
    function __construct($id, $name, $participants, $size, $maxsize, $dateTime, $location, $city, $romance, $active) {
        $this->id           = $id;
        $this->name         = $name;
        $this->participants = $participants;
        $this->size         = $size;
        $this->maxsize      = $maxsize;
        $this->dateTime     = $dateTime;
        $this->location     = $location;
        $this->city         = $city;
        $this->romance      = $romance;
        $this->active       = $active;
    }

    //Getter Functions
    function get_id() {
        return $this->id;
    }
    function get_name() {
        return $this->name;
    }
    function set_name($new) { $this->name = $new; }
    function get_participants() {
        return $this->participants;
    }
	function set_participants($new) { $this->participants = $new; }
    function format_participants($userID) {
        $dateSize = $this->size;
        $participants = "";
        $counter = 0;
        foreach(explode(":",$this->participants) as $user) {
            if($userID != $user) {
                $counter = $counter+1;
                $thisuser = retrieve_dbUser($user);
                if ($thisuser == false) $thisuser = retrieve_dbStranger($user);
                if($thisuser != false) {
                    if($participants == "") $participants = $thisuser->get_firstname();
                    else if($dateSize > 2 && $counter == $dateSize) $participants = $participants.", and ".$thisuser->get_firstname();
                    else if($dateSize == 2 && $counter == $dateSize) $participants = $participants." and ".$thisuser->get_firstname();
                    else $participants = $participants.", ".$thisuser->get_firstname();
                }
            }
        }
        return $participants;
    }
    function get_story($userID) {
        $dateTime = explode(" ", $this->format_dateTime());
        $place = retrieve_dbPartner($this->location);
        return "You have a ".$this->name." scheduled for ".$dateTime[1].$dateTime[2]." on ".$dateTime[0]." at ".urldecode($place->get_name()).". Visit DiningWithStrangers.co for details!";
    }
    function format_dateTime() {
        return date("m/d/y h:i A", $this->dateTime);
    }
    function format_dateTimeLocation() {
        $dateTime = explode(" ", $this->format_dateTime());
        $place = retrieve_dbPartner($this->location);
        return urldecode($place->get_name())." on ".$dateTime[0]." at ".$dateTime[1].$dateTime[2];
    }
    function get_size() {
        return $this->size;
    }
	function set_size($new) { $this->size = $new; }
    function get_maxsize() {
        return $this->maxsize;
    }
    function get_full() {
        if($this->size == $this->maxsize) return true;
        return false;
    }
    function get_dateTime() {
        return $this->dateTime;
    }
    function set_dateTime($new) { $this->dateTime = $new; }
    function get_location() {
        return $this->location;
    }
    function get_city() {
        return $this->city;
    }
    function set_city($new) { $this->city = $new; }
    function get_romance() {
        return $this->romance;
    }
    function get_active() {
        return $this->active;
    }
}
?>