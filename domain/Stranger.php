<?php
/* Stranger Class
 * Version: 12 01 2013
 * Author: Ruben Martinez Jr.
 */
class Stranger {
    private $id; // Unqique User ID
    private $email; // User Email
    private $password; // Password
    private $firstname; // First Name
    private $lastname; // Last Name
    private $type; // Profile Picture
    private $location; // Geographic Origin
    private $city; // Current City
    private $likes; // Likes/Interests
    private $discussion; // Prefered topics of discussion
    private $gender; //Stranger's Gender
    private $interestedin; //Stranger's romantic interest
    private $messageIDs; // Messages Sent to/by User
    private $contacts; // Contacts
    private $futureDates; // Future Dates
    private $pastDates; // Past Dates
    private $rating; // User Rating
    private $active; // Is Account Active?
    private $access; // access level

    //Constructor Function
    function __construct($id, $email, $password, $firstname, $lastname, $type, $location, $city, $likes, $discussion, $gender, $interestedin, $messageIDs, $contacts, $futureDates, $pastDates, $rating, $access, $active) {
        $this->id           = $id;
        $this->email        = $email;
        $this->password     = $password;
        $this->firstname    = $firstname;
        $this->lastname     = $lastname;
        $this->type         = $type;
        $this->location     = $location;
        $this->city         = $city;
        $this->likes        = $likes;
        $this->discussion   = $discussion;
        $this->gender       = $gender;
        $this->interestedin = $interestedin;
        $this->messageIDs   = $messageIDs;
        $this->contacts     = $contacts;
        $this->futureDates  = $futureDates;
        $this->pastDates    = $pastDates;
        $this->rating       = $rating;
        $this->access       = $access;
        $this->active       = $active;
    }

    //Getter Functions
    function get_id() {
        return $this->id;
    } function set_id($new) { $this->id = $new; }
    function get_email() {
        return $this->email;
    } function set_email($new) { $this->email = $new; }
    function get_password() {
        return $this->password;
    } function set_password($new) { $this->password = $new; }
    function get_firstname() {
        return $this->firstname;
    } function set_firstname($new) { $this->firstname = $new; }
    function get_lastname() {
        return $this->lastname;
    } function set_lastname($new) { $this->lastname = $new; }
    function get_type() {
        return $this->type;
    } function set_type($new) { $this->type = $new; }
    function get_location() {
        return $this->location;
    } function set_location($new) { $this->location = $new; }
    function get_city() {
        return $this->city;
    } function set_city($new) { $this->city = $new; }
    function get_likes() {
        return $this->likes;
    } function set_likes($new) { $this->likes = $new; }
    function get_discussion() {
        return $this->discussion;
    } function set_discussion($new) { $this->discussion = $new; }
    function get_gender() {
        return $this->gender;
    } function set_gender($new) { $this->gender = $new; }
    function get_interestedin() {
        return $this->interestedin;
    } function set_interestedin($new) { $this->interestedin = $new; }
    function get_messageIDs() {
        return $this->messageIDs;
    } function set_messageIDs($new) { $this->messageIDs = $new; }
    function get_contacts() {
        return $this->contacts;
    } function set_contacts($new) { $this->contacts = $new; }
    function get_futureDates() {
        return $this->futureDates;
    } function set_futureDates($new) { $this->futureDates = $new; }
    function get_pastDates() {
        return $this->pastDates;
    } function set_pastDates($new) { $this->pastDates = $new; }
    function get_rating() {
        return $this->rating;
    } function set_rating($new) { $this->rating = $new; }
    function get_access() {
        return $this->access;
    } function set_access($new) { $this->access = $new; }
    function get_active() {
        return $this->active;
    } function set_active($new) { $this->active = $new; }
    function get_story() {
        return "<b>".$this->firstname."</b> is from ".urldecode($this->location)." & is interested in ".$this->likes.".<br><br>";
    }
}
?>