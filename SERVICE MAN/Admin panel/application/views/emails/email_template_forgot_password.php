<!DOCTYPE HTML>
<head>
	<meta http-equiv="content-type" content="text/html" />
	<meta name="author" content="lovely" />

	<title>Forgot Password</title>
</head>

<body>
<p>Hi,
<br />
Your password forgot request is accepted please visit following link to change your password.
</p>
<p>
<?php echo site_url("users/modify_password/".$token); ?>
</p>
</body>
</html>

