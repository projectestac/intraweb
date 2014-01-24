</div>
<div id="sidebar">
<h4><?php echo _MENU_PROCEDURE?></h4>
	<div id="navcontainer_1" class="navcontainer">
		<ul class="navlist">
			<?php
			if(!isset($step)){$step = 0;}
			
			$titles = array(
				_MENU_PRESENTATION,
				_MENU_REQUIREMENTS,
				_MENU_DBCREATION,
				_MENU_ADMINUSER,
				_MENU_ENDED
			);

			foreach($titles as $pas => $title){
				$pas++;
				
				if($step > $pas)
				  echo '<li class="ok"><a href="index.php?step='.$pas.'&back=1&lang='.$lang.'" title="'.$title.'">'.$title.'</a></li>';
				else if ($step == $pas)
				  echo '<li><b><a href="index.php?step='.$pas.'&back=1&lang='.$lang.'" title="'.$title.'">'.$title.'</a></b></li>';
				else
				  echo '<li>'.$title.'</li>';
			}
				
			?>
		</ul>

	</div>	
</div>
