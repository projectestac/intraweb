<?php
//header.php
define('_INSTALLTITLE', 'Instal·lació de la Intraweb');

//menu.php
define('_MENU_PROCEDURE','Procés');
define('_MENU_PRESENTATION', 'Presentació');
define('_MENU_REQUIREMENTS', 'Requeriments del sistema');
define('_MENU_DBCREATION', 'Creació de la base de dades');
define('_MENU_ADMINUSER', 'Usuari d\'administració');
define('_MENU_ENDED','Acabat!');

//step1.php
define('_STEP1', '<h2>Benvinguts a la Intraweb</h2>
<p>La Intraweb és una intranet orientada al món educatiu basada en el <a href="http://zikula.org">Zikula</a>, un sistema de gestió de continguts segur i estable.
  <br /> </p> 
  <p>Els usos més destacables de la Intraweb són: </p> 
  <ul> 
    <li>Com a web pública del centre</li> 
    <li>Com a eina de comunicació interpersonal i comunitària</li> 
    <li>Com a instrument pedagògic</li> 
    <li>Com a eina de suport a determinats aspectes de la gestió del centre</li> 
  </ul> 
  <p>Esperem que la Intraweb us agradi.
  <br /> <br /><strong>L\'equip de desenvolupament del projecte Intraweb</strong> </p><br />
 
<h2>Condicions d\'us i instal&middot;laci&oacute; de la maqueta</h2>
		<p>Abans d\'instal&middot;lar la intranet tingueu presents les <a href="http://www.xtec.net/at_usuari/gestusu/identificacio/php/normes_us.htm">normes d\'&uacute;s de p&agrave;gines amb suport PHP de la XTEC</a>.</p>
		<p>Si decidiu instal&middot;lar la intranet en un servidor propi tingueu presents, tamb&eacute;, aquestes normes pel que fa refer&egrave;ncia als fitxers, continguts i privacitat de les dades.</p>
<p>La Intraweb, basada en Zikula, és programari lliure distribuït sota la <a href="http://www.softcatala.cat/wiki/GPL3">llicència GNU/GPL</a>.</p>
		<p>Instal&middot;lar la Intraweb implica acceptar plenament les normes anteriors.</p>');
define('_GOON','Continua');

//step2.php
define('_REQUIREMENTS','Requeriments del sistema');
define('_PHPREQUIREMENTS','Assegureu-vos que la instal·lació del PHP compleix els requeriments següents:');
define('_PHPTOKENAVAILABLE','Té les funcions <b>token</b> disponibles.');
define('_PHPMULTISTRINGAVAILABLE','Té les funcions <b>multibyte string</b> disponibles.');
define('_PHPVERSIONUPPER50','La versió del PHP és superior a la 5.0.');
define('_DIRSANDFILESPERMS','Permisos del directoris i fitxers');
define('_DIRSANDFILESLIST','Cal que els directoris, subdirectoris i fitxers següents tinguin permisos de lectura i escriptura (chmod 777):');

//step3.php
define('_DATABASE','Base de dades');
define('_REQUIREDINFORMATION','Per continuar cal indicar les dades següents:');
define('_ENOUGHPERMS','L\'usuari de la base de dades ha de tenir privilegis suficients per crear taules i introduir-hi registres.');
define('_DBHOST','Nom del servidor de bases de dades:');
define('_DBUSER','Nom d\'usuari de la base de dades:');
define('_DBPASS','Contrasenya de l\'usuari/ària:');
define('_DBNAME','Nom de la base de dades:');
define('_DBTABLESPREFIX','Prefix de les taules de la base de dades:');

//step4.php
define('_STEP4','<h2>Usuari/ària d\'administració</h2>
<p>Per acabar heu de canviar la contrasenya de l\'usuari/ària d\'administració:</p>');
define('_ADMUSER','Nom d\'usuari d\'administració:');
define('_ADMPASS','Contrasenya de l\'usuari/ària d\'administració:');
define('_ADMPASSREPEAT','Repetiu la contrasenya de l\'usuari/ària d\'administració:');
define('_ADMMAIL','Correu electrònic de l\'usuari/ària d\'administració:');

//step5.php
define('_STEP5','<h2>Darrer pas!</h2>
<p>Ja podeu gaudir de la Intraweb, per acabar només heu d\'esborrar el directori i el fitxer següents:</p>
<em>'.get_install_path().'install/</em><br>
<em>'.get_install_path().'install.php</em>
<p>i que canvieu els permisos del fitxer <em>'.get_install_path().'config/config.php</em> a chmod 644.</p>

<p><center><a href="../"><img src="images/next.png" width="24px" height="24px">Vés a la intranet!</a></center></p>
<br>
<h3>Informaci&oacute; sobre el funcionament de la intranet</h3>
<p>Trobareu informaci&oacute; sobre el funcionament de la intranet a la p&agrave;gina de <a href="http://phobos.xtec.cat/intraweb/" target="_blank">suport</a>. Tamb&eacute;, als <a href="http://phobos.xtec.cat/formaciotic/matform/doku.php?id=cursos:d134:index" target="_blank">materials del curs telemàtic d134</a> sobre la instal&middot;laci&oacute; i l\'administraci&oacute; de la intranet i als <a href="http://phobos.xtec.cat/formaciotic/matform/doku.php?id=cursos:d203:index" target="_blank">materials del curs telemàtic d203</a> sobre l\'&uacute;s i la dinamitzaci&oacute; de la intranet.</p>');


//test.php
define('_TOKENFUNCTIONSNOTAVAILABLE','La vostra instal·lació del PHP no té les funcions token disponibles - són necessàries.');
define('_MULTISTRINGFUNCTIONSNOTAVAILABLE','La vostra instal·lació del PHP no té les funcions multibyte string disponibles - són necessàries.');
define('_INVALIDPHPVERSION','La vostra versió del PHP no permet el funcionament correcte de la intranet. Cal que actualitzeu la versió del PHP.');
define('_INCORRECTPERMS','Els permisos no són correctes. Cal que doneu permisos de lectura i escriptura als directoris, subdirectoris i fitxers següents:');
define('_CORRECTPERMS','Els permisos són correctes');
define('_VALUESNEEDED','Cal que ompliu totes les dades');
define('_CONNEXIONDBFAILED','No s\'ha pogut connectar a la base de dades. Comproveu-ne la configuració.');
define('_DBCREATIONFAILED','La base de dades no existeix i no s\'ha pogut crear.');
define('_DBCREATED','S\'ha creat la base de dades correctament');
define('_DBCONNECTED','S\'ha establert la connexió correctament');
define('_FILENOTFOUND','No s\'ha trobat el fitxer sql.txt, proveu de tornar a començar la instal·lació.');
define('_ERROROPENINGFILE','No s\'ha pogut obrir el fitxer sql.txt. Assegureu-vos de que existeix i de que té els permisos adequats.');
define('_ERRORGETTINGTABLES','No s\'han pogut llegir les taules, però la instal·lació pot continuar...\n');
define('_ERRORDELETINGTABLES','No s\'han pogut esborrar les taules, però la instal·lació pot continuar...\n');
define('_ERRORIMPORTINGLINE','S\'ha produït un error en importar la línia');
define('_DBIMPORTED','S\'ha importat la base de dades amb èxit');
define('_CONFIGWRITEERROR','No s\'ha pogut escriure el fitxer de configuració.');
define('_CONFIGWRITED','S\'ha modificat el fitxer de configuració correctament');
define('_CONFIGFILENOTFOUND','No s\'ha trobat el fitxer config/config.php, proveu a tornar a començar la instal·lació.');
define('_PASSNOTEQUALS','Les contrasenyes no coincideixen.');
define('_MAILNOTVALID','L\'adreça electrònica no és vàlida.');
define('_ERRORPASSADMIN','No s\'ha pogut canviar la contrasenya de l\'usuari/ària d\'administració.');
define('_PASSSHORT','La contrasenya és massa curta, escriviu com a mínim 6 caràcters.');
