<p>Thanks for your appointment</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>Your appointment for <?php echo $business->bus_title ?> is confirmed.</p>
<table>
    <tr><td>App No :</td><td><?php echo $app_id; ?></td></tr>
    <tr><td>Date :</td><td><?php echo date("d M, Y",strtotime($infoarray["app-date"])); ?></td></tr>
    <tr><td>Time :</td><td><?php echo date("H:i A",strtotime($infoarray["app-time"])); ?></td></tr>
    <tr><td>Visit Address :</td><td><?php echo $business->bus_google_street; ?></td></tr>
    <tr><td>Contact :</td><td><?php echo $business->bus_contact; ?></td></tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>Thanks</p>