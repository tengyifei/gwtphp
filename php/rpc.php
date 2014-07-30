<?php
require 'vendor/autoload.php';
Logger::configure(array(
    'rootLogger' => array(
        'appenders' => array('default'),
    ),
    'appenders' => array(
		'default' => array(
            'class' => 'LoggerAppenderNull',
        ),
		// uncomment to enable logging
        /*'default' => array(
            'class' => 'LoggerAppenderFile',
            'layout' => array(
                'class' => 'LoggerLayoutHtml'
            ),
            'params' => array(
            	'file' => 'log.html',
            	'append' => true
            )
        ),*/
    )
));

define('ROOT_DIR',dirname(__FILE__));
define('GWTPHP_DIR',ROOT_DIR.'/gwtphp');

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