<?php

class User{
	private $_db,
			$_db_user_table,
			$_data,
			$_isLoggedIn,
			$_sessionName,
			$_cookieName;

	public function __construct($user = null){
		$this->_db = DB::getInstance();
		$this->_db_user_table = 'admins';
		$this->_sessionName = Config::get('sessions/session_name');
		$this->_cookieName = Config::get('remember/cookie_name');

		if (!$user) {
			if (Session::exists($this->_sessionName)) {
				$user = Session::get($this->_sessionName);
				if ($this->find($user)) {
					// user is on
					$this->_isLoggedIn = true;
				} else{
					// process logout
					$this->logout();
				}
			}
		} else{
			// set user data on user instantiation
			$this->find($user);
		
		}
	
	}

	public function create($fields = array()){

		if(!$this->_db->insert( $this->_db_user_table, $fields)){
			throw new Exception('There was a problem creating an account.' . DB::getInstance()->getLastError()); 
		}
	}

	public function update($fields = array(), $id = null) {


        if(!$id && $this->isLoggedIn()) {
            $id = $this->data()['id'];
        }

        if(!$this->_db->where('id', $id)->update($this->_db_user_table, $fields)) {
            throw new Exception('There was a problem updating');
        } else {
        	return true;
        }
    }

	public function find($user = null, $OAuth = array("field" => null, "value" => null)){
		
		if ($user){
			$field = (is_numeric($user)) ? 'id' : 'username';
			$value = $user;

			if (is_bool($user)){

				$field = $OAuth["field"];
				$value = $OAuth["value"];

			}

			$data = $this->_db->where($field, $value)->getOne($this->_db_user_table);
			
			if( count($data) > 0 ){

				$this->_data = $data;
				$this->_isLoggedIn = true;
				
				return true;
			} 
		}

		return false;
	}

	public function login( $username = null , $password  = null , $remember = null){
		
		if ($username == null && $password == null && $this->exists()) {
			
			Session::put($this->_sessionName, $this->data()['id']);

		} else {

			$user = $this->find($username);

			if ($user){

				if ($this->data()['password'] === Hash::make( $password, $this->data()['salt'])){
					
					Session::put($this->_sessionName, $this->data()['id']); 

					if ($remember) {
						$hash = Hash::unique();
						$hashCheck = $this->_db->where('user_id', $this->data()['id'])->getOne('admins_session', 'hash');

						if ( count($hashCheck) > 0 ) {

							$this->_db->where('user_id', $this->data()['id'])->update('admins_session', array(
								'hash' => $hash
								));							
							
						} else {

							$this->_db->insert('admins_session', array(
								'user_id' => $this->data()['id'],
								'hash' => $hash
								));
						}

						Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
					}

					return true;
				}

			} 

		}

		return false;
		
	}

	public function facebooklogin( $facebook_id = null , $password  = null , $remember = null){
		
		if ($facebook_id == null && $password == null && $this->exists()) {
			
			Session::put($this->_sessionName, $this->data()['id']);

		} else {

			$user = $this->find( true, $OAuth = array('field' => 'facebook_id', 'value' => $facebook_id));

			if ($user){

				if ($this->data()['password'] === Hash::make( $password, $this->data()['salt'])){
					
					Session::put($this->_sessionName, $this->data()['id']); 

					if ($remember) {
						$hash = Hash::unique();
						$hashCheck = $this->_db->where('user_id', $this->data()['id'])->getOne('admins_session', 'hash');

						if ( count($hashCheck) > 0 ) {

							$this->_db->where('user_id', $this->data()['id'])->update('admins_session', array(
								'hash' => $hash
								));							
							
						} else {

							$this->_db->insert('admins_session', array(
								'user_id' => $this->data()['id'],
								'hash' => $hash
								));
						}

						Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
					}

					return true;
				}

			} 

		}

		return false;
		
	}

	/*public function facebooklogin( $facebook_id , $remember = false){
		
			$user = $this->find( true, $OAuth = array('field' => 'facebook_id', 'value' => $facebook_id));

			if ($user){
					
					Session::put($this->_sessionName, $this->data()['id']); 

					if ($remember) {
						$hash = Hash::unique();
						$hashCheck = $this->_db->where('user_id', $this->_data['id'])->getOne('admins_session', 'hash');

						if ( count($hashCheck) > 0 ) {

							$this->_db->where('user_id', $this->data()['id'])->update('admins_session', array(
								'hash' => $hash
								));							
							
						} else {

							$this->_db->insert('admins_session', array(
								'user_id' => $this->data()['id'],
								'hash' => $hash
								));
						}

						Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
					}

					return true;

			} 

		return false;
		
	}*/


	public function exists(){
		return (!empty($this->_data))? true : false ;
	}

	public function data(){

		return $this->_data;
	}

	public function isLoggedIn(){
		return $this->_isLoggedIn;
	}

	public function logout(){

		Session::delete($this->_sessionName);

		if($this->_db->where('user_id', $this->data()['id'])->delete('admins_session')){
			Cookie::delete($this->_cookieName);
		}
	}

}