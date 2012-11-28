<?php
require_once('./inc/header.inc');
inverseLock();

$failedLogin=false;

if (isset($_POST['username']) && isset($_POST['password'])) {
	
	// Someone is trying to log in!
	
	$result = validate($_POST['username'],$_POST['password']);
	
	if ($result) {
	
	// SUCCESSFUL LOGINn
		setSession($result);
		header("Location: dashboard.php");
		stab();
	}
	else {$failedLogin=TRUE;}
	
	
}
	
?>



<div class="row"><div class="pull-right" style="padding-bottom:50px;">Don't have an account? <a href="/signup.php" class="btn btn-inverse">Sign Up</a></div></div>

<div class="row">
		<div class="span4 offset4 well">
	<center>	<img src="/assets/img/headerlogo.png" style="padding-bottom: 20px;" alt="Kill The Landline" align="center" /></center>
                  <form action="/index.php" method="post" accept-charset="UTF-8">   
<?php if ($failedLogin) {alertError('Login Failed','Need to <a href="/resetPassword.php">reset your password</a>?<br />');} ?>                    
                      		<input type="text" id="username" class="span4" name="username" <?php if ($failedLogin) { echo 'value="'.$_POST['email'].'"';} else {echo 'placeholder="Username"';}?>  <?php if (!$failedLogin) { echo 'autofocus="autofocus"';}?>>
			<input type="password" id="password" class="span4" name="password" placeholder="Password" <?php if ($failedLogin && isset($_POST['email'])) { echo 'autofocus="autofocus"';}?>>

                       <button class="btn btn-info btn-block" type="submit" name="submit">Sign in</button>
                  </form>
          </div>
          </div>

<?php

function validate($username,$password){
		// Checks if email and password are correct. If so, returns user id. 
	dbOpen();
	
	$username=mysql_real_escape_string(sanitizeLower($username));
	
	$saltQuery="select salt from user where username=\"".$username."\" OR email=\"".$username."\"";
	//echo $saltQuery;
	$saltResult=mysql_query($saltQuery);
	mysql_close();
	if (mysql_num_rows($saltResult)!=1) { return FALSE;}
	else {
	 		$salty= mysql_fetch_array($saltResult, MYSQL_ASSOC);
			$salt=$salty['salt'];
			// We now know that the username /email exists - but is the password right?
			
			$password = pw($salt,sanitize($password));  // Don't forget to hash it accordingly!
			dbOpen();
			$query="select id from user where ( email = \"".$username ."\" OR username= \"".$username ."\" ) && password= \"".$password."\" ";

			$result=mysql_query($query);
		//	echo $query;
			mysql_close();
			if (mysql_num_rows($result)!=1) { return FALSE;}
			else {
	 		$user= mysql_fetch_array($result, MYSQL_ASSOC);
			return $user['id'];
	}

			
	}
	
	$query="select id from user where ( email = \"".$email ."\" && password= \"".$password."\" )";

	$result=mysql_query($query);
	mysql_close();
	if (mysql_num_rows($result)!=1) { return FALSE;}
	else {
	 		$user= mysql_fetch_array($result, MYSQL_ASSOC);
			return $user['id'];
	}
}
	
	





require_once('./inc/footer.inc');
?>