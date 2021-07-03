<?php

require_once 'init_config.php';

		$getIdentity = $is_on = $flash = $userOptions = $subscriptions = $isMobile = false ;

		if (Session::exists('flashMessage')) {
			Session::delete('flashMessage');
		}
		else{
			Session::put('flashMessage', false );
		}
		
//set defaults


//end defaults

//create a global DataBase Instance

		$_db = DB::getInstance();

//end Database instance

//create global variables

	//first global: $socialapp
		$socialapp = new Socialapp();
	//end first

	//second global: $mtbOptions
	//end second

	//subsequent globals: $token, $clean, $paths
	// if you need to add more globals yourself, you can add at this point 
		$token = Token::generate();
		$clean = new Sanitize();
		$paths = Path::getPath();

	//end subsequent globals 

//end global variable


//Check if a user is loged in or remember him if he wanted to be rememembered

	if (Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('sessions/session_name'))) {

		$hash = Cookie::get(Config::get('remember/cookie_name'));
		$hashCheck = $_db->where('hash', $hash)->getOne('admins_session');

		if ( count($hashCheck) > 0) {
			// echo $hashCheck['user_id'];
			$currentUser = new User($hashCheck['user_id']);
			$currentUser->login();
			$getIdentity = $currentUser->data();
		}
	} 
	else if (Session::exists(Config::get('sessions/session_name'))) {
		
		$currentUser = new User(Session::get(Config::get('sessions/session_name')));
		$currentUser->login();
		$getIdentity = $currentUser->data();
	}


	
// end login/remember


// if it is the first time that the user logged in, subscribe him to his own feed

	
// end self activity subscription


require_once __DIR__.'/../includes/libs/load.php';



// comment this if block on production
				

// end if block
