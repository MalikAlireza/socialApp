<?php
include 'core/init.php';

if ($getIdentity) {

	$page = isset($_GET['page']) ? (int) $_GET['page'] : 1 ;
	if ($page < 0) {
		$page = 1;
	}
	$perPage = isset($_GET['per-page']) && $_GET['per-page'] <= 50 ? (int) $_GET['per-page'] : 5 ;

	$start = ($page > 1) ? ( $page * $perPage) - $perPage : 0 ;
	
	$posts = $socialapp->getPosts($start, $perPage);

	$pages = array() ;

	for ($x = $page ; $x <= (int) ceil( $posts['totalPosts'] / $perPage ) ;$x++) {
		$html = '<a class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored' ;
		if($page === $x ){
			$html .= 'mdl-button--raised mdl-button--accent';
		}
		$html .=  '" href="./posts.php?page='.$x.'&per-page='.$perPage.' ">'.$x.'</a>';	
		array_push($pages, $html);

		if ($x == 5 + $page){
			break ;
		}
	}

	echo $Theme->render('posts.html', array(
		'page' => $page,
		'perPage'	=>	$perPage,
		'posts'	=>	$posts['posts'],
		'totalPosts'	=>	$posts['totalPosts'],
		'pages'	=>	$pages

		));
}else{
	Redirect::to('login.php');
}
include 'core/outlines.php';