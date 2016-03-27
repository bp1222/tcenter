<? include("inc/connection.inc"); ?>
<? include("inc/header.php"); ?>

<?
		$username = $PHP_AUTH_USER;

?>

<div class="form" align="center">
	<table width="100%" border="0" align="center">
		<tr>
			<td align="center"><b>ITS RESNET TECHNICAL SUPPORT CUSTOMER PORTAL</b>
				<hr width="60%"></td>
		</tr>
	</table>
	<br>
	<table width="80%" border="0" align="center">
		<tr>
			<td align="center">
				<b>Welcome. What Would You Like To Do?</b>
				<br><br>
			</td>
		</tr>
		<tr>
			<td align="center">
				<a href="waiver.php">Request Assistance From Resnet</a>
			</td>
		</tr>
		<tr>
			<td align="center">
				<a href="checkmyrequests.php">Check My Existing Requests</a>
			</td>
		</tr>
	</table>
	
</div>
</body>
</html>
