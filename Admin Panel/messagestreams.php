<?php

require('core/init.php');


if ($getIdentity) {

	if (isset($_GET['id']) and (int)$_GET['id']) {

		$page = isset($_GET['page']) ? (int) $_GET['page'] : 1 ;
		
		if ($page < 0) {
			$page = 1;
		}
		$perPage = isset($_GET['per-page']) && $_GET['per-page'] <= 50 ? (int) $_GET['per-page'] : 5 ;

		$start = ($page > 1) ? ( $page * $perPage) - $perPage : 0 ;
		
		$messages = $socialapp->getMessagesById($start, $perPage, $_GET['id']);

		$pages = array() ;

		for ($x = $page ; $x <= (int) ceil( $messages['totalMessages'] / $perPage ) ;$x++) {
			$html = '<a class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored' ;
			if($page === $x ){
				$html .= 'mdl-button--raised mdl-button--accent';
			}
			$html .=  '" href="./messagestreams.php?page='.$x.'&id='.$_GET['id'].'&per-page='.$perPage.' ">'.$x.'</a>';	
			array_push($pages, $html);

			if ($x == 5 + $page){
				break ;
			}
		}
		
		
		if ($messages) {

			echo $Theme->render('um.html', array(
			'page' => $page,
			'profileid'=> $_GET['id'],
			'perPage'	=>	$perPage,
			'messages'	=>	$messages['messages'],
			'totalMessages'	=>	$messages['totalMessages'],
			'pages'	=>	$pages
			));

		} else {
			echo $Theme->render('um.html', array(
			'messages'	=>	false
			));

		}
		
	} else {
		redirect::to('404.php');
	}

}
else{
	redirect::to('login.php');
}

include 'core/outlines.php';