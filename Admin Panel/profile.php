<?php

include 'core/init.php';
Session::put('flashMessage', false );
if ($getIdentity) {

	if (isset($_GET['id']) and (int)$_GET['id']) {
		
		$profile = $socialapp->getUser($_GET['id']);
		
		if ($profile) {
			if (Input::exists()) {
				if ( Token::jscheck(Input::get('token'))) {
					// comment this if block on production
							
							if ($getIdentity['username'] == 'demouser' and $CONF['env'] == 0) {
								
								// $Errors = false;

								// Session::flash('admindashboard', $lang['Demo Settings update success message']);

								// header('Location: '.$_SERVER['REQUEST_URI']);

								// exit();
								header("Refresh:0");
								exit();
								
							}
							
						// end if block
							
					$data = array();
					
					foreach ($_POST as $key => $value) {
					
						if ($value != '' && $key != 'token') {
							$key =  str_replace('mdl-textfield__input--', '', $key);
							$data[$key] = $value;

							$go = true;
						}
					}
					if($go){
						$cont = 1 ;
						$query = 'UPDATE users SET ';
						
						foreach ($data as $key => $value) {
							$query .= "$key = ? ";
							if(sizeof($data) != $cont ){
								$query.= ', ';
							}

							$cont ++ ;
								
							}
						$query .=" WHERE id = ? LIMIT 1";
					array_push($data, $_GET['id']);
					}

					$update = $_db->rawQuery($query, $data);

					header("Refresh:0");

				}
				else{
					var_dump('invalid - token');
				}
			}
			
			echo 	$Theme->render('profile.html', array(
						'profile'	=>	$profile
					));
		} else {
			redirect::to('404.php');
		}
		
	} else {
		redirect::to('users.php');
	}

	
}else{
	redirect::to('login.php');
}

include 'core/outlines.php';