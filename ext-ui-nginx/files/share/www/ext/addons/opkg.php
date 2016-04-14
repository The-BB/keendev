<?php
$title='Manage packages on Zyxel Keenetic v2';
$header='Управление пакетами Zyxel Keenetic';
$serveradr=$_SERVER["SERVER_ADDR"];
include('header.php');

echo "<table class='status'><thead>";
echo "<tr><th class='footer' colspan='4'><form method='post' action='../addons/opkg.php'>
<input type='submit' name='filter' value='Фильтр'>
<input type='text' name='filter_value' value=''>
<input type='submit' name='installed' value='Установленные'>
<input type='submit' name='all' value='Все'>
<input type='submit' name='update' value='Проверить обновления'>
</form></th></tr>";

if ($_POST['filter'] == 'Фильтр')
{
	$opkg_list = shell_exec('opkg list | grep '.$_POST['filter_value']);
	$opkg_install = shell_exec('opkg list-installed');
	//$cont_install = explode("\n", $opkg_install);
	$cont_install = trim($opkg_install);
	$content = explode("\n", $opkg_list);
	$list = count($content);

	for ($i = 0; $i <= $list; $i++)
	{
		$pack = substr($content[$i], 0, strpos($content[$i], ' - '));
		$prevers = substr($content[$i], stripos($content[$i], " - ")+3);
		$version = substr($prevers, 0, strpos($prevers, ' - '));
		$desc = substr($prevers, stripos($prevers, " - ")+3);
		if ($pack != '')
		{
			if (strstr($cont_install, $pack.' - '.$version))
			{
				$link = '<a href="../addons/opkg.php?act=remove&target='.$pack.'#'.$pack.'" title="Удалить пакет"><img src="img/p-on.png"></a>';
			}
			else
			{
				$link = '<a href="../addons/opkg.php?act=install&target='.$pack.'#'.$pack.'" title="Установить пакет"><img src="img/p-off.png"></a>';
			}
			if ($class == '') {$class = 'even'; }
			echo "<tr class=".$class.">
			<td class='value' width='18px'>".$link."</td>
			<td class='value'><a name='".$pack."'>".$pack."</a></td>
			<td class='value'>".$version."</td>
			<td class='value'>".$desc."</td>
			</tr>";
			if ($class == 'even') { $class = 'odd'; } else { $class = 'even'; }
		}
	}
	$stop = 'stop';
}

if ($_POST['installed'] == 'Установленные')
{
	$opkg_list = shell_exec('opkg list-installed');
	$content = explode("\n", $opkg_list);
	$list = count($content);

	for ($i = 0; $i <= $list; $i++)
	{
		$pack = substr($content[$i], 0, strpos($content[$i], ' - '));
		$version = substr($content[$i], stripos($content[$i], " - ")+3);
		$desc = substr($prevers, stripos($prevers, " - ")+3);
		if ($pack != '')
		{
			$link = '<a href="../addons/opkg.php?act=remove&target='.$pack.'#'.$pack.'" title="Удалить пакет"><img src="img/p-on.png"></a>';
			if ($class == '') {$class = 'even'; }
			echo "<tr class=".$class.">
			<td class='value' width='18px'>".$link."</td>
			<td class='value'><a name='".$pack."'>".$pack."</a></td>
			<td class='value'>".$version."</td>
			<td class='value'>".$desc."</td>
			</tr>";
			if ($class == 'even') { $class = 'odd'; } else { $class = 'even'; }
		}
	}
	$stop = 'stop';
}

if ($_POST['update'] == 'Проверить обновления')
{
	$opkg_check = shell_exec('opkg update');
	$opkg_list = shell_exec('opkg list-upgradable');
	$opkg_install = shell_exec('opkg list-installed');
	$cont_install = trim($opkg_install);
	$content = explode("\n", $opkg_list);
	$list = count($content);
	for ($i = 0; $i <= $list; $i++)
	{
		$pack = substr($content[$i], 0, strpos($content[$i], ' - '));
		$prevers = substr($content[$i], stripos($content[$i], " - ")+3);
		$version = substr($prevers, 0, strpos($prevers, ' - '));
		$desc = substr($prevers, stripos($prevers, " - ")+3);
		if ($pack != '')
		{
//			if (strstr($cont_install, $pack.' - '.$version))
//			{
				$link = '<a href="../addons/opkg.php?act=upgrade&target='.$pack.'#'.$pack.'" title="Обновить пакет"><img src="img/upd.png"></a>';
//			}
//			else
//			{
//				$link = '<a href="../addons/opkg.php?act=install&target='.$pack.'#'.$pack.'" title="Установить пакет"><img src="img/p-off.png"></a>';
//			}
			if ($class == '') {$class = 'even'; }
			echo "<tr class=".$class.">
			<td class='value' width='18px'>".$link."</td>
			<td class='value'><a name='".$pack."'>".$pack."</a></td>
			<td class='value'>".$version."</td>
			<td class='value'>".$desc."</td>
			</tr>";
			$upgrade = "true";
			if ($class == 'even') { $class = 'odd'; } else { $class = 'even'; }
		}
	}
	if ($upgrade == 'true')
	{
		$noupgrade = "<tr><th class='footer' colspan='4'><form method='post' action='../addons/opkg.php'>
<input type='submit' name='upgrade_packs' value='Обновить пакеты'>
</form></th></tr>";
	}
	else
	{
		$noupgrade = "<tr class='even'><th colspan='4'>На данный момент нет доступных обновлений для установленных пакетов.</td></tr>";
	} 
	echo $noupgrade;
	echo "</table>";
	$stop = 'stop';
}

if ($_GET['act'] == 'install')
{
	$install = shell_exec('opkg install '.$_GET['target']);
}

if ($_GET['act'] == 'remove')
{
	$install = shell_exec('opkg remove '.$_GET['target']);
}

if ($_GET['act'] == 'upgrade')
{
	$install = shell_exec('opkg upgrade '.$_GET['target']);
}

if ($_POST['upgrade_packs'] == 'Обновить пакеты')
{
	$upgrade = shell_exec('opkg upgrade');
	echo "<tr><tr class='even'><td class='value'>Установленные пакеты обновлены.</td></tr>";
}

if ($stop == '')
{
	$opkg_list = shell_exec('opkg list');
	$opkg_install = shell_exec('opkg list-installed');
	$cont_install = trim($opkg_install);
	$content = explode("\n", $opkg_list);
	$list = count($content);

	for ($i = 0; $i <= $list; $i++)
	{
		$pack = substr($content[$i], 0, strpos($content[$i], ' - '));
		$prevers = substr($content[$i], stripos($content[$i], " - ")+3);
		$version = substr($prevers, 0, strpos($prevers, ' - '));
		$desc = substr($prevers, stripos($prevers, " - ")+3);
		if ($pack != '')
		{
			if (strstr($cont_install, $pack.' - '.$version))
			{
				$link = '<a href="../addons/opkg.php?act=remove&target='.$pack.'#'.$pack.'" title="Удалить пакет"><img src="img/p-on.png"></a>';
			}
			else
			{
				$link = '<a href="../addons/opkg.php?act=install&target='.$pack.'#'.$pack.'" title="Установить пакет"><img src="img/p-off.png"></a>';
			}
			if ($class == '') {$class = 'even'; }
			echo "<tr class=".$class.">
			<td class='value' width='18px'>".$link."</td>
			<td class='value'><a name='".$pack."'>".$pack."</a></td>
			<td class='value'>".$version."</td>
			<td class='value'>".$desc."</td>
			</tr>";
			if ($class == 'even') { $class = 'odd'; } else { $class = 'even'; }
		}
	}
}

echo "</table>";
include('footer.php');

unset($title);
unset($header);
unset($serveradr);
unset($link);
unset($class);
unset($desc);
unset($version);
unset($prevers);
unset($pack);
unset($content);
unset($cont_install);
unset($opkg_install);
unset($opkg_list);
unset($noupgrade);
unset($opkg_check);
?>
