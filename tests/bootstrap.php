<?php
define('ROOT_DIR',dirname(__FILE__) . "/../php");
define('GWTPHP_DIR',ROOT_DIR.'/gwtphp');
define('LOG4PHP_DIR',ROOT_DIR.'/log4php');
define('LOG4PHP_CONFIGURATION', ROOT_DIR.'/log4php.xml');

require_once(LOG4PHP_DIR . '/LoggerManager.php');
require_once(GWTPHP_DIR.'/RemoteServiceServlet.class.php');
require_once(GWTPHP_DIR.'/lang/SimpleClassLoader.class.php');
require_once(GWTPHP_DIR.'/lang/ArrayMappedClassLoader.class.php');
require_once(GWTPHP_DIR.'/lang/TypeSignatures.class.php');

GWTPHPContext::getInstance()->setServicesRootDir(dirname(__FILE__).'/gwtphp-maps');
GWTPHPContext::getInstance()->setGWTPHPRootDir(GWTPHP_DIR);

function processRequest($input){
	$servlet = new RemoteServiceServlet();

	$mappedClassLoader = new FolderMappedClassLoader();

	$servlet->setMappedClassLoader($mappedClassLoader);
	
	$ret = $servlet->start($input);
	
	return $ret;
}