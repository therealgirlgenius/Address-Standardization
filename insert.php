<?php
/*
      Author:       Kimberly Stepp
      Date:         June 22, 2018
      Description:  Insert address records into address table in test database
*/

$author       = "Kimberly Stepp";
$date	      = "June 22, 2018";
$description  = "Insert address records into database";
$title	      = "Address Record Insert Confirmation";
$stylesheet	  = "styles";
$submit		  = $_POST['submit'];
$dbName	      = "xxxxxx";

require ("connect2db.inc.php");
require ("htmlHead.inc");

if (!isset($_POST['submit']))
{
	echo "This is not a stand-alone script. Please submit data ";
	die ("from this <a href=\"form.php\">form</a> or return to the <a href=\"menu.php\">Main Menu</a>.");
}

echo "\t<h1>$title</h1>\n";

$street				= strtoupper(mysql_real_escape_string($_POST['address1']));
$other				= strtoupper(mysql_real_escape_string($_POST['address2']));
$city				= strtoupper(mysql_real_escape_string($_POST['city']));
$state				= strtoupper(mysql_real_escape_string($_POST['state']));
$zip5				= mysql_real_escape_string($_POST['zipcode5']);
$zip4				= mysql_real_escape_string($_POST['zipcode4']);
$validated			= mysql_real_escape_string($_POST['selectedAddress']);

$streetValidated	= mysql_real_escape_string($_POST['address1Validated']);
$otherValidated		= mysql_real_escape_string($_POST['address2Validated']);;
$cityValidated		= mysql_real_escape_string($_POST['cityValidated']);
$stateValidated		= mysql_real_escape_string($_POST['stateValidated']);
$zip5Validated		= mysql_real_escape_string($_POST['zipcode5Validated']);
$zip4Validated		= mysql_real_escape_string($_POST['zipcode4Validated']);
$validated  		= mysql_real_escape_string($_POST['selectedAddress']);

if ($validated == '0')
{
	$query = "INSERT INTO address
          (street,other,city,state,zip5,zip4,validated)
		  VALUES
		  ('$street','$other','$city','$state','$zip5','$zip4','$validated')";

	$result = mysql_query($query) or die ("<b>Query Failed.</b><br />" . mysql_error());

	echo "\t<p>The record has been added to the database.</p>\n";
	echo "\t<p><a href=\"form.php\">Enter Another Address</a></p>\n";
	echo "\t<p><a href=\"addressReport.php\">View Address Report</a></p>\n";
	echo "\t<p><a href=\"menu.php\">Go to Main Menu</a></p>\n";			
}

else
{
	$query = "INSERT INTO address
          (street,other,city,state,zip5,zip4,validated)
		  VALUES
		  ('$streetValidated','$otherValidated','$cityValidated','$stateValidated','$zip5Validated','$zip4Validated','$validated')";

	$result = mysql_query($query) or die ("<b>Query Failed.</b><br />" . mysql_error());

	echo "\t<p>The record has been added to the database.</p>\n";
	echo "\t<p><a href=\"form.php\">Enter Another Address</a></p>\n";
	echo "\t<p><a href=\"addressReport.php\">View Address Report</a></p>\n";
	echo "\t<p><a href=\"menu.php\">Go to Main Menu</a></p>\n";		
}

require ("htmlFoot.inc");
mysql_close();
?>
