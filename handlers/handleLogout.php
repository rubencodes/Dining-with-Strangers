<?
session_start();
include_once('../root.php');
if(isset($_SESSION['BowdoinDwSuserID'])) unset($_SESSION['BowdoinDwSuserID']);
header( 'Location: '.root());
?>