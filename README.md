# GWTPHP RPC [![Build Status](https://travis-ci.org/tengyifei/gwtphp.svg?branch=master)](https://travis-ci.org/tengyifei/gwtphp)

Demo: http://goo.gl/2vMteQ

Huge credits go to Rafal M.Malinowski, the author of the original GWTPHP library for GWT 1.5. His project site: https://code.google.com/p/gwtphp/
The project was dead for many years now, but I thought it was a nice idea and hope to resuscitate it by bringing it to GitHub and incorporating the latest features.

## Composer ##
GWTPHP supports [loading from Composer](https://packagist.org/packages/gwtphp/gwtphp) now (which is highly recommended). Please add the following `require` section to your composer.json. You may change the version to whatever newer released version you find on the Packagist site, or dev-master for bleedin-edge testing.
```
"require": {
    "gwtphp/gwtphp": "1.0.2"
}
```
After which the relevant library files can be loaded via `require_once "vendor/autoload.php";`. The stock RPC gateways are already configured to work with Composer.

## Introduction ##
This library gives PHP sites the capability to interface with GWT code via Remote Procedure Call. It aims to be as identical as possible to the original RPC protocol in GWT. No change of source code is needed on the GWT side, and minimal effort is required to configure PHP.

## Changes ##
* Modify the original GWTPHP library to be compatible with version 7 of the GWT RPC protocol
* Implement as many advanced features as possible e.g. Type obfuscation, XSRF protection
* Use native PHP functions to escape and un-escape Unicode strings, obviating the need for huge character mapping tables and improving performance
* Custom GWT linker to extract type information instead of manually configuring PHP files.

## Usage ##
Include the Java source in your project. Then, in the module XML file, add this single line:
```XML
<inherits name="com.tyf.gwtphp.GwtPhp" />
```
Compile the GWT project. A linker will automatically analyse any class implementing RemoteService and discover any referenced classes to generate the class maps needed by the PHP library.

The generated class maps are contained in a folder named gwtphp-maps, which can be found in the same directory as the JavaScript files.

Next, write server-side implementations using the stub classes. The class name of the implementation has to be [class name]Impl. The file containing the implementation needs to be located in the same directory as the stub file, and its file name needs to be [class name]Impl.class.php. This follows the naming conventions of GAE front-end Java servlets.

## Example ##
The repository comes with an example almost set up. Simply run `composer install` in the `war/example` directory and you are all set! Below is a rough walk-through.

Suppose we want to convert the GWT example project contained in stock GWT installation, GreetingService, to operate with PHP.

First include the custom linkers into project source, then edit the module XML to add the inherits statement, before doing a compile.
```XML
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE module PUBLIC "-//Google Inc.//DTD Google Web Toolkit 2.5.1//EN"
  "http://google-web-toolkit.googlecode.com/svn/tags/2.5.1/distro-source/core/src/gwt-module.dtd">
<module rename-to='example'>

  ... omitted ...
  
  <!-- Other module inherits                                      -->
  <inherits name="com.tyf.gwtphp.GwtPhp" />

  <!-- Specify the app entry point class.                         -->
  <entry-point class='com.example.project.client.Example'/>

  <!-- Specify the paths for translatable code                    -->
  <source path='client'/>
  <source path='shared'/>
</module>
```
After that, write the `composer.json` as shown at the beginning. Place it inside the `war/example` folder and run `composer install` or equivalent. Composer will begin to automatically download all related code and dependencies.

Once the process is finished, you will need to copy `rpc.php`, located in `vendor/gwtphp/gwtphp`, to the `war/example` folder. This file is the gateway of all RPC POST requests. In the GWT example project, the default RemoteServiceRelativePath is "greet". The two names need to be identical for RPC to function. Since most web servers are configured to only execute .php files as PHP code, we may change both names to a PHP file name e.g. "greet.php".
```Java
/**
 * The client side stub for the RPC service.
 */
@RemoteServiceRelativePath("greet.php")
public interface GreetingService extends RemoteService {
  String greetServer(String name) throws IllegalArgumentException;
}
```
The gwtphp-maps folder can be found in `war/example`.

Directory structure (as of now):
```
example/
  vendor/
    gwtphp/
	...
  gwtphp-maps/
    com/
      example/
        project/
          client/
            GreetingService.class.php
            GreetingService.gwtphpmap.inc.php
  greet.php
WEB-INF/
Example.html
```
Next, create GreetingServiceImpl.class.php next to GreetingService.class.php, and write the server-side implementation:
```PHP
<?php
class GreetingServiceImpl {
	
	public function greetServer($input){
		//FieldVerifier is omitted
		$userAgent = $_SERVER['HTTP_USER_AGENT'];
		
		$input = $this->escapeHtml($input);
		$userAgent = $this->escapeHtml($userAgent);
		
		return "Hello, " . $input . "!<br><br>I am running " . "PHP " . phpversion()
        . ".<br><br>It looks like you are using:<br>" . $userAgent;
	}
	
	private function escapeHtml($html){
		return htmlspecialchars($html);
	}
}
?>
```
Now your site is ready!

![Working demo](http://i58.tinypic.com/kcccir.png)

## GWT auto-wiping of WAR folder ##
During each invocation, the GWT Compiler deletes all files residing within the WAR folder. This could be problematic as our implementation is also written inside that folder. One solution is to put `composer.json` and `rpc.php` in the parent directory, and copy the newly generated gwtphp-maps headers manually after compilation.

## Logging ##
GWTPHP uses Log4PHP for recording debugging messages. By default, all logging switches are turned off. You may enable logging by editing rpc.php and uncomment the respective configurations.

The log file is located in the same directory as rpc.php. But these paths may be changed in rpc.php.