<?php
	
	$querystring = $_SERVER["QUERY_STRING"] ;
	
	if (strlen($querystring) > 0) {
		$myurl = basename($_SERVER['PHP_SELF'])."?".$querystring ;
	}else{
		$myurl = basename($_SERVER['PHP_SELF']);
	}


	
	if (is_numeric($mtbOptions['facebookappid'])) {
		# code...
		
		$loginUrl = '#';


		$fb = new Facebook\Facebook([
				'app_id' => $mtbOptions['facebookappid'],
				'app_secret' => $mtbOptions['facebooksecret'],
				'deault_graph_version'	=>'v2,5'
		]);

		if (!$getIdentity) {
			

			$helper = $fb->getRedirectLoginHelper();

			$permissions = ['email']; // optional
				
			try {
				if (isset($_SESSION['facebook_access_token'])) {
					$accessToken = $_SESSION['facebook_access_token'];
				} else {
			  		$accessToken = $helper->getAccessToken();
				}
			} catch(Facebook\Exceptions\FacebookResponseException $e) {
			 	// When Graph returns an error
			 	echo 'Graph returned an error: ' . $e->getMessage();
			  	exit;
			} catch(Facebook\Exceptions\FacebookSDKException $e) {
			 	// When validation fails or other local issues
				echo 'Facebook SDK returned an error: ' . $e->getMessage();
			  	exit;
			 }
			if (isset($accessToken)) {
				if (isset($_SESSION['facebook_access_token'])) {
					$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
				} else {
					// getting short-lived access token
					$_SESSION['facebook_access_token'] = (string) $accessToken;
				  	// OAuth 2.0 client handler
					$oAuth2Client = $fb->getOAuth2Client();
					// Exchanges a short-lived access token for a long-lived one
					$longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
					$_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;
					// setting default access token to be used in script
					$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
				}
				// redirect the user back to the same page if it has "code" GET variable
				if (isset($_GET['code'])) {

					header("Location: ./$myurl");

				}
				// getting basic info about user
				try {
					$profile_request = $fb->get('/me?fields=id,name,first_name,last_name,email,picture.width(300)');
					$profile = $profile_request->getGraphNode()->asArray();
				} catch(Facebook\Exceptions\FacebookResponseException $e) {
					// When Graph returns an error
					echo 'Graph returned an error: ' . $e->getMessage();
					session_destroy();
					// redirecting user back to app login page

					header("Location: ./$myurl");
					exit;
				} catch(Facebook\Exceptions\FacebookSDKException $e) {
					// When validation fails or other local issues
					echo 'Facebook SDK returned an error: ' . $e->getMessage();
					exit;
				}
				
				// printing $profile array on the screen which holds the basic info about user

				$_SESSION['fb_profile'] = $profile;
				
				$logger = new User();

				$login = $logger->facebooklogin($profile['id'], $profile['id'], true);

				if (!$login) {
					//register and login again

					$avatar = file_get_contents($profile['picture']['url']);
					$avatar_name =  Hash::unique().'.jpg';
					$avatar_path = __DIR__.'/../../../storage/avatar/'.$avatar_name;


					$image = ImageCreateFromString($avatar);

					$w = ImageSX($image);
					$h = ImageSY($image);
					
					if ($w = $h ) {
						
						// create image 
							$outputIMG = ImageCreateTrueColor($w, $h);
							ImageCopyResampled($outputIMG, $image, 0, 0, 0, 0, $w, $h, ImageSX($image), ImageSY($image));
						// save image
							$was_saved = ImageJPEG($outputIMG, $avatar_path, 100);
					
					} else if($w > $h ){

						// create image 
							$outputIMG = ImageCreateTrueColor($w, $h);
							ImageCopyResampled($outputIMG, $image, 0, 0, 0, 0, $h, $h, ImageSX($image), ImageSY($image));
							imagecrop($outputIMG, ['x' => 0, 'y' => 0, 'width' => $h, 'height' => $h]);
						// save image
							$was_saved = ImageJPEG($outputIMG, $avatar_path, 100);

					}else{

						// create image 
							$outputIMG = ImageCreateTrueColor($w, $h);
							ImageCopyResampled($outputIMG, $image, 0, 0, 0, 0, $w, $w, ImageSX($image), ImageSY($image));
							imagecrop($outputIMG, ['x' => 0, 'y' => 0, 'width' => $w, 'height' => $w]);
						// save image
							$was_saved = ImageJPEG($outputIMG, $avatar_path, 100);
					}

					
					if ($was_saved) {
						
						$salt = Hash::salt(32);


						$profile_data = array(
							'username' => $mytube->removeAccents(strtolower(trim($profile['first_name'].'_'.$profile['last_name']))),
							'avatar'	=>	$mtbOptions['protocol'].'://'.Config::get('baseUrl').'storage/avatar/'.$avatar_name,
							'password' => Hash::make($profile['id'], $salt),
							'salt' => $salt,
							'first_name' => $profile['first_name'],
							'last_name'	=> $profile['last_name'],
							'email'	=>	$profile['email'],
							'joined' => date('Y-m-d  H:i:s'),
							'group' => 0,
							'facebook_id' => $profile['id'],
						);

					} else {
						// TODO 
					}

					try {
						$logger->create($profile_data);

						sleep(5);

						$login = $logger->facebooklogin($profile['id'], $profile['id'], true);

						if ($login) {
							$_SESSION['fb_profile'] = null ;
							//header("Location: $actual_link");
						}

						} catch (Exception $e) {
							echo $e->getMessage();
					}

				}else{
					$_SESSION['fb_profile'] = null ;
					//header("Location: ");
				}

				//session_unset();
				//var_dump($logme.find(true, array('filed' => 'facebook_id', 'value' => $profile['id'])));

				//print_r($profile);

				/*$login = $user->loginwithfacebook(Input::get('username'),Input::get('password'), true );*/
			  	// Now you can redirect to another page and use the access token from $_SESSION['facebook_access_token']
			} else {
				// replace your website URL same as added in the developers.facebook.com/apps e.g. if you used http instead of https and you used non-www version or www version of your website then you must add the same here
				$loginUrl = $helper->getLoginUrl( $paths['base_path'].'/login.php?backto='.base64_encode($paths['base_path'].$myurl).'', $permissions);

				
				
			}

		}

		$Theme->addGlobal('fb_login_url', $loginUrl);

	}
