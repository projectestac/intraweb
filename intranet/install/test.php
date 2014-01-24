<?php
if(isset($_GET["back"])) return;


//Permisos dels fitxers
if($step == 3){
	$ok='';
	$errorBoolean='';
	$errorAux = '';
	$error = '';

	//Check if the php token functions exists
	$funcexists = false;
	if (function_exists('token_get_all')) {
		$funcexists = true;
	}
	
	if(!$funcexists){
		$errorBoolean = true;
		$error = "<em>"._TOKENFUNCTIONSNOTAVAILABLE."</em><br /><br />";
		$step = 2;
	}

	//Check if the php token functions exists
	$funcexists = false;
	if (function_exists('mb_get_info')) {
		$funcexists = true;
	}
	
	if(!$funcexists){
		$errorBoolean = true;
		$error .= "<em>"._MULTISTRINGFUNCTIONSNOTAVAILABLE."</em><br /><br />";
		$step = 2;
	}

	if(phpversion() < 5){
		$errorBoolean = true;
		$error .= "<em>"._INVALIDPHPVERSION."</em><br /><br />";
		$step = 2;
	}

	$install_path = get_install_path();
	
	$writetableArray = array(array('path' => '../pnTemp/',
					'fullPath' => $install_path.'pnTemp/',
					'type' => 1),
				array('path' => '../config/config.php',
					'fullPath' => $install_path.'config/config.php',
					'type' => 2),
				array('path' => '../zkdata/',
					'fullPath' => $install_path.'zkdata/',
					'type' => 1));

	foreach($writetableArray as $writetable){
		if(!comprova_permisos($writetable['path'],$writetable['type'])){
			$errorBoolean = true;
			$errorAux .= "<em>$writetable[fullPath]</em><br>";
			$step = 2;
		}
	}

	$error = ($errorBoolean) ? $error._INCORRECTPERMS."<br>" . $errorAux: '';
	
	if(empty($error)) $ok = _CORRECTPERMS;

}

/*
 * param: type  1 - folder. Check recursibily only in folders. Not in files into the folders
 *		2 - file. Check a file
 *
 *
 *
*/

function comprova_permisos($path, $type) {
	if(!@is_readable($path)) return false;
		
	//Cas que sigui un fitxer
    if (!is_dir($path))
        return is_writable($path);
        
	$return = true;
	
    $dh = opendir($path);
    while (($file = readdir($dh)) !== false) {
        if(strpos($file,'.') !== 0) {
            $fullpath = $path.'/'.$file;
            if(is_link($fullpath)) continue;
            if(is_dir($fullpath))
            	$return &= comprova_permisos($fullpath,1);
            if(is_dir($fullpath) && $type == 1)
		$return &= @is_readable($fullpath) && @is_writable($fullpath);
        }
    }

    closedir($dh);
   
   	return $return;
} 


//Base de dades
if($step == 4){
	$ok = '';
	$error = '';
	$dbhost = $_POST["dbhost"];
	$dbuname = $_POST["dbuname"];
	$dbpass = $_POST["dbpass"];
	$dbname = $_POST["dbname"];
	$dbprefix = $_POST["dbprefix"];

	
	//Camps omplerts?
	if(empty($dbhost) || empty($dbuname) || empty($dbname) || empty($dbprefix)){
		$error = _VALUESNEEDED;
		$step--;
		return;
	}
	
	//Connexió al servidor
	$link = @mysql_connect($dbhost, $dbuname, $dbpass);
	if (!$link) {
		$error = _CONNEXIONDBFAILED;
		$step--;
		return;
	}

	//Connecta a la bd
	$_SESSION["dbhost"] = $_POST["dbhost"];
	$_SESSION["dbuname"] = $_POST["dbuname"];
	$_SESSION["dbpass"] = $_POST["dbpass"];
	$_SESSION["dbname"] = $_POST["dbname"];
	$_SESSION["dbprefix"] = $_POST["dbprefix"];
		
	if(!mysql_select_db($dbname, $link)){
		//Creació de la base de dades
		if(!mysql_query ("CREATE DATABASE " . $dbname, $link)){
			$error = _DBCREATIONFAILED;
			$step--;
			return;
		}
		$ok = _DBCREATED."<br>";
		$ok .= "<br>"._DBCONNECTED."<br>";
	}
	else 
		$ok = _DBCONNECTED."<br>";


	//Importació de la base de dades
	$dbhost = $_SESSION["dbhost"];
	$dbuname = $_SESSION["dbuname"];
	$dbpass = $_SESSION["dbpass"];
	$dbname = $_SESSION["dbname"];
	$dbprefix = $_SESSION["dbprefix"];
	
	//Connexió al servidor
	$link = @mysql_connect($dbhost, $dbuname, $dbpass);
	if (!$link) {
		$error = _CONNEXIONDBFAILED;
		$step--;
		return;
	}

	if(!mysql_select_db($dbname, $link)){
		$error = _CONNEXIONDBFAILED;
		$step--;
		return;
	}

	$file = "lang/".$lang."/sql.txt";

	if(!file_exists($file)){
		$error = _FILENOTFOUND."<br />";
		$step--;
		return;
	}

	$fh = fopen($file, 'r');
	if($fh == false){
		$error = _ERROROPENINGFILE."<br />";
		$step--;
		return;
	}

	if(!$rs = mysql_query("SHOW TABLES FROM $dbname LIKE '".$dbprefix."_%'",$link)){
		$error = _ERRORGETTINGTABLES;	
	}

	$tablesString = '';

	while ($row = mysql_fetch_array($rs)) {
		$tablesString .= '`'.$row[0].'`,';
	}

	if($tablesString != ''){
		$exec = "DROP TABLE " . substr($tablesString,0,-1);

		if(!mysql_query ($exec,$link)){
			$error = _ERRORDELETINGTABLES;	
		}
	}

	mysql_query ("ALTER DATABASE `" . $dbname . "` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci");
	if(!mysql_select_db($dbname, $link)){
		$error = _CONNEXIONDBFAILED;
		$step--;
		return;
	}

	$lines = file($file);
	$exec = '';
	foreach ($lines as $line_num => $line) {
		$line = str_replace('z_',$dbprefix.'_',trim($line));
		
		if(empty($line) || strpos($line,'--') === 0) continue;
		$exec .= $line;
		if(strrpos($line,';') === strlen($line)-1){
			if(!mysql_query ($exec,$link)){
				$error = _ERRORIMPORTINGLINE." ".$line_num.":<br>".$exec."<br>".mysql_error()."\n";	
				$step--;
				return;
			}
			$exec = '';
		}
	}
	
	$ok .= _DBIMPORTED."<br />";
	
	//Lectura del fitxer de configuració
	$file = "../config/config.php";
	$fh = @fopen($file, 'r+');
	if($fh == false){
		$error = _CONFIGFILENOTFOUND."<br />";
		$step--;
		return;
	}
	
	$lines = file($file);
	$final_file = "";
	$bhost = false;
	$buser = false;
	$bpass = false;
	$bname = false;
	$installed = false;
	$tablesPrefix = false;

	// Loop through our array, show HTML source as HTML source; and line numbers too.
	foreach ($lines as $line_num => $line) {
		if(strpos($line, "PNConfig['DBInfo']['default']['dbhost']") && !$bhost){
			$bhost = true;
			$line =  "\$PNConfig['DBInfo']['default']['dbhost'] = '".$dbhost."';\n";
		}else
		if(strpos($line, "PNConfig['DBInfo']['default']['dbuname']") && !$buser){
			$buser = true;
			$line =  "\$PNConfig['DBInfo']['default']['dbuname'] = '".$dbuname."';\n";
		}else
		if(strpos($line, "PNConfig['DBInfo']['default']['dbname']") && !$bname){
			$bname = true;
			$line =  "\$PNConfig['DBInfo']['default']['dbname'] = '".$dbname."';\n";
		}else
		if(strpos($line, "PNConfig['DBInfo']['default']['dbpass']") && !$bpass){
			$bpass = true;
			$line =  "\$PNConfig['DBInfo']['default']['dbpass'] = '".$dbpass."';\n";
		}else
		if(strpos($line, "PNConfig['System']['installed']") && !$installed){
			$installed = true;
			$line =  "\$PNConfig['System']['installed'] = 1;\n";
		}else
		if(strpos($line, "PNConfig['System']['prefix']") && !$tablesPrefix){
			$installed = true;
			$line =  "\$PNConfig['System']['prefix'] = '".$dbprefix."';\n";
		}
		$final_file .= $line;
	
	}
	$file = "../config/config.php";
	$fh = @fopen($file, 'w+');
	if(!fwrite($fh,$final_file)){
		$error = _CONFIGWRITEERROR;
		$step--;
		return;
	}
	$ok .= _CONFIGWRITED."<br>";
	fclose($fh);
}


if($step == 5){
	$ok = '';
	$error = '';
	$admuser = "admin";
	$admpass = $_POST["admpass"];
	$admpass2 = $_POST["admpass2"];
	$admmail = $_POST["admmail"];
	
	//Camps omplerts?
	if(empty($admpass) || empty($admpass2) || empty($admmail)){
		$error = _VALUESNEEDED;
		$step--;
		return;
	}
	
	//Passwords coincidents?
	if(strlen($admpass) < 6){
		$error = _PASSSHORT;
		$step--;
		return;
	}
	
	//Passwords coincidents?
	if($admpass !== $admpass2){
		$error = _PASSNOTEQUALS;
		$step--;
		return;
	}
	
	$passwd = md5($admpass);
	
	if(!eregi('^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$', $admmail)){
		$error = _MAILNOTVALID;
		$step--;
		return;
	}
	
	$dbhost = $_SESSION["dbhost"];
	$dbuname = $_SESSION["dbuname"];
	$dbpass = $_SESSION["dbpass"];
	$dbname = $_SESSION["dbname"];
	$dbprefix = $_SESSION["dbprefix"];

	//Connexió al servidor
	$link = @mysql_connect($dbhost, $dbuname, $dbpass);
	if (!$link) {
		$error = _CONNEXIONDBFAILED;
		$step--;
		return;
	}

	if(!mysql_select_db($dbname, $link)){
		$error = _CONNEXIONDBFAILED;
		$step--;
		return;
	}
	
	if(!$rs = mysql_query("UPDATE ".$dbprefix."_users set pn_email='".$admmail."', pn_pass='".$passwd."' WHERE pn_uname='admin'",$link)){
		$error = _ERRORPASSADMIN;
		echo mysql_error();	
	}
}
