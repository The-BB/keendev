<?php
$title='Firmvares for Zyxel Keenetic NDSMv2';
$header='Неофициальные прошивки NDMSv2 для Zyxel Keenetic';
$serveradr=$_SERVER["SERVER_ADDR"];
include('header.php');
$filendms='./cache/ndms.php';
$fileopkg='./cache/opkg.php';
//
$fwversion = shell_exec('ndmq -p "show version" -P release');
$fwversion = str_replace('FIRMWARE_VERSION="', '', $fwversion);
$fwversion = str_replace('"', '', $fwversion);
$coreversion = shell_exec("uname -r");
$device = shell_exec('ndmq -p "show version" -P device');
//$device = str_replace(' ', '_', $device);
echo "<table class='status'><thead><tr><th class='check' colspan='1'>Доступные неофициальные прошивки NDMSv2 | Установленная версия: ".$fwversion."</th></tr>";
echo "<tr><th class='footer' colspan='6'> Модель: ".$device." | Версия ядра: ".$coreversion."</th></tr>";
echo "<tr class='even'><td class='value'>".$contndms."</td></tr>";
echo "<tr class='odd'><td class='value'>".$contsvn."</td></tr>";
echo "<th class='footer' colspan='1'><form method='post' action='../addons/firmware.php?firmware=check'>
<input type='submit' name='submit' value='Проверить'>
</form></th>";
echo "</table>";
//

if ($_GET["firmware"] == 'check')
{
//page of firmware devices
$firmwares = file_get_contents('http://files.keenopt.ru/firmware/');
$firmwares = str_replace('<h2>Index of /firmware/</h2>', '<h2>FIRMWARES</h2>', $firmwares);
$firmwares = str_replace('<link rel="icon" type="image/x-icon" href="/.hidden/favicon.ico" />', '<link rel=\"icon\" type=\"image/x-icon\" href=\"/.hidden/favicon.ico\" />', $firmwares);
$firmwares = str_replace('<link rel="shortcut icon" href="/.hidden/favicon.png" type="image/png" />', '<link rel=\"shortcut icon\" href=\"/.hidden/favicon.png\" type=\"image/png\" />', $firmwares);
$firmwares = str_replace('<table summary=\'Directory Listing\' cellpadding=\'0\' cellspacing=\'0\'><thead><tr><th class=\'n\'><a href=\'?sort=name&order=desc\'>Name</a></th><th class=\'n\'><a href=\'?sort=modtime\'>Last Modified</a></th><th class=\'n\'><a href=\'?sort=size\'>Size</a></th><th class=\'n\'><a href=\'?sort=file_type\'>Type</a></th></tr></thead><tbody><tr><td class=\'n\'><a href=\'..\'>Parent Directory</a>/</td><td class=\'m\'>&nbsp;</td><td class=\'s\'>&nbsp;</td><td class=\'t\'>Directory</td></tr>', '<table summary=\'Directory Listing\' cellpadding=\'0\' cellspacing=\'0\'><thead><tr><th class=\'n\'><a href=http://files.keenopt.ru/firmware/?sort=name&order=desc>Name</a></th><th class=\'n\'><a href=http://files.keenopt.ru/firmware/?sort=modtime>Last Modified</a></th><th class=\'n\'><a href=http://files.keenopt.ru/firmware/?sort=size>Size</a></th><th class=\'n\'><a href=http://files.keenopt.ru/firmware/?sort=file_type>Type</a></th></tr></thead><tbody><tr><td class=\'n\'><a href=http://files.keenopt.ru/firmware/../>Parent Directory</a>/</td><td class=\'m\'>&nbsp;</td><td class=\'s\'>&nbsp;</td><td class=\'t\'>Directory</td></tr>', $firmwares);
$firmwares = str_replace('<a href=\'', '<a href=\'http://files.keenopt.ru/firmware/', $firmwares);

$fp = fopen($filendms, 'w+');
$write = fwrite($fp, "<?php { echo \"".$firmwares."\" ; } ?>");
fclose($fp);

//page of OPKG
$opkg = file_get_contents('http://opkg.keenopt.ru/' );
$opkg = str_replace('<h2>Index of /</h2>', '<h2>OPKG</h2>', $opkg);
$opkg = str_replace('<link rel="icon" type="image/x-icon" href="/.hidden/favicon.ico" />', '<link rel=\"icon\" type=\"image/x-icon\" href=\"/.hidden/favicon.ico\" />', $opkg);
$opkg = str_replace('<link rel="shortcut icon" href="/.hidden/favicon.png" type="image/png" />', '<link rel=\"shortcut icon\" href=\"/.hidden/favicon.png\" type=\"image/png\" />', $opkg);
$opkg = str_replace('<a href=\'', '<a href=\'http://opkg.keenopt.ru/', $opkg);

$fp = fopen($fileopkg, 'w+');
$write = fwrite($fp, "<?php { echo \"".$opkg."\" ;} ?>");
fclose($fp);
}
//
if (is_file($filendms))
{
	include($filendms);
}
else
{
	$contndms = 'Нет информации о доступных прошивках. Нажмите "Проверить", чтобы получить актуальные данные.';
}
if (is_file($fileopkg))
{
	include($fileopkg);
}
else
{
	$contopkg = '';
}
//
//
//web
echo "<table class='status'><thead><tr><th class='check' colspan='1'>WEB</th></tr>;
<tr class='odd'><td class='value'><a href='http://keenetic.zyxmon.org/wiki/doku.php'>Wiki по системе пакетов opkg для ZyXEL Keenetic</a></td></tr>
<tr class='odd'><td class='value'><a href='http://forum.zyxmon.org/forum6-marshrutizatory-zyxel-keenetic.html'>Форум поддержки opkg для ZyXEL Keenetic (old version)</a></td></tr>
<tr class='odd'><td class='value'><a href='http://forums.zyxmon.org'>Форум поддержки opkg для ZyXEL Keenetic (new version)</a></td></tr>
<tr class='odd'><td class='value'><a href='http://keenopt.ru'>Форум поддержки keenOPT для ZyXEL Keenetic</a></td></tr>
<tr class='odd'><td class='value'><a href='http://files.keenopt.ru/firmware/'>Firmware альфабета</a></td></tr>
</table>";
//
include('footer.php');
unset($serveradr);
unset($filendms);
unset($fileopkg);
unset($firmwares);
unset($opkg);
unset($fp);
unset($fwrite);
unset($fwversion);
unset($coreversion);
unset($contndms);
unset($contsvn);
unset($device);
?>
