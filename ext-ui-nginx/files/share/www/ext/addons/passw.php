<?php
$httpd = '/opt/etc/nginx/';
$psswdsv = './passwd/.htpasswd';
if ($_POST["create"] == 'Включить защиту')
{
	$passwd = shell_exec("cat /opt/etc/passwd | grep -o '.*:0' | cut -d: -f2");
	$marker = stripos($passwd, "ADMIN_PASSWORD=");

	//echo $passwd;
	$filen = fopen($psswdsv, 'w+');
	fwrite($filen, 'root:'.$passwd);
	fclose($filen);

	if ($_POST['server'] == 'nginx')
	{
		$nginxfile = $httpd.'nginx.conf';
		$passwon = file_get_contents($nginxfile);
		$passwon = str_replace('/passwd/nopasswd.conf', '/passwd/passwd.conf', $passwon);
		file_put_contents($nginxfile, $passwon);
		$restart = shell_exec("/opt/sbin/nginx -s reload");
	}
}
if ($_POST["create"] == 'Выключить защиту')
{
	unlink($psswdsv);
	if ($_POST['server'] == 'nginx')
	{
		$nginxfile = $httpd.'nginx.conf';
		$passwoff = file_get_contents($nginxfile);
		$passwoff = str_replace('/passwd/passwd.conf', '/passwd/nopasswd.conf', $passwoff);
		file_put_contents($nginxfile, $passwoff);
		$restart = shell_exec("/opt/sbin/nginx -s reload");
	}
}

$title='Security';
$header='Дополнительная защита админцентра';
$descript='Сразу после установки данного дополнения, входящая в него часть админцентра ни чем не защищена. Доступ может осуществить любой, обратившийся по адресу роутера на 88 порт. Чтобы этого избежать, необходимо включить дополнительную защиту, в результате чего доступ ко всему админцентру будет закрыт единым паролем.<br><br>Нажмите "Включить защиту", чтобы применить свой текущий пароль на весь админцентр.';
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
    <option selected value='nginx'>nginx</option>
</select> 
</td>
<td class='submit' width='130px'>
<input type='hidden' id='ADMIN_NAME' name='ADMIN_NAME' value='root'/>
<input type="submit" name="create" value="<?php echo $onoff; ?>"/>
</td>
</tr>
</table>
</form>
<?php
include('footer.php');
?>