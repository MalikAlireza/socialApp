<?php
class Config{

	public static function get( $path = null ){
		
		
		if ($path) {

			$config = $GLOBALS['config'];
			
			$path = explode('/', $path);

			foreach ($path as $level) {
				
				if ( in_array($config[$level], $config) ) {
					
					$config = $config[$level];
				
				} else {

					return false;
				}
			}

			return $config;
		}

		return 'false';
	}



}