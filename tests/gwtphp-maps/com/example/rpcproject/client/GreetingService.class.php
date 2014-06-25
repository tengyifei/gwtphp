<?php
abstract class GreetingService implements RemoteService {
	
	public abstract function greetServer($name);
	
	public abstract function getSumLong($a, $b);
	
	public abstract function doError();
}
