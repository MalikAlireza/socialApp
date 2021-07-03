<?php

require_once 'core/init.php';

if ($getIdentity) {
	
	if (Input::exists()) {

		if (Token::check(Input::get('token'))) {
			switch (Input::get('search-field')) {
				case 'post':

					$posts = $socialapp->find('posts','description',Input::get('q'));
					
					if ($posts) {
						echo $Theme->render('find.html', array(
							'request' => $_POST,
							'posts'	=>	$posts
						));
					}
					else{
						echo $Theme->render('find.html', array(
							'request' => $_POST,
							'posts'	=>	false
						));
					}
					

				break;
				case 'user':
					$users = $socialapp->find('users','name',Input::get('q'));
					
					if ($users) {
						echo $Theme->render('find.html', array(
							'request' => $_POST,
							'users'	=>	$users
						));
					}
					else{
						echo $Theme->render('find.html', array(
							'request' => $_POST,
							'users'	=>	false
						));
					}
					
				break;
				case 'message':
					$messages = $socialapp->find('messages','message',Input::get('q'));
					
					if ($messages) {
						echo $Theme->render('find.html', array(
							'request' => $_POST,
							'messages'	=>	$messages
						));
					}
					else{
						echo $Theme->render('find.html', array(
							'request' => $_POST,
							'messages'	=>	false
						));
					}
				break;
				case 'report':
					$reports = $socialapp->find('reports','reason',Input::get('q'));
					
					if ($reports) {
						echo $Theme->render('find.html', array(
							'request' => $_POST,
							'reports'	=>	$reports
						));
					}
					else{
						echo $Theme->render('find.html', array(
							'request' => $_POST,
							'reports'	=>	false
						));
					}
				break;
				
				default:
					echo $Theme->render('find.html', array(
						'request' => $_POST
					));
				break;
			}
			

		} 
		else {
			echo $Theme->render('find.html', array(
						'request' => $_POST
					));
		}
		
	}
	
	
}else{
	Redirect::to('login.php');
}
include 'core/outlines.php';
