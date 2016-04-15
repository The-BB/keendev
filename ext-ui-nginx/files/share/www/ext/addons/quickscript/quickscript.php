<?php
shell_exec('/opt/etc/quickscript.sh');
header('Location: ' . $_SERVER['HTTP_REFERER']);
?>
