<?php
	
	$mobileChildTheme = '';

	if (isset($_SESSION['Mobile']) and $_SESSION['Mobile'] == 'Mobile') {
		$mobileChildTheme = '/mobilechildTheme';
	}

	$TwigBase64Decode = new Twig_SimpleFilter('base64decode', function ($string) {

			$value = base64_decode($string);
			$hacks = array('console.log(','alert(');
			$cleanvalue = str_replace($hacks, "####", $value);
	    		return $cleanvalue;
			});
	$TwigBase64Encode = new Twig_SimpleFilter('base64encode', function ($string) {
		return base64_encode($string);
	});

	$TwigJsonDecode = new Twig_SimpleFilter('jsondecode', function ($string) {
		return json_decode($string);
	});


	$ThemeLoader = new Twig_Loader_Filesystem(__DIR__.'/../../../html/');
	$ThemePartialsLoader = new Twig_Loader_Filesystem(__DIR__.'/../../../html/components');
	$ThemeLayoutLoader = new Twig_Loader_Filesystem(__DIR__.'/../../../html/layout');
	$chain = new Twig_Loader_Chain(array($ThemeLayoutLoader,$ThemePartialsLoader,$ThemeLoader));
	$Theme = new Twig_Environment($chain, array(
		//'cache' => __DIR__.'/../../templates/__renderCache',
		'debug' => true
	));

	$thisView = basename($_SERVER["SCRIPT_FILENAME"], '.php');
	
	//to use base64_decode function as a filter
	$Theme->addFilter($TwigBase64Decode);
	$Theme->addFilter($TwigBase64Encode);
	$Theme->addFilter($TwigJsonDecode);
	//Mytube Options
	$Theme->addGlobal('paths', $paths);
	$Theme->addGlobal('env',$CONF['env']);
	$Theme->addGlobal('token', $token);
	$Theme->addGlobal('cookie',	$_COOKIE);
	$Theme->addGlobal('session',$_SESSION);
	$Theme->addGlobal('user',	$getIdentity);
	$Theme->addGlobal('view', $thisView );
	//to debug data with dump function
	$Theme->addExtension(new Twig_Extension_Debug());
	$Theme->addExtension(new Twig_Extensions_Extension_Date());