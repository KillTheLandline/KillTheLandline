<?php
require_once("./inc/header.inc");
inverseLock();
topimg();

/*
Finishes the signup process
1) Checks whether the id/key combo are correct
2) Collects info from user
	a) Name
	b) Password
3) Deletes temporary key and creates user
*/

// Fetch the 'get' info
$idcombo = isset($_GET['id'])&&isset($_GET['key']);
$submit=isset($_POST['id'])&&isset($_POST['key']);

// Did we find any info, or is the person starting fresh?
if ($idcombo||$submit) {
	// First, get the id and key -> could be get or post
	$signupID=sanitize($_REQUEST['id']);
	$confirmKey=sanitize($_REQUEST['key']);
	
	if (!validateKey($signupID,$confirmKey)){
		alertError("Invalid or Expired Link",'The link you clicked is either expired or invalid. Please go to the <a href="/signup.php">signup page</a> to request a new link.');
		stab();
		}
		
	// Ok, now we check to see if the key 	
	$email=validateKey($signupID,$confirmKey);
	
	
	if ($submit) {
		// Check for entries
		$error="";
		// START RECAPTCHA
	/*	require_once('./inc/recaptchalib.php');
	 		$privatekey = "6LfrHNcSAAAAAPf1WhvGtDaDmbAiWy1rdCzrLmNB";
			 $resp = recaptcha_check_answer ($privatekey,
			                                $_SERVER["REMOTE_ADDR"],
			                                $_POST["recaptcha_challenge_field"],
			                                $_POST["recaptcha_response_field"]);

			  if (!$resp->is_valid) {
			    // What happens when the CAPTCHA was entered incorrectly
			    $error=$error."<li>The reCAPTCHA wasn't entered correctly. </li>";
			  }
		// END RECAPTCHA
	*/
		// Let's check if the data is up to par
		$password=sanitize($_POST['password']);
		$username=sanitize($_POST['username']);		
		if (strlen($password)<6) {$error=$error."<li>Password too short. It must be 6 characters long, and it's currently <b>".strlen($password)."</b></li>";}
		if (strlen($password)>=100) {$error=$error."<li>Your password is too long. Let's keep it under 100 characters.</li>";}
		if (empty($username)) {$error=$error."<li>You need a username! You use it to sign in.</li>"; }
		if (strlen($username)>40) {$error =$error."<li>Your username is limited to 40 characters - it's currently <b>".strlen($name)."</b></li>";}
	
		
		
		
		if (!empty($error)) {
			alertError("Invalid Entries","<ul>".$error."</ul>");
			// Then go on to show the form
		}
		else {
			// Complete the process 
			
			// Generate a salt
			$salt="";
			$i=0;
	while ($i<15) { $salt.=chr(rand(97,122)); $i++;}
			
			processNewUser($email,$username,$salt, pw($salt,$password));
			unset($password); // for security
			
			// TODO: Make this pretty; use alertSuccess();
			
			header( 'Location: /dashboard.php?create=true' ) ;
			echo '<a href="/dashboard.php">Account created! Check out the dashboard </a>';
			
			stab();
		}
			
			// Show the form
			
			
		
	}
	
 // THE FORM DISPLAYED 
 
	echo '<div class="page-header"><h1>Sign Up <small>Now we just need a couple final details</small></h1></div>';	
	echo '<div class="row"><div class="span12">
			<form class="form-horizontal" action="/confirm.php" method="post">
			<input type="hidden" id="key" value="'.$confirmKey.'" name="key">
			<input type="hidden" id="id" value="'.$signupID.'" name="id">

			  <fieldset>
			    <div class="control-group">
			      <label class="control-label" for="email" >Email</label>
			      <div class="controls">
			      <span class="input-xlarge uneditable-input">'.strtolower($email).'</span>
			       		<!--<p class="help-block">This is your username</p>-->

			      </div>
			    </div>
			    
			   <div class="control-group">
			      <label class="control-label" for="username">Username</label>
			      <div class="controls">
			        <input type="text" class="input-xlarge" id="username"  name="username" value="'; if (isset($username)) {echo $username;} echo '" >
			        <p class="help-block">You will use this to log in.</p>

			      </div>
			    </div>
			   
			   	<div class="control-group">
			      <label class="control-label" for="password">Password</label>
			      <div class="controls">
			        <input type="password" class="input-xlarge" id="password" name="password">
			        <p class="help-block">Let\'s keep it at least 6 characters long.</p>

			      </div>
			    </div>';
			    /*
				 <div class="control-group">
				      <label class="control-label" for="recaptcha" >One final part</label>
				      <div class="controls">';
						          require_once('inc/recaptchalib.php');
						          $publickey = "6LfrHNcSAAAAAIXLTs7e5Hu2XYjmGZ1ovE498z7C"; // you got this from the signup page
						          echo recaptcha_get_html($publickey, $error = null, $use_ssl = true).'
						    
				      </div>
				    </div>
			
				*/
				echo '
				
			
			  </fieldset>
			  <div class="form-actions">	
			  <button type="submit" class="btn btn-primary">Submit</button>
			</div>
			</form>				
			</div>
			</div>';

	
	
	
}
else {
		alertError("Invalid or Expired Link",'The link you clicked is either expired or invalid. Please go to the <a href="/signup.php">signup page</a> to request a new link.');

stab();	
}








/////////////////////////////////////


function validateKey($id,$key) {
	dbOpen();
	$key=mysql_real_escape_string($key);
	$id=mysql_real_escape_string($id);
	
	$query="select email from emailconfirm where (id=\"$id\" and confirmKey=\"$key\")";
	// echo $query;
	$result=mysql_query($query);
	mysql_close();
	if (mysql_num_rows($result) ==1) {
		$row=mysql_fetch_array($result);
		return $row['email'];
	}
	else
		{
			return false;
		}
	
}
function processNewUser($email,$username,$salt,$password) {
	// Inserts a new user into the database
		if (!existUser($email)) {
	
		dbOpen();
		// First, create the user. 
		$email=mysql_real_escape_string($email);
		$username=mysql_real_escape_string($username);
		// trust $salt
		// trust $password
		 // Don't forget the pw function!
			$query="insert into 
					user (email,username,salt,password) 
					values 
					(\"".$email."\",\"".$username."\",\"".$salt."\",\"".$password."\")
					";
			//	echo $query;
		$result=mysql_query($query);
		$id=mysql_insert_id(); // Use this to log in the user
		
		// Now we delete the temporary user
		// QUICK final security check:
		if (empty($email)) {die();}
		$deleteQuery="delete from emailconfirm where email=\"".strtolower($email)."\"";
		mysql_query($deleteQuery);// Return doesnt' really matter because leftover temprary 
		
		mysql_close();
		
		return setSession($id);
		
	}
	else { return false;}
	
}

?>