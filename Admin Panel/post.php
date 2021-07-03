<?php

include 'core/init.php';

if ($getIdentity) {

	if (isset($_GET['id']) and (int)$_GET['id']) {
		
		$post = $socialapp->getPost($_GET['id']);
		
		if ($post) {
			if (Input::exists()) {
				if ( Token::jscheck(Input::get('token'))) {
					
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
						$query = 'UPDATE posts SET ';
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

					$update = $_db->rawQuery($query, $data);

					header("Refresh:0");

				}
				else{
					var_dump('invalid - token');
				}
			}
			
			echo 	$Theme->render('post.html', array(
						'post'	=>	$post
					));
		} else {
			
			echo	$Theme->render('post.html', array(
						'post'	=>	false
					));
		}
		
	} else {
		redirect::to('users.php');
	}

	
}else{
	redirect::to('login.php');
}

include 'core/outlines.php';