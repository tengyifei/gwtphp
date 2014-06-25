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
				array('type' => 'java.lang.IllegalArgumentException'),
			)
		),
		array(
			'name' => 'getSumLong',
			'mappedName' => 'getSumLong',
			'returnType' => 'java.lang.Long',
			'returnTypeCRC' => '4227064769',
			'params' => array(
				array('type' => 'java.lang.Long'),
				array('type' => 'java.lang.Long'),
			) ,
			'throws' => array(
			)
		),
		array(
			'name' => 'doError',
			'mappedName' => 'doError',
			'returnType' => 'com.example.rpcproject.shared.Exceptions',
			'returnTypeCRC' => '2066449693',
			'params' => array(
			) ,
			'throws' => array(
				array('type' => 'java.lang.IllegalArgumentException'),
			)
		)
	),
);
