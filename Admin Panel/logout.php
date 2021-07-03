<?php

require_once('core/init.php');

$user = new User();
$user->logout();
session_unset();

Redirect::to('login.php');

?>