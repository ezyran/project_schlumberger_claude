<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once "./vendor/autoload.php";
require_once "./src/Account.php";
require_once "./src/Client.php";

$isDevMode = true;
$config = Setup::createXMLMetadataConfiguration(array(__DIR__ . "/config/yml"), $isDevMode);

// database configuration parameters
$conn = array(
    //'url' => 'postgres://postgres:blopblop@172.20.0.2/webapp?serverVersion=10'
    'url' => 'mysql://root:blopblop@172.25.0.4/webapp'
);

// obtaining the entity manager
$entityManager = EntityManager::create($conn, $config);
