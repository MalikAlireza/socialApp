<?php

require_once 'core/init.php';

if ($getIdentity) {
	
	$totalAccounts = $socialapp->getTotal('users');
	$totalSuspendedAccounts = $socialapp->getTotalSuspendedAccounts();
	$totalComments = $socialapp->getTotal('comments');
	$totalMessages = $socialapp->getTotal('messages');
	$totalReports = $socialapp->getTotal('reports');
	$totalBlocks = $socialapp->getTotal('blocked');
	$totalVideos = $socialapp->getTotal('posts','type',2);
	$totalPhotos = $socialapp->getTotal('posts','type',1);

	echo $Theme->render('home.html', array(
		'totalAccounts' => $totalAccounts,
		'totalSuspendedAccounts'	=>	$totalSuspendedAccounts,
		'totalComments'	=>	$totalComments,
		'totalMessages' => $totalMessages,
		'totalReports' => $totalReports,
		'totalVideos' => $totalVideos,
		'totalPhotos' => $totalPhotos,
		'totalBlocks' => $totalBlocks
	));
}else{
	Redirect::to('login.php');
}

include 'core/outlines.php';