<!DOCTYPE html> 
<html>
	<head>
		<title>Register</title>
		<style>
			.error {color: #FF0000; }
		</style>
	</head>
	<body>
	
		<?php //escape user input.  //update to mysqli.  
			session_start();
			$conx = mysql_connect("localhost","root","") or die(mysql_error());
			mysql_select_db("account_system", $conx);
			
			$firstErr = $lastErr = $birthdateErr = $usernameErr = $emailErr = $email2Err = $passwordErr = $passErr = "";
			$first = $last = $birthdate = $username = $email = $email2 = $password = $pass = "";

			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				$valid = true;
				if (empty($_POST["first"])) {
					$firstErr = "First name is required";
					$valid = false;
				} else {	
					$first = test_input($_POST["first"]);
					if (!preg_match("/^[a-zA-Z ]*$/",$first)) {
						$firstErr = "Only letters and white space allowed";
						$valid = false;
					}
				}
			
				if (empty($_POST["last"])) {
					$lastErr = "Last name is required";
					$valid = false;
				} else {
					$last = test_input($_POST["last"]);
					if (!preg_match("/^[a-zA-Z ]*$/",$last)) {
						$lastErr = "Only letters and white space allowed";
						$valid = false;
					}
				}
				
				if (empty($_POST["birthdate"])) {
					$birthdateErr = "Birthdate is required";
					$valid = false;
				} else {
					$birthdate = test_input($_POST["birthdate"]);
				}
				
				if (empty($_POST["username"])) {
					$usernameErr = "Username is required";
					$valid = false;
				} else {
					$username = test_input($_POST["username"]);
					
					//this shouldnt work. i need to test it. //mysql_num_rows();
					$sql = "SELECT username FROM users WHERE username = '".$username."'";
					$result = mysql_query($sql, $conx) or die(mysql_error());
					foreach ($row as $val) { 
						if ($val === $username) {
							$usernameErr = "Username already exists";
							$valid = false;
						}
					}
				}
			
				if (empty($_POST["email"])) {
					$emailErr = "Email is required";
					$valid = false;
				} else {
					$email = test_input($_POST["email"]);
					if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email)) {
						$emailErr = "Invalid email format"; 
						$valid = false;
					}
				}
				
				if (empty($_POST["email2"])) {
					$email2Err = "Email confirmation is required";
					$valid = false;
				} else {
					$email2 = test_input($_POST["email2"]);
					if ($email !== $email2) {
						$email2Err = "Email confirmation does not match";
						$valid = false;
					}
				}
				
				if (empty($_POST["password"])) {
					$passwordErr = "Password is required";
					$valid = false;
				} else {
					$password = test_input($_POST["password"]);
				}
				
				if (empty($_POST["pass"])) {
					$passErr = "Password confirmation is required";
					$valid = false;
				} else {
					$pass = test_input($_POST["pass"]);
					if ($password !== $pass) {
						$pass2Err = "Password confirmation does not match";
						$valid = false;
					}
				}
				
				$_SESSION['first'] = $_POST['first']; $_SESSION['last'] = $_POST['last']; $_SESSION['birthdate'] = $_POST['birthdate']; 
				$_SESSION['username'] = $_POST['username']; $_SESSION['email'] = $_POST['email']; $_SESSION['password'] = $_POST['password'];
				
				//if valid then redirect
				if ($valid) {
					header("Location: subreg.php");
					exit();
				}
			}

			function test_input($data) {
				$data = trim($data);
				$data = stripslashes($data);
				$data = htmlspecialchars($data);
				return $data;
			}
			
			mysql_close($conx);
		?>
		<p><span class="error">* required field.</span></p>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" >
			First Name: <input type="text" name="first" value="<?php echo $first;?>"/>
			<span class="error">* <?php echo $firstErr;?></span>
			<br/><br/>
			Last Name: <input type="text" name="last" value="<?php echo $last;?>"/>
			<span class="error">* <?php echo $lastErr;?></span>
			<br/><br/>
			Birthdate: <input type="date" name="birthdate" value="<?php echo $birthdate;?>"/>
			<span class="error">* <?php echo $birthdateErr;?></span>
			<br/><br/>
			Username: <input type="text" name="username" value="<?php echo $username;?>"/>
			<span class="error">* <?php echo $usernameErr;?></span>
			<br/><br/>
			Email: <input type="text" name="email" value="<?php echo $email;?>"/>
			<span class="error">* <?php echo $emailErr;?></span>
			<br/><br/>
			Confirm Email: <input type="text" name="email2" value="<?php echo $email2;?>"/>
			<span class="error">* <?php echo $email2Err;?></span>
			<br/><br/>
			Password: <input type="text" name="password"/>
			<span class="error">* <?php echo $passwordErr;?></span>
			<br/><br/>
			Confirm Password: <input type="text" name="pass"/>
			<span class="error">* <?php echo $passErr;?></span>
			<br/><br/>
			<input type="submit" name="submit" value="Submit"/>
		</form>
	</body>
</html>
