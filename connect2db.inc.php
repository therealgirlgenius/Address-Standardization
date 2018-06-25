<?php
/*	Author:        Kimberly Stepp
	Filename:      connect2db.inc.php
	Date Written:  June 22, 2018
	Description:   Connect program to SQL server
*/

function connect2db($dbName)
{
  // Create connection to SQL server
  $host     = "localhost";
  $uname    = "xxxxxx";
  $pass     = "xxxxxx";

  $connection = mysql_connect($host, $uname, $pass) // Connection parameters
    or
  die ("Connection to SQL server could not be established.\n");

  $result = mysql_select_db($dbName) // Use database
    or
  die ("<br />$dbName database could not be selected." . mysql_error());
  // End Create connection

} // End Function connect2db()

connect2db($dbName); // Call the function
?>
