<?php
$title='SSH client on Zyxel Keenetic';
$header='Терминал';
include('header.php');
// User requested Mindterm Java SSH applet
$username = "root";
$passwd = "";
$port = "22";
?>
<applet code=com.mindbright.application.MindTerm.class ARCHIVE="terminal/mindterm312.jar" width=800 height=590>
	<param name="cabinets" VALUE="mindterm.cab">
	<param name="sepframe" value="false">
	<param name="debug" value="true">
	<param name="protocol" value="ssh2">
	<param name="cipher" value="blowfish">
	<param name="te" value="xterm-color">
	<param name="port" value="<?php echo $port; ?>">
	<param name="username" value="<?php echo $username; ?>">
	<param name="auth-method" value="password">
	<param name="password" value="<?php echo $passwd; ?>">
	<param name="menus" value="yes">
	<param name="auth-method" value="password">
	<param name="autorun" value="true">
	<param name="allow-new-server" value="no">
	<param name="quiet" value="true">
	<param name="font-size" value="14">
	<param name="term-type" value="xterm-color">
	<param name="encoding" value="UTF-8">
	<param name="copy-select" value="true">
	<param name="exit-on-logout" value="true">
	<param name="force-pty" value="true">
	<param name="backspace-send" value="DEL">
	<param name="bg-color" value="0, 0, 0">
	<param name="fg-color" value="0, 255, 0">
	<param name="cursor-color" value="0, 255, 0">
	<param name="geometry" value="97x32">
	<param name="font-name" value="Monospaced">
	<param name="font-size" value="14">
</applet>
<?php
include('footer.php');
?>