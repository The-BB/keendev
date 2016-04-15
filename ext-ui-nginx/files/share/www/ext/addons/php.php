<?php
$title='Modules PHP on Zyxel Keenetic';
$header='Расширения PHP';
$serveradr=$_SERVER["SERVER_ADDR"];
include('header.php');
$dirphp = "/opt/etc/php5/";
if ($_GET["act"] != '')
{
	if ($_GET["act"] == 'enable')
	{
		shell_exec("mv ".$dirphp.$_GET["mod"]." ".$dirphp.str_replace('.disabled', '', $_GET["mod"]));
	}
	if ($_GET["act"] == 'disable')
	{
		shell_exec("mv ".$dirphp.$_GET["mod"]." ".$dirphp.$_GET["mod"].".disabled");
	}
unset($_GET["act"]);
unset($_GET["mod"]);
}
echo "<table class='status'><thead><tr><th class='left' colspan='3'>Установленные модули PHP</th></tr>";
if (is_dir($dirphp)) {
	if ($dhphp = opendir($dirphp)) {
		while (($filephp = readdir($dhphp)) !== false) {
			$allphp = array(
				"..",
				"."
					);
			if(!in_array($filephp, $allphp))
			{
				$phpmod = str_replace('.ini.disabled', '', $filephp);
				if (strstr($filephp, 'disabled'))
				{
					$img = '<img src="img/p-off.png">';
					$tabphpmod = '<b>'.$phpmod.'</b>';
					$phpchange = "<a href='../addons/php.php?mod=".$filephp."&act=enable'>Подключить</a>";
				}
				else
				{
					$img = '<img src="img/p-on.png">';
					$tabphpmod = $dirphp.$phpmod;
					$phpchange = "<a href='../addons/php.php?mod=".$filephp."&act=disable'>Отключить</a>";
				}
				if ($class == '') {$class = 'even'; }
				echo "<tr class='".$class."'><td class='value' width='18px'>".$img."</td><td class='name'>".$tabphpmod."</td><td class='value'>".$phpchange."</td></tr>";
				if ($class == 'even') { $class = 'odd'; } else { $class = 'even'; }
			}
		}
		closedir($dhphp);
	}
}
echo "<th class='footer' colspan='3'> Версия PHP: ".phpversion()."</th>";
echo "</table>";
echo "<table class='status'><thead><tr class='even'><td class='footer'>
<a href='http://www.php.net/'><img src='./img/logo_php.png' alt='PHP Logo' border='0'></a>
</td></tr>";
include('footer.php');
unset($dirphp);
unset($class);
unset($i);
unset($phpchange);
unset($tabphpmod);
unset($modstatus);
unset($serveradr);
unset($filephp);
unset($dhphp);
?>
