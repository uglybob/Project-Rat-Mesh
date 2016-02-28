<?php

require_once('vendor/autoload.php');

$options = ['uri' => 'http://localhost/'];
$server = new \SoapServer(null, $options);
$server->setClass('Bh\Lib\API');
$server->setPersistence(SOAP_PERSISTENCE_SESSION);
$server->handle();
