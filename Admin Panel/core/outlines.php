<?php
	
	Session::put(Config::get('sessions/token_name'), $token );
	Session::put(config::get('sessions/js_token_name'), $token );	
?>