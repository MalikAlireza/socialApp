<?php
include '../core/init.php';
header('Content-Type: application/json');
$__jsErrors = false ;

if ($getIdentity) {
	
		// comment this if block on production
							
							if ($getIdentity['username'] == 'demouser') {
								
								// $Errors = false;

								// Session::flash('admindashboard', $lang['Demo Settings update success message']);

								// header('Location: '.$_SERVER['REQUEST_URI']);

								
								
								
								$__jsErrors = true;
								echo json_encode($__jsErrors);
								exit();
							}
							
	// end if block

	$action = (isset($_POST['action']))? $_POST['action'] : false ;

	if (!$action) {
		die;
	}

	switch ($action) {

	case 'post':

				if (Input::exists()) {
					if (Token::jscheck(Input::get('token'))) {
						if ($_db->where('id',Input::get('id'))->delete('posts')) {
							$__jsErrors = true;
						}
					} 
					else {
						$__jsErrors = array();
						array_push($__jsErrors, array('field' => 'token_field', 'message' => 'invalid token'));
					}

				}
	break;
	case 'message':

				if (Input::exists()) {
					if (Token::jscheck(Input::get('token'))) {
						if ($_db->where('id',Input::get('id'))->delete('messages')) {
							$__jsErrors = true;
						}
					} 
					else {
						$__jsErrors = array();
						array_push($__jsErrors, array('field' => 'token_field', 'message' => 'invalid token'));
					}

				}
	break;
	case 'report':

				if (Input::exists()) {
					if (Token::jscheck(Input::get('token'))) {
						if ($_db->where('id',Input::get('id'))->delete('reports')) {
							$__jsErrors = true;
						}
					} 
					else {
						$__jsErrors = array();
						array_push($__jsErrors, array('field' => 'token_field', 'message' => 'invalid token'));
					}

				}
	break;
	default:
			# code...
	break;
	}
	echo json_encode($__jsErrors);
}
else{
	return false;
}