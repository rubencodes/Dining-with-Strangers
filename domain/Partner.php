<?php
/* Partner Class
 * Version: 12 01 2013
 * Author: Ruben Martinez Jr.
 */
class Partner {
    private $id; // Unique ID
    private $name; // Formal Name of Partner
    private $location; // Location of Partner
    private $hours; // Business Hours of Partner
    private $codes; // Valid Promo Codes for this Partner
    private $promos; // What promotions is our partner running?
    private $restrictions; //hourly/daily restrictions: future of "business hours"
    //Constructor Function
    function __construct($id,$name,$location,$hours,$codes,$promos,$restrictions) {
        $this->id           = $id;
        $this->name 		= $name;
        $this->location 	= $location;
        $this->hours      	= $hours;
        $this->codes        = $codes;
        $this->promos     	= $promos;
        $this->restrictions = $restrictions;
    }
    
    //Getter Functions
    function get_id() {
        return $this->id;
    }
    function get_name() {
        return $this->name;
    }
    function get_location() {
        return $this->location;
    }
    function get_hours() {
        return $this->hours;
    }
    function get_codes() {
        return $this->codes;
    }
    function get_promos() {
        return $this->promos;
    }
    function get_restrictions() {
        return $this->restrictions;
    }
}
?>
