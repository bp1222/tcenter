<html>
<body>
<H1>Barcode Creation</H1>
<table border=0 cellspacing=2 cellpadding=2>
<form action="barcode.php" method=get>
<tr>
    <td>Code:</td>
    <td><input type=text name=code value="" maxlength=100></td>
</tr>
<tr>
    <td>Size:</td>
    <td><select name=scale>

	<option selected>1
	<option >2
	<option >3
	<option >4
	<option >5
	<option >6
	<option >7
	</select>
    </td>
</tr>
<tr>
    <td>Encoding:</td>
    <td><select name=bar>

	<option value=ANY selected>Best fit
	<option value=EAN >EAN
	<option value=UPC >UPC (12 Digit EAN)
	<option value=ISBN >ISBN (is EAN13)
	<option value=39 >Code 39
	<option value=128 >Code 128 (a,b,c: autoselect)
	<option value=128B >Code 128B
	<option value=128C >Code 128D
	<option value=I25 >I25
	<option value=128RAW >128RAW
	<option value=CBR >CBR
	<option value=MSI >MSI
	<option value=PLS >PLS
	<option value=93 >93
	</select>
    </td>

</tr>
<tr>
    <td>&nbsp;</td>
    <td>Hint: The most used barcode you can see on every supermarket-items is EAN (-13)
   </td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td><input type=submit value="Create-Bars"></td>
</tr>
</FORM>
</table>
</body>
</html>
