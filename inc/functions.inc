<?php
function stab() {
	// elegantly exits a script by including the footer
		require_once("./inc/footer.inc");
		die();
		
		// That was elegant.
}

function lock() {
	
	// Requires a user to be logged in to view the page.
	
	if (!isset($_SESSION['id'])) {
		header("Location: /");
		stab();
	}
}


function topimg() {
	// prints the logo and a logout button
	  echo ' 
	 <div class="row">
	 	<div class="span6">
	 	<img style="padding-bottom: 20px;" src="/assets/img/headerlogo.png">
	 	</div>
	 	<div class="span6">
	 		<div class="pull-right">
	 		';
	 		if (loggedIn()){echo '	<a href="/logout.php" class="btn">Logout</a>';}
	 		echo '
	 		</div>
	 	</div>
	</div>';
}

function alertWarning($title,$message) {
	echo "<div class=\"alert alert-block\">
	  <h4 class=\"alert-heading\">".$title."</h4>
	  ".$message."
	</div>";
} 
function alertError($title,$message) {
	echo "<div class=\"alert alert-error\">
	  <h4 class=\"alert-heading\">".$title."</h4>
	  ".$message."
	</div>";
}
function alertSuccess($title,$message) {
	echo "<div class=\"alert alert-success\">
	  <h4 class=\"alert-heading\">".$title."</h4>
	  ".$message."
	</div>";
}
function alertInfo($title,$message) {
	echo "<div class=\"alert alert-info\">
	  <h4 class=\"alert-heading\">".$title."</h4>
	  ".$message."
	</div>";
}

function emailValidate($email) {
	//Validates an email address
	if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
		return true;
	}
	return false;
}



function sanitize($string) {
	// Removes problematic characters
	return htmlspecialchars(trim(strip_tags($string)));
}
function sanitizeLower($string) {
	// Removes problematic characters
	return strtolower(sanitize($string));
}

function sendEmail($email,$subject,$text) {
	include("inc/swift/swift_required.php");

	/*
	 * Create the body of the message (a plain-text and an HTML version).
	 * $text is your plain-text email
	 * $html is your html version of the email
	 * If the reciever is able to view html emails then only the html
	 * email will be displayed
	 */

	$html = $text; // The email template is now at the Sengrid level 


	// This is your From email address
	$from = array('contact@killthelandline.com' => 'Kill The Landline');
	// Email recipients
	$to=array($email => '');

	// Login credentials

	// Fill this in

	$username = '';
	$password = '';

	// Setup Swift mailer parameters
	$transport = Swift_SmtpTransport::newInstance('smtp.sendgrid.net', 587);
	$transport->setUsername($username);
	$transport->setPassword($password);
	$swift = Swift_Mailer::newInstance($transport);

	// Create a message (subject)
	$message = new Swift_Message($subject);

	// attach the body of the email
	$message->setFrom($from);
	$message->setBody($html, 'text/html');
	$message->setTo($to);
	$message->addPart($text, 'text/plain');

	// send message 
	if ($recipients = $swift->send($message, $failures))
	{
	  // This will let us know how many users received this messagegi
	//  echo 'Message sent out to '.$recipients.' users';
	}
	// something went wrong =(
	else
	{
	  echo "Something went wrong with our email- ";
	  print_r($failures);
	} 
 return true;
}

function dbOpen() {

	// Pulls mysql credentials from the Heroku environment
	
  $url=parse_url(getenv("CLEARDB_DATABASE_URL"));

    mysql_pconnect(
            $server = $url["host"],
            $username = $url["user"],
            $password = $url["pass"]);
            $db=substr($url["path"],1);

    mysql_select_db($db);

}

function pw($salt,$input) {
	// Returns the salted password
	return sha1($salt.$password);
	
}

function loggedIn() {
	// Checks to see if a user is logged in based on their 'id' session variable.
	if (isset($_SESSION['id'])) {
	return true;
	}
	return false;
}

function setSession($id) {
		// Sets up a user session

	dbOpen();
	
	$id=mysql_real_escape_string($id);
	
	$query="select username, email, id from user where id = \"".$id."\"";
	
	$result=mysql_query($query);
	mysql_close();
	if (mysql_num_rows($result) !=1) { return false;}
	
	// Turn it into associative arrays
	$user = mysql_fetch_array($result, MYSQL_ASSOC);
	
	// and set the session
	$_SESSION['id']=$id;
	$_SESSION['email']=$user['email'];
	$_SESSION['username']=$user['username'];
	return true;
}

function existUser($email) {
		// This function checks to see if there is already a user account associated with an email address
		dbOpen();
		$email=strtolower(mysql_real_escape_string($email));

		$query="select count(*) from user where email=\"".$email."\"";
		$result=mysql_fetch_array(mysql_query($query));
		mysql_close();
		$num=$result[0];

		if ($num !=0) {
			return true;
		}
		else
			{
				return false;
			}

}

function inverseLock() {
	/* Stops logged in users from viewing the page
	by redirecting them to the dashboard.
	
	Example: A logged-in user shouldn't see the signup page.
	*/
	
	if (loggedIn()) {
		header("Location: dashboard.php");
		stab();

	}
}


?>