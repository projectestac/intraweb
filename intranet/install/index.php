<?php


global $lang;

$lang = (isset($_REQUEST["lang"])) ? $_REQUEST["lang"] : 'cat';
$step = (isset($_REQUEST["step"])) ? $_REQUEST["step"] : 0;

session_start();

include('header.php');

include('test.php');

if(!empty($ok))
	echo '<p class="ok">'.$ok.'</p>';
	
if(!empty($error))
	echo '<p class="error">'.$error.'</p>';
if($step > 0){
	include('step'.$step.'.php');
	include('menu.php');
}else{
	include('step0.php');
}


include('footer.php');


function get_install_path(){
	$dirs = explode('/',$_SERVER['SCRIPT_FILENAME'],-1);
	$len = count($dirs);
	return $dirs[$len-3].'/'.$dirs[$len-2].'/';
}
?>
