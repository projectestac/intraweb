Projecte Intraweb
=================

El projecte Intraweb proporciona un programari per crear Intranets / Webs de centre per a centres educatius. Podeu trobar més informació sobre el projecte a: http://projectestac.github.io/intraweb/


Instal·lació
============

Cal descarregar i descomprimir el fitxer enllaçat a la pàgina anterior.

Una vegada descomprimit, el contingut de la carpeta intranet s'ha de pujar al servidor via FTP. Es recomana utilitzar el programa Filezilla, programari gratuït sota llicència GPL disponible en català.

Per establir connexions segures amb el Filezilla cal definir llocs. Per establir connexions FTP sense encriptar no cal fer-ho, tot i que és recomanable per qüestions d'usabilitat.

La comanda Administrador de llocs del menú Fitxer obre un entorn des del qual es poden crear i modificar les configuracions de les connexions.

Feu clic a Nou lloc i introduïu el nom. Els paràmetres a completar són:

Amfitrió: Nom_del_servidor
Tipus de servidor: FTP - File Transfer Protocol
Tipus d'entrada: Normal

A continuació feu clic sobre la pestanya Paràmetres de transferència i seleccioneu el mode de transferència Passiu.

Finalment, torneu a la pestanya General, introduïu el nom d'usuari i la contrasenya i feu clic a Connecta.

Una vegada pujats els fitxers al servidor, cal modificar els permisos d'alguns directoris per a que els scripts del Zikula puguin escriure en ells. Els permisos recomanats són 777 i s'han d'establir per els directoris següents:

    * html/intranet/pnTemp/ (i tots els subdirectoris)
    * html/intranet/zkdata/ (i tots els subdirectoris)
    * html/intranet/config/config.php

Una vegada canviats els permisos dels directoris i fitxers de la llista anterior obriu un navegador, aneu a l'adreça http://el_vostre_servidor/intranet/ i s'iniciarà el procès d'instal·lació de la maqueta. Seguiu les passes del procés d'instal·lació i faciliteu tota la informació que se us demana.

En haver acabat la instal·lació, esborreu del servidor el directori html/intranet/install i el fitxer html/intranet/install.php i doneu permisos 644 al fitxer html/intranet/config/config.php.
