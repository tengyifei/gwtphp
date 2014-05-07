<?php
if (!isset($gwtphpmap)) $gwtphpmap = array();
$gwtphpmap[] = 
	array(
	'className' => 'com.example.rpcproject.client.GreetingService',
	'mappedBy' => 'com.example.rpcproject.client.GreetingService',
	'methods' => array (
		array(
			'name' => 'greetServer',
			'mappedName' => 'greetServer',
			'returnType' => 'java.lang.String',
			'returnTypeCRC' => '2004016611',
			'params' => array(
				array('type' => 'java.lang.String'),
			) ,
			'throws' => array(
			)
		)
	)
);
