<?php

include 'lib/bootstrap.php';
include_once __DIR__ . '/plugins/Doctrine/Plugin.php';

global $ZConfig;

PluginUtil::loadPlugin('SystemPlugin_Doctrine_Plugin');
$eventManager = $core->getEventManager();

$dbEvent = new Zikula_Event('doctrine.init_connection', null, array('lazy' => true));

$connection = $eventManager->notify($dbEvent)->getData();

foreach (Doctrine_Manager::getInstance()->getConnections() as $connection) {
    $conn = $connection->getOptions();
    preg_match('/host=(.*);/', $conn['dsn'], $host);
    preg_match('/dbname=(.*)/', $conn['dsn'], $dbname);
}

$path = $ZConfig['Multisites']['filesRealPath'] . '/data';
$serializedPath = addslashes(serialize($path));
$URLBase = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/';

try {
    $tableName = 'module_vars';
    $stmtUpdateDocRoot = $connection->prepare("UPDATE $tableName SET value = '$serializedPath' WHERE module_vars.modname='IWmain' AND module_vars.name='documentRoot' ");
    $stmtUpdateDocRoot->execute();
    echo "Document Root has been updated on server <strong>$host[1]</strong> for database <strong>$dbname[1]</strong><br/>";

    $stmtUpdateDocRoot = $connection->prepare("UPDATE $tableName SET value = '$URLBase' WHERE module_vars.modname='IWmain' AND module_vars.name='URLBase' ");
    $stmtUpdateDocRoot->execute();
    echo "URL base has been updated on server <strong>$host[1]</strong> for database <strong>$dbname[1]</strong><br/>";

} catch (Exception $e) {
    echo "Fatal ERROR- Error updating Document Root on server <strong>$host[1]</strong> for database <strong>$dbname[1]</strong><br/>";
}
