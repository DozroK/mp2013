<?php

require_once __DIR__.'/../externals/doctrine2/lib/vendor/doctrine-common/lib/Doctrine/Common/ClassLoader.php';

$lib = __DIR__ . '/../externals/doctrine2/lib/';


$classLoader = new \Doctrine\Common\ClassLoader('Doctrine\DBAL', $lib.'/vendor/doctrine-dbal/lib');
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Doctrine\Common', $lib.'/vendor/doctrine-common/lib');
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Symfony', (__DIR__ . '/../../lib/vendor'));
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Entities', __DIR__);
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Proxies', __DIR__);
$classLoader->register();

$classLoader = new \Doctrine\Common\ClassLoader('Doctrine\ORM', $lib);
$classLoader->register();


$config = new \Doctrine\ORM\Configuration();


$config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache);
// chemin paramétrable
$driverImpl = $config->newDefaultAnnotationDriver(array(__DIR__."/../entities/Entity"));
$config->setMetadataDriverImpl($driverImpl);

$config->setProxyDir(__DIR__ . '/Proxies');
$config->setProxyNamespace('Proxies');

$connectionOptions = array(
    'driver'   => 'pdo_mysql',
    'host'     => '127.0.0.1',
    'dbname'   => 'mp',
    'user'     => 'root',
    'password' => 'root'
    );


$em = \Doctrine\ORM\EntityManager::create($connectionOptions, $config);

$helpers = new Symfony\Component\Console\Helper\HelperSet(array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
));