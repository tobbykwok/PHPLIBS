<?php

interface ICache{
	public function Get($key, $expiration = false);
	
	public function Set($key, $value);
	
	public function Delete($key);
}