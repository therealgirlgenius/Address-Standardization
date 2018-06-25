<?php
/*  Author:        Kimberly Stepp
    Date:          June 22, 2018
    Description:   Address Report
*/

$author			= "Kimberly Stepp";
$date	    	= "June 22, 2018";
$description	= "Address Report";
$title			= "View Address Report";
$stylesheet		= "styles";
$dbName			= "xxxxxx";

require ("connect2db.inc.php");
require ("htmlHead.inc");

echo "\t<h1>$title</h1>\n";

// SELECT QUERY FROM DATABASE
$query = "SELECT addressID,street,other,city,state,zip5,zip4,validated
          FROM address
          ORDER BY addressID";

$result = mysql_query($query) or die ("<b>Query Failed.</b><br />" . mysql_error());

// COUNT OF NUMBER OF RECORDS IN DATABASE
$numRecords = mysql_num_rows($result);

if ($numRecords == '0')
{
  echo "\t<p>($numRecords) Records Retrieved</p>\n";
  echo "\t<p><a href=\"form.php\">Return to Address Entry Form</a></p>\n";
  echo "\t<p><a href=\"menu.php\">Go to Main Menu</a></p>\n";
}
// BUILD TABLE AND DISPLAY RECORDS IN DATABASE
else
{
echo "\t<table class=\"collapse\">\n";
echo "\t\t<tr>\n";
echo "\t\t\t<th>Address 1</th>\n\t\t\t<th>Address 2</th>\n\t\t\t<th>City</th>\n";
echo "\t\t\t<th class=\"centered\">State</th>\n\t\t\t<th class=\"centered\">Zip Code</th>\n";
echo "\t\t\t<th class=\"centered\">Plus 4</th>\n\t\t\t<th class=\"centered\">Standardized</th>\n";
echo "\t\t</tr>\n";

while ($row = mysql_fetch_row($result))
{
  $street		= stripslashes($row[1]);
  $other  		= stripslashes($row[2]);
  $city			= stripslashes($row[3]);
  $state		= stripslashes($row[4]);
  $zip5			= stripslashes($row[5]);
  $zip4			= stripslashes($row[6]);
  $validated	= stripslashes($row[7]);
  $validated	= ($validated == 1) ? "YES" : "NO";

  echo "\t\t<tr>\n";
  echo "\t\t\t<td>$street</td>\n\t\t\t<td>$other</td>\n\t\t\t<td>$city</td>\n";
  echo "\t\t\t<td class=\"centered\">$state</td>\n\t\t\t<td class=\"centered\">$zip5</td>\n";
  echo "\t\t\t<td class=\"centered\">$zip4</td>\n\t\t\t<td class=\"centered\">$validated</td>\n";
  echo "\t\t</tr>\n";
}

echo "\t</table>\n";

echo "\t<p>($numRecords) Records Retrieved</p>\n";
echo "\t<p><a href=\"form.php\">Return to Address Entry Form</a></p>\n";
echo "\t<p><a href=\"menu.php\">Go to Main Menu</a></p>\n";
}

require ("htmlFoot.inc");
mysql_close();
?>
