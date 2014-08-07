<?php  //need to learn how to email that to user.  //need to redirect to a different page.  //validate that information was submitted, then redirect.
session_start();
$salt = substr(md5(rand()), 0, 5);
$passhash = md5( md5( $_SESSION['password'] ) . md5( $salt ) );
$ip = ip2long($_SERVER['REMOTE_ADDR']);
$validation = md5($_SESSION['email'] . $passhash);
$date = new DateTime();
$date = $date->format("Y-m-d H:i:s");
$username = $_SESSION['username'];
$email = $_SESSION['email'];
$vkey = md5( $email . $passhash );

$conx = mysql_connect("localhost","root","") or die(mysql_error());
mysql_select_db("account_system", $conx);

$sql = "INSERT INTO users ( `username`, `email`, `salt`, `passhash`, `reg_date`, `reg_ip`, `must_validate` )" .
"VALUES ( '$username', '$email', '$salt', '$passhash', '$date', '$ip', 1 )";

mysql_query($sql, $conx) or die(mysql_error());

//if ($valid) {
//	header("Location: somewhere.html");
//	exit();
//}

mysql_close($conx);
?>
