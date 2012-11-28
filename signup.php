<?php
 require_once("./inc/header.inc");
 inverseLock();
 if (isset($_POST['email'])) {
	// Process signup
	$error="";
	if (!emailValidate($_POST['email'])) {
		$error=$error."<li>Invalid Email Address</li>";
	}
	else
	{$email=sanitizeLower($_POST['email']);}
	
	// RECAPTCHA STUFF
	// ok, no more recaptcha
	// END RECAPTCHA

	// Now we check if email is already in use
	//require_once("./inc/sessionFunctions.inc");
	if (existUser($email)) {
	// This user already has an account! 
		$error=$error.'<li>This email already exists in our system. Forgot your password? <a href="/recoverPassword.php">Reset it here.</a></li>';
	}
	
	if (empty($error)) {
		// SEND A CONFIRMATION EMAIL 
		if (loginProcessEmail($email)){
		topimg();
			echo '<div class="row"><div class="span4 offset4">'.alertSuccess("Account Created","Check your email to complete the process!").'</div></div>';
			stab();
		}
	}
	
}
	// Show signup form and take email address. 
?>	
<div class="row">
		<div class="span4 offset4 well">
	<center>	<img src="/assets/img/headerlogo.png" style="padding-bottom: 20px;" alt="Kill The Landline" align="center" /><br />
	<h2>Sign Up</h2></center>
                  <form action="/signup.php" method="post" accept-charset="UTF-8">   
<?php if ($error) {echo '<div class="alert alert-error"><ul>'.$error.'</ul></div>';} ?>                    
                      		<input type="text" id="email" class="span4" name="email" autofocus="autofocus" placeholder="email@example.com">
			
                       <center><button class="btn btn-primary btn-block" type="submit" name="submit">Submit</button><center>
                       
                  </form>
          </div>
    </div>

<?php	
require_once("./inc/footer.inc"); 

////////////////////////

function loginProcessEmail($email) {
	/* At the signup page, users give us an email. 
	This function does three things:
	2) It creates a temporary token
	3) It emails them a confirmation email with an activation email
	4) It directs them to confirmEmail.php with the auth token and directs them to enter their password and complete a reCaptcha
	*/
	// Create the temporary token
	$key="";
	$i=0;
	while ($i<10) { $key.=chr(rand(97,122)); $i++;}
	// Create in user database with temporary token
	dbOpen();
	// FIRST, find an old key
	if (empty($email)) {stab();} // security check
	$flush="select * from emailconfirm where email=\"".$email."\"";
	$oldKey=mysql_query($flush);
	if (mysql_num_rows($oldKey)==1) {
		// There's already an existing key - just send it again;
		$old=mysql_fetch_array($oldKey);
		$id=$old['id'];
		$key=$old['confirmKey'];
		
	}
	else {
	$query="insert into 
				emailconfirm (email,ip,confirmKey) 
				values 
				(\"".mysql_real_escape_string($email)."\",\"".mysql_real_escape_string($_SERVER['REMOTE_ADDR'])."\",\"".mysql_real_escape_string($key)."\")
				";
	$result=mysql_query($query);
	$id=mysql_insert_id();
	}
	//      echo $query;
	
	mysql_close(); // User created
	
	// Email them confirmation email
	$subject = "[Action Required] Confirm Your Email";
	$body='Thanks for registering with Kill The Landline. Please click this link to confirm your email: <br />
	<br />
	<a href="https://app.killthelandline.com/confirm.php?id='.$id.'&key='.$key.'">https://app.killthelandline.com/confirm.php?id='.$id.'&key='.$key.'</a>';
	
	// echo "[$email]<br />[$subject]<br />[$body]";
	sendEmail($email,$subject,$body);
	return true;
	
}
	
	






?>
