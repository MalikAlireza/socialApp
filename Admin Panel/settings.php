<?php
include 'core/init.php';

if ($getIdentity) {

	$Errors = array();

	if (Input::exists()) {
			if (Token::check(Input::get('token'))) {
				
				if ($getIdentity['username'] == 'demouser' and $CONF['env'] == 0) {
								
								// $Errors = false;

								// Session::flash('admindashboard', $lang['Demo Settings update success message']);

								// header('Location: '.$_SERVER['REQUEST_URI']);

								// exit();
								header("Refresh:0");
								exit();
								
				}

				$validate = new Validate();
				$validation = $validate->check($_POST,array(
				'oldpassword' => array(
					'required' => true
					),
				'username' => array(
					'required' => true,
					'min'	=> 5
					),
				'newpassword' => array(
					'required' => true
					),
				'newpasswordagain' => array(
					'required' => true,
					'matches' => 'newpassword'
					)
				));

			
				
				if($validation->passed()){
					
					$user = new User($getIdentity['id']);
					$clean = new Sanitize();
					
					if(Hash::make(Input::get('oldpassword'), $user->data()['salt']) !== $user->data()['password']) {
			            
			            array_push($Errors, array('field' => 'oldpassword', 'message' => 'Your current password is wrong.'));
			        
			        } else {

			            $salt = Hash::salt(32);

						if($user->update(array('password' => Hash::make(Input::get('newpassword'), $salt),'salt' => $salt, 'username' => Input::get('username')))){
							
							$Errors = false;

							Session::put('flashMessage', 'Password Sucessfuly Changed');
						}

					}



				} else {

					$x = 0 ;
					foreach ($validation->errors() as $errors ) {
					
							array_push($Errors, $errors);

					}

				}
			} else {

				 array_push($Errors, array('field' => 'token', 'message' => 'invalid token'));
			}
	}

	echo $Theme->render('settings.html', array(
		'Errors'	=>	$Errors
		));
	Session::delete('flashMessage');
}
else{
	redirect::to('login.php');
}

require_once	'core/outlines.php';