<?php
abstract class GreetingService implements RemoteService {
	
	public abstract function greetServer($name);
}
