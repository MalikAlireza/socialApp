<?php

class Token{

	public static function generate(){
		return md5(uniqid());
	}

	public static function check($token){
		$tokenName = Config::get('sessions/token_name');

		if (Session::exists($tokenName) && $token === Session::get($tokenName)){
			Session::delete($tokenName);

			return true;
		} 
	}

	public static function jscheck($token){
		$tokenName = Config::get('sessions/js_token_name');

		if (Session::exists($tokenName) && $token === Session::get($tokenName)){
			//Session::delete($tokenName);

			return true;
		}

		return false;
	}
}