<?php
/*  Author:			Kimberly Stepp
    Date:			June 22, 2018
    Description:	form.php
*/

$author			= "Kimberly Stepp";
$date			= "June 22, 2018";
$description	= "PHP Form for Address Entry & Validation";
$title			= "Address Entry Form";
$requiredTitle  = "Fields marked with an asterisk (*) are required.";
$stylesheet		= "styles";
$thisScript		= htmlspecialchars($_SERVER['PHP_SELF']);
$startOver		= "form.php";
$processForm	= "insert.php";
$submit			= $_POST['submit'];
$dbName			= "xxxxxx";

// DECLARE VARIABLES AND SET TO EMPTY VALUES
$street = $other = $city = $state = $zip5 = $zip4 = "";
$selectError = "";

require("connect2db.inc.php");
require("htmlHead.inc");

// TRUE BLOCK FOR IF !ISSET
if (!isset($submit))
{ // DISPLAY ADDRESS ENTRY FORM
echo <<<HEREDOC
	<h1>$title</h1>
	<h2>$requiredTitle</h2>
	<form action="$thisScript" method="post">
		<fieldset>
			<legend>Add an Address</legend>
			<p><label class="oneFifty">Street Address</label>
			   <input type="text" name="address1" maxlength="38" required /> *
			</p>
			<p><label class="oneFifty">Apt / Suite / Other</label>
			   <input type="text" name="address2" maxlength="38" />
			</p>
			<p><label class="oneFifty">City</label>
			   <input type="text" name="city" maxlength="15" required /> *
			</p>
			<p><label class="oneFifty">State</label>
			   <input type="text" name="state" maxlength="2" required /> *
			</p>
			<p><label class="oneFifty">Zip Code</label>
			   <input type="text" name="zip" maxlength="5" required /> *
			</p>
			<input type="reset" name="reset" value="Clear Form" />
			<input type="submit" name="submit" value="Validate" />
		</fieldset>
	</form>\n
HEREDOC;
} // END TRUE BLOCK IF !ISSET

// FALSE BLOCK FOR IF !ISSET
else
{
$street		= $_POST['address1'];
$other		= $_POST['address2'];
$city		= $_POST['city'];
$state		= $_POST['state'];
$zip5		= $_POST['zip'];

// CONFIGURE XML REQUEST
$xmlrequest = <<< XMLREQUEST
  <AddressValidateRequest USERID='xxxxxx'>
    <Address>
	  <Address1>$other</Address1>
	  <Address2>$street</Address2>
	  <City>$city</City>
	  <State>$state</State>
	  <Zip5>$zip5</Zip5>
	  <Zip4></Zip4>
	</Address>
  </AddressValidateRequest>
XMLREQUEST;

$request = "http://production.shippingapis.com/ShippingAPI.dll?API=Verify&XML=" . urlencode($xmlrequest);

// OPEN FILE FOR READING & WRITING
$myFile = fopen("./xxxxxx.xml", "w+");

// INITIALIZE AND EXECUTE CURL REQUEST
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $request);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FILE, $myFile);
curl_exec($ch);
curl_close($ch);

// CLOSE FILE
fclose($myFile);

// PARSE XML DATA
$xml = simplexml_load_file("xxxxxx.xml");

// GET XML ATTRIBUTES & ELEMENTS; INITIALIZE VARIABLES
$output = $xml->Address;
$errorOutput = $xml->Address->Error;

$streetValidated	= $output[0]->Address2;
$otherValidated		= $output[0]->Address1;
$cityValidated		= $output[0]->City;
$stateValidated		= $output[0]->State;
$zip5Validated		= $output[0]->Zip5;
$zip4Validated		= $output[0]->Zip4;
$validationError	= $errorOutput[0]->Description;

// IF-ELSEIF-ELSE BLOCK FOR VALIDATION CHECK
// USPS ADDRESS NOT FOUND
if (!empty($validationError))
{	
echo <<<HEREDOC
	<h1>Address Entry Form</h1>
	<h2 class="error">$validationError Please correct address errors. $requiredTitle</h2>
	<form action="$thisScript" method="post">
		<fieldset>
			<legend>Add an Address</legend>
			<p><label class="oneFifty">Street Address</label>
			   <input type="text" name="address1" value="$street" maxlength="38" required /> *
			</p>
			<p><label class="oneFifty">Apt / Suite / Other</label>
			   <input type="text" name="address2" value="$other" maxlength="38" />
			</p>
			<p><label class="oneFifty">City</label>
			   <input type="text" name="city" value="$city" maxlength="15" required /> *
			</p>
			<p><label class="oneFifty">State</label>
			   <input type="text" name="state" value="$state" maxlength="2" required /> *
			</p>
			<p><label class="oneFifty">Zip Code</label>
			   <input type="text" name="zip" value="$zip5" maxlength="5" required /> *
			</p>
			<input type="submit" name="reset" formaction="$startOver" value="Clear Form" />
			<input type="submit" name="submit" value="Validate" />
		</fieldset>
	</form>\n
HEREDOC;
}
// USPS	DOES NOT RETURN PLUS4
elseif ($zip4Validated == "")
{
$validationError = "Address cannot be standardized as entered.";
echo <<<HEREDOC
	<h1>Address Entry Form</h1>
	<h2 class="error">$validationError Please correct address errors.  $requiredTitle</h2>
	<form action="$thisScript" method="post">
		<fieldset>
			<legend>Add an Address</legend>
			<p><label class="oneFifty">Street Address</label>
			   <input type="text" name="address1" value="$street" maxlength="38" required /> *
			</p>
			<p><label class="oneFifty">Apt / Suite / Other</label>
			   <input type="text" name="address2" value="$other" maxlength="38" />
			</p>
			<p><label class="oneFifty">City</label>
			   <input type="text" name="city" value="$city" maxlength="15" required /> *
			</p>
			<p><label class="oneFifty">State</label>
			   <input type="text" name="state" value="$state" maxlength="2" required /> *
			</p>
			<p><label class="oneFifty">Zip Code</label>
			   <input type="text" name="zip" value="$zip5" maxlength="5" required /> *
			</p>
			<input type="submit" name="reset" formaction="$startOver" value="Clear Form" />
			<input type="submit" name="submit" value="Validate" />
		</fieldset>
	</form>\n
HEREDOC;
}
// NO KNOWN ERRORS
else
{
echo "\t<h1>Address Selection</h1>\n";
echo "\t<form action=\"$processForm\" method=\"post\">\n\t\t<fieldset>\n";
echo "\t\t\t<legend>Please select the address that you wish to record in the database</legend>\n";
echo "\t\t\t<table class=\"selectionTable\">\n";
echo "\t\t\t\t<tr>\n";
echo "\t\t\t\t\t<td><input type=\"radio\" name=\"selectedAddress\" value=\"0\" required /></td>\n";
echo "\t\t\t\t\t<td><b>Your Entry:</b><br />\n";
echo "<input type=\"hidden\" name=\"address1\" value=\"$street\">$street ";
echo "<input type=\"hidden\" name=\"address2\" value=\"$other\">$other<br />";
echo "<input type=\"hidden\" name=\"city\" value=\"$city\">$city, ";
echo "<input type=\"hidden\" name=\"state\" value=\"$state\">$state  ";
echo "<input type=\"hidden\" name=\"zipcode5\" value=\"$zip5\">$zip5";
echo "<input type=\"hidden\" name=\"zipcode4\" value=\"$zip4\"></td>\n";
echo "\t\t\t\t</tr>\n";
echo "\t\t\t\t<tr>\n";
echo "\t\t\t\t\t<td><input type=\"radio\" name=\"selectedAddress\" value=\"1\"></td>\n";
echo "\t\t\t\t\t<td><b>USPS Standardized Entry:</b><br />\n";
echo "<input type=\"hidden\" name=\"address1Validated\" value=\"$streetValidated\">$streetValidated ";
echo "<input type=\"hidden\" name=\"address2Validated\" value=\"$otherValidated\">$otherValidated<br />";
echo "<input type=\"hidden\" name=\"cityValidated\" value=\"$cityValidated\">$cityValidated, ";
echo "<input type=\"hidden\" name=\"stateValidated\" value=\"$stateValidated\">$stateValidated  ";
echo "<input type=\"hidden\" name=\"zipcode5Validated\" value=\"$zip5Validated\">$zip5Validated-";
echo "<input type=\"hidden\" name=\"zipcode4Validated\" value=\"$zip4Validated\">$zip4Validated</td>\n";
echo "\t\t\t\t</tr>\n";
echo "\t\t\t</table>\n";
echo "\t\t\t<input type=\"reset\" name=\"reset\" value=\"Clear Form\" />\n";
echo "\t\t\t<input type=\"submit\" name=\"submit\" value=\"Add to Database\" />\n";
echo "\t\t</fieldset>\n\t</form>\n";
} // END IF-ELSEIF-ELSE BLOCK FOR VALIDATION CHECK

} // END FALSE BLOCK FOR IF NOT ISSET

mysql_close();
require("htmlFoot.inc");
?>
