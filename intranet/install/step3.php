<?php
	if(!isset($dbhost)) $dbhost = "localhost";
	if(!isset($dbuname)) $dbuname = "";
	if(!isset($dbpass)) $dbpass = "";
	if(!isset($dbname)) $dbname = "";
	if(!isset($dbprefix)) $dbprefix = "z";
?>

<h2><?php echo _DATABASE?></h2>
<p><?php echo _REQUIREDINFORMATION?></p>
<p><em><?php echo _ENOUGHPERMS?></em></p>
<form action="index.php" method="POST">
	<input name="step" id="step" value="4" type="hidden">
	<input name="lang" id="lang" value="<?php echo $lang?>" type="hidden">
	<table>
		<tr><td><?php echo _DBHOST?></td><td><input name="dbhost" value="<?php echo $dbhost?>"></td></tr>
		<tr><td><?php echo _DBUSER?></td><td><input name="dbuname" value="<?php echo $dbuname?>"></td></tr>
		<tr><td><?php echo _DBPASS?></td><td><input name="dbpass" value="<?php echo $dbpass?>" type="password"></td></tr>
		<tr><td><?php echo _DBNAME?></td><td><input name="dbname" value="<?php echo $dbname?>"></td></tr>    
		<tr><td><?php echo _DBTABLESPREFIX?></td><td><input name="dbprefix" value="<?php echo $dbprefix?>"></td></tr>
		<tr><td></td><td><input type="submit" value="<?php echo _GOON?>"></td></tr>
	</table>
</form>
