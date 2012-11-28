<?php
require_once("./inc/header.inc");
lock();
topimg();

/*
Adds a new line to the user account
*/
$error="";

// Did we find any info, or is the person starting fresh?
if (isset($_POST['submit'])) {
	// First, get the id and key -> could be get or post
	$fname=sanitize($_POST['fname']);
		if (empty($fname)) {$error=$error."<li>First name required</li>";}		
	$lname=sanitize($_POST['lname']);
			if (empty($lname)) {$error=$error."<li>Last name required</li>";}		
		
	$fromLine=sanitize($_POST['fromLine']);	
				if (empty($fromLine)) {$error=$error."<li>From line required</li>";}		
	
	$OutLine1=sanitize($_POST['OutLine1']);		
			if (empty($OutLine1)) {$error=$error."<li>Outbound Line required</li>";}		

	$OutLine2=sanitize($_POST['OutLine2']);		
	$OutLine3=sanitize($_POST['OutLine3']);		
	$address1=sanitize($_POST['address1']);		
				if (empty($address1)) {$error=$error."<li>Address Line 1 Required</li>";}		

	$address2=sanitize($_POST['address2']);
	$city=sanitize($_POST['city']);	
			if (empty($city)) {$error=$error."<li>City required</li>";}		
	
	$state=sanitize($_POST['state']);
			if (empty($state)) {$error=$error."<li>State required</li>";}		
		
	$zipcode=sanitize($_POST['zipcode']);		
			if (empty($zipcode)) {$error=$error."<li>Zipcode required</li>";}		
			
	
		
		
		
		if (!empty($error)) {
			alertError("Invalid Entries","<ul>".$error."</ul>");
			// Then go on to show the form
		}
		else {
			// Complete the process 
			dbOpen();
			$query='Insert into phone
			(fname,lname,fromLine,OutLine1,OutLine2,OutLine3,address1,address2,city,state,zipcode, userID) 
			values (
			"'.mysql_real_escape_string($fname).'",
			"'.mysql_real_escape_string($lname).'",
			"'.mysql_real_escape_string($fromLine).'",
			"'.mysql_real_escape_string($OutLine1).'",
			"'.mysql_real_escape_string($OutLine2).'",
			"'.mysql_real_escape_string($OutLine3).'",
			"'.mysql_real_escape_string($address1).'",
			"'.mysql_real_escape_string($address2).'",
			"'.mysql_real_escape_string($city).'",
			"'.mysql_real_escape_string($state).'",
			"'.mysql_real_escape_string($zipcode).'",
			"'.$_SESSION['id'].'" )';
			$result=mysql_query($query) or alertError('Error',mysql_error());
			header("Location: submitBill.php");

			mysql_close();
			 sendEmail("contact@killthelandline.com","New Line Submitted: $fname $lname","The user ".$_SESSION['username'].' (id = '.$_SESSION['id'].') has submitted a new line and we await a bill. Please reach out to them to confirm any necessary details.');

			
						
			stab();
		}
			
			// Show the form
			
			
		
	}
	
 // THE FORM DISPLAYED 
 
	echo '<div class="page-header"><h1>Add a New Line</h1></div>';	
	echo '<div class="row"><div class="span12">
			<div class="well" style="width:100%">
				<span class="label label-important">Important</span> This page is where you configure tranferring your current telephone number to Kill The Landline. All information provided must be what your current telephone company has on file. Please make sure all information matches your current phone bill.
			</div>
			<form class="form-horizontal" action="/newline.php" method="post">

			  <fieldset>
			
			     <div class="control-group">
			      <label class="control-label" for="fromLine">Phone Number to Transfer</label>
			      <div class="controls">
			        <input type="tel" class="input-xlarge" id="fromLine"  name="fromLine" value="'.$fromLine.'" >
			        <p class="help-block">This name must match your phone bill.</p>

			      </div>
			    </div>
			    <div class="control-group">
			      <label class="control-label" for="OutLine1">Where you Want To Forward The Number </label>
			      <div class="controls">
			        <input type="tel" class="input-xlarge" id="OutLine1"  name="OutLine1" value="'.$OutLine1.'" >
			      </div>
			    </div>
			     <div class="control-group">
			      <label class="control-label" for="OutLine2">Additional Forwarding Number <small>(optional)</small></label>
			      <div class="controls">
			        <input type="tel" class="input-xlarge" id="OutLine2"  name="OutLine2" value="'.$OutLine2.'" >
			      </div>
			    </div>
			    <div class="control-group">
			      <label class="control-label" for="OutLine3">Additional Forwarding Number <small>(optional)</small></label>
			      <div class="controls">
			        <input type="tel" class="input-xlarge" id="OutLine3"  name="OutLine3" value="'.$OutLine3.'" >
			      </div>
			    </div>
			       
			   <div class="control-group">
			      <label class="control-label" for="fname">First Name</label>
			      <div class="controls">
			        <input type="text" class="input-xlarge" id="fname"  name="fname" value="'.$fname.'" >
			        <p class="help-block">This name must match your phone bill.</p>

			      </div>
			    </div>
			   <div class="control-group">
			      <label class="control-label" for="fname">Last Name</label>
			      <div class="controls">
			        <input type="text" class="input-xlarge" id="lname"  name="lname" value="'.$lname.'" >

			      </div>
			    </div>
			    
			    <div class="control-group">
			      <label class="control-label" for="address1">Address</label>
			      <div class="controls">
			        <input type="text" class="input-xlarge" id="address1"  name="address1" value="'.$address1.'" >

			      </div>
			    </div>
			    <div class="control-group">
			      <label class="control-label" for="address2">Address 2 <small>(if needed)</small></label>
			      <div class="controls">
			        <input type="text" class="input-xlarge" id="address2"  name="address2" value="'.$address2.'" >

			      </div>
			    </div>
			    <div class="control-group">
			      <label class="control-label" for="city">City</label>
			      <div class="controls">
			        <input type="text" class="input-xlarge" id="city"  name="city" value="'.$city.'" >

			      </div>
			    </div>
			    
			    
			   <div class="control-group">
			      <label class="control-label" for="state">State</label>
			      <div class="controls">
			        '.stateSelect().'
			       <p class="help-block">You must be in the United States.</p>

			      </div>
			    </div>
			   
			   				    <div class="control-group">
			      <label class="control-label" for="zipcode">Zipcode</label>
			      <div class="controls">
			        <input type="text" class="input-xlarge" id="zipcode"  name="zipcode" value="'.$zipcode.'" >

			      </div>
			    </div>
			    ';
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
			  <button type="submit" name="submit" class="btn btn-primary" value="true" >Submit</button> (Note: This does not begin the transfer)
			</div>
			</form>				
			</div>
			</div>';

	
	
stab();







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
function stateSelect() {
return '	<select name="state">
	<option value="AL">Alabama</option>
	<option value="AK">Alaska</option>
	<option value="AZ">Arizona</option>
	<option value="AR">Arkansas</option>
	<option value="CA">California</option>
	<option value="CO">Colorado</option>
	<option value="CT">Connecticut</option>
	<option value="DE">Delaware</option>
	<option value="DC">District of Columbia</option>
	<option value="FL">Florida</option>
	<option value="GA">Georgia</option>
	<option value="HI">Hawaii</option>
	<option value="ID">Idaho</option>
	<option value="IL">Illinois</option>
	<option value="IN">Indiana</option>
	<option value="IA">Iowa</option>
	<option value="KS">Kansas</option>
	<option value="KY">Kentucky</option>
	<option value="LA">Louisiana</option>
	<option value="ME">Maine</option>
	<option value="MD">Maryland</option>
	<option value="MA">Massachusetts</option>
	<option value="MI">Michigan</option>
	<option value="MN">Minnesota</option>
	<option value="MS">Mississippi</option>
	<option value="MO">Missouri</option>
	<option value="MT">Montana</option>
	<option value="NE">Nebraska</option>
	<option value="NV">Nevada</option>
	<option value="NH">New Hampshire</option>
	<option value="NJ">New Jersey</option>
	<option value="NM">New Mexico</option>
	<option value="NY">New York</option>
	<option value="NC">North Carolina</option>
	<option value="ND">North Dakota</option>
	<option value="OH">Ohio</option>
	<option value="OK">Oklahoma</option>
	<option value="OR">Oregon</option>
	<option value="PA">Pennsylvania</option>
	<option value="RI">Rhode Island</option>
	<option value="SC">South Carolina</option>
	<option value="SD">South Dakota</option>
	<option value="TN">Tennessee</option>
	<option value="TX">Texas</option>
	<option value="UT">Utah</option>
	<option value="VT">Vermont</option>
	<option value="VA">Virginia</option>
	<option value="WA">Washington</option>
	<option value="WV">West Virginia</option>
	<option value="WI">Wisconsin</option>
	<option value="WY">Wyoming</option>
</select>';
}

?>