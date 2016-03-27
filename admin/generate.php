<? include("../inc/header2.php"); ?>

<div class="form" align="center">
	<table width="100%" border="0" align="center">
		<tr>
			<td align="center"><b>Resnet Administrative Request Form</b>
				<hr width="60%"></td>
		</tr>
	</table>
	<br>
	<table width="80%" border="0" align="center">
		<tr>
			<form enctype="multipart/form-data" action="request.php" method="post">
			<td align="center" valign="top">
				Username to generate ticket for:&nbsp;<input type="text" name="passeduname" value="" maxlength="7" size="20">
			</td>
			<td align="center">
					<input type="submit" name="submit" value="Begin Generating Ticket">
				</form>
			</td>
		</tr>
	</table>
	
</div>
</body>
</html>
