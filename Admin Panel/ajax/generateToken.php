<?php

require_once '../core/init.php';

header('Content-Type: application/json');
	
if (Input::exists()) {


			$newToken = Token::generate();
			Session::put(config::get('sessions/js_token_name'), $newToken );
			echo json_encode($newToken) ;

		
}
