<?php
include '../core/init.php';
header('Content-Type: application/json');
$__jsErrors = false ;

if ($getIdentity) {
	
	// comment this if block on production
							
							if ($getIdentity['username'] == 'demouser' and $CONF['env'] == 0) {
								
								// $Errors = false;

								// Session::flash('admindashboard', $lang['Demo Settings update success message']);

								// header('Location: '.$_SERVER['REQUEST_URI']);

								// 
								
								
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

	case 'report':

				if (Input::exists()) {
					if (Token::jscheck(Input::get('token'))) {
						$data = array(
							'action' => 1
							);
						if ($_db->where('id',Input::get('id'))->update('reports', $data)) {
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