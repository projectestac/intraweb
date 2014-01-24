<?php
	if(!isset($admpass)) $admpass = "";
	if(!isset($admpass2)) $admpass2 = "";
	if(!isset($admmail)) $admmail = "";

echo _STEP4;
?>
<form action="index.php" method="POST">
	<input name="step" id="step" value="5" type="hidden">
	<input name="lang" id="lang" value="<?php echo $lang?>" type="hidden">
	<table>
		<tr><td><?php echo _ADMUSER?></td><td><b>admin</b></td></tr>
		<tr><td><?php echo _ADMPASS?></td><td><input name="admpass" value="<?php echo $admpass?>" type="password"></td></tr>
		<tr><td><?php echo _ADMPASSREPEAT?></td><td><input name="admpass2" value="<?php echo $admpass2?>" type="password"></td></tr>
		<tr><td><?php echo _ADMMAIL?></td><td><input name="admmail" value="<?php echo $admmail?>"></td></tr>
		<tr><td></td><td><input type="submit" value="<?php echo _GOON?>"></td></tr>
	</table>
</form>
