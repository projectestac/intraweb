<h2><?php echo _REQUIREMENTS?></h2>
<p><?php echo _PHPREQUIREMENTS?></p>
<ul>
	<li><em><?php if(function_exists('token_get_all')){?><img src="images/ok_small.gif" /><?php }?> <?php echo _PHPTOKENAVAILABLE?></em></li>
	<li><em><?php if(function_exists('mb_get_info')){?><img src="images/ok_small.gif" /><?php }?> <?php echo _PHPMULTISTRINGAVAILABLE?></em></li>
	<li><em><?php if(phpversion() > 5){?><img src="images/ok_small.gif" /><?php }?> <?php echo _PHPVERSIONUPPER50?></em></li>
</ul>
<h2><?php echo _DIRSANDFILESPERMS?></h2>
<p><?php echo _DIRSANDFILESLIST?></p>
<ul>
	<li><em><?php echo get_install_path()?>pnTemp/</em></li>
	<li><em><?php echo get_install_path()?>config/config.php</em></li>
	<li><em><?php echo get_install_path()?>zkdata/</em></li>
</ul>
<?php
echo '<p><center><a href="index.php?step='.($step+1).'&lang='.$lang.'"><img src="images/next.png" width="24px" height="24px">'._GOON.'</a></center></p>';
?>
