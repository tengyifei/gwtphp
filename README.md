GWTPHP
======

Huge credits go to Rafal M.Malinowski, the author of the original GWTPHP library for GWT 1.5
Project site: https://code.google.com/p/gwtphp/

## Introduction ##
This library gives PHP sites the capability to interface with GWT code via Remote Procedure Call. No change of source code is needed on the GWT side, and minimal effort is required to configure PHP.

## Changes ##
* Modify the original GWTPHP library to be fully compatible with GWT 2.5/2.6
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
Suppose we want to convert the GWT example project contained in stock GWT installation, GreetingService, to operate with PHP.
First include the custom linkers into project source, then edit the module XML to add the inherits statement.
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
Compile the project. The gwtphp-maps folder can be found in war/example. Copy the contents in "php" folder in this repository to the war/example directory as well.
The file "rpc.php" is the gateway of all RPC POST requests. In the GWT example project, the default RemoteServiceRelativePath is "greet". The two names need to be identical for RPC to function. Since most web servers are configured to only execute .php files as PHP code, we may change both names to a PHP file e.g. "greet.php".
```Java
/**
 * The client side stub for the RPC service.
 */
@RemoteServiceRelativePath("greet.php")
public interface GreetingService extends RemoteService {
  String greetServer(String name) throws IllegalArgumentException;
}
```

Directory structure (as of now):
```
example/
  gwtphp/
  gwtphp-maps/
    com/
      example/
        project/
          client/
            GreetingService.class.php
            GreetingService.gwtphpmap.inc.php
  log4php/
  log4php.xml
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
Now the site is ready!
![Working demo](http://i58.tinypic.com/kcccir.png)