<?php

/*
 * Connection Sequence
 * Author: Ruben Martinez Jr.
 * Version: 25 April 2013
 */

function connect()
{
    $host      = "BowdoinStrangers.db.11389874.hostedresource.com";
    $database  = "BowdoinStrangers";
    $user      = "BowdoinStrangers";
    $password  = "DwS1029Rox!";
    $connected = mysql_connect($host, $user, $password);
    if (!$connected)
        return mysql_error();
    $selected = mysql_select_db($database, $connected);
    if (!$selected)
        return mysql_error();
    else
        return true;
}
?>