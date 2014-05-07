<?php

define('ROOT_DIR',dirname(__FILE__));
define('GWTPHP_DIR',ROOT_DIR.'/gwtphp');
define('LOG4PHP_DIR',ROOT_DIR.'/log4php');
define('LOG4PHP_CONFIGURATION', ROOT_DIR.'/log4php.xml');

require_once(LOG4PHP_DIR . '/LoggerManager.php');
require_once(GWTPHP_DIR.'/RemoteServiceServlet.class.php');
require_once(GWTPHP_DIR.'/lang/SimpleClassLoader.class.php');
require_once(GWTPHP_DIR.'/lang/ArrayMappedClassLoader.class.php');
require_once(GWTPHP_DIR.'/lang/TypeSignatures.class.php');

GWTPHPContext::getInstance()->setServicesRootDir(ROOT_DIR.'/gwtphp-maps');
GWTPHPContext::getInstance()->setGWTPHPRootDir(GWTPHP_DIR);

$servlet = new RemoteServiceServlet();

$mappedClassLoader = new FolderMappedClassLoader();

$servlet->setMappedClassLoader($mappedClassLoader);
$servlet->start();

?>