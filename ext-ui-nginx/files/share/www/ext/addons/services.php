<?php
$title='Services on Zyxel Keenetic';
$header='Управление службами Zyxel Keenetic';
$serveradr=$_SERVER["SERVER_ADDR"];
include('header.php');
$dir = "/opt/etc/init.d/";
$backdir  = "/opt/share/www/ext/addons/cache/ibakup/";
if ($_GET["task"] != '')
{
	shell_exec($dir.$_GET["srv"]." ".$_GET["task"]);
}
if ($_GET["restart"] != '')
{
	shell_exec($dir.$_GET["srv"]." stop");
	shell_exec($dir.$_GET["srv"]." start");
}
if ($_GET["auto"] != '')
{
	shell_exec("mv ".$dir.$_GET["srv"]." ".$dir.$_GET["auto"].substr($_GET["srv"],1));
}
echo "<table class='status'><thead><tr><th class='left' colspan='2'>Службы</th><th class='left' colspan='3'>Состояние и управление</th><th class='left' colspan='2'>Автозапуск</th></tr>";
$checkserv = shell_exec("ps");
if (is_dir($dir)) {
	if ($dh = opendir($dir)) {
		while (($file = readdir($dh)) !== false) {
			$allserv = array(
				"..",
				".",
				"S30php-fcgi",
				"S80nginx",
				"rc.func",
				"rc.unslung",
				);
			if(!in_array($file, $allserv))
			{
				$service = substr($file,3);
				if ($service == 'transmissiond')
				{
					$service = 'transmission-daemon';
				}
				if ($service == 'samba')
				{
					$service = 'smbd';
				}
				if (strstr($checkserv, $service))
				{
					$img='<img src="img/p-on.png">';
					$status = 'работает';
					$do = 'stop';
					$dolang = 'выкл.';
					//$tabservice = $dir.$service;
				}
				else
				{
					$img='<img src="img/p-off.png">';
					$status = 'не запущен</img>';
					$do = 'start';
					$dolang = 'вкл.';
				}
				$autostart = substr($file,0,1);
				if ($autostart == "S")
				{
					$autostatus = "автоматически";
					$init = 'K';
				}
				else
				{
					$autostatus = "вручную";
					$init = 'S';
				}
				$change = "<a href='../addons/services.php?srv=".$file."&auto=".$init."'>изменить</a>";
				$tabservice = '<a href="../addons/services.php?file='.$file.'">'.$file.'</a>';
				if ($class == '') {$class = 'even'; }
				echo "<tr class='".$class."'>
				<td class='value' width='20px'>".$img."</td>
				<td class='value'>".$tabservice."</td>
				<td class='value'>".$status."</td>
				<td class='value'><a href='../addons/services.php?srv=".$file."&restart=restart'>перезапустить</a></td>
				<td class='value'><a href='../addons/services.php?srv=".$file."&task=".$do."'>".$dolang."</a></td>
				<td class='value'>".$autostatus."</td>
				<td class='value'>".$change."</td>
				</tr>";
				if ($class == 'even') { $class = 'odd'; } else { $class = 'even'; }
			}
			else
			{
				if ($file != "." AND $file != "..")
				{
					$service = substr($file,3);
					if (strstr($checkserv, '/'.$service))
					{
						$img='<img src="img/p-on.png">';
						$status = '-';
						$do = 'stop';
						$dolang = 'Выкл.';
						//$tabservice = $dir.$service;
					}
					else
					{
						$img='<img src="img/p-on.png">';
						$status = '-';
						$do = 'start';
						$dolang = 'Вкл.';
						$tabservice = '<a href="../addons/services.php?file='.$file.'">'.$file.'</a>';
					}
					$autostart = substr($file,0,1);
					if ($autostart == "S")
					{
						$autostatus = "автоматически";
					}
					else
					{
						$autostatus = "вручную";
					}
					$change = "<a href='../addons/services.php?srv=".$file."&auto=S'>изменить</a>";
					$tabservice = '<a href="../addons/services.php?file='.$file.'">'.$file.'</a>';
					if ($class == '') {$class = 'even'; }
					echo "<tr class='".$class."'>
					<td class='value' width='20px'>".$img."</td>
					<td class='value'>".$tabservice."</td>
					<td class='value'>".$status."</td>
					<td class='value'>-</td>
					<td class='value'>-</td>
					<td class='value'>".$autostatus."</td>
					<td class='value'>-</td>
					</tr>";
				if ($class == 'even') { $class = 'odd'; } else { $class = 'even'; }
				}
			}
		}
		closedir($dh);
	}
}
echo "</table>";
echo "<table class='status'>
<tr class='even'><td class='value'>Редактор скриптов автозапуска. Нажмите на нужную службу, чтобы начать редактировать.</td></tr>
</table>";
if ($_GET['file'] != '')
{
	$filedit = shell_exec('cat '.$dir.$_GET['file']);
}
if ($_POST['srvsave'] != '')
{
	$srvfile = $dir.$_POST['srvsave'];
	$fp = fopen($srvfile, 'w+');
	$write = fwrite($fp, $_POST['srvedit']);
	fclose($fp);
	$backup = shell_exec('mv '.$srvfile.' '.$backdir);
	$linuxfile = shell_exec("cat ".$backdir.$_POST['srvsave']." | sed -ne 's/\r//;print' > ".$dir.$_POST['srvsave']);
	$chmod = shell_exec('chmod +x '.$dir.$_POST['srvsave']);
}
echo "<form method='post' action='../addons/services.php'>";
echo "<textarea name='srvedit' cols='95' rows='25'>".$filedit."</textarea>";
echo "<p></p><input type='hidden' name='srvsave' value='".$_GET['file']."'><input type='submit' name='submit' value='Сохранить'>";
echo "</form>";
include('footer.php');
?>
