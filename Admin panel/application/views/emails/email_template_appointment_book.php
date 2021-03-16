<!DOCTYPE HTML>
<head>
	<meta http-equiv="content-type" content="text/html" />
	<meta name="author" content="lovely" />

	<title>New Appointment Booked</title>
</head>

<body>
<p>Hi,<br />
New Appointment booking. Application ID : <?php echo $details->id; ?>
</p>
<?php $this->load->view("appointment/details_table",array("allow_edit"=>false)); ?>
</body>
</html>
