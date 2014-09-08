<?php

/*
 * Connection Sequence
 * Author: Ruben Martinez Jr.
 * Version: 25 April 2013
 */

function connect()
{
    $host      = “”;
    $database  = "";
    $user      = "";
    $password  = "";
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