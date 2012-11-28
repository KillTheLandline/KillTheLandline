<?php
require_once("./inc/header.inc");
lock();
topimg();
// Logged in interface!

$query="select * from phone where userID=\"".$_SESSION['id']."\"";
dbOpen();
$result=mysql_query($query);
if (mysql_num_rows($result)==0) {
		header("Location: newline.php");
}

?>
<div class="row"><div class="pull-right" style="padding-bottom: 20px"><a href="/newline.php" class="btn btn-smal btn-info">Add a New Line</a></div></div>
<div class="row"><div class="span12">
<table class="table table-hover  table-bordered">
<thead>
<tr>
	<th class="span2">Status</th>
	<th class="span4">Incoming Line</th>
	<th class="span4">Dialing Line(s)</th>
	<th class="span1">Manage</th>
	<th class="span1">Cancel</th>
</tr>
</thead>

<tbody>

<?php
while ($phone = mysql_fetch_assoc($result)) {
	echo '<tr ';
		if ($phone['status']=='pendingPayment') { echo 'class="info"';}
		if ($phone['status']=='awaitingBill') {echo 'class="info"';}
		if ($phone['status']=='billSubmitted') { echo 'class="warning"';}
		if ($phone['status']=='billError') { echo 'class="error"';}

	echo '>
	
	<td>';
		if ($phone['status']=='pendingPayment') { echo 'Awaiting Payment';}
		if ($phone['status']=='awaitingBill') { echo 'Awaiting Bill';}
		if ($phone['status']=='billSubmitted') { echo 'Tranferring Number';}
		if ($phone['status']=='billError') { echo 'Bill Rejected';}
		if ($phone['status']=='active') { echo 'Active';}
	echo '</td>
	<td>'.$phone['fromLine'].'</td>
	<td>'.$phone['OutLine1'];
	if (!empty($phone['OutLine2'])) { echo '<br />'.$phone['OutLine2'];}
	if (!empty($phone['OutLine3'])) { echo '<br />'.$phone['OutLine3'];}
	echo '
	</td>';
	if ($phone['status']=='pendingPayment') { echo '<td colspan=2><a class="btn btn-info" href="/billing.php?line='.$phone['id'].'">Pay Now </a></td>';}
	elseif ($phone['status']=='awaitingBill') {echo '<td colspan=2><a class="btn btn-info" href="/submitBill.php">Verify your Current Phone Bill</a></td>';}
	else { echo '
	<td><a href="/manage.php?line='.$phone['id'].'><i class="icon-cog"></i></a></td>
	<td><a href="/cancel.php?line='.$phone['id'].'>x</a></td>
	';
	}
	echo '</tr>';
} // endloop through rows
	

		

echo '
</tbody>
</table></div></div>';

stab();

?>