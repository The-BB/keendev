<?php
$httpd = '/opt/etc/lighttpd/';
$psswdsv = './passwd/.htpasswd';
  if ($_POST["create"] == 'Включить защиту')
	{

	$passwd = shell_exec("cat /opt/etc/passwd | grep -o '.*:0' | cut -d: -f2");
	$marker = stripos($passwd, "ADMIN_PASSWORD=");

	//echo $passwd;
	$filen = fopen($psswdsv, 'w+');
	fwrite($filen, 'root:'.$passwd);
	fclose($filen);

	if ($_POST['server'] == 'lighttpd')
	{
		$memdel = shell_exec("mv ".$httpd."lighttpd.conf ".$httpd."lighttpd.conf_back");
		$confcp = shell_exec("cp ./passwd/on.conf ".$httpd."lighttpd.conf");
	}
}
if ($_POST["create"] == 'Выключить защиту')
{
	unlink($psswdsv);
	if ($_POST['server'] == 'lighttpd')
	{
		$memdel = shell_exec("mv ".$httpd."lighttpd.conf ".$httpd."lighttpd.conf_back");
		$confcp = shell_exec("cp ./passwd/off.conf ".$httpd."lighttpd.conf");
	}
}

$title='Security';
$header='Дополнительная защита админцентра';
$descript='Сразу после установки данного дополнения, входящая в него часть админцентра ни чем не защищена. Доступ может осуществить любой, обратившийся по адресу роутера на 88 порт. Чтобы этого избежать, необходимо включить дополнительную защиту, в результате чего доступ ко всему админцентру будет закрыт единым паролем.';
$serveradr=$_SERVER["SERVER_ADDR"];
include('header.php');

?>
<form method='post' action='../addons/passw.php'>
<table class="sublayout">
<tr>
<td class='submit'>
<?php
if (!is_file($psswdsv))
{
	echo "<font color='red'><b>Дополнительная защита выключена!</b></font>";
	$onoff = "Включить защиту";
}
else
{
	echo "<font color='green'><b>Дополнительная защита паролем включена!</b></font>";
	$onoff = "Выключить защиту";
}
?>
</td>
<td class='submit'>
<select name='server'>
    <option selected value='lighttpd'>lighttpd</option>
</select> 
</td>
<td class='submit' width='10px'>
<input type='hidden' id='ADMIN_NAME' name='ADMIN_NAME' value='root'/>
<input type="submit" name="create" value="<?php echo $onoff; ?>"/>
</td>
</tr>
</table>
</form>
<?php
include('footer.php');
?>
