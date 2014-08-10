<?php
/*
 * Connection Sequence
 * Author: Ruben Martinez Jr.
 * Version: 10 August 2013
 */

function connection()
{ 
 $mysqli = new mysqli("BowdoinStrangers.db.11389874.hostedresource.com", "BowdoinStrangers", "DwS1029Rox!", "BowdoinStrangers");
 /* check connection */
 if (mysqli_connect_errno()) {
     printf("Connect failed: %s\n", mysqli_connect_error());
     exit();
 }
 return $mysqli;
}
?>