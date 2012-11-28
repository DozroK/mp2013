<?php
//http://docs.doctrine-project.org/projects/doctrine-orm/en/2.1/reference/tools.html#reverse-engineering

include_once("bootstrap.php");

$em->getConfiguration()->setMetadataDriverImpl(
    new \Doctrine\ORM\Mapping\Driver\DatabaseDriver(
        $em->getConnection()->getSchemaManager()
    )
);

$cmf = new \Doctrine\ORM\Tools\DisconnectedClassMetadataFactory();
$cmf->setEntityManager($em);
$metadata = $cmf->getAllMetadata();

$cme = new \Doctrine\ORM\Tools\Export\ClassMetadataExporter();


$exporter = $cme->getExporter('yml', __DIR__.'/yml');

$exporter->setMetadata($metadata);
$exporter->export();