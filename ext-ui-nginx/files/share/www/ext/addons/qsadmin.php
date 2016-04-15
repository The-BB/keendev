<?php
$title='QuickScript Editor';
$header='QuickScript Editor';
$descript='QuickScript - это кнопка, расположенная на главном экране панели управления роутера ZyXEL Keenetic. Данная функция предназначена для назначения своих скриптов, которые будут исполняться при нажатии этой кнопки. На этой странице вы можете редактировать эти скрипты Перед тестированием или запуском скрипта необходимо нажать на кнопку "Сохранить скрипт". Также вы можете редактировать скрипт QuickScript вашим любимым редактором - путь к скрипту - /opt/etc/quickscript.sh.';
$serveradr=$_SERVER["SERVER_ADDR"];
include('header.php');
if ($_POST) {
  file_put_contents("/opt/etc/quickscript.sh",$_POST['text']);
  echo('<script>location.reload();</script>');
  exit;
}
$text = htmlspecialchars(file_get_contents("/opt/etc/quickscript.sh"));
?>
<script type="text/javascript">

for (var i = 0; i < document.links.length; i++)
{
    var a = document.links[i];
    if (a.port == '88' && a.hostname != document.location.hostname)
    {
        a.hostname = document.location.hostname;
    }
}

</script>
<form method="POST">
<textarea name="text" cols='94' rows='30'><?=$text?></textarea>
<br><br>
<input type="submit" value="Сохранить скрипт">
<input type="button" value="Выполнить скрипт" onclick="location.href='../addons/quickscript/quickscript.php';"/>
<input type="button" value="Выполнить скрипт с отладкой" onclick="location.href='../addons/quickscript/debug.php';"/>
</form>
