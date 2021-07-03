<?php
	$Errors = false;
	require_once 'core/init.php';

	if (!$getIdentity) {
		


		if (Input::exists()) {

			if (Token::check(Input::get('token'))) {
				

				$validate = new Validate();
				$validation = $validate->check($_POST,array(
				'username' => array(
					'required' => true,
					'exists'	=>  array( 
						'table' => 'admins' ,
						'field'	=> 'username' 
						),
					'alphanumeric' => true,
					'min' => 5
					),
				'password' => array('required' => true)
				));

			
				
				if($validation->passed()){
					
					$user = new User();
					$remember = (Input::get('remember') == 'on' )? true : false;

					$login = $user->login(Input::get('username'),Input::get('password'), $remember);

					if ($login) {
						
						Redirect::to('index.php');

					} else{
						$Errors = array();
						array_push($Errors, array( 'field'		=> 'password', 
													   'message'	=>  'Username and Password don\'t match' 
						));

					}	

				} else {

					$x = 0 ;
					$Errors = array();
					foreach ($validation->errors() as $errors ) {

							array_push($Errors, $errors);

					}

					

				}
			} else {
				$Errors = array();
				array_push($Errors, array('field' => 'token', 'message' => 'Expired token try again'));
			}

		}


		echo $Theme->render('login.html', array(
				'Errors' => $Errors,

			));

	}else{
		Redirect::to('index.php');
	}

	require_once	'core/outlines.php';