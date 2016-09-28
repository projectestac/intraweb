Projecte Intraweb
=================

El projecte Intraweb va proporcionar programari per crear Intranets / Webs de centre per a centres educatius. Podeu trobar més informació sobre el projecte a: http://projectestac.github.io/intraweb/

Els projecte va ser creat i mantingut per la unitat de projectes TAC del Departament d'Ensenyament en el marc del servei de plataformes de centre [Àgora](http://agora.xtec.cat). Al llarg dels cursos 2015/16 i 2016/17 els continguts dels espais Intraweb dels centres allotjats a Àgora s'han anat passant progressivament al nou servei [Àgora Nodes](http://agora.xtec.cat/nodes), basat en WordPress i BuddyPress.

El servei _Àgora-Intraweb_ va tancar definitivament el 15 de juliol de 2016, data en que finalitzà també el seu manteniment, suport i actualització per part del Departament d'Ensenyament.

Aquest repositori conté la versió final del codi utilitzat a Àgora, lleugerament adaptat per permetre el seu ús com a plataforma de lloc únic (l'arquitectura d'Àgora és _multi-site_).

__ATENCIÓ__: Aquest repositori conté la versió final del projecte Intraweb a juliol de 2016 i __ja no és objecte de cap tipus d'actualització, manteniment o suport__. El codi aquí publicat ho és només a efectes d'estudi i experimentació. En tractar-se d'un repositori de programari congelat en el temps, és probable que ja no compleixi els requisits de seguretat exigibles a qualsevol aplicació en línia.


Instal·lació
============

Per començar, cal descarregar i descomprimir el [fitxer ZIP](https://github.com/projectestac/intraweb/archive/master.zip) amb el codi de l'aplicació.

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

__Actualització (28/09/2016)__: El mòdul `IWMoodle` està dissenyat per a sincronitzar informació amb _Àgora-Moodle_, que funciona amb una base de dades Oracle i a partir d'un sistema de paths relatius que difícilment es donaran en instal·lacions de lloc únic. En la majoria de casos caldrà __eliminar el directori `intranet/modules/IWMoodle`__ per evitar que l'absència del mòdul de connexió amb Oracle (_oci_) provoqui un error que impediria carregar l'aplicació.
