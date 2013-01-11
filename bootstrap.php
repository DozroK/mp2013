<?php
// bootstrap.php
use Doctrine\ORM\Tools\Setup,
    Doctrine\ORM\EntityManager,
    Doctrine\ORM\Configuration,
    Doctrine\Common\Cache\ArrayCache as Cache,
    Doctrine\Common\Annotations\AnnotationRegistry,
    Doctrine\Common\ClassLoader;

// Sets the default timezone used by all date/time functions in a script
date_default_timezone_set("Europe/Paris");
 
//autoloading
require_once __DIR__ . '/externals/doctrine2/lib/Doctrine/ORM/Tools/Setup.php';
Setup::registerAutoloadGit(__DIR__ . '/externals/doctrine2');
$loader = new ClassLoader('Entity', __DIR__ . '/entities');
$loader->register();
$loader = new ClassLoader('EntityProxy', __DIR__ . '/entities');
$loader->register();
$loader = new ClassLoader('RDFHelper', __DIR__ . '/entities');
$loader->register();

$loader = new ClassLoader('lib', __DIR__ . '');
$loader->register();
 
//configuration
$config = new Configuration();
$cache = new Cache();
$config->setQueryCacheImpl($cache);
$config->setProxyDir(__DIR__ . '/lib/EntityProxy');
$config->setProxyNamespace('EntityProxy');
$config->setAutoGenerateProxyClasses(true);
 
//mapping (example uses annotations, could be any of XML/YAML or plain PHP)
AnnotationRegistry::registerFile(__DIR__ . '/externals/doctrine2/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php');
$driver = new Doctrine\ORM\Mapping\Driver\AnnotationDriver(
    new Doctrine\Common\Annotations\AnnotationReader(),
    array(__DIR__ . '/library/Entity')
);
$config->setMetadataDriverImpl($driver);
$config->setMetadataCacheImpl($cache);
 
// Parameters
$parameters = parse_ini_file(__DIR__.'/parameters.ini');

//Request
$get = $_GET;
 
//getting the EntityManager
$em = EntityManager::create(
    array(
    'driver'   => 'pdo_mysql',
    'host'     => $parameters["host"],
    'dbname'   => $parameters["dbname"],
    'user'     => $parameters["user"],
    'password' => $parameters["password"],
    'charset' => 'utf8',
    'driverOptions' => array(
        1002=>'SET NAMES utf8'
        )
    ),
    $config
);
