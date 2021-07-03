<?php 
session_start();

error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 'On');
	

require_once __DIR__ . '/db_config.php';
require __DIR__ . '/../vendor/autoload.php';
require __DIR__.'/../classes/Classes.php';

$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$GLOBALS['config'] = array(
	
	'baseUrl'	=>	$CONF['baseUrl'],
	'remember' => array(
        'cookie_name' => 'hash',
        'cookie_expiry' => 604800
    ),
    'sessions' => array(
        'session_name' => 'user',
        'token_name' => 'token',
        'js_token_name' => 'jstoken',
    ),
	'mysql' => array(
		'host'		=>		$CONF['host'],
		'username'		=>	$CONF['user'],
		'password'	=>		$CONF['pass'],
		'db'	=>			$CONF['name']	
		),
);
