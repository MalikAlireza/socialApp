<?php
class DB{

	public static function getInstance(){

		$db = new MysqliDb(array(
			'host' => Config::get('mysql/host'),
			'username' => Config::get('mysql/username'),
			'password' => Config::get('mysql/password'),
			'db' => Config::get('mysql/db'),
			'charset' => 'utf8'));
		
		return $db::getInstance();
	}

}