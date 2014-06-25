<?php

class RPCTest extends \PHPUnit_Framework_TestCase
{

    public function testAddTwoLong()
    {
        $post = 
<<<'EOD'
7|0|5|http://www.example.com|06947190A0EEE28C80FC4D690B2E92F0|com.example.rpcproject.client.GreetingService|getSumLong|java.lang.Long/4227064769|1|2|3|4|2|5|5|5|Pun8|5|DOe6|
EOD;
		$expected = 
<<<'EOD'
//OK['S9G2',1,["java.lang.Long\/4227064769"],0,7]
EOD;

		$this->assertEquals($expected, processRequest($post));
    }
	
    public function testPassString()
    {
		$randStr = base64_encode(sha1(strval(time())));
        $post = 
<<<EOT
7|0|6|http://www.example.com|06947190A0EEE28C80FC4D690B2E92F0|com.example.rpcproject.client.GreetingService|greetServer|java.lang.String/2004016611|{$randStr}|1|2|3|4|1|5|6|
EOT;
		$expected = 
<<<EOT
//OK[1,["Ack:{$randStr}"],0,7]
EOT;

		$this->assertEquals($expected, processRequest($post));
    }
	
    public function testReturnException()
    {
        $post = 
<<<'EOD'
7|0|4|http://www.example.com|06947190A0EEE28C80FC4D690B2E92F0|com.example.rpcproject.client.GreetingService|doError|1|2|3|4|0|
EOD;
		$expected = 
<<<'EOD'
//EX[2,1,["java.lang.IllegalArgumentException\/1755012560","Java system exception"],0,7]
EOD;

		$this->assertEquals($expected, processRequest($post));
    }

}