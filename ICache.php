<?php

abstract class ICache{
	abstract function Get($key, $expiration = false);
	
	abstract function Set($key, $value);
	
	abstract function Delete($key);
}