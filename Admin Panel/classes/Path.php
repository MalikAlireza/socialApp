<?php

class Path{
	

	public static function getPath(){
		global $mtbOptions;
			return array(
				'theme_path' => $mtbOptions['protocol'] . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF'])  .'/templates/'.$mtbOptions['theme'].'/',
				'base_path' => $mtbOptions['protocol'] . '//' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']).'/'
				);
	}

}