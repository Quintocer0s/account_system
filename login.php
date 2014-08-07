<!DOCTYPE html>
<html>
	<head>
		<title>Login</title>
		<style>
			.error {color: #FF0000; }
		</style>
	</head>
	<body>
	
		<?php //finish remember me process.
			session_start();
			$conx = mysql_connect("192.168.137.5","root","PenisKiss") or die(mysql_error());
			mysql_select_db("account_system", $conx);
			
			$usernameErr = $passwordErr = $username = $password = "";
			
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				$valid = true;
				
				if (empty($_POST["username"])) {
					$usernameErr = "Username is required";
					$valid = false;
				} else {
					$username = test_input($_POST["username"]);
					
					$sql = "SELECT * FROM users WHERE username = '".$username."'";
					$result = mysql_query($sql, $conx) or die(mysql_error());
					$row = mysql_fetch_assoc($result);
					
					if (empty($row['username'])) {
						$usernameErr = "Username does not exist";
						$valid = false;
					}
				}

				if (empty($_POST["password"])) {
					$passwordErr = "Password is required";
					$valid = false;
				} else {
					$password = test_input($_POST["password"]);
					$passhash = md5( md5($password) . md5( $row["salt"] ) );
					if ( $passhash !== $row["passhash"] ) {
						$passwordErr = "Wrong password";
						$valid = false;
					}
				}
			}

			if ($valid) {
				$_SESSION["USER_ID"] = $row["id"];
				//header("Location: ../php/register.php");
				//exit();
			}
	
			function test_input($data) {
				$data = trim($data);
				$data = stripslashes($data);
				$data = htmlspecialchars($data);
				return $data;
			}
	
			//email verification
			//check must verify. if set to 1 check for verification within URL
			//if no vURL then login fails. offer to send another email
			//if YES vURL, then reconstruct it and check that it matches.
			//if it does, set must verify to 0 and login.
			
			//remember me.
			
	
			mysql_close($conx);
		?>
		<p><span class="error"></span></p>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
			Username: <input type="text" name="username" value="<?php echo $username;?>"/>
			<span class="error">* <?php echo $usernameErr;?></span>
			<br/><br/>
			Password: <input type="text" name="password"/>
			<span class="error">* <?php echo $passwordErr;?></span>
			<br/><br/>
			<input type="submit" name="submit" value="Submit"/>
		</form>
	</body>
</html>